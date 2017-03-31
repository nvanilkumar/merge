<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

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

define('FOPEN_READ', 'rb');
define('FOPEN_READ_WRITE', 'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE', 'ab');
define('FOPEN_READ_WRITE_CREATE', 'a+b');
define('FOPEN_WRITE_CREATE_STRICT', 'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');


/* ERROR MESSAGES */
/* SolrHandler facet error messages */
define('ERROR_FACET_TYPE', 'Invalid FacetType');
define('ERROR_FACET_VALUE', 'Invalid FacetValues');

/* End SolrHandler event url error messages */

/* SolrHandler event url error messages */
define('ERROR_EVENT_URL', 'Invalid Event Url');

/* End SolrHandler event url error messages */

/* Paremetar error message */
define('ERROR_WRONG_PARAMETER', 'Invalid Parameters');
define('ERROR_INVALID_ACCESS', 'Invalid Access Token');
define('ERROR_INVALID_DATA', 'Invalid Data');
define('ERROR_INVALID_EVENTID', 'Invalid event id');
define('ERROR_INVALID_INPUT', 'Invalid Input');
define('ERROR_NO_DATA', 'Sorry, No records found');
define('ERROR_INTERNAL_DB_ERROR', 'Internal Server Error');
define('ERROR_NOT_ACCEPTABLE', 'Not Acceptable');
define('ERROR_INVALID_CATEGORY_ID', 'Invalid Category id');
define('ERROR_INVALID_MAJOR', 'Invalid Major');
define('ERROR_NO_COUNTRY', 'The Country Id field is required');
define('ERROR_NO_COUNTRY_ID_AND_NAME', 'Either country ID or Name is required');
define('ERROR_NO_CITIES', 'No cities');
define('ERROR_INVALID_COUNTRY', 'Invalid Country');
define('SUCCESS_VALID_COUNTRY','Valid Country');
define('ERROR_NO_STATE', 'Invalid State');
define('ERROR_NO_CATEGORIES', 'No categories');
define('ERROR_NO_SUBCATEGORIES', 'No subcategories');
define('ERROR_NO_EVENTS_SUBCATEGORIES', 'No events in subcategories');
define('ERROR_NO_TAGS', 'No tags under given keyword');
define('ERROR_NO_TIMEZONE', 'No timezones');
define('ERROR_INVALID_KEYWORD', 'Invalid key word');
define('ERROR_INVALID_DISCOUNT_CODE', 'Invalid discount code');
define('ERROR_FILE_UPLOAD', 'Oops !!! Something is wrong');
define('ERROR_ADD_TICKET', 'Oops !!! Something is wrong');
define('ERROR_ADD_TICKET_ARRAY', 'Label and Value should be array');
define('ERROR_NO_STATES', 'No states');
define('ERROR_NO_TICKET', 'No tickets');
define('ERROR_NO_CUSTOME_DATE', 'The dateValue field is required when day is 7');
define('ERROR_FILE_SIZE', 'Image size should be less than 500 KB');
define('ERROR_FILE_DIMENTIONS', 'Follow image dimensions');
define('ERROR_NO_MOR_EVENTS', 'Please move on ');
define('ERROR_NO_CURRENCIES', "No Currencies");
define('ERROR_NO_SESSION', "No active session");
define('ERROR_GUEST_FILE_UPLOAD', 'Upload CSV Format Only.');
define('ERROR_INVALID_MTS','Invalid date time format');
// invalid date formate mm/dd/yy

define('ERROR_DATE_VALUE_FORMAT', 'Invalid date format');
define('ERROR_TIME_VALUE_FORMAT', 'Invalid time format');
define('ERROR_START_DATE_GREATER', 'StartDate should be less than EndDate');
define('ERROR_EVENT_START_DATE_GREATER_THAN_NOW', 'Event start date & time should be greater than present date & time');
define('ERROR_EVENT_START_DATE_GREATER', 'Event StartDate should be less than EndDate');
define('ERROR_TICKET_START_DATE_GREATER', 'Ticket StartDate should be less than EndDate');
define('ERROR_START_DATE_GREATER_THAN_NOW', 'Start date & time should be greater than present date & time');
define('ERROR_END_DATE_GREATER_THAN_NOW', 'EndDate & time should be greater than present date & time');
define('ERROR_DATE_GREATER_THAN_NOW', 'Date value should be greater than current date');
define('ERROR_START_TIME_GREATER_THAN_NOW', 'Start time should be greater than present time');
define('ERROR_TAGS_VALUE', 'Invalid tags');
define('ERROR_INVALID_TAG_VALUE', 'Enter valid tag name with words and _ , @ , & , - , . , \\ , /');

