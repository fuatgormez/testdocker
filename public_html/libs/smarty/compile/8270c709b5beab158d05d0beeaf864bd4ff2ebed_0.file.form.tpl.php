<?php
/* Smarty version 3.1.30, created on 2019-07-12 16:52:42
  from "/usr/local/www/apache24/noexec/ttact-intern-software/views/main/Abteilungen/components/form.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5d289ebab42b24_67339985',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '8270c709b5beab158d05d0beeaf864bd4ff2ebed' => 
    array (
      0 => '/usr/local/www/apache24/noexec/ttact-intern-software/views/main/Abteilungen/components/form.tpl',
      1 => 1562525873,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5d289ebab42b24_67339985 (Smarty_Internal_Template $_smarty_tpl) {
?>

<div class="form-group">
    <label class="form-control-label" for="bezeichnung">Bezeichnung</label>
    <div class="input-group">
        <span class="input-group-addon"><i class="fa fa-fw fa-tasks"></i></span>
        <input type="text" class="form-control" name="bezeichnung" id="bezeichnung" placeholder="Beispielabteilung" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['bezeichnung'])===null||$tmp==='' ? '' : $tmp);?>
" required>
    </div>
</div>

<div class="form-group">
    <label class="form-control-label" for="in_rechnung_stellen">In Rechnung stellen</label>
    <div class="input-group">
        <span class="input-group-addon"><i class="fa fa-fw fa-tasks"></i></span>
        <select name="in_rechnung_stellen" id="in_rechnung_stellen" class="form-control" required>
            <option value="ja"<?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['in_rechnung_stellen'])) {
if ($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['in_rechnung_stellen'] == "ja") {?> selected<?php }
}?>>ja</option>
            <option value="nein"<?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['in_rechnung_stellen'])) {
if ($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['in_rechnung_stellen'] == "nein") {?> selected<?php }
}?>>nein</option>
        </select>
    </div>
</div>

<?php if ($_smarty_tpl->tpl_vars['smarty_vars']->value['company'] == 'tps') {?>
    <div class="form-group">
        <label class="form-control-label" for="palettenabteilung">Palettenabteilung</label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-fw fa-tasks"></i></span>
            <select name="palettenabteilung" id="palettenabteilung" class="form-control" required>
                <option value="nein"<?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['palettenabteilung'])) {
if ($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['palettenabteilung'] == "nein") {?> selected<?php }
}?>>nein</option>
                <option value="ja"<?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['palettenabteilung'])) {
if ($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['palettenabteilung'] == "ja") {?> selected<?php }
}?>>ja</option>
            </select>
        </div>
    </div>
<?php }?>

<button type="submit" class="btn btn-secondary form-control">Speichern</button><?php }
}
