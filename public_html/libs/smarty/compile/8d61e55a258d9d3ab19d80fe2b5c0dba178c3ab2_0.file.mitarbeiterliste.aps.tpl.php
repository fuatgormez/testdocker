<?php
/* Smarty version 3.1.30, created on 2019-07-12 12:36:40
  from "/usr/local/www/apache24/noexec/ttact-intern-software/views/main/Kunden/mitarbeiterliste.aps.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5d2862b842cde8_19189297',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '8d61e55a258d9d3ab19d80fe2b5c0dba178c3ab2' => 
    array (
      0 => '/usr/local/www/apache24/noexec/ttact-intern-software/views/main/Kunden/mitarbeiterliste.aps.tpl',
      1 => 1562525860,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5d2862b842cde8_19189297 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_assignInScope('container_class', "container" ,false ,2);
?>

<?php $_smarty_tpl->_assignInScope('title', "Kunden | Mitarbeiterliste" ,false ,2);
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

            <form action="/kunden/mitarbeiterliste" method="post">
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
                        <label class="form-control-label" for="bis">Bis <strong>inklusive</strong> <span class="required">*</span></label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-calendar fa-fw"></i></span>
                            <input type="text" class="form-control datum" id="bis" name="bis" placeholder="TT.MM.JJJJ" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['bis'])===null||$tmp==='' ? '' : $tmp);?>
" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-12">
                        <label class="form-control-label" for="kunde">Kunde<?php if ($_smarty_tpl->tpl_vars['smarty_vars']->value['kunde_pflichtangabe']) {?> <span class="required">*</span><?php }?></label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-user fa-fw"></i></span>
                            <select class="form-control selectable" id="kunde" tabindex="-1" name="kunde">
                                <option value="">-- keinen ausgewählt</option>
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
                <button type="submit" name="action" value="berechnen" class="btn btn-secondary form-control">Anzeigen</button>
            </form>
        </div>
    </div>
</div>

<?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['liste'])) {?>
    <form action="/kunden/mitarbeiterliste" method="post">
        <div class="row mb-4">
            <div class="col">
                <div class="content-box">
                    <table class="table table-striped table-bordered table-hover dt-responsive nowrap mb-0" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>&nbsp;</th>
                                <th>&nbsp;</th>
                                <th>Mitarbeiter</th>
                                <th>Tätigkeit</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['smarty_vars']->value['liste'], 'row');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['row']->value) {
?>
                                <tr>
                                    <td><input type="checkbox" name="export[]" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['row']->value['export'])===null||$tmp==='' ? "???" : $tmp);?>
" checked></td>
                                    <td>
                                        <button type="submit" name="anlage2" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['row']->value['anlage2_data'])===null||$tmp==='' ? "???" : $tmp);?>
" class="btn btn-secondary btn-block" onclick='this.form.target="_blank";'>Anlage 2</button>
                                    </td>
                                    <td><?php echo (($tmp = @$_smarty_tpl->tpl_vars['row']->value['personalnummer'])===null||$tmp==='' ? "???" : $tmp);?>
 - <?php echo (($tmp = @$_smarty_tpl->tpl_vars['row']->value['nachname'])===null||$tmp==='' ? "???" : $tmp);?>
, <?php echo (($tmp = @$_smarty_tpl->tpl_vars['row']->value['vorname'])===null||$tmp==='' ? "???" : $tmp);?>
</td>
                                    <td><?php echo (($tmp = @$_smarty_tpl->tpl_vars['row']->value['taetigkeit'])===null||$tmp==='' ? '' : $tmp);?>
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

        <div class="row mb-4">
            <div class="col">
                <div class="content-box">
                    <input type="hidden" name="von" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['von'])===null||$tmp==='' ? "???" : $tmp);?>
">
                    <input type="hidden" name="bis" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['bis'])===null||$tmp==='' ? "???" : $tmp);?>
">
                    <input type="hidden" name="kunde" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['kunde'])===null||$tmp==='' ? "???" : $tmp);?>
">
                    <input type="hidden" name="filename" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['filename'])===null||$tmp==='' ? "???" : $tmp);?>
">
                    <button type="submit" name="exportieren" value="true" class="btn btn-secondary btn-block" onclick='this.form.target="_blank";'>Für Excel exportieren</button>
                </div>
            </div>
        </div>
    </form>
<?php }?>

<?php $_smarty_tpl->smarty->ext->_capture->open($_smarty_tpl, 'css', null, null);
?>

    <!-- daterangepicker -->
    <link href="/assets/vendors/bootstrap-daterangepicker-master/daterangepicker.css" rel="stylesheet">

    <!-- Select2 -->
    <link href="/assets/vendors/select2/dist/css/select2.min.css" rel="stylesheet">

    <!-- Custom -->
    <style>
        .btn-checkbox {
	        color: #fff;
	        background-color: #d9534f;
	        border-color: #d9534f;
        }

        .btn-checkbox:hover, .btn-checkbox:active, .btn-checkbox:focus {
	        background-color: #c9302c;
	        border-color: #c12e2a;
        }

        .btn-checkbox.active {
	        background-color: #5cb85c;
	        border-color: #5cb85c;
        }

        .btn-checkbox.active:hover {
	        background-color: #449d44;
	        border-color: #419641;
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
