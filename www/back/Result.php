<?php


class Result {
    //==========================================================================
    // PRIVATE PROPERTIES / GETTERS / SETTERS
    //==========================================================================
    // Init values
    private $result  = "";
    private $userID  = "";
    private $exoID   = "";
    private $langID  = "";
    private $message = "";
    // Setters
    private function setResult($v){
        $this->result = $v;
    }
    private function setUserID($v){
        $this->userID = $v;
    }
    private function setExoID($v){
        $this->exoID = $v;
    }
    private function setLangID($v){
        $this->langID = $v;
    }
    private function setMessage($v){
        $this->message = $v;
    }
    // Getters
    private function getResult(){
        return $this->result;
    }
    private function getUserID(){
        return $this->userID;
    }
    private function getExoID(){
        return $this->exoID;
    }
    private function getLangID(){
        return $this->langID;
    }
    private function getMessage(){
        return $this->message;
    }

    
    //==========================================================================
    // CONSTRUCTOR
    //==========================================================================
    //--------------------------------------------------------------
    public function __construct() {
        // Check parameters
        $this->checkParams();
    }
    
    
    //==========================================================================
    // PRIVATE METHODS
    //==========================================================================
    //--------------------------------------------------------------
    // check the current object is still valid
    private function isValidResult(){
        return ( $this->getResult() == RES_CHECKEXO_VALID_PARAMS );
    }
    
    //--------------------------------------------------------------
    // get user folder path
    private function getUserPath(){
        return "../data/users/user_" . $this->getUserID() ."/";
    }
    
    //--------------------------------------------------------------
    // Check parameters from form, and set result according to
    private function checkParams() {
        // Init result to 'invalid'
        $this->setResult(RES_CHECKEXO_INVALID_PARAMS);
        // Check user ID presence
        if( !isset($_POST[FORM_CHECKEXO_USER_ID]) ){
            $this->setResult(RES_CHECKEXO_MISS_USER_ID);
        }
        // Check exercice ID presence
        else if( !isset($_POST[FORM_CHECKEXO_EXO_ID]) ){
            $this->setResult(RES_CHECKEXO_MISS_EXO_ID);
        }
        // Check programming language ID presence
        else if( !isset($_POST[FORM_CHECKEXO_LANG_ID]) ){
            $this->setResult(RES_CHECKEXO_MISS_LANG_ID);
        }
        // Check user code presence
        else if( !isset($_POST[FORM_CHECKEXO_CODE_TEXT]) ){
            $this->setResult(RES_CHECKEXO_MISS_CODE_TEXT);
        }
        // Check userID is not empty and is a number
        else if( !ctype_digit($_POST[FORM_CHECKEXO_USER_ID]) || ($_POST[FORM_CHECKEXO_USER_ID] == "") ){
            $this->setResult(RES_CHECKEXO_BAD_USER_ID);
        }
        // Check exercice ID is not empty
        else if( $_POST[FORM_CHECKEXO_EXO_ID] == "" ){
            $this->setResult(RES_CHECKEXO_BAD_EXO_ID);
        }
        // Check language ID is a correct one
        else {
            // Check language ID is in the list
            switch($_POST[FORM_CHECKEXO_LANG_ID]){
                case FORM_LANG_ID_C:
                case FORM_LANG_ID_JAVA:
                case FORM_LANG_ID_JS:
                case FORM_LANG_ID_PHP:
                case FORM_LANG_ID_PYTHON:
                    $this->setResult(RES_CHECKEXO_VALID_PARAMS);
                    break;
                default :
                    $this->setResult(RES_CHECKEXO_BAD_LANG_ID);
                    break;
            }        
        }        
        // end of method : here we have checked the input parameters are present and may be correct
        // store all input from form
        if( $this->isValidResult() ){
            $this->setUserID($_POST[FORM_CHECKEXO_USER_ID]);
            $this->setExoID ($_POST[FORM_CHECKEXO_EXO_ID ]);
            $this->setLangID($_POST[FORM_CHECKEXO_LANG_ID]);
        }
    }

