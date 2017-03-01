<?php

/*
 *
  100 	Continue
  101 	Switching Protocols
  200 	OK 	Action completed successfully
  201 	Created 	Success following a POST command
  202 	Accepted 	The request has been accepted for processing, but the processing has not been completed.
  203 	Partial Information 	Response to a GET command, indicates that the returned meta information is from a private overlaid web.
  204 	No Content 	Server has received the request but there is no information to send back.
  205 	Reset Content
  206 	Partial Content 	The requested file was partially sent.   Usually caused by stopping or refreshing a web page.
  300 	Multiple Choices
  301 	Moved Permanently 	Requested a directory instead of a specific file.   The web server added the filename index.html, index.htm, home.html, or home.htm to the URL.
  302 	Moved Temporarily
  303 	See Other
  304 	Not Modified 	The cached version of the requested file is the same as the file to be sent.
  305 	Use Proxy
  400 	Bad Request 	The request had bad syntax or was impossible to be satisified.
  401 	Unauthorized 	User failed to provide a valid user name / password required for access to file / directory.
  402 	Payment Required
  403 	Forbidden 	The request does not specify the file name. Or the directory or the file does not have the permission that allows the pages to be viewed from the web.
  404 	Not Found 	The requested file was not found.
  405 	Method Not Allowed
  406 	Not Acceptable
  407 	Proxy Authentication Required
  408 	Request Time-Out
  409 	Conflict
  410 	Gone
  411 	Length Required
  412 	Precondition Failed
  413 	Request Entity Too Large
  414 	Request-URL Too Large
  415 	Unsupported Media Type
  500 	Server Error 	In most cases, this error is a result of a problem with the code or program you are calling rather than with the web server itself.
  501 	Not Implemented 	The server does not support the facility required.
  502 	Bad Gateway
  503 	Out of Resources 	The server cannot process the request due to a system overload.  This should be a temporary condition.
  504 	Gateway Time-Out 	The service did not respond within the time frame that the gateway was willing to wait.
  505 	HTTP Version not supported
 */
define('HTTP_CODE_CONTINUE', 100);
define('HTTP_CODE_SWITCH_PROTOCALS', 101);
define('HTTP_CODE_OK', 200);
define('HTTP_CODE_CREATED', 201);
define('HTTP_CODE_ACCEPTED', 202);
define('HTTP_CODE_PARTIAL_INFORMATON', 203);
define('HTTP_CODE_NO_CONTENT', 204);
define('HTTP_CODE_RESET_CONTENT', 205);
define('HTTP_CODE_PARTIAL_CONTENT', 206);
define('HTTP_CODE_MULTIPLE_CHOICES', 300);
define('HTTP_CODE_MOVED_PERMANENTLY', 301);
define('HTTP_CODE_MOVED_TEMPORARILY', 302);
define('HTTP_CODE_SEE_OTHER', 303);
define('HTTP_CODE_NOT_MODIFIED', 304);
define('HTTP_CODE_USE_PROXY', 305);
define('HTTP_CODE_BAD_REQUEST', 400);
define('HTTP_CODE_UNAUTHORIZED', 401);
define('HTTP_CODE_PAYMENT_REQUIRED', 402);
define('HTTP_CODE_FORBIDDEN', 403);
define('HTTP_CODE_NOT_FOUND', 404);
define('HTTP_CODE_METHOD_NOT_ALLOWED', 405);
define('HTTP_CODE_NOT_ACCEPTABLE', 406);
define('HTTP_CODE_PROXY_AUTHENTICATION_REQUIRED', 407);
define('HTTP_CODE_REQUEST_TIME_OUT', 408);
define('HTTP_CODE_CONFLICT', 409);
define('HTTP_CODE_GONE', 410);
define('HTTP_CODE_LENGTH_REQUIRED', 411);
define('HTTP_CODE_PRECONDITION_FAILED', 412);
define('HTTP_CODE_REQUEST_ENTITY_TOO_LARGE', 413);
define('HTTP_CODE_REQUEST_URL_TOO_LARGE', 411);
define('HTTP_CODE_UNSUPPORTED_MEDIA_TYPE', 415);
define('HTTP_CODE_SERVER_ERROR', 500);
define('HTTP_CODE_NOT_IMPLEMENTED', 501);
define('HTTP_CODE_BAD_GATEWAY', 502);
define('HTTP_CODE_OUT_OF_RESOURCES', 503);
define('HTTP_CODE_GATEWAY_TIME_OUT', 504);
define('HTTP_CODE_VERSION_NOT_SUPPORTED', 505);
/* * ***** */
define('APP_TIME', time());
define('APP_PUBLIC_PATH', APP_ROOT_PATH . 'Public' . APP_DS);
define('APP_UPLOAD_PATH', APP_PUBLIC_PATH . 'Upload' . APP_DS);
define('APP_CACHE_PATH', APP_PUBLIC_PATH . 'Cache' . APP_DS);
define('APP_META_PATH', APP_PUBLIC_PATH . 'Meta' . APP_DS);
define('APP_CLIENT_PATH', APP_PUBLIC_PATH . 'Client' . APP_DS);
define('APP_LOG_PATH', APP_PUBLIC_PATH . 'Log' . APP_DS);
define('APP_MODULE_PATH', APP_PATH . 'Module' . APP_DS);
define('APP_THEME_PATH', APP_PATH . 'Theme' . APP_DS);
define('APP_DEFAULT_PASSWORD', '123456');
define('APP_DEFAULT_GROUP', '2');
if(file_exists(APP_PATH_SETTING . 'Debug.php'))
{
	include_once APP_PATH_SETTING. 'Debug.php';
}