define('ERROR_EMPTY_TICKET_NAME', 'Ticket Name cannot be empty.');
define('ERROR_TICKET_NAME_MIN_LENGTH', 'Ticket Name must be atleast 2 characters.');
define('ERROR_TICKET_NAME_MAX_LENGTH', 'Ticket Name must be atmost 75 characters.');
define('ERROR_TICKET_NAME_PATTERN', 'Ticket Name can contain letters,numbers, _,-,*,@,+,(,),&,%,$,# and comma characters.');
define('ERROR_TICKET_ORDER_REQUIRED','Ticket order is required');
define('ERROR_TICKET_TYPE_REQUIRED','Ticket type is required');
define('ERROR_TICKET_START_DATE_REQUIRED','Ticket start date is required');
define('ERROR_TICKET_START_TIME_REQUIRED','Ticket start time is required');
define('ERROR_TICKET_END_DATE_REQUIRED','Ticket end date is required');
define('ERROR_TICKET_END_TIME_REQUIRED','Ticket end time is required');
define('ERROR_TICKET_TYPE_INVALID','Ticket type should be between 1 and 4(1 for free,2 for paid,3 for donation,4 for add-on items)');
define('ERROR_TICKET_ORDER_INVALID','Ticket order should be numeric value greater than zero.');
define('ERROR_TICKET_DESCRIPTION_MAX_LENGTH','Ticket description can be atmost 300 characters');
define('ERROR_TICKET_PRICE_EMPTY','Ticket price cannot be empty.');
define('ERROR_TICKET_PRICE_NON_NUMERIC','Ticket price should be numeric value greater than zero.');
define('ERROR_TICKET_QUANTITY_NON_NUMERIC','Ticket Quantity should be numeric value greater than zero');
define('ERROR_TICKET_MIN_QTY_NON_NUMERIC','Ticket Min Quantity should be numeric value greater than zero');
define('ERROR_TICKET_MIN_QTY_MORE_THAN_QTY','Ticket Min Quantity should not be more than sale quantity');
define('ERROR_TICKET_MAX_QTY_NON_NUMERIC','Ticket Max Quantity should be numeric value greater than zero');
define('ERROR_TICKET_MAX_QTY_MORE_THAN_QTY','Ticket Max Quantity should not be more than sale quantity');
define('ERROR_TICKET_MAX_QTY_LESS_THAN_MIN_QTY','Ticket Min Quantity should not be more than max quantity');
define('ERROR_SOMETHING_WENT_WRONG', 'Something went wrong, please try again');
define('ERROR_DUPLICATE_GLOBAL_PROMOTER', 'Duplicate promoter code.');
define('ERROR_EXISTED_EVENT_URL', 'Url already exists');

define('ERROR_TICKET_TAX_ADDED', 'Ticket tax added ');
define('ERROR_NO_EVENT', 'Invalid Event');
define('SUCCESS_EVENT_EXISTED', 'Valid Event');

define('ERROR_NO_TAX', 'No tax');
define('ERROR_NO_TAX_MAPPING', 'No tax mapping available');
define('ERROR_FOLDER_ID', 'No Folder Id');

define('ERROR_TRANSACTIONS_AVAILABLE', "This event have transactions, You can't change Event Type");
define('ERROR_TAX_TRANSACTIONS_AVAILABLE', "This event have transactions, You can't change Event Tax");
define('ERROR_DELETE_TRANSACTIONS_AVAILABLE', "This event have transactions, You can't delete tickets");
define('ERROR_TICKETNAME_TRANSACTIONS_AVAILABLE', "Your ticket have transactions, You can't change ticket name");
define('ERROR_TICKETTYPE_TRANSACTIONS_AVAILABLE', "Your ticket have transactions, You can't change ticket type");
define('ERROR_TICKETPRICE_TRANSACTIONS_AVAILABLE', "Your ticket have transactions, You can't change ticket price");
define('ERROR_TICKETCURRENCY_TRANSACTIONS_AVAILABLE', "Your ticket have transactions, You can't change ticket currency");
define('ERROR_BLOCK_EVENT_TITLES', "Please do not use these  XXXX in your event title. Rephrase it");

define('ERROR_TICKET_LIMIT', "Your tickets limit is exceeded");
define('ERROR_TICKET_REMAINING_LIMIT', "You can book XXXX more tickets only");
define('ERROR_TICKET_LIMIT_UPDATE', "Minimum ticket Quantity for this promoter is XXXX ");

