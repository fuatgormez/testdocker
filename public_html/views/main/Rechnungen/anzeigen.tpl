{{$container_class="container-fluid" scope=parent}}

{{$title="Rechnungen | anzeigen" scope=parent}}
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
            {{if isset($smarty_vars.success)}}
            <div class="alert alert-success">
                {{$smarty_vars.success}}
            </div>
            {{/if}}
            {{if isset($smarty_vars.error)}}
            <div class="alert alert-danger">
                {{$smarty_vars.error}}
            </div>
            {{/if}}
            
            <form id="rechnungenform" action="/rechnungen/anzeigen" method="post">
                <div class="row">
                    <div class="form-group col-12 col-sm-6">
                        <label class="form-control-label" for="monat">Monat</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-calendar fa-fw"></i></span>
                            <select name="monat" id="monat" class="form-control" required>
                                <option value="1"{{if isset($smarty_vars.values.monat)}}{{if $smarty_vars.values.monat == 1}} selected{{/if}}{{/if}}>Januar</option>
                                <option value="2"{{if isset($smarty_vars.values.monat)}}{{if $smarty_vars.values.monat == 2}} selected{{/if}}{{/if}}>Februar</option>
                                <option value="3"{{if isset($smarty_vars.values.monat)}}{{if $smarty_vars.values.monat == 3}} selected{{/if}}{{/if}}>März</option>
                                <option value="4"{{if isset($smarty_vars.values.monat)}}{{if $smarty_vars.values.monat == 4}} selected{{/if}}{{/if}}>April</option>
                                <option value="5"{{if isset($smarty_vars.values.monat)}}{{if $smarty_vars.values.monat == 5}} selected{{/if}}{{/if}}>Mai</option>
                                <option value="6"{{if isset($smarty_vars.values.monat)}}{{if $smarty_vars.values.monat == 6}} selected{{/if}}{{/if}}>Juni</option>
                                <option value="7"{{if isset($smarty_vars.values.monat)}}{{if $smarty_vars.values.monat == 7}} selected{{/if}}{{/if}}>Juli</option>
                                <option value="8"{{if isset($smarty_vars.values.monat)}}{{if $smarty_vars.values.monat == 8}} selected{{/if}}{{/if}}>August</option>
                                <option value="9"{{if isset($smarty_vars.values.monat)}}{{if $smarty_vars.values.monat == 9}} selected{{/if}}{{/if}}>September</option>
                                <option value="10"{{if isset($smarty_vars.values.monat)}}{{if $smarty_vars.values.monat == 10}} selected{{/if}}{{/if}}>Oktober</option>
                                <option value="11"{{if isset($smarty_vars.values.monat)}}{{if $smarty_vars.values.monat == 11}} selected{{/if}}{{/if}}>November</option>
                                <option value="12"{{if isset($smarty_vars.values.monat)}}{{if $smarty_vars.values.monat == 12}} selected{{/if}}{{/if}}>Dezember</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-12 col-sm-6">
                        <label class="form-control-label" for="jahr">Jahr</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-calendar fa-fw"></i></span>
                            <input type="text" class="form-control" id="jahr" name="jahr" placeholder="JJJJ" value="{{$smarty_vars.values.jahr|default:""}}" required>
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
{{if isset($smarty_vars.rechnungsliste)}}
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
                                <td class="text-right borderless sorter-false">{{$smarty_vars.total_brutto}}</td>
                                <td class="text-right borderless sorter-false">{{$smarty_vars.total_netto}}</td>
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
                            {{foreach from=$smarty_vars.rechnungsliste item='row'}}
                                <tr {{if $row.rechnung_status == 2}} class="custom-danger"{{/if}} id="row{{$row.rechnung_id}}">
                                    <td>
                                        <input type="checkbox" name="export[]" value="{{$row.export|default:"???"}}" checked>
                                    </td>
                                    <td class="p-1 text-white">
                                        {{if $row.rechnung_status != 2}}
                                            <a role="button" class="btn btn-warning btn-block btn-sm" data-toggle="popover" title="Bestätigen" data-trigger="click" data-content="<input type='button' onClick='setStornierung({{$row.rechnung_id}})' class='btn btn-warning btn-block btn-sm' value='Stornieren'>" id="popover" data-placement="top">Stornieren</a>
                                        {{/if}}
                                    </td>

                                    <td class="clickable p-1" data-href="/rechnungen/pdf/{{$row.rechnung_id}}"  data-target="_blank"><strong>{{$row.rechnungsnummer}}</strong></td>
                                    <td class="clickable p-1" data-href="/rechnungen/pdf/{{$row.rechnung_id}}" data-target="_blank">{{$row.datum}}</td>
                                    <td class="clickable p-1" data-href="/rechnungen/pdf/{{$row.rechnung_id}}" data-target="_blank">{{$row.kunde}}</td>
                                    <td class="clickable text-right p-1" data-href="/rechnungen/pdf/{{$row.rechnung_id}}" data-target="_blank">{{$row.brutto}}</td>
                                    <td class="clickable text-right p-1" data-href="/rechnungen/pdf/{{$row.rechnung_id}}" data-target="_blank">{{$row.netto}}</td>
                                    <td class="clickable p-1 text-center" data-href="/rechnungen/pdf/{{$row.rechnung_id}}" data-target="_blank">{{$row.zahlungsziel}}</td>

                                    <td id="status{{$row.rechnung_id}}" class="p-1 text-white text-center">
                                        {{if $row.rechnung_status == 1}}
                                            <a role="button" href="#" class="btn btn-success btn-block btn-sm" data-toggle="popover" title="Bestätigen" data-trigger="click" data-content="<input type='text' class='form-control' id='datum{{$row.rechnung_id}}' onkeyup='setPaid(event,{{$row.rechnung_id}})' value='{{$row.bezahltam|date_format:"%d.%m.%Y"}}'><input type='button' onClick='setPaid({{$row.rechnung_id}})' class='btn btn-success btn-block btn-sm' value='Speichern'>" id="popover" data-placement="top">bezahlt am {{$row.bezahltam|date_format:"%d.%m.%Y"}}</a>
                                        {{elseif $row.rechnung_status == 2}}
                                            <a role="button" href="#" class="btn btn-warning btn-block btn-sm">Stornierung am {{$row.stornierungsdatum|date_format:"%d.%m.%Y"}}</a>
                                        {{else}}
                                            <a role="button" href="#" class="btn btn-danger btn-block btn-sm" data-toggle="popover" title="Bestätigen" data-trigger="click" data-content="<input type='text' class='form-control' id='datum{{$row.rechnung_id}}' value='' onkeyup='setPaid2(event,{{$row.rechnung_id}})'>" id="popover" data-placement="top" >OFFEN</a>
                                        {{/if}}
                                    </td>
                                    <td class="p-1">
                                        <input type="text"  class="form-control"  id="kommentar{{$row.rechnung_id}}" onChange="setKommentar({{$row.rechnung_id}})" value="{{$row.kommentar}}">
                                    </td>
                                    <input class="filter tablesorter-filter" type="hidden" value="{{$row.rechnung_status}}" data-column="10">
                                </tr>
                            {{/foreach}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col">
                <div class="content-box">
                    <input type="hidden" name="filename" value="{{$smarty_vars.filename|default:"???"}}">
                    <button type="submit" name="action" value="exportieren" class="btn btn-secondary btn-block" onclick='this.form.target="_blank";'>Für Excel exportieren</button>
                </div>
            </div>
        </div>
    </form>
{{/if}}

{{capture name='css'}}
    <style>
        .clickable:hover {
            cursor: pointer;
        }
    </style>
{{/capture}}

{{$css=$smarty.capture.css scope=parent}}

{{capture name='scripts'}}
<link href="/assets/vendors/tablesorter-2.17.8/css/theme.default.css" rel="stylesheet">
<script src="/assets/vendors/tablesorter-2.17.8/js/jquery.tablesorter.min.js"></script>
<script src="/assets/vendors/tablesorter-2.17.8/js/jquery.tablesorter.widgets.min.js"></script>
<script>
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
</script>
{{/capture}}

{{$js=$smarty.capture.scripts scope=parent}}