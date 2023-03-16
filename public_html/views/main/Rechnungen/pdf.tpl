<!DOCTYPE html>
<html lang="de">
    <head>
    	<link href="assets/vendors/bootstrap-4.0.0-alpha.6-dist/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    	<script src="assets/vendors/bootstrap-4.0.0-alpha.6-dist/js/bootstrap.min.js"></script>
    	<script src="assets/vendors/jquery-3.2.1/jquery-3.2.1.min.js"></script>
    	<style>
			html{
				/*font-family:arial;*/
				font-size:0.85em;
				color: #000000 !important ;
			}
			.font-s-7{font-size:7pt;line-height:3mm !important;}
			.font-s-8{font-size:8pt;}
			.font-s-9{font-size:9pt;}
			.font-s-10{font-size:10pt;line-height:5mm !important;}
			.font-s-12{font-size:12pt;}
			.small{
				font-size:0.7em;
			}
			.row .col {color: #000000 !important ;}
			.table-bordered-dark{border:1px #000000 !important ;}
			.table-bordered-dark td,.table-bordered-dark th{border:1px solid #000000 !important ;}
			.table-borderless{border:0px}
			.table-borderless td,.table-borderless th{border:0px}
			.hr-dark{margin-top:1rem;margin-bottom:1rem;border:0;border-top:1px solid black !important ;}
			.text-underline{text-decoration:underline;}
    	</style>
    <!------ Include the above in your HEAD tag ---------->
    </head>
    <body>
        <div class="container" style="width:95%;">
            <br>
            <div class="row p-0">
                <div class="col text-right p-0" style="padding-right: 0px !important;">
                    {{$smarty_vars.rechnunglogo}}
                </div>
            </div>
            <br>
            <br>
            <br>
            <br><br>
            <div class="row p-0">
                <div class="col text-underline font-s-7">
                    {{$smarty_vars.absenderadresse}}
                </div>
            </div>
            <br>
            <div class="row p-0">
                <div class="col font-s-10" style="width:110mm;">
                    <p class="pb-0">{{$smarty_vars.rechnungsanschrift}}</p>
                </div>
            </div>

            <div class="row p-0">
                <div class="col p-0 font-s-10">
                    <table class="table table-borderless p-0">
                        <tr>
                            <td class="p-0">&nbsp; </td>
                            <td class="text-right p-0" style="width:70mm;">Datum:</td>
                            <td class="text-right p-0"  style="width:30mm;">{{$smarty_vars.rechnungsdatum}}</td>
                        </tr>
                        <tr>
                            <td class="p-0" style="padding-left: 15px !important;">{{$smarty_vars.kostenstelle}}</td>
                            <td class="text-right p-0" >Rechnungs-Nr.:</td>
                            <td class="text-right p-0" >{{$smarty_vars.rechnungsnummer}}</td>
                        </tr>
                        <tr>
                            <td class="p-0">&nbsp; </td>
                            <td class="text-right p-0">Leistungszeitraum:</td>
                            <td class="text-right p-0">{{$smarty_vars.leistungszeitraum}}</td>
                        </tr>
                        <tr>
                            <td class="p-0">&nbsp; </td>
                            <td class="text-right p-0">Kundennummer:</td>
                            <td class="text-right p-0">{{$smarty_vars.kundennummer}}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="row p-0">
                <div class="col ">
                    <p class="font-weight-bold font-italic mb-1 font-s-12">{{$smarty_vars.rechnungtitle}}</p>
                    <br>
                    <p class="font-s-10">{{$smarty_vars.anrede}}</p>
                    <p class="font-s-10">für die von uns erbrachten Leistungen stellen wir Ihnen für den Zeitraum vom {{$smarty_vars.zeitraum_von}} bis<br>
                    {{$smarty_vars.zeitraum_bis}} nachfolgend, aufgeführte Rechnung</p><br>
                </div>
            </div>

            {{if isset($smarty_vars.rechnungsliste)}}
                <div class="row p-0">
                    <div class="col">
                        <table class="table table-bordered-dark  p-0">
                            <tr>
                                <td class="p-0 font-s-9" style="width:10mm;">&nbsp;Pos </td>
                                <td class="p-0 font-s-9" > &nbsp;Leistungsart </td>
                                <td class="text-right p-0 font-s-9" style="width:20mm;"> Menge </td>
                                <td class="text-right p-0 font-s-9" style="width:27mm;"> Einzelpreis in € </td>
                                <td class="text-right p-0 font-s-9" style="width:35mm;"> Gesamtpreis in € </td>
                            </tr>
                            {{foreach from=$smarty_vars.rechnungsliste item='row'}}
                                <tr>
                                    <td class="p-0 font-s-10">  &nbsp;{{$row.postennummer}} </td>
                                    <td class="p-0 font-s-10">   &nbsp;{{$row.leistungsart}} </td>
                                    <td class="text-right p-0 font-s-10"> {{$row.menge}} </td>
                                    <td class="text-right p-0 font-s-10"> {{$row.einzelpreis}} </td>
                                    <td class="text-right p-0 font-s-10"> {{$row.gesamtpreis}} </td>
                                </tr>
                          {{/foreach}}
                       </table>
                    </div>
                </div>
                <br>
                <br>
            {{/if}}

            {{if isset($smarty_vars.gesamtbetrag)}}
                <div class="row p-0">
                    <div class="col font-s-10">
                        <table class="table table-bordered-dark">
                            {{foreach from=$smarty_vars.gesamtbetrag item='row' key=i name=gesamtbetrag}}
                                <tr>
                                    <td class="p-0 {{if $smarty.foreach.gesamtbetrag.last}} text-underline {{/if}}" style="{{if $smarty.foreach.gesamtbetrag.last}} padding-bottom:1px !important; {{/if}}">&nbsp;{{$row.bezeichnung}}</td>
                                    <td class="text-right p-0 {{if $smarty.foreach.gesamtbetrag.last}} text-underline {{/if}}" style="width:35mm;">{{$row.betrag}}&nbsp;</td>
                                </tr>
                            {{/foreach}}
                        </table>
                   </div>
                </div>
                <br>
                <br>
            {{/if}}

            <div class="row p-0">
                <div class="col font-s-10">
                    <p class="">Der ausgewiesene Rechnungsbetrag ist zum {{$smarty_vars.zahlungsziel}} zur Zahlung fällig. Sollte der Rechnungsbetrag von Ihnen nicht innerhalb von 30 Tagen nach Eintritt der Fälligkeit und Zugang dieser Rechnung vollständig bezahlt werden, geraten Sie automatisch in Verzug, ohne dass es einer gesonderten Mahnung bedarf. Hierdurch anfallende zusätzliche Kosten sind von Ihnen zu erstatten.</p><br>
                    <p class="">Für Rückfragen stehen wir Ihnen jederzeit gern zur Verfügung.</p><br>
                    <p class="">Mit freundlichem Gruß.</p>
                </div>
            </div>
            <br>
            <br>

            <div class="fixed-bottom mx-auto" style="width:100%;margin-bottom:50px;height:1px;border:0px solid black;" >
                <hr class="hr-dark p-0" style="width:95%;">
                <div class="row p-0" style="margin-left:18px;text-align: center;">
                     <div class="col p-0" style="margin-top:0px;">
                        <table class="table table-borderless" style="margin-top:0px;">
                            <tr class="p-0">
                                <td class="p-0" style="width:45mm;">
                                    <p class="p-0 font-s-7">
                                        {{$smarty_vars.inhabertitel}}<br>
                                        {{$smarty_vars.inhaber}}<br>
                                        Tel: +49 (0) 30 / 3434909 - 0<br>
                                        Fax: +49 (0) 30 / 3434909 - 21<br>
                                    </p>
                                </td>
                                <td class="p-0" style="width:45mm;">
                                    <p class="p-0 font-s-7">
                                        Betriebsanschrift<br>
                                        Breitenbachstraße 10,<br>
                                        13509 - Berlin<br>
                                        info@ttact.de - www.ttact.de<br>
                                    </p>
                                </td>
                                <td class="p-0" style="width:45mm;">
                                    <p class="p-0 font-s-7">
                                        Informationen<br>
                                        USt-Id: {{$smarty_vars.umsatzsteuerid}}<br>
                                        Amtsgericht: Charlottenburg<br>
                                        {{$smarty_vars.handelsregisternummer}}<br>
                                    </p>
                                </td>
                                <td class="p-0" style="width:45mm;">
                                    <p class="p-0 font-s-7">
                                        Bankverbindung<br>
                                        Berliner Volksbank<br>
                                        Kto.{{$smarty_vars.kontonummer}} BLZ: 10090000<br>
                                        IBAN: {{$smarty_vars.iban}}<br>
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>