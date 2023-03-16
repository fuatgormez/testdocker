{{$container_class="container" scope=parent}}

{{$title="Benutzer | erstellen" scope=parent}}

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

            <form action="/benutzer/erstellen" method="post">
                <div class="row">
                    <div class="form-group col-12 col-sm-6">
                        <label class="form-control-label" for="benutzername">Benutzername *</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-user fa-fw"></i></span>
                            <input type="text" class="form-control" id="benutzername" name="benutzername" placeholder="max.mustermann" value="{{$smarty_vars.values.benutzername|default:""}}" required>
                        </div>
                    </div>
                    <div class="form-group col-12 col-sm-6">
                        <label class="form-control-label" for="name">Name *</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-user fa-fw"></i></span>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Max Mustermann" value="{{$smarty_vars.values.name|default:""}}" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-12 col-sm-6">
                        <label class="form-control-label" for="passwort_neu">Neues Passwort *</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-key fa-fw"></i></span>
                            <input type="password" class="form-control" id="passwort_neu" name="passwort_neu" placeholder="Passwort" autocomplete="off" required>
                        </div>
                    </div>
                    <div class="form-group col-12 col-sm-6">
                        <label class="form-control-label" for="passwort_neu_bestaetigen">bestätigen *</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-key fa-fw"></i></span>
                            <input type="password" class="form-control" id="passwort_neu_bestaetigen" name="passwort_neu_bestaetigen" placeholder="bestätigen" autocomplete="off" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-12 col-sm-6">
                        <label class="form-control-label" for="kunde">Kundenbeschränkung</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-lock fa-fw"></i></span>
                            <select class="form-control selectable" id="kunde" tabindex="-1" name="kunde">
                                <option value="-1">keine Beschränkung</option>
                                {{if isset($smarty_vars.kundenliste)}}
                                    {{foreach from=$smarty_vars.kundenliste item='row'}}
                                        <option value="{{$row.id}}"{{if isset($smarty_vars.values.kunde)}}{{if $smarty_vars.values.kunde == $row.id}} selected{{/if}}{{/if}}>{{$row.kundennummer}} - {{$row.name}}</option>
                                    {{/foreach}}
                                {{/if}}
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-12 col-sm-6">
                        <label class="form-control-label" for="benutzergruppe">Benutzergruppe</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-lock fa-fw"></i></span>
                            <select class="form-control selectable" id="benutzergruppe" tabindex="-1" name="benutzergruppe">
                                {{if isset($smarty_vars.benutzergruppen)}}
                                    {{foreach from=$smarty_vars.benutzergruppen item='row'}}
                                        <option value="{{$row.id}}"{{if isset($smarty_vars.values.benutzergruppe)}}{{if $smarty_vars.values.benutzergruppe == $row.id}} selected{{/if}}{{/if}}>{{$row.bezeichnung}}</option>
                                    {{/foreach}}
                                {{/if}}
                            </select>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-secondary form-control">Speichern</button>
            </form>
        </div>
    </div>
</div>

{{capture name='styles'}}
    <!-- Select2 -->
    <link href="/assets/vendors/select2/dist/css/select2.min.css" rel="stylesheet">
    <style>
        .select2-container .select2-selection--multiple {
            min-height: 100px;
        }

        @media (max-width: 575px) {
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
    <!-- /Select2 -->

    <!-- General -->
    <style>
        a.btn:hover, a.btn:link, a.btn:visited, a.btn:active {
            color: black;
        }
    </style>
    <!-- /General-->
{{/capture}}

{{$css=$smarty.capture.styles scope=parent}}

{{capture name='scripts'}}
    <!-- Select2 -->
    <script src="/assets/vendors/select2/dist/js/select2.full.min.js"></script>
    <script>
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
    </script>
    <!-- /Select2 -->
{{/capture}}

{{$js=$smarty.capture.scripts scope=parent}}