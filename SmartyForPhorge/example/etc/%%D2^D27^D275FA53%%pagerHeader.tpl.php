<?php /* Smarty version 2.6.20, created on 2008-11-03 18:54:34
         compiled from /www/lib/SmartyForPhorge//Views/pagerHeader.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'getUrl', '/www/lib/SmartyForPhorge//Views/pagerHeader.tpl', 15, false),)), $this); ?>


[page 
<?php $_from = $this->_tpl_vars['pager']->getPageNumbers(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['num'] => $this->_tpl_vars['link']):
?>
	<?php if ($this->_tpl_vars['link'] != ""): ?>
		<a class="small-link" href="<?php echo $this->_tpl_vars['link']; ?>
">
			<?php echo $this->_tpl_vars['num']; ?>

		</a>
	<?php else: ?>
		<span><?php echo $this->_tpl_vars['num']; ?>
</span>
	<?php endif; ?>
<?php endforeach; endif; unset($_from); ?>]

<?php if ($this->_tpl_vars['pager']->getPage() > 1): ?> 	
	[<a class="small-link" href="<?php echo smarty_function_getUrl(array('module' => $this->_tpl_vars['module'],'action' => $this->_tpl_vars['action'],'id' => $this->_tpl_vars['id'],'page' => $this->_tpl_vars['pager']->getPage()-1), $this);?>
">
		previous
	</a>]
<?php else: ?>
	[<span class="small">previous</span>]
<?php endif; ?>


<?php if ($this->_tpl_vars['pager']->getPage() < $this->_tpl_vars['pager']->numberOfPages() && $this->_tpl_vars['pager']->numberOfPages() > 1): ?> 	
	[<a class="small-link" href="<?php echo smarty_function_getUrl(array('module' => $this->_tpl_vars['module'],'action' => $this->_tpl_vars['action'],'id' => $this->_tpl_vars['id'],'page' => $this->_tpl_vars['pager']->getPage()+1), $this);?>
">
		next
	</a>]
<?php else: ?>
	[<span class="small">next</span>]	
<?php endif; ?>		    

<?php if ($this->_tpl_vars['pager']->getPage() != 1): ?> 	
	[<a class="small-link" href="<?php echo smarty_function_getUrl(array('module' => $this->_tpl_vars['module'],'action' => $this->_tpl_vars['action'],'id' => $this->_tpl_vars['id'],'page' => 1), $this);?>
">
		first
	</a>]
<?php else: ?>
	[<span class="small">first</span>]
<?php endif; ?>
<?php if ($this->_tpl_vars['pager']->getPage() != $this->_tpl_vars['pager']->numberOfPages() && $this->_tpl_vars['pager']->numberOfPages() > 1): ?> 	
	[<a class="small-link" href="<?php echo smarty_function_getUrl(array('module' => $this->_tpl_vars['module'],'action' => $this->_tpl_vars['action'],'id' => $this->_tpl_vars['id'],'page' => $this->_tpl_vars['pager']->numberOfPages()), $this);?>
">
		last
	</a>]
<?php else: ?>
	[<span class="small">last</span>]	
<?php endif; ?>	

<br><br>