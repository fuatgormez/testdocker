{{$title="Liste" scope=parent}}

<div class="row mb-4">
    <div class="col">
        <div class="content-box">
            {{if isset($smarty_vars.error)}}
                <div class="alert alert-danger">
                    {{$smarty_vars.error}}
                </div>
            {{/if}}
            
            {{include file='views/main/components/tables.tpl'}}

            {{if isset($smarty_vars.liste)}}
                {{$table_tag|default:"<table>"}}
                    <thead>
                        <tr>
                            <th>Personalnummer</th>
                            <th>Vorname</th>
                            <th>Nachname</th>
                            <th>letzter Einsatz</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{foreach from=$smarty_vars.liste item='row'}}
                            <tr>
                                <td>{{$row.personalnummer}}</td>
                                <td>{{$row.vorname}}</td>
                                <td>{{$row.nachname}}</td>
                                <td>{{$row.letztes_mal_gearbeitet}}</td>
                            </tr>
                        {{/foreach}}
                    </tbody>
                </table>
            {{/if}}
        </div>
    </div>
</div>

{{$css=$css scope=parent}}

{{$js=$js scope=parent}}