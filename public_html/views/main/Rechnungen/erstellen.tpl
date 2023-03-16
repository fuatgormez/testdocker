{{$container_class="container" scope=parent}}

{{$title="Rechnungen | erstellen" scope=parent}}

<div class="row mb-4">
    <div class="col">
        <div class="content-box">
            <h4 class="mb-4">Rechnungsdaten</h4>

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

            <form action="/rechnungen/erstellen" method="post">
                <div class="row">
                    <div class="form-group col-12 col-sm-6">
                        <label class="form-control-label" for="von">Von <span class="required">*</span></label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-calendar fa-fw"></i></span>
                            <input type="text" class="form-control datum" id="von" name="von" placeholder="TT.MM.JJJJ" value="{{$smarty_vars.values.von|default:""}}" required>
                        </div>
                    </div>
                    <div class="form-group col-12 col-sm-6">
                        <label class="form-control-label" for="bis">Bis <strong>inklusive</strong><span class="required">*</span></label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-calendar fa-fw"></i></span>
                            <input type="text" class="form-control datum" id="bis" name="bis" placeholder="TT.MM.JJJJ" value="{{$smarty_vars.values.bis|default:""}}" required>
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
                        <label class="form-control-label" for="rechnungsdatum">Rechnungsdatum <span class="required">*</span></label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-calendar fa-fw"></i></span>
                            <input type="text" class="form-control datum" id="rechnungsdatum" name="rechnungsdatum" placeholder="TT.MM.JJJJ" value="{{$smarty_vars.values.rechnungsdatum|default:""}}" required>
                        </div>
                    </div>
                    <div class="form-group col-12 col-sm-6">
                        <label class="form-control-label" for="zahlungsziel">Zahlungsziel <span class="required">*</span></label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-calendar fa-fw"></i></span>
                            <input type="text" class="form-control datum" id="zahlungsziel" name="zahlungsziel" placeholder="TT.MM.JJJJ" value="{{$smarty_vars.values.zahlungsziel|default:""}}" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-12">
                        <label class="form-control-label" for="kassendifferenz">Kassendifferenz</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-eur fa-fw"></i></span>
                            <input type="text" class="form-control" id="kassendifferenz" name="kassendifferenz" placeholder="45,50" value="{{$smarty_vars.values.kassendifferenz|default:""}}">
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-secondary form-control">Vorschau erstellen</button>
            </form>
        </div>
    </div>
</div>

{{if isset($smarty_vars.rechnungsliste)}}
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
                        {{foreach from=$smarty_vars.rechnungsliste item='row'}}
                            <tr>
                                <td>{{$row.leistungsart}}</td>
                                <td class="text-right">{{$row.menge|default:0|number_format:2:",":"."}}</td>
                                <td class="text-right">{{$row.einzelpreis|default:0|number_format:2:",":"."}}</td>
                                <td class="text-right">{{$row.gesamtpreis|default:0|number_format:2:",":"."}}</td>
                            </tr>
                        {{/foreach}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
{{/if}}

{{if isset($smarty_vars.gesamtbetrag)}}
    <div class="row mb-4">
        <div class="col">
            <div class="content-box">
                <h4 class="mb-4">Rechnungssumme</h4>
                <table class="table table-bordered dt-responsive nowrap mb-0" cellspacing="0" width="100%">
                    <tbody>
                        {{foreach from=$smarty_vars.gesamtbetrag item='row'}}
                            <tr>
                                <td>{{$row.name}}</td>
                                <td class="text-right">{{$row.betrag|default:0}}</td>
                            </tr>
                        {{/foreach}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
{{/if}}

{{if $smarty_vars.rechnung_ready_to_save}}
    <div class="row mb-4">
        <div class="col">
            <div class="content-box">
                <div class="row">
                    <div class="col-12 col-sm">
                        <form action="/rechnungen/pdf" method="post" target="_blank">
                            <input type="hidden" name="rechnung_data" value='{{$smarty_vars.rechnung_data|default:""}}'>
                            <input type="hidden" name="rechnungsposten_data" value='{{$smarty_vars.rechnungsposten_data|default:""}}'>
                            <button type="submit" class="btn btn-secondary btn-block">PDF anzeigen</button>
                        </form>
                    </div>
                    <div class="col-12 col-sm">
                        <form action="/rechnungen/bearbeiten" method="post">
                            <input type="hidden" name="rechnung_data" value='{{$smarty_vars.rechnung_data|default:""}}'>
                            <input type="hidden" name="rechnungsposten_data" value='{{$smarty_vars.rechnungsposten_data|default:""}}'>
                            <button type="submit" class="btn btn-secondary btn-block">Rechnung bearbeiten</button>
                        </form>
                    </div>
                    <div class="col-12 col-sm">
                        <form action="/rechnungen/anzeigen" method="post">
                            <input type="hidden" name="rechnung_data" value='{{$smarty_vars.rechnung_data|default:""}}'>
                            <input type="hidden" name="rechnungsposten_data" value='{{$smarty_vars.rechnungsposten_data|default:""}}'>
                            <button type="submit" class="btn btn-secondary btn-block">Rechnung speichern</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
{{/if}}

{{capture name='css'}}
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
    </script>
{{/capture}}

{{$js=$smarty.capture.scripts scope=parent}}
