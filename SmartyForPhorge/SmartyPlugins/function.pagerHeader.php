1<?php

function smarty_function_pagerHeader($params, &$smarty){
	
	//$moduleName = $params['module'];
	
	$pager = $params['pager'];
	if(! $pager instanceof Pager){
		throw new GeneralException("Pager param is not of type Pager in smarty function PagerHeader");
	}
	
	$response = new Response();
	$response->put('pager', $pager);
    $response->put('_view', $pager->getHeaderTemplate());
    Phorge::displayBlock('ViewerBlock', Phorge::getConfigProperty('smarty.module.name'), $response);
    /*
     * $template = $pager->getHeaderTemplate();
	$pSmarty = new PhorgedSmarty();	
	$pSmarty->assign(MODULE, $request->get(MODULE));
	$pSmarty->assign(ACTION, $request->get(ACTION));
	$pSmarty->assign(ID, $request->get(ID));
	$pSmarty->setTemplate('templates', Phorge::getConfigProperty('smarty.phorge.dir'));	
	$pSmarty->assign('pager', $pager);
	
	return $pSmarty->fetch($template);

     */
}


?>