define('ERROR_VENUESEAT_NO_RECORD','Invalid input');
define('ERROR_VENUESEAT_NOT_AVAILABLE','Seat is in not available state');
define('ERROR_VENUESEAT_UPDATION','Seat updation failed');
define('ERROR_VENUESEAT_INVALID_TYPE','Invalid type.Mention add or remove');
/* END OF ERROR MESSAGES */

/* SUCCESS MESSAGE */
define('SUCCESS_FILE_UPLOAD', 'File upload successful');

define('SUCCESS_EVENT_URL_AVAILABLE', 'Url is available');
define('SUCCESS_TICKET_ADDED', 'Ticket is added sucessfully');
define('SUCCESS_CUSTOMFIELD', 'Custom field modified ');
define('SUCCESS_WEBHOOK_UPDATED', 'Web hook URL updated ');

define('SUCCESS_SIGNUP', 'Registration Successful');

/* END OF SUCCESS MESSAGE */


/* Memcache Variables declaration */

define('MEMCACHE_MAJOR_CATEGORY', 'majorCategoryList');
define('MEMCACHE_MAJOR_CATEGORY_TTL', 5 * 60);

define('MEMCACHE_ALL_CATEGORY', 'allCategoryList');
define('MEMCACHE_ALL_CATEGORY_TTL', 5 * 60);

define('MEMCACHE_MAJOR_COUNTRY', 'majorCountryList');
define('MEMCACHE_MAJOR_COUNTRY_TTL', 5 * 60);

define('MEMCACHE_ALL_COUNTRY', 'allCountryList');
define('MEMCACHE_ALL_COUNTRY_TTL', 5 * 60);

define('MEMCACHE_BLOG_DATA', 'memCacheBlogData');
define('MEMCACHE_BLOG_DATA_TTL', 5 * 60);

define('MEMCACHE_MAJOR_CITY', 'majorCityList');
define('MEMCACHE_MAJOR_CITY_TTL', 5 * 60);

define('MEMCACHE_TIME_ZONE', 'timeZoneList');
define('MEMCACHE_TIME_ZONE_TTL', 5 * 60);

define('MEMCACHE_TIME_ZONE_DETAIL', 'timeZoneDetail');
define('MEMCACHE_TIME_ZONE_DETAIL_TTL', 5 * 60);


define('MEMCACHE_ALL_CURRENCY', 'allCurrencyList');
define('MEMCACHE_ALL_CURRENCY_TTL', 5 * 60);
/* End of Memcache Variables declaration */

/* cookie expiration time */
define('COOKIE_EXPIRATION_TIME', 30 * 24 * 60 * 60);

define('LABEL_ALL_CITIES', "All Cities");
define('LABEL_ALL_CATEGORIES', "All Categories");
define('LABEL_ALL_TIME', "All Time");

define('GENERAL_INQUIRY_MOBILE', '+91-9396555888');
define('GENERAL_INQUIRY_EMAIL', 'support@meraevents.com');

/* End of file constants.php */
/* Location: ./application/config/constants.php */

//custom filter (CF) constants
define('CF_TODAY', 'today');
define('CF_TOMORROW', 'tomorrow');
define('CF_THIS_WEEK', 'this week');
define('CF_THIS_WEEKEND', 'this weekend');
define('CF_THIS_MONTH', 'this month');
define('CF_ALL_TIME', 'all time');

define('PAGETITLE_CREATE_EVENT', 'Create Event');

// Solr event creating,updating,deleting

define('SOLR_EVENT_CREATE', 'Event created');
define('SOLR_EVENT_UPDATE', 'Event updated');
define('SOLR_EVENT_DELETE', 'Event deleted');

// Solr Tag creating,updating,deleting

define('SOLR_TAG_CREATE', 'Tag created');
define('SOLR_TAG_UPDATE', 'Tag updated');
define('SOLR_TAG_DELETE', 'Tag deleted');



//File table filetype field enum values
//('userprofile','banner','thumbnail','countrythumb','categorythumb')
define('FILE_TYPE_THUMBNAIL', 'thumbnail');
define('FILE_TYPE_BANNER', 'banner');
define('FILE_TYPE_COUNTRYTHUMB', 'countrythumb');
define('FILE_TYPE_CATEGORYTHUMB', 'categorythumb');
define('FILE_TYPE_USERPROFILE', 'userprofile');
define('FILE_TYPE_CUSTOMFIELD', 'customfield');
define('FILE_TYPE_EVENT_GALLERY', 'eventgallery');
define('FILE_TYPE_EVENT_GALLERY_THUMBNAIL', 'eventgallerythumbnail');
//image extensions
define('IMAGE_EXTENTIONS', 'jpg|jpeg|gif|png');


