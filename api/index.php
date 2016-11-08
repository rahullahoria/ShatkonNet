<?php

require_once "header.php";

include 'db.php';
require 'Slim/Slim.php';


//app resource

//Sub resource states
require_once "resources/app/states/getAppStates.php";
require_once "resources/app/states/getOptions.php";
require_once "resources/app/states/getTasks.php";

//app
require_once "app.php";



?>