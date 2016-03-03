<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
define('DIR_WRITE_MODE', 0755);

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

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
define('EXIT_SUCCESS', 0); // no errors
define('EXIT_ERROR', 1); // generic error
define('EXIT_CONFIG', 3); // configuration error
define('EXIT_UNKNOWN_FILE', 4); // file not found
define('EXIT_UNKNOWN_CLASS', 5); // unknown class
define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
define('EXIT_USER_INPUT', 7); // invalid user input
define('EXIT_DATABASE', 8); // database error
define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

/*
 * Authentication Levels
 */

define('LEVEL_ADMIN', 1);
define('LEVEL_PEOPLE', 2);
define('LEVEL_COLLEGE', 3);
define('LEVEL_COMPANY', 4);

define('ALL','ALL');
/*
	Homepage Latest post limit
 */
define("LATEST_LIMIT", 5);

/*
 * Entity Constants
 */

define('ENTITY_PEOPLE', 1);
define('ENTITY_COLLEGE', 2);
define('ENTITY_COURSE', 3);
define('ENTITY_COMPANY', 4);
define('ENTITY_JOB', 5);

/*
 * Stripe Constants
 *
 * Account 
 * Email    - prathameshm@codebox.in
 * Password - phpmysql
 * Edit: value changed to lillt account
 */

if(strpos($_SERVER['SERVER_NAME'],'localhost') !== false || strpos($_SERVER['SERVER_NAME'],'staging.codebox.in') !== false || strpos($_SERVER['SERVER_NAME'],'dynamologic.info') !== false)
{
		// Test Keys
		define('STRIPE_SECRET_KEY',      'sk_test_7RLjMQx9Uhg7Q8kP9E3l8sqd');
		define('STRIPE_PUBLISHABLE_KEY', 'pk_test_6Kilku5fgY1arZlHgysidhck');
}
elseif(strpos($_SERVER['SERVER_NAME'],'liilt.com') !== false )
{
		// Live Keys
		define('STRIPE_SECRET_KEY',      'sk_live_1dMgXv8Dmjco3QaHaUKBQvRM'); 
		define('STRIPE_PUBLISHABLE_KEY', 'pk_live_G1AW7vtEW0ASTw0mJTdPiMqX');
}    


// Subscription Charges & currency
define('STRIPE_MEMBERSHIP_CHARGE', 99 * 100); // amount * 100 (this charge is for 1 year of membership)
define('STRIPE_PREMIUM_CHARGE', 49 * 100); // amount * 100
define('STRIPE_FEATURED_CHARGE', 99 * 100); // amount * 100
define('STRIPE_BOOSTER_CHARGE', 49 * 100); // amount * 100
define('STRIPE_BOOST_POST_CHARGE', 5 * 100); // amount * 100
define('STRIPE_CURRENCY', 'eur');

/*
 * User Constants
 */

define('USER_IS_PRO_MEMBER', 1);
define('USER_IS_FREE_MEMBER', 0);

define('PROFILE_PHOTO', 0);
define('PROFILE_PHOTO_FACEBOOK', 1);
define('PROFILE_PHOTO_GOOGLE', 2);
define('PROFILE_PHOTO_LINKEDIN', 3);
define('PROFILE_PHOTO_TWITTER', 4);

//Mandrill and Mail constants
//define('MANDRILL_API_KEY','UjVxox7dBEqYNCUWoILZxg'); // nikhil's mandrill account
define('MANDRILL_API_KEY','FXA8rivz9EgKEMblQRe6Zg'); // paul's mandrill account
define('SEND_FROM_EMAIL_ID', 'no-reply@liilt.com');
define('SEND_FROM_EMAIL_NAME', 'LiiLT Admin');

/*
 * Social Login Api Keys
 */

define('GOOGLE_CLIENT_ID', '738989257136-3b4nguj4746vccvo9loqao8rlbiputc7.apps.googleusercontent.com');
define('GOOGLE_CLIENT_SECRET', 'Khw5TwcR8d0za-F4gGqJPZh9');
define('GOOGLE_DEVELOPER_KEY','AIzaSyDFLJk5qd_fU6cEEAAgc19Oj0oP0LfMWeQ');

define('FACEBOOK_APP_ID', '1587016351573282');
define('FACEBOOK_APP_SECRET', '74c2d1f0e83d40c666aa888060f457d0');

define('LINKEDIN_CLIENT_ID', '759apdzcci1o38');
define('LINKEDIN_CLIENT_SECRET', 'MNYcIsjIQEya0ICa');

define('TWITTER_CONSUMER_KEY', '5iKHloSKGiLgeZsWT80Hc3cia');
define('TWITTER_CONSUMER_SECRET', '4848NKOnk1yyE79LsKC0FY5uBL1xAWXJo6XTp1KvUQ8DrnVfbv');

/*
 * Email Published Constants
 */

define('IS_EMAIL_PUBLISHED_YES',1);
define('IS_EMAIL_PUBLISHED_NO',0);

/*
 *	LIMIT Constants
 */

define('LIMIT_START',0);
define('LIMIT',30);
define('RESUME_LIMIT',30);

/**
*
* Blog Api
*
**/
define('BLOG_API_RECENT_POST_URL', 'https://www.liilt.com/thebuzz/api/get_recent_posts/');

define('AFFILIATE_TRACKING_COOKIE_VALIDITY',15); // affiliate tracking cookie expires after 15 days
define('AFFILIATE_URL', 'https://www.liilt.com/affiliates/sale.php'); // affiliate url to send the curl request to

