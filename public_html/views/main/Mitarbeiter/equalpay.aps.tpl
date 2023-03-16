{{$container_class="container" scope=parent}}

{{$title="Mitarbeiter | Equal Pay" scope=parent}}

{{if isset($smarty_vars.liste)}}
    <div class="row mb-4">
        <div class="col">
            <div class="content-box">
                <table class="table table-striped table-bordered table-hover dt-responsive nowrap mb-0" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            {{**<th>&nbsp;</th>**}}
                            <th>Mitarbeiter</th>
                            <th>Kunde</th>
                            <th class="text-right">Prozentsatz</th>
                            <th>Equal Pay</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{foreach from=$smarty_vars.liste item='row'}}
                            <tr{{if $row.class != ''}} class="table-{{$row.class}}"{{/if}}>
                                {{**<td><input type="checkbox" name="export[]" value="{{$row.export|default:"???"}}" checked></td>**}}
                                <td>{{$row.personalnummer|default:"???"}} - {{$row.nachname|default:"???"}}, {{$row.vorname|default:"???"}}</td>
                                <td>{{$row.kunde|default:"???"}}</td>
                                <td class="text-right">{{$row.prozentsatz|default:"???"}} %</td>
                                <td>{{$row.equalpay|default:""}}</td></td>
                            </tr>
                        {{/foreach}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{**<div class="row mb-4">
        <div class="col">
            <div class="content-box">
                <input type="hidden" name="filename" value="{{$smarty_vars.values.filename|default:"???"}}">
                <button type="submit" name="action" value="exportieren" class="btn btn-secondary btn-block" onclick='this.form.target="_blank";'>Für Excel exportieren</button>
            </div>
        </div>
    </div>**}}
{{/if}}

{{capture name='css'}}
{{/capture}}

{{$css=$smarty.capture.css scope=parent}}

{{capture name='scripts'}}
{{/capture}}

{{$js=$smarty.capture.scripts scope=parent}}
