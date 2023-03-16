{{$title="Schichtplaner" scope=parent}}

{{if isset($smarty_vars.values.title_zusatz)}}
    {{$title_zusatz=$smarty_vars.values.title_zusatz scope=parent}}
{{/if}}

{{$hide_title=true scope=parent}}

<div class="row mb-4" id="error_container" style="display:none;">
    <div class="col">
        <div class="content-box bg-danger text-white" id="error_content"></div>
    </div>
</div>

<div class="row mb-4 print-hide">
    <div class="col" id="header-col">
        <div class="content-box p-0" id="header">

            <div class="title-box text-white px-4 py-3" id="header-non-sticky">
                <h4 class="mb-0 hidden-xs-down">Schichtplaner<span class="float-right"><a class="change-kw-button" href="/schichten/planer/{{$smarty_vars.letzte_woche.jahr|default:"???"}}/{{$smarty_vars.letzte_woche.kw|default:"???"}}/{{$smarty_vars.kunden_url_string|default:""}}"><i class="fa fa-chevron-circle-left mr-3"></i></a><span class="hidden-xs-down">KW {{$smarty_vars.von|date_format:"W"|default:"???"}}</span><span class="hidden-sm-down"> | {{$smarty_vars.von|date_format:"d.m."|default:"???"}} - {{$smarty_vars.bis|date_format:"d.m.Y"|default:"???"}}</span><a class="change-kw-button" href="/schichten/planer/{{$smarty_vars.naechste_woche.jahr|default:"???"}}/{{$smarty_vars.naechste_woche.kw|default:"???"}}/{{$smarty_vars.kunden_url_string|default:""}}"><i class="fa fa-chevron-circle-right ml-3 mr-2"></i></a><span class="hidden-md-down"> | {{$smarty_vars.kunden|default:"???"}}</span><span class="fa fa-bars ml-4 header-button"></span></span></h4>
                <h5 class="mb-0 hidden-sm-up">Schichtplaner<span class="float-right"><span class="fa fa-bars ml-4 header-button"></span></span></h5>
            </div>


            <div class="title-box text-white p-2 px-sm-4 py-sm-3" id="header-sticky" style="display:none;">
                <h5 class="hidden-xs-down"><strong>Schichtplaner</strong><span class="float-right" id="kunde-gross">&nbsp;</span></h5>
                <h5 class="mb-0 hidden-xs-down">KW {{$smarty_vars.von|date_format:"W"|default:"???"}} | {{$smarty_vars.von|date_format:"d.m."|default:"???"}} - {{$smarty_vars.bis|date_format:"d.m.Y"|default:"???"}}<span class="float-right" id="abteilung-gross">&nbsp;</span></h5>
                <div class="header-info hidden-sm-up"><strong>Schichtplaner</strong></div>
                <div class="header-info hidden-sm-up" id="kunde-klein">&nbsp;</div>
                <div class="header-info hidden-sm-up" id="abteilung-klein">&nbsp;</div>
            </div>

            <div class="p-4" id="header-menu" style="display: none;">
                <div class="row">
                    <div class="col-12 col-lg-4 mb-5 mb-lg-0">
                        <h3 class="mb-3">Schichtplaner öffnen</h3>
                        <form action="/schichten" method="post">
                            <div class="mb-3">
                                <h5>Kalenderwoche auswählen</h5>
                                <div class="btn-group d-flex w-100 mb-3" role="group">
                                    <button type="button" class="btn btn-secondary" id="vorige_kw"><i class="fa fa-angle-left"></i></button>
                                    <button type="button" class="btn btn-secondary w-100" id="aktuelle_kw">Aktuelle K<span class="hidden-sm-up">W</span><span class="hidden-xs-down hidden-lg-up">alenderwoche</span><span class="hidden-lg-down">alenderwoche</span><span class="hidden-md-down hidden-xl-up">W</span></button>
                                    <button type="button" class="btn btn-secondary" id="naechste_kw"><i class="fa fa-angle-right"></i></button>
                                </div>
                                <div class="input-group">
                                    <div class="input-group-btn">
                                        <button type="button" class="btn btn-secondary dropdown-toggle p-xs-down-1" data-toggle="dropdown">
                                            <span class="hidden-md-down" id="jahresauswahl_button">{{$smarty_vars.values.jahr|default:"???"}}</span>
                                        </button>
                                        <div class="dropdown-menu" id="jahresauswahl">
                                            {{if isset($smarty_vars.jahresliste)}}
                                                {{foreach from=$smarty_vars.jahresliste item='row'}}
                                                    <a class="dropdown-item" href="javascript:;" data-jahr="{{$row}}">{{$row}}</a>
                                                {{/foreach}}
                                            {{/if}}
                                        </div>
                                    </div>

                                    <select class="selectable form-control" id="kalenderwoche" tabindex="-1" name="kalenderwoche" data-placeholder="-- bitte auswählen" required>
                                        <option value="">-- bitte auswählen</option>
                                        {{if isset($smarty_vars.kalenderwochen)}}
                                            {{foreach from=$smarty_vars.kalenderwochen item='row'}}
                                                <option value="{{$row.kw}}" {{if isset($smarty_vars.values.kalenderwoche)}} {{if $smarty_vars.values.kalenderwoche == $row.kw}} selected{{/if}}{{/if}}>KW {{$row.kw}} | {{$row.von}} - {{$row.bis}}</option>
                                            {{/foreach}}
                                        {{/if}}
                                    </select>

                                    <input type="hidden" name="jahr" id="jahr" value="{{$smarty_vars.values.jahr|default:""}}">
                                </div>
                            </div>
                            {{if $smarty_vars.kunden_auswaehlen_anzeigen}}
                                <div class="mb-3">
                                    <h5>Kunden auswählen</h5>
                                    <select class="selectable selectable_multiple form-control" id="kunden" tabindex="-1" name="kunden[]" data-placeholder=" hier klicken..." multiple>
                                        {{if isset($smarty_vars.kundenliste)}}
                                            {{foreach from=$smarty_vars.kundenliste item='row'}}
                                                <option value="{{$row.kundennummer}}"{{if $row.selected}} selected{{/if}}>{{$row.kundennummer}} - {{$row.name}}</option>
                                            {{/foreach}}
                                        {{/if}}
                                    </select>
                                </div>
                            {{/if}}
                            <div>
                                <button type="submit" class="btn btn-secondary btn-block">Schichtplaner öffnen</button>
                            </div>
                        </form>
                    </div>
                    <div class="col-12 col-lg-4 mb-5 mb-lg-0">
                        <h3 class="mb-3">Filter</h3>
                        <div class="mb-3">
                            <h5>Abteilungen filtern</h5>
                            <select class="selectable selectable_multiple form-control" id="abteilungsfilter" tabindex="-1" data-placeholder=" hier klicken..." multiple>
                                {{if isset($smarty_vars.abteilungsliste)}}
                                    {{foreach from=$smarty_vars.abteilungsliste item='row'}}
                                        <option value="{{$row.id}}">{{$row.bezeichnung}}</option>
                                    {{/foreach}}
                                {{/if}}
                            </select>
                        </div>
                        <div class="mb-3">
                            <h5>Mitarbeiter filtern</h5>
                            <select class="selectable selectable_multiple form-control" id="mitarbeiterfilter" tabindex="-1" data-placeholder=" hier klicken..." multiple>
                                {{if isset($smarty_vars.mitarbeiterliste)}}
                                    {{foreach from=$smarty_vars.mitarbeiterliste item='row'}}
                                        <option value="{{$row.id}}">{{$row.nachname}}, {{$row.vorname}} ({{$row.personalnummer}})</option>
                                    {{/foreach}}
                                {{/if}}
                            </select>
                        </div>
                        <div class="mb-3">
                            <h5>Schichten filtern</h5>
                            <div><input type="checkbox" class="js-switch schichtfilter" data-status="offen" checked> offen</div>
                            <div><input type="checkbox" class="js-switch schichtfilter" data-status="nicht_benachrichtigt" checked> noch nicht benachrichtigt</div>
                            <div><input type="checkbox" class="js-switch schichtfilter" data-status="benachrichtigt" checked> benachrichtigt</div>
                            <div><input type="checkbox" class="js-switch schichtfilter" data-status="nicht_bestaetigt" checked> umgeplant / noch zu bestätigen</div>
                            <div><input type="checkbox" class="js-switch schichtfilter" data-status="kann_nicht" checked> MA abgesagt / Tag geht nicht</div>
                            <div><input type="checkbox" class="js-switch schichtfilter" data-status="kann_andere_uhrzeit" checked> MA abgesagt / Zeit geht nicht</div>
                            <div><input type="checkbox" class="js-switch schichtfilter" data-status="stundenzettel_bestaetigt" checked> mit Stundenzettel abgeglichen</div>
                            <div><input type="checkbox" class="js-switch schichtfilter" data-status="archiviert" checked> abgeschlossen</div>
                        </div>
                        <div>
                            <button class="btn btn-secondary btn-block" id="alle_filter_zuruecksetzen">Alle Filter zurücksetzen</button>
                        </div>
                    </div>
                    <div class="col-12 col-lg-4">
                        <h3 class="mb-3">Statistiken</h3>
                        <div class="mb-3">
                            <h5>Schichten</h5>
                            <div class="progress mt-3 mb-2">
                                <div class="progress-bar" id="progress_bar" style="width: {{$smarty_vars.statistiken.prozent|default:"0"}}%; height: 20px; line-height: 20px; overflow: hidden;">{{$smarty_vars.statistiken.prozent|default:"0"}}%</div>
                            </div>
                            <div class="mb-3"><span id="statistik_offen">{{$smarty_vars.statistiken.offen|default:"???"}}</span> offene<span id="statistik_insgesamt" class="float-right">{{$smarty_vars.statistiken.insgesamt|default:"???"}} insgesamt</span></div>
                            <div>noch nicht benachrichtigt:<span id="statistik_nicht_benachrichtigt" class="float-right">{{$smarty_vars.statistiken.nicht_benachrichtigt|default:"???"}}</span></div>
                            <div>benachrichtigt:<span id="statistik_benachrichtigt" class="float-right">{{$smarty_vars.statistiken.benachrichtigt|default:"???"}}</span></div>
                            <div>umgeplant / noch zu bestätigen:<span id="statistik_nicht_bestaetigt" class="float-right">{{$smarty_vars.statistiken.nicht_bestaetigt|default:"???"}}</span></div>
                            <div>MA abgesagt / Tag geht nicht:<span id="statistik_kann_nicht" class="float-right">{{$smarty_vars.statistiken.kann_nicht|default:"???"}}</span></div>
                            <div>MA abgesagt / Zeit geht nicht:<span id="statistik_kann_andere_uhrzeit" class="float-right">{{$smarty_vars.statistiken.kann_andere_uhrzeit|default:"???"}}</span></div>
                            <div>mit Stundenzettel abgeglichen:<span id="statistik_stundenzettel_bestaetigt" class="float-right">{{$smarty_vars.statistiken.stundenzettel_bestaetigt|default:"???"}}</span></div>
                            <div>abgeschlossen:<span id="statistik_archiviert" class="float-right">{{$smarty_vars.statistiken.archiviert|default:"???"}}</span></div>
                        </div>
                        <div>
                            {{if $smarty_vars.aenderungen}}
                                <button id="woche_abschliessen" class="btn btn-secondary btn-block">K<span class="hidden-xs-down">alenderwoche</span><span class="hidden-sm-up">W</span> abschließen</button>
                                <button id="monat_abschliessen" class="btn btn-secondary btn-block">{{$smarty_vars['monat'][$smarty_vars.von|date_format:"m"]}} abschließen</button>
                                {{if $smarty_vars.woche_oeffnen_dispotabelle_herunterladen}}
                                    <button id="woche_oeffnen" class="btn btn-secondary btn-block">K<span class="hidden-xs-down">alenderwoche</span><span class="hidden-sm-up">W</span> öffnen</button>
                                    <a class="btn btn-secondary btn-block" target="_blank" href="/schichten/dispotabelle/{{$smarty_vars.requested_year|default:"???"}}/{{$smarty_vars.requested_week|default:"???"}}">Dispotabelle herunterladen</a>
                                {{/if}}
                            {{/if}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4 print-show">
    <div class="col">
        <div class="content-box p-4 header-print">
            <h3>KW {{$smarty_vars.requested_week|default:"???"}} | {{$smarty_vars.von|date_format:"d.m."|default:"???"}} - {{$smarty_vars.bis|date_format:"d.m.Y"|default:"???"}}</h3>
        </div>
    </div>
</div>

{{if isset($smarty_vars.data)}}
    {{foreach from=$smarty_vars.data item='kunde'}}
        <div class="row mb-4">
            <div class="col">
                <div class="content-box p-0">
                    <div class="kunde-title px-4 py-3" data-kunde="{{$kunde.kundennummer|default:"???"}} - {{$kunde.name|default:"???"}}">
                        <div class="kunde-title-bezeichnung float-left">
                            <h4 class="mb-0 hidden-xs-down">Kunde {{$kunde.kundennummer|default:"???"}} | {{$kunde.name|default:"???"}}</h4>
                            <h5 class="mb-0 hidden-sm-up">Kunde {{$kunde.kundennummer|default:"???"}} | {{$kunde.name|default:"???"}}</h5>
                        </div>
                        <div class="kunde-title-info-symbol print-hide">
                            <h4 class="mb-0 text-right hidden-xs-down"><span class="fa fa-info-circle kunde-button print-hidden" data-kunde-id="{{$kunde.id|default:"???"}}"></span></h4>
                            <h5 class="mb-0 text-right hidden-sm-up"><span class="fa fa-info-circle kunde-button print-hidden" data-kunde-id="{{$kunde.id|default:"???"}}"></span></h5>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                        
                    <div class="p-4 kunde-menu" id="kunde-{{$kunde.id|default:"???"}}-menu" style="display: none;">
                        <div class="row">
                            <div class="col-12 col-sm-6 col-md-4">
                                <div><strong>Kundennummer</strong></div>
                                <div class="mb-3">{{$kunde.kundennummer|default:"???"}}</div>

                                <div><strong>Name</strong></div>
                                <div class="mb-3">{{$kunde.name|default:"???"}}</div>

                                <div><strong>Strasse, Hausnr.</strong></div>
                                <div class="mb-3">{{$kunde.strasse|default:"- nicht vorhanden -"}}</div>

                                <div><strong>Postleitzahl</strong></div>
                                <div class="mb-3">{{$kunde.postleitzahl|default:"- nicht vorhanden -"}}</div>

                                <div><strong>Ort</strong></div>
                                <div class="mb-3 mb-md-0">{{$kunde.ort|default:"- nicht vorhanden -"}}</div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-4">
                                <div><strong>Ansprechpartner</strong></div>
                                <div class="mb-3">{{$kunde.ansprechpartner|default:"- nicht vorhanden -"}}</div>

                                <div><strong>Telefon 1</strong></div>
                                <div class="mb-3">{{$kunde.telefon1|default:"- nicht vorhanden -"}}</div>

                                <div><strong>Telefon 2</strong></div>
                                <div class="mb-3">{{$kunde.telefon2|default:"- nicht vorhanden -"}}</div>

                                <div><strong>Fax</strong></div>
                                <div class="mb-3">{{$kunde.fax|default:"- nicht vorhanden -"}}</div>

                                <div><strong>E-Mail-Adresse</strong></div>
                                <div class="mb-3 mb-md-0"><a href="mailto:{{$kunde.emailadresse|default:""}}">{{$kunde.emailadresse|default:"- nicht vorhanden -"}}</a></div>
                            </div>
                            <div class="col-12 col-md-4">
                                {{if $smarty_vars.aenderungen}}
                                    <div><button class="btn btn-secondary btn-block kunde_abschliessen mb-3" data-kunde-id="{{$kunde.id|default:"???"}}">Kunden abschließen</button></div>
                                    <div><button class="btn btn-secondary btn-block monat_abschliessen mb-3" data-kunde-id="{{$kunde.id|default:"???"}}">{{$smarty_vars['monat'][$smarty_vars.von|date_format:"m"]}} abschließen</button></div>
                                    {{if $smarty_vars.woche_oeffnen_dispotabelle_herunterladen}}
                                        <div><button class="btn btn-secondary btn-block kunde_oeffnen" data-kunde-id="{{$kunde.id|default:"???"}}">Kunden öffnen</button></div>
                                    {{/if}}
                                {{/if}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{if $smarty_vars.company != 'tps'}}
            <div class="print-show mb-4">
                Bezugnehmend auf den Arbeitnehmerüberlassungsvertrag vom {{$kunde.unterzeichnungsdatumrahmenvertrag}} besetzen wir Ihre Schichten mit den aufgeführten Mitarbeitern: | Betriebsnummer: 26073440
            </div>
        {{/if}}

        {{foreach from=$kunde.abteilungen item='abteilung'}}
            <div class="row mb-4 abteilung abteilung-{{$abteilung.id|default:"???"}}">
                {{if $smarty_vars.aenderungen}}
                    <div class="hidden-lg-down col-xl-3 print-hide">
                        <div class="content-box schicht-menu-container-large" id="schicht-menu-container-large-{{$kunde.id|default:"???"}}-{{$abteilung.id|default:"???"}}">
                            <h4>Bearbeiten</h4>

                            -- bitte eine/mehrere Schicht(en) auswählen
                        </div>
                    </div>
                {{/if}}
                <div class="col-12 col-xl">
                    <div class="content-box abteilung-box" id="abteilung-box-{{$kunde.id|default:"???"}}-{{$abteilung.id|default:"???"}}" data-abteilung="{{$abteilung.name|default:"???"}}" data-kunde-id="{{$kunde.id|default:"???"}}" data-abteilung-id="{{$abteilung.id|default:"???"}}">
                        <h4>{{$abteilung.name|default:"???"}}  <span class="abteilung-preis">{{$abteilung.preis|default:" "}} </span></h4>
                        <div class="row mx-0">
                            {{foreach from=$abteilung.schichten item='schichten' key='wochentag'}}
                                <!-- Wochentagsspalte -->
                                <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl px-1px overflow-x-auto" id="wochentagsspalte-{{$kunde.id|default:"???"}}-{{$abteilung.id|default:"???"}}-{{$wochentag|default:"???"}}">
                                    <!-- Wochentagsbezeichnung -->
                                    <div class="wochentag">
                                        <div class="float-left wochentag-name">
                                            {{$smarty_vars.wochentage.$wochentag.name|default:"???"}}
                                        </div>
                                        <div class="text-right wochentag-pluszeichen-container">
                                            {{if $smarty_vars.aenderungen}}
                                                <i class="fa fa-plus-circle wochentag-pluszeichen print-hide" data-year="{{$smarty_vars.requested_year|default:"???"}}" data-kw="{{$smarty_vars.requested_week|default:"???"}}" data-day="{{$wochentag|default:"???"}}" data-kunde-id="{{$kunde.id|default:"???"}}" data-abteilung-id="{{$abteilung.id|default:"???"}}"></i>
                                            {{/if}}
                                        </div>
                                        <div>
                                            {{$smarty_vars.wochentage.$wochentag.datum|default:"???"}}
                                        </div>
                                    </div>
                                    <!-- /Wochentagsbezeichnung -->

                                    {{if $smarty_vars.company == 'tps'}}
                                        {{if $abteilung.palettenabteilung}}
                                        <!-- Palettenanzahl -->
                                            <div class="palettenanzahl has-tooltip p-0" id="palettenanzahl-{{$kunde.id|default:"???"}}-{{$abteilung.id|default:"???"}}-{{$wochentag|default:"???"}}" data-toggle="tooltip" data-placement="top" title="Palettenanzahl">
                                                <div class="form-group m-0">
                                                    <input type="text" class="form-control palettenanzahl-input" id="palettenanzahl-input-{{$kunde.id|default:"???"}}-{{$abteilung.id|default:"???"}}-{{$wochentag|default:"???"}}" value="{{$abteilung.paletten.$wochentag|default:0|number_format:2:",":"."}}" data-original-value="{{$abteilung.paletten.$wochentag|default:0|number_format:2:",":"."}}" data-year="{{$smarty_vars.requested_year|default:"???"}}" data-kw="{{$smarty_vars.requested_week|default:"???"}}" data-day="{{$wochentag|default:"???"}}" data-kunde-id="{{$kunde.id|default:"???"}}" data-abteilung-id="{{$abteilung.id|default:"???"}}">
                                                </div>
                                            </div>
                                            <!-- /Palettenanzahl -->
                                        {{/if}}
                                    {{/if}}

                                    <!-- Schichtstunden -->
                                    <div class="schichtstunden has-tooltip" data-toggle="tooltip" data-placement="top" title="Schichtstunden" id="schichtstunden-{{$kunde.id|default:"???"}}-{{$abteilung.id|default:"???"}}-{{$wochentag|default:"???"}}">
                                        {{$abteilung.stunden.$wochentag|default:"0"|number_format:2:",":"."}}{{if $smarty_vars.company != 'tps'}}{{if $wochentag == 7}} / <span class="font-weight-bold" id="schichtstunden-insgesamt-{{$kunde.id|default:"???"}}-{{$abteilung.id|default:"???"}}">{{$abteilung.stunden.insgesamt|default:"0"|number_format:2:",":"."}}</span>{{/if}}{{/if}}
                                    </div>
                                    <!-- /Schichtstunden -->

                                    {{if $smarty_vars.company == 'tps'}}
                                        {{if $abteilung.palettenabteilung}}
                                            <!-- Produktivitätsfaktor -->
                                            <div class="produktivitaetsfaktor has-tooltip" data-toggle="tooltip" data-placement="top" title="Produktivitätsfaktor" id="produktivitaetsfaktor-{{$kunde.id|default:"???"}}-{{$abteilung.id|default:"???"}}-{{$wochentag|default:"???"}}">
                                                {{$abteilung.produktivitaetsfaktor.$wochentag|default:0|number_format:2:",":"."}}
                                            </div>
                                            <!-- /Produktivitätsfaktor -->
                                        {{/if}}
                                    {{/if}}

                                    {{foreach from=$schichten item='schicht'}}
                                        <!-- Schichten -->
                                        <div class="schicht {{$schicht.status|default:"???"}}" id="schicht-{{$schicht.id|default:"???"}}" data-id="{{$schicht.id|default:"???"}}" data-status="{{$schicht.status|default:"???"}}" data-mitarbeiter-id="{{$schicht.mitarbeiter_id|default:""}}">
                                            <div class="text-center{{if ($schicht.zusatzschicht)}} bold{{/if}}"><span id="schicht-{{$schicht.id|default:"???"}}-von">{{$schicht.von|default:"???"}}</span> - <span id="schicht-{{$schicht.id|default:"???"}}-bis">{{$schicht.bis|default:"???"}}</span></div>

                                            <div class="float-left schicht-nachname" id="schicht-{{$schicht.id|default:"???"}}-nachname">{{$schicht.nachname|default:"???"}}</div>
                                            <div class="text-right schicht-pause" id="schicht-{{$schicht.id|default:"???"}}-pause">{{$schicht.pause|default:"&nbsp;"}}</div>

                                            <div class="float-left schicht-vorname" id="schicht-{{$schicht.id|default:"???"}}-vorname">{{$schicht.vorname|default:""}}</div>
                                            <div class="text-right schicht-personalnummer" id="schicht-{{$schicht.id|default:"???"}}-personalnummer">{{$schicht.personalnummer|default:"&nbsp;"}}</div>
                                        </div>
                                        <!-- /Schichten -->
                                    {{/foreach}}
                                </div>
                                <!-- /Wochentagsspalte -->
                            {{/foreach}}

                            {{if $smarty_vars.company == 'tps'}}
                                <!-- "Insgesamt"-Spalte -->
                                <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl px-1px overflow-x-auto">
                                    <!-- Überschrift -->
                                    <div class="wochentag wochentag-insgesamt text-white">
                                        <div class="wochentag-name wochentag-name-insgesamt text-right">
                                            <strong>Σ</strong> Insgesamt
                                        </div>
                                        <div>
                                            &nbsp;
                                        </div>
                                    </div>
                                    <!-- /Überschrift -->

                                    {{if $abteilung.palettenabteilung}}
                                        <!-- Palettenanzahl -->
                                        <div class="palettenanzahl palettenanzahl-insgesamt text-white has-tooltip font-weight-bold" data-toggle="tooltip" data-placement="top" title="Palettenanzahl" id="palettenanzahl-insgesamt-{{$kunde.id|default:"???"}}-{{$abteilung.id|default:"???"}}">
                                            {{$abteilung.paletten.insgesamt|default:0|number_format:2:",":"."}}
                                        </div>
                                        <!-- /Palettenanzahl -->
                                    {{/if}}

                                    <!-- Schichtstunden -->
                                    <div class="schichtstunden schichtstunden-insgesamt text-white has-tooltip font-weight-bold" data-toggle="tooltip" data-placement="top" title="Schichtstunden" id="schichtstunden-insgesamt-{{$kunde.id|default:"???"}}-{{$abteilung.id|default:"???"}}">
                                        {{$abteilung.stunden.insgesamt|default:"0"|number_format:2:",":"."}}
                                    </div>
                                    <!-- /Schichtstunden -->

                                    {{if $abteilung.palettenabteilung}}
                                        <!-- Produktivitätsfaktor -->
                                        <div class="produktivitaetsfaktor produktivitaetsfaktor-insgesamt text-white has-tooltip font-weight-bold" data-toggle="tooltip" data-placement="top" title="Produktivitätsfaktor" id="produktivitaetsfaktor-insgesamt-{{$kunde.id|default:"???"}}-{{$abteilung.id|default:"???"}}">
                                            {{$abteilung.produktivitaetsfaktor.insgesamt|default:0|number_format:2:",":"."}}
                                        </div>
                                        <!-- /Produktivitätsfaktor -->
                                    {{/if}}
                                </div>
                                <!-- /"Insgesamt"-Spalte -->
                            {{/if}}
                        </div>
                    </div>
                </div>
            </div>
        {{/foreach}}
    {{/foreach}}
{{/if}}

<div class="row mb-4 print-show">
                <div class="col">
                    <div class="content-box p-4 header-print">
                        <div class="col-12 col-xl">
                                <div class="row mx-0" >
                                        <div class="col-3 col-sm-6 col-md-4 col-lg-3 col-xl px-1px overflow-x-auto">

                                            <b>ANDROM Personalservice GmbH</b><br>
                                            Breitenbachstraße 10, 13509 Berlin<br>
                                            Tel: 030 34 34 90 90<br>
                                            Internet: www.ttact.de
                                        </div>

                                        <div class="col-6 col-sm-6 col-md-4 col-lg-3 col-xl px-1px overflow-x-auto" style="text-align: center !important;">
                                            <script>
                                                var options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                                                var today  = new Date();
                                                console.log(today.toLocaleDateString("de-DE")); // 9/17/2016
                                                console.log(today.toLocaleDateString("de-DE", options)); // Saturday, September 17, 2016
                                                console.log(today.toLocaleDateString("de-DE"));// not time
                                                document.write(today.toLocaleString("de-DE"));// with time
                                            </script>
                                        </div>

                                        <div class="col-3 col-sm-6 col-md-4 col-lg-3 col-xl px-1px overflow-x-auto">
                                            <p style="margin-bottom:30px">
                                                {{$kunde.name|default:"???"}}
                                            </p>
                                            ________________________<br>
                                            Unterschriftt
                                        </div>
                                    </div>

                        </div>
                    </div>
                </div>
            </div>

{{capture name='styles'}}
    <!-- Switchery -->
    <link href="/assets/vendors/switchery/dist/switchery.min.css" rel="stylesheet">

    <!-- Select2 -->
    <link href="/assets/vendors/select2/dist/css/select2.min.css" rel="stylesheet">
    <style>

        .select2-container .select2-selection--multiple {
            min-height: 100px;
        }

        @media (max-width: 1199px) {
            .dropdown-toggle::after {
                margin-left: 0;
            }

            .p-xs-down-1 {
                padding: .5rem .5rem;
            }

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

    <!-- Selectable -->
    <link rel="stylesheet" href="/assets/vendors/jquery-ui-1.12.1.custom/jquery-ui.min.css">
    <style>
        .ui-selecting {
            opacity: 0.3;
        }

        .ui-selected {
            opacity: 0.5;
        }
    </style>
    <!-- /Selectable -->

    <!-- Schichtplaner basics -->
    <style>
        .schichtstunden{{if $smarty_vars.company == 'tps'}}, .palettenanzahl, .produktivitaetsfaktor{{/if}} {
            padding: 5px;
            background: #ddd;
            margin-bottom: 2px;
            text-align: right;
        }

        {{if $smarty_vars.company == 'tps'}}
            .palettenanzahl-input {
                padding: 5px;
                line-height: 1.5;
                color: #292b2c;
                font-size: 0.9rem;
                text-align: right;
                background: none;
                border: 0;
                border-radius: 0;
                font-family: -apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif;
            }

            .palettenanzahl-input:hover, .palettenanzahl-input:focus {
                background: #ccc;
            }

            .palettenanzahl-error {
                background-color: #f8d7da;
            }

            .palettenanzahl-error .palettenanzahl-input {
                color: #721c24;
            }

            .palettenanzahl-error .palettenanzahl-input:hover, .palettenanzahl-error .palettenanzahl-input:focus {
                background: #f5c6cb;
            }

            .palettenanzahl-changed {
                background-color: #cce5ff;
            }

            .palettenanzahl-changed .palettenanzahl-input {
                color: #004085;
            }

            .palettenanzahl-changed .palettenanzahl-input:hover, .palettenanzahl-changed .palettenanzahl-input:focus {
                background: #b8daff;
            }

            .palettenanzahl-success {
                background-color: #d4edda;
            }

            .palettenanzahl-success .palettenanzahl-input {
                color: #155724;
            }

            .palettenanzahl-success .palettenanzahl-input:hover, .palettenanzahl-success .palettenanzahl-input:focus {
                background: #c3e6cb;
            }

            .palettenanzahl-insgesamt, .schichtstunden-insgesamt, .produktivitaetsfaktor-insgesamt {
                background: #505050;
            }
        {{/if}}

        .content-box > h4, .content-box > h5 {
            border-bottom: 1px solid #F0F0F0;
            margin-bottom: 15px;
        }

        .px-1px {
            padding-left: 1px !important;
            padding-right: 1px !important;
        }

        .schicht, .schicht > div, .wochentag, .wochentag > div, .kunde-title-info-symbol, .header-info, .schicht-menu, .schicht-menu * {
            white-space: nowrap;
            text-overflow: ellipsis;
            overflow: hidden;
        }

        .schicht-nachname, .schicht-vorname {
            width: calc(100% - 40px);
        }

        .schicht-pause, .schicht-personalnummer {
            width: 40px;    
        }

        .schicht-personalnummer {
            opacity: .5;
        }

        .schicht-menu-container-large > .schicht-menu {
            /*font-size: 0.9rem;*/
        }

        .schicht-menu {
            margin-bottom: 2px;
        }

        .schicht-menu-header > div {
            background: #333;
            padding: 10px;
            color: white;
            text-align: center;
        }

        .schicht-menu-header > div:hover {
            cursor: pointer;
            background: #404040;
        }

        .schicht-menu-header > div.active {
            background: #282828;
        }

        .schicht-menu-header > div.active:hover {
            cursor: unset;
            background: #282828;
        }

        .schicht-menu-mitarbeiter-header {
            background: #ccc;
        }

        .schicht-menu-mitarbeiter-header-menu > div {
            background: #B0B0B0;
        }

        .schicht-menu-mitarbeiter-header-menu > div:hover {
            background: #BEBEBE;
            cursor: pointer;
        }

        .schicht-menu-mitarbeiter-header-menu > div.active {
            background: #A8A8A8;
        }

        .schicht-menu-mitarbeiter-header-menu > div.active:hover {
            cursor: unset;
            background: #A8A8A8;
        }

        .schicht-menu-mitarbeiter-liste {
            height: 300px;
            overflow: auto;
        }

        .schicht-menu-mitarbeiter-liste > div {
            padding: 10px;
            background: #ddd;
            margin-top: 2px;
        }

        .schicht-menu-mitarbeiter-liste > div:hover {
            cursor: pointer;
            background: #E8E8E8;
        }

        .schicht-menu-mitarbeiter-liste > div.disabled {
            opacity: .65;
        }

        .schicht-menu-mitarbeiter-liste > div.disabled:hover {
            cursor: not-allowed;
            background: #ddd;
        }

        .schicht-menu-mitarbeiter-liste > div > div.mitarbeiter-name {
            width: calc(100% - 40px);
        }

        .schicht-menu-mitarbeiter-liste > div > div.mitarbeiter-personalnummer {
            width: 40px;
        }

        .schicht-menu-status > div, .schicht-menu-optionen > div {
            background: #eee;
        }

        .schicht-menu-status button.nicht_bestaetigt {
            background-color: #99CC00;
        }

        .schicht-menu-status button.kann_nicht {
            background-color: #990000;
            color: white;
        }

        .schicht-menu-status button.kann_andere_uhrzeit {
            background-color: #663300;
            color: white;
        }

        .schicht-menu-status button.btn-dark, .schicht-menu-optionen button.btn-dark {
            background-color: #777;
            color: white;
        }

        .schicht-menu-status button:hover, .schicht-menu-optionen button:hover {
            opacity: .8;
        }

        .schicht-menu-status button.disabled:hover, .schicht-menu-optionen button.disabled:hover {
            opacity: .65;
        }

        .wochentag {
            margin-bottom: 2px;
            background: #eee;
            padding: 10px;
        }

        {{if $smarty_vars.company == 'tps'}}
            .wochentag-insgesamt {
                background:#292B2C;
            }
        {{/if}}

        .wochentag .wochentag-name {
            width: calc(100% - 40px);
        }

        {{if $smarty_vars.company == 'tps'}}
            .wochentag .wochentag-name.wochentag-name-insgesamt {
                width: 100%;
            }
        {{/if}}

        .wochentag .wochentag-pluszeichen-container {
            width: 40px;
        }

        .wochentag .wochentag-pluszeichen:hover {
            cursor: pointer;
            color: #666;
        }

        .header-info {
            font-size: 0.8rem;
        }

        .overflow-x-auto {
            overflow-x: auto;
        }

        .kunde-title {
            background: #292B2C !important;
            color: white;
        }

        .kunde-title .kunde-title-bezeichnung {
            width: calc(100% - 45px);
        }

        .kunde-title .kunde-title-info-symbol {
            width: 45px;
        }

        .kunde-title .kunde-title-info-symbol span:hover {
            cursor: pointer;
            color: #ddd;
        }

        #header {
            z-index: 1030 !important;
        }

        #header-sticky-wrapper.is-sticky > #header.content-box {
            box-shadow: 0 0 10px black !important;
        }

        #header-sticky-wrapper.is-sticky > #header.content-box:hover {
            box-shadow: 0 0 15px black !important;
        }

        #kunde-gross, #abteilung-gross {
            white-space: nowrap;
            text-overflow: ellipsis;
            overflow: hidden;
        }

        #kunde-gross {
            max-width: 60%;
        }

        #abteilung-gross {
            max-width: 40%;
        }

        .abteilung-preis {
            font-size: 11px;
            padding-left: 5px;   
        }

        .header-button:hover {
            cursor: pointer;
            color: #ddd;
        }

        /*#nprogress .bar {
            visibility: hidden;
        }*/

        .schicht {
            margin-bottom: 2px;
            height: calc(4.05rem + 16px);
            padding: 8px;
        }

        .schicht:hover {
            cursor: pointer;
        }

        .abteilung-box {
            font-size: 0.9rem;
        }

        a.change-kw-button {
            color: white;
        }

        a.change-kw-button:hover {
            color: #ddd;
        }

        .kunde-menu {
            background: white;
        }

        .schicht-menu-status-benachrichtigen-mitarbeiterbox > div {
            padding: 10px;
            margin-bottom: 2px;
            background: #ddd;
        }

        .schicht-menu-status-benachrichtigen-mitarbeiterbox > div.float-left {
            width: calc(100% - 50px);
            white-space: nowrap;
            text-overflow: ellipsis;
        }

        .schicht-menu-status-benachrichtigen-mitarbeiterbox > div.text-right {
            width: 50px;
        }

        .schicht-menu-status-benachrichtigen-mitarbeiterbox > div a {
            color: black;
        }

        .schicht-menu-status-benachrichtigen-mitarbeiterbox > div a:hover {
            color: #444;
            cursor: pointer;
        }

        a.btn:hover, a.btn:link, a.btn:visited, a.btn:active {
            color: black;
        }

        i.hover:hover {
            cursor: pointer;
            color: #666;
        }

        .popover {
            min-width: 150px;
        }
    </style>
    <!-- /Schichtplaner basics -->

    <!-- Schichten styles -->
    <style>
        .schicht .bold {
            font-weight: bold;
        }

        .schicht.offen {
            background-color: #D9534F;
            color: white;
        }

        .schicht.offen:hover {
            background-color: #be4945;
        }

        .schicht.nicht_benachrichtigt {
            background-color: #0084FF;
        }

        .schicht.nicht_benachrichtigt:hover {
            background-color: #0074df;
            color: white;
        }

        .schicht.benachrichtigt {
            background-color: #17F80D;
        }

        .schicht.benachrichtigt:hover {
            background-color: #14d90b;
        }

        .schicht.nicht_bestaetigt {
            background-color: #99CC00;
        }

        .schicht.nicht_bestaetigt:hover {
            background-color: #86b300;
        }

        .schicht.kann_nicht {
            background-color: #990000;
            color: white;
        }

        .schicht.kann_nicht:hover {
            background-color: #860000;
        }

        .schicht.kann_andere_uhrzeit {
            background-color: #663300;
            color: white;
        }

        .schicht.kann_andere_uhrzeit:hover {
            background-color: #592d00;
        }

        .schicht.stundenzettel_bestaetigt {
            background-color: #006600;
            color: white;
        }

        .schicht.stundenzettel_bestaetigt:hover {
            background-color: #005900;
        }

        .schicht.archiviert {
            background-color: #333333;
            color: white;
        }

        .schicht.archiviert:hover {
            background-color: #2d2d2d;
        }
    </style>
    <!-- /Schichten styles -->

    <!-- Printing -->
    <style>
        .print-show {
            display: none;
        }
    
        @media print {
            .container {
                width: auto;
            }

            nav.navbar, .print-hide {
                display: none;
            }

            .schicht, .wochentag {
                background: white !important;
                color: black !important;
                border: 1px solid black !important;
            }

            .abteilung-box, .kunde-title, .header-print {
                border: 2px solid black !important;
                background: white !important;
                color: black !important;
            }

            body {
                background: white !important;
            }

            .print-show {
                display: block;
            }
        }
    </style>
    <!-- /Printing-->
{{/capture}}

