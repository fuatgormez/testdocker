<!DOCTYPE html>
<html lang="de">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="assets/vendors/bootstrap-4.0.0-alpha.6-dist/css/bootstrap.min.css">

    <!-- FontAwesome CSS -->
    <link rel="stylesheet" href="assets/vendors/font-awesome-4.7.0/css/font-awesome.min.css">

    <!-- NProgress CSS -->
    <link rel="stylesheet" href="assets/vendors/nprogress-master/nprogress.css">

    <!-- Main CSS -->
    <link rel="stylesheet" href="assets/main.css">

    <!-- Custom CSS -->
    <style type="text/css">
        html {
            margin: 0;
        }

        body {
            font-size: 10pt;
            line-height: 12pt;
            margin-top: 10mm;
            margin-bottom: 20mm;
        }

        .content {
            margin-right: 12.5mm;
            margin-left: 20mm;
        }

        .content table {
            border-collapse: collapse;
            width: 100%;
        }

        .content table td {
            border: 1px solid #444;
            padding: 0.5mm 1.5mm 0.5mm 1.5mm;
        }

        .content table.signature {
            font-size: 8pt;
            line-height: 9pt;
            width: 100%;
            margin-top: 4mm;
            margin-bottom: 8mm;
        }

        .content table.signature td {
            vertical-align: top;
            border: none;
            padding: 0;
        }

        footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            margin-bottom: -10mm;
        }

        footer table {
            margin: 0 auto;
            font-size: 7.5pt;
            line-height: 8.5pt;
        }

        footer table td {
            border: none;
            width: 45mm;
        }

        footer table td.last {
            width: 42mm
        }
    </style>

    <!-- jQuery JavaScript -->
    <script src="assets/vendors/jquery-3.2.1/jquery-3.2.1.min.js"></script>

    <!-- Tether JavaScript -->
    <script src="assets/vendors/tether-1.4.0/tether.min.js"></script>

    <!-- Bootstrap JavaScript -->
    <script src="assets/vendors/bootstrap-4.0.0-alpha.6-dist/js/bootstrap.min.js"></script>

    <!-- NProgress JavaScript -->
    <script src="assets/vendors/nprogress-master/nprogress.js"></script>

    <!-- Main JavaScript -->
    <script src="assets/main.js"></script>