    //--------------------------------------------------------------
    // Create all the folders and files needed to perform operations
    // This will copy the user code into a dummy.txt file
    private function createDiskEnvironment() {
        // Create user folder
        if( $this->isValidResult() ){
            // Prepare user folder
            $path = $this->getUserPath();
            // Create user folder
            if ( file_exists($path) ){
                // If the file is NOT A FOLDER, that means there is a probleme because we cannot create a folder with its name
                if( !is_dir($path) ){
                    $this->setResult(RES_CHECKEXO_USER_DIR_IN_USE);
                }
            }
            else{
                if( mkdir($path) === FALSE ){
                    $this->setResult(RES_CHECKEXO_USER_DIR_CREATE_ERR);
                }            
            }
        }
        // Create user file
        if( $this->isValidResult() ){
            // (re)create user file
            $fp = fopen($path. USER_CODE_FILENAME, "w");
            if ($fp === FALSE){
                $this->setResult(RES_CHECKEXO_USER_FILE_CREATE_ERR);
            }    
            else{
                // Copy user code to the user folder
                if( fwrite($fp, $_POST[FORM_CHECKEXO_CODE_TEXT]) === FALSE ){
                    $this->setResult(RES_CHECKEXO_USER_FILE_WRITE_ERR);
                }
                // Close user file access
                fclose($fp);
            }
        }
    }

