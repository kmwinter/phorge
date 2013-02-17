<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UrlMappingBean.class
 *
 * @author kwinters
 */
class UrlMapper {
    
    
    private $mappings;
    
    
    public function getMappings() {
        return $this->mappings;
    }
        
    public function setMappings($mappings) {
        $this->mappings = $mappings;
    }
        

    
    public function resolveMapping($path){
        if(! is_array($this->mappings)){
            throw new Exception("Invalid mapping array");
        }

        foreach($this->mappings as $mappingPattern => $package){
            if($mappingPattern == '*'){
                return $package;
            }

            //must be start of URI?
            if(substr($mappingPattern, 0, 1) == '/'){
                $mappingPattern = "^$mappingPattern";
            }

            //must match through end of URI?            
            $lastChar = substr($mappingPattern, strlen($mappingPattern) - 1, 1);
            if( $lastChar != '*'){
                $mappingPattern = $mappingPattern . '$';
            }

            $mappingPattern = str_replace('*', '', $mappingPattern);
            $mappingPattern = str_replace('/', '\/', $mappingPattern);

            #echo "<br>pattern: $mappingPattern, subject: $path<br>";
            if(preg_match("/$mappingPattern/", $path)){
                return $package;
            }
            //echo "result: $result";
            #str_replace(strstr('*'), '', $mappingPattern);
            #$mappingPattern = str_replace('*', '');

            
        }
        
        throw new Exception("No mapping found for $path");
        
    }
    
    
}
?>
