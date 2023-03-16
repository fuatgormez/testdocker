{{$container_class="container" scope=parent}}

{{$title="Mitarbeiter | zusammensetzen" scope=parent}}

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
            
            <div class="alert alert-warning">
                <strong>Hinweis:</strong> Dieser Vorgang kann nicht rückgängig gemacht werden. Alle Daten des Von-Mitarbeiters werden mit dem Zu-Mitarbeiter verknüpft und der Von-Mitarbeiter anschließend unwiderrufbar gelöscht.
            </div>

            <form action="/mitarbeiter/zusammensetzen" method="post">
                <div class="row">
                    <div class="form-group col-12 col-lg-6">
                        <label class="form-control-label" for="von">Von <span class="required">*</span></label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-user fa-fw"></i></span>
                            <select class="form-control selectable" id="von" tabindex="-1" name="von" required>
                                <option value="">-- bitte auswählen</option>
                                {{if isset($smarty_vars.mitarbeiterliste)}}
                                    {{foreach from=$smarty_vars.mitarbeiterliste item='row'}}
                                        <option value="{{$row.id}}" {{if isset($smarty_vars.values.von)}} {{if $smarty_vars.values.von == $row.id}} selected{{/if}}{{/if}}>{{$row.personalnummer}} - {{$row.nachname}}, {{$row.vorname}}</option>
                                    {{/foreach}}
                                {{/if}}
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-12 col-lg-6">
                        <label class="form-control-label" for="zu">Zu <span class="required">*</span></label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-user fa-fw"></i></span>
                            <select class="form-control selectable" id="zu" tabindex="-1" name="zu" required>
                                <option value="">-- bitte auswählen</option>
                                {{if isset($smarty_vars.mitarbeiterliste)}}
                                    {{foreach from=$smarty_vars.mitarbeiterliste item='row'}}
                                        <option value="{{$row.id}}" {{if isset($smarty_vars.values.zu)}} {{if $smarty_vars.values.zu == $row.id}} selected{{/if}}{{/if}}>{{$row.personalnummer}} - {{$row.nachname}}, {{$row.vorname}}</option>
                                    {{/foreach}}
                                {{/if}}
                            </select>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-secondary form-control">Vorgang starten</button>
            </form>
        </div>
    </div>
</div>

{{capture name='css'}}
    <!-- Select2 -->
    <link href="/assets/vendors/select2/dist/css/select2.min.css" rel="stylesheet">
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