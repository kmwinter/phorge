<?php /* Smarty version 2.6.17, created on 2008-11-03 09:03:42
         compiled from /Users/kwinters/projects/TestProject/Application/Views/DefaultError.tpl */ ?>
<b>Error</b>: <?php echo $this->_tpl_vars['exception']->getMessage(); ?>


<?php $_from = $this->_tpl_vars['exception']->getTrace(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['step']):
?>
    <div class="trace-item-detail">
		<?php echo $this->_tpl_vars['step']['file']; ?>
 :  <?php echo $this->_tpl_vars['step']['class']; ?>
 <?php echo $this->_tpl_vars['step']['type']; ?>
 <?php echo $this->_tpl_vars['step']['function']; ?>

            <b>line <?php echo $this->_tpl_vars['step']['line']; ?>
</b>
    </div>

<?php endforeach; endif; unset($_from); ?>