{{$css=$smarty.capture.styles scope=parent}}

{{capture name='scripts'}}
    {{if $smarty_vars.aenderungen}}
        <!-- Mitarbeiterinfo -->
        <script>
            $(document).ready(function () {
                $(".schicht").contextmenu(function (e) {
                    e.preventDefault();
                    var id = $(this).data('id');

                    $.ajax({
                        type: "POST",
                        url: "/schichten/ajax",
                        data: {type: 'schnellinfo', id: id},
                        success: function ($return) {
                            if ($return.hasOwnProperty('status')) {
                                if ($return.status == "success") {
                                    $('#schicht-' + id).popover({
                                        html: true,
                                        title: '' +
                                            '<div class="row">' +
                                                '<div class="col">Mitarbeiterinfo</div>' +
                                                '<div class="col text-right"><a class="ml-auto" onclick="$(\'#schicht-' + id + '\').popover(\'hide\');"><i class="hover fa fa-times"></i></a></div>' +
                                            '</div>' +
                                        '',
                                        content: '' +
                                            '<div><strong>Personalnr.</strong></div>' +
                                            '<div>' + $return.personalnummer + '</div>' +
                                            '<div class="mt-2"><strong>Telefon</strong></div>' +
                                            '<div>' + $return.telefon + '</div>' +
                                            '<div class="mt-2"><strong>E-Mail</strong></div>' +
                                            '<div>' + $return.emailadresse + '</div>' +
                                            '<div class="mt-2"><strong>Vertr. Std. (Woche / Monat)</strong></div>' +
                                            '<div>' + $return.vertr_woche + ' / ' + $return.vertr_monat + '</div>' +
                                            '<div class="mt-2"><strong>Bislang gepl. (Woche / Monat)</strong></div>' +
                                            '<div>' + $return.gepl_woche + ' / ' + $return.gepl_monat + '</div>' +
                                        '',
                                        placement: 'bottom'
                                    });
                                    $('#schicht-' + id).popover('show');
                                } else if ($return.status == "not_logged_in") {
                                    location.reload();
                                } else {
                                    showError("Es ist ein Fehler aufgetreten.", 1);
                                }
                            } else {
                                showError("Es ist ein Fehler aufgetreten.", 2);
                            }
                        },
                        error: function () {
                            showError("Es ist ein Fehler aufgetreten.", 3);
                        }
                    });
                });
            });
        </script>
        <!-- /Mitarbeiterinfo -->
    {{/if}}

    <!-- Header menu: Filter -->
    <script>
        $(document).ready(function () {
            function woche_abschliessen(year, week, callback) {
                $.ajax({
                    type: "POST",
                    url: "/schichten/ajax",
                    data: {type: 'close', what: 'woche', year: year, week: week},
                    success: function ($return) {
                        if ($return.hasOwnProperty('status')) {
                            if ($return.status == "success") {
                                callback($return);
                            } else if ($return.status == "not_logged_in") {
                                location.reload();
                            } else {
                                showError("Es ist ein Fehler aufgetreten.", 1);
                                callback({});
                            } 
                        } else {
                            showError("Es ist ein Fehler aufgetreten.", 2);
                            callback({});
                        }
                    },
                    error: function () {
                        showError("Es ist ein Fehler aufgetreten.", 3);
                        callback({});
                    }
                });
            }

            function monat_abschliessen(year, week, callback) {
                $.ajax({
                    type: "POST",
                    url: "/schichten/ajax",
                    data: {type: 'close', what: 'monat', year: year, week: week},
                    success: function ($return) {
                        if ($return.hasOwnProperty('status')) {
                            if ($return.status == "success") {
                                callback($return);
                            } else if ($return.status == "not_logged_in") {
                                location.reload();
                            } else {
                                showError("Es ist ein Fehler aufgetreten.", 1);
                                callback({});
                            } 
                        } else {
                            showError("Es ist ein Fehler aufgetreten.", 2);
                            callback({});
                        }
                    },
                    error: function () {
                        showError("Es ist ein Fehler aufgetreten.", 3);
                        callback({});
                    }
                });
            }

            function woche_oeffnen(year, week, callback) {
                $.ajax({
                    type: "POST",
                    url: "/schichten/ajax",
                    data: {type: 'open', what: 'woche', year: year, week: week},
                    success: function ($return) {
                        if ($return.hasOwnProperty('status')) {
                            if ($return.status == "success") {
                                callback($return);
                            } else if ($return.status == "not_logged_in") {
                                location.reload();
                            } else {
                                showError("Es ist ein Fehler aufgetreten.", 1);
                                callback({});
                            } 
                        } else {
                            showError("Es ist ein Fehler aufgetreten.", 2);
                            callback({});
                        }
                    },
                    error: function () {
                        showError("Es ist ein Fehler aufgetreten.", 3);
                        callback({});
                    }
                });
            }

            function kunde_abschliessen(year, week, kunde, callback) {
                $.ajax({
                    type: "POST",
                    url: "/schichten/ajax",
                    data: {type: 'close', what: 'kunde', year: year, week: week, kunde: kunde},
                    success: function ($return) {
                        if ($return.hasOwnProperty('status')) {
                            if ($return.status == "success") {
                                callback($return);
                            } else if ($return.status == "not_logged_in") {
                                location.reload();
                            } else {
                                showError("Es ist ein Fehler aufgetreten.", 1);
                                callback({});
                            } 
                        } else {
                            showError("Es ist ein Fehler aufgetreten.", 2);
                            callback({});
                        }
                    },
                    error: function () {
                        showError("Es ist ein Fehler aufgetreten.", 3);
                        callback({});
                    }
                });
            }

            function monat_abschliessen_kunde(year, week, kunde, callback) {
                $.ajax({
                    type: "POST",
                    url: "/schichten/ajax",
                    data: {type: 'close', what: 'monat_kunde', year: year, week: week, kunde: kunde},
                    success: function ($return) {
                        if ($return.hasOwnProperty('status')) {
                            if ($return.status == "success") {
                                callback($return);
                            } else if ($return.status == "not_logged_in") {
                                location.reload();
                            } else {
                                showError("Es ist ein Fehler aufgetreten.", 1);
                                callback({});
                            } 
                        } else {
                            showError("Es ist ein Fehler aufgetreten.", 2);
                            callback({});
                        }
                    },
                    error: function () {
                        showError("Es ist ein Fehler aufgetreten.", 3);
                        callback({});
                    }
                });
            }

            function kunde_oeffnen(year, week, kunde, callback) {
                $.ajax({
                    type: "POST",
                    url: "/schichten/ajax",
                    data: {type: 'open', what: 'kunde', year: year, week: week, kunde: kunde},
                    success: function ($return) {
                        if ($return.hasOwnProperty('status')) {
                            if ($return.status == "success") {
                                callback($return);
                            } else if ($return.status == "not_logged_in") {
                                location.reload();
                            } else {
                                showError("Es ist ein Fehler aufgetreten.", 1);
                                callback({});
                            } 
                        } else {
                            showError("Es ist ein Fehler aufgetreten.", 2);
                            callback({});
                        }
                    },
                    error: function () {
                        showError("Es ist ein Fehler aufgetreten.", 3);
                        callback({});
                    }
                });
            }

            // Woche abschliessen
            $("#woche_abschliessen").click(function () {
                woche_abschliessen({{$smarty_vars.requested_year|default:"'???'"}}, {{$smarty_vars.requested_week|default:"'???'"}}, function () {});
            });

            // Monat abschliessen
            $("#monat_abschliessen").click(function () {
                monat_abschliessen({{$smarty_vars.requested_year|default:"'???'"}}, {{$smarty_vars.requested_week|default:"'???'"}}, function () {});
            });

            // Woche öffnen
            $("#woche_oeffnen").click(function () {
                woche_oeffnen({{$smarty_vars.requested_year|default:"'???'"}}, {{$smarty_vars.requested_week|default:"'???'"}}, function () {});
            });

            // Kunde abschliessen
            $(".kunde_abschliessen").click(function () {
                kunde_abschliessen({{$smarty_vars.requested_year|default:"'???'"}}, {{$smarty_vars.requested_week|default:"'???'"}}, $(this).data("kundeId"), function () {});
            });

            // Kunde abschliessen
            $(".monat_abschliessen").click(function () {
                monat_abschliessen_kunde({{$smarty_vars.requested_year|default:"'???'"}}, {{$smarty_vars.requested_week|default:"'???'"}}, $(this).data("kundeId"), function () {});
            });

            // Kunde öffnen
            $(".kunde_oeffnen").click(function () {
                kunde_oeffnen({{$smarty_vars.requested_year|default:"'???'"}}, {{$smarty_vars.requested_week|default:"'???'"}}, $(this).data("kundeId"), function () {});
            });

            // Abteilungen filtern
            $("#abteilungsfilter").change(function () {
                var selected = $("#abteilungsfilter option:selected");
                if (selected.length == 0) {
                    $(".abteilung").show();
                } else {
                    $(".abteilung").hide();
                    selected.each(function () {
                        $(".abteilung-" + $(this).val()).show();
                    });
                }
            });

            // Mitarbeiter filtern
            $("#mitarbeiterfilter").change(function () {
                filter_schichten();
            });

            // Schichten filtern
            $(".schichtfilter").change(function () {
                filter_schichten();
            });

            function filter_schichten() {
                var show_mitarbeiter = [];
                var show_status = [];
                $("#mitarbeiterfilter option:selected").each(function () {
                    show_mitarbeiter.push(parseInt($(this).val()));
                });
                $(".schichtfilter:checked").each(function () {
                    show_status.push($(this).data("status"));
                });
                
                if (show_status.length > 0) {
                    if (show_mitarbeiter.length > 0) {
                        // filter status + mitarbeiter
                        $(".schicht").each(function () {
                            if ($.inArray($(this).data("mitarbeiterId"), show_mitarbeiter) !== -1) {
                                if ($.inArray($(this).data("status"), show_status) !== -1) {
                                    $(this).show();
                                } else {
                                    $(this).hide();
                                }
                            } else {
                                $(this).hide();
                            }
                        });
                    } else {
                        // filter only status
                        $(".schicht").each(function () {
                            if ($.inArray($(this).data("status"), show_status) !== -1) {
                                $(this).show();
                            } else {
                                $(this).hide();
                            }
                        });
                    }
                } else {
                    $(".schicht").hide();
                }
            }

            // Alle Filter zurücksetzen
            $("#alle_filter_zuruecksetzen").click(function () {
                $("#abteilungsfilter").val("[]").change();
                $("#mitarbeiterfilter").val("[]").change();
                $(".schichtfilter:not(:checked)").click();
            });
        });
    </script>
    <!-- /Header menu: Filter -->

    <!-- Header menu: Schichtplaner öffnen -->
    <script>
        $kalenderwochen_are_being_loaded = false;

        $(document).ready(function () {
            // minimize DOM access by saving objects in vars
            $kalenderwoche = $("#kalenderwoche");
            $kunden = $("#kunden");
            $jahr = $("#jahr");
            $vorige_kw = $("#vorige_kw");
            $aktuelle_kw = $("#aktuelle_kw");
            $naechste_kw = $("#naechste_kw");
            $jahresauswahl = $("#jahresauswahl").find("a");
            $jahresauswahl_button = $("#jahresauswahl_button");

            function changeJahr(jahr, callback) {
                if ($jahr.val() != jahr) {
                    $.ajax({
                        type: "POST",
                        url: "/schichten/ajax",
                        data: {type: 'kalenderwochen', year: jahr},
                        success: function (data) {
                            if (data.hasOwnProperty('status')) {
                                if (data.status == "success") {
                                    if (data.hasOwnProperty('kalenderwochen')) {
                                        $kalenderwoche.select2("destroy");
                                        $kalenderwoche.html("");
                                        $.each(data.kalenderwochen, function (index, obj) {
                                            var option = $('<option/>');
                                            option.attr("value", obj.kw);
                                            option.text("KW " + obj.kw + " | " + obj.von + " - " + obj.bis);
                                            $kalenderwoche.append(option);
                                        });
                                        $kalenderwoche.select2({
                                            allowClear: false,
                                            language: {
                                                "noResults": function() {
                                                    return "Keine Ergebnisse gefunden.";
                                                }
                                            },
                                            width: "100%"
                                        });
                                        $jahr.val(jahr);
                                        $jahresauswahl_button.html(jahr);
                                        callback();
                                    } else {
                                        showError("Es ist ein Fehler aufgetreten.");
                                    }
                                } else if (data.status == "not_logged_in") {
                                    location.reload();
                                } else {
                                    showError("Es ist ein Fehler aufgetreten.");
                                } 
                            } else {
                                showError("Es ist ein Fehler aufgetreten.");
                            }
                        },
                        error: function () {
                            showError("Es ist ein Fehler aufgetreten.");
                        }
                    });
                } else {
                    callback();
                }
            }

            // Jahresauswahl button
            $jahresauswahl.click(function () {
                if ($jahr.val() != $(this).data("jahr")) {
                    changeJahr($(this).data("jahr"), function () {});
                }
            });

            // Aktuelle KW
            $aktuelle_kw.click(function () {
                changeJahr({{$smarty.now|date_format:"Y"|default:"-1"}}, function() {
                    $kalenderwoche.val({{$smarty.now|date_format:"W"|default:"-1"}}).change();
                });
            });

            // Vorige KW
            $vorige_kw.click(function () {
                changeJahr({{$smarty_vars.prev.year|default:"-1"}}, function() {
                    $kalenderwoche.val({{$smarty_vars.prev.week|default:"-1"}}).change();
                });
            });

            // Nächste KW
            $naechste_kw.click(function () {
                changeJahr({{$smarty_vars.next.year|default:"-1"}}, function() {
                    $kalenderwoche.val({{$smarty_vars.next.week|default:"-1"}}).change();
                });
            });
        });
    </script>
    <!-- /Header menu: Schichtplaner öffnen -->

    <!-- Tooltip -->
    <script>
        $('.has-tooltip').tooltip();
    </script>
    <!-- Tooltip -->

    <!-- Switchery -->
    <script src="/assets/vendors/switchery/dist/switchery.min.js"></script>
    <script>
        $(document).ready(function() {
            if ($(".js-switch")[0]) {
                var elems = Array.prototype.slice.call(document.querySelectorAll(".js-switch"));
                elems.forEach(function (html) {
                    var switchery = new Switchery(html, {
                        color: "#26B99A",
                        size: "small"
                    });
                });
            }
        });
    </script>
    <!-- /Switchery -->

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

    <!-- Variables -->
    <script>
        $(document).ready(function () {
            $header = $("#header");

            $header_button = $(".header-button");
            $header_menu = $("#header-menu");
        
            $header_col = $("#header-col");

            $header_non_sticky = $("#header-non-sticky");
            $header_sticky = $("#header-sticky");

            $abteilung_gross = $("#abteilung-gross");
            $abteilung_klein = $("#abteilung-klein");

            $kunde_gross = $("#kunde-gross");
            $kunde_klein = $("#kunde-klein");

            $abteilungen = $(".abteilung-box");
            $kunden = $(".kunde-title");
        });
    </script>
    <!-- / Variables-->

    <!-- Sticky -->
    <script src="/assets/vendors/garand-sticky-73b0fbe/jquery.sticky.js"></script>
    <script>
        $(document).ready(function(){
            $header.sticky();

            $(window).resize(function () {
                if ($header_menu.css('display') == 'none') {
                    $header.sticky('update');
                }
            });

            $header.on('sticky-start', function () {
                $header_sticky.show();
                $header_non_sticky.hide();
            });
            $header.on('sticky-end', function () {
                $header_sticky.hide();
                $header_non_sticky.show();
            });

            $header_button.click(function () {
                if ($header_menu.css('display') == 'none') {
                    $header.unstick();
                    $header_menu.show();
                } else {
                    $header_menu.hide();
                    $header_col.css('height', 'unset');
                    $header.sticky();
                }
            });
        });
    </script>
    <!-- / Sticky-->

    <!-- ScrollSpy -->
    <script src="/assets/vendors/jquery-scrollspy-master/jquery-scrollspy.min.js"></script>
    <script>
        $(document).ready(function () {
            function initScrollspyKunden(index) {
                var $max = 0;
                if (index + 1 in $kunden) {
                    $max = $kunden.eq(index + 1).offset().top - $header.height();
                } else {
                    $max = $(document).height();
                }
                $kunden.eq(index).scrollspy({
                    min: $kunden.eq(index).offset().top - $header.height(),
                    max: $max,
                    onEnter: function(element, position) {
                        $kunde_gross.html($kunden.eq(index).data("kunde"));
                        $kunde_klein.html($kunden.eq(index).data("kunde"));
                        $abteilung_gross.html("&nbsp;");
                        $abteilung_klein.html("&nbsp;");
                    }
                });
            }

            function initScrollspyAbteilungen(index) {
                var $max = 0;
                if (index + 1 in $abteilungen) {
                    $max = $abteilungen.eq(index).offset().top + $abteilungen.eq(index).height() - $header.height();
                } else {
                    $max = $(document).height();
                }
                $abteilungen.eq(index).scrollspy({
                    min: $abteilungen.eq(index).offset().top - $header.height(),
                    max: $max,
                    onEnter: function(element, position) {
                        $abteilung_gross.html($abteilungen.eq(index).data("abteilung"));
                        $abteilung_klein.html($abteilungen.eq(index).data("abteilung"));
                    }
                });
            }

            $kunden.each(function (index) {
                initScrollspyKunden(index);
            });

            $abteilungen.each(function (index) {
                initScrollspyAbteilungen(index);
            });

            $(window).resize(function () {
                $abteilung_gross.html("&nbsp;");
                $abteilung_klein.html("&nbsp;");
                $kunde_gross.html("&nbsp;");
                $kunde_klein.html("&nbsp;");

                $kunden.each(function (index) {
                    $kunden.eq(index).scrollspy({}, 'destroy');
                });
                $abteilungen.each(function (index) {
                    $abteilungen.eq(index).scrollspy({}, 'destroy');
                });
                $kunden.each(function (index) {
                    initScrollspyKunden(index);
                });
                $abteilungen.each(function (index) {
                    initScrollspyAbteilungen(index);
                });
            });
        });
    </script>
    <!-- / ScrollSpy-->

    <!-- jquery.inputmask -->
    <script src="/assets/vendors/jquery.inputmask/dist/min/jquery.inputmask.bundle.min.js"></script>

    <!-- Selectable -->
    <script src="/assets/vendors/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
    <script>
        function set(schichten, field, value, callback) {
            $.ajax({
                type: "POST",
                url: "/schichten/ajax",
                data: {type: 'set', schichten: schichten, field: field, value: value},
                success: function ($return) {
                    if ($return.hasOwnProperty('status')) {
                        if ($return.status == "success") {
                            callback($return);
                        } else if ($return.status == "not_logged_in") {
                            location.reload();
                        } else {
                            showError("Es ist ein Fehler aufgetreten.", 1);
                            callback({});
                        } 
                    } else {
                        showError("Es ist ein Fehler aufgetreten.", 2);
                        callback({});
                    }
                },
                error: function () {
                    showError("Es ist ein Fehler aufgetreten.", 3);
                    callback({});
                }
            });
        }

        function setBenachrichtigt(schichten, mode, callback) {
            $.ajax({
                type: "POST",
                url: "/schichten/ajax",
                data: {type: 'set', schichten: schichten, field: 'status', value: 'benachrichtigt', mode: mode},
                success: function ($return) {
                    if ($return.hasOwnProperty('status')) {
                        if ($return.status == "success") {
                            callback($return);
                        } else if ($return.status == "not_logged_in") {
                            location.reload();
                        } else {
                            showError("Es ist ein Fehler aufgetreten.", 1);
                            callback({});
                        } 
                    } else {
                        showError("Es ist ein Fehler aufgetreten.", 2);
                        callback({});
                    }
                },
                error: function () {
                    showError("Es ist ein Fehler aufgetreten.", 3);
                    callback({});
                }
            });
        }

        function setZeit(schichten, von, bis, callback) {
            $.ajax({
                type: "POST",
                url: "/schichten/ajax",
                data: {type: 'set', schichten: schichten, field: 'zeit', value_von: von, value_bis: bis},
                success: function ($return) {
                    if ($return.hasOwnProperty('status')) {
                        if ($return.status == "success") {
                            callback($return);
                        } else if ($return.status == "not_logged_in") {
                            location.reload();
                        } else {
                            showError("Es ist ein Fehler aufgetreten.", 1);
                            callback({});
                        } 
                    } else {
                        showError("Es ist ein Fehler aufgetreten.", 2);
                        callback({});
                    }
                },
                error: function () {
                    showError("Es ist ein Fehler aufgetreten.", 3);
                    callback({});
                }
            });
        }

        function del(schichten, callback) {
            $.ajax({
                type: "POST",
                url: "/schichten/ajax",
                data: {type: 'delete', schichten: schichten},
                success: function ($return) {
                    if ($return.hasOwnProperty('status')) {
                        if ($return.status == "success") {
                            callback($return);
                        } else if ($return.status == "not_logged_in") {
                            location.reload();
                        } else {
                            showError("Es ist ein Fehler aufgetreten.", 1);
                            callback({});
                        } 
                    } else {
                        showError("Es ist ein Fehler aufgetreten.", 2);
                        callback({});
                    }
                },
                error: function () {
                    showError("Es ist ein Fehler aufgetreten.", 3);
                    callback({});
                }
            });
        }

        function add(year, kw, day, kunde, abteilung, callback) {
            $.ajax({
                type: "POST",
                url: "/schichten/ajax",
                data: {type: 'add', year: year, kw: kw, day: day, kunde: kunde, abteilung: abteilung},
                success: function ($return) {
                    if ($return.hasOwnProperty('status')) {
                        if ($return.status == "success") {
                            callback($return);
                        } else if ($return.status == "not_logged_in") {
                            location.reload();
                        } else {
                            showError("Es ist ein Fehler aufgetreten.", 1);
                            callback({});
                        } 
                    } else {
                        showError("Es ist ein Fehler aufgetreten.", 2);
                        callback({});
                    }
                },
                error: function () {
                    showError("Es ist ein Fehler aufgetreten.", 3);
                    callback({});
                }
            });
        }

        function get(data, config, callback) {
            var options = {};
            options.type = 'get';
            if (data == 'mitarbeiter') {
                if (config.hasOwnProperty('schichten')) {
                    options.data = 'mitarbeiter';
                    options.schichten = config.schichten;
                }
            } else if (data == 'schichten') {
                if (config.hasOwnProperty('last_update') && config.hasOwnProperty('year') && config.hasOwnProperty('week') && config.hasOwnProperty('kunden')) {
                    options.data = 'schichten';
                    options.year = config.year;
                    options.week = config.week;
                    options.kunden = config.kunden;
                }
            } else if (data == 'benachrichtigungen') {
                if (config.hasOwnProperty('schichten') && config.hasOwnProperty('mode')) {
                    options.data = 'benachrichtigungen';
                    options.schichten = config.schichten;
                    options.mode = config.mode;
                }
            }

            $.ajax({
                type: "POST",
                url: "/schichten/ajax",
                data: options,
                success: function ($return) {
                    if ($return.hasOwnProperty('status')) {
                        if ($return.status == "success") {
                            callback($return);
                        } else if ($return.status == "not_logged_in") {
                            location.reload();
                        } else {
                            showError("Es ist ein Fehler aufgetreten.", 4);
                            callback({});
                        } 
                    } else {
                        showError("Es ist ein Fehler aufgetreten.", 5);
                        callback({});
                    }
                },
                error: function () {
                    showError("Es ist ein Fehler aufgetreten.", 6);
                    callback({});
                }
            });
        }

        function close_schicht_menu (kunde_id, abteilung_id) {
            $("#schicht-menu-container-large-" + kunde_id + "-" + abteilung_id).html("<h4>Bearbeiten</h4>-- bitte eine/mehrere Schicht(en) auswählen");

            var schicht_menu = $("#schicht-menu-" + kunde_id + "-" + abteilung_id);
                if (schicht_menu.length > 0) {
                    schicht_menu.remove();
                }

            var ui_selected = $("#abteilung-box-" + kunde_id + "-" + abteilung_id + " .ui-selected");
                ui_selected.removeClass("ui-selected");
        }

        $(document).ready(function () {
            {{if $smarty_vars.aenderungen}}
                $('.abteilung-box').selectable({
                    filter: ".schicht",
                    cancel: ".schicht-menu,.wochentag-pluszeichen,input",
                    start: function () {
                        $(':focus').blur();
                    },
                    stop: function () {
                        var kunde_id = $(this).data('kundeId');
                        var abteilung_id = $(this).data('abteilungId');

                        var schicht_menu_test = $("#schicht-menu-" + kunde_id + "-" + abteilung_id);

                        if (schicht_menu_test.length > 0) {
                            schicht_menu_test.remove();
                        }

                        var selection = [];
                        var selection_has_offen = false;
                        var selection_has_nicht_benachrichtigt = false;
                        var selection_has_benachrichtigt = false;
                        var selection_has_nicht_bestaetigt = false;
                        var selection_has_kann_nicht = false;
                        var selection_has_kann_andere_uhrzeit = false;
                        var selection_has_stundenzettel_bestaetigt = false;
                        var selection_has_archiviert = false;
                        $(".ui-selected", this).each(function () {
                            selection.push($(this).data('id'));
                            switch ($(this).data('status')) {
                                case "offen":
                                    selection_has_offen = true;
                                    break;
                                case "nicht_benachrichtigt":
                                    selection_has_nicht_benachrichtigt = true;
                                    break;
                                case "benachrichtigt":
                                    selection_has_benachrichtigt = true;
                                    break;
                                case "nicht_bestaetigt":
                                    selection_has_nicht_bestaetigt = true;
                                    break;
                                case "kann_nicht":
                                    selection_has_kann_nicht = true;
                                    break;
                                case "kann_andere_uhrzeit":
                                    selection_has_kann_andere_uhrzeit = true;
                                    break;
                                case "stundenzettel_bestaetigt":
                                    selection_has_stundenzettel_bestaetigt = true;
                                    break;
                                case "archiviert":
                                    selection_has_archiviert = true;
                                    break;
                            }
                        });

                        var schicht_menu_container_large = $("#schicht-menu-container-large-" + kunde_id + "-" + abteilung_id);

                        if (selection.length > 0) {
                            var schicht_menu = $("<div/>", {class: "schicht-menu container-fluid", id: "schicht-menu-" + kunde_id + "-" + abteilung_id});
                                schicht_menu.attr("data-schicht-id", $(selection).last()[0]);
                                schicht_menu.attr("data-kunde-id", kunde_id);
                                schicht_menu.attr("data-abteilung-id", abteilung_id);

                                var schicht_menu_header = $("<div/>", {class: "schicht-menu-header row"});
                                    var schicht_menu_header_mitarbeiter = $("<div/>", {class: "col active schicht-menu-header-mitarbeiter"});
                                        schicht_menu_header_mitarbeiter.html("Mitarbeiter");
                                        $(schicht_menu_header_mitarbeiter).click(function () {
                                            $("#schicht-menu-" + kunde_id + "-" + abteilung_id + " > .schicht-menu-mitarbeiter").show();
                                            $("#schicht-menu-" + kunde_id + "-" + abteilung_id + " .schicht-menu-header-mitarbeiter").addClass("active");
                                            $("#schicht-menu-" + kunde_id + "-" + abteilung_id + " > .schicht-menu-optionen").hide();
                                            $("#schicht-menu-" + kunde_id + "-" + abteilung_id + " .schicht-menu-header-optionen").removeClass("active");
                                            $("#schicht-menu-" + kunde_id + "-" + abteilung_id + " > .schicht-menu-status").hide();
                                            $("#schicht-menu-" + kunde_id + "-" + abteilung_id + " .schicht-menu-header-status").removeClass("active");
                                        });
                                        schicht_menu_header.append(schicht_menu_header_mitarbeiter);

                                    var schicht_menu_header_status = $("<div/>", {class: "col schicht-menu-header-status"});
                                        schicht_menu_header_status.html("Status");
                                        $(schicht_menu_header_status).click(function () {
                                            $("#schicht-menu-" + kunde_id + "-" + abteilung_id + " > .schicht-menu-status").show();
                                            $("#schicht-menu-" + kunde_id + "-" + abteilung_id + " .schicht-menu-header-status").addClass("active");
                                            $("#schicht-menu-" + kunde_id + "-" + abteilung_id + " > .schicht-menu-mitarbeiter").hide();
                                            $("#schicht-menu-" + kunde_id + "-" + abteilung_id + " .schicht-menu-header-mitarbeiter").removeClass("active");
                                            $("#schicht-menu-" + kunde_id + "-" + abteilung_id + " > .schicht-menu-optionen").hide();
                                            $("#schicht-menu-" + kunde_id + "-" + abteilung_id + " .schicht-menu-header-optionen").removeClass("active");
                                        });
                                        schicht_menu_header.append(schicht_menu_header_status);

                                    var schicht_menu_header_optionen = $("<div/>", {class: "col schicht-menu-header-optionen"});
                                        schicht_menu_header_optionen.html("Optionen");
                                        $(schicht_menu_header_optionen).click(function () {
                                            $("#schicht-menu-" + kunde_id + "-" + abteilung_id + " > .schicht-menu-mitarbeiter").hide();
                                            $("#schicht-menu-" + kunde_id + "-" + abteilung_id + " .schicht-menu-header-mitarbeiter").removeClass("active");
                                            $("#schicht-menu-" + kunde_id + "-" + abteilung_id + " > .schicht-menu-status").hide();
                                            $("#schicht-menu-" + kunde_id + "-" + abteilung_id + " .schicht-menu-header-status").removeClass("active");
                                            $("#schicht-menu-" + kunde_id + "-" + abteilung_id + " > .schicht-menu-optionen").show();
                                            $("#schicht-menu-" + kunde_id + "-" + abteilung_id + " .schicht-menu-header-optionen").addClass("active");
                                        });
                                        schicht_menu_header.append(schicht_menu_header_optionen);

                                    schicht_menu.append(schicht_menu_header);
                                //--end schicht_menu_header

                                var schicht_menu_mitarbeiter = $("<div/>", {class: "schicht-menu-mitarbeiter row"});
                                    var schicht_menu_mitarbeiter_header = $("<div/>", {class: "schicht-menu-mitarbeiter-header col-12"});
                                        var schicht_menu_mitarbeiter_header_menu = $("<div/>", {class: "schicht-menu-mitarbeiter-header-menu row"});
                                            var schicht_menu_mitarbeiter_header_menu_stamm = $("<div/>", {class: "col text-center active py-2"});
                                                schicht_menu_mitarbeiter_header_menu_stamm.html("Stamm");
                                                schicht_menu_mitarbeiter_header_menu.append(schicht_menu_mitarbeiter_header_menu_stamm);

                                            var schicht_menu_mitarbeiter_header_menu_springer = $("<div/>", {class: "col text-center py-2"});
                                                schicht_menu_mitarbeiter_header_menu_springer.html("Springer");
                                                schicht_menu_mitarbeiter_header_menu.append(schicht_menu_mitarbeiter_header_menu_springer);

                                            var schicht_menu_mitarbeiter_header_menu_sonstige = $("<div/>", {class: "col text-center py-2"});
                                                schicht_menu_mitarbeiter_header_menu_sonstige.html("Sonstige");
                                                schicht_menu_mitarbeiter_header_menu.append(schicht_menu_mitarbeiter_header_menu_sonstige);

                                            schicht_menu_mitarbeiter_header.append(schicht_menu_mitarbeiter_header_menu);

                                         var schicht_menu_mitarbeiter_header_form = $("<div/>", { class: "form-group mb-0 py-2" });
                                            var schicht_menu_mitarbeiter_header_form_inputgroup = $("<div/>", { class: "input-group" });
                                                var schicht_menu_mitarbeiter_header_form_inputgroup_input = $("<input/>", { type: "text", class: "form-control", placeholder: "Mustermann, Max"});
                                                    schicht_menu_mitarbeiter_header_form_inputgroup_input.on('input', function () {
                                                        var haystack = $("#schicht-menu-" + kunde_id + "-" + abteilung_id + " .schicht-menu-mitarbeiter-liste > div");
                                                        var needle = this.value.toUpperCase();
                                                        if (needle == '') {
                                                            $(haystack).show();
                                                        } else {
                                                            $(haystack).each(function () {
                                                                if ($(this).data("searchtext").toUpperCase().indexOf(needle) !== -1) {
                                                                    $(this).show();
                                                                } else {
                                                                    $(this).hide();
                                                                }
                                                            });
                                                        }
                                                    });
                                                    schicht_menu_mitarbeiter_header_form_inputgroup.append(schicht_menu_mitarbeiter_header_form_inputgroup_input);
                                                var schicht_menu_mitarbeiter_header_form_inputgroup_span = $("<span/>", { class: "input-group-addon" });
                                                    schicht_menu_mitarbeiter_header_form_inputgroup_span.html('<i class="fa fa-fw fa-search"></i>');
                                                    schicht_menu_mitarbeiter_header_form_inputgroup.append(schicht_menu_mitarbeiter_header_form_inputgroup_span);
                                                schicht_menu_mitarbeiter_header_form.append(schicht_menu_mitarbeiter_header_form_inputgroup);
                                            schicht_menu_mitarbeiter_header.append(schicht_menu_mitarbeiter_header_form);
                                    schicht_menu_mitarbeiter.append(schicht_menu_mitarbeiter_header);

                                    var schicht_menu_mitarbeiter_liste = $("<div/>", {class: "schicht-menu-mitarbeiter-liste col-12 p-0"});
                                        var kein_mitarbeiter = $("<div/>");
                                            kein_mitarbeiter.attr("data-searchtext", "");
                                            kein_mitarbeiter.html(
                                                '<div class="float-left mitarbeiter-name">' +
                                                    '-- keinen Mitarbeiter auswählen' +
                                                '</div>'
                                            );
                                            if (selection_has_archiviert) {
                                                kein_mitarbeiter.addClass("disabled");
                                            } else {
                                                kein_mitarbeiter.click(function () {
                                                    set(selection, 'mitarbeiter', '', function ($result) {
                                                        if ($result.status == "success") {
                                                            close_schicht_menu(kunde_id, abteilung_id);
                                                        }
                                                    });
                                                });
                                            }
                                            schicht_menu_mitarbeiter_liste.append(kein_mitarbeiter);

                                        get('mitarbeiter', {schichten: selection}, function ($result) {
                                            if ($result.hasOwnProperty('mitarbeiter')) {
                                                if ($result.mitarbeiter.length > 0) {
                                                    $.each($result.mitarbeiter, function (index, row) {
                                                        var disabled = false;
                                                        if (selection_has_archiviert) {
                                                            disabled = true;
                                                        }

                                                        var mitarbeiter = row.data;
                                                        var div = $("<div/>");
                                                            div.attr("data-searchtext", mitarbeiter.nachname + ", " + mitarbeiter.vorname + " " + mitarbeiter.personalnummer);
                                                            div.html(
                                                                '<div class="float-left mitarbeiter-name">' +
                                                                    '<strong>' + mitarbeiter.nachname + "</strong>, " + mitarbeiter.vorname +
                                                                '</div>' +
                                                                '<div class="text-right mitarbeiter-personalnummer">' +
                                                                    mitarbeiter.personalnummer +
                                                                '</div>'
                                                            );
                                                            if (row.sperre || row.zeitliche_ueberschneidung || !row.abteilungsfreigabe) {
                                                                disabled = true;
                                                            }
                                                            var div_info = $("<div/>");
                                                                div_info.css("display", "none");
                                                                div_info.html(
                                                                    'Stamm: ' + (row.stamm ? 'ja' : 'nein') + '<br>' +
                                                                    'Springer: ' + (row.springer ? 'ja' : 'nein') + '<br>' +
                                                                    'Sperre: ' + (row.sperre ? 'ja' : 'nein') + '<br>' +
                                                                    'Zeitliche Überschneidung: ' + (row.zeitliche_ueberschneidung ? 'ja' : 'nein') + '<br>' +
                                                                    'Abteilungsfreigabe: ' + (row.abteilungsfreigabe ? 'ja' : 'nein') + '<br>' +
                                                                    'Innerhalb d. Arbeitszeiten: ' + (row.innerhalb_arbeitszeiten ? 'ja' : 'nein')
                                                                );
                                                                div.contextmenu(function (event) {
                                                                    event.preventDefault();
                                                                    div_info.toggle();
                                                                });
                                                                div.append(div_info);

                                                            if (disabled) {
                                                                div.addClass("disabled");
                                                            } else {
                                                                div.click(function () {
                                                                    set(selection, 'mitarbeiter', mitarbeiter.id, function ($result) {
                                                                        if ($result.status == "success") {
                                                                            close_schicht_menu(kunde_id, abteilung_id);
                                                                        }
                                                                    });
                                                                });
                                                            }
                                                            schicht_menu_mitarbeiter_liste.append(div);
                                                    });
                                                } else {
                                                    // es stehen keine Mitarbeiter zur Verfügung.
                                                }
                                            } else {
                                                showError("Es ist ein Fehler aufgetreten.", 7);
                                            }
                                        });

                                        schicht_menu_mitarbeiter_liste.html();
                                        schicht_menu_mitarbeiter.append(schicht_menu_mitarbeiter_liste);

                                    schicht_menu.append(schicht_menu_mitarbeiter);
                                //--end schicht_menu_mitarbeiter

                                var schicht_menu_status = $("<div/>", {class: "schicht-menu-status row", style: "display: none;"});
                                    var schicht_menu_status_content = $("<div/>", {class: "col-12 py-3"});
                                        var schicht_menu_status_content_h5_benachrichtigen = $("<h5/>");
                                            schicht_menu_status_content_h5_benachrichtigen.html("Benachrichtigen");
                                            schicht_menu_status_content.append(schicht_menu_status_content_h5_benachrichtigen);

                                            var schicht_menu_status_content_benachrichtigen_button1 = $("<button/>", {class: "btn btn-secondary btn-block"});
                                                schicht_menu_status_content_benachrichtigen_button1.html("Markierte Schichten");
                                                if (selection_has_offen || selection_has_archiviert) {
                                                    schicht_menu_status_content_benachrichtigen_button1.addClass("disabled");
                                                } else {
                                                    schicht_menu_status_content_benachrichtigen_button1.click(function () {
                                                        get('benachrichtigungen', {schichten: selection, mode: 'markierte'}, function ($return) {
                                                            schicht_menu_status_content_benachrichtigen_button1.off().addClass("disabled");
                                                            schicht_menu_status_content_benachrichtigen_button2.off().addClass("disabled");
                                                            schicht_menu_status_content_benachrichtigen_button3.off().addClass("disabled");

                                                            var container = $("<div/>", {class: "py-3 schicht-menu-status-benachrichtigen-mitarbeiterbox"});
                                                                $.each($return.mitarbeiter, function (index, value) {
                                                                    var div = $("<div/>");
                                                                        div.html('<div class="float-left">' + value.nachname + ", " + value.vorname + ' (' + value.personalnummer + ')</div><div class="text-right"><a href="' + value.email + '"><i class="fa fa-envelope"></i></a> <a href="' + value.whatsapp + '"><i class="fa fa-whatsapp"></i></a></div>');
                                                                        container.append(div);
                                                                });
                                                                var button = $("<button/>", {class: "btn btn-primary btn-block mt-2"});
                                                                    button.html("Status ändern");
                                                                    button.click(function () {
                                                                        setBenachrichtigt(selection, 'markierte', function ($return) {
                                                                            if ($return.status == "success") {
                                                                                close_schicht_menu(kunde_id, abteilung_id);
                                                                            }
                                                                        });
                                                                    });
                                                                    container.append(button);
                                                                schicht_menu_status_content_benachrichtigen_button1.after(container);
                                                        });
                                                    });
                                                }
                                                schicht_menu_status_content.append(schicht_menu_status_content_benachrichtigen_button1);

                                            var schicht_menu_status_content_benachrichtigen_button2 = $("<button/>", {class: "btn btn-secondary btn-block"});
                                                schicht_menu_status_content_benachrichtigen_button2.html("Alle von diesem Kunden");
                                                if (selection_has_offen || selection_has_archiviert) {
                                                    schicht_menu_status_content_benachrichtigen_button2.addClass("disabled");
                                                } else {
                                                    schicht_menu_status_content_benachrichtigen_button2.click(function () {
                                                        get('benachrichtigungen', {schichten: selection, mode: 'alle_kunde'}, function ($return) {
                                                            schicht_menu_status_content_benachrichtigen_button1.off().addClass("disabled");
                                                            schicht_menu_status_content_benachrichtigen_button2.off().addClass("disabled");
                                                            schicht_menu_status_content_benachrichtigen_button3.off().addClass("disabled");

                                                            var container = $("<div/>", {class: "py-3 schicht-menu-status-benachrichtigen-mitarbeiterbox"});
                                                                $.each($return.mitarbeiter, function (index, value) {
                                                                    var div = $("<div/>");
                                                                        div.html('<div class="float-left">' + value.nachname + ", " + value.vorname + ' (' + value.personalnummer + ')</div><div class="text-right"><a href="' + value.email + '"><i class="fa fa-envelope"></i></a> <a href="' + value.whatsapp + '"><i class="fa fa-whatsapp"></i></a></div>');
                                                                        container.append(div);
                                                                });
                                                                var button = $("<button/>", {class: "btn btn-primary btn-block mt-2"});
                                                                    button.html("Status ändern");
                                                                    button.click(function () {
                                                                        setBenachrichtigt(selection, 'alle_kunde', function ($return) {
                                                                            if ($return.status == "success") {
                                                                                close_schicht_menu(kunde_id, abteilung_id);
                                                                            }
                                                                        });
                                                                    });
                                                                    container.append(button);
                                                                schicht_menu_status_content_benachrichtigen_button2.after(container);
                                                        });
                                                    });
                                                }
                                                schicht_menu_status_content.append(schicht_menu_status_content_benachrichtigen_button2);

                                            var schicht_menu_status_content_benachrichtigen_button3 = $("<button/>", {class: "btn btn-secondary btn-block"});
                                                schicht_menu_status_content_benachrichtigen_button3.html("Alle für diese KW");
                                                if (selection_has_offen || selection_has_archiviert) {
                                                    schicht_menu_status_content_benachrichtigen_button3.addClass("disabled");
                                                } else {
                                                    schicht_menu_status_content_benachrichtigen_button3.click(function () {
                                                        get('benachrichtigungen', {schichten: selection, mode: 'alle_kw'}, function ($return) {
                                                            schicht_menu_status_content_benachrichtigen_button1.off().addClass("disabled");
                                                            schicht_menu_status_content_benachrichtigen_button2.off().addClass("disabled");
                                                            schicht_menu_status_content_benachrichtigen_button3.off().addClass("disabled");

                                                            var container = $("<div/>", {class: "py-3 schicht-menu-status-benachrichtigen-mitarbeiterbox"});
                                                                $.each($return.mitarbeiter, function (index, value) {
                                                                    var div = $("<div/>");
                                                                        div.html('<div class="float-left">' + value.nachname + ", " + value.vorname + ' (' + value.personalnummer + ')</div><div class="text-right"><a href="' + value.email + '"><i class="fa fa-envelope"></i></a> <a href="' + value.whatsapp + '" target="_blank"><i class="fa fa-whatsapp"></i></a></div>');
                                                                        container.append(div);
                                                                });
                                                                var button = $("<button/>", {class: "btn btn-primary btn-block mt-2"});
                                                                    button.html("Status ändern");
                                                                    button.click(function () {
                                                                        setBenachrichtigt(selection, 'alle_kw', function ($return) {
                                                                            if ($return.status == "success") {
                                                                                close_schicht_menu(kunde_id, abteilung_id);
                                                                            }
                                                                        });
                                                                    });
                                                                    container.append(button);
                                                                schicht_menu_status_content_benachrichtigen_button3.after(container);
                                                        });
                                                    });
                                                }
                                                schicht_menu_status_content.append(schicht_menu_status_content_benachrichtigen_button3);

                                        var schicht_menu_status_content_h5_status = $("<h5/>", {class: "mt-4"});
                                            schicht_menu_status_content_h5_status.html("Status ändern");
                                            schicht_menu_status_content.append(schicht_menu_status_content_h5_status);

                                            var schicht_menu_status_content_status_button1 = $("<button/>", {class: "btn nicht_bestaetigt btn-block"});
                                                schicht_menu_status_content_status_button1.html("Wurde umgeplant");
                                                if (selection_has_offen || selection_has_archiviert) {
                                                    schicht_menu_status_content_status_button1.addClass("disabled");
                                                } else {
                                                    schicht_menu_status_content_status_button1.click(function () {
                                                        set(selection, 'status', 'nicht_bestaetigt', function ($result) {
                                                            if ($result.status == "success") {
                                                                close_schicht_menu(kunde_id, abteilung_id);
                                                            }
                                                        });
                                                    });
                                                }
                                                schicht_menu_status_content.append(schicht_menu_status_content_status_button1);

                                            var schicht_menu_status_content_status_button2 = $("<button/>", {class: "btn kann_nicht btn-block"});
                                                schicht_menu_status_content_status_button2.html("Kann überhaupt nicht");
                                                if (selection_has_offen || selection_has_archiviert) {
                                                    schicht_menu_status_content_status_button2.addClass("disabled");
                                                } else {
                                                    schicht_menu_status_content_status_button2.click(function () {
                                                        set(selection, 'status', 'kann_nicht', function ($result) {
                                                            if ($result.status == "success") {
                                                                close_schicht_menu(kunde_id, abteilung_id);
                                                            }
                                                        });
                                                    });
                                                }
                                                schicht_menu_status_content.append(schicht_menu_status_content_status_button2);

                                            var schicht_menu_status_content_status_button3 = $("<button/>", {class: "btn kann_andere_uhrzeit btn-block"});
                                                schicht_menu_status_content_status_button3.html("Uhrzeit passt nicht");
                                                if (selection_has_offen || selection_has_archiviert) {
                                                    schicht_menu_status_content_status_button3.addClass("disabled");
                                                } else {
                                                    schicht_menu_status_content_status_button3.click(function () {
                                                        set(selection, 'status', 'kann_andere_uhrzeit', function ($result) {
                                                            if ($result.status == "success") {
                                                                close_schicht_menu(kunde_id, abteilung_id);
                                                            }
                                                        });
                                                    });
                                                }
                                                schicht_menu_status_content.append(schicht_menu_status_content_status_button3);

                                        schicht_menu_status.append(schicht_menu_status_content);

                                    schicht_menu.append(schicht_menu_status);
                                //--end schicht_menu_status

                                var schicht_menu_optionen = $("<div/>", {class: "schicht-menu-optionen row", style: "display: none;"});
                                    var schicht_menu_optionen_content = $("<div/>", {class: "col-12 py-3"});
                                        var schicht_menu_optionen_content_h5_pause = $("<h5/>");
                                            schicht_menu_optionen_content_h5_pause.html("Pause hinzufügen");
                                            schicht_menu_optionen_content.append(schicht_menu_optionen_content_h5_pause);

                                            var schicht_menu_optionen_content_pause_div = $("<div/>", {class: "btn-group d-flex w-100", role: "group"});
                                                var schicht_menu_optionen_content_pause_div_button00 = $("<button/>", {class: "btn btn-secondary w-100 px-0", type: "button"});
                                                    schicht_menu_optionen_content_pause_div_button00.html("00");
                                                    if (selection_has_offen || selection_has_nicht_benachrichtigt || selection_has_nicht_bestaetigt || selection_has_kann_nicht || selection_has_kann_andere_uhrzeit || selection_has_archiviert) {
                                                        schicht_menu_optionen_content_pause_div_button00.addClass("disabled");
                                                    } else {
                                                        schicht_menu_optionen_content_pause_div_button00.click(function () {
                                                            set(selection, 'pause', '00:00', function ($result) {
                                                                if ($result.status == "success") {
                                                                    close_schicht_menu(kunde_id, abteilung_id);
                                                                }
                                                            });
                                                        });
                                                    }
                                                    schicht_menu_optionen_content_pause_div.append(schicht_menu_optionen_content_pause_div_button00);

                                                var schicht_menu_optionen_content_pause_div_button15 = $("<button/>", {class: "btn btn-secondary w-100 px-0", type: "button"});
                                                    schicht_menu_optionen_content_pause_div_button15.html("15");
                                                    if (selection_has_offen || selection_has_nicht_benachrichtigt || selection_has_nicht_bestaetigt || selection_has_kann_nicht || selection_has_kann_andere_uhrzeit || selection_has_archiviert) {
                                                        schicht_menu_optionen_content_pause_div_button15.addClass("disabled");
                                                    } else {
                                                        schicht_menu_optionen_content_pause_div_button15.click(function () {
                                                            set(selection, 'pause', '00:15', function ($result) {
                                                                if ($result.status == "success") {
                                                                    close_schicht_menu(kunde_id, abteilung_id);
                                                                }
                                                            });
                                                        });
                                                    }
                                                    schicht_menu_optionen_content_pause_div.append(schicht_menu_optionen_content_pause_div_button15);

                                                var schicht_menu_optionen_content_pause_div_button30 = $("<button/>", {class: "btn btn-secondary w-100 px-0", type: "button"});
                                                    schicht_menu_optionen_content_pause_div_button30.html("30");
                                                    if (selection_has_offen || selection_has_nicht_benachrichtigt || selection_has_nicht_bestaetigt || selection_has_kann_nicht || selection_has_kann_andere_uhrzeit || selection_has_archiviert) {
                                                        schicht_menu_optionen_content_pause_div_button30.addClass("disabled");
                                                    } else {
                                                        schicht_menu_optionen_content_pause_div_button30.click(function () {
                                                            set(selection, 'pause', '00:30', function ($result) {
                                                                if ($result.status == "success") {
                                                                    close_schicht_menu(kunde_id, abteilung_id);
                                                                }
                                                            });
                                                        });
                                                    }
                                                    schicht_menu_optionen_content_pause_div.append(schicht_menu_optionen_content_pause_div_button30);

                                                var schicht_menu_optionen_content_pause_div_button45 = $("<button/>", {class: "btn btn-secondary w-100 px-0", type: "button"});
                                                    schicht_menu_optionen_content_pause_div_button45.html("45");
                                                    if (selection_has_offen || selection_has_nicht_benachrichtigt || selection_has_nicht_bestaetigt || selection_has_kann_nicht || selection_has_kann_andere_uhrzeit || selection_has_archiviert) {
                                                        schicht_menu_optionen_content_pause_div_button45.addClass("disabled");
                                                    } else {
                                                        schicht_menu_optionen_content_pause_div_button45.click(function () {
                                                            set(selection, 'pause', '00:45', function ($result) {
                                                                if ($result.status == "success") {
                                                                    close_schicht_menu(kunde_id, abteilung_id);
                                                                }
                                                            });
                                                        });
                                                    }
                                                    schicht_menu_optionen_content_pause_div.append(schicht_menu_optionen_content_pause_div_button45);

                                                var schicht_menu_optionen_content_pause_div_button60 = $("<button/>", {class: "btn btn-secondary w-100 px-0", type: "button"});
                                                    schicht_menu_optionen_content_pause_div_button60.html("60");
                                                    if (selection_has_offen || selection_has_nicht_benachrichtigt || selection_has_nicht_bestaetigt || selection_has_kann_nicht || selection_has_kann_andere_uhrzeit || selection_has_archiviert) {
                                                        schicht_menu_optionen_content_pause_div_button60.addClass("disabled");
                                                    } else {
                                                        schicht_menu_optionen_content_pause_div_button60.click(function () {
                                                            set(selection, 'pause', '01:00', function ($result) {
                                                                if ($result.status == "success") {
                                                                    close_schicht_menu(kunde_id, abteilung_id);
                                                                }
                                                            });
                                                        });
                                                    }
                                                    schicht_menu_optionen_content_pause_div.append(schicht_menu_optionen_content_pause_div_button60);

                                                schicht_menu_optionen_content.append(schicht_menu_optionen_content_pause_div);

                                            var schicht_menu_optionen_content_pause_div = $("<div/>", {class: "form-group mb-0"});
                                                var schicht_menu_optionen_content_pause_div_input = $("<input/>", {class: "form-control text-center mt-2", type: "text"});
                                                    if (selection_has_offen || selection_has_nicht_benachrichtigt || selection_has_nicht_bestaetigt || selection_has_kann_nicht || selection_has_kann_andere_uhrzeit || selection_has_archiviert) {
                                                        schicht_menu_optionen_content_pause_div_input.attr("disabled", "disabled");
                                                    } else {
                                                        schicht_menu_optionen_content_pause_div_input.attr("data-inputmask", "\'mask\' : \'99:99\'");
                                                        schicht_menu_optionen_content_pause_div_input.inputmask();
                                                    }

                                                    schicht_menu_optionen_content_pause_div.append(schicht_menu_optionen_content_pause_div_input);
                                                schicht_menu_optionen_content.append(schicht_menu_optionen_content_pause_div);

                                            var schicht_menu_optionen_content_pause_button = $("<button/>", {class: "btn btn-secondary form-control mt-2 px-0", type: "submit"});
                                                schicht_menu_optionen_content_pause_button.html("Speichern");
                                                if (selection_has_offen || selection_has_nicht_benachrichtigt || selection_has_nicht_bestaetigt || selection_has_kann_nicht || selection_has_kann_andere_uhrzeit || selection_has_archiviert) {
                                                    schicht_menu_optionen_content_pause_button.addClass("disabled");
                                                } else {
                                                    schicht_menu_optionen_content_pause_button.click(function () {
                                                        var value = schicht_menu_optionen_content_pause_div_input.val().replace(/\D/g,'');
                                                        if (value.length != 4 || !value.match(/^0[0-9][0-5][0-9]$/)) {
                                                            schicht_menu_optionen_content_pause_div.addClass("has-danger");
                                                        } else {
                                                            set(selection, 'pause', value, function ($result) {
                                                                if ($result.status == "success") {
                                                                    close_schicht_menu(kunde_id, abteilung_id);
                                                                }
                                                            });
                                                        }
                                                    });
                                                }
                                                schicht_menu_optionen_content.append(schicht_menu_optionen_content_pause_button);

                                        var schicht_menu_optionen_content_h5_zeit = $("<h5/>", {class: "mt-4"});
                                            schicht_menu_optionen_content_h5_zeit.html("Zeit ändern");
                                            schicht_menu_optionen_content.append(schicht_menu_optionen_content_h5_zeit);

                                            var schicht_menu_optionen_content_zeit_row = $("<div/>", {class: "row form-group mb-0 no-gutters"});
                                                var schicht_menu_optionen_content_zeit_row_col1 = $("<div/>", {class: "col pr-1"});
                                                    var schicht_menu_optionen_content_zeit_row_col1_input = $("<input/>", {class: "form-control text-center", type: "text"});
                                                        if (selection_has_archiviert) {
                                                            schicht_menu_optionen_content_zeit_row_col1_input.attr("disabled", "disabled");
                                                        } else {
                                                            schicht_menu_optionen_content_zeit_row_col1_input.attr("data-inputmask", "\'mask\' : \'99:99\'");
                                                            schicht_menu_optionen_content_zeit_row_col1_input.inputmask();
                                                        }
                                                        schicht_menu_optionen_content_zeit_row_col1.append(schicht_menu_optionen_content_zeit_row_col1_input);

                                                    schicht_menu_optionen_content_zeit_row.append(schicht_menu_optionen_content_zeit_row_col1);

                                                var schicht_menu_optionen_content_zeit_row_col2 = $("<div/>", {class: "col pl-1"});
                                                    var schicht_menu_optionen_content_zeit_row_col2_input = $("<input/>", {class: "form-control text-center", type: "text"});
                                                        if (selection_has_archiviert) {
                                                            schicht_menu_optionen_content_zeit_row_col2_input.attr("disabled", "disabled");
                                                        } else {
                                                            schicht_menu_optionen_content_zeit_row_col2_input.attr("data-inputmask", "\'mask\' : \'99:99\'");
                                                            schicht_menu_optionen_content_zeit_row_col2_input.inputmask();
                                                        }
                                                        schicht_menu_optionen_content_zeit_row_col2.append(schicht_menu_optionen_content_zeit_row_col2_input);

                                                    schicht_menu_optionen_content_zeit_row.append(schicht_menu_optionen_content_zeit_row_col2);

                                                schicht_menu_optionen_content.append(schicht_menu_optionen_content_zeit_row);

                                            var schicht_menu_optionen_content_zeit_button = $("<button/>", {class: "btn btn-secondary form-control mt-2 px-0", type: "submit"});
                                                schicht_menu_optionen_content_zeit_button.html("Speichern");
                                                if (selection_has_archiviert) {
                                                    schicht_menu_optionen_content_zeit_button.addClass("disabled");
                                                } else {
                                                    schicht_menu_optionen_content_zeit_button.click(function () {
                                                        var von = schicht_menu_optionen_content_zeit_row_col1_input.val().replace(/\D/g,'');
                                                        var bis = schicht_menu_optionen_content_zeit_row_col2_input.val().replace(/\D/g,'');
                                                        if (von.length != 4 || !von.match(/^[0-2][0-9][0-5][0-9]$/) || bis.length != 4 || !bis.match(/^[0-2][0-9][0-5][0-9]$/)) {
                                                            schicht_menu_optionen_content_zeit_row.addClass("has-danger");
                                                        } else {
                                                            setZeit(selection, von, bis, function ($result) {
                                                                if ($result.status == "success") {
                                                                    close_schicht_menu(kunde_id, abteilung_id);
                                                                }
                                                            });
                                                        }
                                                    });
                                                }
                                                schicht_menu_optionen_content.append(schicht_menu_optionen_content_zeit_button);

                                        var schicht_menu_optionen_content_h5_loeschen = $("<h5/>", {class: "mt-4"});
                                            schicht_menu_optionen_content_h5_loeschen.html("Löschen");
                                            schicht_menu_optionen_content.append(schicht_menu_optionen_content_h5_loeschen);

                                            var schicht_menu_optionen_content_loeschen_button = $("<button/>", {class: "btn btn-secondary btn-block"});
                                                schicht_menu_optionen_content_loeschen_button.html("Schicht(en) löschen");
                                                if (selection_has_archiviert) {
                                                    schicht_menu_optionen_content_loeschen_button.addClass("disabled");
                                                } else {
                                                    schicht_menu_optionen_content_loeschen_button.click(function () {
                                                        del(selection, function ($result) {
                                                            if ($result.status == "success") {
                                                                close_schicht_menu(kunde_id, abteilung_id);
                                                            }
                                                        });
                                                    });
                                                }
                                                schicht_menu_optionen_content.append(schicht_menu_optionen_content_loeschen_button);

                                        schicht_menu_optionen.append(schicht_menu_optionen_content);
                                    schicht_menu.append(schicht_menu_optionen);
                                //--end schicht_menu_optionen
                            //--end schicht_menu
                            schicht_menu_container_large.html("<h4>Bearbeiten</h4>");
                            if ($(window).width() >= 1200) {
                                schicht_menu_container_large.append(schicht_menu);
                            } else {
                                schicht_menu.insertAfter($("#schicht-" + $(selection).last()[0]));
                            }
                        } else {
                            schicht_menu_container_large.html("<h4>Bearbeiten</h4>-- bitte eine/mehrere Schicht(en) auswählen");
                        }
                    }
                });
            {{/if}}

            $(window).resize(function () {
                if ($(window).width() >= 1200) {
                    $(".schicht-menu").each(function () {
                        $(this).appendTo("#schicht-menu-container-large-" + $(this).data("kundeId") + "-" + $(this).data("abteilungId"));
                    });
                } else {
                    $(".schicht-menu").each(function () {
                        $(this).insertAfter("#schicht-" + $(this).data("schichtId"));
                    });
                }
            });

            $(".wochentag-pluszeichen").click(function () {
                add($(this).data("year"), $(this).data("kw"), $(this).data("day"), $(this).data("kundeId"), $(this).data("abteilungId"), function ($result) {
                    if ($result.status == "success") {
                        close_schicht_menu(kunde_id, abteilung_id);
                    }
                });
            });

            $(".kunde-button").click(function () {
                $("#kunde-" + $(this).data("kundeId") + "-menu").toggle();
            });
        });
    </script>
    <!-- /Selectable -->

    <!-- Live Updates -->
    <script>
        $(document).ready(function () {
            $error_container = $("#error_container");
            $error_content = $("#error_content");
            $last_update = '{{$smarty.now|date_format:"Y-m-d H:i:s"|default:"???"}}';
            $year = '{{$smarty_vars.requested_year|default:"???"}}';
            $week = '{{$smarty_vars.requested_week|default:"???"}}';
            $kunden_json = '{{$smarty_vars.kunden_json|default:"???"}}';
            update();
        });

        function showError(message, code) {
            $error_content.html(message);
            $error_container.show();
        }

        function update() {
            $.ajax({
                type: "POST",
                url: "/schichten/ajax",
                data: {type: 'get', data: 'schichten', last_update: $last_update, year: $year, week: $week, kunden: $kunden_json},
                success: function (data) {
                    $error_container.hide();

                    if (data.hasOwnProperty('status')) {
                        if (data.status == "success") {
                            $error_container.hide();
                            if (data.hasOwnProperty('schichten') && data.hasOwnProperty('last_update')) {
                                if (data.schichten.length > 0) {
                                    $.each(data.schichten, function (index, schicht) {
                                        {{if $smarty_vars.company == 'tps'}}if (schicht.action == 'update_palettenanzahl') {
                                            $("#palettenanzahl-insgesamt-" + schicht.kunde + "-" + schicht.abteilung).html(schicht.palettenanzahl_insgesamt);
                                            $("#palettenanzahl-input-" + schicht.kunde + "-" + schicht.abteilung + "-" + schicht.wochentag).val(schicht.palettenanzahl_wochentag);

                                            parent = getParent($("#palettenanzahl-input-" + schicht.kunde + "-" + schicht.abteilung + "-" + schicht.wochentag));
                                            if (parent.hasClass('palettenanzahl-changed')) {
                                                parent.removeClass('palettenanzahl-changed');
                                            }
                                            if (parent.hasClass('palettenanzahl-success')) {
                                                parent.removeClass('palettenanzahl-success');
                                            }
                                            if (parent.hasClass('palettenanzahl-error')) {
                                                parent.removeClass('palettenanzahl-error');
                                            }
                                        } else if (schicht.action == 'update_produktivitaetsfaktor') {
                                            $("#produktivitaetsfaktor-insgesamt-" + schicht.kunde + "-" + schicht.abteilung).html(schicht.produktivitaetsfaktor_insgesamt);
                                            $("#produktivitaetsfaktor-" + schicht.kunde + "-" + schicht.abteilung + "-" + schicht.wochentag).html(schicht.produktivitaetsfaktor_wochentag);
                                        } else{{/if}} if (schicht.action == 'update_schichtstunden') {
                                            $("#schichtstunden-insgesamt-" + schicht.kunde + "-" + schicht.abteilung).html(schicht.stunden_insgesamt);
                                            $("#schichtstunden-" + schicht.kunde + "-" + schicht.abteilung + "-" + schicht.wochentag).html(schicht.stunden_wochentag);
                                        } else if (schicht.action == 'update_statistics') {
                                            $("#progress_bar").html(schicht.statistik_prozent + "%");
                                            $("#progress_bar").css("width", schicht.statistik_prozent + "%");
                                            $("#statistik_offen").html(schicht.statistik_offen);
                                            $("#statistik_insgesamt").html(schicht.statistik_insgesamt + " insgesamt");
                                            $("#statistik_nicht_benachrichtigt").html(schicht.statistik_nicht_benachrichtigt);
                                            $("#statistik_benachrichtigt").html(schicht.statistik_benachrichtigt);
                                            $("#statistik_nicht_bestaetigt").html(schicht.statistik_nicht_bestaetigt);
                                            $("#statistik_kann_nicht").html(schicht.statistik_kann_nicht);
                                            $("#statistik_kann_andere_uhrzeit").html(schicht.statistik_kann_andere_uhrzeit);
                                            $("#statistik_stundenzettel_bestaetigt").html(schicht.statistik_stundenzettel_bestaetigt);
                                            $("#statistik_archiviert").html(schicht.statistik_archiviert);
                                        } else if (schicht.action == 'insert') {
                                            location.reload();
                                        } else if (schicht.action == 'update') {
                                            if (schicht.field == "vorname") {
                                                $("#schicht-" + schicht.id + "-vorname").html(schicht.value);
                                            } else if (schicht.field == "nachname") {
                                                $("#schicht-" + schicht.id + "-nachname").html(schicht.value);
                                            } else if (schicht.field == "personalnummer") {
                                                $("#schicht-" + schicht.id + "-personalnummer").html(schicht.value);
                                            } else if (schicht.field == "von") {
                                                $("#schicht-" + schicht.id + "-von").html(schicht.value);
                                            } else if (schicht.field == "bis") {
                                                $("#schicht-" + schicht.id + "-bis").html(schicht.value);
                                            } else if (schicht.field == "status") {
                                                $("#schicht-" + schicht.id).attr("class", "schicht " + schicht.value);
                                                $("#schicht-" + schicht.id).data("status", schicht.value);
                                                $(".schichtfilter").change();
                                            } else if (schicht.field == "pause") {
                                                $("#schicht-" + schicht.id + "-pause").html(schicht.value);
                                            } else if (schicht.field == "kunde_id") {
                                                location.reload();
                                            } else if (schicht.field == "abteilung_id") {
                                                location.reload();
                                            }

                                            if (schicht.field == "vorname" || schicht.field == "nachname" || schicht.field == "personalnummer") {
                                                if (schicht.hasOwnProperty('mitarbeiter_id')) {
                                                    $("#schicht-" + schicht.id).data("mitarbeiterId", schicht.mitarbeiter_id);
                                                    $("#mitarbeiterfilter").change();
                                                }
                                            }

                                            if ($("#schicht-" + schicht.id).data("status") != "stundenzettel_bestaetigt" && $("#schicht-" + schicht.id).data("status") != "archiviert") {
                                                $("#schicht-" + schicht.id + "-pause").html("&nbsp;");
                                            }
                                        } else if (schicht.action == 'delete') {
                                            $("#schicht-" + schicht.id).remove();
                                        }
                                    });
                                    $last_update = data.last_update;
                                }
                            } else {
                                showError("Es ist ein Fehler aufgetreten.", 8);
                            }
                        } else if (data.status == "not_logged_in") {
                            location.reload();
                        } else {
                            showError("Es ist ein Fehler aufgetreten.", 9);
                        } 
                    } else {
                        showError("Es ist ein Fehler aufgetreten.", 10);
                    }

                    setTimeout(update, 1000);
                },
                error: function () {
                    showError("Es ist ein Fehler aufgetreten.", 11);
                    setTimeout(update, 1000);
                }
            });
        }
    </script>
    <!-- /Live Updates -->

    {{if $smarty_vars.company == 'tps'}}
        <!-- Palettenanzahl Input -->
        <script>
            function getParent(input) {
                return $('#palettenanzahl-' + input.data('kundeId') + '-' + input.data('abteilungId') + '-' + input.data('day'));
            }

            function markChanged(input) {
                var parent = getParent(input);
                if (parent.hasClass('palettenanzahl-error')) {
                    parent.removeClass('palettenanzahl-error');
                }
                if (parent.hasClass('palettenanzahl-success')) {
                    parent.removeClass('palettenanzahl-success');
                }
                if (!parent.hasClass('palettenanzahl-changed')) {
                    parent.addClass('palettenanzahl-changed');
                }
            }

            function markError(input) {
                var parent = getParent(input);
                if (parent.hasClass('palettenanzahl-changed')) {
                    parent.removeClass('palettenanzahl-changed');
                }
                if (parent.hasClass('palettenanzahl-success')) {
                    parent.removeClass('palettenanzahl-success');
                }
                if (!parent.hasClass('palettenanzahl-error')) {
                    parent.addClass('palettenanzahl-error');
                }
            }

            function markUnchanged(input) {
                var parent = getParent(input);
                if (parent.hasClass('palettenanzahl-changed')) {
                    parent.removeClass('palettenanzahl-changed');
                }
                if (parent.hasClass('palettenanzahl-success')) {
                    parent.removeClass('palettenanzahl-success');
                }
                if (parent.hasClass('palettenanzahl-error')) {
                    parent.removeClass('palettenanzahl-error');
                }
            }

            function markSuccess(input) {
                var parent = getParent(input);
                if (parent.hasClass('palettenanzahl-changed')) {
                    parent.removeClass('palettenanzahl-changed');
                }
                if (parent.hasClass('palettenanzahl-error')) {
                    parent.removeClass('palettenanzahl-error');
                }
                if (!parent.hasClass('palettenanzahl-success')) {
                    parent.addClass('palettenanzahl-success');
                }
            }

            function save_palettenanzahl(input) {
                if (input.val() != input.data('originalValue')) {
                    if (input.val().match(/^\d{1,3}(,\d{1,2})?$/)) {
                        $.ajax({
                            type: "POST",
                            url: "/schichten/ajax",
                            data: {
                                type: 'set_palettenanzahl',
                                kunde: input.data('kundeId'),
                                abteilung: input.data('abteilungId'),
                                year: input.data('year'),
                                week: input.data('kw'),
                                day: input.data('day'),
                                anzahl: input.val()
                            },
                            success: function ($return) {
                                if ($return.hasOwnProperty('status')) {
                                    if ($return.status == "success") {
                                        markSuccess(input);
                                        input.blur();

                                        if ($return.hasOwnProperty('set_to_zero')) {
                                            input.data('originalValue', '0,00');
                                            input.val('0,00');
                                            markUnchanged(input);
                                        }
                                    } else if ($return.status == "not_logged_in") {
                                        location.reload();
                                    } else {
                                        showError("Es ist ein Fehler aufgetreten.", 1);
                                    }
                                } else {
                                    showError("Es ist ein Fehler aufgetreten.", 2);
                                }
                            },
                            error: function () {
                                showError("Es ist ein Fehler aufgetreten.", 3);
                            }
                        });
                    } else {
                        markError(input);
                    }
                } else {
                    markUnchanged(input);
                }
            }

            $(document).ready(function() {
                var palettenanzahl_input = $(".palettenanzahl-input");
                palettenanzahl_input.keyup(function(e) {
                    if (e.which == 13) {
                        save_palettenanzahl($(this));
                    } else if (e.which == 27) {
                        $(this).val($(this).data('originalValue'));
                        $(this).blur();
                        markUnchanged($(this));
                    }
                });
                palettenanzahl_input.on('input', function() {
                    if ($(this).val() == $(this).data('originalValue')) {
                        markUnchanged($(this));
                    } else if ($(this).val().match(/^\d{1,3}(,\d{1,2})?$/)) {
                        markChanged($(this));
                    } else {
                        markError($(this));
                    }
                });
                palettenanzahl_input.focus(function() {
                    $(this).select();
                });
            });
        </script>
        <!-- /Palettenanzahl Input -->
    {{/if}}
{{/capture}}

{{$js=$smarty.capture.scripts scope=parent}}