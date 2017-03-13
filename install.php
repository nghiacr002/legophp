<?php

use APP\Engine\Installation;
use APP\Engine\Application;
use APP\Engine\Router;

define('SIMPLE_APP', true);
define('APP_DS', DIRECTORY_SEPARATOR);
define('APP_ROOT_PATH', dirname(__FILE__) . APP_DS);
define('APP_PATH', APP_ROOT_PATH . "Application" . APP_DS);
define('APP_PATH_SETTING', APP_PATH . 'Setting' . APP_DS);
define('APP_PATH_LIB', APP_ROOT_PATH . 'Library' . APP_DS);
define('INSTALLING', true);
require_once APP_PATH_SETTING . 'Loader.php';

$mainApp = new Application($_CONF);
$installApp = new Installation();

$sCallAction = isset($_REQUEST['action']) ? $_REQUEST['action'] : "";
switch ($sCallAction)
{
    case "checkRequirements":
        $installApp->start($mainApp);
        break;
    case "config";
        $installApp->config($mainApp);
        break;
    case 'installDB':
        $installApp->installDB($mainApp);
        break;
    case 'installCustomScript':
    	$installApp->installCustomScript($mainApp);
    	break;

}
$sPathDir = dirname($_SERVER["REQUEST_URI"]);
if(empty($sPathDir))
{
	$sPathDir = "/";
}
else
{
	$sPathDir.="/";
}
$mainApp->router = new Router();
$mainApp->router->instance()->setBasePath($sPathDir);
$mainApp->setConfig('system', 'base_path',$sPathDir);
$sRegisterUser = $mainApp->router->url()->makeUrl('user/register',array('mode' => 'install'));
if(!empty($sCallAction))
{
	exit;
}
?>
<html>
    <head>
    	<script type="text/javascript">
    		var registerURL = '<?php echo $sRegisterUser; ?>';
    	</script>
        <script src="<?php echo $installApp->getBaseUrl() ?>Application/Theme/Frontend/Default/Js/jquery-2.2.3.min.js"></script>
        <title>INSTALL</title>

        <style>
            .echo_content{
                display:block;
            }
            .command{
                font-weight:bold;
                color:#000;
            }
            .success{
                color: green;
            }
            #installation-holder p{
                margin:2px 0;
            }
            .fail {
                color:red
            }
            .form-control label{
                width:100px;
                text-align:right;
                display:inline-block;
                margin-right:10px;
            }
            #loading pre{
                font-family: monospace;
                font-size: 1.5em;
                font-weight: bold;
                display: block;

                display: inline-block;
                margin: .25em;
            }
        </style>
        <script>
            var spinner = [
                "╔════╤╤╤╤════╗\n║    │││ \\   ║\n║    │││  O  ║\n║    OOO     ║",
                "╔════╤╤╤╤════╗\n║    ││││    ║\n║    ││││    ║\n║    OOOO    ║",
                "╔════╤╤╤╤════╗\n║   / │││    ║\n║  O  │││    ║\n║     OOO    ║",
                "╔════╤╤╤╤════╗\n║    ││││    ║\n║    ││││    ║\n║    OOOO    ║"
            ];
        </script>
        <script>
            var prev_msg = "";
            var INSTALL = {
                start: function () {
                    $('#license-reader').hide();
                    $('#accept-button').hide();
                    $('#installation-holder').html("");
                    $.ajax({
                        async: true,
                        url: 'install.php',
                        method: 'POST',
                        data: {
                            action: 'checkRequirements'
                        },
                        xhr: function () {
                            var xhr = new XMLHttpRequest();
                            xhr.open('GET', 'install.php?action=checkRequirements', true);
                            xhr.onprogress = function (e) {
                                _txt = INSTALL.getResponse(e.currentTarget.responseText);
                                INSTALL.msg(_txt);
                                if (_txt == "Completed") {
                                    INSTALL.config();
                                }
                            }
                            return xhr;
                        },
                        cache: false,
                    }
                    ).done(function (data) {
                    	if(data.indexOf("INSTALL FAIL. CHECK FOR RED COMMENTS") >=0){
							$('#accept-button').show();
						}

                    }).error(function () {

                    });
                },
                config: function () {
                    INSTALL.clean();
                    $('#step_0').hide();
                    $('#step_1').show();
                },
                saveConfig: function (f) {
                    $.ajax({
                        async: true,
                        url: 'install.php?action=config',
                        method: 'POST',
                        data: $(f).serialize(),
                        xhr: function () {
                            var xhr = new XMLHttpRequest();
                            xhr.open('GET', 'install.php?action=config', true);
                            xhr.onprogress = function (e) {
                                _txt = INSTALL.getResponse(e.currentTarget.responseText);
                                INSTALL.msg(_txt);
                                console.log(_txt);
                                if (_txt == "Completed") {
                                    INSTALL.installDB();
                                }
                            }
                            return xhr;
                        },
                        cache: false,
                    }
                    ).done(function (data) {


                    }).error(function () {

                    });
                },
                installDB: function () {
                    $('#step_0').hide();
                    $('#step_1').hide();
                    $('#step_2').show();
                    INSTALL.clean();
                    INSTALL.msg('');
                    INSTALL.loading(true);

                    $.ajax({
                        async: true,
                        url: 'install.php?action=installDB',
                        method: 'POST',
                        data: {},
                        xhr: function () {
                            var xhr = new XMLHttpRequest();
                            xhr.open('GET', 'install.php?action=installDB', true);
                            xhr.onprogress = function (e) {
                                _txt = INSTALL.getResponse(e.currentTarget.responseText);
                                INSTALL.msg(_txt);
                                if (_txt == "Completed") {
									INSTALL.installCustomScript();
									console.log('start install custom Scription');
                                }
                                if (_txt == "step_1") {
                                    INSTALL.loading(false);
                                    $('#step_1').show();
                                    $('#step_2').hide();
                                }
                            }
                            return xhr;
                        },
                        cache: false,
                    }
                    ).done(function (data) {

                    }).error(function () {

                    });
                },
                installCustomScript: function(){
                	$('#step_0').hide();
                    $('#step_1').hide();
                    $('#step_2').hide();
                    $('#step_3').show();
                    INSTALL.clean();
                    INSTALL.msg('');
                    INSTALL.loading(true);
                    $.ajax({
                        async: true,
                        url: 'install.php?action=installCustomScript',
                        method: 'POST',
                        data: {},
                        xhr: function () {
                            var xhr = new XMLHttpRequest();
                            xhr.open('GET', 'install.php?action=installCustomScript', true);
                            xhr.onprogress = function (e) {
                                _txt = INSTALL.getResponse(e.currentTarget.responseText);
                                INSTALL.msg(_txt);
                                if (_txt == "Completed") {
									window.location.href = registerURL;
                                }
                                if (_txt == "step_3") {
                                	window.location.href = registerURL;
                                }
                            }
                            return xhr;
                        },
                        cache: false,
                    }
                    ).done(function (data) {

                    }).error(function () {

                    });
                },
                msg: function (msg) {
                    $('#installation-holder').append('<p>' + msg + '</p>');
                },
                clean: function () {
                    $('#installation-holder').html("");
                },
                getResponse: function (txt)
                {
                    var _txt = txt;
                    _txt = _txt.replace(prev_msg, "");
                    prev_msg = txt;
                    return _txt;
                },
                loading: function (m) {
                    if (m) {
                        $('#loading').show();
                        el = document.getElementById('pre-holder');
                        (function (spinner, el) {
                            var i = 0;
                            setInterval(function () {
                                el.innerHTML = spinner[i];
                                i = (i + 1) % spinner.length;
                            }, 300);
                        })(spinner, el);
                    } else {
                        $('#loading').hide();
                    }
                }
            }

        </script>
    </head>
    <body>
        <h1>Installation for LegoPHP <?php echo $mainApp->getVersion(); ?></h1>
        <div id="step_0">
            <div id="license-reader"><pre>
