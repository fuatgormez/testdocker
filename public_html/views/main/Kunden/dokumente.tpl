{{$container_class="container" scope=parent}}

{{$title="Kunden | Dokumente" scope=parent}}

{{if isset($smarty_vars.error)}}
    {{foreach from=$smarty_vars.error item='row'}}
        <div class="row mb-4">
            <div class="col">
                <div class="content-box bg-danger text-white">
                    {{$row}}
                </div>
            </div>
        </div>
    {{/foreach}}
{{/if}}

{{if isset($smarty_vars.success)}}
    {{foreach from=$smarty_vars.success item='row'}}
        <div class="row mb-4">
            <div class="col">
                <div class="content-box bg-success text-white">
                    {{$row}}
                </div>
            </div>
        </div>
    {{/foreach}}
{{/if}}

<div class="row mb-4">
    {{if isset($smarty_vars.kundenliste)}}
        <div class="col-12">
            <div class="content-box">
                <h4 class="mb-4">Kunden auswählen</h4>
                <div class="input-group">
                    {{if isset($smarty_vars.prev_kunde)}}
                        <div class="input-group-btn">
                            <a id="kunde_prev" type="button" class="btn btn-secondary" href="/kunden/dokumente/{{$smarty_vars.prev_kunde}}">
                                <i class="fa fa-angle-left"></i>
                            </a>
                        </div>
                    {{else}}
                        <span class="input-group-addon">
                            <i class="fa fa-user fa-fw"></i>
                        </span>
                    {{/if}}

                    <select class="form-control selectable" id="kundenswitch" tabindex="-1" required>
                        <option value="">-- bitte auswählen</option>
                        {{foreach from=$smarty_vars.kundenliste item='row'}}
                            <option value="{{$row.kundennummer}}" {{if isset($smarty_vars.kundennummer)}}{{if $smarty_vars.kundennummer == $row.kundennummer}} selected{{/if}}{{/if}}>{{$row.kundennummer}} - {{$row.name}}</option>
                        {{/foreach}}
                    </select>

                    {{if isset($smarty_vars.next_kunde)}}
                        <div class="input-group-btn">
                            <a id="kunde_next" type="button" class="btn btn-secondary" href="/kunden/dokumente/{{$smarty_vars.next_kunde}}">
                                <i class="fa fa-angle-right"></i>
                            </a>
                        </div>
                    {{/if}}
                </div>
            </div>
        </div>
    {{/if}}
</div>

{{if isset($smarty_vars.dokumentenliste)}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="content-box">
                <h4 class="mb-4">Dokumente anzeigen</h4>
                {{include file='views/main/components/tables.tpl'}}

                {{$table_tag|default:"<table>"}}
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th class="text-right">Größe</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{foreach from=$smarty_vars.dokumentenliste item='row'}}
                            <tr>
                                <td class="clickable" data-href="/dokument/{{if $smarty_vars.current_user.usergroup->hasRight('dokumente_alle_kunden')}}bearbeiten{{else}}anzeigen{{/if}}/{{$row.id}}">{{$row.name}}</td>
                                <td class="clickable text-right" data-href="/dokument/{{if $smarty_vars.current_user.usergroup->hasRight('dokumente_alle_kunden')}}bearbeiten{{else}}anzeigen{{/if}}/{{$row.id}}">{{$row.size}}</td>
                            </tr>
                        {{/foreach}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
{{/if}}

{{if $smarty_vars.current_user.usergroup->hasRight('dokumente_alle_kunden')}}
    {{if isset($smarty_vars.kundennummer)}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="content-box">
                    <h4 class="mb-4">Dokumente hochladen</h4>
                    <form action="/kunden/dokumente/{{$smarty_vars.kundennummer}}" method="post" enctype="multipart/form-data">
                        <input class="form-control mb-3" type="file" name="datei[]" multiple>
                        <button type="submit" class="btn btn-secondary btn-block">Hochladen</button>
                    </form>
                </div>
            </div>
        </div>
    {{/if}}
{{/if}}

{{capture name='styles'}}
    {{$css|default:''}}

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
{{/capture}}

{{$css=$smarty.capture.styles scope=parent}}

{{capture name='scripts'}}
    {{$js|default:''}}

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

    <!-- Kundenswitch -->
    <script>
        $kundenswitch = $('#kundenswitch');
        $kundenswitch.change(function () {
            if ($kundenswitch.val() != '') {
                window.location = '/kunden/dokumente/' + $kundenswitch.val();
            }
        });
    </script>
    <!-- /Kundenswitch -->
{{/capture}}

{{$js=$smarty.capture.scripts scope=parent}}