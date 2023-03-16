<?php
/* Smarty version 3.1.30, created on 2019-07-11 04:43:28
  from "/usr/local/www/apache24/noexec/ttact-intern-software/views/main/Schichten/index.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5d26a2503305d3_01047636',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '1835d86ab7bf936a90be85b5399ae83d8db95c3d' => 
    array (
      0 => '/usr/local/www/apache24/noexec/ttact-intern-software/views/main/Schichten/index.tpl',
      1 => 1562525857,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5d26a2503305d3_01047636 (Smarty_Internal_Template $_smarty_tpl) {
if (!is_callable('smarty_modifier_date_format')) require_once '/usr/local/www/apache24/noexec/ttact-intern-software/libs/smarty/plugins/modifier.date_format.php';
$_smarty_tpl->_assignInScope('container_class', "container" ,false ,2);
?>

<?php $_smarty_tpl->_assignInScope('title', "Schichten" ,false ,2);
?>

<div class="row mb-4" id="error_container" style="display:none;">
    <div class="col">
        <div class="content-box bg-danger text-white" id="error_content"></div>
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

<form action="/schichten" method="post">
    <div class="row mb-4">
        <div class="col">
            <div class="content-box overflow-x-unset">
                <h3 class="content-box-title">Kalenderwoche auswählen</h3>
                <div class="row">
                    <div class="col-12 mb-3 col-md-auto mb-md-0">
                        <div class="btn-group d-flex w-100" role="group">
                            <button type="button" class="btn btn-secondary" id="vorige_kw"><i class="fa fa-angle-left"></i></button>
                            <button type="button" class="btn btn-secondary w-100" id="aktuelle_kw">Aktuelle K<span class="hidden-sm-up">W</span><span class="hidden-xs-down hidden-md-up">alenderwoche</span><span class="hidden-sm-down">W</span></button>
                            <button type="button" class="btn btn-secondary" id="naechste_kw"><i class="fa fa-angle-right"></i></button>
                        </div>
                    </div>
                    <div class="col-12 col-md">
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

                            <select class="selectable form-control" id="kalenderwoche" tabindex="-1" name="kalenderwoche" data-placeholder="-- bitte auswählen" required>
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
    <?php if ($_smarty_tpl->tpl_vars['smarty_vars']->value['kunden_auswaehlen_anzeigen']) {?>
        <div class="row mb-4">
            <div class="col">
                <div class="content-box">
                    <h3 class="content-box-title">Kunden auswählen</h3>
                    <select class="selectable selectable_multiple form-control" id="kunden" tabindex="-1" name="kunden[]" data-placeholder=" hier klicken..." multiple>
                        <?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['kundenliste'])) {?>
                            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['smarty_vars']->value['kundenliste'], 'row');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['row']->value) {
?>
                                <option value="<?php echo $_smarty_tpl->tpl_vars['row']->value['kundennummer'];?>
" <?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['kunde'])) {?> <?php if ($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['kunde'] == $_smarty_tpl->tpl_vars['row']->value['kundennummer']) {?> selected<?php }
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
    <?php }?>
    <div class="row mb-4">
        <div class="col">
            <div class="content-box">
                <button type="submit" class="btn btn-secondary form-control">Schichtplaner öffnen</button>
            </div>
        </div>
    </div>
</form>

<?php $_smarty_tpl->_assignInScope('css', '
    <!-- Select2 -->
    <link href="/assets/vendors/select2/dist/css/select2.min.css" rel="stylesheet">

    <!-- Select2 -->
    <style>
        .select2-container .select2-selection--multiple {
            min-height: 100px;
        }

        @media (max-width: 575px) {
            .dropdown-toggle::after {
                margin-left: 0;
            }

            .p-xs-down-1 {
                padding: .5rem .5rem;
            }

            .select2-selection__rendered {
                padding-right: 0 !important;
            }
        }

        .select2-selection--multiple {
            border: 1px solid rgba(0,0,0,.15) !important;
            border-radius: 0 .25rem .25rem 0 !important;
        }

        .select2-selection--single .select2-selection__arrow {
            height: calc(2rem + 6px) !important;
        }
    </style>

    <!-- General -->
    <style>
        .overflow-x-unset {
            overflow-x: unset !important;
        }
    </style>
' ,false ,2);
?>

<?php $_smarty_tpl->smarty->ext->_capture->open($_smarty_tpl, 'js', null, null);
?>

    <!-- Select2 -->
    <?php echo '<script'; ?>
 src="/assets/vendors/select2/dist/js/select2.full.min.js"><?php echo '</script'; ?>
>

    <!-- Select2 -->
    <?php echo '<script'; ?>
>
        $(document).ready(function() {
            $(".selectable").select2({
                allowClear: false,
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
    <!-- /Select2 -->

    <!-- General -->
    <?php echo '<script'; ?>
>
        $kalenderwochen_are_being_loaded = false;

        $(document).ready(function () {
            // minimize DOM access by saving objects in vars
            $kalenderwoche = $("#kalenderwoche");
            $kunden = $("#kunden");
            $jahr = $("#jahr");
            $vorige_kw = $("#vorige_kw");
            $aktuelle_kw = $("#aktuelle_kw");
            $naechste_kw = $("#naechste_kw");
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

            // Jahresauswahl button
            $jahresauswahl.click(function () {
                if ($jahr.val() != $(this).data("jahr")) {
                    changeJahr($(this).data("jahr"), function () {});
                }
            });

            // Aktuelle KW
            $aktuelle_kw.click(function () {
                changeJahr(<?php echo smarty_modifier_date_format(time(),"Y");?>
, function() {
                    $kalenderwoche.val(<?php echo smarty_modifier_date_format(time(),"W");?>
).change();
                });
            });

            // Vorige KW
            $vorige_kw.click(function () {
                changeJahr(<?php echo $_smarty_tpl->tpl_vars['smarty_vars']->value['prev']['year'];?>
, function() {
                    $kalenderwoche.val(<?php echo $_smarty_tpl->tpl_vars['smarty_vars']->value['prev']['week'];?>
).change();
                });
            });

            // Nächste KW
            $naechste_kw.click(function () {
                changeJahr(<?php echo $_smarty_tpl->tpl_vars['smarty_vars']->value['next']['year'];?>
, function() {
                    $kalenderwoche.val(<?php echo $_smarty_tpl->tpl_vars['smarty_vars']->value['next']['week'];?>
).change();
                });
            });
        });
    <?php echo '</script'; ?>
>
<?php $_smarty_tpl->smarty->ext->_capture->close($_smarty_tpl);
?>


<?php $_smarty_tpl->_assignInScope('js', $_smarty_tpl->smarty->ext->_capture->getBuffer($_smarty_tpl, 'js') ,false ,2);
}
}
