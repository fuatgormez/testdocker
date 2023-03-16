<?php
/* Smarty version 3.1.30, created on 2019-07-12 13:47:46
  from "/usr/local/www/apache24/noexec/ttact-intern-software/views/main/Rechnungen/erstellen.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5d287362787c96_82137438',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'd699fd51e6af48d983d9e27b5effa5de04f62dbc' => 
    array (
      0 => '/usr/local/www/apache24/noexec/ttact-intern-software/views/main/Rechnungen/erstellen.tpl',
      1 => 1562525855,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5d287362787c96_82137438 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_assignInScope('container_class', "container" ,false ,2);
?>

<?php $_smarty_tpl->_assignInScope('title', "Rechnungen | erstellen" ,false ,2);
?>

<div class="row mb-4">
    <div class="col">
        <div class="content-box">
            <h4 class="mb-4">Rechnungsdaten</h4>

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

            <form action="/rechnungen/erstellen" method="post">
                <div class="row">
                    <div class="form-group col-12 col-sm-6">
                        <label class="form-control-label" for="von">Von <span class="required">*</span></label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-calendar fa-fw"></i></span>
                            <input type="text" class="form-control datum" id="von" name="von" placeholder="TT.MM.JJJJ" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['von'])===null||$tmp==='' ? '' : $tmp);?>
" required>
                        </div>
                    </div>
                    <div class="form-group col-12 col-sm-6">
                        <label class="form-control-label" for="bis">Bis <strong>inklusive</strong><span class="required">*</span></label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-calendar fa-fw"></i></span>
                            <input type="text" class="form-control datum" id="bis" name="bis" placeholder="TT.MM.JJJJ" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['bis'])===null||$tmp==='' ? '' : $tmp);?>
" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-12">
                        <label class="form-control-label" for="kunde">Kunde <span class="required">*</span></label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-user fa-fw"></i></span>
                            <select class="form-control selectable" id="kunde" tabindex="-1" name="kunde" required>
                                <option value="">-- bitte auswählen</option>
                                <?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['kundenliste'])) {?>
                                    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['smarty_vars']->value['kundenliste'], 'row');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['row']->value) {
?>
                                        <option value="<?php echo $_smarty_tpl->tpl_vars['row']->value['id'];?>
" <?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['kunde'])) {?> <?php if ($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['kunde'] == $_smarty_tpl->tpl_vars['row']->value['id']) {?> selected<?php }
}?>><?php echo $_smarty_tpl->tpl_vars['row']->value['kundennummer'];?>
 - <?php echo $_smarty_tpl->tpl_vars['row']->value['name'];?>
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
                <div class="row">
                    <div class="form-group col-12 col-sm-6">
                        <label class="form-control-label" for="rechnungsdatum">Rechnungsdatum <span class="required">*</span></label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-calendar fa-fw"></i></span>
                            <input type="text" class="form-control datum" id="rechnungsdatum" name="rechnungsdatum" placeholder="TT.MM.JJJJ" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['rechnungsdatum'])===null||$tmp==='' ? '' : $tmp);?>
" required>
                        </div>
                    </div>
                    <div class="form-group col-12 col-sm-6">
                        <label class="form-control-label" for="zahlungsziel">Zahlungsziel <span class="required">*</span></label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-calendar fa-fw"></i></span>
                            <input type="text" class="form-control datum" id="zahlungsziel" name="zahlungsziel" placeholder="TT.MM.JJJJ" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['zahlungsziel'])===null||$tmp==='' ? '' : $tmp);?>
" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-12">
                        <label class="form-control-label" for="kassendifferenz">Kassendifferenz</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-eur fa-fw"></i></span>
                            <input type="text" class="form-control" id="kassendifferenz" name="kassendifferenz" placeholder="45,50" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['kassendifferenz'])===null||$tmp==='' ? '' : $tmp);?>
">
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-secondary form-control">Vorschau erstellen</button>
            </form>
        </div>
    </div>
</div>

<?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['rechnungsliste'])) {?>
    <div class="row mb-4">
        <div class="col">
            <div class="content-box">
                <h4 class="mb-4">Rechnungsposten</h4>
                <table class="table table-striped table-bordered dt-responsive nowrap mb-0" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Leistungsart</th>
                            <th class="text-right">Menge</th>
                            <th class="text-right">Einzelpreis in €</th>
                            <th class="text-right">Gesamtpreis in €</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['smarty_vars']->value['rechnungsliste'], 'row');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['row']->value) {
?>
                            <tr>
                                <td><?php echo $_smarty_tpl->tpl_vars['row']->value['leistungsart'];?>
</td>
                                <td class="text-right"><?php echo number_format((($tmp = @$_smarty_tpl->tpl_vars['row']->value['menge'])===null||$tmp==='' ? 0 : $tmp),2,",",".");?>
</td>
                                <td class="text-right"><?php echo number_format((($tmp = @$_smarty_tpl->tpl_vars['row']->value['einzelpreis'])===null||$tmp==='' ? 0 : $tmp),2,",",".");?>
</td>
                                <td class="text-right"><?php echo number_format((($tmp = @$_smarty_tpl->tpl_vars['row']->value['gesamtpreis'])===null||$tmp==='' ? 0 : $tmp),2,",",".");?>
</td>
                            </tr>
                        <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php }?>

<?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['gesamtbetrag'])) {?>
    <div class="row mb-4">
        <div class="col">
            <div class="content-box">
                <h4 class="mb-4">Rechnungssumme</h4>
                <table class="table table-bordered dt-responsive nowrap mb-0" cellspacing="0" width="100%">
                    <tbody>
                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['smarty_vars']->value['gesamtbetrag'], 'row');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['row']->value) {
?>
                            <tr>
                                <td><?php echo $_smarty_tpl->tpl_vars['row']->value['name'];?>
</td>
                                <td class="text-right"><?php echo (($tmp = @$_smarty_tpl->tpl_vars['row']->value['betrag'])===null||$tmp==='' ? 0 : $tmp);?>
</td>
                            </tr>
                        <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php }?>

<?php if ($_smarty_tpl->tpl_vars['smarty_vars']->value['rechnung_ready_to_save']) {?>
    <div class="row mb-4">
        <div class="col">
            <div class="content-box">
                <div class="row">
                    <div class="col-12 col-sm">
                        <form action="/rechnungen/pdf" method="post" target="_blank">
                            <input type="hidden" name="rechnung_data" value='<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['rechnung_data'])===null||$tmp==='' ? '' : $tmp);?>
'>
                            <input type="hidden" name="rechnungsposten_data" value='<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['rechnungsposten_data'])===null||$tmp==='' ? '' : $tmp);?>
'>
                            <button type="submit" class="btn btn-secondary btn-block">PDF anzeigen</button>
                        </form>
                    </div>
                    <div class="col-12 col-sm">
                        <form action="/rechnungen/bearbeiten" method="post">
                            <input type="hidden" name="rechnung_data" value='<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['rechnung_data'])===null||$tmp==='' ? '' : $tmp);?>
'>
                            <input type="hidden" name="rechnungsposten_data" value='<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['rechnungsposten_data'])===null||$tmp==='' ? '' : $tmp);?>
'>
                            <button type="submit" class="btn btn-secondary btn-block">Rechnung bearbeiten</button>
                        </form>
                    </div>
                    <div class="col-12 col-sm">
                        <form action="/rechnungen/anzeigen" method="post">
                            <input type="hidden" name="rechnung_data" value='<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['rechnung_data'])===null||$tmp==='' ? '' : $tmp);?>
'>
                            <input type="hidden" name="rechnungsposten_data" value='<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['rechnungsposten_data'])===null||$tmp==='' ? '' : $tmp);?>
'>
                            <button type="submit" class="btn btn-secondary btn-block">Rechnung speichern</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php }?>

<?php $_smarty_tpl->smarty->ext->_capture->open($_smarty_tpl, 'css', null, null);
?>

    <!-- daterangepicker -->
    <link href="/assets/vendors/bootstrap-daterangepicker-master/daterangepicker.css" rel="stylesheet">

    <!-- Select2 -->
    <link href="/assets/vendors/select2/dist/css/select2.min.css" rel="stylesheet">

    <!-- General -->
    <style>
        a.btn:hover, a.btn:link, a.btn:visited, a.btn:active {
            color: black;
        }
    </style>
<?php $_smarty_tpl->smarty->ext->_capture->close($_smarty_tpl);
?>


<?php $_smarty_tpl->_assignInScope('css', $_smarty_tpl->smarty->ext->_capture->getBuffer($_smarty_tpl, 'css') ,false ,2);
?>

<?php $_smarty_tpl->smarty->ext->_capture->open($_smarty_tpl, 'scripts', null, null);
?>

    <!-- moment -->
    <?php echo '<script'; ?>
 src="/assets/vendors/bootstrap-daterangepicker-master/moment.min.js"><?php echo '</script'; ?>
>

    <!-- daterangepicker -->
    <?php echo '<script'; ?>
 src="/assets/vendors/bootstrap-daterangepicker-master/daterangepicker.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
>
        $(".datum").daterangepicker({
            "singleDatePicker": true,
            "showISOWeekNumbers": true,
            "locale": {
                "format": "DD.MM.YYYY",
                "separator": " - ",
                "applyLabel": "Übernehmen",
                "cancelLabel": "Abbrechen",
                "fromLabel": "Von",
                "toLabel": "Bis",
                "customRangeLabel": "Manuell",
                "weekLabel": "W",
                "daysOfWeek": [
                    "So",
                    "Mo",
                    "Di",
                    "Mi",
                    "Do",
                    "Fr",
                    "Sa"
                ],
                "monthNames": [
                    "Januar",
                    "Februar",
                    "März",
                    "April",
                    "Mai",
                    "Juni",
                    "Juli",
                    "August",
                    "September",
                    "Oktober",
                    "November",
                    "Dezember"
                ],
                "firstDay": 1
            }
        });
    <?php echo '</script'; ?>
>

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
