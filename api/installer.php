<?php 
    include_once("config.php");
    include_once("includes/database.php");
    include_once("includes/install.php");

    include_once("includes/classes/Auth.php");
    include_once("includes/classes/Options.php");
    include_once("includes/classes/Upload.php");

    header("Access-Control-Allow-Origin: " . APP_CORS_URLS);
    header("Access-Control-Allow-Headers: " . APP_CORS_URLS);
    header("Access-Control-Allow-Credentials: true");

    define("ROOT_DIR", dirname(__FILE__));

    createTables($db);

    global $auth;
    global $options;
    global $upload;

    $auth = new Auth($db);
    $options = new Options($db);
    $upload = new Upload($db);

    $options->setDefaultOptions();

    if(APP_SETUP_COMPLETE == true) {
        echo "Setup already complete!";
        exit();
    }

    $auth->createUser("admin", "", "admin", "admin");
    echo "created admin user <br>";

    try {
        updateLineInFile(ROOT_DIR . "/config.php", 'define("APP_SETUP_COMPLETE", true);', 2);
    } catch(Exception $e) {
        echo "error while saving config";
    }

    echo "updated config set APP_SETUP_COMPLETE to true, this endpoint is now unavailable! <br>";
    echo "You can now login to the dashboard with username: admin, password: admin. <br>";
    echo "It is recommended to then create a new admin user and delete the default one for security reasons!";

    function updateLineInFile($filePath, $newLineContent, $lineNumber) {
        $fileLines = file($filePath, FILE_IGNORE_NEW_LINES);
    
        if ($lineNumber < 1 || $lineNumber > count($fileLines)) {
            throw new Exception("Invalid line number: $lineNumber");
        }
    
        $fileLines[$lineNumber - 1] = $newLineContent;
    
        $fileContents = implode(PHP_EOL, $fileLines);
        file_put_contents($filePath, $fileContents);
    }
    