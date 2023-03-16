<?php
/* Smarty version 3.1.30, created on 2019-07-11 07:44:14
  from "/usr/local/www/apache24/noexec/ttact-intern-software/views/main/Auftraege/index.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5d26ccae4e8326_45514297',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '0f87ff593815ff86f5a0ae1186b7b385d70576a3' => 
    array (
      0 => '/usr/local/www/apache24/noexec/ttact-intern-software/views/main/Auftraege/index.tpl',
      1 => 1562525863,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5d26ccae4e8326_45514297 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_assignInScope('container_class', "container" ,false ,2);
?>

<?php $_smarty_tpl->_assignInScope('title', "Aufträge" ,false ,2);
?>

<form action="/auftraege" method="post">
    <div class="row mb-4">
        <div class="col">
            <div class="content-box overflow-x-unset">
                <h3 class="content-box-title">Neuer Auftrag</h3>

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
                <?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['warning'])) {?>
                    <div class="alert alert-warning">
                        <?php echo $_smarty_tpl->tpl_vars['smarty_vars']->value['warning'];?>

                    </div>
                <?php }?>

                <div class="row">
                    <div class="form-group col-12 col-lg-6">
                        <label class="form-control-label" for="kunde">Kunde <span class="required">*</span></label>
                        <div class="input-group">
                            <span class="input-group-addon hidden-450px-down"><i class="fa fa-user fa-fw"></i></span>
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
                    <div class="form-group col-12 col-lg-6">
                        <label class="form-control-label" for="kalenderwoche">Kalenderwoche <span class="required">*</span></label>
                        <div class="input-group">
                          <div class="input-group-btn">
                              <button type="button" class="btn btn-secondary dropdown-toggle p-xs-down-1" data-toggle="dropdown">
                                  <span class="hidden-xs-down" id="jahresauswahl_button"><?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['jahr'])===null||$tmp==='' ? "???" : $tmp);?>
</span>
                              </button>
                              <div class="dropdown-menu" id="jahresauswahl">
                                  <?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['jahresliste'])) {?>
                                      <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['smarty_vars']->value['jahresliste'], 'row');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['row']->value) {
?>
                                          <a class="dropdown-item" href="javascript:;" data-jahr="<?php echo $_smarty_tpl->tpl_vars['row']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['row']->value;?>
</a>
                                      <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

                                  <?php }?>
                              </div>
                          </div>

                            <select class="form-control selectable" id="kalenderwoche" tabindex="-1" name="kalenderwoche" required>
                                <option value="">-- bitte auswählen</option>
                                <?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['kalenderwochen'])) {?>
                                    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['smarty_vars']->value['kalenderwochen'], 'row');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['row']->value) {
?>
                                        <option value="<?php echo $_smarty_tpl->tpl_vars['row']->value['kw'];?>
" <?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['kalenderwoche'])) {?> <?php if ($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['kalenderwoche'] == $_smarty_tpl->tpl_vars['row']->value['kw']) {?> selected<?php }
}?>>KW <?php echo $_smarty_tpl->tpl_vars['row']->value['kw'];?>
 | <?php echo $_smarty_tpl->tpl_vars['row']->value['von'];?>
 - <?php echo $_smarty_tpl->tpl_vars['row']->value['bis'];?>
</option>
                                    <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

                                <?php }?>
                            </select>

                            <input type="hidden" name="jahr" id="jahr" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['jahr'])===null||$tmp==='' ? '' : $tmp);?>
">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="auftragszeiten_container">
        <div class="row mb-4">
            <div class="col">
                <div class="content-box">
                    <div class="form-group mb-0">
                        <label class="form-control-label">Abteilung <span class="required">*</span></label>
                        <div class="input-group">
                            <span class="input-group-addon hidden-450px-down"><i class="fa fa-tasks fa-fw"></i></span>
                            <select class="form-control abteilungsauswahl" tabindex="-1" id="abteilungsauswahl-0" name="abteilungsauswahl[0]" disabled required>
                                <option value="">-- bitte auswählen</option>
                                <?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['abteilungsliste'])) {?>
                                    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['smarty_vars']->value['abteilungsliste'], 'row');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['row']->value) {
?>
                                        <option value="<?php echo $_smarty_tpl->tpl_vars['row']->value['id'];?>
" class="abteilung-<?php echo $_smarty_tpl->tpl_vars['row']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['row']->value['bezeichnung'];?>
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
            </div>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col">
            <div class="content-box">
                <button type="submit" class="btn btn-secondary form-control">Speichern</button>
            </div>
        </div>
    </div>
</form>

<?php $_smarty_tpl->_assignInScope('css', '
    <!-- Select2 -->
    <link href="/assets/vendors/select2/dist/css/select2.min.css" rel="stylesheet">

    <!-- General -->
    <style>
        @media (max-width: 449px) {
            .hidden-450px-down {
                display: none;
            }
        }
        .overflow-x-unset {
            overflow-x: unset !important;
        }
    </style>
' ,false ,2);
?>

<?php $_smarty_tpl->smarty->ext->_capture->open($_smarty_tpl, 'scripts', null, null);
?>

    <!-- Select2 -->
    <?php echo '<script'; ?>
 src="/assets/vendors/select2/dist/js/select2.full.min.js"><?php echo '</script'; ?>
>

    <!-- jquery.inputmask -->
    <?php echo '<script'; ?>
 src="/assets/vendors/jquery.inputmask/dist/min/jquery.inputmask.bundle.min.js"><?php echo '</script'; ?>
>

    <?php echo '<script'; ?>
>
        function initAbteilungsauswahl(row) {
            $("#abteilungsauswahl-" + row).select2({
                placeholder: "-- bitte auswählen",
                allowClear: true,
                language: {
                    "noResults": function() {
                        return "Keine Ergebnisse gefunden.";
                    }
                },
                width: "100%"
            });

            $("#abteilungsauswahl-" + row).change(function () {
                if (!$(this).hasClass("triggered")) {
                    $(this).addClass("triggered");

                    div_container = $("<div/>", {class: "row mb-4"});
                    div_col = $("<div/>", {class: "col"});
                    div_content_box = $("<div/>", {class: "content-box"});
                    div_row = $("<div/>", {class: "row mx-0"});

                    wochentage = new Array();
                    wochentage[0] = 'Mo<span class="hidden-lg-up">ntag</span>';
                    wochentage[1] = '&nbsp;';
                    wochentage[2] = 'Di<span class="hidden-lg-up">enstag</span>';
                    wochentage[3] = '&nbsp;';
                    wochentage[4] = 'Mi<span class="hidden-lg-up">ttwoch</span>';
                    wochentage[5] = '&nbsp;';
                    wochentage[6] = 'Do<span class="hidden-lg-up">nnerstag</span>';
                    wochentage[7] = '&nbsp;';
                    wochentage[8] = 'Fr<span class="hidden-lg-up">eitag</span>';
                    wochentage[9] = '&nbsp;';
                    wochentage[10] = 'Sa<span class="hidden-lg-up">mstag</span>';
                    wochentage[11] = '&nbsp;';
                    wochentage[12] = 'So<span class="hidden-lg-up">nntag</span>';
                    wochentage[13] = '&nbsp;';

                    for (var i = 0; i < 14; i++) {
                        var div_form_group = $("<div/>", {class: "form-group col-6 col-sm-3 col-lg px-1"});
                        var label = $("<label/>", {class: "form-control-label"});
                        label.html(wochentage[i]);
                        var input = $("<input/>", {class: "form-control rounded-0 text-center px-0"});
                        input.attr("type", "text");
                        input.attr("name", "zaw[" + row + "][" + i + "]");
                        input.attr("id", "zaw-" + row + "-" + i);
                        input.attr("data-inputmask", "\'mask\' : \'99:99\'");
                        input.attr("data-row", row);
                        input.attr("data-col", i);
                        input.on("keyup", function (e) {
                            if (e.keyCode == 8) {
                                if (($(this).val() == "__:__") || ($(this).val() == "")) {
                                    $(this).trigger("cleared");
                                    var r = parseInt($(this).attr("data-row"));
                                    var c = parseInt($(this).attr("data-col"));
                                    if (c == "0") {
                                        $("#zaw-" + (r - 1) + "-13").focus();
                                    } else {
                                        $("#zaw-" + r + "-" + (c - 1)).focus();
                                    }
                                }
                            }
                        });
                        input.on("input", function () {
                            if ($(this).val().match(/^[0-9][0-9]:[0-9][0-9]$/)) {
                                var r = parseInt($(this).attr("data-row"));
                                var c = parseInt($(this).attr("data-col"));
                                if (c == "13") {
                                    $("#zaw-" + (r + 1) + "-0").focus();
                                } else {
                                    $("#zaw-" + r + "-" + (c + 1)).focus();
                                }
                            }
                        });
                        input.inputmask();
                        input.keypress(function (e) {
                            if (e.which == 13) {
                                e.preventDefault();
                                $("#abteilungsauswahl-" + (row + 1)).val($("#abteilungsauswahl-" + row).val()).change();
                                $("#zaw-" + (row + 1) + "-0").focus();
                            }
                        });

                        div_form_group.append(label);
                        div_form_group.append(input);

                        div_row.append(div_form_group);
                    }

                    div_content_box.append(div_row);
                    div_col.append(div_content_box);
                    div_container.append(div_col);
                    $("#auftragszeiten_container").append(div_container);

                    /* Abteilungsauswahl */
                    aa_div_container = $("<div/>", {class: "row mb-4"});
                    aa_div_col = $("<div/>", {class: "col"});
                    aa_div_content_box = $("<div/>", {class: "content-box"});
                    aa_div_form_group = $("<div/>", {class: "form-group mb-0"});
                    aa_label = $("<label/>", {class: "form-control-label"});
                    aa_label.html("Abteilung");
                    aa_div_input_group = $("<div/>", {class: "input-group"});
                    aa_div_input_group.html('<span class="input-group-addon hidden-450px-down"><i class="fa fa-tasks fa-fw"></i></span>');
                    aa_select = $("<select/>", {class: "form-control abteilungsauswahl"});
                    aa_select.attr("tabindex", "-1");
                    aa_select.attr("id", "abteilungsauswahl-" + (row + 1));
                    aa_select.attr("name", "abteilungsauswahl[" + (row + 1) + "]");
                    aa_select.html($("#abteilungsauswahl-0").html());

                    aa_div_input_group.append(aa_select);
                    aa_div_form_group.append(aa_label);
                    aa_div_form_group.append(aa_div_input_group);
                    aa_div_content_box.append(aa_div_form_group);
                    aa_div_col.append(aa_div_content_box);
                    aa_div_container.append(aa_div_col);

                    $("#auftragszeiten_container").append(aa_div_container);

                    initAbteilungsauswahl(row + 1);
                }
            });
        }

        function updateAbteilungsauswahl() {
            $kunde = $("#kunde");
            $jahr = $("#jahr");
            $kalenderwoche = $("#kalenderwoche");
            //alert("I got triggered:\nKunde: " + $kunde.val() + "\nJahr: " + $jahr.val() + "\nKalenderwoche: " + $kalenderwoche.val());

            if ($kunde.val() != '' && $jahr.val() != '' && $kalenderwoche.val() != '') {
                $.ajax({
                    type: "POST",
                    url: "/auftraege/ajax",
                    data: {
                        kunde: $kunde.val(),
                        jahr: $jahr.val(),
                        kalenderwoche: $kalenderwoche.val()
                    },
                    success: function ($return) {
                        if ($return.hasOwnProperty('status')) {
                            if ($return.status == "success") {
                                $('.abteilungsauswahl').attr('disabled', true);
                                $('.abteilungsauswahl').each(function () {
                                    if ($(this).hasClass("select2-hidden-accessible")) {
                                        $(this).select2('destroy');
                                    }
                                });
                                $('.abteilungsauswahl').val('');
                                $.each($return.abteilungen, function (i, value) {
                                    if (value.freigegeben) {
                                        $(".abteilung-" + value.id).attr('disabled', false);
                                    } else {
                                        $(".abteilung-" + value.id).attr('disabled', true);
                                    }
                                });
                                $(".abteilungsauswahl").select2({
                                    placeholder: "-- bitte auswählen",
                                    allowClear: true,
                                    language: {
                                        "noResults": function() {
                                            return "Keine Ergebnisse gefunden.";
                                        }
                                    },
                                    width: "100%"
                                });
                                $('.abteilungsauswahl').attr('disabled', false);
                            } else if ($return.status == "not_logged_in") {
                                location.reload();
                            } else {
                                showError("Es ist ein Fehler aufgetreten.", 1);
                                callback({});
                            }
                        } else {
                            showError("Es ist ein Fehler aufgetreten.", 2);
                            callback({});
                        }
                    },
                    error: function () {
                        showError("Es ist ein Fehler aufgetreten.", 3);
                        callback({});
                    }
                });
            } else {
                $('.abteilungsauswahl').attr('disabled', true);
                $('.abteilungsauswahl').each(function () {
                    if ($(this).hasClass("select2-hidden-accessible")) {
                        $(this).select2('destroy');
                    }
                });
                $('.abteilungsauswahl').val('');
            }
        }

        function showError(a, b) {};
        function callback(a) {};

        $(document).ready(function() {
            $("#kunde").change(function () {
                updateAbteilungsauswahl();
            });

            initAbteilungsauswahl(0);

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

            <?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['abteilungsauswahl'][0])) {?>
                $.ajax({
                    type: "POST",
                    url: "/auftraege/ajax",
                    data: {kunde: $("#kunde").val()},
                    success: function ($return) {
                        if ($return.hasOwnProperty('status')) {
                            if ($return.status == "success") {
                                $('.abteilungsauswahl').attr('disabled', true);
                                $('.abteilungsauswahl').select2('destroy');
                                $.each($return.abteilungen, function (i, value) {
                                    if (value.freigegeben) {
                                        $(".abteilung-" + value.id).attr('disabled', false);
                                    } else {
                                        $(".abteilung-" + value.id).attr('disabled', true);
                                    }
                                });
                                $(".abteilungsauswahl").select2({
                                    placeholder: "-- bitte auswählen",
                                    allowClear: true,
                                    language: {
                                        "noResults": function() {
                                            return "Keine Ergebnisse gefunden.";
                                        }
                                    },
                                    width: "100%"
                                });
                                $('.abteilungsauswahl').attr('disabled', false);
                            } else if ($return.status == "not_logged_in") {
                                location.reload();
                            } else {
                                showError("Es ist ein Fehler aufgetreten.", 1);
                                callback({});
                            }
                        } else {
                            showError("Es ist ein Fehler aufgetreten.", 2);
                            callback({});
                        }
                    },
                    error: function () {
                        showError("Es ist ein Fehler aufgetreten.", 3);
                        callback({});
                    }
                });

                <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['smarty_vars']->value['values']['abteilungsauswahl'], 'value', false, 'index');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['index']->value => $_smarty_tpl->tpl_vars['value']->value) {
?>
                    $("#abteilungsauswahl-<?php echo $_smarty_tpl->tpl_vars['index']->value;?>
").val("<?php echo $_smarty_tpl->tpl_vars['value']->value;?>
").change();
                    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['smarty_vars']->value['values']['zaw'][$_smarty_tpl->tpl_vars['index']->value], 'value2', false, 'index2');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['index2']->value => $_smarty_tpl->tpl_vars['value2']->value) {
?>
                        $("#zaw-<?php echo $_smarty_tpl->tpl_vars['index']->value;?>
-<?php echo $_smarty_tpl->tpl_vars['index2']->value;?>
").val("<?php echo $_smarty_tpl->tpl_vars['value2']->value['value'];?>
").change();
                        <?php if ($_smarty_tpl->tpl_vars['value2']->value['error']) {?>
                            $("#zaw-<?php echo $_smarty_tpl->tpl_vars['index']->value;?>
-<?php echo $_smarty_tpl->tpl_vars['index2']->value;?>
").parent().addClass("has-danger");
                        <?php }?>
                    <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

                <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

            <?php }?>
        });
    <?php echo '</script'; ?>
