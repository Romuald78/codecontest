<?php


class CmdSystem {
    
    public static function execute($cmd, &$ret)
    {
        // Call system command
        $ll = system( "mv ". $path . USER_CODE_FILENAME ." ". $path . USER_CODE_FILENAME_C, $retVal );
        // give execution result to upper layer (reference)
        $ret = $retVal;
        // return last line
        return $ll;
    }

    
}



?>