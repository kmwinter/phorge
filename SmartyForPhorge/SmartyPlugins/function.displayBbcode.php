<?php

pminclude('sfp:bbcode.*');

$customDir = Phorge::getConfigProperty('custom.dir');
//require_once($customDir . '/classes/stringparser.class.php');
//require_once($customDir . '/classes/stringparser_bbcode.class.php');


// Unify line breaks of different operating systems
function convertlinebreaks ($text) {
	
	return preg_replace ("/\015\012|\015|\012/", "\n", $text);
}

// Remove everything but the newline charachter
function bbcode_stripcontents ($text) {
    return preg_replace ("/[^\n]/", '', $text);
}

function do_bbcode_url ($action, $attributes, $content, $params, $node_object) {
    if ($action == 'validate') {
        return true;
    }
    if (!isset ($attributes['default'])) {
        return '<a href="'.htmlspecialchars ($content).'">'.htmlspecialchars ($content).'</a>';
    }
    return '<a href="'.htmlspecialchars ($attributes['default']).'">'.$content.'</a>';
}


// Function to include images
function do_bbcode_img ($action, $attributes, $content, $params, $node_object) {
    if ($action == 'validate') {
        return true;
    }
    return '<img src="'.htmlspecialchars($content).'" alt="">';
}



function smarty_function_displayBbcode($params, &$smarty){
		
	$text = $params['text'];
	$assign = $params['var'];
	
	
	$bbcode = new StringParser_BBCode ();
	$bbcode->addFilter (STRINGPARSER_FILTER_PRE, 'convertlinebreaks');
	
	$bbcode->addParser (array ('block', 'inline', 'link', 'listitem'), 'htmlspecialchars');
	$bbcode->addParser (array ('block', 'inline', 'link', 'listitem'), 'nl2br');
	$bbcode->addParser ('list', 'bbcode_stripcontents');
	
	$bbcode->addCode ('b', 'simple_replace', null, array ('start_tag' => '<b>', 'end_tag' => '</b>'),
	                  'inline', array ('listitem', 'block', 'inline', 'link'), array ());
	$bbcode->addCode ('i', 'simple_replace', null, array ('start_tag' => '<i>', 'end_tag' => '</i>'),
	                  'inline', array ('listitem', 'block', 'inline', 'link'), array ());
	$bbcode->addCode ('url', 'usecontent?', 'do_bbcode_url', array ('usecontent_param' => 'default'),
	                  'link', array ('listitem', 'block', 'inline'), array ('link'));
	$bbcode->addCode ('link', 'callback_replace_single', 'do_bbcode_url', array (),
	                  'link', array ('listitem', 'block', 'inline'), array ('link'));
	$bbcode->addCode ('img', 'usecontent', 'do_bbcode_img', array (),
	                  'image', array ('listitem', 'block', 'inline', 'link'), array ());
	$bbcode->addCode ('bild', 'usecontent', 'do_bbcode_img', array (),
	                  'image', array ('listitem', 'block', 'inline', 'link'), array ());
	$bbcode->setOccurrenceType ('img', 'image');
	$bbcode->setOccurrenceType ('bild', 'image');
	$bbcode->setMaxOccurrences ('image', 2);
	$bbcode->addCode ('list', 'simple_replace', null, array ('start_tag' => '<ul>', 'end_tag' => '</ul>'),
	                  'list', array ('block', 'listitem'), array ());
	$bbcode->addCode ('*', 'simple_replace', null, array ('start_tag' => '<li>', 'end_tag' => '</li>'),
	                  'listitem', array ('list'), array ());
	$bbcode->setCodeFlag ('*', 'closetag', BBCODE_CLOSETAG_OPTIONAL);
	$bbcode->setCodeFlag ('*', 'paragraphs', true);
	$bbcode->setCodeFlag ('list', 'paragraph_type', BBCODE_PARAGRAPH_BLOCK_ELEMENT);
	$bbcode->setCodeFlag ('list', 'opentag.before.newline', BBCODE_NEWLINE_DROP);
	$bbcode->setCodeFlag ('list', 'closetag.before.newline', BBCODE_NEWLINE_DROP);
	$bbcode->setRootParagraphHandling (true);
	
	#$text = '[b]This is a test![/b]';
	
	if(! empty($assign)){
		$smarty->assign($assign, $bbcode->parse ($text));
		return false;
	}
	
	return $bbcode->parse($text);
}

?>