>

    <!-- General -->
    <?php echo '<script'; ?>
>
        $kalenderwochen_are_being_loaded = false;

        $(document).ready(function () {
            // minimize DOM access by saving objects in vars
            $kalenderwoche = $("#kalenderwoche");
            $jahr = $("#jahr");
            $jahresauswahl = $("#jahresauswahl").find("a");
            $error_container = $("#error_container");
            $error_content = $("#error_content");
            $jahresauswahl_button = $("#jahresauswahl_button");

            function showError(message) {
                $error_content.html(message);
                $error_container.show();
            }

            function changeJahr(jahr, callback) {
                if ($jahr.val() != jahr) {
                    $.ajax({
                        type: "POST",
                        url: "/schichten/ajax",
                        data: {type: 'kalenderwochen', year: jahr},
                        success: function (data) {
                            if (data.hasOwnProperty('status')) {
                                if (data.status == "success") {
                                    if (data.hasOwnProperty('kalenderwochen')) {
                                        $kalenderwoche.select2("destroy");
                                        $kalenderwoche.html("");
                                        $.each(data.kalenderwochen, function (index, obj) {
                                            var option = $('<option/>');
                                            option.attr("value", obj.kw);
                                            option.text("KW " + obj.kw + " | " + obj.von + " - " + obj.bis);
                                            $kalenderwoche.append(option);
                                        });
                                        $kalenderwoche.select2({
                                            allowClear: false,
                                            language: {
                                                "noResults": function() {
                                                    return "Keine Ergebnisse gefunden.";
                                                }
                                            },
                                            width: "100%"
                                        });
                                        $jahr.val(jahr);
                                        $jahresauswahl_button.html(jahr);
                                        updateAbteilungsauswahl();
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
                } else {
                    callback();
                }
            }

            $kalenderwoche.change(function () {
                updateAbteilungsauswahl();
            });

            // Jahresauswahl button
            $jahresauswahl.click(function () {
                if ($jahr.val() != $(this).data("jahr")) {
                    changeJahr($(this).data("jahr"), function () {});
                }
            });
        });
    <?php echo '</script'; ?>
>
<?php $_smarty_tpl->smarty->ext->_capture->close($_smarty_tpl);
?>


<?php $_smarty_tpl->_assignInScope('js', $_smarty_tpl->smarty->ext->_capture->getBuffer($_smarty_tpl, 'scripts') ,false ,2);
}
}
