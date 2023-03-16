{{$title="Abteilungen" scope=parent}}

{{if isset($smarty_vars.warning)}}
    <div class="row mb-4">
        <div class="col">
            <div class="content-box bg-danger text-white">
                {{$smarty_vars.warning}}
            </div>
        </div>
    </div>
{{/if}}

<div class="row">
    <div class="col-12 col-lg-8 mb-4">
        <div class="content-box">
            <h3 class="content-box-title">Alle anzeigen</h3>

            {{include file='views/main/components/tables.tpl'}}

            {{if isset($smarty_vars.abteilungsliste)}}
                {{$table_tag|default:"<table>"}}
                    <thead>
                        <tr>
                            <th>Abteilungs-ID</th>
                            <th>Bezeichnung</th>
                            <th>In Rechnung stellen</th>
                            {{if $smarty_vars.company == 'tps'}}
                                <th>Palettenabteilung</th>
                            {{/if}}
                        </tr>
                    </thead>
                    <tbody>
                        {{foreach from=$smarty_vars.abteilungsliste item='row'}}
                            <tr>
                                <th role="row">{{$row.id}}</th>
                                <td class="clickable" data-href="/abteilungen/bearbeiten/{{$row.id}}">{{$row.bezeichnung}}</td>
                                <td class="clickable" data-href="/abteilungen/bearbeiten/{{$row.id}}">{{$row.in_rechnung_stellen}}</td>
                                {{if $smarty_vars.company == 'tps'}}
                                    <td class="clickable" data-href="/abteilungen/bearbeiten/{{$row.id}}">{{$row.palettenabteilung}}</td>
                                {{/if}}
                            </tr>
                        {{/foreach}}
                    </tbody>
                </table>
            {{/if}}
        </div>
    </div>
    <div class="col-12 col-lg-4 mb-4">
        <div class="content-box">
            <h3 class="content-box-title">Neue Abteilung</h3>
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

            <form action="/abteilungen" method="post">
                {{include file='views/main/Abteilungen/components/form.tpl'}}
            </form>
        </div>
    </div>
</div>

{{$css=$css scope=parent}}

{{$js=$js scope=parent}}