//image Types
define('IMAGE_EVENT_LOGO', 'eventlogo');
define('IMAGE_TOP_BANNER', 'topbanner');
define('IMAGE_BOTTOM_BANNER', 'bottombanner');
//define('DEFAULT_PROFILE_IMAGE', 'imgo.jpg');
define('DEFAULT_PROFILE_IMAGE','profile-icon-50.png');
define('DEFAULT_EVENT_THUMB_IMAGE', 'event_thumb.jpg');
define('DEFAULT_EVENT_BANNER_IMAGE','event_banner.jpg');
//Tickets degault values
define('DEFAULT_TICKET_QUANTITY', 9999);
define('MIN_TICKET_QUANTITY', 1);
define('MAX_TICKET_QUANTITY', 9);
define('ERROR_NO_IMAGE', 'Sorry, No Images');
//APP IDS
define('FB_APP_ID', 442176082607903);
define('FB_APP_SECRET', '872aeae2d17d4059d00fa5fea0d00fa6');

//user related messages
define('ERROR_INVALID_USER', 'Oops! Check if email & password are correct');
define('ACCOUNT_ALREADY_ACTIVATED', 'Hey!! Your account is live. Login rightaway. Forgot password? ');
define('ACCOUNT_ALREADY_ACTIVATED_ACTIVATION_PAGE', 'Your account  is already activated . please login or  <a href="login#forgotDiv">click here</a> if you have forgotten your password');
define('ERROR_CONTACT_ME', 'Contact MeraEvents');
define('ERROR_NO_EMAIL', "We can't find this email");
define('EMAIL_SUCESS', 'Check your email. Activate the link.');
define('ERROR_LINK_EXPIRED', 'Sorry!!! Verification link expired');
define('USER_STATUS_UPDATE', 'Hey! Now you are our user');
define('USER_SESSION_UPDATE', 'User Session updated');
define('ACCOUNT_ACTIVATED', 'Your account is now active. Login ');
define('ERROR_NOT_REGESTRED', 'Enter registered email id');
define('Email_SENT', 'Email sent to you');
define('ERROR_NOT_ACTIVATED_EMAIL', 'Email is not activated');
define('ERROR_PASSWORD_NOT_MATCH', 'Enter password as above ');
define('SUCCESS_UPDATED_PASSWORD', 'Password updated ');
define('SUCCESS_MAIL_SENT', 'Please check your mail');
define('SUCCESS_EMAIL_ATTENDEES', 'Message has been sent to all/selected Attendees..');
define('SUCCESS_TEST_EMAIL_ATTENDEES', 'Test Mail has been sent..');
define('ERROR_NO_USER', 'Sorry. You are not registered ');
define('ERROR_INVALID_VERIFICATION_STRING', 'This link is not valid any more');
define('ERROR_INVALID_SESSION', 'Invalid admin session');
define('SUCCESS_UPDATED','Successfully updated');
define('SUCCESS_ADDED_USER','User created');
define('NOT_ACTIVATE_PASSWORD_MAIL','Email is not activated yet. Check your inbox to set password and activate this Email.');

//transaction related messages
define('ERROR_NO_RECORDS', 'No records found');
define('ERROR_INVALID_TRANSACTION_TYPE', 'Invalid transaction type');
define('ERROR_NO_ATTENDEES_DATA', 'No attendees, yet');
define('ERROR_NO_ATTENDEEDETAIL_DATA', 'No data');
// token related messages
define('USER_TOKEN_UPDATE', 'Token updated');

//Status Code
define('STATUS_OK', 200);
define('STATUS_BAD_REQUEST', 400);
define('STATUS_CONFLICT', 409);
define('STATUS_SERVER_ERROR', 500);
define('STATUS_UPDATED', 201);
define('STATUS_CREATED', 201);
define('STATUS_INVALID', 462);
define('STATUS_INVALID_SESSION', 406);
define('STATUS_PRECONDITION_FAILED', 412);
define('STATUS_NO_DATA', 504);
define('STATUS_INVALID_INPUTS', 460);

