<?php
/* Smarty version 3.1.30, created on 2019-08-01 17:24:54
  from "/usr/local/www/apache24/noexec/ttact-intern-software/views/main/Mitarbeiter/notizen.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5d430446d69027_86052375',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '97a129374901d777a83e999f6127854c629c758c' => 
    array (
      0 => '/usr/local/www/apache24/noexec/ttact-intern-software/views/main/Mitarbeiter/notizen.tpl',
      1 => 1562525869,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5d430446d69027_86052375 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_assignInScope('container_class', "container" ,false ,2);
?>

<?php $_smarty_tpl->_assignInScope('title', "Mitarbeiter | Notizen" ,false ,2);
$_smarty_tpl->_assignInScope('hide_title', true ,false ,2);
?>

<div class="row mb-4 print-hide">
    <div class="col">
        <div class="title-box text-white p-4">
            <h3 class="mb-0">Mitarbeiter | Notizen</h3>
        </div>
    </div>
</div>

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

            <div class="alert alert-info print-hide">
                Bitte beachten Sie, dass nur für diejenigen Mitarbeiter Notizen angezeigt werden, die im eingegebenen Zeitraum für die Lohnabrechnung relevant wären.
            </div>

            <form action="/mitarbeiter/notizen" method="post" class="print-hide">
                <div class="row">
                    <div class="form-group col-12 col-sm-6">
                        <label class="form-control-label" for="monat">Monat</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-calendar fa-fw"></i></span>
                            <select class="form-control selectable" id="monat" tabindex="-1" name="monat" required>
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
                    <div class="form-group col-12 col-sm-6">
                        <label class="form-control-label" for="jahr">Jahr</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-calendar fa-fw"></i></span>
                            <input type="text" class="form-control" id="jahr" name="jahr" placeholder="JJJJ" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['jahr'])===null||$tmp==='' ? '' : $tmp);?>
" required>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-secondary form-control">Alle Notizen anzeigen</button>
            </form>
        </div>
    </div>
</div>

<?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['notizenliste'])) {?>
    <div class="row mb-4">
        <div class="col">
            <div class="content-box">
                <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['smarty_vars']->value['notizenliste'], 'row');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['row']->value) {
?>
                    <div><strong><?php echo $_smarty_tpl->tpl_vars['row']->value['personalnummer'];?>
 - <?php echo $_smarty_tpl->tpl_vars['row']->value['nachname'];?>
, <?php echo $_smarty_tpl->tpl_vars['row']->value['vorname'];?>
</strong></div>
                    <div class="mb-4"><?php echo $_smarty_tpl->tpl_vars['row']->value['notiz'];?>
</div>
                <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

            </div>
        </div>
    </div>
<?php }?>

<?php $_smarty_tpl->smarty->ext->_capture->open($_smarty_tpl, 'css', null, null);
?>

    <!-- Select2 -->
    <link href="/assets/vendors/select2/dist/css/select2.min.css" rel="stylesheet">

    <style>
        @media print {
            .container {
                width: auto;
            }

            nav.navbar, .print-hide {
                display: none;
            }

            body {
                background: white !important;
            }
        }
    </style>
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
