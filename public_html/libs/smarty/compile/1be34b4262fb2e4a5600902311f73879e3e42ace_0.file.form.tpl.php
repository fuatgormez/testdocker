<?php
/* Smarty version 3.1.30, created on 2019-07-11 11:36:32
  from "/usr/local/www/apache24/noexec/ttact-intern-software/views/main/Lohnkonfiguration/components/form.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5d270320e9a388_71528929',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '1be34b4262fb2e4a5600902311f73879e3e42ace' => 
    array (
      0 => '/usr/local/www/apache24/noexec/ttact-intern-software/views/main/Lohnkonfiguration/components/form.tpl',
      1 => 1562525873,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5d270320e9a388_71528929 (Smarty_Internal_Template $_smarty_tpl) {
?>
<div class="row">
    <div class="form-group col-12<?php if ($_smarty_tpl->tpl_vars['smarty_vars']->value['company'] != 'tps') {?> col-sm-6<?php }?>">
        <label class="form-control-label" for="gueltig_ab">Gültig ab <span class="required">*</span></label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-calendar fa-fw"></i></span>
            <input name="gueltig_ab" type="text" class="form-control" id="gueltig_ab" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['gueltig_ab'])===null||$tmp==='' ? '' : $tmp);?>
" placeholder="TT.MM.JJJJ">
        </div>
    </div>
    <?php if ($_smarty_tpl->tpl_vars['smarty_vars']->value['company'] != 'tps') {?>
        <div class="form-group col-12 col-sm-6">
            <label class="form-control-label" for="tarif">Tarif <span class="required">*</span></label>
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-calculator fa-fw"></i></span>
                <select name="tarif" class="form-control" id="tarif">
                    <option value="">kein Tarif</option>
                    <?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['tarifliste'])) {?>
                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['smarty_vars']->value['tarifliste'], 'row');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['row']->value) {
?>
                            <option value="<?php echo $_smarty_tpl->tpl_vars['row']->value['id'];?>
"<?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['tarif'])) {
if ($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['tarif'] == $_smarty_tpl->tpl_vars['row']->value['id']) {?> selected<?php }
}?>><?php echo $_smarty_tpl->tpl_vars['row']->value['bezeichnung'];?>
</option>
                        <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

                    <?php }?>
                </select>
            </div>
        </div>
    <?php }?>
</div>
<div class="row">
    <div class="form-group col-12 col-sm-6">
        <label class="form-control-label" for="wochenstunden">Wochenstunden</label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-clock-o fa-fw"></i></span>
            <input name="wochenstunden" type="text" class="form-control" id="wochenstunden" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['wochenstunden'])===null||$tmp==='' ? '' : $tmp);?>
" placeholder="z.B. 35 oder 11.5">
        </div>
    </div>
    <div class="form-group col-12 col-sm-6">
        <label class="form-control-label" for="lohn">Gesamtlohn/Std<?php if ($_smarty_tpl->tpl_vars['smarty_vars']->value['company'] == 'tps') {?> <span class="required">*</span><?php }?></label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-eur fa-fw"></i></span>
            <input name="lohn" type="text" class="form-control" id="lohn" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['lohn'])===null||$tmp==='' ? '' : $tmp);?>
" placeholder="z.B. 9 oder 10.07">
        </div>
    </div>
</div>
<button type="submit" class="btn btn-secondary form-control">Speichern</button><?php }
}
