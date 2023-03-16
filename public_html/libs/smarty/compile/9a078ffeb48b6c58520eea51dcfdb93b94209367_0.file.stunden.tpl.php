<?php
/* Smarty version 3.1.30, created on 2019-07-12 11:21:18
  from "/usr/local/www/apache24/noexec/ttact-intern-software/views/main/Berechnungen/stunden.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5d28510ecca8f6_93335834',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '9a078ffeb48b6c58520eea51dcfdb93b94209367' => 
    array (
      0 => '/usr/local/www/apache24/noexec/ttact-intern-software/views/main/Berechnungen/stunden.tpl',
      1 => 1562525865,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5d28510ecca8f6_93335834 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_assignInScope('title', "Berechnungen | Stunden" ,false ,2);
?>

<div class="container">

    <div class="row mb-4" id="error-box" style="display:none;">
        <div class="col">
            <div class="content-box bg-warning text-white" id="error-content">
            </div>
        </div>
    </div>

    <?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['error'])) {?>
        <div class="row mb-4">
            <div class="col">
                <div class="content-box bg-danger text-white">
                    <?php echo $_smarty_tpl->tpl_vars['smarty_vars']->value['error'];?>

                </div>
            </div>
        </div>
    <?php }?>

    <?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['success'])) {?>
        <div class="row mb-4">
            <div class="col">
                <div class="content-box bg-success text-white">
                    <?php echo $_smarty_tpl->tpl_vars['smarty_vars']->value['success'];?>

                </div>
            </div>
        </div>
    <?php }?>

    <div class="row mb-4">
        <div class="col">
            <div class="content-box">
                <form action="/berechnungen/stunden" method="post">
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
                            <label class="form-control-label" for="kunde">Kunde <span class="badge badge-default">Optional</span></label>
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
                    <div class="row">
                        <div class="form-group col-12 col-sm-6">
                            <label class="form-control-label" for="abteilung">Abteilung <span class="badge badge-default">Optional</span></label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-tasks fa-fw"></i></span>
                                <select class="form-control selectable" id="abteilung" tabindex="-1" name="abteilung" disabled>
                                    <option value="">-- keine ausgewählt</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-12 col-sm-6">
                            <label class="form-control-label" for="mitarbeiter">Mitarbeiter <span class="badge badge-default">Optional</span></label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-user fa-fw"></i></span>
                                <select class="form-control selectable" id="mitarbeiter" tabindex="-1" name="mitarbeiter" disabled>
                                    <option value="">-- keinen ausgewählt</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <button type="submit" name="action" value="berechnen" class="btn btn-secondary form-control">Berechnen</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['liste'])) {?>
    <?php if (count($_smarty_tpl->tpl_vars['smarty_vars']->value['liste']) > 0) {?>
        <form action="/berechnungen/stunden" method="post">
            <div class="row mb-4">
                <div class="col">
                    <div class="content-box">
                        <table class="table table-striped table-bordered table-hover dt-responsive nowrap mb-0" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>&nbsp;</th>
                                    <th>Mitarbeiter</th>
                                    <th>Kunde</th>
                                    <th>Abteilung</th>
                                    <th>Datum</th>
                                    <th>Von</th>
                                    <th>Bis</th>
                                    <th>Pause</th>
                                    <th class="text-right">Stunden</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['smarty_vars']->value['liste'], 'row');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['row']->value) {
?>
                                    <tr<?php if ($_smarty_tpl->tpl_vars['row']->value['insgesamt']) {?> class="bg-primary text-white"<?php }?>>
                                        <?php if ($_smarty_tpl->tpl_vars['row']->value['insgesamt']) {?>
                                            <td>&nbsp;</td>
                                        <?php } else { ?>
                                            <td><input type="checkbox" name="export[]" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['row']->value['export'])===null||$tmp==='' ? "???" : $tmp);?>
" checked></td>
                                        <?php }?>
                                        <td><?php echo (($tmp = @$_smarty_tpl->tpl_vars['row']->value['personalnummer'])===null||$tmp==='' ? "???" : $tmp);?>
 - <?php echo (($tmp = @$_smarty_tpl->tpl_vars['row']->value['nachname'])===null||$tmp==='' ? "???" : $tmp);?>
, <?php echo (($tmp = @$_smarty_tpl->tpl_vars['row']->value['vorname'])===null||$tmp==='' ? "???" : $tmp);?>
</td>
                                        <?php if ($_smarty_tpl->tpl_vars['row']->value['insgesamt']) {?>
                                            <td>Insgesamt</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                        <?php } else { ?>
                                            <td><?php echo $_smarty_tpl->tpl_vars['row']->value['kundennummer'];?>
 - <?php echo $_smarty_tpl->tpl_vars['row']->value['kundenname'];?>
</td>
                                            <td><?php echo $_smarty_tpl->tpl_vars['row']->value['abteilung'];?>
</td>
                                            <td><?php echo $_smarty_tpl->tpl_vars['row']->value['datum'];?>
</td>
                                            <td><?php echo $_smarty_tpl->tpl_vars['row']->value['von'];?>
</td>
                                            <td><?php echo $_smarty_tpl->tpl_vars['row']->value['bis'];?>
</td>
                                            <td><?php echo $_smarty_tpl->tpl_vars['row']->value['pause'];?>
</td>
                                        <?php }?>
                                        <td class="text-right"><?php echo (($tmp = @$_smarty_tpl->tpl_vars['row']->value['stunden'])===null||$tmp==='' ? "???" : $tmp);?>
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
                        <input type="hidden" name="filename" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['filename'])===null||$tmp==='' ? "???" : $tmp);?>
">
                        <input type="hidden" name="action" value="exportieren">
                        <button type="submit" name="action" value="exportieren" class="btn btn-secondary btn-block" onclick='this.form.target="_blank";'>Für Excel exportieren</button>
                    </div>
                </div>
            </div>
        </form>
    <?php } else { ?>
        <div class="row mb-4">
            <div class="col">
                <div class="content-box">
                    <div class="p-4 text-center">
                        Es wurden keine Ergebnisse gefunden.
                    </div>
                </div>
            </div>
        </div>
    <?php }
}?>

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
        function makeSelectable(element) {
            if (!element.hasClass("select2-hidden-accessible")) {
                element.select2({
                    placeholder: "-- bitte auswählen",
                    allowClear: true,
                    language: {
                        "noResults": function () {
                            return "Keine Ergebnisse gefunden.";
                        }
                    },
                    width: "100%"
                });
            }
        }

        function removeSelectable(element) {
            if (element.hasClass("select2-hidden-accessible")) {
                element.select2('destroy');
            }
        }
    <?php echo '</script'; ?>
>

    <!-- Abteilungsliste und Mitarbeiterliste -->
    <?php echo '<script'; ?>
>
        $(document).ready(function() {
            var von = $('#von');
            var bis = $('#bis');
            var kunde = $('#kunde');
            var abteilung = $('#abteilung');
            var mitarbeiter = $('#mitarbeiter');

            makeSelectable(kunde);

            function showError(message) {
                $('#error-content').html(message);
                $('#error-box').show();
            }

            function hideError() {
                $('#error-box').hide();
                $('#error-content').html('');
            }

            function updateListen(callback) {
                var moment_von = moment(von.val(), "DD.MM.YYYY");
                var moment_bis = moment(bis.val(), "DD.MM.YYYY");

                if (!moment_von.isValid() || !moment_bis.isValid() || (moment_bis < moment_von)) {
                    removeSelectable(abteilung);
                    abteilung.prop('disabled', true);

                    removeSelectable(mitarbeiter);
                    mitarbeiter.prop('disabled', true);

                    von.parent().addClass('has-danger');
                    bis.parent().addClass('has-danger');
                } else {
                    von.parent().removeClass('has-danger');
                    bis.parent().removeClass('has-danger');

                    removeSelectable(abteilung);
                    abteilung.html('<option value="">-- keine ausgewählt</option>');
                    abteilung.prop('disabled', true);

                    removeSelectable(mitarbeiter);
                    mitarbeiter.html('<option value="">-- keinen ausgewählt</option>');
                    mitarbeiter.prop('disabled', true);

                    $.ajax({
                        type: "POST",
                        url: "/berechnungen/ajax",
                        data: {
                            von: von.val(),
                            bis: bis.val(),
                            kunde: kunde.val()
                        },
                        success: function (data) {
                            if (data.hasOwnProperty('status')) {
                                if (data.status == "success") {
                                    if (data.hasOwnProperty('abteilungen')) {
                                        $.each(data.abteilungen, function (index, obj) {
                                            var option = $('<option/>');
                                            option.attr("value", obj.id);
                                            option.text(obj.bezeichnung);
                                            abteilung.append(option);
                                        });
                                        makeSelectable(abteilung);
                                        abteilung.prop('disabled', false);

                                        $.each(data.mitarbeiter, function (index, obj) {
                                            var option = $('<option/>');
                                            option.attr("value", obj.id);
                                            option.text(obj.personalnummer + ' - ' + obj.nachname + ', ' + obj.vorname);
                                            mitarbeiter.append(option);
                                        });
                                        makeSelectable(mitarbeiter);
                                        mitarbeiter.prop('disabled', false);

                                        hideError();

                                        callback();
                                    } else {
                                        showError("Es ist ein Fehler aufgetreten.");
                                    }
                                } else if (data.status == "not_logged_in") {
                                    location.reload();
                                } else {
                                    showError("Es ist ein Fehler aufgetreten.");
                                }
                            } else {
                                showError("Es ist ein Fehler aufgetreten.");
                            }
                        },
                        error: function () {
                            showError("Es ist ein Fehler aufgetreten.");
                        }
                    });
                }
            }

            von.change(function () {updateListen(function () {})});
            bis.change(function () {updateListen(function () {})});
            kunde.change(function () {updateListen(function () {})});

            updateListen(function () {
                <?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['abteilung'])) {?>
                    abteilung.val('<?php echo $_smarty_tpl->tpl_vars['smarty_vars']->value['values']['abteilung'];?>
').change();
                <?php }?>

                <?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['mitarbeiter'])) {?>
                    mitarbeiter.val('<?php echo $_smarty_tpl->tpl_vars['smarty_vars']->value['values']['mitarbeiter'];?>
').change();
                <?php }?>
            });
        });
    <?php echo '</script'; ?>
>
<?php $_smarty_tpl->smarty->ext->_capture->close($_smarty_tpl);
?>


<?php $_smarty_tpl->_assignInScope('js', $_smarty_tpl->smarty->ext->_capture->getBuffer($_smarty_tpl, 'scripts') ,false ,2);
}
}
