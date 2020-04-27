<?php

function autoloadClass($className)
{
    $project  = "app";                 //Name of the project folder
    // getting path of server or ROOT
    $path =  $_SERVER["DOCUMENT_ROOT"];
    
    $path = $path . "/" . $project;
    
    $locations = array_filter(glob($path. '/*'), 'is_dir');
       
    $directories = array();
    foreach ($locations as $location) {
        $temp = explode('/', $location);      //exploding to get the subdirectory name
        array_push($directories, end($temp));  //storing only  name of the subdirectory in directories array
    }

    
    foreach ($directories as $directorie) {
        $filename = $path . "/" . $directorie . "/model" . "/" .  strtolower($className) . ".php";   //making file directory by appending the classname and ".php"
       
        if (is_readable($filename)) {
            require_once $filename;
        }
    }
}


// to load classes which are in the Util Directory
function loadutilClass($className)
{
    $filename = __DIR__ . '/' . strtolower($className) . '.php';

    if (is_readable($filename)) {
        require_once $filename;
    }
}
spl_autoload_register("autoloadClass");

spl_autoload_register("loadutilClass");
?>