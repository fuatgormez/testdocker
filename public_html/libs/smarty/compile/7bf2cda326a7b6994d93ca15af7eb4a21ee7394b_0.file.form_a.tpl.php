<?php
/* Smarty version 3.1.30, created on 2019-07-11 08:58:35
  from "/usr/local/www/apache24/noexec/ttact-intern-software/views/main/Mitarbeiter/components/form_a.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5d26de1bce4c74_57233063',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '7bf2cda326a7b6994d93ca15af7eb4a21ee7394b' => 
    array (
      0 => '/usr/local/www/apache24/noexec/ttact-intern-software/views/main/Mitarbeiter/components/form_a.tpl',
      1 => 1562525873,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5d26de1bce4c74_57233063 (Smarty_Internal_Template $_smarty_tpl) {
?>
<div class="row">
    <div class="form-group col-12 col-sm-6">
        <label class="form-control-label" for="personalnummer">Personalnummer <span class="required">*</span></label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-link fa-fw"></i></span>
            <input type="number" min="1" class="form-control" id="personalnummer" name="personalnummer" placeholder="Personalnummer" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['personalnummer'])===null||$tmp==='' ? '' : $tmp);?>
" required>
        </div>
    </div>
    <div class="form-group col-12 col-sm-6">
        <label class="form-control-label" for="anrede">Anrede <span class="required">*</span></label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-venus-mars fa-fw"></i></span>
            <select name="geschlecht" class="form-control" id="geschlecht" required>
                <option value="" <?php if ((isset($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['geschlecht']))) {?> <?php if (($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['geschlecht'] == '')) {?> selected<?php }
}?>>Bitte auswählen</option>
                <option value="männlich" <?php if ((isset($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['geschlecht']))) {?> <?php if (($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['geschlecht'] == 'männlich')) {?> selected<?php }
}?>>Herr</option>
                <option value="weiblich" <?php if ((isset($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['geschlecht']))) {?> <?php if (($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['geschlecht'] == 'weiblich')) {?> selected<?php }
}?>>Frau</option>
            </select>
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-12 col-sm-6">
        <label class="form-control-label" for="vorname">Vorname <span class="required">*</span></label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-user fa-fw"></i></span>
            <input type="text" class="form-control" id="vorname" name="vorname" placeholder="Max" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['vorname'])===null||$tmp==='' ? '' : $tmp);?>
" required>
        </div>
    </div>
    <div class="form-group col-12 col-sm-6">
        <label class="form-control-label" for="nachname">Nachname <span class="required">*</span></label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-user fa-fw"></i></span>
            <input type="text" class="form-control" id="nachname" name="nachname" placeholder="Mustermann" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['nachname'])===null||$tmp==='' ? '' : $tmp);?>
" required>
        </div>
    </div>
</div><?php }
}
