local url    = require "socket.url";
local jid    = require "util.jid";
local stanza = require "util.stanza";
local b64    = require "util.encodings".base64;
local sp     = require "util.encodings".stringprep;

local JSON = { };

-- Use lua-cjson if it is available
local ok, error = pcall(function() JSON = require "cjson.safe" end);

-- Fall back to util.json
if not ok or error then JSON = require "util.json" end

local um = require "core.usermanager";
local rm = require "core.rostermanager";
local mm = require "core.modulemanager";
local hm = require "core.hostmanager";
local sm = require "core.storagemanager";

local hostname  = module:get_host();
local secure    = module:get_option_boolean("admin_rest_secure", false);
local base_path = module:get_option_string("admin_rest_base", "/hello");
local whitelist = module:get_option_array("admin_rest_whitelist", nil);

local function to_set(list)
  local l = #list;
  if l == 0 then return nil end
  local set = { };
  for i=1, l do set[list[i]] = true end
  return set;
end

-- Convert whitelist into a whiteset for efficient lookup
if whitelist then whitelist = to_set(whitelist) end

local function split_path(path)
  local result = {};
  local pattern = "(.-)/";
  local last_end = 1;
  local s, e, cap = path:find(pattern, 1);

  while s do
    if s ~= 1 or cap ~= "" then
      table.insert(result, cap);
    end
    last_end = e + 1;
    s, e, cap = path:find(pattern, last_end);
  end

  if last_end <= #path then
    cap = path:sub(last_end);
    table.insert(result, cap);
  end

  return result;
end

local function parse_path(path)
  local split = split_path(url.unescape(path));
  return {
    route     = split[2];
    resource  = split[3];
    attribute = split[4];
  };
end

-- Parse request Authentication headers. Return username, password
local function parse_auth(auth)
  return b64.decode(auth:match("[^ ]*$") or ""):match("([^:]*):(.*)");
end

-- Make a *one-way* subscription. User will see when contact is online,
-- contact will not see when user is online.
local function subscribe(user_jid, contact_jid)
  local user_node, user_host = jid.split(user_jid);
  local contact_username, contact_host = jid.split(contact_jid);
  -- Update user's roster to say subscription request is pending...
  rm.set_contact_pending_out(user_node, user_host, contact_jid);
  -- Update contact's roster to say subscription request is pending...
  rm.set_contact_pending_in(contact_username, contact_host, user_jid);
  -- Update contact's roster to say subscription request approved...
  rm.subscribed(contact_username, contact_host, user_jid);
  -- Update user's roster to say subscription request approved...
  rm.process_inbound_subscription_approval(user_node, user_host, contact_jid);
end

-- Unsubscribes user from contact (not contact from user, if subscribed).
function unsubscribe(user_jid, contact_jid)
  local user_node, user_host = jid.split(user_jid);
  local contact_username, contact_host = jid.split(contact_jid);
  -- Update user's roster to say subscription is cancelled...
  rm.unsubscribe(user_node, user_host, contact_jid);
  -- Update contact's roster to say subscription is cancelled...
  rm.unsubscribed(contact_username, contact_host, user_jid);
end

local function Response(status_code, message, array)
  local response = { };

  local ok, error = pcall(function()
    message = JSON.encode({ result = message });
  end);

  if not ok or error then
    response.status_code = 500
    response.body = "Failed to encode JSON response";
  else
    response.status_code = status_code;
    response.body = message;
  end

  return response;
end