    //--------------------------------------------------------------
    // Compile user code if needed
    private function compileUserCode(){
        // check if this object is still valid
        if( $this->isValidResult() ){
            // Prepare user path
            $path = $this->getUserPath();
            // Check which compilation must be performed
            switch( $this->getLangID() ){
                
                //- - - - - - - - - - - - - - - - - - - - - 
                // Interpreted languages
                // Nothing to do with interpreted languages
                //- - - - - - - - - - - - - - - - - - - - - 
                case FORM_LANG_ID_JS:
                case FORM_LANG_ID_PHP:
                case FORM_LANG_ID_PYTHON:
                    break;
                
                //- - - - - - - - - - - - - - - - - - - - - 
                // C compilation
                //- - - - - - - - - - - - - - - - - - - - - 
                case FORM_LANG_ID_C:
                    // Prepare C compilation (rename user code file)
                    $ll = system( "mv ". $path . USER_CODE_FILENAME ." ". $path . USER_CODE_FILENAME_C, $retVal );
                    if( $retVal === FALSE ){
                        $this->setResult(RES_CHECKEXO_SYSCMD_COMPILE_ERR);
                    }
                    else{
                        if( $retVal === 0 ){        
                            // Compile the user code
                            $ll2 = system( "gcc ". $path . USER_CODE_FILENAME_C ." -o ". $path . USER_CODE_EXECUTABLE_C, $retVal2 );
                            if( $retVal2 === FALSE ){
                                $this->setResult(RES_CHECKEXO_SYSCMD_COMPILE_ERR);
                            }
                            else{
                                if( $retVal2 === 0 ){        
                                    // OK just do nothing for the moment : executable is ready
                                }
                                else{
                                    $this->setResult(RES_CHECKEXO_SYSCMD_COMPILE_ERR); 
                                }
                            }
                        }
                        else{
                            $this->setResult(RES_CHECKEXO_SYSCMD_FAIL);
                        }
                    }
                    break;

                //- - - - - - - - - - - - - - - - - - - - - 
                // Java compilation
                //- - - - - - - - - - - - - - - - - - - - - 
                case FORM_LANG_ID_JAVA:
                    // Prepare C compilation (rename user code file)
                    $ll = system( "mv ". $path . USER_CODE_FILENAME ." ". $path . USER_CODE_FILENAME_JAVA, $retVal );
                    if( $retVal === FALSE ){
                        $this->setResult(RES_CHECKEXO_SYSCMD_COMPILE_ERR);
                    }
                    else{
                        if( $retVal === 0 ){        
                            // Compile the user code
                            $ll2 = system( "javac ". $path . USER_CODE_FILENAME_JAVA , $retVal2 );
                            if( $retVal2 === FALSE ){
                                $this->setResult(RES_CHECKEXO_SYSCMD_COMPILE_ERR);
                            }
                            else{
                                if( $retVal2 === 0 ){        
                                    // OK just do nothing for the moment : executable is ready
                                }
                                else{
                                    $this->setResult(RES_CHECKEXO_SYSCMD_COMPILE_ERR); 
                                }
                            }
                        }
                        else{
                            $this->setResult(RES_CHECKEXO_SYSCMD_FAIL);
                        }
                    }
                    break;

                //- - - - - - - - - - - - - - - - - - - - - 
                // Bad programming language ID
                //- - - - - - - - - - - - - - - - - - - - - 
                default :
                    $this->setResult(RES_CHECKEXO_BAD_LANG_ID);
                    break;
            }
        }
    }

    
    //--------------------------------------------------------------
    // Execute user code
    private function executeUserCode(){
        // Prepare language command
        $langCmd = "";
        // Prepare user path
        $path = $this->getUserPath();
        // check if this object is still valid
        if( $this->isValidResult() ){
            // check which language has been used and build command according to            
            switch( $this->getLangID() ){
                case FORM_LANG_ID_JS:
                    $langCmd = "node " . $path . USER_CODE_FILENAME;    
                    break;
                case FORM_LANG_ID_PHP:
                    $langCmd = "php " . $path . USER_CODE_FILENAME;    
                    break;
                case FORM_LANG_ID_PYTHON:
                    $langCmd = "python3 " . $path . USER_CODE_FILENAME;    
                    break;
                case FORM_LANG_ID_C:
                    $langCmd = $path . USER_CODE_EXECUTABLE_C;
                    break;
                case FORM_LANG_ID_JAVA:
                    $langCmd = "java -cp ". $path ." ". USER_CODE_EXECUTABLE_JAVA;    
                    break;
                // Bad programming language ID
                default :
                    $this->setResult(RES_CHECKEXO_BAD_LANG_ID);
                    break;
            }            
        }
        // Prepare full command according to specific command
        $PYTHON_EXE   = "python3";
        $GEN_INPUT    = "../data/exercices/" . $this->getExoID() . "/genExercice.py " . $this->getExoID() . " question";
        $CHECK_OUTPUT = "../data/exercices/" . $this->getExoID() . "/genExercice.py " . $this->getExoID() . " answer";
        $USER_SCRIPT  = $langCmd;
        $USER_OUT     = $path . USER_CODE_OUT;
        $CMD = $PYTHON_EXE ." ". $GEN_INPUT ." | ". $USER_SCRIPT ." 2>&1 | ". $PYTHON_EXE . " " . $CHECK_OUTPUT ." > ". $USER_OUT;

        // Execute if current object is still valid
        if( $this->isValidResult() ){
            
            // Store command into the message
            $this->setMessage($CMD);

            // Call the system command
            $lastLine = system($CMD, $retVal);
            if( $retVal === FALSE ){
                $this->setResult(RES_CHECKEXO_SYSCMD_EXEC_ERR);
            }
            else{
                if( $retVal === 0 ){        
                    $this->setResult(RES_CHECKEXO_SYSCMD_SUCCESS);
                }
                else{
                    $this->setResult(RES_CHECKEXO_SYSCMD_FAIL);
                }
                // Get out.txt message into error message
                $this->setMessage( file_get_contents($path . USER_CODE_OUT) ); 
            }
        }
    }



    
    
    //==========================================================================
    // PUBLIC METHODS
    //==========================================================================
    //--------------------------------------------------------------
    // This method will perform ALL the operations needed to test
    // the provided user code. If an error occurs at anytime,
    // the current object properties will be modified according to.
    public function processUserCode(){
        // Create hard disk environment
        $this->createDiskEnvironment();
        // Compile user code if needed
        $this->compileUserCode();
        // Execute user code
        $this->executeUserCode();
    }
    
    
    //==========================================================================
    // TOSTRING METHOD (JSON)
    //==========================================================================

    //--------------------------------------------------------------
    // Returns a json string from the current object
    public function __toString() {
        // Create array from properties
        $tab = Array();
        $tab[JSON_CHECKEXO_RESULT ] = $this->result;
        $tab[JSON_CHECKEXO_USER_ID] = $this->userID;
        $tab[JSON_CHECKEXO_EXO_ID ] = $this->exoID;
        $tab[JSON_CHECKEXO_LANG_ID] = $this->langID;
        $tab[JSON_CHECKEXO_MESSAGE] = $this->message;
        // convert array to json
        $out = json_encode($tab, JSON_FORCE_OBJECT);
        // return json string
        return $out;
    }
    
    
    
}



?>