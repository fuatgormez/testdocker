<?php
/* Smarty version 3.1.30, created on 2019-07-19 18:11:47
  from "/usr/local/www/apache24/noexec/ttact-intern-software/views/main/Berechnungen/lohn.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5d31ebc3b47395_87719979',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '6090c02f6c711351f6027be2d068ca0da0b69965' => 
    array (
      0 => '/usr/local/www/apache24/noexec/ttact-intern-software/views/main/Berechnungen/lohn.tpl',
      1 => 1562525865,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5d31ebc3b47395_87719979 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_assignInScope('title', "Berechnungen | Lohn" ,false ,2);
?>

<div class="container">
    <?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['error'])) {?>
        <div class="row mb-4">
            <div class="col">
                <div class="content-box bg-danger text-white">
                    <?php echo $_smarty_tpl->tpl_vars['smarty_vars']->value['error'];?>

                </div>
            </div>
        </div>
    <?php }?>

    <?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['success'])) {?>
        <div class="row mb-4">
            <div class="col">
                <div class="content-box bg-success text-white">
                    <?php echo $_smarty_tpl->tpl_vars['smarty_vars']->value['success'];?>

                </div>
            </div>
        </div>
    <?php }?>

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
                                    <option value="1"<?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['monat'])) {
if ($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['monat'] == 1) {?> selected<?php }
}?>>Januar</option>
                                    <option value="2"<?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['monat'])) {
if ($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['monat'] == 2) {?> selected<?php }
}?>>Februar</option>
                                    <option value="3"<?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['monat'])) {
if ($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['monat'] == 3) {?> selected<?php }
}?>>März</option>
                                    <option value="4"<?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['monat'])) {
if ($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['monat'] == 4) {?> selected<?php }
}?>>April</option>
                                    <option value="5"<?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['monat'])) {
if ($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['monat'] == 5) {?> selected<?php }
}?>>Mai</option>
                                    <option value="6"<?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['monat'])) {
if ($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['monat'] == 6) {?> selected<?php }
}?>>Juni</option>
                                    <option value="7"<?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['monat'])) {
if ($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['monat'] == 7) {?> selected<?php }
}?>>Juli</option>
                                    <option value="8"<?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['monat'])) {
if ($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['monat'] == 8) {?> selected<?php }
}?>>August</option>
                                    <option value="9"<?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['monat'])) {
if ($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['monat'] == 9) {?> selected<?php }
}?>>September</option>
                                    <option value="10"<?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['monat'])) {
if ($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['monat'] == 10) {?> selected<?php }
}?>>Oktober</option>
                                    <option value="11"<?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['monat'])) {
if ($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['monat'] == 11) {?> selected<?php }
}?>>November</option>
                                    <option value="12"<?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['monat'])) {
if ($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['monat'] == 12) {?> selected<?php }
}?>>Dezember</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-12 col-sm-6">
                            <label class="form-control-label" for="jahr">Jahr</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-calendar fa-fw"></i></span>
                                <input type="text" class="form-control" id="jahr" name="jahr" placeholder="JJJJ" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['jahr'])===null||$tmp==='' ? '' : $tmp);?>
" required>
                            </div>
                        </div>
                    </div>
                    <button type="submit" name="action" value="berechnen" class="btn btn-secondary form-control">Berechnen</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['liste'])) {?>
    <form action="/berechnungen/lohn" method="post">
        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['smarty_vars']->value['liste'], 'mitarbeiter', false, 'personalnummer');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['personalnummer']->value => $_smarty_tpl->tpl_vars['mitarbeiter']->value) {
?>
            <div class="row mb-4">
                <div class="col-12<?php if ($_smarty_tpl->tpl_vars['smarty_vars']->value['company'] != 'tps') {?> col-lg-9<?php }?>">
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
                                <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['mitarbeiter']->value, 'row');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['row']->value) {
?>
                                    <tr>
                                        <td><input type="checkbox" name="export[]" value="<?php echo $_smarty_tpl->tpl_vars['row']->value['export'];?>
" checked></td>
                                        <td><?php echo $_smarty_tpl->tpl_vars['row']->value['personalnummer'];?>
 - <?php echo $_smarty_tpl->tpl_vars['row']->value['nachname'];?>
, <?php echo $_smarty_tpl->tpl_vars['row']->value['vorname'];?>
</td>
                                        <td class="text-right"><?php echo $_smarty_tpl->tpl_vars['row']->value['lohnart'];?>
</td>
                                        <td><?php echo $_smarty_tpl->tpl_vars['row']->value['bezeichnung'];?>
</td>
                                        <td class="text-right"><?php echo $_smarty_tpl->tpl_vars['row']->value['anzahl'];?>
</td>
                                        <td class="text-right"><?php echo $_smarty_tpl->tpl_vars['row']->value['lohnsatz'];?>
</td>
                                        <td class="text-right"><?php echo $_smarty_tpl->tpl_vars['row']->value['betrag'];?>
</td>
                                    </tr>
                                <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

                            </tbody>
                        </table>
                    </div>
                </div>
                <?php if ($_smarty_tpl->tpl_vars['smarty_vars']->value['company'] != 'tps') {?>
                    <div class="col-12 col-lg-3">
                        <div class="content-box">
                            <?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['fehlzeitenliste'][$_smarty_tpl->tpl_vars['personalnummer']->value])) {?>
                                <h4><?php echo $_smarty_tpl->tpl_vars['smarty_vars']->value['fehlzeitenliste'][$_smarty_tpl->tpl_vars['personalnummer']->value]['mini_oder_sv'];?>
</h4>

                                <div>
                                    <a href="/mitarbeiter/kalender/<?php echo (($tmp = @$_smarty_tpl->tpl_vars['personalnummer']->value)===null||$tmp==='' ? "???" : $tmp);?>
" target="_blank">-- Mitarbeiterkalender öffnen --</a>
                                </div>

                                <div class="row">
                                    <div class="col-9">Alter AZK-Stand</div>
                                    <div class="col-3 text-right"><?php echo $_smarty_tpl->tpl_vars['smarty_vars']->value['fehlzeitenliste'][$_smarty_tpl->tpl_vars['personalnummer']->value]['alter_azk'];?>
</div>
                                </div>
                                <div class="row">
                                    <div class="col-9">Veränderung</div>
                                    <div class="col-3 text-right"><?php echo $_smarty_tpl->tpl_vars['smarty_vars']->value['fehlzeitenliste'][$_smarty_tpl->tpl_vars['personalnummer']->value]['veraenderung'];?>
</div>
                                </div>
                                <div class="row">
                                    <div class="col-9">Neuer AZK-Stand</div>
                                    <div class="col-3 text-right"><?php echo $_smarty_tpl->tpl_vars['smarty_vars']->value['fehlzeitenliste'][$_smarty_tpl->tpl_vars['personalnummer']->value]['neuer_azk'];?>
</div>
                                </div>
                                <?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['fehlzeitenliste'][$_smarty_tpl->tpl_vars['personalnummer']->value]['benoetigte_fehlzeiten_stunden'])) {?>
                                    <div class="row">
                                        <div class="col-9">Vorhandene Fehlzeitenstd.</div>
                                        <div class="col-3 text-right"><?php echo $_smarty_tpl->tpl_vars['smarty_vars']->value['fehlzeitenliste'][$_smarty_tpl->tpl_vars['personalnummer']->value]['vorhandene_fehlzeiten_stunden'];?>
</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-9">Benötigte Fehlzeitenstd.</div>
                                        <div class="col-3 text-right"><?php echo $_smarty_tpl->tpl_vars['smarty_vars']->value['fehlzeitenliste'][$_smarty_tpl->tpl_vars['personalnummer']->value]['benoetigte_fehlzeiten_stunden'];?>
</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-9">Tagessoll</div>
                                        <div class="col-3 text-right"><?php echo $_smarty_tpl->tpl_vars['smarty_vars']->value['fehlzeitenliste'][$_smarty_tpl->tpl_vars['personalnummer']->value]['tagessoll'];?>
</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-9">Benötigte Fehlzeitentage</div>
                                        <div class="col-3 text-right"><?php echo $_smarty_tpl->tpl_vars['smarty_vars']->value['fehlzeitenliste'][$_smarty_tpl->tpl_vars['personalnummer']->value]['benoetigte_fehlzeiten_tage'];?>
</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-9">Freie Kalendertage</div>
                                        <div class="col-3 text-right"><?php echo $_smarty_tpl->tpl_vars['smarty_vars']->value['fehlzeitenliste'][$_smarty_tpl->tpl_vars['personalnummer']->value]['freie_kalendertage'];?>
</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-9">Fehlzeitentage buchen</div>
                                        <div class="col-3 text-right"><?php echo $_smarty_tpl->tpl_vars['smarty_vars']->value['fehlzeitenliste'][$_smarty_tpl->tpl_vars['personalnummer']->value]['buchen_fehlzeiten_tage'];?>
</div>
                                    </div>
                                    <div class="row mt-4">
                                        <div class="form-group col-12">
                                            <label class="form-control-label" for="anzahl_fehltage"><strong>Anzahl zu buchender Fehltage</strong></label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-calendar-o fa-fw"></i></span>
                                                <input type="number" class="form-control" id="anzahl_fehltage" name="anzahl_fehltage[<?php echo $_smarty_tpl->tpl_vars['personalnummer']->value;?>
]" placeholder="z.B. 3" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['fehlzeitenliste'][$_smarty_tpl->tpl_vars['personalnummer']->value]['buchen_fehlzeiten_tage_value'])===null||$tmp==='' ? '' : $tmp);?>
" required>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="btn-group btn-block" data-toggle="buttons">
                                                <label class="btn btn-checkbox btn-block mb-0">
                                                    <input type="checkbox" name="fehltage_speichern[]" value="<?php echo $_smarty_tpl->tpl_vars['personalnummer']->value;?>
"> Fehltage speichern
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                <?php }?>
                            <?php }?>
                        </div>
                    </div>
                <?php }?>
            </div>
        <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

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
                            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['smarty_vars']->value['lohnartstatistiken'], 'row', false, 'lohnart');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['lohnart']->value => $_smarty_tpl->tpl_vars['row']->value) {
?>
                                <tr>
                                    <td><?php echo $_smarty_tpl->tpl_vars['lohnart']->value;?>
</td>
                                    <td class="text-right"><?php echo number_format((($tmp = @$_smarty_tpl->tpl_vars['row']->value['anzahl'])===null||$tmp==='' ? 0 : $tmp),2,",",".");?>
</td>
                                    <td class="text-right"><?php echo number_format((($tmp = @$_smarty_tpl->tpl_vars['row']->value['betrag'])===null||$tmp==='' ? 0 : $tmp),2,",",".");?>
</td>
                                </tr>
                            <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col">
                <div class="content-box">
                    <input type="hidden" name="filename" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['filename'])===null||$tmp==='' ? "???" : $tmp);?>
">
                    <button type="submit" name="action" value="exportieren" class="btn btn-secondary btn-block" onclick='this.form.target="_blank";'>Für Agenda exportieren</button>
                </div>
            </div>
            <?php if ($_smarty_tpl->tpl_vars['smarty_vars']->value['company'] != 'tps') {?>
                <div class="col">
                    <div class="content-box">
                        <input type="hidden" name="jahr" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['jahr'])===null||$tmp==='' ? "???" : $tmp);?>
">
                        <input type="hidden" name="monat" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['monat'])===null||$tmp==='' ? "???" : $tmp);?>
">
                        <button type="submit" name="action" value="fehltage_speichern" class="btn btn-secondary btn-block" onclick='this.form.target="_self";'>Fehltage speichern</button>
                    </div>
                </div>
            <?php }?>
        </div>
    </form>
<?php }?>

<?php $_smarty_tpl->smarty->ext->_capture->open($_smarty_tpl, 'css', null, null);
?>

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
<?php $_smarty_tpl->smarty->ext->_capture->close($_smarty_tpl);
?>


<?php $_smarty_tpl->_assignInScope('css', $_smarty_tpl->smarty->ext->_capture->getBuffer($_smarty_tpl, 'css') ,false ,2);
?>

<?php $_smarty_tpl->smarty->ext->_capture->open($_smarty_tpl, 'scripts', null, null);
?>

<?php $_smarty_tpl->smarty->ext->_capture->close($_smarty_tpl);
?>


<?php $_smarty_tpl->_assignInScope('js', $_smarty_tpl->smarty->ext->_capture->getBuffer($_smarty_tpl, 'scripts') ,false ,2);
}
}