-- Build static responses
local RESPONSES = {
  missing_auth    = Response(400, "Missing authorization header");
  invalid_auth    = Response(400, "Invalid authentication details");
  auth_failure    = Response(401, "Authentication failure");
  unauthorized    = Response(401, "User must be an administrator");
  decode_failure  = Response(400, "Request body is not valid JSON");
  invalid_path    = Response(404, "Invalid request path");
  invalid_method  = Response(405, "Invalid request method");
  invalid_body    = Response(400, "Body does not exist or is malformed");
  invalid_host    = Response(404, "Host does not exist or is malformed");
  invalid_user    = Response(404, "User does not exist or is malformed");
  invalid_contact = Response(404, "Contact does not exist or is malformed");
  drop_message    = Response(501, "Message dropped per configuration");
  internal_error  = Response(500, "Internal server error");
  pong            = Response(200, "PONG");
};

local function respond(event, res, headers)
	local response = event.response;

  if headers then
    for header, data in pairs(headers) do
      response.headers[header] = data;
    end
  end

  response.headers.content_type = "application/json";
  response.status_code = res.status_code;
  response:send(res.body);
end

local function get_host(hostname)
  return hosts[hostname];
end

local function get_sessions(hostname)
  local host = get_host(hostname);
  return host and host.sessions;
end

local function get_session(hostname, username)
  local sessions = get_sessions(hostname);
  return sessions and sessions[username];
end

local function get_connected_users(hostname)
  local sessions = get_sessions(hostname);
  local users = { };

  for username, user in pairs(sessions or {}) do
    for resource, _ in pairs(user.sessions or {}) do
      table.insert(users, {
        username = username,
        resource = resource
      });
    end
  end

  return users;
end

local function get_recipient(hostname, username)
  local session = get_session(hostname, username)
  local offline = not session and um.user_exists(username, hostname);
  return session, offline;
end

local function normalize_user(user)
  local cleaned = { };
  cleaned.connected = user.connected or false;
  cleaned.sessions  = { };
  cleaned.roster    = { };

  for resource, session in pairs(user.sessions or {}) do
    local c_session = {
      resource = resource;
      id       = session.conn.id;
      ip       = session.conn._ip;
      port     = session.conn._port;
      secure   = session.secure;
    }
    table.insert(cleaned.sessions, c_session);
  end

  if user.roster and #user.roster > 0 then
    cleaned.roster = user.roster;
  end

  return cleaned;
end

local function get_user_connected(event, path, body)
  local username = sp.nodeprep(path.resource);

  if not username then
    return respond(event, RESPONSES.invalid_user);
  end

  local jid = jid.join(username, hostname);
  local connected = get_session(hostname, username);
  local response;

  if connected then
    response = Response(200, { connected = true });
  else
    response = Response(404, { connected = false });
  end

  respond(event, response);
end
-- end of initialization


---user module related changes
local function add_user(event, path, body)
  local username = sp.nodeprep(path.resource);
  local password = body["password"];
  local regip = body["regip"];

  if not username then
    return respond(event, RESPONSES.invalid_path);
  end

  if not password then
    return respond(event, RESPONSES.invalid_body);
  end

  local jid = jid.join(username, hostname);

  if um.user_exists(username, hostname) then
    return respond(event, Response(409, "User already exists: " .. jid));
  end

  if not um.create_user(username, password, hostname) then
    return respond(event, RESPONSES.internal_error);
  end

  local result = "User registered: " .. jid;

  respond(event, Response(201, result));

  module:fire_event("user-registered", {
    username = username,
    host = hostname,
    ip = regip,
    source   = "mod_admin_rest"
  })

  module:log("info", result);
end

local function remove_user(event, path, body)
 return "Hello world!"; 
  local username = sp.nodeprep(path.resource);

  if not username then
    return respond(event, RESPONSES.invalid_user);
  end

  local jid = jid.join(username, hostname);

  if not um.user_exists(username, hostname) then
    return respond(event, Response(404, "User does not exist: " .. jid));
  end

  if not um.delete_user(username, hostname) then
    return respond(event, RESPONSES.internal_error);
  end

  respond(event, Response(200, "User deleted: " .. jid));

  module:fire_event("user-deleted", {
    username = username;
    hostname = hostname;
    source = "mod_admin_rest";
  });

  module:log("info", "Deregistered user: " .. jid);
