<?php
//=============================================================================
// IMPORTS
//=============================================================================



//=============================================================================
// FUNCTIONS
//=============================================================================

function checkParams(){
    // Init result
    $result = "invalidParams";
    
    // Check user ID presence
    if( !isset($_POST["userID"]) ){
        $result = "missingUserID";
    }
    // Check exercice ID presence
    else if( !isset($_POST["exoID"]) ){
        $result = "missingExoID";
    }
    // Check programming language ID presence
    else if( !isset($_POST["langID"]) ){
        $result = "missingLangID";
    }
    // Check user code presence
    else if( !isset($_POST["codeText"]) ){
        $result = "missingCodeText";
    }
    // Check userID is not empty and is a number
    else if( !ctype_digit($_POST["userID"]) || ($_POST["userID"] == "") ){
        $result = "badValueUserID";
    }
    // Check exercice ID is not empty
    else if( $_POST["exoID"] == "" ){
        $result = "emptyExoID";
    }
    // Check other parameters
    else {
        // Check language ID is in the list
        switch($_POST["langID"]){
            case "Java":
            case "C":
            case "Python":
                $result = "validParams";
                break;
            default :
                $result = "badValueLangID";
                break;
        }        
    }
    
    // return result of param check
    return $result;
}

function initResultArray(){
    // Init response array
    $out = array();
    $out["result"] = "";
    $out["userID"] = "";
    $out["exoID"]  = "";
    $out["langID"] = "";
    $out["message"] = "";

    // Fill response array
    $out["result"] = checkParams();
    if( $out["result"] == "validParams" ){
        $out["userID"] = $_POST["userID"];
        $out["exoID"]  = $_POST["exoID"];
        $out["langID"] = $_POST["langID"];
    }

    // Prepare user folder
    $path = "../data/users/user_" . $out["userID"] ."/";
    
    // Create user folder
    if ( file_exists($path) ){
        // If the file is NOT A FOLDER, that means there is a probleme because we cannot create a folder with its name
        if( !is_dir($path) ){
            $out["result"] = "userDirNameAlreadyUsed";
        }
    }
    else{
        if( mkdir($path) === FALSE ){
            $out[result] = "userDirCreationError";
        }            
    }

    // Create User file
    if( $out["result"] == "validParams" ){
        // (re)create user file
        $fp = fopen($path. "dummy.txt", "w");
        
        if ($fp === FALSE){
            $out['result'] = "userFileCreationError";
        }    
        else{
            // Copy user code to the user folder
            if( fwrite($fp, $_POST["codeText"]) === FALSE ){
                $out["result"] = "userFileWriteError";
            }
            // Close user file access
            fclose($fp);
        }
    }
    
    // Return result array
    return $out;
}

function getSystemCommand($infos){
    // Prepare input generation
    //   ./venv/Scripts/python.exe ./genExercice.py exo001 question
    // | ./venv/Scripts/python.exe ./dummy.py
    // | ./venv/Scripts/python.exe ./genExercice.py exo001 answer
    
    //   "../system/langEnv/python/python.exe" ../data/exercices/exo001/genExercice.py exo001 question
    // | "../system/langEnv/python/python.exe" ../data/users/user_123/dummy.txt 2>>&1
    // | "../system/langEnv/python/python.exe" ../data/exercices/exo001/genExercice.py exo001 answer > ../data/users/user_123/out.txt
    
    
    $PYTHON_EXE          = stripslashes('"../system/langEnv/python/python.exe"');
    $GEN_INPUT_SCRIPT    = "../data/exercices/" . $infos["exoID"] . "/genExercice.py " . $infos["exoID"] . " question";
    $CHECK_OUTPUT_SCRIPT = "../data/exercices/" . $infos["exoID"] . "/genExercice.py " . $infos["exoID"] . " answer";
    $USER_SCRIPT         = "../data/users/user_" . $infos["userID"] . "/dummy.txt";
    $USER_OUT            = "../data/users/user_" . $infos["userID"] . "/out.txt";

    $CMD = $PYTHON_EXE ." ". $GEN_INPUT_SCRIPT ." | ". $PYTHON_EXE ." ". $USER_SCRIPT ." 2>>&1 | ". $PYTHON_EXE . " " . $CHECK_OUTPUT_SCRIPT ." > ". $USER_OUT;
  
    return $CMD;
}



//=============================================================================
// MAIN PROCESS
//=============================================================================

// init response
$res = initResultArray();

// Prepare system command
$sysCmd = getSystemCommand($res);

$res["message"] = $sysCmd;

// Call the system
$lastLine = system($sysCmd, $retVal);
if( $retVal === FALSE ){
    $res["result"] = "sysCmdExecError";
}
else{
    if( $retVal === 0 ){        
        $res["result"] = "sysCmdSuccess";
    }
    else{
        $res["result"] = "sysCmdFailure";
    }
    // Get out.txt message into error message
    $path = "../data/users/user_" . $res["userID"] ."/";
    $res["message"] = file_get_contents($path . "out.txt");    
}

// convert array to JSON format
$json = json_encode($res, JSON_FORCE_OBJECT);

// display JSON structure
echo $json;

?>