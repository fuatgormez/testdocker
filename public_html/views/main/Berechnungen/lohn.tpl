{{$title="Berechnungen | Lohn" scope=parent}}

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

    {{if isset($smarty_vars.success)}}
        <div class="row mb-4">
            <div class="col">
                <div class="content-box bg-success text-white">
                    {{$smarty_vars.success}}
                </div>
            </div>
        </div>
    {{/if}}

    <div class="row mb-4">
        <div class="col">
            <div class="content-box bg-primary text-white">
                Bitte beachten Sie, dass für die Lohnberechnung nur diejenigen Mitarbeiter berücksichtigt werden, die in Agenda
                <ol class="mb-0">
                    <li>ein Eintrittsdatum hinterlegt und</li>
                    <li>entweder kein Austrittsdatum oder</li>
                    <li>ein Austrittsdatum größer oder gleich dem ersten Tag des Abrechnungsmonats hinterlegt haben.</li>
                </ol>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col">
            <div class="content-box">
                <form action="/berechnungen/lohn" method="post">
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
                    <button type="submit" name="action" value="berechnen" class="btn btn-secondary form-control">Berechnen</button>
                </form>
            </div>
        </div>
    </div>
</div>

{{if isset($smarty_vars.liste)}}
    <form action="/berechnungen/lohn" method="post">
        {{foreach from=$smarty_vars.liste item='mitarbeiter' key='personalnummer'}}
            <div class="row mb-4">
                <div class="col-12{{if $smarty_vars.company != 'tps'}} col-lg-9{{/if}}">
                    <div class="content-box">
                        <table class="table table-striped table-bordered table-hover dt-responsive nowrap mb-0" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>&nbsp;</th>
                                    <th>Mitarbeiter</th>
                                    <th class="text-right">Lohnart</th>
                                    <th>Bezeichnung</th>
                                    <th class="text-right">Anzahl</th>
                                    <th class="text-right">Lohnsatz</th>
                                    <th class="text-right">Betrag</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{foreach from=$mitarbeiter item='row'}}
                                    <tr>
                                        <td><input type="checkbox" name="export[]" value="{{$row.export}}" checked></td>
                                        <td>{{$row.personalnummer}} - {{$row.nachname}}, {{$row.vorname}}</td>
                                        <td class="text-right">{{$row.lohnart}}</td>
                                        <td>{{$row.bezeichnung}}</td>
                                        <td class="text-right">{{$row.anzahl}}</td>
                                        <td class="text-right">{{$row.lohnsatz}}</td>
                                        <td class="text-right">{{$row.betrag}}</td>
                                    </tr>
                                {{/foreach}}
                            </tbody>
                        </table>
                    </div>
                </div>
                {{if $smarty_vars.company != 'tps'}}
                    <div class="col-12 col-lg-3">
                        <div class="content-box">
                            {{if isset($smarty_vars.fehlzeitenliste.$personalnummer)}}
                                <h4>{{$smarty_vars.fehlzeitenliste.$personalnummer.mini_oder_sv}}</h4>

                                <div>
                                    <a href="/mitarbeiter/kalender/{{$personalnummer|default:"???"}}" target="_blank">-- Mitarbeiterkalender öffnen --</a>
                                </div>

                                <div class="row">
                                    <div class="col-9">Alter AZK-Stand</div>
                                    <div class="col-3 text-right">{{$smarty_vars.fehlzeitenliste.$personalnummer.alter_azk}}</div>
                                </div>
                                <div class="row">
                                    <div class="col-9">Veränderung</div>
                                    <div class="col-3 text-right">{{$smarty_vars.fehlzeitenliste.$personalnummer.veraenderung}}</div>
                                </div>
                                <div class="row">
                                    <div class="col-9">Neuer AZK-Stand</div>
                                    <div class="col-3 text-right">{{$smarty_vars.fehlzeitenliste.$personalnummer.neuer_azk}}</div>
                                </div>
                                {{if isset($smarty_vars.fehlzeitenliste.$personalnummer.benoetigte_fehlzeiten_stunden)}}
                                    <div class="row">
                                        <div class="col-9">Vorhandene Fehlzeitenstd.</div>
                                        <div class="col-3 text-right">{{$smarty_vars.fehlzeitenliste.$personalnummer.vorhandene_fehlzeiten_stunden}}</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-9">Benötigte Fehlzeitenstd.</div>
                                        <div class="col-3 text-right">{{$smarty_vars.fehlzeitenliste.$personalnummer.benoetigte_fehlzeiten_stunden}}</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-9">Tagessoll</div>
                                        <div class="col-3 text-right">{{$smarty_vars.fehlzeitenliste.$personalnummer.tagessoll}}</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-9">Benötigte Fehlzeitentage</div>
                                        <div class="col-3 text-right">{{$smarty_vars.fehlzeitenliste.$personalnummer.benoetigte_fehlzeiten_tage}}</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-9">Freie Kalendertage</div>
                                        <div class="col-3 text-right">{{$smarty_vars.fehlzeitenliste.$personalnummer.freie_kalendertage}}</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-9">Fehlzeitentage buchen</div>
                                        <div class="col-3 text-right">{{$smarty_vars.fehlzeitenliste.$personalnummer.buchen_fehlzeiten_tage}}</div>
                                    </div>
                                    <div class="row mt-4">
                                        <div class="form-group col-12">
                                            <label class="form-control-label" for="anzahl_fehltage"><strong>Anzahl zu buchender Fehltage</strong></label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-calendar-o fa-fw"></i></span>
                                                <input type="number" class="form-control" id="anzahl_fehltage" name="anzahl_fehltage[{{$personalnummer}}]" placeholder="z.B. 3" value="{{$smarty_vars.fehlzeitenliste.$personalnummer.buchen_fehlzeiten_tage_value|default:""}}" required>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="btn-group btn-block" data-toggle="buttons">
                                                <label class="btn btn-checkbox btn-block mb-0">
                                                    <input type="checkbox" name="fehltage_speichern[]" value="{{$personalnummer}}"> Fehltage speichern
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                {{/if}}
                            {{/if}}
                        </div>
                    </div>
                {{/if}}
            </div>
        {{/foreach}}
        <div class="row mb-4">
            <div class="col">
                <div class="content-box">
                    <h4>Lohnartstatistiken</h4>
                    <table class="table table-striped table-bordered table-hover dt-responsive nowrap mb-0" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Lohnart</th>
                                <th class="text-right">Anzahl</th>
                                <th class="text-right">Betrag</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{foreach from=$smarty_vars.lohnartstatistiken item='row' key='lohnart'}}
                                <tr>
                                    <td>{{$lohnart}}</td>
                                    <td class="text-right">{{$row.anzahl|default:0|number_format:2:",":"."}}</td>
                                    <td class="text-right">{{$row.betrag|default:0|number_format:2:",":"."}}</td>
                                </tr>
                            {{/foreach}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col">
                <div class="content-box">
                    <input type="hidden" name="filename" value="{{$smarty_vars.values.filename|default:"???"}}">
                    <button type="submit" name="action" value="exportieren" class="btn btn-secondary btn-block" onclick='this.form.target="_blank";'>Für Agenda exportieren</button>
                </div>
            </div>
            {{if $smarty_vars.company != 'tps'}}
                <div class="col">
                    <div class="content-box">
                        <input type="hidden" name="jahr" value="{{$smarty_vars.values.jahr|default:"???"}}">
                        <input type="hidden" name="monat" value="{{$smarty_vars.values.monat|default:"???"}}">
                        <button type="submit" name="action" value="fehltage_speichern" class="btn btn-secondary btn-block" onclick='this.form.target="_self";'>Fehltage speichern</button>
                    </div>
                </div>
            {{/if}}
        </div>
    </form>
{{/if}}

{{capture name='css'}}
    <style>
        .btn-checkbox {
	        color: #fff;
	        background-color: #d9534f;
	        border-color: #d9534f;
        }

        .btn-checkbox:hover, .btn-checkbox:active, .btn-checkbox:focus {
	        background-color: #c9302c;
	        border-color: #c12e2a;
        }

        .btn-checkbox.active {
	        background-color: #5cb85c;
	        border-color: #5cb85c;
        }

        .btn-checkbox.active:hover {
	        background-color: #449d44;
	        border-color: #419641;
        }
    </style>
{{/capture}}

{{$css=$smarty.capture.css scope=parent}}

{{capture name='scripts'}}
{{/capture}}

{{$js=$smarty.capture.scripts scope=parent}}