<?php
/* Smarty version 3.1.30, created on 2019-07-11 08:58:35
  from "/usr/local/www/apache24/noexec/ttact-intern-software/views/main/Mitarbeiter/components/form_b.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5d26de1bd04079_03259880',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'b7a32fa604d72f4825350795a48ef5992b95adfd' => 
    array (
      0 => '/usr/local/www/apache24/noexec/ttact-intern-software/views/main/Mitarbeiter/components/form_b.tpl',
      1 => 1562525873,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5d26de1bd04079_03259880 (Smarty_Internal_Template $_smarty_tpl) {
?>
<div class="row">
    <div class="form-group col-12 col-sm-6">
        <label class="form-control-label" for="strasse">Straße</label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-map-marker fa-fw"></i></span>
            <input type="text" class="form-control" id="strasse" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['strasse'])===null||$tmp==='' ? '' : $tmp);?>
" readonly>
        </div>
    </div>
    <div class="form-group col-12 col-sm-6">
        <label class="form-control-label" for="hausnummer">Hausnummer</label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-map-marker fa-fw"></i></span>
            <input type="text" class="form-control" id="hausnummer" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['hausnummer'])===null||$tmp==='' ? '' : $tmp);?>
" readonly>
        </div>
    </div>
</div>
<div class="form-group">
    <label class="form-control-label" for="adresszusatz">Adresszusatz</label>
    <div class="input-group">
        <span class="input-group-addon"><i class="fa fa-map-marker fa-fw"></i></span>
        <input type="text" class="form-control" id="adresszusatz" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['adresszusatz'])===null||$tmp==='' ? '' : $tmp);?>
" readonly>
    </div>
</div>
<div class="row">
    <div class="form-group col-12 col-sm-6">
        <label class="form-control-label" for="postleitzahl">Postleitzahl</label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-map-marker fa-fw"></i></span>
            <input type="text" class="form-control" id="postleitzahl" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['postleitzahl'])===null||$tmp==='' ? '' : $tmp);?>
" readonly>
        </div>
    </div>
    <div class="form-group col-12 col-sm-6">
        <label class="form-control-label" for="ort">Ort</label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-map-marker fa-fw"></i></span>
            <input type="text" class="form-control" id="ort" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['ort'])===null||$tmp==='' ? '' : $tmp);?>
" readonly>
        </div>
    </div>
</div>

<div class="form-group">
    <label class="form-control-label" for="geburtsdatum">Geburtsdatum</label>
    <div class="input-group">
        <span class="input-group-addon"><i class="fa fa-map-marker fa-fw"></i></span>
        <input type="text" class="form-control" id="geburtsdatum" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['geburtsdatum'])===null||$tmp==='' ? '' : $tmp);?>
" readonly>
    </div>
</div>

<div class="row">
    <div class="form-group col-12 col-sm-6">
        <label class="form-control-label" for="iban">IBAN</label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-map-marker fa-fw"></i></span>
            <input type="text" class="form-control" id="iban" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['iban'])===null||$tmp==='' ? '' : $tmp);?>
" readonly>
        </div>
    </div>
    <div class="form-group col-12 col-sm-6">
        <label class="form-control-label" for="bic">BIC</label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-map-marker fa-fw"></i></span>
            <input type="text" class="form-control" id="bic" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['bic'])===null||$tmp==='' ? '' : $tmp);?>
" readonly>
        </div>
    </div>
</div><?php }
}