define('STATUS_INVALID_DISCOUNT_CODE', 463);
define('STATUS_INVALID_REFERRAL_CODE', 464);
define('STATUS_MAIL_NOT_SENT', 465);
define('STATUS_SMS_NOT_SENT', 466);
define('STATUS_DISCOUNT_USAGE_EXCEEDED', 467);
define('STATUS_MAIL_SENT', 200);
define('STATUS_INVALID_USER', 506);
define('STATUS_ACCOUNT_ALREADY_ACTIVATED', 461);


// seo related messages
define('SEO_DETAILS_UPDATE', 'Updated Successfully');

//discount realated messages
define('SUCCESS_DISCOUNT_ADDED', "Added discount code ");
define('SUCCESS_ADDED_TICKETDISCOUNT', "Discount enabled to eTicket");
define('ERROR_NO_DISCOUNT', "No discounts for this event");
define('SUCCESS_DELETED_DISCOUNT', 'Discount code deleted ');
define('SUCCESS_DISCOUNT_UPDATED', 'Discount info updated ');

// dashboard ticket options messages
define('TICKET_OPTIONS_UPDATE', 'Ticketing options updated ');
define('REPORTS_DISPLAY_LIMIT', 1000);
define('REPORTS_TRANSACTION_LIMIT', 2000);
define('VIRAL_TICKET_UPDATE', 'Commission set successfully');
define('VIRAL_TICKET_ERROR', 'Please enter Commission Values');
define('EVENTS_DISPLAY_LIMIT', 8);
//viral ticket related
define('ERROR_NO_VIRAL_TICKET', 'No viral tickets');
define('SUCCESS_VIRAL_TICKET_SAVED', 'Viral Ticketing options are saved');
define('ERROR_DUPLICATE_CUSTOM_FIELD', 'You are trying to add duplicate custom field');
define('ERROR_DUPLICATE_COLLABORATOR', 'You are trying to add duplicate collaborator for this event');
define('ERROR_INVALID_REFERRAL_CODE','Invalid referral code');
define('ERROR_INVALID_PROMOTER_CODE','Invalid promoter code');

//collaborator
define('SUCCESS_ADDED_COLLABORATOR', 'New collaborator is added');
define('SUCCESS_UPDATED_COLLABORATOR', 'Collaborator updated');
define('SUCCESS_ADDED_COLLABORATORACCESS', 'Collaborator access updated ');
define('SUCCESS_UPDATED_COLLABORATORACCESS', 'Successfully updated the collaborator access');
define('ERROR_NO_COLLABORATORS', 'No collaborators');
define('SUCCESS_ALERT_SET', 'Alerts set successfully');
//promoter related messages

define('SUCCESS_ADDED_PROMOTER', 'New promoter added');
define('SUCCESS_UPDATED_PROMOTER_STATUS', 'Promoter status updated ');
define('ERROR_NO_PROMOTER', 'No promoters');
define('ERROR_NO_PROMOTER_TRANSACTION', 'No transactions with this promoter code');
define('ERROR_DUPLICATE_PROMOTER', 'Duplicate promoter code, Already using for this event');
define('ERROR_DUPLICATE_PROMOTER_EMAIL', 'Duplicate promoter email for this event');


// Profile related messages
define('UPDATE_BANK_DETAILS', 'Bank details updated ');
define('UPDATE_ALERTS', 'Alert updated');
define('UPDATE_COMPANY_DETAILS', 'Company info updated');
define('UPDATE_PERSONAL_DETAILS', 'Profile updated');
define('SUCCESS_ATTENDEE_DETAIL_ADDED', 'Attendee data added');
define('ERROR_ADD_ATTENDEE_DETAIL', 'Error in adding attendee details');

define('SUCCESS_ATTENDEE_ADDED', 'Added attendee');
define('ERROR_ADD_ATTENDEE', 'Attendee could not be added');

define('SUCCESS_BOOKMARK_SAVED', 'Saved bookmark');
define('ERROR_SAVE_BOOKMARK', 'Error in saving bookmark');

define('SUCCESS_BOOKMARK_REMOVED', 'Bookmark removed');
define('ERROR_REMOVE_BOOKMARK', 'Error in removing bookmark');

define('ERROR_ALREADY_BOOKMARK', 'Event already bookmarked');
define('ERROR_NOT_BOOKMARK', 'You have not bookmarked this event');

define('ERROR_NO_USER_BOOKMARKS', 'No bookmarks found ');
define('ERROR_USERID', 'Sorry !!! You are not authorised ');
define('ERROR_NOT_AUTHORIZED', 'Not authorised');

//Terms and condition related
define('SUCCESS_TNC_UPDATED', 'T&Cs updated');

