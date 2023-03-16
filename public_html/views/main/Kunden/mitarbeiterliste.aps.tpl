{{$container_class="container" scope=parent}}

{{$title="Kunden | Mitarbeiterliste" scope=parent}}

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

            <form action="/kunden/mitarbeiterliste" method="post">
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
                        <label class="form-control-label" for="kunde">Kunde{{if $smarty_vars.kunde_pflichtangabe}} <span class="required">*</span>{{/if}}</label>
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
                <button type="submit" name="action" value="berechnen" class="btn btn-secondary form-control">Anzeigen</button>
            </form>
        </div>
    </div>
</div>

{{if isset($smarty_vars.liste)}}
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
                            {{foreach from=$smarty_vars.liste item='row'}}
                                <tr>
                                    <td><input type="checkbox" name="export[]" value="{{$row.export|default:"???"}}" checked></td>
                                    <td>
                                        <button type="submit" name="anlage2" value="{{$row.anlage2_data|default:"???"}}" class="btn btn-secondary btn-block" onclick='this.form.target="_blank";'>Anlage 2</button>
                                    </td>
                                    <td>{{$row.personalnummer|default:"???"}} - {{$row.nachname|default:"???"}}, {{$row.vorname|default:"???"}}</td>
                                    <td>{{$row.taetigkeit|default:""}}</td>
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
                    <input type="hidden" name="von" value="{{$smarty_vars.values.von|default:"???"}}">
                    <input type="hidden" name="bis" value="{{$smarty_vars.values.bis|default:"???"}}">
                    <input type="hidden" name="kunde" value="{{$smarty_vars.values.kunde|default:"???"}}">
                    <input type="hidden" name="filename" value="{{$smarty_vars.values.filename|default:"???"}}">
                    <button type="submit" name="exportieren" value="true" class="btn btn-secondary btn-block" onclick='this.form.target="_blank";'>Für Excel exportieren</button>
                </div>
            </div>
        </div>
    </form>
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
