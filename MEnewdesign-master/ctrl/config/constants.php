<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');


/* ERROR MESSAGES */
/* SolrHandler facet error messages */
define ('ERROR_FACET_TYPE', 'Invalid FacetType');
define ('ERROR_FACET_VALUE', 'Invalid FacetValues');

/* End SolrHandler event url error messages */

/* SolrHandler event url error messages */
define ('ERROR_EVENT_URL', 'Invalid Event Url');

/* End SolrHandler event url error messages */

/* Paremetar error message */
define ('ERROR_WRONG_PARAMETER', 'Invalid Parameters');
define('ERROR_INVALID_ACCESS', 'Invalid Access Token');
define('ERROR_INVALID_DATA', 'Invalid Data');
define('ERROR_INVALID_EVENTID', 'Invalid event id');
define('ERROR_INVALID_INPUT',  'Invalid Input');
define('ERROR_NO_DATA', 'No data available');
define('ERROR_INTERNAL_DB_ERROR', 'Internal Server Error');
define('ERROR_NOT_ACCEPTABLE', 'Not Acceptable');
define('ERROR_INVALID_CATEGORY_ID', 'Invalid Category id');
define('ERROR_INVALID_MAJOR', 'Invalid Major');
define('ERROR_NO_COUNTRY','The Country Id field is required');
define('ERROR_NO_COUNTRY_ID_AND_NAME','Eiether of the Country Id or Country Name field is required');
define('ERROR_NO_CITIES', 'No cities');
define('ERROR_INVALID_COUNTRY', 'Invalid Country');
define('ERROR_NO_STATE', 'Invalid State');
define('ERROR_NO_CATEGORIES', 'No categories');
define('ERROR_NO_SUBCATEGORIES', 'No subcategories');
define('ERROR_NO_EVENTS_SUBCATEGORIES', 'No events in subcategories');
define('ERROR_NO_TAGS', 'No tags under given keyword');
define('ERROR_NO_TIMEZONE', 'No timezones');
define('ERROR_INVALID_KEYWORD', 'Invalid key word');

define('ERROR_FILE_UPLOAD', 'Something went wrong while uploading the file');
define('ERROR_ADD_TICKET', 'Something went wrong while creating Ticket');
define('ERROR_ADD_TICKET_ARRAY', 'Label and Value should be array');
define('ERROR_NO_STATES', 'No states');
define('ERROR_NO_TICKET','No tickets');
define('ERROR_NO_CUSTOME_DATE','The dateValue field is required when day is 7');
define('ERROR_FILE_SIZE','Please choose an image lesser than 500kb');
define('ERROR_FILE_DIMENTIONS','Please choose an image with given dimentions');
define('ERROR_NO_MOR_EVENTS' , 'Hey! There are no more Event');
define('ERROR_NO_CURRENCIES',"No Currencies");
define('ERROR_NO_SESSION',"No active session");
// invalid date formate mm/dd/yy

define('ERROR_DATE_VALUE_FORMAT', 'Invalid date format');
define('ERROR_START_DATE_GREATER', 'StartDate should be less than EndDate');
define('ERROR_START_DATE_GREATER_THAN_NOW', 'StartDate should be grather than Now date & time');
define('ERROR_DATE_GREATER_THAN_NOW', 'Date value should be greater than current date');
define('ERROR_TAGS_VALUE', 'Invalid Tags data');

define('ERROR_SOMETHING_WENT_WRONG','Something went wrong, please try again');

define('ERROR_EXISTED_EVENT_URL','Url already existed');

define('ERROR_TICKET_TAX_ADDED','Ticket Tax Added sucessfull');
define('ERROR_NO_EVENT','Invalid Event');

define('ERROR_NO_TAX','No tax');
define('ERROR_FOLDER_ID','No Folder Id');
/* END OF ERROR MESSAGES */

/* SUCCESS MESSAGE */
define('SUCCESS_FILE_UPLOAD', 'Successfully uploaded the file');

define('SUCCESS_EVENT_URL_AVAILABLE','Url is available');
define('SUCCESS_TICKET_ADDED','Ticket is added sucessfully');

define('SUCCESS_SIGNUP','Successfully Registered');

/* END OF SUCCESS MESSAGE*/


/* Memcache Variables declaration */

define ('MEMCACHE_MAJOR_CATEGORY', 'majorCategoryList');
define ('MEMCACHE_MAJOR_CATEGORY_TTL', 5*60);

define ('MEMCACHE_ALL_CATEGORY', 'allCategoryList');
define ('MEMCACHE_ALL_CATEGORY_TTL', 5*60);

define ('MEMCACHE_MAJOR_COUNTRY', 'majorCountryList');
define ('MEMCACHE_MAJOR_COUNTRY_TTL', 5*60);

define ('MEMCACHE_ALL_COUNTRY', 'allCountryList');
define ('MEMCACHE_ALL_COUNTRY_TTL', 5*60);

