{{$title="Mitarbeiter | Kalenderübersicht" scope=parent}}

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

            <h3><a class="change-month-button" href="\mitarbeiter\kalenderuebersicht\{{$smarty_vars.values.prev_year|default:"???"}}\{{$smarty_vars.values.prev_month|default:"???"}}"><i class="fa fa-chevron-circle-left mr-1"></i></a> {{$smarty_vars.values.monatsname|default:"???"}} {{$smarty_vars.values.year|default:"???"}} <a class="change-month-button" href="\mitarbeiter\kalenderuebersicht\{{$smarty_vars.values.next_year|default:"???"}}\{{$smarty_vars.values.next_month|default:"???"}}"><i class="fa fa-chevron-circle-right ml-1"></i></a></h3>

            <div class="mt-4"><strong>Kb</strong>: Krank (bezahlt), <strong>Ub</strong>: Urlaub (bezahlt), <strong>Kk</strong>: Kind krank (unbezahlt), <strong>F</strong>: Frei (unbezahlt), <strong>Ku</strong>: Krank (unbezahlt), <strong>uF</strong>: Unentschuldigt Fehlen (unbezahlt), <strong>FT</strong>: Feiertag (bezahlt), <strong>FZ</strong>: Fehlzeit (unbezahlt), <strong>Ug</strong>: Urlaub genehmigt (unbezahlt)</div>

            {{if isset($smarty_vars.kalenderuebersicht)}}
                <table class="table table-bordered mt-4">
                    <thead>
                        <tr>
                            <td>Mitarbeiter</td>
                            {{foreach from=$smarty_vars.kalendertageliste item='col'}}
                                <th class="text-center">{{$col|default:"&nbsp;"}}</th>
                            {{/foreach}}
                        </tr>
                    </thead>
                    <tbody>
                        {{foreach from=$smarty_vars.kalenderuebersicht item='row' key='personalnummer'}}
                            <tr>
                                <th>{{$personalnummer}} - {{$smarty_vars.mitarbeiternamen.$personalnummer}}</th>
                                {{foreach from=$row item='col'}}
                                    <td class="text-center"{{if $col != ''}} style="background-color:#333; color:white;"{{/if}}>{{$col|default:"&nbsp;"}}</td>
                                {{/foreach}}
                            </tr>
                        {{/foreach}}
                    </tbody>
                </table>
                
            {{/if}}
        </div>
    </div>
</div>

{{$css = '
    <style>
        a.change-month-button {
            color: black;
        }
        a.change-month-button:hover {
            color: #333;
        }
    </style>
' scope = parent}}