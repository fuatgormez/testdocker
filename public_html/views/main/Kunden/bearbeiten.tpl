{{$container_class="container" scope=parent}}

{{$title="Kunden | bearbeiten" scope=parent}}

<div class="row mb-4">
    <div class="col">
        <div class="content-box">
            <h3 class="content-box-title">Kunde Nr. {{$smarty_vars.values.kundennummer|default:"???"}}: <strong>{{$smarty_vars.values.name|default:""}}</strong></h3>

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

            <form action="/kunden/bearbeiten/{{$smarty_vars.values.kundennummer}}" method="post">
                {{include file = 'views/main/Kunden/components/form.tpl'}}
            </form>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col">
        <div class="content-box">
            {{if $smarty_vars.current_user.usergroup->hasRight('konditionen')}}
                <h4>Konditionen</h4>
                <a class="btn btn-secondary btn-block my-3" href="/kundenkondition/erstellen/{{$smarty_vars.values.kundennummer|default:"???"}}"><i class="fa fa-plus-circle mr-1"></i> Neue Kundenkondition hinzufügen</a>
                {{include file='views/main/components/tables.tpl'}}
                {{if isset($smarty_vars.kundenkonditionsliste)}}
                    {{$table_tag|default:"<table>"}}
                    <thead>
                        <tr>
                            <td>Gültig ab</td>
                            <td>Gültig bis</td>
                            <td>Abteilung</td>
                            <td class="text-right">Preis</td>
                            <td class="text-right">Sonntagsz.</td>
                            <td class="text-right">Feiertagsz.</td>
                            <td class="text-right">Nachtz.</td>
                            <td class="text-right">Nacht von</td>
                            <td class="text-right">Nacht bis</td>
                            {{if $smarty_vars.company == 'tps'}}
                                <td class="text-right">Zeit/Palette</td>
                            {{/if}}
                        </tr>
                    </thead>
                    <tbody>
                        {{foreach from=$smarty_vars.kundenkonditionsliste item='row'}}
                            <tr>
                                <td data-order="{{$row.gueltig_ab_ordering}}">{{$row.gueltig_ab}}</td>
                                <td data-order="{{$row.gueltig_bis_ordering}}" class="clickable" data-href="/kundenkondition/bearbeiten/{{$row.id}}">{{$row.gueltig_bis}}</td>
                                <td class="clickable" data-href="/kundenkondition/bearbeiten/{{$row.id}}">{{$row.abteilung}}</td>
                                <td class="clickable text-right" data-href="/kundenkondition/bearbeiten/{{$row.id}}">{{$row.preis|default:""|number_format:2:",":"."}}</td>
                                <td class="clickable text-right" data-href="/kundenkondition/bearbeiten/{{$row.id}}">{{$row.sonntagszuschlag}}</td>
                                <td class="clickable text-right" data-href="/kundenkondition/bearbeiten/{{$row.id}}">{{$row.feiertagszuschlag}}</td>
                                <td class="clickable text-right" data-href="/kundenkondition/bearbeiten/{{$row.id}}">{{$row.nachtzuschlag}}</td>
                                <td class="clickable text-right" data-href="/kundenkondition/bearbeiten/{{$row.id}}">{{$row.nacht_von}}</td>
                                <td class="clickable text-right" data-href="/kundenkondition/bearbeiten/{{$row.id}}">{{$row.nacht_bis}}</td>
                                {{if $smarty_vars.company == 'tps'}}
                                    <td class="clickable text-right" data-href="/kundenkondition/bearbeiten/{{$row.id}}">{{$row.zeit_pro_palette}}</td>
                                {{/if}}
                            </tr>
                        {{/foreach}}
                    </tbody>
                    </table>
                {{/if}}
            {{/if}}
        </div>
    </div>
</div>

{{capture name='styles'}}
    <!-- General -->
    <style>
        a.btn:hover, a.btn:link, a.btn:visited, a.btn:active {
            color: black;
        }
    </style>
    <!-- /General-->

    {{$css|default:""}}
{{/capture}}

{{$css=$smarty.capture.styles scope=parent}}

{{capture name='scripts'}}
    <!-- Autosize -->
    <script src="/assets/vendors/autosize/dist/autosize.min.js"></script>
    <script>
        $(document).ready(function() {
            autosize($(".resizable"));
        });
    </script>
    <!-- /Autosize -->

    {{$js|default:""}}
{{/capture}}

{{$js=$smarty.capture.scripts scope=parent}}