define ('MEMCACHE_BLOG_DATA', 'memCacheBlogData');
define ('MEMCACHE_BLOG_DATA_TTL', 5*60);

define ('MEMCACHE_MAJOR_CITY', 'majorCityList');
define ('MEMCACHE_MAJOR_CITY_TTL', 5*60);

define ('MEMCACHE_TIME_ZONE', 'timeZoneList');
define ('MEMCACHE_TIME_ZONE_TTL', 5*60);

define('MEMCACHE_ALL_CURRENCY', 'allCurrencyList');
define ('MEMCACHE_ALL_CURRENCY_TTL', 5*60);
/* End of Memcache Variables declaration */

/* cookie expiration time*/
define('COOKIE_EXPIRATION_TIME', 30*24*60*60);

define('LABEL_ALL_CITIES', "All Cities");
define('LABEL_ALL_CATEGORIES', "All Categories");
define('LABEL_ALL_TIME', "All Time");

define('GENERAL_INQUIRY_MOBILE','+91-9396555888');
define('GENERAL_INQUIRY_EMAIL','support@meraevents.com');

/* End of file constants.php */
/* Location: ./application/config/constants.php */

//custom filter (CF) constants
define('CF_TODAY','today');
define('CF_TOMORROW','tomorrow');
define('CF_THIS_WEEK','this week');
define('CF_THIS_WEEKEND','this weekend');
define('CF_THIS_MONTH','this month');
define('CF_ALL_TIME','all time');

define('PAGETITLE_CREATE_EVENT','Create Event');

// Solr event creating,updating,deleting

define('SOLR_EVENT_CREATE','Event created');
define('SOLR_EVENT_UPDATE','Event updated');
define('SOLR_EVENT_DELETE','Event deleted');

// Solr Tag creating,updating,deleting

define('SOLR_TAG_CREATE','Tag created');
define('SOLR_TAG_UPDATE','Tag updated');
define('SOLR_TAG_DELETE','Tag deleted');



//File table filetype field enum values
//('userprofile','banner','thumbnail','countrythumb','categorythumb')
define('FILE_TYPE_THUMBNAIL','thumbnail');
define('FILE_TYPE_BANNER','banner');
define('FILE_TYPE_COUNTRYTHUMB','countrythumb');
define('FILE_TYPE_CATEGORYTHUMB','categorythumb');
define('FILE_TYPE_USERPROFILE','userprofile');

//image extensions
define('IMAGE_EXTENTIONS','jpg|jpeg|gif|png');


//image Types
define('IMAGE_EVENT_LOGO','eventlogo');
define('IMAGE_TOP_BANNER','topbanner');
define('IMAGE_BOTTOM_BANNER','bottombanner');


//Tickets degault values
define('DEFAULT_TICKET_QUANTITY',9999);
define('MIN_TICKET_QUANTITY',1);
define('MAX_TICKET_QUANTITY',9);

//APP IDS
define('FB_APP_ID',442176082607903);
define('FB_APP_SECRET','872aeae2d17d4059d00fa5fea0d00fa6');

//user related messages
define('ERROR_INVALID_USER','Invalid email/password');
define('ACCOUNT_ALREADY_ACTIVATED','Your account  is already activated . click forgot password if you have forgotten your password');
define('ERROR_CONTACT_ME','Please Contact MeraEvents');
define('ERROR_NO_EMAIL','Email does not exist');
define('EMAIL_SUCESS','Resend activation link has been sent to your email id');
define('ERROR_LINK_EXPIRED','Your verification link has expired');
define('USER_STATUS_UPDATE','User status updated sucessfully');
define('ACCOUNT_ACTIVATED','Your account has been activated. Please login with your credentials');
define('ERROR_NOT_REGESTRED','Please enter registered Email Id');
define('Email_SENT','Email Sent Successfully');
define('ERROR_NOT_ACTIVATED_EMAIL','User email is not activated');
define('ERROR_PASSWORD_NOT_MATCH','Confirm password must be equal to the password');
define('SUCCESS_UPDATED_PASSWORD','Successfully updated the password');
define('SUCCESS_MAIL_SENT','Mail sent successfully. Please check your inbox');
define('ERROR_NO_USER','User is not registered'); 
define('ERROR_INVALID_VERIFICATION_STRING', 'Invalid verification string');
define('SUCCESS_ADDED_USER','User created');

// token related messages
define('USER_TOKEN_UPDATE','Token updated sucessfully');

//Status Code
define('STATUS_OK',200);
define('STATUS_BAD_REQUEST',400);
define('STATUS_CONFLICT',409);
define('STATUS_SERVER_ERROR',500);
define('STATUS_ERROR',504);
define('STATUS_UPDATED',201);
define('STATUS_INVALID',462);
define('STATUS_PRECONDITION_FAILED',412);



