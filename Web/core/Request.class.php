<?php

/**
 * This class is an object representation of the $_REQUEST superglobal. It holds all
 * request parameters as well as the meta data held in the $_SERVER superglobal.
 *
 * This class is also responsible for determining if the current request and the former
 * request were identical (browser refresh).
 *
 *
 */
pminclude('lib:HashMap.HashMap');
class Request extends HashMap {


    static $instances;
    const PREVIOUS_POST = '_previous_post';
    const GET = '_get';
    const POST = '_post';
    const SERVER = '_server';

    private $keyList = array();
    private $isRepost = false;
    private $method;

    public function __construct() {

        Request::$instances = Request::$instances + 1;

        $previousPost = null;
        if(is_array($_SESSION)) {
            if( key_exists(self::PREVIOUS_POST, $_SESSION)) {
                $previousPost = $_SESSION[self::PREVIOUS_POST];
            }
        }
        #$this->put('repost', $previousPost == $_POST);
        $this->isRepost = $previousPost == $_POST;


        //if more than one Request instance is created, this prevents
        //the prevous post from getting messed up
        if(Request::$instances == 1) {
            $_SESSION[self::PREVIOUS_POST] = $_POST;
        }

        $this->method = $_SERVER['REQUEST_METHOD'];

        $this->keyList[self::GET] = array_keys($_GET);
        $this->keyList[self::POST] = array_keys($_POST);
        $this->keyList[self::SERVER] = array_keys($_SERVER);


        if(strtoupper($this->getMethod()) == 'POST') {
            $this->addProperties($_POST);
        }

        $this->addProperties($_GET);
        $this->addProperties($_SERVER);


    }

    private function addProperties($array) {
        foreach($array as $key => $value) {

            $this->put($key, stripslashes($value));
        }
    }

    public function getMethod() {
        return $this->method;
    }

    public function setMethod($method) {
        $this->method = $method;
    }

    public function isRepost() {
        return $this->isRepost;
    }


    public function getGetProperties() {
        return $this->getSubset(self::GET);
    }

    public function getPostProperties() {
        return $this->getSubset(self::POST);
    }

    public function getServerProperties() {
        return $this->getSubset(self::SERVER);
    }

    private function getSubset($type) {
        $array = array();
        foreach($this->keyList[$type] as $key) {
            $array[$key] = parent::get($key);
        }

        return $array;
    }



}

?>