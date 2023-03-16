{{$container_class="container" scope=parent}}

{{$title="Dokument | bearbeiten" scope=parent}}

<form action="/dokument/bearbeiten/{{$smarty_vars.values.id}}" method="post">
    <div class="row mb-4">
        <div class="col">
            <div class="content-box">
                <h3 class="content-box-title"><strong>{{$smarty_vars.values.name|default:""}}</strong></h3>

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

                <div class="row">
                    <div class="form-group col-12 col-sm-6">
                        <label class="form-control-label" for="kunde">Kunde</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-user fa-fw"></i></span>
                            <select class="form-control selectable" name="kundennummer" id="kundennummer" tabindex="-1" required>
                                <option value="">-- bitte auswählen</option>
                                {{foreach from=$smarty_vars.kundenliste item='row'}}
                                    <option value="{{$row.kundennummer}}" {{if isset($smarty_vars.values.kundennummer)}}{{if $smarty_vars.values.kundennummer == $row.kundennummer}} selected{{/if}}{{/if}}>{{$row.kundennummer}} - {{$row.name}}</option>
                                {{/foreach}}
                            </select>
                        </div>
                    </div>

                    <div class="form-group col-12 col-sm-6">
                        <label class="form-control-label" for="name">Name</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-file fa-fw"></i></span>
                            <input type="text" class="form-control" id="name" name="name" value="{{$smarty_vars.values.name|default:""}}" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col">
            <div class="content-box">
                <div class="row">
                    <div class="col-12 col-sm">
                        <a class="btn btn-secondary btn-block" href="/dokument/anzeigen/{{$smarty_vars.values.id}}">
                            Dokument anzeigen
                        </a>
                    </div>
                    <div class="col-12 col-sm">
                        <a class="btn btn-secondary btn-block" href="/dokument/loeschen/{{$smarty_vars.values.id}}">
                            Dokument löschen
                        </a>
                    </div>
                    <div class="col-12 col-sm">
                        <button type="submit" class="btn btn-secondary btn-block" name="submitted" value="true">Änderungen speichern</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

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
            text-decoration: none;
            font-family: sans-serif;
            font-size: 100%;
            line-height: 1.15;
            margin: 0;
            cursor: default;
        }
    </style>
    <!-- /General -->

    {{$css|default:""}}
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

    {{$js|default:""}}
{{/capture}}

{{$js=$smarty.capture.scripts scope=parent}}