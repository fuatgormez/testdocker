<?php
/* Smarty version 3.1.30, created on 2019-07-12 13:11:19
  from "/usr/local/www/apache24/noexec/ttact-intern-software/views/main/Mitarbeiter/zusammensetzen.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5d286ad74a8743_14442626',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'a27d8e7c3894fd19aae9df9584f6e03cd16a53e6' => 
    array (
      0 => '/usr/local/www/apache24/noexec/ttact-intern-software/views/main/Mitarbeiter/zusammensetzen.tpl',
      1 => 1562525866,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5d286ad74a8743_14442626 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_assignInScope('container_class', "container" ,false ,2);
?>

<?php $_smarty_tpl->_assignInScope('title', "Mitarbeiter | zusammensetzen" ,false ,2);
?>

<div class="row mb-4">
    <div class="col">
        <div class="content-box">
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
            
            <div class="alert alert-warning">
                <strong>Hinweis:</strong> Dieser Vorgang kann nicht rückgängig gemacht werden. Alle Daten des Von-Mitarbeiters werden mit dem Zu-Mitarbeiter verknüpft und der Von-Mitarbeiter anschließend unwiderrufbar gelöscht.
            </div>

            <form action="/mitarbeiter/zusammensetzen" method="post">
                <div class="row">
                    <div class="form-group col-12 col-lg-6">
                        <label class="form-control-label" for="von">Von <span class="required">*</span></label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-user fa-fw"></i></span>
                            <select class="form-control selectable" id="von" tabindex="-1" name="von" required>
                                <option value="">-- bitte auswählen</option>
                                <?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['mitarbeiterliste'])) {?>
                                    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['smarty_vars']->value['mitarbeiterliste'], 'row');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['row']->value) {
?>
                                        <option value="<?php echo $_smarty_tpl->tpl_vars['row']->value['id'];?>
" <?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['von'])) {?> <?php if ($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['von'] == $_smarty_tpl->tpl_vars['row']->value['id']) {?> selected<?php }
}?>><?php echo $_smarty_tpl->tpl_vars['row']->value['personalnummer'];?>
 - <?php echo $_smarty_tpl->tpl_vars['row']->value['nachname'];?>
, <?php echo $_smarty_tpl->tpl_vars['row']->value['vorname'];?>
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
                    <div class="form-group col-12 col-lg-6">
                        <label class="form-control-label" for="zu">Zu <span class="required">*</span></label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-user fa-fw"></i></span>
                            <select class="form-control selectable" id="zu" tabindex="-1" name="zu" required>
                                <option value="">-- bitte auswählen</option>
                                <?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['mitarbeiterliste'])) {?>
                                    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['smarty_vars']->value['mitarbeiterliste'], 'row');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['row']->value) {
?>
                                        <option value="<?php echo $_smarty_tpl->tpl_vars['row']->value['id'];?>
" <?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['zu'])) {?> <?php if ($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['zu'] == $_smarty_tpl->tpl_vars['row']->value['id']) {?> selected<?php }
}?>><?php echo $_smarty_tpl->tpl_vars['row']->value['personalnummer'];?>
 - <?php echo $_smarty_tpl->tpl_vars['row']->value['nachname'];?>
, <?php echo $_smarty_tpl->tpl_vars['row']->value['vorname'];?>
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
                </div>
                <button type="submit" class="btn btn-secondary form-control">Vorgang starten</button>
            </form>
        </div>
    </div>
</div>

<?php $_smarty_tpl->smarty->ext->_capture->open($_smarty_tpl, 'css', null, null);
?>

    <!-- Select2 -->
    <link href="/assets/vendors/select2/dist/css/select2.min.css" rel="stylesheet">
<?php $_smarty_tpl->smarty->ext->_capture->close($_smarty_tpl);
?>


<?php $_smarty_tpl->_assignInScope('css', $_smarty_tpl->smarty->ext->_capture->getBuffer($_smarty_tpl, 'css') ,false ,2);
?>

<?php $_smarty_tpl->smarty->ext->_capture->open($_smarty_tpl, 'scripts', null, null);
?>

    <!-- Select2 -->
    <?php echo '<script'; ?>
 src="/assets/vendors/select2/dist/js/select2.full.min.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
>
        $(document).ready(function () {
            $(".selectable").select2({
                placeholder: "-- bitte auswählen",
                allowClear: true,
                language: {
                    "noResults": function() {
                        return "Keine Ergebnisse gefunden.";
                    }
                },
                width: "100%"
            });
        });
    <?php echo '</script'; ?>
>
<?php $_smarty_tpl->smarty->ext->_capture->close($_smarty_tpl);
?>


<?php $_smarty_tpl->_assignInScope('js', $_smarty_tpl->smarty->ext->_capture->getBuffer($_smarty_tpl, 'scripts') ,false ,2);
}
}
