{{$container_class="container" scope=parent}}

{{$title="Mitarbeiter | bearbeiten" scope=parent}}

<div class="row mb-4">
    <div class="col-12 col-md-7 col-xl-8 mb-4 mb-md-0">
        <div class="content-box">
            <h3 class="mb-0 py-md-1">Mitarbeiter Nr. {{$smarty_vars.values.personalnummer|default:"???"}}: <strong>{{$smarty_vars.values.vorname|default:""}} {{$smarty_vars.values.nachname|default:""}}</strong></h3>
        </div>
    </div>

    <div class="col-12 col-md-5 col-xl-4">
        <div class="content-box">
            {{if isset($smarty_vars.mitarbeiterliste)}}
                <select class="form-control selectable" id="mitarbeiterswitch" tabindex="-1" required>
                    <option value="">-- bitte auswählen</option>
                    {{foreach from=$smarty_vars.mitarbeiterliste item='row'}}
                        <option value="{{$row.personalnummer}}" {{if isset($smarty_vars.values.personalnummer)}} {{if $smarty_vars.values.personalnummer == $row.personalnummer}} selected{{/if}}{{/if}}>{{$row.personalnummer}} - {{$row.nachname}}, {{$row.vorname}}</option>
                    {{/foreach}}
                </select>
            {{/if}}
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col">
        <div class="content-box pt-0">
            <div class="row mb-4 mitarbeiter-bearbeiten-menu">
                <a class="col-12 col-lg text-center{{if isset($smarty_vars.values.tab)}}{{if $smarty_vars.values.tab == 'persoenliches'}} active{{/if}}{{else}} active{{/if}}" href="/mitarbeiter/bearbeiten/{{$smarty_vars.values.personalnummer|default:"???"}}/persoenliches">
                    Persönliches
                </a>
                {{if $smarty_vars.current_user.usergroup->hasRight('notizen')}}
                    <a class="col-12 col-lg text-center{{if isset($smarty_vars.values.tab)}}{{if $smarty_vars.values.tab == 'notizen'}} active{{/if}}{{/if}}" href="/mitarbeiter/bearbeiten/{{$smarty_vars.values.personalnummer|default:"???"}}/notizen">
                        Notizen
                    </a>
                {{/if}}
                {{if $smarty_vars.current_user.usergroup->hasRight('vertragliches')}}
                    <a class="col-12 col-lg text-center{{if isset($smarty_vars.values.tab)}}{{if $smarty_vars.values.tab == 'vertragliches'}} active{{/if}}{{/if}}" href="/mitarbeiter/bearbeiten/{{$smarty_vars.values.personalnummer|default:"???"}}/vertragliches">
                        Vertragliches
                    </a>
                {{/if}}
                <a class="col-12 col-lg text-center{{if isset($smarty_vars.values.tab)}}{{if $smarty_vars.values.tab == 'praeferenzen'}} active{{/if}}{{/if}}" href="/mitarbeiter/bearbeiten/{{$smarty_vars.values.personalnummer|default:"???"}}/praeferenzen">
                    Präferenzen
                </a>
                {{if $smarty_vars.current_user.usergroup->hasRight('lohnbuchungen')}}
                    <a class="col-12 col-lg text-center{{if isset($smarty_vars.values.tab)}}{{if $smarty_vars.values.tab == 'lohnbuchungen'}} active{{/if}}{{/if}}" href="/mitarbeiter/bearbeiten/{{$smarty_vars.values.personalnummer|default:"???"}}/lohnbuchungen">
                        Lohnbuchungen
                    </a>
                {{/if}}
                <a class="col-12 col-lg text-center" href="/mitarbeiter/kalender/{{$smarty_vars.values.personalnummer|default:"???"}}">
                    Kalender
                </a>
            </div>

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

            {{if isset($smarty_vars.values.tab)}}
                {{if $smarty_vars.values.tab == 'persoenliches'}}
                    <form action="/mitarbeiter/bearbeiten/{{$smarty_vars.values.personalnummer}}/persoenliches" method="post">
                        {{include file = 'views/main/Mitarbeiter/components/form_a.tpl'}}
                        {{include file = 'views/main/Mitarbeiter/components/form_b.tpl'}}
                        {{include file = 'views/main/Mitarbeiter/components/form_c.tpl'}}
                    </form>
                {{elseif ($smarty_vars.values.tab == 'notizen')}}
                    <form action="/mitarbeiter/bearbeiten/{{$smarty_vars.values.personalnummer}}/notizen" method="post">
                        <div class="row">
                            <div class="form-group col-12">
                                <label class="form-control-label" for="notizen_allgemein">Allgemein</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                    <textarea class="form-control resizable" name="notizen_allgemein" id="notizen_allgemein">{{$smarty_vars.values.notizen_allgemein|default:""}}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-12 col-md-6">
                                <label class="form-control-label" for="notizen_januar">Januar</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                    <textarea class="form-control resizable" name="notizen_januar" id="notizen_januar">{{$smarty_vars.values.notizen_januar|default:""}}</textarea>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6">
                                <label class="form-control-label" for="notizen_februar">Februar</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                    <textarea class="form-control resizable" name="notizen_februar" id="notizen_februar">{{$smarty_vars.values.notizen_februar|default:""}}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-12 col-md-6">
                                <label class="form-control-label" for="notizen_maerz">März</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                    <textarea class="form-control resizable" name="notizen_maerz" id="notizen_maerz">{{$smarty_vars.values.notizen_maerz|default:""}}</textarea>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6">
                                <label class="form-control-label" for="notizen_april">April</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                    <textarea class="form-control resizable" name="notizen_april" id="notizen_april">{{$smarty_vars.values.notizen_april|default:""}}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-12 col-md-6">
                                <label class="form-control-label" for="notizen_mai">Mai</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                    <textarea class="form-control resizable" name="notizen_mai" id="notizen_mai">{{$smarty_vars.values.notizen_mai|default:""}}</textarea>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6">
                                <label class="form-control-label" for="notizen_juni">Juni</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                    <textarea class="form-control resizable" name="notizen_juni" id="notizen_juni">{{$smarty_vars.values.notizen_juni|default:""}}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-12 col-md-6">
                                <label class="form-control-label" for="notizen_juli">Juli</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                    <textarea class="form-control resizable" name="notizen_juli" id="notizen_juli">{{$smarty_vars.values.notizen_juli|default:""}}</textarea>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6">
                                <label class="form-control-label" for="notizen_august">August</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                    <textarea class="form-control resizable" name="notizen_august" id="notizen_august">{{$smarty_vars.values.notizen_august|default:""}}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-12 col-md-6">
                                <label class="form-control-label" for="notizen_september">September</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                    <textarea class="form-control resizable" name="notizen_september" id="notizen_september">{{$smarty_vars.values.notizen_september|default:""}}</textarea>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6">
                                <label class="form-control-label" for="notizen_oktober">Oktober</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                    <textarea class="form-control resizable" name="notizen_oktober" id="notizen_oktober">{{$smarty_vars.values.notizen_oktober|default:""}}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-12 col-md-6">
                                <label class="form-control-label" for="notizen_november">November</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                    <textarea class="form-control resizable" name="notizen_november" id="notizen_november">{{$smarty_vars.values.notizen_november|default:""}}</textarea>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6">
                                <label class="form-control-label" for="notizen_dezember">Dezember</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                    <textarea class="form-control resizable" name="notizen_dezember" id="notizen_dezember">{{$smarty_vars.values.notizen_dezember|default:""}}</textarea>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="notizen_submitted" value="true">
                        <button type="submit" class="btn btn-secondary form-control">Speichern</button>
                    </form>
                {{elseif ($smarty_vars.values.tab == 'vertragliches')}}

                    {{if $smarty_vars.current_user.usergroup->hasRight('austritt_befristungen')}}
                    <form action="/mitarbeiter/bearbeiten/{{$smarty_vars.values.personalnummer}}/vertragliches" method="post">
                    {{/if}}

                        <h4>Eckdaten</h4>
                        <div class="row">
                            <div class="form-group col-12 col-sm-6">
                                <label class="form-control-label" for="eintritt">Eintritt</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calendar fa-fw"></i></span>
                                    <input type="text" class="form-control" id="eintritt" value="{{$smarty_vars.values.eintritt|default:""}}" readonly>
                                </div>
                            </div>
                            <div class="form-group col-12 col-sm-6">
                                <label class="form-control-label" for="austritt">Austritt</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calendar fa-fw"></i></span>
                                    <input type="text" class="form-control" id="austritt" name="austritt" value="{{$smarty_vars.values.austritt|default:""}}"{{if $smarty_vars.current_user.usergroup->hasRight('austritt_befristungen')}} placeholder="TT.MM.JJJJ"{{else}} readonly{{/if}}>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-12 col-sm-6">
                                <label class="form-control-label" for="befristung">Befristung</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calendar fa-fw"></i></span>
                                    <input type="text" class="form-control" id="befristung" name="befristung" value="{{$smarty_vars.values.befristung|default:""}}"{{if $smarty_vars.current_user.usergroup->hasRight('austritt_befristungen')}} placeholder="TT.MM.JJJJ"{{else}} readonly{{/if}}>
                                </div>
                            </div>
                            <div class="form-group col-12 col-sm-6">
                                <label class="form-control-label" for="befristung1">1. Befristung</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calendar fa-fw"></i></span>
                                    <input type="text" name="befristung1" class="form-control" id="befristung1" value="{{$smarty_vars.values.befristung1|default:""}}"{{if $smarty_vars.current_user.usergroup->hasRight('austritt_befristungen')}} placeholder="TT.MM.JJJJ"{{else}} readonly{{/if}}>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-12 col-sm-6">
                                <label class="form-control-label" for="befristung2">2. Befristung</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calendar fa-fw"></i></span>
                                    <input type="text" name="befristung2" class="form-control" id="befristung2" value="{{$smarty_vars.values.befristung2|default:""}}"{{if $smarty_vars.current_user.usergroup->hasRight('austritt_befristungen')}} placeholder="TT.MM.JJJJ"{{else}} readonly{{/if}}>
                                </div>
                            </div>
                            <div class="form-group col-12 col-sm-6">
                                <label class="form-control-label" for="befristung3">3. Befristung</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calendar fa-fw"></i></span>
                                    <input type="text" name="befristung3" class="form-control" id="befristung3" value="{{$smarty_vars.values.befristung3|default:""}}"{{if $smarty_vars.current_user.usergroup->hasRight('austritt_befristungen')}} placeholder="TT.MM.JJJJ"{{else}} readonly{{/if}}>
                                </div>
                            </div>
                        </div>
                        {{if $smarty_vars.current_user.usergroup->hasRight('austritt_befristungen')}}
                        <input type="hidden" name="vertragliches_submitted" value="true">
                        <button type="submit" class="btn btn-secondary form-control">Speichern</button>
                        {{/if}}

                    {{if $smarty_vars.current_user.usergroup->hasRight('austritt_befristungen')}}
                    </form>
                    {{/if}}

                    <h4 class="mt-4">Urlaub</h4>
                    <div class="row">
                        <div class="form-group col-12 col-sm-6">
                            <label class="form-control-label" for="jahresurlaub">Jahresurlaub</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-info fa-fw"></i></span>
                                <input type="text" class="form-control" id="jahresurlaub" value="{{$smarty_vars.values.jahresurlaub|default:""}}" readonly>
                            </div>
                        </div>
                        <div class="form-group col-12 col-sm-6">
                            <label class="form-control-label" for="resturlaub_vorjahr">Resturlaub Vorjahr</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-info fa-fw"></i></span>
                                <input type="text" class="form-control" id="resturlaub_vorjahr" value="{{$smarty_vars.values.resturlaub_vorjahr|default:""}}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-12 col-sm-6">
                            <label class="form-control-label" for="urlaubstage_genommen">Urlaubstage genommen</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-info fa-fw"></i></span>
                                <input type="text" class="form-control" id="urlaubstage_genommen" value="{{$smarty_vars.values.urlaubstage_genommen|default:""}}" readonly>
                            </div>
                        </div>
                        <div class="form-group col-12 col-sm-6">
                            <label class="form-control-label" for="urlaubstage_uebrig">Urlaubstage übrig</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-info fa-fw"></i></span>
                                <input type="text" class="form-control" id="urlaubstage_uebrig" value="{{$smarty_vars.values.urlaubstage_uebrig|default:""}}" readonly>
                            </div>
                        </div>
                    </div>

                    {{if $smarty_vars.company != 'tps'}}
                        <h4 class="mt-4">Lohnzusammensetzung {{$smarty_vars.aktuelle_lohndaten.monatsbezeichnung|default:"XXX"}} {{$smarty_vars.aktuelle_lohndaten.jahr|default:"XXX"}}</h4>
                        <div class="row">
                            <div class="form-group col-12 col-sm-6">
                                <label class="form-control-label" for="tarifbezeichnung">Tarifbezeichnung</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-info fa-fw"></i></span>
                                    <input type="text" class="form-control" id="tarifbezeichnung" value="{{$smarty_vars.aktuelle_lohndaten.tarifbezeichnung|default:""}}" readonly>
                                </div>
                            </div>
                            <div class="form-group col-12 col-sm-6">
                                <label class="form-control-label" for="tariflohn">Tariflohn</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-euro fa-fw"></i></span>
                                    <input type="text" class="form-control" id="tariflohn" value="{{$smarty_vars.aktuelle_lohndaten.tariflohn|default:0|number_format:2:",":"."}}" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-12 col-sm-6">
                                <label class="form-control-label" for="zuschlag_9_monate">Zuschlag 9 Monate</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-euro fa-fw"></i></span>
                                    <input type="text" class="form-control" id="zuschlag_9_monate" value="{{$smarty_vars.aktuelle_lohndaten.zuschlag_9_monate|default:0|number_format:2:",":"."}}" readonly>
                                </div>
                            </div>
                            <div class="form-group col-12 col-sm-6">
                                <label class="form-control-label" for="zuschlag_12_monate">Zuschlag 12 Monate</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-euro fa-fw"></i></span>
                                    <input type="text" class="form-control" id="zuschlag_12_monate" value="{{$smarty_vars.aktuelle_lohndaten.zuschlag_12_monate|default:0|number_format:2:",":"."}}" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-12 col-sm-6">
                                <label class="form-control-label" for="uebertarifliche_zulage">Übertarifliche Zulage</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-euro fa-fw"></i></span>
                                    <input type="text" class="form-control" id="uebertarifliche_zulage" value="{{$smarty_vars.aktuelle_lohndaten.uebertarifliche_zulage|default:0|number_format:2:",":"."}}" readonly>
                                </div>
                            </div>
                            <div class="form-group col-12 col-sm-6">
                                <label class="form-control-label" for="gesamtlohn">Gesamtlohn</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-euro fa-fw"></i></span>
                                    <input type="text" class="form-control" id="gesamtlohn" value="{{$smarty_vars.aktuelle_lohndaten.gesamtlohn|default:0|number_format:2:",":"."}}" readonly>
                                </div>
                            </div>
                        </div>
                    {{/if}}

                    <h4 class="mt-3">Lohnkonfiguration</h4>
                    <a class="btn btn-secondary btn-block my-3" href="/lohnkonfiguration/erstellen/{{$smarty_vars.values.personalnummer|default:"???"}}"><i class="fa fa-plus-circle mr-1"></i> Neue Lohnkonfiguration hinzufügen</a>
                    {{include file='views/main/components/tables.tpl'}}
                    {{if isset($smarty_vars.lohnkonfigurationsliste)}}
                        {{$table_tag|default:"<table>"}}
                            <thead>
                                <tr>
                                    <td>Gültig ab</td>
                                    {{if $smarty_vars.company != 'tps'}}
                                        <td>Tarif</td>
                                    {{/if}}
                                    <td>Wochenstunden</td>
                                    <td>Lohn/Std (€)</td>
                                </tr>
                            </thead>
                            <tbody>
                                {{foreach from=$smarty_vars.lohnkonfigurationsliste item='row'}}
                                    <tr>
                                        <td class="clickable text-right" data-href="/lohnkonfiguration/bearbeiten/{{$row.id}}">{{$row.gueltig_ab}}</td>
                                        {{if $smarty_vars.company != 'tps'}}
                                            <td class="clickable text-right" data-href="/lohnkonfiguration/bearbeiten/{{$row.id}}">{{$row.tarif}}</td>
                                        {{/if}}
                                        <td class="clickable text-right" data-href="/lohnkonfiguration/bearbeiten/{{$row.id}}">{{$row.wochenstunden}}</td>
                                        <td class="clickable text-right" data-href="/lohnkonfiguration/bearbeiten/{{$row.id}}">{{if $row.lohn != ''}}{{$row.lohn|default:0|number_format:2:",":"."}}{{/if}}</td>
                                    </tr>
                                {{/foreach}}
                            </tbody>
                        </table>
                    {{/if}}

                    {{if $smarty_vars.current_user.usergroup->hasRight('tagessoll')}}
                        <h4 class="mt-4">Tagessoll</h4>
                        {{if isset($smarty_vars.tagessollliste)}}
                            <table class="table table-striped table-bordered table-hover dt-responsive nowrap mt-3" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <td class="text-right font-weight-bold">Jahr</td>
                                        <td class="text-right font-weight-bold">Monat</td>
                                        <td class="text-right font-weight-bold">Tagessoll</td>
                                        {{if $smarty_vars.company != 'tps'}}
                                            <td class="text-right font-weight-bold">Montag</td>
                                            <td class="text-right font-weight-bold">Dienstag</td>
                                            <td class="text-right font-weight-bold">Mittwoch</td>
                                            <td class="text-right font-weight-bold">Donnerstag</td>
                                            <td class="text-right font-weight-bold">Freitag</td>
                                            <td class="text-right font-weight-bold">Samstag</td>
                                            <td class="text-right font-weight-bold">Sonntag</td>
                                        {{/if}}
                                    </tr>
                                </thead>
                                <tbody>
                                    {{foreach from=$smarty_vars.tagessollliste item='row'}}
                                        <tr class="black-links">
                                            <td class="text-right"><a href="/tagessoll/bearbeiten/{{$row.id}}" target="_blank">{{$row.jahr}}</a></td>
                                            <td class="text-right"><a href="/tagessoll/bearbeiten/{{$row.id}}" target="_blank">{{$row.monat}}</a></td>
                                            <td class="text-right"><a href="/tagessoll/bearbeiten/{{$row.id}}" target="_blank">{{$row.tagessoll_allgemein|default:0|number_format:2:",":"."}}</a></td>
                                            {{if $smarty_vars.company != 'tps'}}
                                                <td class="text-right"><a href="/tagessoll/bearbeiten/{{$row.id}}" target="_blank">{{$row.tagessoll_montag|default:0|number_format:2:",":"."}}</a></td>
                                                <td class="text-right"><a href="/tagessoll/bearbeiten/{{$row.id}}" target="_blank">{{$row.tagessoll_dienstag|default:0|number_format:2:",":"."}}</a></td>
                                                <td class="text-right"><a href="/tagessoll/bearbeiten/{{$row.id}}" target="_blank">{{$row.tagessoll_mittwoch|default:0|number_format:2:",":"."}}</a></td>
                                                <td class="text-right"><a href="/tagessoll/bearbeiten/{{$row.id}}" target="_blank">{{$row.tagessoll_donnerstag|default:0|number_format:2:",":"."}}</a></td>
                                                <td class="text-right"><a href="/tagessoll/bearbeiten/{{$row.id}}" target="_blank">{{$row.tagessoll_freitag|default:0|number_format:2:",":"."}}</a></td>
                                                <td class="text-right"><a href="/tagessoll/bearbeiten/{{$row.id}}" target="_blank">{{$row.tagessoll_samstag|default:0|number_format:2:",":"."}}</a></td>
                                                <td class="text-right"><a href="/tagessoll/bearbeiten/{{$row.id}}" target="_blank">{{$row.tagessoll_sonntag|default:0|number_format:2:",":"."}}</a></td>
                                            {{/if}}
                                        </tr>
                                    {{/foreach}}
                                </tbody>
                            </table>
                        {{/if}}
                    {{/if}}

                    {{if $smarty_vars.company != 'tps'}}
                        {{if $smarty_vars.current_user.usergroup->hasRight('arbeitszeitkonto')}}
                            <h4 class="mt-4">Arbeitszeitkonto</h4>
                            {{if isset($smarty_vars.arbeitszeitkontoliste)}}
                                <table class="table table-striped table-bordered table-hover dt-responsive nowrap mt-3" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <td class="text-right font-weight-bold">Jahr</td>
                                            <td class="text-right font-weight-bold">Monat</td>
                                            <td class="text-right font-weight-bold">Stunden</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{foreach from=$smarty_vars.arbeitszeitkontoliste item='row'}}
                                            <tr class="black-links">
                                                <td class="text-right"><a href="/arbeitszeitkonto/bearbeiten/{{$row.id}}" target="_blank">{{$row.jahr}}</a></td>
                                                <td class="text-right"><a href="/arbeitszeitkonto/bearbeiten/{{$row.id}}" target="_blank">{{$row.monat}}</a></td>
                                                <td class="text-right"><a href="/arbeitszeitkonto/bearbeiten/{{$row.id}}" target="_blank">{{$row.stunden|default:0|number_format:2:",":"."}}</a></td>
                                            </tr>
                                        {{/foreach}}
                                    </tbody>
                                </table>
                            {{/if}}
                        {{/if}}
                    {{/if}}
                {{elseif ($smarty_vars.values.tab == 'praeferenzen')}}
                    <form action="/mitarbeiter/bearbeiten/{{$smarty_vars.values.personalnummer|default:"???"}}/praeferenzen" method="post">
                        <h4 class="mt-4">Arbeitszeiten</h4>
                        <div class="row mx-0">
	                        <div class="form-group col-6 col-sm-3 col-lg px-1 mb-lg-0{{if isset($smarty_vars.values.montag_von_error)}}{{if $smarty_vars.values.montag_von_error}} has-danger{{/if}}{{/if}}">
		                        <label class="form-control-label">Mo<span class="hidden-lg-up">ntag</span></label>
                                <input class="form-control rounded-0 text-center px-0 inputmask" name="montag_von" data-inputmask="'mask' : '99:99'" type="text" value="{{$smarty_vars.values.montag_von|default:""}}">
	                        </div>
	                        <div class="form-group col-6 col-sm-3 col-lg px-1 mb-lg-0{{if isset($smarty_vars.values.montag_bis_error)}}{{if $smarty_vars.values.montag_bis_error}} has-danger{{/if}}{{/if}}">
		                        <label class="form-control-label">&nbsp;</label>
                                <input class="form-control rounded-0 text-center px-0 inputmask" name="montag_bis" data-inputmask="'mask' : '99:99'" type="text" value="{{$smarty_vars.values.montag_bis|default:""}}">
	                        </div>
	                        <div class="form-group col-6 col-sm-3 col-lg px-1 mb-lg-0{{if isset($smarty_vars.values.dienstag_von_error)}}{{if $smarty_vars.values.dienstag_von_error}} has-danger{{/if}}{{/if}}">
		                        <label class="form-control-label">Di<span class="hidden-lg-up">enstag</span></label>
                                <input class="form-control rounded-0 text-center px-0 inputmask" name="dienstag_von" data-inputmask="'mask' : '99:99'" type="text" value="{{$smarty_vars.values.dienstag_von|default:""}}">
	                        </div>
	                        <div class="form-group col-6 col-sm-3 col-lg px-1 mb-lg-0{{if isset($smarty_vars.values.dienstag_bis_error)}}{{if $smarty_vars.values.dienstag_bis_error}} has-danger{{/if}}{{/if}}">
		                        <label class="form-control-label">&nbsp;</label>
                                <input class="form-control rounded-0 text-center px-0 inputmask" name="dienstag_bis" data-inputmask="'mask' : '99:99'" type="text" value="{{$smarty_vars.values.dienstag_bis|default:""}}">
	                        </div>
	                        <div class="form-group col-6 col-sm-3 col-lg px-1 mb-lg-0{{if isset($smarty_vars.values.mittwoch_von_error)}}{{if $smarty_vars.values.mittwoch_von_error}} has-danger{{/if}}{{/if}}">
		                        <label class="form-control-label">Mi<span class="hidden-lg-up">ttwoch</span></label>
                                <input class="form-control rounded-0 text-center px-0 inputmask" name="mittwoch_von" data-inputmask="'mask' : '99:99'" type="text" value="{{$smarty_vars.values.mittwoch_von|default:""}}">
	                        </div>
	                        <div class="form-group col-6 col-sm-3 col-lg px-1 mb-lg-0{{if isset($smarty_vars.values.mittwoch_bis_error)}}{{if $smarty_vars.values.mittwoch_bis_error}} has-danger{{/if}}{{/if}}">
		                        <label class="form-control-label">&nbsp;</label>
                                <input class="form-control rounded-0 text-center px-0 inputmask" name="mittwoch_bis" data-inputmask="'mask' : '99:99'" type="text" value="{{$smarty_vars.values.mittwoch_bis|default:""}}">
	                        </div>
	                        <div class="form-group col-6 col-sm-3 col-lg px-1 mb-lg-0{{if isset($smarty_vars.values.donnerstag_von_error)}}{{if $smarty_vars.values.donnerstag_von_error}} has-danger{{/if}}{{/if}}">
		                        <label class="form-control-label">Do<span class="hidden-lg-up">nnerstag</span></label>
                                <input class="form-control rounded-0 text-center px-0 inputmask" name="donnerstag_von" data-inputmask="'mask' : '99:99'" type="text" value="{{$smarty_vars.values.donnerstag_von|default:""}}">
	                        </div>
	                        <div class="form-group col-6 col-sm-3 col-lg px-1 mb-lg-0{{if isset($smarty_vars.values.donnerstag_bis_error)}}{{if $smarty_vars.values.donnerstag_bis_error}} has-danger{{/if}}{{/if}}">
		                        <label class="form-control-label">&nbsp;</label>
                                <input class="form-control rounded-0 text-center px-0 inputmask" name="donnerstag_bis" data-inputmask="'mask' : '99:99'" type="text" value="{{$smarty_vars.values.donnerstag_bis|default:""}}">
	                        </div>
	                        <div class="form-group col-6 col-sm-3 col-lg px-1 mb-lg-0{{if isset($smarty_vars.values.freitag_von_error)}}{{if $smarty_vars.values.freitag_von_error}} has-danger{{/if}}{{/if}}">
		                        <label class="form-control-label">Fr<span class="hidden-lg-up">eitag</span></label>
                                <input class="form-control rounded-0 text-center px-0 inputmask" name="freitag_von" data-inputmask="'mask' : '99:99'" type="text" value="{{$smarty_vars.values.freitag_von|default:""}}">
	                        </div>
	                        <div class="form-group col-6 col-sm-3 col-lg px-1 mb-lg-0{{if isset($smarty_vars.values.freitag_bis_error)}}{{if $smarty_vars.values.freitag_bis_error}} has-danger{{/if}}{{/if}}">
		                        <label class="form-control-label">&nbsp;</label>
                                <input class="form-control rounded-0 text-center px-0 inputmask" name="freitag_bis" data-inputmask="'mask' : '99:99'" type="text" value="{{$smarty_vars.values.freitag_bis|default:""}}">
	                        </div>
	                        <div class="form-group col-6 col-sm-3 col-lg px-1 mb-lg-0{{if isset($smarty_vars.values.samstag_von_error)}}{{if $smarty_vars.values.samstag_von_error}} has-danger{{/if}}{{/if}}">
		                        <label class="form-control-label">Sa<span class="hidden-lg-up">mstag</span></label>
                                <input class="form-control rounded-0 text-center px-0 inputmask" name="samstag_von" data-inputmask="'mask' : '99:99'" type="text" value="{{$smarty_vars.values.samstag_von|default:""}}">
	                        </div>
	                        <div class="form-group col-6 col-sm-3 col-lg px-1 mb-lg-0{{if isset($smarty_vars.values.samstag_bis_error)}}{{if $smarty_vars.values.samstag_bis_error}} has-danger{{/if}}{{/if}}">
		                        <label class="form-control-label">&nbsp;</label>
                                <input class="form-control rounded-0 text-center px-0 inputmask" name="samstag_bis" data-inputmask="'mask' : '99:99'" type="text" value="{{$smarty_vars.values.samstag_bis|default:""}}">
	                        </div>
	                        <div class="form-group col-6 col-sm-3 col-lg px-1 mb-lg-0{{if isset($smarty_vars.values.sonntag_von_error)}}{{if $smarty_vars.values.sonntag_von_error}} has-danger{{/if}}{{/if}}">
		                        <label class="form-control-label">So<span class="hidden-lg-up">nntag</span></label>
                                <input class="form-control rounded-0 text-center px-0 inputmask" name="sonntag_von" data-inputmask="'mask' : '99:99'" type="text" value="{{$smarty_vars.values.sonntag_von|default:""}}">
	                        </div>
	                        <div class="form-group col-6 col-sm-3 col-lg px-1 mb-lg-0{{if isset($smarty_vars.values.sonntag_bis_error)}}{{if $smarty_vars.values.sonntag_bis_error}} has-danger{{/if}}{{/if}}">
		                        <label class="form-control-label">&nbsp;</label>
                                <input class="form-control rounded-0 text-center px-0 inputmask" name="sonntag_bis" data-inputmask="'mask' : '99:99'" type="text" value="{{$smarty_vars.values.sonntag_bis|default:""}}">
	                        </div>
                        </div>

                        <h4 class="mt-4">Freigegeben für</h4>
                        <select class="selectable selectable_multiple form-control" tabindex="-1" name="abteilungsfreigaben[]" data-placeholder=" hier klicken..." multiple>
                            {{if isset($smarty_vars.abteilungsliste)}}
                                {{foreach from=$smarty_vars.abteilungsliste item='row'}}
                                    <option value="{{$row.id}}" {{if isset($smarty_vars.values.abteilungsfreigaben)}} {{if in_array($row.id, $smarty_vars.values.abteilungsfreigaben)}} selected{{/if}}{{/if}}>{{$row.bezeichnung}}</option>
                                {{/foreach}}
                            {{/if}}
                        </select>

                        <h4 class="mt-4">Stammmitarbeiter für</h4>
                        <select class="selectable selectable_multiple form-control" tabindex="-1" name="stamm[]" data-placeholder=" hier klicken..." multiple>
                            {{if isset($smarty_vars.kundenliste)}}
                                {{foreach from=$smarty_vars.kundenliste item='row'}}
                                    <option value="{{$row.kundennummer}}" {{if isset($smarty_vars.values.stamm)}} {{if in_array($row.kundennummer, $smarty_vars.values.stamm)}} selected{{/if}}{{/if}}>{{$row.kundennummer}} - {{$row.name}}</option>
                                {{/foreach}}
                            {{/if}}
                        </select>

                        <h4 class="mt-4">Springer für</h4>
                        <select class="selectable selectable_multiple form-control" tabindex="-1" name="springer[]" data-placeholder=" hier klicken..." multiple>
                            {{if isset($smarty_vars.kundenliste)}}
                                {{foreach from=$smarty_vars.kundenliste item='row'}}
                                    <option value="{{$row.kundennummer}}" {{if isset($smarty_vars.values.springer)}} {{if in_array($row.kundennummer, $smarty_vars.values.springer)}} selected{{/if}}{{/if}}>{{$row.kundennummer}} - {{$row.name}}</option>
                                {{/foreach}}
                            {{/if}}
                        </select>

                        <h4 class="mt-4">Gesperrt für</h4>
                        <select class="selectable selectable_multiple form-control" tabindex="-1" name="sperre[]" data-placeholder=" hier klicken..." multiple>
                            {{if isset($smarty_vars.kundenliste)}}
                                {{foreach from=$smarty_vars.kundenliste item='row'}}
                                    <option value="{{$row.kundennummer}}" {{if isset($smarty_vars.values.sperre)}} {{if in_array($row.kundennummer, $smarty_vars.values.sperre)}} selected{{/if}}{{/if}}>{{$row.kundennummer}} - {{$row.name}}</option>
                                {{/foreach}}
                            {{/if}}
                        </select>

                        <input type="hidden" name="praeferenzen_submitted" value="true">
                        <button type="submit" class="btn btn-secondary form-control mt-3">Speichern</button>
                    </form>
                {{elseif ($smarty_vars.values.tab == 'lohnbuchungen')}}
                    <a class="btn btn-secondary btn-block my-3" href="/lohnbuchung/erstellen/{{$smarty_vars.values.personalnummer|default:"???"}}"><i class="fa fa-plus-circle mr-1"></i> Neue Lohnbuchung hinzufügen</a>
                    {{include file='views/main/components/tables.tpl'}}
                    {{if isset($smarty_vars.lohnbuchungsliste)}}
                        {{$table_tag|default:"<table>"}}
                            <thead>
                                <tr>
                                    <td>ID</td>
                                    <td>Buchungsmonat</td>
                                    <td>Lohnart</td>
                                    <td>Wert</td>
                                    <td>Faktor</td>
                                    <td>Bezeichnung</td>
                                    <td>Angelegt von</td>
                                    <td>Angelegt am</td>
                                </tr>
                            </thead>
                            <tbody>
                                {{foreach from=$smarty_vars.lohnbuchungsliste item='row'}}
                                    <tr>
                                        <td class="clickable" data-href="/lohnbuchung/bearbeiten/{{$row.id}}">{{$row.id}}</td>
                                        <td class="clickable" data-href="/lohnbuchung/bearbeiten/{{$row.id}}">{{$row.buchungsmonat}}</td>
                                        <td class="clickable" data-href="/lohnbuchung/bearbeiten/{{$row.id}}">{{$row.lohnart}}</td>
                                        <td class="clickable" data-href="/lohnbuchung/bearbeiten/{{$row.id}}">{{$row.wert}}</td>
                                        <td class="clickable" data-href="/lohnbuchung/bearbeiten/{{$row.id}}">{{$row.faktor}}</td>
                                        <td class="clickable" data-href="/lohnbuchung/bearbeiten/{{$row.id}}">{{$row.bezeichnung}}</td>
                                        <td class="clickable" data-href="/lohnbuchung/bearbeiten/{{$row.id}}">{{$row.benutzer}}</td>
                                        <td class="clickable" data-href="/lohnbuchung/bearbeiten/{{$row.id}}">{{$row.zeit}}</td>
                                    </tr>
                                {{/foreach}}
                            </tbody>
                        </table>
                    {{/if}}
                {{/if}}
            {{/if}}
        </div>
    </div>