define('ERROR_EVENTID_ARRAY', 'Give event ID array as input');
define('ERROR_NO_ORDERLOG_FOUND', 'No orderlog data found');
define('ERROR_ORDERID_USED', 'Successfull Transaction done already with this orderid');

define('SUCCESS_EVENTSIGNUP_ADDED', 'Event signup data entered');
define('ERROR_EVENTSIGNUP_ADDED', 'Event signup data error ');

define('SUCCESS_EVENTSIGNUP_CUSTOMFIELDS_ADDED', 'Event signup custom fields added ');
define('ERROR_EVENTSIGNUP_CUSTOMFIELDS_ADDED', 'Error');



define('OFFLINE_PROMOTER_UPDATE', 'Updated Successfully');
define('ERROR_PAYMENT_GATEWAY', 'Select proper payment gateway ');
define('ERROR_NO_GATEWAY', 'Select atleast one payment gateway');
define('SUCCESS_ADDED_OFFLINE_PROMOTER','New offline promoter has been added successfully');
define('ERROR_NO_OFFLINE_PROMOTER', 'Sorry, there are no offline promoters for this event');

define('ERROR_EVENT_PAYMENTGATEWAYS', 'No payment gateways');


define('SUCCESS_ORDERLOG_UPDATED','Order log updated ');
define('ERROR_ORDERLOG_UPDATED', 'Error');

define('SUCCESSFUL_TRANSACTION', 'Congrats! Transaction successful');
define('ERROR_UNSUCCESSFUL_TRANSACTION', 'Transaction incomplete ');
define('SOMETHING_WRONG', 'Please try again');

define('ERROR_CHECKSUM', 'Error checksum data');

define('ERROR_EVENTID_ORDERID_REQUIRED', 'Eventid/Orderld required ');
define('ERROR_ORDERID_REQUIRED', 'OrderId required');
define('SUCCESS_UPDATED_PAYMENT_GATEWAY', "Payment Gateways updated ");

/* Stage details for PAYTM - NEED TO BE CLEANED */
define('PAYTM_ENVIRONMENT', 'PROD'); // TEST/PROD



define('ERROR_MULTIPLE_CURRENCY','Choose one currency per transaction ');



$PAYTM_DOMAIN = "pguat.paytm.com";
if (PAYTM_ENVIRONMENT == 'PROD') {
	$PAYTM_DOMAIN = 'secure.paytm.in';
}

define('PAYTM_REFUND_URL', 'https://' . $PAYTM_DOMAIN . '/oltp/HANDLER_INTERNAL/REFUND');
define('PAYTM_STATUS_QUERY_URL', 'https://' . $PAYTM_DOMAIN . '/oltp/HANDLER_INTERNAL/TXNSTATUS');
define('PAYTM_TXN_URL', 'https://' . $PAYTM_DOMAIN . '/oltp-web/processTransaction');
define('SUCCESS_SMS_SEND', 'SMS sent');
define('ERROR_SMS_SEND', 'Try Again');
define('SUCCESS_EVENTSIGNUP_UPDATE', 'Event Signup updated');
define('ERROR_EVENTSIGNUP_UPDATE', 'Error');
define('ERROR_NOT_EVENTSIGNUPID', 'Invalid Eventsignupid');

define('SUCCESS_TICKET_UPDATE', 'Ticket updated');
define('ERROR_TICKET_UPDATE', 'Error');
define('SUCCESS_BOOKING', 'Booked');
define('ERROR_TICKET_EXCEEDED', ' has exceeded the quantity');
define('SINGLE_ATTENDEE', 0);
define('MULTIPLE_ATTENDEES', 1);
define('SESSIONLOCK_INSERTION_FAILED','Session lock insertion failed');
define('ERROR_ADD_COLLABORATOR', 'Organizer email and collaborator email should not be same');
define('ERROR_COLLABORATOR_MODULES', 'Please choose atleast one module');
define('SUCCESS_UPDATED_EVENT_STATUS',"Event status updated");
define('ERROR_SELECT_PAYPAL','Please select paypal for all USD transactions');
define('SUCCESS_CREATED_ORDERID', "Order ID generated");

define('SUCCESS_EVENTSIGNUPTAX_ADDED', 'Event signup tax data added');
define('ERROR_EVENTSIGNUPTAX_ADDED', 'Error');

define('SUCCESS_GATEWAY_ADDED','Gateway added successfully for the booking');
define('ERROR_SELECT_TICKET_QTY','Please select ticket quantity');

