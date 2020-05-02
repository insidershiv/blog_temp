<?php

function autoloadClass($className)
{
    $project  = "app";                 //Name of the project folder
   
    $path =  $_SERVER["DOCUMENT_ROOT"];  // getting path of server or ROOT
    
    $path = $path . "/" . $project;
    
    $locations = array_filter(glob($path. '/*'), 'is_dir');    //this gives all those paths which are a directory
       
    $directories = array();
    foreach ($locations as $location) {
        $temp = explode('/', $location);      //exploding to get the subdirectory name
        array_push($directories, end($temp));  //storing only  name of the subdirectory in directories array
    }

    
    $className = explode('\\', $className);    //Exploading because when using namespce we get the namespace\classname
                                                                 
    $className = end($className);             //so to get the classname out of what we got we use end() and update the classname

    foreach ($directories as $directorie) {
        $filename = $path . "/" . $directorie . "/model" . "/" .  strtolower($className) . ".php";
   
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

spl_autoload_register("loadutilClass");

spl_autoload_register("autoloadClass");
?>