</div>

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
        .mitarbeiter-bearbeiten-menu a {
            background: #333;
            color: white;
            padding: 10px 0 10px 0;
            display: block;
        }
        .mitarbeiter-bearbeiten-menu a:hover {
            background: #404040;
            cursor: pointer;
        }
        .mitarbeiter-bearbeiten-menu a:hover, .mitarbeiter-bearbeiten-menu a:link, .mitarbeiter-bearbeiten-menu a:visited, .mitarbeiter-bearbeiten-menu a:active {
            text-decoration: none;
        }
        .mitarbeiter-bearbeiten-menu a.active {
            background: #282828;
        }
        .mitarbeiter-bearbeiten-menu a.active:hover {
            background: #282828;
            cursor: unset;
        }
        a.btn:hover, a.btn:link, a.btn:visited, a.btn:active {
            color: black;
        }
        .black-links a:hover, .black-links a:link, .black-links a:visited, .black-links a:active {
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

    <!-- jquery.inputmask -->
    <script src="/assets/vendors/jquery.inputmask/dist/min/jquery.inputmask.bundle.min.js"></script>
    <script>
        $(document).ready(function () {
            $(".inputmask").inputmask();
        });
    </script>
    <!-- /jquery.inputmask-->

    <!-- Mitarbeiterswitch -->
    <script>
        $maswitch = $('#mitarbeiterswitch');
        $maswitch.change(function () {
            if ($maswitch.val() != '') {
                window.location = '/mitarbeiter/bearbeiten/' + $maswitch.val();
            }
        });
    </script>
    <!-- /Mitarbeiterswitch -->

    {{$js|default:""}}
{{/capture}}

{{$js=$smarty.capture.scripts scope=parent}}