define('ERROR_ALREADY_LOGGED','Some user already logged in');
define('SUCCESS_OFFLINE_BOOKING', 'Successfully booked the tickets. Please check your mail for Customer visitor pass.');

define('SUCCESS_USERPOINT_ADDED', 'User points are added');
define('SUCCESS_VIRALTICKETSALE_ADDED', 'Viral ticket sale added');
define('ERROR_BOOK_UNPUBLISHED_EVENT','You cannot book a ticket for an unpublished event');

define('SUCCESS_SESSIONLOCK_UPDATED','Session lock updated');
define('ERROR_SESSIONLOCK_UPDATED', 'Error');
define('SUCCESS_ADDED_GATEWAYDATA', 'Added payment gateway data');

define('ERROR_NO_CUSTOMFIELD_DATA', 'No custom fields found');
define('ERROR_INVALID_IMAGE', 'Upload '.IMAGE_EXTENTIONS.' images only');

define('ERROR_NO_PROMOTER_EVENTS', 'No Promoter Events');
define('ERROR_NO_OFFLINE_TICKETS', 'No Offline Tickets  This Event');
define('ERROR_NO_EVENTS', 'No Events found');
define('ERROR_NO_GUEST_DATA', 'No Guest Booking Data');
define('MELOGO','http://dn32eb0ljmu7q.cloudfront.net/images/static/me-logo.png');

//Template subjects and types
define('SUBJECT_INVITE_FRIEND','You are invited to attend ');
define('TYPE_INVITE_FRIEND','inviteFriends');
define('SUBJECT_COLLABORATOR_INVITE','MeraEvents - Collaborator Invite');
define('TYPE_COLLABORATOR_INVITE','collaboratorInvite');
define('TYPE_ORGEVENTSIGNUP_SUCCESS','orgEventSignupSuccess');
define('SUBJECT_ORGEVENTSIGNUP_SUCCESS','You have received a successful registration for ');
define('TYPE_DELEGATEEVENT_REG','delegateEventRegistration');
define('SUBJECT_DELEGATEEVENT_REG','You have successfully registered for ');
define('TYPE_SHARE','viralShare');
define('SUBJECT_SHARE','Share and Get Discount');
define('TYPE_REFERALBONUS_CONGRATS','referralBonusCongrats');
define('SUBJECT_REFERALBONUS_CONGRATS','Congrats,Your viral ticket has been used');
define('TYPE_EVENT_PUBLISH','eventPublish');
define('SUBJECT_EVENT_PUBLISH','Your event have published successful ' );
define('TYPE_SIGNUP','signup');
define('SUBJECT_SIGNUP',"Your MeraEvents.com Account details");
define('SUBJECT_RESEND_ACTIVATION','MeraEvents - Resend Activation');
define('TYPE_FORGET_PASSWORD','forgotPassword');
define('SUBJECT_FORGET_PASSWORD','Forgot Password mail from www.Meraevents.com');
define('TYPE_GUEST_BOOKING','guestListBookingNewUser');
define('TYPE_OFFLINENODISPLAY_BOOKING','offlineNodisplayBooking');
define('SUBJECT_OFFLINENODISPLAY_BOOKING','You have successfully registered for ');
define('TYPE_PROMOTER_INVITE','promoterInvite');
define('TYPE_GLOBAL_PROMOTER_INVITE','globalPromoterInvite');
define('TYPE_GLOBAL_PROMOTER_CONGRATZ','globalPromoterCongratz');
define('SUBJECT_PROMOTER_INVITE', 'MeraEvents - Promoter Invite');
define('SUBJECT_GLOBAL_PROMOTER_INVITE', 'MeraEvents - Global Affiliate');
define('USERNAME_EXIST', 'UserName already exists');
define('USERNAME_AVAILABLE', 'UserName avaliable');
define('SUCCESS_DELETED_EVENT','Successfully deleted the event');

define('TYPE_DELETE_REQUEST','organizerDeleteRequest');
define('SUBJECT_DELETE_REQUEST','You have successfully sent a delete request for ');
define('TYPE_SALES_DELREQUEST','salesDeleteRequest');
define('SUBJECT_SALES_DELREQUEST','Request for deleting an event');
define('DELREQUEST_SENT','Delete request has been sent successfully');
define('DELREQUEST_SENT_ALREADY','Delete Request has already been sent');

