<?php
/* Smarty version 3.1.30, created on 2019-07-11 04:43:25
  from "/usr/local/www/apache24/noexec/ttact-intern-software/views/main.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5d26a24db93ad5_91065165',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'f3a26ccdcd03239ba86b246e850c35214a3e94aa' => 
    array (
      0 => '/usr/local/www/apache24/noexec/ttact-intern-software/views/main.tpl',
      1 => 1562525853,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5d26a24db93ad5_91065165 (Smarty_Internal_Template $_smarty_tpl) {
if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['content'])) {?>
    <?php $_smarty_tpl->smarty->ext->_capture->open($_smarty_tpl, 'content', null, null);
$_smarty_tpl->_subTemplateRender($_smarty_tpl->tpl_vars['smarty_vars']->value['content'], $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
$_smarty_tpl->smarty->ext->_capture->close($_smarty_tpl);
?>

<?php }?>

<!DOCTYPE html>
<html lang="de">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title><?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['software_name'])===null||$tmp==='' ? "XXX" : $tmp);?>
 | <?php echo (($tmp = @$_smarty_tpl->tpl_vars['title']->value)===null||$tmp==='' ? "Kein Titel vorhanden." : $tmp);
echo (($tmp = @$_smarty_tpl->tpl_vars['title_zusatz']->value)===null||$tmp==='' ? '' : $tmp);?>
</title>

    <?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['path_to_favicon'])) {?>
        <!-- Favicon -->
        <link rel="shortcut icon" type="image/png" href="<?php echo $_smarty_tpl->tpl_vars['smarty_vars']->value['path_to_favicon'];?>
">
    <?php }?>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/assets/vendors/bootstrap-4.0.0-alpha.6-dist/css/bootstrap.min.css">

    <!-- FontAwesome CSS -->
    <link rel="stylesheet" href="/assets/vendors/font-awesome-4.7.0/css/font-awesome.min.css">

    <!-- NProgress CSS -->
    <link rel="stylesheet" href="/assets/vendors/nprogress-master/nprogress.css">

    <?php echo (($tmp = @$_smarty_tpl->tpl_vars['css']->value)===null||$tmp==='' ? '' : $tmp);?>


    <!-- Main CSS -->
    <link rel="stylesheet" href="/assets/main.css">

    <?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['path_to_company_css'])) {?>
        <!-- Custom CSS -->
        <link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['smarty_vars']->value['path_to_company_css'];?>
">
    <?php }?>
</head>
<body>
    <div class="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['container_class']->value)===null||$tmp==='' ? "container-fluid" : $tmp);?>
">
        <nav class="navbar navbar-toggleable-md navbar-inverse bg-inverse mb-4">
            <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarSupportedContent">
                <span class="navbar-toggler-icon"></span>
            </button>
            <?php if ($_smarty_tpl->tpl_vars['smarty_vars']->value['company'] == 'aps') {?>
                <a class="navbar-brand" href="/"><strong style="color:blue;">[</strong> APS <strong style="color:blue;">]</strong></a>
            <?php } elseif ($_smarty_tpl->tpl_vars['smarty_vars']->value['company'] == 'tps') {?>
                <a class="navbar-brand" href="/"><strong style="color:red;">[</strong> TPS <strong style="color:red;">]</strong></a>
            <?php } else { ?>
                <a class="navbar-brand" href="/"><strong>[</strong> <?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['software_name'])===null||$tmp==='' ? "XXX" : $tmp);?>
 <strong>]</strong></a>
            <?php }?>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="/">Startseite</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="javascript:;" data-toggle="dropdown">Schichten</a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="/schichten">Schichtplaner öffnen</a>
                            <?php if (($_smarty_tpl->tpl_vars['smarty_vars']->value['current_user']['usergroup']->hasRight('auftraege_alle_kunden') || $_smarty_tpl->tpl_vars['smarty_vars']->value['current_user']['usergroup']->hasRight('auftraege_bestimmte_kunden'))) {?>
                                <a class="dropdown-item" href="/auftraege">Aufträge erstellen</a>
                            <?php }?>
                        </div>
                    </li>
                    <?php if (($_smarty_tpl->tpl_vars['smarty_vars']->value['current_user']['usergroup']->hasRight('kundendaten') || $_smarty_tpl->tpl_vars['smarty_vars']->value['current_user']['usergroup']->hasRight('mitarbeiterliste') || $_smarty_tpl->tpl_vars['smarty_vars']->value['current_user']['usergroup']->hasRight('dokumente_alle_kunden') || $_smarty_tpl->tpl_vars['smarty_vars']->value['current_user']['usergroup']->hasRight('dokumente_einsehen_bestimmte_kunden'))) {?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="javascript:;" data-toggle="dropdown">Kunden</a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <?php if ($_smarty_tpl->tpl_vars['smarty_vars']->value['current_user']['usergroup']->hasRight('kundendaten')) {?>
                                    <a class="dropdown-item" href="/kunden">Alle anzeigen</a>
                                    <a class="dropdown-item" href="/kunden/erstellen">Neu anlegen</a>
                                <?php }?>
                                <?php if ($_smarty_tpl->tpl_vars['smarty_vars']->value['company'] != 'tps') {?>
                                    <?php if ($_smarty_tpl->tpl_vars['smarty_vars']->value['current_user']['usergroup']->hasRight('kundendaten')) {?>
                                        <div class="dropdown-divider"></div>
                                    <?php }?>
                                    <?php if ($_smarty_tpl->tpl_vars['smarty_vars']->value['current_user']['usergroup']->hasRight('mitarbeiterliste')) {?>
                                        <a class="dropdown-item" href="/kunden/mitarbeiterliste">Mitarbeiterliste</a>
                                    <?php }?>
                                <?php }?>
                                <?php if (($_smarty_tpl->tpl_vars['smarty_vars']->value['current_user']['usergroup']->hasRight('dokumente_alle_kunden') || $_smarty_tpl->tpl_vars['smarty_vars']->value['current_user']['usergroup']->hasRight('dokumente_einsehen_bestimmte_kunden'))) {?>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="/kunden/dokumente">Dokumente</a>
                                <?php }?>
                            </div>
                        </li>
                    <?php }?>
                    <?php if ($_smarty_tpl->tpl_vars['smarty_vars']->value['current_user']['usergroup']->hasRight('mitarbeiter')) {?>
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
                                <?php if ($_smarty_tpl->tpl_vars['smarty_vars']->value['current_user']['usergroup']->hasRight('notizen')) {?>
                                    <a class="dropdown-item" href="/mitarbeiter/notizen">Notizen</a>
                                <?php }?>
                                <?php if ($_smarty_tpl->tpl_vars['smarty_vars']->value['company'] != 'tps') {?>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="/mitarbeiter/equalpay">Equal Pay</a>
                                <?php }?>
                            </div>
                        </li>
                    <?php }?>
                    <?php if (($_smarty_tpl->tpl_vars['smarty_vars']->value['current_user']['usergroup']->hasRight('berechnungen_lohn') || $_smarty_tpl->tpl_vars['smarty_vars']->value['current_user']['usergroup']->hasRight('berechnungen_stunden_bestimmte_kunden') || $_smarty_tpl->tpl_vars['smarty_vars']->value['current_user']['usergroup']->hasRight('berechnungen_stunden_alle_kunden'))) {?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="javascript:;" data-toggle="dropdown">Berechnungen</a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <?php if (($_smarty_tpl->tpl_vars['smarty_vars']->value['current_user']['usergroup']->hasRight('berechnungen_stunden_bestimmte_kunden') || $_smarty_tpl->tpl_vars['smarty_vars']->value['current_user']['usergroup']->hasRight('berechnungen_stunden_alle_kunden'))) {?>
                                    <a class="dropdown-item" href="/berechnungen/stunden">Stunden</a>
                                <?php }?>
                                <?php if (($_smarty_tpl->tpl_vars['smarty_vars']->value['current_user']['usergroup']->hasRight('berechnungen_lohn'))) {?>
                                    <a class="dropdown-item" href="/berechnungen/lohn">Lohn</a>
                                    <?php if ($_smarty_tpl->tpl_vars['smarty_vars']->value['company'] != 'tps') {?>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="/berechnungen/uebersicht">Übersicht</a>
                                    <?php }?>
                                <?php }?>
                            </div>
                        </li>
                    <?php }?>
                    <?php if ($_smarty_tpl->tpl_vars['smarty_vars']->value['current_user']['usergroup']->hasRight('rechnungen')) {?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="javascript:;" data-toggle="dropdown">Rechnungen</a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="/rechnungen/anzeigen">Alle anzeigen</a>
                                <a class="dropdown-item" href="/rechnungen/erstellen">Neu erstellen</a>
                            </div>
                        </li>
                    <?php }?>
                </ul>
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle username" href="javascript:;" data-toggle="dropdown">
                            <?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['current_user']['full_name'])===null||$tmp==='' ? "???" : $tmp);?>

                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <?php if ($_smarty_tpl->tpl_vars['smarty_vars']->value['current_user']['usergroup']->hasRight('eigenes_passwort_aendern')) {?>
                                <a class="dropdown-item" href="/benutzer/passwort">Passwort ändern</a>
                            <?php }?>
                            <a class="dropdown-item" href="/benutzer/abmelden">Abmelden</a>
                            <?php if (($_smarty_tpl->tpl_vars['smarty_vars']->value['current_user']['usergroup']->hasRight('benutzer_stufe1') || $_smarty_tpl->tpl_vars['smarty_vars']->value['current_user']['usergroup']->hasRight('benutzer_stufe2') || $_smarty_tpl->tpl_vars['smarty_vars']->value['current_user']['usergroup']->hasRight('benutzer_stufe3') || $_smarty_tpl->tpl_vars['smarty_vars']->value['current_user']['usergroup']->hasRight('benutzer_stufe4') || $_smarty_tpl->tpl_vars['smarty_vars']->value['current_user']['usergroup']->hasRight('benutzer_stufe5'))) {?>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="/benutzer">Benutzer</a>
                            <?php }?>
                            <?php if ($_smarty_tpl->tpl_vars['smarty_vars']->value['current_user']['usergroup']->hasRight('abteilungen')) {?>
                                <a class="dropdown-item" href="/abteilungen">Abteilungen</a>
                            <?php }?>
                            <?php if ($_smarty_tpl->tpl_vars['smarty_vars']->value['company'] != 'tps') {?>
                                <?php if ($_smarty_tpl->tpl_vars['smarty_vars']->value['current_user']['usergroup']->hasRight('tarife')) {?>
                                    <a class="dropdown-item" href="/tarife">Tarife</a>
                                <?php }?>
                            <?php }?>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>

        <?php if (!isset($_smarty_tpl->tpl_vars['hide_title']->value)) {?>
            <div class="row mb-4">
                <div class="col">
                    <div class="title-box text-white p-4">
                        <h3 class="mb-0"><?php echo (($tmp = @$_smarty_tpl->tpl_vars['title']->value)===null||$tmp==='' ? "Kein Titel vorhanden." : $tmp);
if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['time'])) {?> <small class="float-right hidden-sm-down"><?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['time'])===null||$tmp==='' ? '' : $tmp);?>
</small><?php }?></h3>
                    </div>
                </div>
            </div>
        <?php }?>

        <?php echo (($tmp = @$_smarty_tpl->smarty->ext->_capture->getBuffer($_smarty_tpl, 'content'))===null||$tmp==='' ? '' : $tmp);?>

    </div>

    <!-- jQuery JavaScript -->
    <?php echo '<script'; ?>
 src="/assets/vendors/jquery-3.2.1/jquery-3.2.1.min.js"><?php echo '</script'; ?>
>

    <!-- Tether JavaScript -->
    <?php echo '<script'; ?>
 src="/assets/vendors/tether-1.4.0/tether.min.js"><?php echo '</script'; ?>
>

    <!-- Bootstrap JavaScript -->
    <?php echo '<script'; ?>
 src="/assets/vendors/bootstrap-4.0.0-alpha.6-dist/js/bootstrap.min.js"><?php echo '</script'; ?>
>

    <!-- NProgress JavaScript -->
    <?php echo '<script'; ?>
 src="/assets/vendors/nprogress-master/nprogress.js"><?php echo '</script'; ?>
>

    <?php echo (($tmp = @$_smarty_tpl->tpl_vars['js']->value)===null||$tmp==='' ? '' : $tmp);?>


    <!-- Main JavaScript -->
    <?php echo '<script'; ?>
 src="/assets/main.js"><?php echo '</script'; ?>
>

    <?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['path_to_company_js'])) {?>
        <!-- Custom JavaScript -->
        <?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['smarty_vars']->value['path_to_company_js'];?>
"><?php echo '</script'; ?>
>
    <?php }?>
</body>
</html>
<?php }
}
