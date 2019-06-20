<?php

// report all errors (debug only)
error_reporting( E_ALL );
ini_set('display_errors', 1);

// Include needed files
include_once("./const/const.inc.php");
include_once("./CmdSystem.php");
include_once("./Result.php");

$ret = 0;
CmdSystem::execute("ls", $ret);
echo $ret;

/*

// Create Result object
$result = new Result();

// Process user code
$result->processUserCode();

// Display JSON object
echo $result;

//*/

?>