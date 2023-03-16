{{if isset($smarty_vars.content)}}
    {{capture name='content'}}{{include file = $smarty_vars.content}}{{/capture}}
{{/if}}

<!DOCTYPE html>
<html lang="de">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>{{$smarty_vars.software_name|default:"XXX"}} | {{$title|default:"Kein Titel vorhanden."}}{{$title_zusatz|default:""}}</title>

    {{if isset($smarty_vars.path_to_favicon)}}
        <!-- Favicon -->
        <link rel="shortcut icon" type="image/png" href="{{$smarty_vars.path_to_favicon}}">
    {{/if}}

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/assets/vendors/bootstrap-4.0.0-alpha.6-dist/css/bootstrap.min.css">

    <!-- FontAwesome CSS -->
    <link rel="stylesheet" href="/assets/vendors/font-awesome-4.7.0/css/font-awesome.min.css">

    <!-- NProgress CSS -->
    <link rel="stylesheet" href="/assets/vendors/nprogress-master/nprogress.css">

    {{$css|default:""}}

    <!-- Main CSS -->
    <link rel="stylesheet" href="/assets/main.css">

    {{if isset($smarty_vars.path_to_company_css)}}
        <!-- Custom CSS -->
        <link rel="stylesheet" href="{{$smarty_vars.path_to_company_css}}">
    {{/if}}
