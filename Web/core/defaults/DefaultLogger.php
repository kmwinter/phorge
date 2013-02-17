<?php

pminclude('phorge:core.interfaces.PhorgeLogger');




class DefaultLogger implements PhorgeLogger {
	
	
	public $levels;

	protected $messages = array();
	protected $minimumLevel = 'WARN';
	protected $dateFormat = 'r';
        protected $className;
	
	
	public function __construct($className = null){
            #$this->levels = new enum('TRACE', 'DEBUG', 'NOTICE', 'WARN', 'ERROR');
            $this->levels = array('TRACE', 'DEBUG', 'NOTICE', 'WARN', 'ERROR');

            $this->className = $className;

	}



        protected function findkey($label){
            
            $key =  array_keys($this->levels, $label);
            if(! $key){
                throw new Exception("Log level $label not found");
            }

            return $key[0];
        }

        protected function checkedLog($testLevel, $message){
            
            
            if($this->findkey($testLevel) >= $this->findkey($this->minimumLevel)){
                
                $this->log($message, $testLevel);
            }

            return false;

        }

        protected function createMessage($message, $level){
            $time = microtime(true);
            $message = array('time'=>$time, 'message'=>'[' . $level . "] $message");
            $this->messages[] = $message;
        }

        protected function getFormattedMessage($message, $level){
            $messageArray = $this->createMessage($message, $level);
            return $this->transformTime($messageArray['time']) . " -- [$level] -- " . $messageArray['message'];

        }


        public function getDateFormat() {
            return $this->dateFormat;
        }

        public function setDateFormat($dateFormat) {
            $this->dateFormat = $dateFormat;
        }

        public function getLevel() {
            return $this->minimumLevel;
        }

        public function setLevel($level) {

            if(! in_array($level, $this->levels)){
                throw  new Exception("Invalid log level: $level");
            }

            $this->minimumLevel = $level;
        }




	public function trace($message){
            $this->checkedLog('TRACE', $message);
            
	}
	
	public function debug($message){					
            $this->checkedLog('DEBUG', $message);
	}
	
	public function notice ($message){
            $this->checkedLog('NOTICE', $message);
	}
	
	public function warn($message){
            $this->checkedLog('WARN', $message);
	}
	
	public function error($message){
            $this->checkedLog('ERROR', $message);
	}

        

	public function log($message, $level){
            $this->createMessage($message, $level);
	}
	
	public function getMessages(){
		return $this->messages;
	}
	
	private function transformTime($time){
		return date($this->dateFormat, $time);
	}
	
	public function getLogOutput($newLine = "\n"){		
            $output = "$newLine";
            foreach($this->messages as $index => $messageArray){

                $time = $messageArray['time'];
                $message = $messageArray['message'];
                $output .= $this->transformTime($time) . " $message" . "$newLine";

            }
            return $output;
	}
	
}

?>