<?php
/* Smarty version 3.1.30, created on 2019-07-11 08:58:35
  from "/usr/local/www/apache24/noexec/ttact-intern-software/views/main/Mitarbeiter/components/form_c.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5d26de1bd12947_64555521',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '7bb13cc0eff8b53bf7c36480a6781b686e254d16' => 
    array (
      0 => '/usr/local/www/apache24/noexec/ttact-intern-software/views/main/Mitarbeiter/components/form_c.tpl',
      1 => 1562525873,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5d26de1bd12947_64555521 (Smarty_Internal_Template $_smarty_tpl) {
?>
<div class="row">
    <div class="form-group col-12 col-sm-6">
        <label class="form-control-label" for="telefon1">Telefon 1</label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-phone fa-fw"></i></span>
            <input type="text" class="form-control" id="telefon1" name="telefon1" placeholder="030 123456 01" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['telefon1'])===null||$tmp==='' ? '' : $tmp);?>
">
        </div>
    </div>
    <div class="form-group col-12 col-sm-6">
        <label class="form-control-label" for="telefon2">Telefon 2</label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-phone fa-fw"></i></span>
            <input type="text" class="form-control" id="telefon2" name="telefon2" placeholder="030 123456 02" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['telefon2'])===null||$tmp==='' ? '' : $tmp);?>
">
        </div>
    </div>
</div>

<div class="form-group">
    <label class="form-control-label" for="emailadresse">E-Mail-Adresse</label>
    <div class="input-group">
        <span class="input-group-addon"><i class="fa fa-envelope fa-fw"></i></span>
        <input type="email" class="form-control" id="emailadresse" name="emailadresse" placeholder="beispiel@domain.de" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['emailadresse'])===null||$tmp==='' ? '' : $tmp);?>
">
    </div>
</div>
<button type="submit" class="btn btn-secondary form-control">Speichern</button><?php }
}
