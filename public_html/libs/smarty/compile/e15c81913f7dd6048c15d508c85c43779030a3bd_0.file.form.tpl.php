<?php
/* Smarty version 3.1.30, created on 2019-10-10 13:12:53
  from "/usr/local/www/apache24/noexec/ttact-intern-software/views/main/Tariflohnbetrag/components/form.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5d9f1235ebf3a6_78707214',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'e15c81913f7dd6048c15d508c85c43779030a3bd' => 
    array (
      0 => '/usr/local/www/apache24/noexec/ttact-intern-software/views/main/Tariflohnbetrag/components/form.tpl',
      1 => 1562525873,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5d9f1235ebf3a6_78707214 (Smarty_Internal_Template $_smarty_tpl) {
?>

<div class="row">
    <div class="form-group col-12 col-sm-6">
        <label class="form-control-label" for="gueltig_ab">Gültig ab <span class="required">*</span></label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-calendar fa-fw"></i></span>
            <input name="gueltig_ab" type="text" class="form-control" id="gueltig_ab" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['gueltig_ab'])===null||$tmp==='' ? '' : $tmp);?>
" placeholder="TT.MM.JJJJ">
        </div>
    </div>
    <div class="form-group col-12 col-sm-6">
        <label class="form-control-label" for="lohn">Stundenlohn</label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-eur fa-fw"></i></span>
            <input name="lohn" type="text" class="form-control" id="lohn" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['lohn'])===null||$tmp==='' ? '' : $tmp);?>
" placeholder="z.B. 9 oder 10.07">
        </div>
    </div>
</div>
<button type="submit" class="btn btn-secondary form-control">Speichern</button>
<?php }
}
