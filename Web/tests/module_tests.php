<?php


class ModuleTests extends UnitTestCase {

    public function testDefaultModule() {
        $module = Phorge::getModule();
        $this->assertEqual($module->getDirectory(), FRAMEWORK_ROOT . '/examples');
        
        $actionDispatcher = $module->getActionDispatcher();
        $class = get_class($actionDispatcher);
        $this->assertEqual($class, 'SimpleActionDispatcher');

        $this->assertEqual($actionDispatcher->getPrefix(), 'Actions');

        $viewDispatcher = $module->getViewDispatcher();
        $class = get_class($viewDispatcher);
        $this->assertEqual($class, 'SimpleViewDispatcher');

        $moduleRoot = $module->getDirectory();
        $this->assertTrue(in_array("$moduleRoot/Views", $viewDispatcher->getViewDirectory()));
        
        $this->assertEqual($viewDispatcher->getAppendValue(),
                            Phorge::getConfigProperty(DEFAULT_VIEW_EXTENSION));


        $response = new Response();
        $request = Phorge::getRequest();
		$request->setMethod('GET');
        $modelAndView = $module->getActionOutput($request, $response, 'Welcome');
        $result = $viewDispatcher->getViewOutput($modelAndView);        
        $this->assertEqual($modelAndView->getView(), 'bar/welcome');



    }


   
}
?>