</head>
<body>
    <div class="{{$container_class|default:"container-fluid"}}">
        <nav class="navbar navbar-toggleable-md navbar-inverse bg-inverse mb-4">
            <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarSupportedContent">
                <span class="navbar-toggler-icon"></span>
            </button>
            {{if $smarty_vars.company == 'aps'}}
                <a class="navbar-brand" href="/"><strong style="color:blue;">[</strong> APS <strong style="color:blue;">]</strong></a>
            {{elseif $smarty_vars.company == 'tps'}}
                <a class="navbar-brand" href="/"><strong style="color:red;">[</strong> TPS <strong style="color:red;">]</strong></a>
            {{else}}
                <a class="navbar-brand" href="/"><strong>[</strong> {{$smarty_vars.software_name|default:"XXX"}} <strong>]</strong></a>
            {{/if}}

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="/">Startseite</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="javascript:;" data-toggle="dropdown">Schichten</a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="/schichten">Schichtplaner öffnen</a>
                            {{if ($smarty_vars.current_user.usergroup->hasRight('auftraege_alle_kunden') || $smarty_vars.current_user.usergroup->hasRight('auftraege_bestimmte_kunden'))}}
                                <a class="dropdown-item" href="/auftraege">Aufträge erstellen</a>
                            {{/if}}
                        </div>
                    </li>
                    {{if ($smarty_vars.current_user.usergroup->hasRight('kundendaten') || $smarty_vars.current_user.usergroup->hasRight('mitarbeiterliste') || $smarty_vars.current_user.usergroup->hasRight('dokumente_alle_kunden') || $smarty_vars.current_user.usergroup->hasRight('dokumente_einsehen_bestimmte_kunden'))}}
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="javascript:;" data-toggle="dropdown">Kunden</a>
                            <div class="dropdown-menu dropdown-menu-right">
                                {{if $smarty_vars.current_user.usergroup->hasRight('kundendaten')}}
                                    <a class="dropdown-item" href="/kunden">Alle anzeigen</a>
                                    <a class="dropdown-item" href="/kunden/erstellen">Neu anlegen</a>
                                {{/if}}
                                {{if $smarty_vars.company != 'tps'}}
                                    {{if $smarty_vars.current_user.usergroup->hasRight('kundendaten')}}
                                        <div class="dropdown-divider"></div>
                                    {{/if}}
                                    {{if $smarty_vars.current_user.usergroup->hasRight('mitarbeiterliste')}}
                                        <a class="dropdown-item" href="/kunden/mitarbeiterliste">Mitarbeiterliste</a>
                                    {{/if}}
                                {{/if}}
                                {{if ($smarty_vars.current_user.usergroup->hasRight('dokumente_alle_kunden') || $smarty_vars.current_user.usergroup->hasRight('dokumente_einsehen_bestimmte_kunden'))}}
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="/kunden/dokumente">Dokumente</a>
                                {{/if}}
                            </div>
                        </li>
                    {{/if}}
                    {{if $smarty_vars.current_user.usergroup->hasRight('mitarbeiter')}}
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="javascript:;" data-toggle="dropdown">Mitarbeiter</a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="/mitarbeiter/aktiv">Aktive anzeigen</a>
                                <a class="dropdown-item" href="/mitarbeiter/inaktiv">Inaktive anzeigen</a>
                                <a class="dropdown-item" href="/mitarbeiter">Alle anzeigen</a>
                                <a class="dropdown-item" href="/mitarbeiter/erstellen">Neu anlegen</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="/mitarbeiter/kalenderuebersicht">Kalenderübersicht</a>
                                <a class="dropdown-item" href="/mitarbeiter/zusammensetzen">Zusammensetzen</a>
                                <a class="dropdown-item" href="/mitarbeiter/datenabgleich">Datenabgleich</a>
                                {{if $smarty_vars.current_user.usergroup->hasRight('notizen')}}
                                    <a class="dropdown-item" href="/mitarbeiter/notizen">Notizen</a>
                                {{/if}}
                                {{if $smarty_vars.company != 'tps'}}
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="/mitarbeiter/equalpay">Equal Pay</a>
                                {{/if}}
                            </div>
                        </li>
                    {{/if}}
                    {{if ($smarty_vars.current_user.usergroup->hasRight('berechnungen_lohn') || $smarty_vars.current_user.usergroup->hasRight('berechnungen_stunden_bestimmte_kunden') || $smarty_vars.current_user.usergroup->hasRight('berechnungen_stunden_alle_kunden'))}}
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="javascript:;" data-toggle="dropdown">Berechnungen</a>
                            <div class="dropdown-menu dropdown-menu-right">
                                {{if ($smarty_vars.current_user.usergroup->hasRight('berechnungen_stunden_bestimmte_kunden') || $smarty_vars.current_user.usergroup->hasRight('berechnungen_stunden_alle_kunden'))}}
                                    <a class="dropdown-item" href="/berechnungen/stunden">Stunden</a>
                                {{/if}}
                                {{if ($smarty_vars.current_user.usergroup->hasRight('berechnungen_lohn'))}}
                                    <a class="dropdown-item" href="/berechnungen/lohn">Lohn</a>
                                    {{if $smarty_vars.company != 'tps'}}
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="/berechnungen/uebersicht">Übersicht</a>
                                    {{/if}}
                                {{/if}}
                            </div>
                        </li>
                    {{/if}}
                    {{if $smarty_vars.current_user.usergroup->hasRight('rechnungen')}}
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="javascript:;" data-toggle="dropdown">Rechnungen</a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="/rechnungen/anzeigen">Alle anzeigen</a>
                                <a class="dropdown-item" href="/rechnungen/erstellen">Neu erstellen</a>
                            </div>
                        </li>
                    {{/if}}
                </ul>
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle username" href="javascript:;" data-toggle="dropdown">
                            {{$smarty_vars.current_user.full_name|default:"???"}}
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            {{if $smarty_vars.current_user.usergroup->hasRight('eigenes_passwort_aendern')}}
                                <a class="dropdown-item" href="/benutzer/passwort">Passwort ändern</a>
                            {{/if}}
                            <a class="dropdown-item" href="/benutzer/abmelden">Abmelden</a>
                            {{if ($smarty_vars.current_user.usergroup->hasRight('benutzer_stufe1') || $smarty_vars.current_user.usergroup->hasRight('benutzer_stufe2') || $smarty_vars.current_user.usergroup->hasRight('benutzer_stufe3') || $smarty_vars.current_user.usergroup->hasRight('benutzer_stufe4') || $smarty_vars.current_user.usergroup->hasRight('benutzer_stufe5'))}}
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="/benutzer">Benutzer</a>
                            {{/if}}
                            {{if $smarty_vars.current_user.usergroup->hasRight('abteilungen')}}
                                <a class="dropdown-item" href="/abteilungen">Abteilungen</a>
                            {{/if}}
                            {{if $smarty_vars.company != 'tps'}}
                                {{if $smarty_vars.current_user.usergroup->hasRight('tarife')}}
                                    <a class="dropdown-item" href="/tarife">Tarife</a>
                                {{/if}}
                            {{/if}}
                        </div>
                    </li>
                </ul>
            </div>
        </nav>

        {{if !isset($hide_title)}}
            <div class="row mb-4">
                <div class="col">
                    <div class="title-box text-white p-4">
                        <h3 class="mb-0">{{$title|default:"Kein Titel vorhanden."}}{{if isset($smarty_vars.time)}} <small class="float-right hidden-sm-down">{{$smarty_vars.time|default:""}}</small>{{/if}}</h3>
                    </div>
                </div>
            </div>
        {{/if}}

        {{$smarty.capture.content|default:""}}
    </div>

    <!-- jQuery JavaScript -->
    <script src="/assets/vendors/jquery-3.2.1/jquery-3.2.1.min.js"></script>

    <!-- Tether JavaScript -->
    <script src="/assets/vendors/tether-1.4.0/tether.min.js"></script>

    <!-- Bootstrap JavaScript -->
    <script src="/assets/vendors/bootstrap-4.0.0-alpha.6-dist/js/bootstrap.min.js"></script>

    <!-- NProgress JavaScript -->
    <script src="/assets/vendors/nprogress-master/nprogress.js"></script>

    {{$js|default:""}}

    <!-- Main JavaScript -->
    <script src="/assets/main.js"></script>

    {{if isset($smarty_vars.path_to_company_js)}}
        <!-- Custom JavaScript -->
        <script src="{{$smarty_vars.path_to_company_js}}"></script>
    {{/if}}
</body>
</html>
