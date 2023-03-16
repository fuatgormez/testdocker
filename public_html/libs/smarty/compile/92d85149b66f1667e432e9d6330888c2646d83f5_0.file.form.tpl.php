<?php
/* Smarty version 3.1.30, created on 2019-08-05 14:11:15
  from "/usr/local/www/apache24/noexec/ttact-intern-software/views/main/Tarife/components/form.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5d481ce30ebae1_83487977',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '92d85149b66f1667e432e9d6330888c2646d83f5' => 
    array (
      0 => '/usr/local/www/apache24/noexec/ttact-intern-software/views/main/Tarife/components/form.tpl',
      1 => 1562525873,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5d481ce30ebae1_83487977 (Smarty_Internal_Template $_smarty_tpl) {
?>

<div class="form-group">
    <div class="input-group">
        <span class="input-group-addon"><i class="fa fa-fw fa-tasks"></i></span>
        <input type="text" class="form-control" name="bezeichnung" placeholder="Beispieltarif" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['bezeichnung'])===null||$tmp==='' ? '' : $tmp);?>
" required>
    </div>
</div>

<button type="submit" class="btn btn-secondary form-control">Speichern</button><?php }
}
