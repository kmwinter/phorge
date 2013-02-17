<?php
pminclude('phorge:core.interfaces.Action');

class Welcome implements Action{

    private $externalMessage;

    public function doGet(Request $request, Response $response){

            $response->put('message', 'This message was set in the Action class ' . get_class($this));
            $response->put('externalMessage', $this->externalMessage);
            return 'bar/welcome';
    }


    public function doPost(Request $request, Response $response){
        $this->doGet($request, $response);
    }

    public function getExternalMessage() {
        return $this->externalMessage;
    }

    public function setExternalMessage($externalMessage) {
        $this->externalMessage = $externalMessage;
    }



}
?>