============================================================================

<strong style="font-size:20px;">MIT License</strong>

Copyright (c) [2016] [NghiaDH]

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.

<strong>By click on process button, you have already read and agreed with our license</strong>

=============================================================================</pre></div>
            <div id="accept-button">
                <button onclick="INSTALL.start()">Install</button>
            </div>
        </div>
         <div id="installation-holder">

        </div>
        <div id="step_1" style="display:none;">
            <h3>Configure server</h3>
            <form id="server-configuration" onsubmit="INSTALL.saveConfig(this);
                    return false;" method="POST">
                <div class="form-control">
                    <label>Host</label>
                    <input type="text" name="db[host]" value="localhost">
                </div>
                <div class="form-control">
                    <label>DB Name</label>
                    <input type="text" name="db[name]" value="">
                </div>
                <div class="form-control">
                    <label>User</label>
                    <input type="text" name="db[user]" value="">
                </div>
                <div class="form-control">
                    <label>Password</label>
                    <input type="password" name="db[pwd]" value="">
                </div>

                <div class="form-control">
                    <label>Port</label>
                    <input type="text" name="db[port]" value="3306">
                </div>
                <div class="form-control">
                    <label>Prefix</label>
                    <input type="text" name="db[prefix]" value="tbl_">
                </div>
                <div class="form-control">
                    <label>Charset</label>
                    <input type="text" name="db[charset]" value="utf8">
                </div>
                <div class="form-control">
                    <label>Adapter</label>
                    <select name="db[adapter]">
                        <option value="mysqli">MySQLi</option>
                    </select>
                </div>
                <div class="form-control">
                    <input type="submit" class="btn btn-success" value="save & continue"/>
                </div>
            </form>
        </div>
        <div id="step_2" style="display:none">
            <h3>Installing Database</h3>
        </div>
        <div id="loading" style="display:none;">
            <pre id="pre-holder">
            </pre>
        </div>

    </body>
</html>