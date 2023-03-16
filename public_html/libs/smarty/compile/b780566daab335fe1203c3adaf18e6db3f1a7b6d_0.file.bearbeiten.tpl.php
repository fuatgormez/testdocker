<?php
/* Smarty version 3.1.30, created on 2019-07-15 10:58:50
  from "/usr/local/www/apache24/noexec/ttact-intern-software/views/main/Lohnbuchung/bearbeiten.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5d2c404a8a88c5_66878024',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'b780566daab335fe1203c3adaf18e6db3f1a7b6d' => 
    array (
      0 => '/usr/local/www/apache24/noexec/ttact-intern-software/views/main/Lohnbuchung/bearbeiten.tpl',
      1 => 1562525851,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5d2c404a8a88c5_66878024 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_assignInScope('container_class', "container" ,false ,2);
?>

<?php $_smarty_tpl->_assignInScope('title', "Lohnbuchung | bearbeiten" ,false ,2);
?>

<div class="row mb-4">
    <div class="col">
        <div class="content-box">
            <h3 class="content-box-title">für <strong><?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['vorname'])===null||$tmp==='' ? "???" : $tmp);?>
 <?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['nachname'])===null||$tmp==='' ? "???" : $tmp);?>
 (<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['personalnummer'])===null||$tmp==='' ? "???" : $tmp);?>
)</strong></h3>

            <?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['success'])) {?>
            <div class="alert alert-success">
                <?php echo $_smarty_tpl->tpl_vars['smarty_vars']->value['success'];?>

            </div>
            <?php }?>
            <?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['error'])) {?>
            <div class="alert alert-danger">
                <?php echo $_smarty_tpl->tpl_vars['smarty_vars']->value['error'];?>

            </div>
            <?php }?>

            <form action="/lohnbuchung/bearbeiten/<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['id'])===null||$tmp==='' ? "???" : $tmp);?>
" method="post">
                <div class="row">
                    <div class="form-group col-12 col-sm-6">
                        <label class="form-control-label" for="jahr">Jahr <span class="required">*</span></label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-calendar fa-fw"></i></span>
                            <input name="jahr" type="text" class="form-control" id="jahr" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['jahr'])===null||$tmp==='' ? '' : $tmp);?>
" placeholder="JJJJ">
                        </div>
                    </div>
                    <div class="form-group col-12 col-sm-6">
                        <label class="form-control-label" for="monat">Monat <span class="required">*</span></label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-calendar fa-fw"></i></span>
                            <select name="monat" class="form-control" id="monat">
                                <option value="">-- bitte auswählen</option>
                                <option value="1"<?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['monat'])) {
if ($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['monat'] == 1) {?> selected<?php }
}?>>Januar</option>
                                <option value="2"<?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['monat'])) {
if ($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['monat'] == 2) {?> selected<?php }
}?>>Februar</option>
                                <option value="3"<?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['monat'])) {
if ($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['monat'] == 3) {?> selected<?php }
}?>>März</option>
                                <option value="4"<?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['monat'])) {
if ($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['monat'] == 4) {?> selected<?php }
}?>>April</option>
                                <option value="5"<?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['monat'])) {
if ($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['monat'] == 5) {?> selected<?php }
}?>>Mai</option>
                                <option value="6"<?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['monat'])) {
if ($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['monat'] == 6) {?> selected<?php }
}?>>Juni</option>
                                <option value="7"<?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['monat'])) {
if ($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['monat'] == 7) {?> selected<?php }
}?>>Juli</option>
                                <option value="8"<?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['monat'])) {
if ($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['monat'] == 8) {?> selected<?php }
}?>>August</option>
                                <option value="9"<?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['monat'])) {
if ($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['monat'] == 9) {?> selected<?php }
}?>>September</option>
                                <option value="10"<?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['monat'])) {
if ($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['monat'] == 10) {?> selected<?php }
}?>>Oktober</option>
                                <option value="11"<?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['monat'])) {
if ($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['monat'] == 11) {?> selected<?php }
}?>>November</option>
                                <option value="12"<?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['monat'])) {
if ($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['monat'] == 12) {?> selected<?php }
}?>>Dezember</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-12">
                        <label class="form-control-label" for="lohnart">Lohnart <span class="required">*</span></label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-calculator fa-fw"></i></span>
                            <input name="lohnart" type="text" class="form-control" id="lohnart" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['lohnart'])===null||$tmp==='' ? '' : $tmp);?>
" placeholder="z.B. 203 oder 8413">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-12 col-sm-6">
                        <label class="form-control-label" for="wert">Wert <span class="required">*</span></label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-eur fa-fw"></i></span>
                            <input name="wert" type="text" class="form-control" id="wert" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['wert'])===null||$tmp==='' ? '' : $tmp);?>
" placeholder="z.B. 300 oder 8.50">
                        </div>
                    </div>
                    <div class="form-group col-12 col-sm-6">
                        <label class="form-control-label" for="faktor">Faktor</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-times fa-fw"></i></span>
                            <input name="faktor" type="text" class="form-control" id="faktor" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['faktor'])===null||$tmp==='' ? '' : $tmp);?>
" placeholder="z.B. (leer) oder 5">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-12">
                        <label class="form-control-label" for="bezeichnung">Bezeichnung</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                            <input name="bezeichnung" type="text" class="form-control" id="bezeichnung" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['bezeichnung'])===null||$tmp==='' ? '' : $tmp);?>
" placeholder="z.B. Vorschuss oder Vergessene Stunden">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 mb-3 col-sm-6 mb-sm-0">
                        <button name="action" value="loeschen" type="submit" class="btn btn-danger form-control">Löschen</button>
                    </div>
                    <div class="col-12 col-sm-6">
                        <button name="action" value="speichern" type="submit" class="btn btn-secondary form-control">Speichern</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div><?php }
}