</head>
<body>
    <div class="content">
        <div class="text-right">
            <img src="assets/img/ANDROM1.png" width="auto" height="48mm">
        </div>
        <div class="mb-0 pb-0" style="font-size: 12pt; font-weight: bold; margin-top: -5mm;">
            Anlage 2 zum Arbeitnehmerüberlassungsvertrag
        </div>
        <div>
            Einzelarbeitnehmerüberlassung
        </div>
        <div style="margin-top: 4mm;">
            Der Verleiher stellt dem Entleiher auf Basis des Arbeitnehmerüberlassungsvertrages vom {{$smarty_vars.rahmenvertragsdatum|default:''}} folgenden Leiharbeitnehmer zur Verfügung:
        </div>
        <table style="margin-top: 4mm;">
            <tr>
                <td>Leiharbeiter</td>
                <td style="padding: 0; border: none;">
                    <table>
                        <tr>
                            <td>Nachname, Vorname</td>
                            <td>Geburtsdatum</td>
                            <td>Personal-Nr.</td>
                        </tr>
                        <tr>
                            <td>{{$smarty_vars.nachname|default:''}}, {{$smarty_vars.vorname|default:''}}</td>
                            <td>{{$smarty_vars.geburtsdatum|default:''}}</td>
                            <td>{{$smarty_vars.personalnummer|default:''}}</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td>Arbeitsort</td>
                <td>{{$smarty_vars.arbeitsort|default:''}}</td>
            </tr>
            <tr>
                <td>Stellenbezeichnung</td>
                <td>Verkaufshilfe</td>
            </tr>
            <tr>
                <td>Tätigkeit</td>
                <td>{{$smarty_vars.taetigkeit|default:''}}</td>
            </tr>
            <tr>
                <td>Tätigkeitsbeschreibung</td>
                <td>{{$smarty_vars.taetigkeitsbeschreibung|default:''}}</td>
            </tr>
            <tr>
                <td>Erforderliche arbeitsmedizinische Untersuchungen</td>
                <td>Keine</td>
            </tr>
            <tr>
                <td>Erforderliche Qualifikationen</td>
                <td>Keine</td>
            </tr>
            <tr>
                <td>Erforderliche Schutzausrüstung</td>
                <td>Keine</td>
            </tr>
            <tr>
                <td>Deutschkenntnisse</td>
                <td>B1 / B2</td>
            </tr>
            <tr>
                <td>Vertragliche Wochenstunden des Leiharbeitnehmers</td>
                <td>{{$smarty_vars.wochenstunden|default:''}}</td>
            </tr>
            <tr>
                <td>Arbeitszeit</td>
                <td>Entleiher plant den Leiharbeitnehmer nach Bedarf mit den o.g. Wochenstunden</td>
            </tr>
            <tr>
                <td>Beginn der Überlassung</td>
                <td>{{$smarty_vars.ueberlassungsbeginn|default:''}}</td>
            </tr>
            <tr>
                <td>Voraussichtliche Dauer der Überlassung</td>
                <td>{{$smarty_vars.ueberlassungsdauer|default:''}}</td>
            </tr>
            <tr>
                <td>Vergütung</td>
                <td>{{$smarty_vars.verguetung|default:''}}</td>
            </tr>
            <tr>
                <td>Zuschläge</td>
                <td>
                    25% Nachtzuschlag (23.00 – 6.00 Uhr)<br>
                    50% Sonntagszuschlag<br>
                    100% Feiertagszuschlag<br>
                    Mehrarbeitszuschläge (25% Zuschlag ab 15% Mehrarbeit als vertragliche Wochenstunden)
                </td>
            </tr>
            <tr>
                <td>Sonstige Zulagen</td>
                <td>Keine</td>
            </tr>
            <tr>
                <td>Übernahme des Leiharbeiters</td>
                <td>
                
                    Während der Überlassung ist eine Übernahme des Leiharbeiters durch den Entleiher bei Zahlung einer Provision (3 Bruttomonatsgehälter) möglich.
                </td>
            </tr>
        </table>
        <div style="text-decoration: underline; margin-top: 4mm;">
            Berlin, {{$smarty_vars.datum|default:''}}
        </div>
        <div>
            Ort, Datum
        </div>
        <table class="signature">
            <tr>
                <td>
                    ANDROM Personalservice GmbH<br>
                    Breitenbachstraße 10, 13509 Berlin<br>
                    Tel.: 030 34349090<br>
                    Fax: 030 343490921<br>
                    Mail: info@ttact.de<br>
                    Internet: www.ttact.de
                </td>
                <td style="width: 25mm;">&nbsp;</td>
                <td>
                    {{$smarty_vars.kundensignatur|default:''}}
                </td>
            </tr>
        </table>
    </div>
    <footer>
        <table>
            <tr>
                <td>
                    <strong>Geschäftsführer</strong><br>
                    Hakan Kinaci<br>
                    Tel: +49 (0) 30 / 3434909 - 0<br>
                    Fax: +49 (0) 30 / 3434909 - 21
                </td>
                <td>
                    <strong>Betriebsanschrift</strong><br>
                    Breitenbachstraße 10,<br>
                    13509 - Berlin<br>
                    info@ttact.de / www.ttact.de
                </td>
                <td>
                    <strong>Informationen</strong><br>
                    USt-Id: 27 / 208 / 31014<br>
                    Amtsgericht: Charlottenburg<br>
                    HRB 143506 B
                </td>
                <td class="last">
                    <strong>Bankverbindung</strong><br>
                    Berliner Volksbank<br>
                    Kto: 2420828007 - BLZ: 10090000<br>
                    IBAN: DE41 1009 0000 2420 8280 07
                </td>
            </tr>
        </table>
    </footer>
</body>
</html>
