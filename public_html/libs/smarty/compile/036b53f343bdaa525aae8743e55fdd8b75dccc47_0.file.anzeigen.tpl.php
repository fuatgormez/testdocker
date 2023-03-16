<?php
/* Smarty version 3.1.30, created on 2019-07-11 12:18:15
  from "/usr/local/www/apache24/noexec/ttact-intern-software/views/main/Rechnungen/anzeigen.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5d270ce703c159_65477383',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '036b53f343bdaa525aae8743e55fdd8b75dccc47' => 
    array (
      0 => '/usr/local/www/apache24/noexec/ttact-intern-software/views/main/Rechnungen/anzeigen.tpl',
      1 => 1562525856,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5d270ce703c159_65477383 (Smarty_Internal_Template $_smarty_tpl) {
if (!is_callable('smarty_modifier_date_format')) require_once '/usr/local/www/apache24/noexec/ttact-intern-software/libs/smarty/plugins/modifier.date_format.php';
$_smarty_tpl->_assignInScope('container_class', "container-fluid" ,false ,2);
?>

<?php $_smarty_tpl->_assignInScope('title', "Rechnungen | anzeigen" ,false ,2);
?>
<div class="alert alert-danger my-alert-top sticky-top" id="error-alert" style="display:none;">
    <strong>Fehler! </strong>
     <span id="error_content"></span>
</div>
<div class="alert alert-success my-alert-top sticky-top" id="success-alert" style="display:none;">
    <strong>Erfolg! </strong>
     <span id="success_content"></span>
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
            
            <form id="rechnungenform" action="/rechnungen/anzeigen" method="post">
                <div class="row">
                    <div class="form-group col-12 col-sm-6">
                        <label class="form-control-label" for="monat">Monat</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-calendar fa-fw"></i></span>
                            <select name="monat" id="monat" class="form-control" required>
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
                <button type="submit" class="btn btn-secondary form-control">Rechnungen anzeigen</button>
            </form>
        </div>
    </div>
</div>
	<div class="row mb-4">
		<div class="col">
			<div class="content-box">
			<div class="row">
				<div class="col-sm-2">
					
				</div>
				<div class="col-sm-1">
					
				</div>
				<div class="col-sm-4">
				</div>

				<label class="col-sm-1 col-form-label-sm">Filter:</label>
				<div class="col-sm-3">
					<select class="form-control-sm" id="rfilter" name="rfilter"  form="rechnungenform">
						<option value="0" {if isset($rfilter)}{if $rfilter == 0} selected{/if}{/if}>Zeige Alle Rechnungen</option>
						<option value="1" {if isset($rfilter)}{if $rfilter == 1} selected{/if}{/if}>Zeige unbezahlte Rechnungen</option>
						<option value="2" {if isset($rfilter)}{if $rfilter == 2} selected{/if}{/if}>Zeige bezahlte Rechnungen</option>
						<option value="3" {if isset($rfilter)}{if $rfilter == 3} selected{/if}{/if}>Zeige Storno Rechnungen</option>
					</select>
				</div>
				<input type="hidden" name="letzermonat" id="letzermonat" value="{$aktivtab}">
				<div class="col-sm-1">
					<button name="filtern" type="submit" form="rechnungenform" class="btn btn-primary btn-sm">Filtern</button>
				</div>
			</div>
			</div>
		</div>
	</div>
<?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['rechnungsliste'])) {?>
    <form action="/rechnungen/anzeigen" method="post">
        <div class="row mb-4">
            <div class="col">
                <div class="content-box">
                    <table class="table table-striped table-bordered table-hover dt-responsive nowrap mb-0" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <td class="borderless  sorter-false"></td>
                                <td class="borderless sorter-false"></td>
                                <td class="borderless sorter-false"></td>
                                <td class="borderless sorter-false"></td>
                                <td class="borderless sorter-false"></td>
                                <td class="text-right borderless sorter-false"><?php echo $_smarty_tpl->tpl_vars['smarty_vars']->value['total_brutto'];?>
</td>
                                <td class="text-right borderless sorter-false"><?php echo $_smarty_tpl->tpl_vars['smarty_vars']->value['total_netto'];?>
</td>
                                <td class="borderless sorter-false"></td>
                                <td class="borderless sorter-false"></td>
                                <td class="borderless sorter-false"></td>
                            </tr>
                            <tr>
                                <th>&nbsp;</th>
                                <th>Aktion</th>
                                <th>Rechnungsnr.</th>
                                <th>Datum</th>
                                <th>Kunde</th>
                                <th class="text-right">Brutto</th>
                                <th class="text-right">Netto</th>
                                <th>Zahlungsziel</th>
                                <th>Status</th>
                                <th>Kommentar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['smarty_vars']->value['rechnungsliste'], 'row');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['row']->value) {
?>
                                <tr <?php if ($_smarty_tpl->tpl_vars['row']->value['rechnung_status'] == 2) {?> class="custom-danger"<?php }?> id="row<?php echo $_smarty_tpl->tpl_vars['row']->value['rechnung_id'];?>
">
                                    <td>
                                        <input type="checkbox" name="export[]" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['row']->value['export'])===null||$tmp==='' ? "???" : $tmp);?>
" checked>
                                    </td>
                                    <td class="p-1 text-white">
                                        <?php if ($_smarty_tpl->tpl_vars['row']->value['rechnung_status'] != 2) {?>
                                            <a role="button" class="btn btn-warning btn-block btn-sm" data-toggle="popover" title="Bestätigen" data-trigger="click" data-content="<input type='button' onClick='setStornierung(<?php echo $_smarty_tpl->tpl_vars['row']->value['rechnung_id'];?>
)' class='btn btn-warning btn-block btn-sm' value='Stornieren'>" id="popover" data-placement="top">Stornieren</a>
                                        <?php }?>
                                    </td>

                                    <td class="clickable p-1" data-href="/rechnungen/pdf/<?php echo $_smarty_tpl->tpl_vars['row']->value['rechnung_id'];?>
"  data-target="_blank"><strong><?php echo $_smarty_tpl->tpl_vars['row']->value['rechnungsnummer'];?>
</strong></td>
                                    <td class="clickable p-1" data-href="/rechnungen/pdf/<?php echo $_smarty_tpl->tpl_vars['row']->value['rechnung_id'];?>
" data-target="_blank"><?php echo $_smarty_tpl->tpl_vars['row']->value['datum'];?>
</td>
                                    <td class="clickable p-1" data-href="/rechnungen/pdf/<?php echo $_smarty_tpl->tpl_vars['row']->value['rechnung_id'];?>
" data-target="_blank"><?php echo $_smarty_tpl->tpl_vars['row']->value['kunde'];?>
</td>
                                    <td class="clickable text-right p-1" data-href="/rechnungen/pdf/<?php echo $_smarty_tpl->tpl_vars['row']->value['rechnung_id'];?>
" data-target="_blank"><?php echo $_smarty_tpl->tpl_vars['row']->value['brutto'];?>
</td>
                                    <td class="clickable text-right p-1" data-href="/rechnungen/pdf/<?php echo $_smarty_tpl->tpl_vars['row']->value['rechnung_id'];?>
" data-target="_blank"><?php echo $_smarty_tpl->tpl_vars['row']->value['netto'];?>
</td>
                                    <td class="clickable p-1 text-center" data-href="/rechnungen/pdf/<?php echo $_smarty_tpl->tpl_vars['row']->value['rechnung_id'];?>
" data-target="_blank"><?php echo $_smarty_tpl->tpl_vars['row']->value['zahlungsziel'];?>
</td>

                                    <td id="status<?php echo $_smarty_tpl->tpl_vars['row']->value['rechnung_id'];?>
" class="p-1 text-white text-center">
                                        <?php if ($_smarty_tpl->tpl_vars['row']->value['rechnung_status'] == 1) {?>
                                            <a role="button" href="#" class="btn btn-success btn-block btn-sm" data-toggle="popover" title="Bestätigen" data-trigger="click" data-content="<input type='text' class='form-control' id='datum<?php echo $_smarty_tpl->tpl_vars['row']->value['rechnung_id'];?>
' onkeyup='setPaid(event,<?php echo $_smarty_tpl->tpl_vars['row']->value['rechnung_id'];?>
)' value='<?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['row']->value['bezahltam'],"%d.%m.%Y");?>
'><input type='button' onClick='setPaid(<?php echo $_smarty_tpl->tpl_vars['row']->value['rechnung_id'];?>
)' class='btn btn-success btn-block btn-sm' value='Speichern'>" id="popover" data-placement="top">bezahlt am <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['row']->value['bezahltam'],"%d.%m.%Y");?>
</a>
                                        <?php } elseif ($_smarty_tpl->tpl_vars['row']->value['rechnung_status'] == 2) {?>
                                            <a role="button" href="#" class="btn btn-warning btn-block btn-sm">Stornierung am <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['row']->value['stornierungsdatum'],"%d.%m.%Y");?>
</a>
                                        <?php } else { ?>
                                            <a role="button" href="#" class="btn btn-danger btn-block btn-sm" data-toggle="popover" title="Bestätigen" data-trigger="click" data-content="<input type='text' class='form-control' id='datum<?php echo $_smarty_tpl->tpl_vars['row']->value['rechnung_id'];?>
' value='' onkeyup='setPaid2(event,<?php echo $_smarty_tpl->tpl_vars['row']->value['rechnung_id'];?>
)'>" id="popover" data-placement="top" >OFFEN</a>
                                        <?php }?>
                                    </td>
                                    <td class="p-1">
                                        <input type="text"  class="form-control"  id="kommentar<?php echo $_smarty_tpl->tpl_vars['row']->value['rechnung_id'];?>
" onChange="setKommentar(<?php echo $_smarty_tpl->tpl_vars['row']->value['rechnung_id'];?>
)" value="<?php echo $_smarty_tpl->tpl_vars['row']->value['kommentar'];?>
">
                                    </td>
                                    <input class="filter tablesorter-filter" type="hidden" value="<?php echo $_smarty_tpl->tpl_vars['row']->value['rechnung_status'];?>
" data-column="10">
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
                    <input type="hidden" name="filename" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['filename'])===null||$tmp==='' ? "???" : $tmp);?>
">
                    <button type="submit" name="action" value="exportieren" class="btn btn-secondary btn-block" onclick='this.form.target="_blank";'>Für Excel exportieren</button>
                </div>
            </div>
        </div>
    </form>
<?php }?>

<?php $_smarty_tpl->smarty->ext->_capture->open($_smarty_tpl, 'css', null, null);
?>

    <style>
        .clickable:hover {
            cursor: pointer;
        }
    </style>
<?php $_smarty_tpl->smarty->ext->_capture->close($_smarty_tpl);
?>


<?php $_smarty_tpl->_assignInScope('css', $_smarty_tpl->smarty->ext->_capture->getBuffer($_smarty_tpl, 'css') ,false ,2);
?>

<?php $_smarty_tpl->smarty->ext->_capture->open($_smarty_tpl, 'scripts', null, null);
?>

<link href="/assets/vendors/tablesorter-2.17.8/css/theme.default.css" rel="stylesheet">
<?php echo '<script'; ?>
 src="/assets/vendors/tablesorter-2.17.8/js/jquery.tablesorter.min.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="/assets/vendors/tablesorter-2.17.8/js/jquery.tablesorter.widgets.min.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
>
    $(document).ready(function () {
        $("tbody .clickable").click(function () {
            var $th = $(this);
            $th.on('click', function() {
                window.open($th.attr('data-href'), $th.attr('data-target'));
            });
        });

        $('table').tablesorter({
            widgets: [
                'zebra',
                'columns',
                'filter',
                'stickyHeaders'
            ],
            headers: {
                8: {filter: false},
                7: {sorter: false},
                8: {sorter: false},
                0: {sorter: false}
            },
            usNumberFormat: false,
            sortReset: true,
            sortRestart: true,

        });

        $('.custom-danger').each(function(index) {
            $(this).children('td').addClass('custom-danger');
        });
    });

    selectmonat = 1;

    $error_container = $("#error_container");
    $error_content = $("#error_content");
    $success_content = $("#success_content");

    function showError(message) {
        $error_content.html(message);
        $("#error-alert").show();
        window.setTimeout(function() {
            $("#error-alert").hide();
        }, 4000);
    }
    function showSuccess(message) {
        $("#success_content").html(message);
        $("#success-alert").show();
        window.setTimeout(function() {
            $("#success-alert").hide();
        }, 4000);
    }

    $(function () {
        $('[data-toggle="popover"]').popover({
            container: 'body',
            html: true
        })
    })

    function setKommentar(rechnung_id) {
        $.ajax({
            type: "POST",
            url: "/rechnungen/ajax",
            data: {
                type: 'kommentar',
                kommentar: $('#kommentar' + rechnung_id).val(),
                rechnung_id: rechnung_id
            },
            success: function ($return) {
                if ($return.hasOwnProperty('status')) {
                    if ($return.status == "success") {
                        showSuccess($return.data.message);
                    } else if ($return.status == "error") {
                        showError($return.data.message);
                    } else if ($return.status == "not_logged_in") {
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

    function setPaid2(e,rechnung_id) {
        var unicode = e.keyCode ? e.keyCode : e.charCode;
        if (unicode == 13) {
            setPaid(rechnung_id);
        }
    }

    function setPaid(rechnung_id) {
        $.ajax({
            type: "POST",
            url: "/rechnungen/ajax",
            data: {type: 'bezahltam', datum: $("#datum"+rechnung_id).val(), rechnung_id:rechnung_id },
            success: function ($return) {
                $('[data-toggle="popover"]').popover('hide');
                if ($return.hasOwnProperty('status')) {
                    if ($return.status == "success") {
                        showSuccess($return.data.message);
                        $("#status"+rechnung_id).html($return.data.content);
                    } else if ($return.status == "error") {
                        showError($return.data.message);
                    } else if ($return.status == "not_logged_in") {
                        location.reload();
                    } else {
                        showError("Es ist ein Fehler aufgetreten.");
                    }
                } else {
                    showError("Es ist ein Fehler aufgetreten.");
                }
            },
            error: function () {
                $('[data-toggle="popover"]').popover('hide');
                showError("Es ist ein Fehler aufgetreten.");
            }
        });
    }
    function setStornierung(rechnung_id) {
        $.ajax({
            type: "POST",
            url: "/rechnungen/ajax",
            data: {type: 'stornierung',  rechnung_id:rechnung_id },
            success: function ($return) {
                if ($return.hasOwnProperty('status')) {
                    $('[data-toggle="popover"]').popover('hide');
                    if ($return.status == "success") {

                        showSuccess($return.data.message);
                        $("#status" + rechnung_id).html($return.data.content);
                        $("#row" + rechnung_id).each(function(index) {
                            $(this).children('td').first().children('a').first().addClass('text-hide');	$(this).children('td').addClass('custom-danger');
                        });

                    } else if ($return.status == "error") {
                        showError($return.data.message);
                    } else if ($return.status == "not_logged_in") {
                        location.reload();
                    } else {
                        showError("Es ist ein Fehler aufgetreten.");
                    }
                } else {
                    $('[data-toggle="popover"]').popover('hide');
                    showError("Es ist ein Fehler aufgetreten.");
                }
            },
            error: function () {
                showError("Es ist ein Fehler aufgetreten.");
            }
        });
    }
<?php echo '</script'; ?>
>
<?php $_smarty_tpl->smarty->ext->_capture->close($_smarty_tpl);
?>


<?php $_smarty_tpl->_assignInScope('js', $_smarty_tpl->smarty->ext->_capture->getBuffer($_smarty_tpl, 'scripts') ,false ,2);
}
}