//Default seo related data
define('SEO_DEFAULT_DESCRIPTION', "Book Passes/Tickets on-line for EVENTNAME. Get Event Details, Rating, Timings for Events, Concerts, Live Shows and Parties in MeraEvents.com"); 
define('SEO_DEFAULT_KEYWORDS',"Events in India, Event, Upcoming Events, Events, Event Ticket Booking Website, "
                . "Event Ticket Booking Online, Event today, Events today, Today events, Event ticket booking online,"
                . " Event Ticket Booking, Event ticket booking sites, Concerts, Event Happening, Online Ticket for "
                . "Events, Events Booking Online, live shows ticket booking, Upcoming Events India, Online Events "
                . "Booking, Buy concert tickets, Tickets for concerts, Tickets for events");
//Unpublished event error message
define('UNPUBLISHED_EVENT_ERROR',"The Event you are trying to sign up is not published. Please search for similar     " . '<a href= "REDIRECT_URL">events</a>' . '<hr>' . "For any additional support 
please send mail to support@meraevents.com or 
Call us at +91-9396555888.");

define('ERROR_EMAIL_NOT_SENT', 'Email not sent');
define('ERROR_SMS_SENT', 'SMS not sent');
define('ERROR_DISCOUNT_USAGE_EXCEEDED', 'Discount code usage limit exceeded by ');
define('SUCCESS_ADDED_IN_SENTMESSAGE','Added in sent message records');
define('ADMIN_EMAIL','MeraEvents<admin@meraevents.com>');
define('SUBJECT_EVENT_TICKETSOLDDATA','Event Ticket Sold Data for the Event');
define('DB_LOCALITY','Locality');
define('ORGANIZATION_EVENTS_DISPLAY_LIMIT',12);
define('TYPE_CONTACT_ORGANIZER','contactOrganizer');

// oauth api related mesages
define('NO_EVENTS_SUCH_USER','There is no such an event for the User');
define('DEFAULT_TIME_ZONE','Asia/Kolkata');

define('NOCAT_BANNER',"http://dn32eb0ljmu7q.cloudfront.net/images/static/event_thumb.jpg");
define('NOCAT_THUMB' , "http://dn32eb0ljmu7q.cloudfront.net/images/static/event_thumb.jpg");
define('NOCAT_IMAGE' , "http://dn32eb0ljmu7q.cloudfront.net/images/static/event_thumb.jpg");
define('ERROR_FILETYPE' , "'Only jpg|jpeg|gif|png are allowed");
define('ERROR_FILEUPLOADING' , "Error in uploading the file.Try with another image");

// Custom Date Msg
//89077-Sensation --- 78572-Deltin Royale
define ("CUSTOMVALIDATIONEVENTIDS", json_encode(array(
78572)));
define ("CONFIGCUSTOMDATEMSG", json_encode(array(
'78572'=>"Package applicable as per the booking day."
)));
define ("CONFIGCUSTOMTIMEMSG", json_encode(array(
"89077"=>"6PM onwards.","101219"=>""
)));
define ("CONFIGTRANSACTIONDATEONPASS", json_encode(array(
'78572'=>true
)));
// MND prod Tickets Deltin
define ("SPECIALTICKETS", json_encode(array(
'78572'=>array('weekends'=>array(65091,65092),"weekdays"=>array(65089,65090)
))));
/* // Local
define ("SPECIALTICKETS", json_encode(array(
'83414'=>array('weekends'=>array(69798,69799),"weekdays"=>array(69797,69796)
)))); */
// Blog Memcache Expiry Time
define('BLOG_MEMCACHE',"BlogList");
define('BLOG_MEMCACHEEXPIRYTIME',"6*60*60");
define('SALES_MAIL','sales@meraevents.com');
define('MARKETING_MAIL','digitalmarketing@meraevents.com');

define('SUCCESS_DEVICE_DETAIL_ADDED', 'Successfully added device details');
define('ERROR_DISCOUNT_PERCENTAGE_EXCEEDED', 'Discount percentage should not exceed 100');
define('EVENT_PROMOCODES',json_encode(array(98752=>704238,98753=>704239)));
define('ERROR_TICKET_REQUIRED', 'You Must Create Atleast One Ticket');
define('RESEND_VERIFICATION_MAIL','Verification Email Resent');
define('RESEND_ACTIVATION_MAIL','Activation link has been sent to ur email');
define('FILE_TYPE_APPIMAGE','appimage');
define('DEFAULT_EVENTS_DISPLAY',12);



define('GLOBAL_AFFILIATE_COMMISSION',4);
// 99105 - org log on delegatepass
define ("ORGLOGOONPASS_99105", json_encode(array('99105'=>'http://static.meraevents.com/orglogo/99105-logo.jpg')));
