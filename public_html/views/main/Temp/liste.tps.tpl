{{$title="Liste" scope=parent}}

<form action="/temp/liste" method="post">
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
                                <th>&nbsp;</th>
                                <th class="text-right">Personalnr.</th>
                                <th>Name</th>
                                <th class="text-right">Stundenlohn</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{foreach from=$smarty_vars.liste item='row'}}
                                <tr>
                                    <td><input type="checkbox" name="export[]" value="{{$row.export|default:""}}" checked></td>
                                    <td class="text-right">{{$row.personalnummer}}</td>
                                    <td>{{$row.vorname}} {{$row.nachname}}</td>
                                    <td class="text-right">{{$row.stundenlohn|default:0|number_format:2:",":"."}}</td>
                                </tr>
                            {{/foreach}}
                        </tbody>
                    </table>
                {{/if}}
            </div>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col">
            <div class="content-box">
                <input type="hidden" name="filename" value="{{$smarty_vars.filename|default:"???"}}">
                <button type="submit" name="action" value="exportieren" class="btn btn-secondary btn-block" onclick='this.form.target="_blank";'>Exportieren</button>
            </div>
        </div>
    </div>
</form>

{{$css=$css scope=parent}}

{{$js=$js scope=parent}}