/*
 *	Friend request accept constants
 */

define('IS_ACCEPTED_FRIEND_REQUEST_YES',1);
define('IS_ACCEPTED_FRIEND_REQUEST_NO',0);

/*
 *	Block user constants
 */

define('IS_BLOCKED_USER_YES',1);
define('IS_BLOCKED_USER_NO',0);

/*
 * Curl Constants
 */

define('CURL_GET', 1);
define('CURL_POST', 2);

/*
 *	Liilt user constants
 */

define('LIILT_USER','hello@liilt.com');
define('LIILT_COLLEGE_ID', 2);
define('LIILT_COMPANY_ID', 4);

/**
*
* Login error type
*
**/
define('VERIFICATION_ERROR',1);
define('INVALID_CREDENTIALS', 2);

/**
*
* Message type
*
**/
define('TYPE_MESSAGE', 1);
define('TYPE_COURSE', 2);
define('TYPE_JOB', 3);

define('BASIC_USER_MAX_COLLEGES',1);
define('BASIC_USER_MAX_COMPANIES',1);
define('BASIC_USER_MAX_JOBS',1);
define('BASIC_USER_MAX_COURSES',1);

/*
 *  SOCIAL ACCOUNT USER CONSTANTS
 */

define('IS_SOCIAL_ACCOUNT_USER_YES', 1);
define('IS_SOCIAL_ACCOUNT_USER_NO', 0);

/*
 *  Pro Membership Constants
 */

define('PRO_MEMBERSHIP_EXPIRY_PERIOD','+1 Year');

/*
 *  Featured Profiles Constants
 */

define('FEATURED_EXPIRY_PERIOD','+3 Months');

/*
 *  Premium Profile Constants
 */

define('PREMIUM_EXPIRY_PERIOD','+3 Months');


/*
 *  Notification Constants
 */

define( 'NOTIFICATION_MESSAGE_RECEIVED', 1 );
define( 'NOTIFICATION_FRIEND_REQUEST_RECEIVED', 2 );
define( 'NOTIFICATION_FRIEND_REQUEST_ACCEPTED', 3 );
define( 'NOTIFICATION_FRIEND_REQUEST_REJECTED', 4 );
define( 'NOTIFICATION_INVOICE_AVAILABLE', 5 );
define( 'NOTIFICATION_COURSE_ENQUIRY_MESSAGE_RECEIVED', 6 );
define( 'NOTIFICATION_JOB_APPLICATION_MESSAGE_RECEIVED', 7 );


/*
 *  XERO API Constants
 */
if(strpos($_SERVER['SERVER_NAME'],'localhost') !== false || strpos($_SERVER['SERVER_NAME'],'staging.codebox.in') !== false)
{
		//TEST CONSTANTS
	define('XERO_CONSUMER_KEY','WZECUKPFC1NFCNOANCW3J2LBLKKPC9');
	define('XERO_CONSUMER_SECRET','DDGDVRLGUAXDEF59OLE3FNP7GV9ODD');
}
elseif(strpos($_SERVER['SERVER_NAME'],'liilt.com') !== false)
{
		//LIVE CONSTANTS
	// email : paul@liilt.com
	//pass : Howayadoin247
	define('XERO_CONSUMER_KEY','WEW5XNCZER1HH6LLOHD9FYXMQGHXXR');
	define('XERO_CONSUMER_SECRET','ARGXWKEUPAMQGBTZMKKZBQYH4SNP4H');
}

define ("XERO_APP_TYPE", "Private" );


/*
 *	Contact Form Email constants
 */

define('CUSTOMER_SUPPORT_EMAIL','support@liilt.com');
define('TECHNICAL_SUPPORT_EMAIL','support@liilt.com');
define('PRESS_EMAIL','hello@liilt.com');
define('ADVERTISING_EMAIL','hello@liilt.com');
define('FEEDBACK_EMAIL','support@liilt.com');
define('GENERAL_EMAIL','hello@liilt.com');

/*
 *	MailChimp Constants
 */

define('MAILCHIMP_API_KEY', '920d7c10a74b8e733bc180672ee03c43-us8');
define('MAILCHIMP_LIST_ID', 'c8ba32987b');

/*
 *  CONSUMER TYPE Constants
 */
define('CUSTOMER_BUSINESS','B');
define('CUSTOMER_CONSUMER','C');

/*
 *  XERO PAYMENT PRODUCT ID Constants
 */
define('XPP_PROFESSIONAL','1');

define('XPP_PREMIUM_JOBS','2');
define('XPP_FEATURED_JOBS','3');

define('XPP_PREMIUM_COMPANIES','4');
define('XPP_FEATURED_COMPANIES','5');

define('XPP_PREMIUM_COLLEGES','6');
define('XPP_FEATURED_COLLEGES','7');

define('XPP_PREMIUM_COURSES','8');
define('XPP_FEATURED_COURSES','9');

define('XPP_PREMIUM_PROFILES','10');
define('XPP_FEATURED_PROFILES','11');

/*
 *	Custom Registration Constants
 */
define('IS_CUSTOM_REGISTRATION_YES',1);
define('IS_CUSTOM_REGISTRATION_NO',0);
define('CUSTOM_REGISTRATION_MANUAL',1);
define('CUSTOM_REGISTRATION_DOMAIN',2);

/*
 *	JSON API USER constants
 */

define('JSON_API_USER_NONCE','9c1ca00f3f');
define('WORDPRESS_BASE_URL','https://www.liilt.com/thebuzz/');