<?php

/**
 * spring-like XML bean parsing for php IOC
 *
 * @author kwinters
 */

pminclude('ioc:BeanFactory');
pminclude('ioc:IOCParser');
pminclude('ioc:IocException');
pminclude('ioc:ClassNotFoundException');
define('IOC_APP_CONFIG_FILE', 'ioc.xml.file');
define('IOC_COMPONENT_ELEMENT', 'ioc.component.element');
define('IOC_ROOT_ELEMENT', 'ioc.root.element');
class IOCXMLParser implements IOCParser {

    const IOC_XML_FILE = '_xml_file';
    private $config;
    private $componentElement;
    private $rootElement;
    private $namespace;
    private $dom;
    private $nodeMap;
    private $componentIndex = 0;


    /**
     * Return a list of BeanFactories associated with provided XML sources
     * 
     * @return array<BeanFactory>
     */
    public function getBeanFactories(){
        
        //get the root element name [ioc] and component element [component] name from the 
        //Properties object 
        $root = $this->config->get(IOC_ROOT_ELEMENT);
        $element = $this->config->get(IOC_COMPONENT_ELEMENT);                
        $this->rootElement = $root;
        $this->componentElement= $element;

        //get xml file [list] from the Properties object
        $xmlFiles = $this->config->get(IOC_APP_CONFIG_FILE);
        
        if(empty($xmlFiles)){
            throw new IocException("IOC xml file not specified");
        }

        //parse a comma delimited list of xml files into an array
        if(!is_array($xmlFiles)){
            $xmlFiles = split(',', $xmlFiles);            
        }
        

        //combine array of xmlFiles into a single DomDocument
        $xml = $this->combineXmlFiles($xmlFiles);

        
        
        $this->dom = $xml;



        //query the combined DomDocument for all components
        $query = "/$root/$element";            
        $factories = $this->queryComponents($query);
        
        return $factories;
    }


    public function getComponentObject(BeanFactory $factory){
        
        $node = $this->nodeMap[$factory->getComponentIndex()];
        $object = $this->parseBean($node);
        //TODO log this for debugging purposes.
        return $object;
    }

 
    
    
    
    /**
     * Queries the provided domDocument and returns a list of matching
     * BeanFactories complete with instantiated objects.
     * 
     * @param String $query XpathQuery String
     * @param DomDocument $doc
     * @return array<BeanFactory> returns an array of BeanFactory objects 
     */
    private function queryComponents($query){
        
        $xpath = new DOMXPath($this->dom);
        $beanNodes = $xpath->query($query);
        
        $factories = array();
        foreach($beanNodes as $node){
            
            $id = $node->attributes->getNamedItem('id')->value;
            
            $class = $node->attributes->getNamedItem("class")->value;
            $scope = $node->attributes->getNamedItem("scope")->value;

            $factory = new BeanFactory();
            $factory->setId($id);
            $factory->setClass($class);
            $factory->setScope($scope);
            $factory->setParser($this);
            $this->componentIndex++;
            $factory->setComponentIndex($this->componentIndex);
            #$object = $this->parseBean($node, $doc, $factories);
            #$factory->setBean($object);
            
            $factories[] = $factory;
            $this->nodeMap[$this->componentIndex] = $node;

        }
        
        return $factories;
    
    }
    
    /**
     *  takes a <component> element and instantiates it as an object
     * 
     * @param DomElement $node
     * @param DomDocument $doc
     * @return Object rendered component 
     */
    private function parseBean($node){
        $class = $node->attributes->getNamedItem("class")->value;

        
        $objName = pminclude(($this->namespace? "$this->namespace:": '') . $class);
        $object = new $objName();
                  
         
        $propNodes = $node->childNodes;
        foreach($propNodes as $property){
          
            if($property->nodeName == 'property'){
                
                $name = $property->attributes->getNamedItem('name')->value;
                
                
                $ref = $property->attributes->getNamedItem('ref')->value;
                if($ref){
                     
                    //query xml document for referenced component
                    try {
                        $factory = $this->getFactoryById($ref);
                        $value = $factory->getBean();
                    }catch(Exception $e){
                        //TODO need to de-couple from Phorge Logger
                        Logger::error("Could not find a component with ref $ref");
                    }


                }else {
                 
                    $value = null;
                    if($property->hasChildNodes()){

                        foreach($property->childNodes as $child){

                            if($child->nodeName == 'bean' ||
                                $child->nodeName == 'map' ||
                                $child->nodeName == 'list' ||
                                $child->nodeName == '#text'){

                                $value = $this->parseElement($child);

                                if(is_scalar($value)){
                                    if(trim($value)){
                                        break;
                                    }
                                }else {
                                    break;
                                }
                            }
                        }
                    }else {
                        $value = $property->attributes->getNamedItem('value')->value;
                    }
                }
                
                 
                 
                $methodName = "set" . ucfirst($name);
                
                #if(method_exists($objName, $methodName)){
                if(is_callable(array($objName, $methodName))){
                    #echo "calling setter $methodName on object $objName<br/>";
                    $object->$methodName($value);
                }else {
                    throw new Exception("IOC XML Parser error: method $methodName does not exist for object $objName");
                }
            }         
        }
        
        return $object;
    }


