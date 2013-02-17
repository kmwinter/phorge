<?php
pminclude('phorge:core.interfaces.Block');
class ViewerBlock implements Block {

    public function generateResponse(Request $request, Response $response){
        
        $view = $response->get('_view');

        if(! $view){
            throw new Exception("View not defined for ViewerBlock");
        }

        return $view;
    }
}

?>
