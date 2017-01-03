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
local base_path = module:get_option_string("admin_rest_base", "/rest_api");
local whitelist = module:get_option_array("rest_api_whitelist", nil);

local server_time = module:depends("uptime");
local cmg = require "core.configmanager";
 
 --set the main prosody server config file path & conf.d folder path
local main_config_file_name="/etc/prosody/prosody.cfg.lua";
local conf_d_path ="/etc/prosody/conf.d/"; 
 

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

  
--my method

local function server_details(event, path, body)
--to get the pid of the server
	local pidfile = config.get("*", "pidfile");
 	local pfile = io.open(pidfile, "r+");
 	local pid = tonumber(pfile:read("*a"));
	pfile:close();
--to get the server uptime 
 	local mytime=server_time.uptime_text();	
 	local server_details ={}
 	 table.insert(server_details,{
 	 	PID = pid,
 	 	uptime = mytime
 	 });

-- Muc status
	local response= cmg.getconfig();
	local grouplist = {};
	for k,v in pairs(response) do
		local list={};
		if k ~= "*" and v["component_module"] == "muc" then
			list[k]=v;
	  		table.insert(grouplist, list);
	  end
	end
	
	local muc_status = false;
	module:log("info", "count", muc_status);
	local count = #grouplist;
	if count > 0 then
		muc_stauts= true
	
	end
