<?php
//class meant to just absorb function calls. 
//only used when Pear::Log not available

pminclude('phorge:core.defaults.DefaultLogger');


class NullLogger extends DefaultLogger {

    private $logFile;


    public function log($message, $level){
        if(! is_writable($this->logFile))
            throw new Exception("Could not write to logfile $this->logFile");

        $message = $this->getFormattedMessage($message, $level);
        $fp = fopen($this->logFile, 'a');
        fwrite($fp, $message);
        fclose($fp);
    }

    public function getLogFile() {
        return $this->logFile;
    }

    public function setLogFile($logFile) {
        $this->logFile = $logFile;
    }



	
}

?>