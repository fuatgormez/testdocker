<?php exit('burdasin');
// set correct time zone
ini_set('date.timezone', 'Europe/Berlin');

// set maximum execution time to forever
ini_set('max_execution_time', 0);

// start session
session_start();

// whether this is a production or development environment
$development = true;

// which company data should be loaded
$company = '';

// database credentials
$db_host = '';
$db_user = '';
$db_password = '';
$db_name = '';

// error message if startup failed
define('STARTUP_ERROR_MSG', 'Die Software kann nicht geladen werden.');

// set values for variables from above
if ($_SERVER['SERVER_NAME'] == "aps.localhost") {
    $development = true;
    $company = 'aps';
    $db_host = 'localhost';
    $db_user = 'root';
    $db_password = 'logitech';
    $db_name = 'aps';
} elseif ($_SERVER['SERVER_NAME'] == "tps.localhost") {
    $development = true;
    $company = 'tps';
    $db_host = 'localhost';
    $db_user = 'root';
    $db_password = 'logitech';
    $db_name = 'tps';
} elseif ($_SERVER['SERVER_NAME'] == "cps.localhost") {
    $development = true;
    $company = 'cps';
    $db_host = 'localhost';
    $db_user = 'root';
    $db_password = 'logitech';
    $db_name = 'cps';
} elseif ($_SERVER['SERVER_NAME'] == "aps.ttact.de") {
    $development = false;
    $company = 'aps';
    $db_host = 'localhost';
    $db_user = 'root';
    $db_password = 'lacowu#7';
    $db_name = 'aps';
} elseif ($_SERVER['SERVER_NAME'] == "tps.ttact.de") {
    $development = false;
    $company = 'tps';
    $db_host = 'localhost';
    $db_user = 'root';
    $db_password = 'lacowu#7';
    $db_name = 'tps';
} elseif ($_SERVER['SERVER_NAME'] == "cps.ttact.de") {
    $development = false;
    $company = 'cps';
    $db_host = 'localhost';
    $db_user = 'root';
    $db_password = 'lacowu#7';
    $db_name = 'cps';
} elseif ($_SERVER['SERVER_NAME'] == "aps-dev.ttact.de") {
    $development = false;
    $company = 'aps';
    $db_host = 'localhost';
    $db_user = 'root';
    $db_password = 'nowabu#5';
    $db_name = 'aps';
} elseif ($_SERVER['SERVER_NAME'] == "tps-dev.ttact.de") {
    $development = false;
    $company = 'tps';
    $db_host = 'localhost';
    $db_user = 'root';
    $db_password = 'nowabu#5';
    $db_name = 'tps';
} elseif ($_SERVER['SERVER_NAME'] == "cps-dev.ttact.de") {
    $development = false;
    $company = 'cps';
    $db_host = 'localhost';
    $db_user = 'root';
    $db_password = 'nowabu#5';
    $db_name = 'cps';
} else {
    exit(STARTUP_ERROR_MSG);
}

// show errors if development environment
if ($development) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(-1);
}

// include/register autoloaders
spl_autoload_register(function ($class_name) use ($company) {
    // project-specific namespace prefix
    $prefix = 'ttact\\';

    // base directory for the namespace prefix
    $base_dir = __DIR__ . '/libs/ttact/';

    // does the class use the namespace prefix?
    $len = strlen($prefix);
    if (strncmp($prefix, $class_name, $len) !== 0) {
        // no, move to the next registered autoloader
        return;
    }

    // get the relative class name
    $relative_class = substr($class_name, $len);

    // replace the namespace prefix with the base directory, replace namespace
    // separators with directory separators in the relative class name, append
    // with .php
    $file = $base_dir . str_replace('\\', '/', $relative_class);

    if (file_exists($file . '.' . $company . '.php')) {
        // if the file exists with company suffix, require it
        require $file . '.' . $company . '.php';
    } elseif (file_exists($file . '.php')) {
        // otherwise try without company suffix
        require $file . '.php';
    }
});
require 'libs/period/autoload.php';
require 'libs/dompdf/autoload.inc.php';
require 'libs/smarty/Smarty.class.php';

// create Database instance
$db = new \ttact\Database($db_host, $db_user, $db_password, $db_name);
if ($db instanceof \ttact\Database) {
    // create a new Smarty instance
    $smarty = new Smarty();
    $smarty->setLeftDelimiter('{{');
    $smarty->setRightDelimiter('}}');
    $smarty->setTemplateDir('views/');
    $smarty->setCompileDir('libs/smarty/compile/');
    $smarty->setCacheDir('libs/smarty/cache/');
    #$smarty->setCaching(true);
    #$smarty->setCacheLifetime(86400);
    #$smarty->setCompileCheck(false);
    #$smarty->setDebugging(true);

    // create a new UserInput instance
    $user_input = new \ttact\UserInput($_GET, $_POST);

    // create a new MiscUtils instance
    $misc_utils = new \ttact\MiscUtils($_SERVER['SERVER_NAME'] . (($_SERVER['SERVER_PORT'] != 80) ? (':' . $_SERVER['SERVER_PORT']) : ''));

    // create a new PasswordUtils instance
    $password_utils = new \ttact\PasswordUtils();

    // create an instance of the base controller and inject all dependencies.
    $bootstrap = new \ttact\Bootstrap($db, $smarty, session_id(), $user_input, $misc_utils, $password_utils, $company);

    // print the website
    try {
        echo $bootstrap->getPageContent();
    } catch(\Exception $exception) {
        exit(STARTUP_ERROR_MSG);
    }
} else {
    exit(STARTUP_ERROR_MSG);
}