    /**
     * combine all xml configuration files specified in array into a single DomDocument
     *
     * @param array $xmlFileList
     * @return DomDocument
     */
    private function combineXmlFiles($xmlFileList){
        
        $combined = new DomDocument();
        $root = $combined->createElement($this->rootElement);

        foreach($xmlFileList as $filePath){
            $filePath = trim($filePath);


            if(! file_exists($filePath)){
                throw new IocException("$filePath is an invalid xml file");
            }

            $xml = file_get_contents($filePath);

            //load this particular xml file
            $doc = new DomDocument();
            $doc->loadXML($this->searchAndReplace($xml, $this->config));

            
            //find all components
            $xpath = new DOMXPath($doc);
            $query = "/$this->rootElement/$this->componentElement";

            $beanNodes = $xpath->query($query);

            //loop through components and add them to the combined domDocument
            foreach($beanNodes as $node){
                $imported = $combined->importNode($node, true);
                $root->appendChild($imported);
            }

        }

        $combined->appendChild($root);

        return $combined;
    }



    /**
     * returns a single BeanFactory object represented by the ID param.
     * An exception is thrown if more than a single component is found
     * of the provided class or if none is found.
     *
     * @param String $id
     * @param DomDocument $doc
     * @return BeanFactory
     */
    private function getFactoryById($id){

        $query = "/$this->rootElement/$this->componentElement[@id='$id']";

        //can't find a resource that's not it its own dom
        $factories = $this->queryComponents($query);


        if(count($factories) < 1){
            throw new IocException("No beans found with identifier $id");
        }


        if(count($factories) > 1){
            throw new IocException("Muliple results found with identifier ($id)");
        }


        return $factories[0];

    }

    /**
     * returns a single BeanFactory object associated with the provided class
     * param. An exception is thrown if more than a single component is found
     * of the provided class or if none is found.
     *
     * @param String $class
     * @param DomDocument $doc
     * @return BeanFactory
     */
    private function getFactoryByClass($class){
        $query = "/$this->rootElement/$this->componentElement[@class='$class']";
        $factories = $this->queryComponents($query);


        if(count($factories) < 1){
            throw new ClassNotFoundException("No beans found of class $class");
        }


        if(count($factories) > 1){
            throw new IocException("Muliple results found of class $class");
        }


        return $factories[0];
    }


    
    /**
     * transform a domDocument <list>/<map> element into an associative array
     *
     * 
     * @param DomElement $node
     * @return array
     */
    private function parseList($node){
        $map = array();
        
        foreach($node->childNodes as $entry){
            //only accept 'entry' nodes
            if($entry->nodeName == 'entry'){
                //get (optional) entry id
                $id = $entry->attributes->getNamedItem('id')->value;
                //parse children looking for value
                if($entry->hasChildNodes()){
                    foreach($entry->childNodes as $child){
                        //take first bean, map or list value (filter out empty text)
                        if($child->nodeName == 'bean' ||
                            $child->nodeName == 'map' ||
                            $child->nodeName == 'list' ||
                            $child->nodeName == '#text'){
                          
                            $value = $this->parseElement($child);

                            if(is_scalar($value)){
                                if( trim($value)){
                                    break;
                                }
                            }else {
                                break;
                            }
                          
                        }                        
                    }
                }else {
                    //no children, get value from attribute
                    $value = $entry->attributes->getNamedItem('value')->value;
                }

                if($id){
                    $map[$id] = $value;
                }else{
                    $map[] = $value;
                }
            }
        }

        return $map;
    }
    
        

    /**
     * takes a DomElement, determines its type and then returns
     * the appropriate rendered result
     * 
     * @param DomElement $node
     * @param DomDocument $doc
     * @return Mixed result 
     * 
     */
    private function parseElement($node){
    
        $name = $node->nodeName   ;
    
        switch($name){
            case 'bean': return $this->parseBean($node);
            //case 'property': return $this->parseProperty($node);
            case 'map' : return $this->parseList($node);
            case 'list' : return $this->parseList($node);
            case '#text': return $node->nodeValue;        
            default: throw new Exception("'$name' is not a valid element");
        }
    
    }
  
  
    /**
     * parse <property> xml element into a Property object
     *
     * @deprecated
     * @param DomElement $node
     * @param DomDocument $doc
     * @return Property 
     */
    private function parseProperty($node){
        $property = new Property();
        $name = $node->attributes->getNamedItem('name');

        //echo "<br>--property name: $name->value";
    
        $property->setName($name->value);

        $value = array();
        if($node->hasChildNodes()){
            //echo ' has child nodes ';
            foreach($node->childNodes as $child){
                //take first bean, map or list value            
                if($child->nodeName == 'bean' ||
                    $child->nodeName == 'map' ||
                    $child->nodeName == 'list' ||
                    $child->nodeName == '#text'){
              
                    $property->setValue($this->parseElement($child));
                    break;
                }
            }

        }else {
      
            $value = $node->attributes->getNamedItem('value');
            $property->setValue($value->value);
  
        }

        return $property;
    
    }


    /**
     * do java-like properties value replacements. ${property.name} is
     * replaced by the value of the 'property.name' property
     * 
     * @param String XML string value
     * @param Properties properties object source
     * @return String parsed XML output
     *
     */
    private function searchAndReplace($xml, $config){
        #
        preg_match_all('/\${([a-zA-Z0-9._-]+)}*/', $xml, $matches );
        
        foreach($matches[1] as $match){
            //don't allow process to replace itself ??
            
            $xml = str_replace('${' . $match . '}', $config->get($match), $xml);
        }
        return $xml;
    }
    
    
    
    public function setNamespace($namespace){
        $this->namespace = $namespace;
    }

    public function setConfigProperties(Properties $properties){


        $this->config = $properties;
    }

  
}

?>