--users list
	local data_path = CFG_DATADIR or "data";
	if not pcall(require, "luarocks.loader") then
		pcall(require, "luarocks.require");
	end
	local lfs = require "lfs";
	function decode(s)
		return s:gsub("%%([a-fA-F0-9][a-fA-F0-9])", function (c)
			return string.char(tonumber("0x"..c));
			end);
	end
    local users = { };
  	for host in lfs.dir(data_path) do
		local accounts = data_path.."/"..host.."/accounts";
		if lfs.attributes(accounts, "mode") == "directory" then
			for user in lfs.dir(accounts) do
				if user:sub(1,1) ~= "." then
				  local jid = jid.join(decode(user:gsub("%.dat$", "")), hostname);
				  local connected = get_session(hostname, decode(user:gsub("%.dat$", "")));
					  if connected then
   					   connected = true;
 					  else
   					   connected = false;
  					  end
					 	 table.insert(users, {
          username = decode(user:gsub("%.dat$", "")).."@"..decode(host),
          status =  connected
        
        });
					end
				end
			end
		end
	respond(event, Response(200, { server_details = server_details, muc_status =muc_stauts ,connected_users_count=#users,response=response  }));
 
end

local function activatehost(event, path, body)

	if body ~= nil  then
	 	local host = body["host"];
	 	
	 	local host_status=check_host(host);
	 	
	 	if host_status == true then 
	 		respond(event, Response(409, { message ="host already defined"   }));
 		end
	 	
	 	local host_status=	hm.activate(host);
	 
	 	if host_status == true then
	 		local key= "defined";
	 	 	local value = true;
	 		cmg._M.set(host, key, value)
	 		
	 		local config_file_name= conf_d_path .. host ..".cfg.lua";
	 		create_vhost(config_file_name, host);
	    	respond(event, Response(200, { message ="Virtual host ".. host .." added successfully"   }));
	   	else
	   		respond(event, Response(400, { message ="Failed to add virtual host "..host   }));	 	
	    end
	else
	   respond(event, Response(400, { message ="Not a valid virtual host"   }));
	end     	
    
end

local function deactivatehost(event, path, body)
	if body ~= nil  then
	 	local host = body["host"];
	 	local host_status=	hm.deactivate(host);
	 	if host_status == true then
	 		local host_file_pat = conf_d_path .. host ..".cfg.lua";
 			remove_file(host_file_pat);
 			local lstatus=cmg.load(main_config_file_name);
	    	respond(event, Response(200, { message ="Virtual host ".. host .." removed successfully."   }));
	   else
	   		respond(event, Response(400, { message ="Failed to remove virtual host "..host   }));	 	
	    end
	else
	   respond(event, Response(400, { message ="Not a valid virtual host"   }));
	end 

  
end

local function add_muc(event, path, body)

		if body ~= nil  then
			local host =  body["host"];
	 	 
	 		local status =true;
	 		if host == nil  then
	 			respond(event, Response(400, { message ="Not a valid virtual host "..host   }));
	 			status =false;
	 		end
	 	  
	 	 	muc_host= "muc." .. host;
 			local mhost_status=check_host(muc_host);
	 	
	 		if mhost_status == true then 
	 			respond(event, Response(409, { message ="MUC already enabled for virtual host "..host   }));
	 			status =false;
	 		end
	 		if status == true then
	 			local append_text= 'Component "'..muc_host..'" "muc" \n';
 
				--appending the text to config file
			 
			 	local config_file_name= conf_d_path .. host ..".cfg.lua";
			  	change_config_text(config_file_name, append_text)
			 

				-- load & activate new muc server
				local lstatus=cmg.load(main_config_file_name);
			 	local new_server_status = hm.activate(muc_host);
			 	mm.load_modules_for_host(host);
	 	 	 
	 			respond(event, Response(200, { message ="MUC enabled successfully for host "..host   }));
			end
 		else
	  		respond(event, Response(400, { message ="Not a valid virtual host"   }));
		end  

end

local function remove_muc(event, path, body)

	if body ~= nil  then
	 	local host = body["host"];
	  
	 	muc_host="muc." .. host;
	 	local main_host_status=check_host(host);
	 	
	 	if main_host_status == false then 
	 		respond(event, Response(409, { message ="Virtual host ".. host .." not found"   }));
	 	else
	 		
	 		local host_status=	hm.deactivate(muc_host);
	 		if host_status == true then
	 			local remove_text= 'Component "'..muc_host..'" "muc"';
	 			local host_file_path = conf_d_path .. host ..".cfg.lua";
	 			remove_file_text(remove_text, host_file_path)
	 		 
	 			
			    local lstatus=cmg.load(main_config_file_name);
	 			 
	 		
	    		respond(event, Response(200, { message ="MUC removed successfully for host "..host   }));
	    	else 
	    		respond(event, Response(400, { message ="Failed to remove MUC for host "..host   }));
	    	end
	 	end
	else	 	
		respond(event, Response(400, { message ="Not a valid virtual host"   })); 	
	end     	
  
end

--To chekc the specified host exist are not
function check_host(host)
	local config= cmg.getconfig();
	local host_status =false;
	for k,v in pairs(config) do
		local list={};
		if k ~= "*" and k == host  then
		 host_status = true;
	  end
	end
	
	return host_status;
end

--to create the vhost file
function create_vhost(file_name,host)
	local f=io.open(file_name,"r")
	if f~=nil then
        io.close(f);
        return true
    else
		f=io.open(file_name,"w");
		local append_text ='VirtualHost "'.. host .. '" \n enabled = true \n';
          
		f:write(append_text);
    	f:close();
    	os.execute("chmod 777 " .. file_name);

        return false
    end
end

-- To remove the text from a specifed file
function remove_file_text(remove_text, host_file)
	local f = io.open(host_file, "r")
	local content = f:read("*all")
	f:close()
	
	 --
	 -- Edit the string
	 --
	 content = string.gsub(content, remove_text , "")
	
	 --
	 -- Write it out
	 --
	 local f = io.open(host_file, "w")
	 f:write(content)
	 f:close()
end

--To remove the specified file
function remove_file(file_name)

	os.remove(file_name);
	return true;
end

-- To append the componet text
function change_config_text(file_name, append_text)
	local f=io.open(file_name,"a")
	f:write(append_text);
    f:close();
    os.execute("chmod 777 " .. file_name);
end



--end of my method



 

local function isempty(s)
  return s == nil or s == ''
end


--Routes and suitable request methods
local ROUTES = {
 
  addhost ={
	POST = activatehost
  };
  removehost ={
  	POST = deactivatehost	
  };
  addmuc ={
  	POST = add_muc
  };
  
  removemuc ={
  	POST = remove_muc	
  };
   systemstatus = {
    GET = server_details;
  };
  
 

};

--Reserved top-level request routes
local RESERVED = to_set({ "admin" });

--Entry point for incoming requests.
--Authenticate admin and route request.
local function handle_request(event)
  local request = event.request;
  local conname=module:get_host_type();
  local multicast_prefix = module:get_option_string("admin_rest_multicast_prefix", nil);
 

  module:log("info","11111");
  if isempty(whitelist) then
 	return respond(event, { status_code = 401, message = 'Not an authorised request' });
  end
   module:log("info","222",request.conn.ip());
  if not whitelist[request.conn.ip()] then
  	return respond(event,Response(401, "Not an authorised request" ));
  end

  -- ********** Authenticate ********** --
 
  -- Prevent insecure requests
  if secure and not request.secure then return end

 

  -- ********** Route ********** --

  local path = parse_path(request.path);
  local route, hostname = path.route, hostname;



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
    if body ~= nil and not body["regip"] then
      body["regip"] = request.conn.ip();
    end
  end

  return handler(event, path, body);
end

module:depends("http");

module:provides("http", {
  name = base_path:gsub("^/", "");
  route = {
    ["GET /*"]    = handle_request;
    ["POST /*"]   = handle_request;
    ["PUT /*"]    = handle_request;
    ["DELETE /*"] = handle_request;
    ["PATCH /*"]  = handle_request;
  };
})
