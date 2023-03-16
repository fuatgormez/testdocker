{{$container_class="container" scope=parent}}

{{$title="Mitarbeiter | Notizen" scope=parent}}
{{$hide_title=true scope=parent}}

<div class="row mb-4 print-hide">
    <div class="col">
        <div class="title-box text-white p-4">
            <h3 class="mb-0">Mitarbeiter | Notizen</h3>
        </div>
    </div>
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

            <div class="alert alert-info print-hide">
                Bitte beachten Sie, dass nur für diejenigen Mitarbeiter Notizen angezeigt werden, die im eingegebenen Zeitraum für die Lohnabrechnung relevant wären.
            </div>

            <form action="/mitarbeiter/notizen" method="post" class="print-hide">
                <div class="row">
                    <div class="form-group col-12 col-sm-6">
                        <label class="form-control-label" for="monat">Monat</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-calendar fa-fw"></i></span>
                            <select class="form-control selectable" id="monat" tabindex="-1" name="monat" required>
                                <option value="">-- bitte auswählen</option>
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
                <button type="submit" class="btn btn-secondary form-control">Alle Notizen anzeigen</button>
            </form>
        </div>
    </div>
</div>

{{if isset($smarty_vars.notizenliste)}}
    <div class="row mb-4">
        <div class="col">
            <div class="content-box">
                {{foreach from=$smarty_vars.notizenliste item='row'}}
                    <div><strong>{{$row.personalnummer}} - {{$row.nachname}}, {{$row.vorname}}</strong></div>
                    <div class="mb-4">{{$row.notiz}}</div>
                {{/foreach}}
            </div>
        </div>
    </div>
{{/if}}

{{capture name='css'}}
    <!-- Select2 -->
    <link href="/assets/vendors/select2/dist/css/select2.min.css" rel="stylesheet">

    <style>
        @media print {
            .container {
                width: auto;
            }

            nav.navbar, .print-hide {
                display: none;
            }

            body {
                background: white !important;
            }
        }
    </style>
{{/capture}}

{{$css=$smarty.capture.css scope=parent}}

{{capture name='scripts'}}
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