end
-- end of user module

-- white list methods


local function get_whitelist(event, path, body)
  local list = { };

  if whitelist then
    for ip, _ in pairs(whitelist) do
      table.insert(list, ip);
    end
  end

  respond(event, Response(200, { whitelist = list, count = #list }));
end

local function add_whitelisted(event, path, body)
  local ip = path.resource;
  if not whitelist then whitelist = { } end

  whitelist[ip] = true;

  local result = "IP added to whitelist: " .. ip;

  respond(event, Response(200, result));

  module:log("warn", result);
end

local function remove_whitelisted(event, path, body)
  local ip = path.resource;

  if not whitelist or not whitelist[ip] then
    return respond(event, Response(404, "IP is not whitelisted: " .. ip));
  end

  local new_list = { };
  for whitelisted, _ in pairs(whitelist) do
    if whitelisted ~= ip then
      new_list[whitelisted] = true;
    end
  end
  whitelist = new_list;

  local result = "IP removed from whtielist: " .. ip;

  respond(event, Response(200, result));

  module:log("warn", result);
end

-- end of white list methods

--Routes and suitable request methods
local ROUTES = {
  ping = {
    GET = ping;
  };

  user = {
    GET    = get_user;
    POST   = add_user;
    DELETE = remove_user;

  };

  user_connected = {
    GET = get_user_connected;
  };


 

  whitelist = {
    GET    = get_whitelist;
    PUT    = add_whitelisted;
    DELETE = remove_whitelisted;
  }
};


--Reserved top-level request routes
local RESERVED = to_set({ "admin" });

--Authenticate admin and route request.
local function handle_request(event)
  local request = event.request;

  -- Check whitelist for IP
  if whitelist and not whitelist[request.conn._ip] then
    return respond(event, { status_code = 401, message = nil });
  end

  -- ********** Authenticate ********** --

  -- Prevent insecure requests
  if secure and not request.secure then return end

  -- Request must have authorization header
  if not request.headers["authorization"] then
    return respond(event, RESPONSES.missing_auth);
  end

  local auth = request.headers.authorization;
  local username, password = parse_auth(auth);

  username = jid.prep(username);

  -- Validate authentication details
  if not username or not password then
    return respond(event, RESPONSES.invalid_auth);
  end

  local user_node, user_host = jid.split(username);

  -- Validate host
  if not hosts[user_host] then
    return respond(event, RESPONSES.invalid_host);
  end

  -- Authenticate user
  if not um.test_password(user_node, user_host, password) then
    return respond(event, RESPONSES.auth_failure);
  end

  -- ********** Route ********** --

  local path = parse_path(request.path);
  local route, hostname = path.route, hostname;

  -- Restrict to admin
  if not um.is_admin(username, hostname) then
    return respond(event, RESPONSES.unauthorized);
  end

  local handlers = ROUTES[route];

  -- Confirm that route exists
  if not route or not handlers then
    return respond(event, RESPONSES.invalid_path);
  end

  -- Confirm that the host exists
  if not RESERVED[route] then
    if not hostname or not hosts[hostname] then
      return respond(event, RESPONSES.invalid_host);
    end
  end

  local handler = handlers[request.method];

  -- Confirm that handler exists for method
  if not handler then
    return respond(event, RESPONSES.invalid_method);
  end

  local body = { };

  -- Parse JSON request body
  if request.body and #request.body > 0 then
    if not pcall(function() body = JSON.decode(request.body) end) then
      return respond(event, RESPONSES.decode_failure);
    end
    if not body["regip"] then
      body["regip"] = request.conn._ip;
    end
  end

  return handler(event, path, body);
end



-- Ensure that mod_http is loaded:
module:depends("http");
 
-- Now publish our HTTP 'app':
module:provides("http", {
   
    route = {
        ["GET /*"]    = handle_request;
    };
});
