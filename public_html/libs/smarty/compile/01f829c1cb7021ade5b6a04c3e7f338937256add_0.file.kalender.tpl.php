<?php
/* Smarty version 3.1.30, created on 2019-07-11 08:58:37
  from "/usr/local/www/apache24/noexec/ttact-intern-software/views/main/Mitarbeiter/kalender.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5d26de1db26530_62554750',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '01f829c1cb7021ade5b6a04c3e7f338937256add' => 
    array (
      0 => '/usr/local/www/apache24/noexec/ttact-intern-software/views/main/Mitarbeiter/kalender.tpl',
      1 => 1562525867,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5d26de1db26530_62554750 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_assignInScope('title', "Mitarbeiter | Kalender" ,false ,2);
?>

<div class="row mb-4">
    <div class="col-12 col-md-7 col-xl-8 mb-4 mb-md-0">
        <div class="content-box">
            <h3 class="mb-0 py-md-1">Mitarbeiter Nr. <?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['personalnummer'])===null||$tmp==='' ? "???" : $tmp);?>
: <strong><?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['vorname'])===null||$tmp==='' ? '' : $tmp);?>
 <?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['nachname'])===null||$tmp==='' ? '' : $tmp);?>
</strong></h3>
        </div>
    </div>

    <div class="col-12 col-md-5 col-xl-4">
        <div class="content-box">
            <?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['mitarbeiterliste'])) {?>
                <div class="input-group">
                    <div class="input-group-btn">
                        <a id="mitarbeiter_prev" type="button" class="btn btn-secondary" href="javascript:;">
                            <i class="fa fa-angle-left"></i>
                        </a>
                    </div>

                    <select class="form-control selectable" id="mitarbeiterswitch" tabindex="-1" required>
                        <option value="">-- bitte auswählen</option>
                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['smarty_vars']->value['mitarbeiterliste'], 'row');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['row']->value) {
?>
                            <option value="<?php echo $_smarty_tpl->tpl_vars['row']->value['personalnummer'];?>
" <?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['personalnummer'])) {?> <?php if ($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['personalnummer'] == $_smarty_tpl->tpl_vars['row']->value['personalnummer']) {?> selected<?php }
}?>><?php echo $_smarty_tpl->tpl_vars['row']->value['personalnummer'];?>
 - <?php echo $_smarty_tpl->tpl_vars['row']->value['nachname'];?>
, <?php echo $_smarty_tpl->tpl_vars['row']->value['vorname'];?>
</option>
                        <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

                    </select>

                    <div class="input-group-btn">
                        <a id="mitarbeiter_next" type="button" class="btn btn-secondary" href="javascript:;">
                            <i class="fa fa-angle-right"></i>
                        </a>
                    </div>
                </div>
            <?php }?>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-12 col-lg-8 col-xl-9 mb-4">
        <div class="content-box">
            <div id="calendar"></div>
        </div>
    </div>
    <div class="col-12 col-lg-4 col-xl-3">
        <div class="content-box">
            <h2 class="mb-3">Daten</h2>

            <h5 class="mb-3">Vertragliche Daten</h5>

            <div class="row">
                <div class="col-9">Eintritt</div>
                <div class="col-3 text-right" id="allgemein_eintritt">???</div>
            </div>
            <div class="row">
                <div class="col-9">Austritt</div>
                <div class="col-3 text-right" id="allgemein_austritt">???</div>
            </div>
            <div class="row">
                <div class="col-9">Wochenstunden</div>
                <div class="col-3 text-right" id="allgemein_wochenstunden">???</div>
            </div>

            <?php if ($_smarty_tpl->tpl_vars['smarty_vars']->value['current_user']['usergroup']->hasRight('lohnrelevante_stunden')) {?>
                <h5 class="mb-3 mt-4">Lohnrelevante Stunden</h5>

                <h6><strong>Ist</strong></h6>

                <div class="row">
                    <div class="col-9">Krank</div>
                    <div class="col-3 text-right" id="stunden_krank">???</div>
                </div>
                <div class="row">
                    <div class="col-9">Urlaub</div>
                    <div class="col-3 text-right" id="stunden_urlaub">???</div>
                </div>
                <?php if ($_smarty_tpl->tpl_vars['smarty_vars']->value['company'] != 'tps') {?>
                    <div class="row">
                        <div class="col-9">Feiertag</div>
                        <div class="col-3 text-right" id="stunden_feiertag">???</div>
                    </div>
                <?php }?>
                <div class="row">
                    <div class="col-9">Import</div>
                    <div class="col-3 text-right" id="stunden_import">???</div>
                </div>
                <div class="row">
                    <div class="col-9">Schichten</div>
                    <div class="col-3 text-right" id="stunden_schichten">???</div>
                </div>
                <div class="row">
                    <div class="col-9">Insgesamt</div>
                    <div class="col-3 text-right" id="stunden_insgesamt">???</div>
                </div>

                <h6 class="mt-3"><strong>Soll</strong></h6>

                <div class="row">
                    <div class="col-9">Insgesamt</div>
                    <div class="col-3 text-right" id="stunden_soll">???</div>
                </div>
                <?php if ($_smarty_tpl->tpl_vars['smarty_vars']->value['company'] != 'tps') {?>
                    <div class="row">
                        <div class="col-9">Mehrarbeitszuschläge ab</div>
                        <div class="col-3 text-right" id="stunden_mehrarbeit">???</div>
                    </div>
                <?php }?>

                <h6 class="mt-3"><strong>Differenz</strong></h6>

                <div class="row">
                    <div class="col-9">bis Soll-Stunden</div>
                    <div class="col-3 text-right" id="differenz_bis_insgesamt">???</div>
                </div>
                <?php if ($_smarty_tpl->tpl_vars['smarty_vars']->value['company'] != 'tps') {?>
                    <div class="row">
                        <div class="col-9">bis Mehrarbeitszuschläge</div>
                        <div class="col-3 text-right" id="differenz_bis_mehrarbeit">???</div>
                    </div>
                <?php }?>

                <h5 class="mb-3 mt-4">&#216; Stunden/Tag letzte 13 Wochen</h5>
                <div class="row">
                    <div class="col-9">Alle</div>
                    <div class="col-3 text-right" id="tagessoll_alle">???</div>
                </div>
                <?php if ($_smarty_tpl->tpl_vars['smarty_vars']->value['company'] != 'tps') {?>
                    <div class="row">
                        <div class="col-9">Montag</div>
                        <div class="col-3 text-right" id="tagessoll_montag">???</div>
                    </div>
                    <div class="row">
                        <div class="col-9">Dienstag</div>
                        <div class="col-3 text-right" id="tagessoll_dienstag">???</div>
                    </div>
                    <div class="row">
                        <div class="col-9">Mittwoch</div>
                        <div class="col-3 text-right" id="tagessoll_mittwoch">???</div>
                    </div>
                    <div class="row">
                        <div class="col-9">Donnerstag</div>
                        <div class="col-3 text-right" id="tagessoll_donnerstag">???</div>
                    </div>
                    <div class="row">
                        <div class="col-9">Freitag</div>
                        <div class="col-3 text-right" id="tagessoll_freitag">???</div>
                    </div>
                    <div class="row">
                        <div class="col-9">Samstag</div>
                        <div class="col-3 text-right" id="tagessoll_samstag">???</div>
                    </div>
                    <div class="row">
                        <div class="col-9">Sonntag</div>
                        <div class="col-3 text-right" id="tagessoll_sonntag">???</div>
                    </div>
                <?php }?>

                <?php if ($_smarty_tpl->tpl_vars['smarty_vars']->value['company'] != 'tps') {?>
                    <h5 class="mb-3 mt-4">Arbeitszeitkonto</h5>

                    <div class="row">
                        <div class="col-9">Letzter Monat</div>
                        <div class="col-3 text-right" id="azk_letzter_monat">???</div>
                    </div>
                    <div class="row">
                        <div class="col-9">Dieser Monat</div>
                        <div class="col-3 text-right" id="azk_dieser_monat">???</div>
                    </div>
                <?php }?>
            <?php }?>
        </div>
    </div>
</div>

<div class="modal fade" id="bearbeiten-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Kalendereintrag bearbeiten</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="bearbeiten-error" class="alert alert-danger p-3" style="display:none;"></div>
                <div id="bearbeiten-kalendereintrag">
                    <div class="row">
                        <div class="form-group col-12 col-sm-6">
                            <label class="form-control-label" for="bearbeiten-von">Von</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-calendar fa-fw"></i></span>
                                <input type="text" class="form-control" id="bearbeiten-von" value="" placeholder="TT.MM.JJJJ">
                            </div>
                        </div>
                        <div class="form-group col-12 col-sm-6">
                            <label class="form-control-label" for="bearbeiten-bis">Bis</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-calendar fa-fw"></i></span>
                                <input type="text" class="form-control" id="bearbeiten-bis" value="" placeholder="TT.MM.JJJJ">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-12">
                            <label class="form-control-label" for="bearbeiten-bezeichnung">Bezeichnung</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                <input type="text" class="form-control" id="bearbeiten-bezeichnung" value="" placeholder="Beispielbezeichnung">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-12">
                            <label class="form-control-label" for="bearbeiten-art">Art</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-cog fa-fw"></i></span>
                                <select class="form-control selectable" id="bearbeiten-art">
                                    <option value="krank_bezahlt">Krank (wird bezahlt)</option>
                                    <option value="urlaub_bezahlt">Urlaub (wird bezahlt)</option>
                                    <option value="kind_krank">Kind krank</option>
                                    <option value="weiterbildung">Weiterbildung</option>
                                    <option value="frei">Frei</option>
                                    <option value="krank_unbezahlt">Krank (unbezahlt)</option>
                                    <option value="unentschuldigt_fehlen">Unentschuldigtes Fehlen</option>
                                    <option value="feiertag_bezahlt">Feiertag (<?php if ($_smarty_tpl->tpl_vars['smarty_vars']->value['company'] == 'tps') {?>un<?php }?>bezahlt)</option>
                                    <?php if ($_smarty_tpl->tpl_vars['smarty_vars']->value['company'] != 'tps') {?>
                                        <option value="fehlzeit">Fehlzeit</option>
                                    <?php }?>
                                    <option value="unbekannt">Unbekannt</option>
                                    <option value="urlaub_genehmigt">Urlaub genehmigt (unbezahlt)</option>
                                    <option value="krank">Krank (unbezahlt)</option>
                                    <option value="urlaub_unbezahlt">Urlaub (unbezahlt)</option>
                                    <?php if ($_smarty_tpl->tpl_vars['smarty_vars']->value['company'] != 'tps') {?>
                                        <option value="benachbarten_feiertag_bezahlen">Benachbarten Feiertag bezahlen (unbezahlt)</option>
                                    <?php }?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="bearbeiten-stundenimporteintrag" style="display:none;">
                    <div class="row">
                        <div class="form-group col-12">
                            <label class="form-control-label" for="bearbeiten-datum">Datum</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-calendar fa-fw"></i></span>
                                <input type="text" class="form-control datum" id="bearbeiten-datum" value="" placeholder="TT.MM.JJJJ">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-12 col-sm-6">
                            <label class="form-control-label" for="bearbeiten-uhrzeit-von">Von</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-clock-o fa-fw"></i></span>
                                <input type="text" class="form-control inputmask text-center" id="bearbeiten-uhrzeit-von" data-inputmask="'mask' : '99:99'">
                            </div>
                        </div>
                        <div class="form-group col-12 col-sm-6">
                            <label class="form-control-label" for="bearbeiten-uhrzeit-bis">Bis</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-clock-o fa-fw"></i></span>
                                <input type="text" class="form-control inputmask text-center" id="bearbeiten-uhrzeit-bis" data-inputmask="'mask' : '99:99'">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-12">
                            <label class="form-control-label" for="bearbeiten-pause">Pause</label>
                            <div class="btn-group d-flex w-100" role="group" id="bearbeiten-pause-buttons">
                                <button class="btn btn-secondary w-100 px-0" type="button" data-value="00:00">00</button>
                                <button class="btn btn-secondary w-100 px-0" type="button" data-value="00:15">15</button>
                                <button class="btn btn-secondary w-100 px-0" type="button" data-value="00:30">30</button>
                                <button class="btn btn-secondary w-100 px-0" type="button" data-value="00:45">45</button>
                                <button class="btn btn-secondary w-100 px-0" type="button" data-value="01:00">60</button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-12">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-pause-circle fa-fw"></i></span>
                                <input type="text" class="form-control inputmask text-center" id="bearbeiten-pause" data-inputmask="'mask' : '99:99'">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-12">
                            <label class="form-control-label" for="bearbeiten-kunde">Kunde</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-user fa-fw"></i></span>
                                <select class="form-control selectable" id="bearbeiten-kunde" tabindex="-1">
                                    <option value="">-- bitte auswählen</option>
                                    <?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['kundenliste'])) {?>
                                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['smarty_vars']->value['kundenliste'], 'row');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['row']->value) {
?>
                                            <option value="<?php echo $_smarty_tpl->tpl_vars['row']->value['id'];?>
" <?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['kunde'])) {?> <?php if ($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['kunde'] == $_smarty_tpl->tpl_vars['row']->value['id']) {?> selected<?php }
}?>><?php echo $_smarty_tpl->tpl_vars['row']->value['kundennummer'];?>
 - <?php echo $_smarty_tpl->tpl_vars['row']->value['name'];?>
</option>
                                        <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

                                    <?php }?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" id="bearbeiten-id">
                <button type="button" class="btn btn-danger mr-auto" id="bearbeiten-loeschen">Löschen</button>
                <button type="button" class="btn btn-primary" id="bearbeiten-speichern">Speichern</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Abbrechen</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="erstellen-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Kalendereintrag erstellen</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
				<div class="btn-group d-flex w-100" data-toggle="buttons">
				  <label class="btn btn-secondary active w-100" id="erstellen-typ-kalendereintrag">
					<input type="radio" name="erstellen-typ" autocomplete="off" checked> Kalendereintrag
				  </label>
				  <label class="btn btn-secondary w-100" id="erstellen-typ-stundenimporteintrag">
					<input type="radio" name="erstellen-typ" autocomplete="off"> Stundenimporteintrag
				  </label>
				</div>

                <div id="erstellen-error" class="alert alert-danger p-3" style="display:none;"></div>
                <div id="erstellen-kalendereintrag">
                    <div class="row">
                        <div class="form-group col-12 col-sm-6">
                            <label class="form-control-label" for="erstellen-von">Von</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-calendar fa-fw"></i></span>
                                <input type="text" class="form-control datum" id="erstellen-von" value="" placeholder="TT.MM.JJJJ">
                            </div>
                        </div>
                        <div class="form-group col-12 col-sm-6">
                            <label class="form-control-label" for="erstellen-bis">Bis</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-calendar fa-fw"></i></span>
                                <input type="text" class="form-control datum" id="erstellen-bis" value="" placeholder="TT.MM.JJJJ">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-12">
                            <label class="form-control-label" for="erstellen-bezeichnung">Bezeichnung</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                <input type="text" class="form-control" id="erstellen-bezeichnung" value="" placeholder="Beispielbezeichnung">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-12">
                            <label class="form-control-label" for="erstellen-art">Art</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-cog fa-fw"></i></span>
                                <select class="form-control selectable" id="erstellen-art">
                                    <option value="">-- bitte auswählen</option>
                                    <option value="krank_bezahlt">Krank (wird bezahlt)</option>
                                    <option value="urlaub_bezahlt">Urlaub (wird bezahlt)</option>
                                    <option value="kind_krank">Kind krank</option>
                                    <option value="weiterbildung">Weiterbildung</option>
                                    <option value="frei">Frei</option>
                                    <option value="krank_unbezahlt">Krank (unbezahlt)</option>
                                    <option value="unentschuldigt_fehlen">Unentschuldigtes Fehlen</option>
                                    <option value="feiertag_bezahlt">Feiertag (<?php if ($_smarty_tpl->tpl_vars['smarty_vars']->value['company'] == 'tps') {?>un<?php }?>bezahlt)</option>
                                    <?php if ($_smarty_tpl->tpl_vars['smarty_vars']->value['company'] != 'tps') {?>
                                        <option value="fehlzeit">Fehlzeit</option>
                                    <?php }?>
                                    <option value="unbekannt">Unbekannt</option>
                                    <option value="urlaub_genehmigt">Urlaub genehmigt (unbezahlt)</option>
                                    <option value="krank">Krank (unbezahlt)</option>
                                    <option value="urlaub_unbezahlt">Urlaub (unbezahlt)</option>
                                    <?php if ($_smarty_tpl->tpl_vars['smarty_vars']->value['company'] != 'tps') {?>
                                        <option value="benachbarten_feiertag_bezahlen">Benachbarten Feiertag bezahlen (unbezahlt)</option>
                                    <?php }?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="erstellen-stundenimporteintrag" style="display:none;">
                    <div class="row">
                        <div class="form-group col-12">
                            <label class="form-control-label" for="erstellen-datum">Datum</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-calendar fa-fw"></i></span>
                                <input type="text" class="form-control datum" id="erstellen-datum" value="" placeholder="TT.MM.JJJJ">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-12 col-sm-6">
                            <label class="form-control-label" for="erstellen-uhrzeit-von">Von</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-clock-o fa-fw"></i></span>
                                <input type="text" class="form-control inputmask text-center" id="erstellen-uhrzeit-von" data-inputmask="'mask' : '99:99'">
                            </div>
                        </div>
                        <div class="form-group col-12 col-sm-6">
                            <label class="form-control-label" for="erstellen-uhrzeit-bis">Bis</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-clock-o fa-fw"></i></span>
                                <input type="text" class="form-control inputmask text-center" id="erstellen-uhrzeit-bis" data-inputmask="'mask' : '99:99'">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-12">
                            <label class="form-control-label" for="erstellen-pause">Pause</label>
                            <div class="btn-group d-flex w-100" role="group" id="erstellen-pause-buttons">
                                <button class="btn btn-secondary w-100 px-0" type="button" data-value="00:00">00</button>
                                <button class="btn btn-secondary w-100 px-0" type="button" data-value="00:15">15</button>
                                <button class="btn btn-secondary w-100 px-0" type="button" data-value="00:30">30</button>
                                <button class="btn btn-secondary w-100 px-0" type="button" data-value="00:45">45</button>
                                <button class="btn btn-secondary w-100 px-0" type="button" data-value="01:00">60</button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-12">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-pause-circle fa-fw"></i></span>
                                <input type="text" class="form-control inputmask text-center" id="erstellen-pause" data-inputmask="'mask' : '99:99'">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-12">
                            <label class="form-control-label" for="erstellen-kunde">Kunde</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-user fa-fw"></i></span>
                                <select class="form-control selectable" id="erstellen-kunde" tabindex="-1">
                                    <option value="">-- bitte auswählen</option>
                                    <?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['kundenliste'])) {?>
                                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['smarty_vars']->value['kundenliste'], 'row');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['row']->value) {
?>
                                            <option value="<?php echo $_smarty_tpl->tpl_vars['row']->value['id'];?>
" <?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['kunde'])) {?> <?php if ($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['kunde'] == $_smarty_tpl->tpl_vars['row']->value['id']) {?> selected<?php }
}?>><?php echo $_smarty_tpl->tpl_vars['row']->value['kundennummer'];?>
 - <?php echo $_smarty_tpl->tpl_vars['row']->value['name'];?>
</option>
                                        <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

                                    <?php }?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="erstellen-speichern">Speichern</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Abbrechen</button>
            </div>
        </div>
    </div>
</div>

<?php $_smarty_tpl->smarty->ext->_capture->open($_smarty_tpl, 'styles', null, null);
?>

    <!-- fullcalendar -->
    <link rel="stylesheet" href="/assets/vendors/fullcalendar-3.4.0/fullcalendar.min.css">

    <!-- daterangepicker -->
    <link href="/assets/vendors/bootstrap-daterangepicker-master/daterangepicker.css" rel="stylesheet">

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
<?php $_smarty_tpl->smarty->ext->_capture->close($_smarty_tpl);
?>


<?php $_smarty_tpl->_assignInScope('css', $_smarty_tpl->smarty->ext->_capture->getBuffer($_smarty_tpl, 'styles') ,false ,2);
?>

<?php $_smarty_tpl->smarty->ext->_capture->open($_smarty_tpl, 'scripts', null, null);
?>

    <!-- jquery.inputmask -->
    <?php echo '<script'; ?>
 src="/assets/vendors/jquery.inputmask/dist/min/jquery.inputmask.bundle.min.js"><?php echo '</script'; ?>
>

    <!-- fullcalendar -->
    <?php echo '<script'; ?>
 src="/assets/vendors/fullcalendar-3.4.0/lib/moment.min.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 src="/assets/vendors/fullcalendar-3.4.0/fullcalendar.min.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 src="/assets/vendors/fullcalendar-3.4.0/locale/de.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
>
        function updateInfo (view, element) {
            $.ajax({
                type: "POST",
                url: "/mitarbeiter/ajax",
                data: {
                    type: 'get_tagessoll', 
                    mitarbeiter: '<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['personalnummer'])===null||$tmp==='' ? "???" : $tmp);?>
', 
                    jahr: view.intervalStart.format("YYYY"), 
                    monat: view.intervalStart.format("MM")
                },
                success: function ($return) {
                    if ($return.hasOwnProperty('status')) {
                        if ($return.status == "success") {
                            $("#allgemein_eintritt").html($return.allgemein.eintritt);
                            $("#allgemein_austritt").html($return.allgemein.austritt);
                            $("#allgemein_wochenstunden").html($return.allgemein.wochenstunden);

                            $("#mitarbeiter_prev").attr('href', '/mitarbeiter/kalender/' + $return.mitarbeiter.prev + '/' + view.intervalStart.format("YYYY") + '/' + view.intervalStart.format("MM"));
                            $("#mitarbeiter_next").attr('href', '/mitarbeiter/kalender/' + $return.mitarbeiter.next + '/' + view.intervalStart.format("YYYY") + '/' + view.intervalStart.format("MM"));

                            <?php if ($_smarty_tpl->tpl_vars['smarty_vars']->value['current_user']['usergroup']->hasRight('lohnrelevante_stunden')) {?>
                                $("#tagessoll_alle").html($return.tagessoll.alle);
                                <?php if ($_smarty_tpl->tpl_vars['smarty_vars']->value['company'] != 'tps') {?>
                                    $("#tagessoll_montag").html($return.tagessoll.montag);
                                    $("#tagessoll_dienstag").html($return.tagessoll.dienstag);
                                    $("#tagessoll_mittwoch").html($return.tagessoll.mittwoch);
                                    $("#tagessoll_donnerstag").html($return.tagessoll.donnerstag);
                                    $("#tagessoll_freitag").html($return.tagessoll.freitag);
                                    $("#tagessoll_samstag").html($return.tagessoll.samstag);
                                    $("#tagessoll_sonntag").html($return.tagessoll.sonntag);

                                    $("#azk_letzter_monat").html($return.azk.letzter_monat);
                                    $("#azk_dieser_monat").html($return.azk.dieser_monat);

                                    $("#stunden_feiertag").html($return.stunden.feiertag);

                                    $("#stunden_mehrarbeit").html($return.stunden.mehrarbeit);

                                    $("#differenz_bis_mehrarbeit").html($return.differenz.mehrarbeit);
                                <?php }?>

                                $("#stunden_krank").html($return.stunden.krank);
                                $("#stunden_urlaub").html($return.stunden.urlaub);
                                $("#stunden_import").html($return.stunden.import);
                                $("#stunden_schichten").html($return.stunden.schichten);
                                $("#stunden_insgesamt").html($return.stunden.insgesamt);

                                $("#stunden_soll").html($return.stunden.soll);

                                $("#differenz_bis_insgesamt").html($return.differenz.insgesamt);
                            <?php }?>
                        } else if ($return.status == "not_logged_in") {
                            location.reload();
                        } else {
                            alert("Es ist ein Fehler aufgetreten.");
                        } 
                    } else {
                        alert("Es ist ein Fehler aufgetreten.");
                    }
                },
                error: function () {
                    alert("Es ist ein Fehler aufgetreten.");
                }
            });
        }

        $(document).ready(function() {
            $('#calendar').fullCalendar({
                defaultDate: moment('<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['year'])===null||$tmp==='' ? '' : $tmp);?>
-<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['month'])===null||$tmp==='' ? '' : $tmp);?>
-01'),
                eventSources: [
                    {
                        url: '/mitarbeiter/ajax',
                        type: 'POST',
                        data: {
                            type: 'get',
                            mitarbeiter: '<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['personalnummer'])===null||$tmp==='' ? "???" : $tmp);?>
'
                        }
                    }
                ],
                displayEventEnd: true,
                weekNumbers: true,
                eventClick: function (event, jsEvent, view) {
                    if (event.hasOwnProperty('id')) {
                        if (event.type == 'stundenimporteintrag') {
                            $("#bearbeiten-error").html("").hide();
                            $("#bearbeiten-id").val(event.id);
                            $("#bearbeiten-datum").val(event.datum);
                            $("#bearbeiten-uhrzeit-von").val(event.von_uhrzeit);
                            $("#bearbeiten-uhrzeit-bis").val(event.bis_uhrzeit);
                            $("#bearbeiten-pause").val(event.pause);
                            $("#bearbeiten-kunde").val(event.kunde).change();
                            $("#bearbeiten-kalendereintrag").hide();
                            $("#bearbeiten-stundenimporteintrag").show();
                            $('#bearbeiten-modal').modal();
                        } else if (event.hasOwnProperty('bezeichnung')) {
                            $("#bearbeiten-error").html("").hide();
                            $("#bearbeiten-id").val(event.id);
                            $("#bearbeiten-von").val(event.von);
                            $("#bearbeiten-bis").val(event.bis);
                            $("#bearbeiten-bezeichnung").val(event.bezeichnung);
                            $("#bearbeiten-art").val(event.type).change();
                            $("#bearbeiten-kalendereintrag").show();
                            $("#bearbeiten-stundenimporteintrag").hide();
                            $('#bearbeiten-modal').modal();
                        }
                    }
                },
                dayClick: function (date, jsEvent, view) {
                    if (date.format('DD.MM.YYYY').match(/[0-3][0-9][.][0-1][0-9][.][1-2][0-9][0-9][0-9]/)) {
                        $("#erstellen-error").html("").hide();
                        $("#erstellen-von").val(date.format('DD.MM.YYYY'));
                        $("#erstellen-bis").val(date.format('DD.MM.YYYY'));
                        $("#erstellen-datum").val(date.format('DD.MM.YYYY'));
                        $('#erstellen-modal').modal();
                    }
                },
                viewRender: updateInfo
            });

            $("#bearbeiten-speichern").click(function () {
                if ($("#bearbeiten-stundenimporteintrag").is(":hidden")) {
                    var von = $("#bearbeiten-von").val();
                    var bis = $("#bearbeiten-bis").val();
                    if ($("#bearbeiten-id").val().length == 0) {
                        $("#bearbeiten-error").html("Es ist ein technischer Fehler aufgetreten.").show();
                    } else if (von.length != 10 || !von.match(/[0-3][0-9][.][0-1][0-9][.][1-2][0-9][0-9][0-9]/)) {
                        $("#bearbeiten-error").html("Das Datum im Feld <strong>Von</strong> ist ungültig.").show();
                    } else if (bis.length != 10 || !bis.match(/[0-3][0-9][.][0-1][0-9][.][1-2][0-9][0-9][0-9]/)) {
                        $("#bearbeiten-error").html("Das Datum im Feld <strong>Bis</strong> ist ungültig.").show();
                    } else if ($("#bearbeiten-art").val().length == 0) {
                        $("#bearbeiten-error").html("Bitte geben Sie eine Art für diesen Kalendereintrag ein.").show();
                    } else {
                        $.ajax({
                            type: "POST",
                            url: "/mitarbeiter/ajax",
                            data: {
                                type: 'set', 
                                id: $("#bearbeiten-id").val(), 
                                von: $("#bearbeiten-von").val(), 
                                bis: $("#bearbeiten-bis").val(), 
                                bezeichnung: $("#bearbeiten-bezeichnung").val(), 
                                art: $("#bearbeiten-art").val()
                            },
                            success: function ($return) {
                                if ($return.hasOwnProperty('status')) {
                                    if ($return.status == "success") {
                                        $('#bearbeiten-modal').modal('hide');
                                        $("#bearbeiten-error").html("").hide();
                                        $("#calendar").fullCalendar('refetchEvents');
                                        updateInfo($("#calendar").fullCalendar('getView'), null);
                                    } else if ($return.status == "not_logged_in") {
                                        location.reload();
                                    } else {
                                        $("#bearbeiten-error").html("Es ist ein Fehler aufgetreten.").show();
                                    } 
                                } else {
                                    $("#bearbeiten-error").html("Es ist ein Fehler aufgetreten.").show();
                                }
                            },
                            error: function () {
                                $("#bearbeiten-error").html("Es ist ein Fehler aufgetreten.").show();
                            }
                        });
                    }
                } else {
                    var datum = $("#bearbeiten-datum").val();
                    var von = $("#bearbeiten-uhrzeit-von").val().replace(/\D/g,'');
                    var bis = $("#bearbeiten-uhrzeit-bis").val().replace(/\D/g,'');
                    var pause = $("#bearbeiten-pause").val().replace(/\D/g,'');

                    if (datum.length != 10 || !datum.match(/[0-3][0-9][.][0-1][0-9][.][1-2][0-9][0-9][0-9]/)) {
                        $("#bearbeiten-error").html("Das Datum ist ungültig.").show();
                    } else if (von.length != 4 || !von.match(/^[0-2][0-9][0-5][0-9]/)) {
                        $("#bearbeiten-error").html("Bitte geben Sie die Von-Uhrzeit an.").show();
                    } else if (bis.length != 4 || !bis.match(/^[0-2][0-9][0-5][0-9]/)) {
                        $("#bearbeiten-error").html("Bitte geben Sie die Bis-Uhrzeit an.").show();
                    } else if (pause.length != 4 || !pause.match(/^0[0-9][0-5][0-9]/)) {
                        $("#bearbeiten-error").html("Bitte geben Sie die Pause an.").show();
                    } else if ($("#bearbeiten-kunde").val().length == 0) {
                        $("#bearbeiten-error").html("Bitte geben Sie den Kunden an.").show();
                    } else {
                        $.ajax({
                            type: "POST",
                            url: "/mitarbeiter/ajax",
                            data: {
                                type: 'set_stundenimporteintrag', 
                                id: $("#bearbeiten-id").val(), 
                                datum: $("#bearbeiten-datum").val(), 
                                von: von, 
                                bis: bis, 
                                pause: pause, 
                                kunde: $("#bearbeiten-kunde").val()
                            },
                            success: function ($return) {
                                if ($return.hasOwnProperty('status')) {
                                    if ($return.status == "success") {
                                        $('#bearbeiten-modal').modal('hide');
                                        $("#bearbeiten-error").html("").hide();
                                        $("#calendar").fullCalendar('refetchEvents');
                                        updateInfo($("#calendar").fullCalendar('getView'), null);
                                    } else if ($return.status == "not_logged_in") {
                                        location.reload();
                                    } else {
                                        $("#bearbeiten-error").html("Es ist ein Fehler aufgetreten.").show();
                                    } 
                                } else {
                                    $("#bearbeiten-error").html("Es ist ein Fehler aufgetreten.").show();
                                }
                            },
                            error: function () {
                                $("#bearbeiten-error").html("Es ist ein Fehler aufgetreten.").show();
                            }
                        });
                    }
                }
            });

            $("#erstellen-speichern").click(function () {
                if ($('#erstellen-typ-stundenimporteintrag > input').prop('checked')) {
                    var datum = $("#erstellen-datum").val();
                    var von = $("#erstellen-uhrzeit-von").val().replace(/\D/g,'');
                    var bis = $("#erstellen-uhrzeit-bis").val().replace(/\D/g,'');
                    var pause = $("#erstellen-pause").val().replace(/\D/g,'');

                    if (datum.length != 10 || !datum.match(/[0-3][0-9][.][0-1][0-9][.][1-2][0-9][0-9][0-9]/)) {
                        $("#erstellen-error").html("Das Datum ist ungültig.").show();
                    } else if (von.length != 4 || !von.match(/^[0-2][0-9][0-5][0-9]/)) {
                        $("#erstellen-error").html("Bitte geben Sie die Von-Uhrzeit an.").show();
                    } else if (bis.length != 4 || !bis.match(/^[0-2][0-9][0-5][0-9]/)) {
                        $("#erstellen-error").html("Bitte geben Sie die Bis-Uhrzeit an.").show();
                    } else if (pause.length != 4 || !pause.match(/^0[0-9][0-5][0-9]/)) {
                        $("#erstellen-error").html("Bitte geben Sie die Pause an.").show();
                    } else if ($("#erstellen-kunde").val().length == 0) {
                        $("#erstellen-error").html("Bitte geben Sie den Kunden an.").show();
                    } else {
                        $.ajax({
                            type: "POST",
                            url: "/mitarbeiter/ajax",
                            data: {
                                type: 'new_stundenimporteintrag', 
                                mitarbeiter: '<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['personalnummer'])===null||$tmp==='' ? "???" : $tmp);?>
', 
                                datum: $("#erstellen-datum").val(), 
                                von: von, 
                                bis: bis, 
                                pause: pause, 
                                kunde: $("#erstellen-kunde").val()
                            },
                            success: function ($return) {
                                if ($return.hasOwnProperty('status')) {
                                    if ($return.status == "success") {
                                        $('#erstellen-modal').modal('hide');
                                        $("#erstellen-error").html("").hide();
                                        $("#calendar").fullCalendar('refetchEvents');
                                        updateInfo($("#calendar").fullCalendar('getView'), null);
                                    } else if ($return.status == "not_logged_in") {
                                        location.reload();
                                    } else {
                                        $("#erstellen-error").html("Es ist ein Fehler aufgetreten.").show();
                                    } 
                                } else {
                                    $("#erstellen-error").html("Es ist ein Fehler aufgetreten.").show();
                                }
                            },
                            error: function () {
                                $("#erstellen-error").html("Es ist ein Fehler aufgetreten.").show();
                            }
                        });
                    }
                } else {
                    var von = $("#erstellen-von").val();
                    var bis = $("#erstellen-bis").val();
                    if (von.length != 10 || !von.match(/[0-3][0-9][.][0-1][0-9][.][1-2][0-9][0-9][0-9]/)) {
                        $("#erstellen-error").html("Das Datum im Feld <strong>Von</strong> ist ungültig.").show();
                    } else if (bis.length != 10 || !bis.match(/[0-3][0-9][.][0-1][0-9][.][1-2][0-9][0-9][0-9]/)) {
                        $("#erstellen-error").html("Das Datum im Feld <strong>Bis</strong> ist ungültig.").show();
                    } else if ($("#erstellen-art").val().length == 0) {
                        $("#erstellen-error").html("Bitte geben Sie eine Art für diesen Kalendereintrag ein.").show();
                    } else {
                        $.ajax({
                            type: "POST",
                            url: "/mitarbeiter/ajax",
                            data: {
                                type: 'new', 
                                mitarbeiter: '<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['personalnummer'])===null||$tmp==='' ? "???" : $tmp);?>
', 
                                von: $("#erstellen-von").val(), 
                                bis: $("#erstellen-bis").val(), 
                                bezeichnung: $("#erstellen-bezeichnung").val(), 
                                art: $("#erstellen-art").val()
                            },
                            success: function ($return) {
                                if ($return.hasOwnProperty('status')) {
                                    if ($return.status == "success") {
                                        $('#erstellen-modal').modal('hide');
                                        $("#erstellen-error").html("").hide();
                                        $("#calendar").fullCalendar('refetchEvents');
                                        updateInfo($("#calendar").fullCalendar('getView'), null);
                                    } else if ($return.status == "not_logged_in") {
                                        location.reload();
                                    } else {
                                        $("#erstellen-error").html("Es ist ein Fehler aufgetreten.").show();
                                    } 
                                } else {
                                    $("#erstellen-error").html("Es ist ein Fehler aufgetreten.").show();
                                }
                            },
                            error: function () {
                                $("#erstellen-error").html("Es ist ein Fehler aufgetreten.").show();
                            }
                        });
                    }
                }
            });

            $("#bearbeiten-loeschen").click(function () {
                if ($("#bearbeiten-id").val().length == 0) {
                    $("#bearbeiten-error").html("Es ist ein technischer Fehler aufgetreten.").show();
                } else if ($("#bearbeiten-stundenimporteintrag").is(":hidden")) {
                    $.ajax({
                        type: "POST",
                        url: "/mitarbeiter/ajax",
                        data: {
                            type: 'delete', 
                            id: $("#bearbeiten-id").val()
                        },
                        success: function ($return) {
                            if ($return.hasOwnProperty('status')) {
                                if ($return.status == "success") {
                                    $('#bearbeiten-modal').modal('hide');
                                    $("#bearbeiten-error").html("").hide();
                                    $("#calendar").fullCalendar('refetchEvents');
                                    updateInfo($("#calendar").fullCalendar('getView'), null);
                                } else if ($return.status == "not_logged_in") {
                                    location.reload();
                                } else {
                                    $("#bearbeiten-error").html("Es ist ein Fehler aufgetreten.").show();
                                } 
                            } else {
                                $("#bearbeiten-error").html("Es ist ein Fehler aufgetreten.").show();
                            }
                        },
                        error: function () {
                            $("#bearbeiten-error").html("Es ist ein Fehler aufgetreten.").show();
                        }
                    });
                } else {
                    $.ajax({
                        type: "POST",
                        url: "/mitarbeiter/ajax",
                        data: {
                            type: 'delete_stundenimporteintrag', 
                            id: $("#bearbeiten-id").val()
                        },
                        success: function ($return) {
                            if ($return.hasOwnProperty('status')) {
                                if ($return.status == "success") {
                                    $('#bearbeiten-modal').modal('hide');
                                    $("#bearbeiten-error").html("").hide();
                                    $("#calendar").fullCalendar('refetchEvents');
                                    updateInfo($("#calendar").fullCalendar('getView'), null);
                                } else if ($return.status == "not_logged_in") {
                                    location.reload();
                                } else {
                                    $("#bearbeiten-error").html("Es ist ein Fehler aufgetreten.").show();
                                } 
                            } else {
                                $("#bearbeiten-error").html("Es ist ein Fehler aufgetreten.").show();
                            }
                        },
                        error: function () {
                            $("#bearbeiten-error").html("Es ist ein Fehler aufgetreten.").show();
                        }
                    });
                }
            });
        });
    <?php echo '</script'; ?>
>
    <!-- /fullcalendar -->

    <!-- daterangepicker -->
    <?php echo '<script'; ?>
 src="/assets/vendors/bootstrap-daterangepicker-master/daterangepicker.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
>
        $(".datum").daterangepicker({
            "singleDatePicker": true,
            "showISOWeekNumbers": true,
            "locale": {
                "format": "DD.MM.YYYY",
                "separator": " - ",
                "applyLabel": "Übernehmen",
                "cancelLabel": "Abbrechen",
                "fromLabel": "Von",
                "toLabel": "Bis",
                "customRangeLabel": "Manuell",
                "weekLabel": "W",
                "daysOfWeek": [
                    "So",
                    "Mo",
                    "Di",
                    "Mi",
                    "Do",
                    "Fr",
                    "Sa"
                ],
                "monthNames": [
                    "Januar",
                    "Februar",
                    "März",
                    "April",
                    "Mai",
                    "Juni",
                    "Juli",
                    "August",
                    "September",
                    "Oktober",
                    "November",
                    "Dezember"
                ],
                "firstDay": 1
            }
        });
    <?php echo '</script'; ?>
>

    <!-- Select2 -->
    <?php echo '<script'; ?>
 src="/assets/vendors/select2/dist/js/select2.full.min.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
>
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
    <?php echo '</script'; ?>
>
    <!-- /Select2 -->

    <!-- Mitarbeiterswitch -->
    <?php echo '<script'; ?>
>
        $maswitch = $('#mitarbeiterswitch');
        $maswitch.change(function () {
            if ($maswitch.val() != '') {
                window.location = '/mitarbeiter/kalender/' + $maswitch.val() + '/' + $('#calendar').fullCalendar('getDate').format("YYYY") + '/' + $('#calendar').fullCalendar('getDate').format("MM");
            }
        });
    <?php echo '</script'; ?>
>
    <!-- /Mitarbeiterswitch -->

    <!-- Kalendereintrag erstellen: Typwechsel -->
    <?php echo '<script'; ?>
>
        $(document).ready(function () {
            $("#erstellen-typ-kalendereintrag").click(function () {
                $("#erstellen-kalendereintrag").show();
                $("#erstellen-stundenimporteintrag").hide();
            });

            $("#erstellen-typ-stundenimporteintrag").click(function () {
                $("#erstellen-kalendereintrag").hide();
                $("#erstellen-stundenimporteintrag").show();
            });
        });
    <?php echo '</script'; ?>
>
    <!-- /Kalendereintrag erstellen: Typwechsel -->

    <!-- inputmask -->
    <?php echo '<script'; ?>
>
        $(document).ready(function () {
            $('.inputmask').inputmask();
        });
    <?php echo '</script'; ?>
>
    <!-- /inputmask-->

    <!-- erstellen-pause-buttons -->
    <?php echo '<script'; ?>
>
        $(document).ready(function () {
            $('#erstellen-pause-buttons > button').click(function () {
                $("#erstellen-pause").val($(this).data('value'));
            });
        });
    <?php echo '</script'; ?>
>
    <!-- /erstellen-pause-buttons -->

    <!-- bearbeiten-pause-buttons -->
    <?php echo '<script'; ?>
>
        $(document).ready(function () {
            $('#bearbeiten-pause-buttons > button').click(function () {
                $("#bearbeiten-pause").val($(this).data('value'));
            });
        });
    <?php echo '</script'; ?>
>
    <!-- /bearbeiten-pause-buttons -->
<?php $_smarty_tpl->smarty->ext->_capture->close($_smarty_tpl);
?>


<?php $_smarty_tpl->_assignInScope('js', $_smarty_tpl->smarty->ext->_capture->getBuffer($_smarty_tpl, 'scripts') ,false ,2);
}
}
