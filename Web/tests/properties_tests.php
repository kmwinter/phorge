<?php


class PropertiesTests extends UnitTestCase {

    public function testBasic() {
        $key = 'my.key';
        $value = 'my_value';
        $properties = new Properties();
        $properties->put($key, $value);
        
        $this->assertEqual($properties->get($key), $value);

    }


    public function testPatternMatching() {
        $key = 'my.key';
        $value = "my_value";
        $replaceValue = '${my.key}';
        $replaceKey = 'replace.me';

        $properties = new Properties();
        $properties->put($key, $value);
        $properties->put($replaceKey, $replaceValue);
        $this->assertEqual($properties->get($replaceKey), $value);
        
    }
}
?>
