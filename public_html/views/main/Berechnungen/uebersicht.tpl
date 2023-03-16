{{$title="Übersicht" scope=parent}}

<form action="/berechnungen/uebersicht" method="post">
    <div class="container">
        {{if isset($smarty_vars.error)}}
            <div class="row mb-4">
                <div class="col">
                    <div class="content-box bg-danger text-white">
                        {{$smarty_vars.error}}
                    </div>
                </div>
            </div>
        {{/if}}
        <div class="row mb-4">
            <div class="col">
                <div class="content-box">
                    <div class="row">
                        <div class="form-group col-12 col-sm-6">
                            <label class="form-control-label" for="monat">Monat</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-calendar fa-fw"></i></span>
                                <select name="monat" id="monat" class="form-control" required>
                                    <option value="1"{{if isset($smarty_vars.values.monat)}}{{if $smarty_vars.values.monat == 1}} selected{{/if}}{{/if}}>Januar</option>
                                    <option value="2"{{if isset($smarty_vars.values.monat)}}{{if $smarty_vars.values.monat == 2}} selected{{/if}}{{/if}}>Februar</option>
                                    <option value="3"{{if isset($smarty_vars.values.monat)}}{{if $smarty_vars.values.monat == 3}} selected{{/if}}{{/if}}>März</option>
                                    <option value="4"{{if isset($smarty_vars.values.monat)}}{{if $smarty_vars.values.monat == 4}} selected{{/if}}{{/if}}>April</option>
                                    <option value="5"{{if isset($smarty_vars.values.monat)}}{{if $smarty_vars.values.monat == 5}} selected{{/if}}{{/if}}>Mai</option>
                                    <option value="6"{{if isset($smarty_vars.values.monat)}}{{if $smarty_vars.values.monat == 6}} selected{{/if}}{{/if}}>Juni</option>
                                    <option value="7"{{if isset($smarty_vars.values.monat)}}{{if $smarty_vars.values.monat == 7}} selected{{/if}}{{/if}}>Juli</option>
                                    <option value="8"{{if isset($smarty_vars.values.monat)}}{{if $smarty_vars.values.monat == 8}} selected{{/if}}{{/if}}>August</option>
                                    <option value="9"{{if isset($smarty_vars.values.monat)}}{{if $smarty_vars.values.monat == 9}} selected{{/if}}{{/if}}>September</option>
                                    <option value="10"{{if isset($smarty_vars.values.monat)}}{{if $smarty_vars.values.monat == 10}} selected{{/if}}{{/if}}>Oktober</option>
                                    <option value="11"{{if isset($smarty_vars.values.monat)}}{{if $smarty_vars.values.monat == 11}} selected{{/if}}{{/if}}>November</option>
                                    <option value="12"{{if isset($smarty_vars.values.monat)}}{{if $smarty_vars.values.monat == 12}} selected{{/if}}{{/if}}>Dezember</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-12 col-sm-6">
                            <label class="form-control-label" for="jahr">Jahr</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-calendar fa-fw"></i></span>
                                <input type="text" class="form-control" id="jahr" name="jahr" placeholder="JJJJ" value="{{$smarty_vars.values.jahr|default:""}}" required>
                            </div>
                        </div>
                    </div>
                    <button type="submit" name="action" value="anzeigen" class="btn btn-secondary form-control">Anzeigen</button>
                </div>
            </div>
        </div>
    </div>

    {{if isset($smarty_vars.liste)}}
        <div class="row mb-4">
            <div class="col">
                <div class="content-box">
                    {{include file='views/main/components/tables.tpl'}}

                    {{if isset($smarty_vars.liste)}}
                        {{$table_tag|default:"<table>"}}
                            <thead>
                                <tr>
                                    <th>&nbsp;</th>
                                    <th class="text-right">P.Nr.</th>
                                    <th>Name</th>
                                    <th class="text-right">Eintritt</th>
                                    <th class="text-right">Austritt</th>
                                    <th class="text-right">Wochenstd.</th>
                                    <th class="text-right">Krankheitsstd.</th>
                                    <th class="text-right">Urlaubsstd.</th>
                                    <th class="text-right">Feiertagsstd.</th>
                                    <th class="text-right">Importstd.</th>
                                    <th class="text-right">Schichtstd.</th>
                                    <th class="text-right">Gesamtstd.</th>
                                    <th class="text-right">Sollstd.</th>
                                    <th class="text-right">AZK Vormonat</th>
                                    <th class="text-right">AZK Aktuell</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{foreach from=$smarty_vars.liste item='row'}}
                                    <tr>
                                        <td><input type="checkbox" name="export[]" value="{{$row.export|default:""}}" checked></td>
                                        <td class="text-right">{{$row.personalnummer}}</td>
                                        <td>{{$row.vorname}} {{$row.nachname}}</td>
                                        <td class="text-right">{{$row.eintritt}}</td>
                                        <td class="text-right">{{$row.austritt}}</td>
                                        <td class="text-right">{{$row.wochenstunden|default:0|number_format:2:",":"."}}</td>
                                        <td class="text-right">{{$row.stunden_krankheit|default:0|number_format:2:",":"."}}</td>
                                        <td class="text-right">{{$row.stunden_urlaub|default:0|number_format:2:",":"."}}</td>
                                        <td class="text-right">{{$row.stunden_feiertag|default:0|number_format:2:",":"."}}</td>
                                        <td class="text-right">{{$row.stunden_import|default:0|number_format:2:",":"."}}</td>
                                        <td class="text-right">{{$row.stunden_schichten|default:0|number_format:2:",":"."}}</td>
                                        <td class="text-right">{{$row.stunden_insgesamt|default:0|number_format:2:",":"."}}</td>
                                        <td class="text-right">{{$row.stunden_soll|default:0|number_format:2:",":"."}}</td>
                                        <td class="text-right">{{$row.azk_vormonat|default:0|number_format:2:",":"."}}</td>
                                        <td class="text-right">{{$row.azk_aktuell|default:0|number_format:2:",":"."}}</td>
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
                    <input type="hidden" name="filename" value="{{$smarty_vars.values.filename|default:"???"}}">
                    <button type="submit" name="action" value="exportieren" class="btn btn-secondary btn-block" onclick='this.form.target="_blank";'>Exportieren</button>
                </div>
            </div>
        </div>

        {{$css=$css scope=parent}}

        {{$js=$js scope=parent}}
    {{/if}}
</form>