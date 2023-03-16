<?php
/* Smarty version 3.1.30, created on 2022-10-31 10:04:13
  from "/usr/local/www/apache24/noexec/ttact-intern-software/views/main/Rechnungen/pdf.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_635f8f8d52b214_89673741',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '2802b8bb6c3ee29911eb58e42a133296ebd6852c' => 
    array (
      0 => '/usr/local/www/apache24/noexec/ttact-intern-software/views/main/Rechnungen/pdf.tpl',
      1 => 1666997243,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_635f8f8d52b214_89673741 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!DOCTYPE html>
<html lang="de">
    <head>
    	<link href="assets/vendors/bootstrap-4.0.0-alpha.6-dist/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    	<?php echo '<script'; ?>
 src="assets/vendors/bootstrap-4.0.0-alpha.6-dist/js/bootstrap.min.js"><?php echo '</script'; ?>
>
    	<?php echo '<script'; ?>
 src="assets/vendors/jquery-3.2.1/jquery-3.2.1.min.js"><?php echo '</script'; ?>
>
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
                    <?php echo $_smarty_tpl->tpl_vars['smarty_vars']->value['rechnunglogo'];?>

                </div>
            </div>
            <br>
            <br>
            <br>
            <br><br>
            <div class="row p-0">
                <div class="col text-underline font-s-7">
                    <?php echo $_smarty_tpl->tpl_vars['smarty_vars']->value['absenderadresse'];?>

                </div>
            </div>
            <br>
            <div class="row p-0">
                <div class="col font-s-10" style="width:110mm;">
                    <p class="pb-0"><?php echo $_smarty_tpl->tpl_vars['smarty_vars']->value['rechnungsanschrift'];?>
</p>
                </div>
            </div>

            <div class="row p-0">
                <div class="col p-0 font-s-10">
                    <table class="table table-borderless p-0">
                        <tr>
                            <td class="p-0">&nbsp; </td>
                            <td class="text-right p-0" style="width:70mm;">Datum:</td>
                            <td class="text-right p-0"  style="width:30mm;"><?php echo $_smarty_tpl->tpl_vars['smarty_vars']->value['rechnungsdatum'];?>
</td>
                        </tr>
                        <tr>
                            <td class="p-0" style="padding-left: 15px !important;"><?php echo $_smarty_tpl->tpl_vars['smarty_vars']->value['kostenstelle'];?>
</td>
                            <td class="text-right p-0" >Rechnungs-Nr.:</td>
                            <td class="text-right p-0" ><?php echo $_smarty_tpl->tpl_vars['smarty_vars']->value['rechnungsnummer'];?>
</td>
                        </tr>
                        <tr>
                            <td class="p-0">&nbsp; </td>
                            <td class="text-right p-0">Leistungszeitraum:</td>
                            <td class="text-right p-0"><?php echo $_smarty_tpl->tpl_vars['smarty_vars']->value['leistungszeitraum'];?>
</td>
                        </tr>
                        <tr>
                            <td class="p-0">&nbsp; </td>
                            <td class="text-right p-0">Kundennummer:</td>
                            <td class="text-right p-0"><?php echo $_smarty_tpl->tpl_vars['smarty_vars']->value['kundennummer'];?>
</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="row p-0">
                <div class="col ">
                    <p class="font-weight-bold font-italic mb-1 font-s-12"><?php echo $_smarty_tpl->tpl_vars['smarty_vars']->value['rechnungtitle'];?>
</p>
                    <br>
                    <p class="font-s-10"><?php echo $_smarty_tpl->tpl_vars['smarty_vars']->value['anrede'];?>
</p>
                    <p class="font-s-10">für die von uns erbrachten Leistungen stellen wir Ihnen für den Zeitraum vom <?php echo $_smarty_tpl->tpl_vars['smarty_vars']->value['zeitraum_von'];?>
 bis<br>
                    <?php echo $_smarty_tpl->tpl_vars['smarty_vars']->value['zeitraum_bis'];?>
 nachfolgend, aufgeführte Rechnung</p><br>
                </div>
            </div>

            <?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['rechnungsliste'])) {?>
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
                            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['smarty_vars']->value['rechnungsliste'], 'row');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['row']->value) {
?>
                                <tr>
                                    <td class="p-0 font-s-10">  &nbsp;<?php echo $_smarty_tpl->tpl_vars['row']->value['postennummer'];?>
 </td>
                                    <td class="p-0 font-s-10">   &nbsp;<?php echo $_smarty_tpl->tpl_vars['row']->value['leistungsart'];?>
 </td>
                                    <td class="text-right p-0 font-s-10"> <?php echo $_smarty_tpl->tpl_vars['row']->value['menge'];?>
 </td>
                                    <td class="text-right p-0 font-s-10"> <?php echo $_smarty_tpl->tpl_vars['row']->value['einzelpreis'];?>
 </td>
                                    <td class="text-right p-0 font-s-10"> <?php echo $_smarty_tpl->tpl_vars['row']->value['gesamtpreis'];?>
 </td>
                                </tr>
                          <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

                       </table>
                    </div>
                </div>
                <br>
                <br>
            <?php }?>

            <?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['gesamtbetrag'])) {?>
                <div class="row p-0">
                    <div class="col font-s-10">
                        <table class="table table-bordered-dark">
                            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['smarty_vars']->value['gesamtbetrag'], 'row', false, 'i', 'gesamtbetrag', array (
  'last' => true,
  'iteration' => true,
  'total' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['i']->value => $_smarty_tpl->tpl_vars['row']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_gesamtbetrag']->value['iteration']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_gesamtbetrag']->value['last'] = $_smarty_tpl->tpl_vars['__smarty_foreach_gesamtbetrag']->value['iteration'] == $_smarty_tpl->tpl_vars['__smarty_foreach_gesamtbetrag']->value['total'];
?>
                                <tr>
                                    <td class="p-0 <?php if ((isset($_smarty_tpl->tpl_vars['__smarty_foreach_gesamtbetrag']->value['last']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_gesamtbetrag']->value['last'] : null)) {?> text-underline <?php }?>" style="<?php if ((isset($_smarty_tpl->tpl_vars['__smarty_foreach_gesamtbetrag']->value['last']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_gesamtbetrag']->value['last'] : null)) {?> padding-bottom:1px !important; <?php }?>">&nbsp;<?php echo $_smarty_tpl->tpl_vars['row']->value['bezeichnung'];?>
</td>
                                    <td class="text-right p-0 <?php if ((isset($_smarty_tpl->tpl_vars['__smarty_foreach_gesamtbetrag']->value['last']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_gesamtbetrag']->value['last'] : null)) {?> text-underline <?php }?>" style="width:35mm;"><?php echo $_smarty_tpl->tpl_vars['row']->value['betrag'];?>
&nbsp;</td>
                                </tr>
                            <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

                        </table>
                   </div>
                </div>
                <br>
                <br>
            <?php }?>

            <div class="row p-0">
                <div class="col font-s-10">
                    <p class="">Der ausgewiesene Rechnungsbetrag ist zum <?php echo $_smarty_tpl->tpl_vars['smarty_vars']->value['zahlungsziel'];?>
 zur Zahlung fällig. Sollte der Rechnungsbetrag von Ihnen nicht innerhalb von 30 Tagen nach Eintritt der Fälligkeit und Zugang dieser Rechnung vollständig bezahlt werden, geraten Sie automatisch in Verzug, ohne dass es einer gesonderten Mahnung bedarf. Hierdurch anfallende zusätzliche Kosten sind von Ihnen zu erstatten.</p><br>
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
                                        <?php echo $_smarty_tpl->tpl_vars['smarty_vars']->value['inhabertitel'];?>
<br>
                                        <?php echo $_smarty_tpl->tpl_vars['smarty_vars']->value['inhaber'];?>
<br>
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
                                        USt-Id: <?php echo $_smarty_tpl->tpl_vars['smarty_vars']->value['umsatzsteuerid'];?>
<br>
                                        Amtsgericht: Charlottenburg<br>
                                        <?php echo $_smarty_tpl->tpl_vars['smarty_vars']->value['handelsregisternummer'];?>
<br>
                                    </p>
                                </td>
                                <td class="p-0" style="width:45mm;">
                                    <p class="p-0 font-s-7">
                                        Bankverbindung<br>
                                        Berliner Volksbank<br>
                                        Kto.<?php echo $_smarty_tpl->tpl_vars['smarty_vars']->value['kontonummer'];?>
 BLZ: 10090000<br>
                                        IBAN: <?php echo $_smarty_tpl->tpl_vars['smarty_vars']->value['iban'];?>
<br>
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html><?php }
}
