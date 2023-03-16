{{$title="Berechnungen | Stunden" scope=parent}}

<div class="container">

    <div class="row mb-4" id="error-box" style="display:none;">
        <div class="col">
            <div class="content-box bg-warning text-white" id="error-content">
            </div>
        </div>
    </div>

    {{if isset($smarty_vars.error)}}
        <div class="row mb-4">
            <div class="col">
                <div class="content-box bg-danger text-white">
                    {{$smarty_vars.error}}
                </div>
            </div>
        </div>
    {{/if}}

    {{if isset($smarty_vars.success)}}
        <div class="row mb-4">
            <div class="col">
                <div class="content-box bg-success text-white">
                    {{$smarty_vars.success}}
                </div>
            </div>
        </div>
    {{/if}}

    <div class="row mb-4">
        <div class="col">
            <div class="content-box">
                <form action="/berechnungen/stunden" method="post">
                    <div class="row">
                        <div class="form-group col-12 col-sm-6">
                            <label class="form-control-label" for="von">Von <span class="required">*</span></label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-calendar fa-fw"></i></span>
                                <input type="text" class="form-control datum" id="von" name="von" placeholder="TT.MM.JJJJ" value="{{$smarty_vars.values.von|default:""}}" required>
                            </div>
                        </div>
                        <div class="form-group col-12 col-sm-6">
                            <label class="form-control-label" for="bis">Bis <strong>inklusive</strong> <span class="required">*</span></label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-calendar fa-fw"></i></span>
                                <input type="text" class="form-control datum" id="bis" name="bis" placeholder="TT.MM.JJJJ" value="{{$smarty_vars.values.bis|default:""}}" required>
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
                                    {{if isset($smarty_vars.kundenliste)}}
                                        {{foreach from=$smarty_vars.kundenliste item='row'}}
                                            <option value="{{$row.id}}" {{if isset($smarty_vars.values.kunde)}} {{if $smarty_vars.values.kunde == $row.id}} selected{{/if}}{{/if}}>{{$row.kundennummer}} - {{$row.name}}</option>
                                        {{/foreach}}
                                    {{/if}}
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

{{if isset($smarty_vars.liste)}}
    {{if count($smarty_vars.liste) > 0}}
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
                                {{foreach from=$smarty_vars.liste item='row'}}
                                    <tr{{if $row.insgesamt}} class="bg-primary text-white"{{/if}}>
                                        {{if $row.insgesamt}}
                                            <td>&nbsp;</td>
                                        {{else}}
                                            <td><input type="checkbox" name="export[]" value="{{$row.export|default:"???"}}" checked></td>
                                        {{/if}}
                                        <td>{{$row.personalnummer|default:"???"}} - {{$row.nachname|default:"???"}}, {{$row.vorname|default:"???"}}</td>
                                        {{if $row.insgesamt}}
                                            <td>Insgesamt</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                        {{else}}
                                            <td>{{$row.kundennummer}} - {{$row.kundenname}}</td>
                                            <td>{{$row.abteilung}}</td>
                                            <td>{{$row.datum}}</td>
                                            <td>{{$row.von}}</td>
                                            <td>{{$row.bis}}</td>
                                            <td>{{$row.pause}}</td>
                                        {{/if}}
                                        <td class="text-right">{{$row.stunden|default:"???"}}</td>
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
                        <input type="hidden" name="filename" value="{{$smarty_vars.values.filename|default:"???"}}">
                        <input type="hidden" name="action" value="exportieren">
                        <button type="submit" name="action" value="exportieren" class="btn btn-secondary btn-block" onclick='this.form.target="_blank";'>Für Excel exportieren</button>
                    </div>
                </div>
            </div>
        </form>
    {{else}}
        <div class="row mb-4">
            <div class="col">
                <div class="content-box">
                    <div class="p-4 text-center">
                        Es wurden keine Ergebnisse gefunden.
                    </div>
                </div>
            </div>
        </div>
    {{/if}}
{{/if}}

{{capture name='css'}}
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
{{/capture}}

{{$css=$smarty.capture.css scope=parent}}

{{capture name='scripts'}}
    <!-- moment -->
    <script src="/assets/vendors/bootstrap-daterangepicker-master/moment.min.js"></script>

    <!-- daterangepicker -->
    <script src="/assets/vendors/bootstrap-daterangepicker-master/daterangepicker.js"></script>
    <script>
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
    </script>

    <!-- Select2 -->
    <script src="/assets/vendors/select2/dist/js/select2.full.min.js"></script>
    <script>
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
    </script>

    <!-- Abteilungsliste und Mitarbeiterliste -->
    <script>
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
                {{if isset($smarty_vars.values.abteilung)}}
                    abteilung.val('{{$smarty_vars.values.abteilung}}').change();
                {{/if}}

                {{if isset($smarty_vars.values.mitarbeiter)}}
                    mitarbeiter.val('{{$smarty_vars.values.mitarbeiter}}').change();
                {{/if}}
            });
        });
    </script>
{{/capture}}

{{$js=$smarty.capture.scripts scope=parent}}
