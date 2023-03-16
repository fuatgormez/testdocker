<?php
/* Smarty version 3.1.30, created on 2019-07-11 08:58:35
  from "/usr/local/www/apache24/noexec/ttact-intern-software/views/main/Mitarbeiter/bearbeiten.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5d26de1bc90321_24182030',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '078fb650ab911b762be727ca9ecf4f9d0262771c' => 
    array (
      0 => '/usr/local/www/apache24/noexec/ttact-intern-software/views/main/Mitarbeiter/bearbeiten.tpl',
      1 => 1562525869,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:views/main/Mitarbeiter/components/form_a.tpl' => 1,
    'file:views/main/Mitarbeiter/components/form_b.tpl' => 1,
    'file:views/main/Mitarbeiter/components/form_c.tpl' => 1,
    'file:views/main/components/tables.tpl' => 2,
  ),
),false)) {
function content_5d26de1bc90321_24182030 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_assignInScope('container_class', "container" ,false ,2);
?>

<?php $_smarty_tpl->_assignInScope('title', "Mitarbeiter | bearbeiten" ,false ,2);
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
            <?php }?>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col">
        <div class="content-box pt-0">
            <div class="row mb-4 mitarbeiter-bearbeiten-menu">
                <a class="col-12 col-lg text-center<?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['tab'])) {
if ($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['tab'] == 'persoenliches') {?> active<?php }
} else { ?> active<?php }?>" href="/mitarbeiter/bearbeiten/<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['personalnummer'])===null||$tmp==='' ? "???" : $tmp);?>
/persoenliches">
                    Persönliches
                </a>
                <?php if ($_smarty_tpl->tpl_vars['smarty_vars']->value['current_user']['usergroup']->hasRight('notizen')) {?>
                    <a class="col-12 col-lg text-center<?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['tab'])) {
if ($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['tab'] == 'notizen') {?> active<?php }
}?>" href="/mitarbeiter/bearbeiten/<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['personalnummer'])===null||$tmp==='' ? "???" : $tmp);?>
/notizen">
                        Notizen
                    </a>
                <?php }?>
                <?php if ($_smarty_tpl->tpl_vars['smarty_vars']->value['current_user']['usergroup']->hasRight('vertragliches')) {?>
                    <a class="col-12 col-lg text-center<?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['tab'])) {
if ($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['tab'] == 'vertragliches') {?> active<?php }
}?>" href="/mitarbeiter/bearbeiten/<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['personalnummer'])===null||$tmp==='' ? "???" : $tmp);?>
/vertragliches">
                        Vertragliches
                    </a>
                <?php }?>
                <a class="col-12 col-lg text-center<?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['tab'])) {
if ($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['tab'] == 'praeferenzen') {?> active<?php }
}?>" href="/mitarbeiter/bearbeiten/<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['personalnummer'])===null||$tmp==='' ? "???" : $tmp);?>
/praeferenzen">
                    Präferenzen
                </a>
                <?php if ($_smarty_tpl->tpl_vars['smarty_vars']->value['current_user']['usergroup']->hasRight('lohnbuchungen')) {?>
                    <a class="col-12 col-lg text-center<?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['tab'])) {
if ($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['tab'] == 'lohnbuchungen') {?> active<?php }
}?>" href="/mitarbeiter/bearbeiten/<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['personalnummer'])===null||$tmp==='' ? "???" : $tmp);?>
/lohnbuchungen">
                        Lohnbuchungen
                    </a>
                <?php }?>
                <a class="col-12 col-lg text-center" href="/mitarbeiter/kalender/<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['personalnummer'])===null||$tmp==='' ? "???" : $tmp);?>
">
                    Kalender
                </a>
            </div>

            <?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['success'])) {?>
            <div class="alert alert-success">
                <?php echo $_smarty_tpl->tpl_vars['smarty_vars']->value['success'];?>

            </div>
            <?php }?>
            <?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['error'])) {?>
            <div class="alert alert-danger">
                <?php echo $_smarty_tpl->tpl_vars['smarty_vars']->value['error'];?>

            </div>
            <?php }?>

            <?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['tab'])) {?>
                <?php if ($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['tab'] == 'persoenliches') {?>
                    <form action="/mitarbeiter/bearbeiten/<?php echo $_smarty_tpl->tpl_vars['smarty_vars']->value['values']['personalnummer'];?>
/persoenliches" method="post">
                        <?php $_smarty_tpl->_subTemplateRender("file:views/main/Mitarbeiter/components/form_a.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

                        <?php $_smarty_tpl->_subTemplateRender("file:views/main/Mitarbeiter/components/form_b.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

                        <?php $_smarty_tpl->_subTemplateRender("file:views/main/Mitarbeiter/components/form_c.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

                    </form>
                <?php } elseif (($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['tab'] == 'notizen')) {?>
                    <form action="/mitarbeiter/bearbeiten/<?php echo $_smarty_tpl->tpl_vars['smarty_vars']->value['values']['personalnummer'];?>
/notizen" method="post">
                        <div class="row">
                            <div class="form-group col-12">
                                <label class="form-control-label" for="notizen_allgemein">Allgemein</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                    <textarea class="form-control resizable" name="notizen_allgemein" id="notizen_allgemein"><?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['notizen_allgemein'])===null||$tmp==='' ? '' : $tmp);?>
</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-12 col-md-6">
                                <label class="form-control-label" for="notizen_januar">Januar</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                    <textarea class="form-control resizable" name="notizen_januar" id="notizen_januar"><?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['notizen_januar'])===null||$tmp==='' ? '' : $tmp);?>
</textarea>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6">
                                <label class="form-control-label" for="notizen_februar">Februar</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                    <textarea class="form-control resizable" name="notizen_februar" id="notizen_februar"><?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['notizen_februar'])===null||$tmp==='' ? '' : $tmp);?>
</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-12 col-md-6">
                                <label class="form-control-label" for="notizen_maerz">März</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                    <textarea class="form-control resizable" name="notizen_maerz" id="notizen_maerz"><?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['notizen_maerz'])===null||$tmp==='' ? '' : $tmp);?>
</textarea>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6">
                                <label class="form-control-label" for="notizen_april">April</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                    <textarea class="form-control resizable" name="notizen_april" id="notizen_april"><?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['notizen_april'])===null||$tmp==='' ? '' : $tmp);?>
</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-12 col-md-6">
                                <label class="form-control-label" for="notizen_mai">Mai</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                    <textarea class="form-control resizable" name="notizen_mai" id="notizen_mai"><?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['notizen_mai'])===null||$tmp==='' ? '' : $tmp);?>
</textarea>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6">
                                <label class="form-control-label" for="notizen_juni">Juni</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                    <textarea class="form-control resizable" name="notizen_juni" id="notizen_juni"><?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['notizen_juni'])===null||$tmp==='' ? '' : $tmp);?>
</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-12 col-md-6">
                                <label class="form-control-label" for="notizen_juli">Juli</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                    <textarea class="form-control resizable" name="notizen_juli" id="notizen_juli"><?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['notizen_juli'])===null||$tmp==='' ? '' : $tmp);?>
</textarea>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6">
                                <label class="form-control-label" for="notizen_august">August</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                    <textarea class="form-control resizable" name="notizen_august" id="notizen_august"><?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['notizen_august'])===null||$tmp==='' ? '' : $tmp);?>
</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-12 col-md-6">
                                <label class="form-control-label" for="notizen_september">September</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                    <textarea class="form-control resizable" name="notizen_september" id="notizen_september"><?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['notizen_september'])===null||$tmp==='' ? '' : $tmp);?>
</textarea>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6">
                                <label class="form-control-label" for="notizen_oktober">Oktober</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                    <textarea class="form-control resizable" name="notizen_oktober" id="notizen_oktober"><?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['notizen_oktober'])===null||$tmp==='' ? '' : $tmp);?>
</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-12 col-md-6">
                                <label class="form-control-label" for="notizen_november">November</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                    <textarea class="form-control resizable" name="notizen_november" id="notizen_november"><?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['notizen_november'])===null||$tmp==='' ? '' : $tmp);?>
</textarea>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6">
                                <label class="form-control-label" for="notizen_dezember">Dezember</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                    <textarea class="form-control resizable" name="notizen_dezember" id="notizen_dezember"><?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['notizen_dezember'])===null||$tmp==='' ? '' : $tmp);?>
</textarea>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="notizen_submitted" value="true">
                        <button type="submit" class="btn btn-secondary form-control">Speichern</button>
                    </form>
                <?php } elseif (($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['tab'] == 'vertragliches')) {?>

                    <?php if ($_smarty_tpl->tpl_vars['smarty_vars']->value['current_user']['usergroup']->hasRight('austritt_befristungen')) {?>
                    <form action="/mitarbeiter/bearbeiten/<?php echo $_smarty_tpl->tpl_vars['smarty_vars']->value['values']['personalnummer'];?>
/vertragliches" method="post">
                    <?php }?>

                        <h4>Eckdaten</h4>
                        <div class="row">
                            <div class="form-group col-12 col-sm-6">
                                <label class="form-control-label" for="eintritt">Eintritt</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calendar fa-fw"></i></span>
                                    <input type="text" class="form-control" id="eintritt" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['eintritt'])===null||$tmp==='' ? '' : $tmp);?>
" readonly>
                                </div>
                            </div>
                            <div class="form-group col-12 col-sm-6">
                                <label class="form-control-label" for="austritt">Austritt</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calendar fa-fw"></i></span>
                                    <input type="text" class="form-control" id="austritt" name="austritt" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['austritt'])===null||$tmp==='' ? '' : $tmp);?>
"<?php if ($_smarty_tpl->tpl_vars['smarty_vars']->value['current_user']['usergroup']->hasRight('austritt_befristungen')) {?> placeholder="TT.MM.JJJJ"<?php } else { ?> readonly<?php }?>>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-12 col-sm-6">
                                <label class="form-control-label" for="befristung">Befristung</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calendar fa-fw"></i></span>
                                    <input type="text" class="form-control" id="befristung" name="befristung" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['befristung'])===null||$tmp==='' ? '' : $tmp);?>
"<?php if ($_smarty_tpl->tpl_vars['smarty_vars']->value['current_user']['usergroup']->hasRight('austritt_befristungen')) {?> placeholder="TT.MM.JJJJ"<?php } else { ?> readonly<?php }?>>
                                </div>
                            </div>
                            <div class="form-group col-12 col-sm-6">
                                <label class="form-control-label" for="befristung1">1. Befristung</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calendar fa-fw"></i></span>
                                    <input type="text" name="befristung1" class="form-control" id="befristung1" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['befristung1'])===null||$tmp==='' ? '' : $tmp);?>
"<?php if ($_smarty_tpl->tpl_vars['smarty_vars']->value['current_user']['usergroup']->hasRight('austritt_befristungen')) {?> placeholder="TT.MM.JJJJ"<?php } else { ?> readonly<?php }?>>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-12 col-sm-6">
                                <label class="form-control-label" for="befristung2">2. Befristung</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calendar fa-fw"></i></span>
                                    <input type="text" name="befristung2" class="form-control" id="befristung2" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['befristung2'])===null||$tmp==='' ? '' : $tmp);?>
"<?php if ($_smarty_tpl->tpl_vars['smarty_vars']->value['current_user']['usergroup']->hasRight('austritt_befristungen')) {?> placeholder="TT.MM.JJJJ"<?php } else { ?> readonly<?php }?>>
                                </div>
                            </div>
                            <div class="form-group col-12 col-sm-6">
                                <label class="form-control-label" for="befristung3">3. Befristung</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calendar fa-fw"></i></span>
                                    <input type="text" name="befristung3" class="form-control" id="befristung3" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['befristung3'])===null||$tmp==='' ? '' : $tmp);?>
"<?php if ($_smarty_tpl->tpl_vars['smarty_vars']->value['current_user']['usergroup']->hasRight('austritt_befristungen')) {?> placeholder="TT.MM.JJJJ"<?php } else { ?> readonly<?php }?>>
                                </div>
                            </div>
                        </div>
                        <?php if ($_smarty_tpl->tpl_vars['smarty_vars']->value['current_user']['usergroup']->hasRight('austritt_befristungen')) {?>
                        <input type="hidden" name="vertragliches_submitted" value="true">
                        <button type="submit" class="btn btn-secondary form-control">Speichern</button>
                        <?php }?>

                    <?php if ($_smarty_tpl->tpl_vars['smarty_vars']->value['current_user']['usergroup']->hasRight('austritt_befristungen')) {?>
                    </form>
                    <?php }?>

                    <h4 class="mt-4">Urlaub</h4>
                    <div class="row">
                        <div class="form-group col-12 col-sm-6">
                            <label class="form-control-label" for="jahresurlaub">Jahresurlaub</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-info fa-fw"></i></span>
                                <input type="text" class="form-control" id="jahresurlaub" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['jahresurlaub'])===null||$tmp==='' ? '' : $tmp);?>
" readonly>
                            </div>
                        </div>
                        <div class="form-group col-12 col-sm-6">
                            <label class="form-control-label" for="resturlaub_vorjahr">Resturlaub Vorjahr</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-info fa-fw"></i></span>
                                <input type="text" class="form-control" id="resturlaub_vorjahr" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['resturlaub_vorjahr'])===null||$tmp==='' ? '' : $tmp);?>
" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-12 col-sm-6">
                            <label class="form-control-label" for="urlaubstage_genommen">Urlaubstage genommen</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-info fa-fw"></i></span>
                                <input type="text" class="form-control" id="urlaubstage_genommen" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['urlaubstage_genommen'])===null||$tmp==='' ? '' : $tmp);?>
" readonly>
                            </div>
                        </div>
                        <div class="form-group col-12 col-sm-6">
                            <label class="form-control-label" for="urlaubstage_uebrig">Urlaubstage übrig</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-info fa-fw"></i></span>
                                <input type="text" class="form-control" id="urlaubstage_uebrig" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['urlaubstage_uebrig'])===null||$tmp==='' ? '' : $tmp);?>
" readonly>
                            </div>
                        </div>
                    </div>

                    <?php if ($_smarty_tpl->tpl_vars['smarty_vars']->value['company'] != 'tps') {?>
                        <h4 class="mt-4">Lohnzusammensetzung <?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['aktuelle_lohndaten']['monatsbezeichnung'])===null||$tmp==='' ? "XXX" : $tmp);?>
 <?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['aktuelle_lohndaten']['jahr'])===null||$tmp==='' ? "XXX" : $tmp);?>
</h4>
                        <div class="row">
                            <div class="form-group col-12 col-sm-6">
                                <label class="form-control-label" for="tarifbezeichnung">Tarifbezeichnung</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-info fa-fw"></i></span>
                                    <input type="text" class="form-control" id="tarifbezeichnung" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['aktuelle_lohndaten']['tarifbezeichnung'])===null||$tmp==='' ? '' : $tmp);?>
" readonly>
                                </div>
                            </div>
                            <div class="form-group col-12 col-sm-6">
                                <label class="form-control-label" for="tariflohn">Tariflohn</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-euro fa-fw"></i></span>
                                    <input type="text" class="form-control" id="tariflohn" value="<?php echo number_format((($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['aktuelle_lohndaten']['tariflohn'])===null||$tmp==='' ? 0 : $tmp),2,",",".");?>
" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-12 col-sm-6">
                                <label class="form-control-label" for="zuschlag_9_monate">Zuschlag 9 Monate</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-euro fa-fw"></i></span>
                                    <input type="text" class="form-control" id="zuschlag_9_monate" value="<?php echo number_format((($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['aktuelle_lohndaten']['zuschlag_9_monate'])===null||$tmp==='' ? 0 : $tmp),2,",",".");?>
" readonly>
                                </div>
                            </div>
                            <div class="form-group col-12 col-sm-6">
                                <label class="form-control-label" for="zuschlag_12_monate">Zuschlag 12 Monate</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-euro fa-fw"></i></span>
                                    <input type="text" class="form-control" id="zuschlag_12_monate" value="<?php echo number_format((($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['aktuelle_lohndaten']['zuschlag_12_monate'])===null||$tmp==='' ? 0 : $tmp),2,",",".");?>
" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-12 col-sm-6">
                                <label class="form-control-label" for="uebertarifliche_zulage">Übertarifliche Zulage</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-euro fa-fw"></i></span>
                                    <input type="text" class="form-control" id="uebertarifliche_zulage" value="<?php echo number_format((($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['aktuelle_lohndaten']['uebertarifliche_zulage'])===null||$tmp==='' ? 0 : $tmp),2,",",".");?>
" readonly>
                                </div>
                            </div>
                            <div class="form-group col-12 col-sm-6">
                                <label class="form-control-label" for="gesamtlohn">Gesamtlohn</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-euro fa-fw"></i></span>
                                    <input type="text" class="form-control" id="gesamtlohn" value="<?php echo number_format((($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['aktuelle_lohndaten']['gesamtlohn'])===null||$tmp==='' ? 0 : $tmp),2,",",".");?>
" readonly>
                                </div>
                            </div>
                        </div>
                    <?php }?>

                    <h4 class="mt-3">Lohnkonfiguration</h4>
                    <a class="btn btn-secondary btn-block my-3" href="/lohnkonfiguration/erstellen/<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['personalnummer'])===null||$tmp==='' ? "???" : $tmp);?>
"><i class="fa fa-plus-circle mr-1"></i> Neue Lohnkonfiguration hinzufügen</a>
                    <?php $_smarty_tpl->_subTemplateRender("file:views/main/components/tables.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

                    <?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['lohnkonfigurationsliste'])) {?>
                        <?php echo (($tmp = @$_smarty_tpl->tpl_vars['table_tag']->value)===null||$tmp==='' ? "<table>" : $tmp);?>

                            <thead>
                                <tr>
                                    <td>Gültig ab</td>
                                    <?php if ($_smarty_tpl->tpl_vars['smarty_vars']->value['company'] != 'tps') {?>
                                        <td>Tarif</td>
                                    <?php }?>
                                    <td>Wochenstunden</td>
                                    <td>Lohn/Std (€)</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['smarty_vars']->value['lohnkonfigurationsliste'], 'row');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['row']->value) {
?>
                                    <tr>
                                        <td class="clickable text-right" data-href="/lohnkonfiguration/bearbeiten/<?php echo $_smarty_tpl->tpl_vars['row']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['row']->value['gueltig_ab'];?>
</td>
                                        <?php if ($_smarty_tpl->tpl_vars['smarty_vars']->value['company'] != 'tps') {?>
                                            <td class="clickable text-right" data-href="/lohnkonfiguration/bearbeiten/<?php echo $_smarty_tpl->tpl_vars['row']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['row']->value['tarif'];?>
</td>
                                        <?php }?>
                                        <td class="clickable text-right" data-href="/lohnkonfiguration/bearbeiten/<?php echo $_smarty_tpl->tpl_vars['row']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['row']->value['wochenstunden'];?>
</td>
                                        <td class="clickable text-right" data-href="/lohnkonfiguration/bearbeiten/<?php echo $_smarty_tpl->tpl_vars['row']->value['id'];?>
"><?php if ($_smarty_tpl->tpl_vars['row']->value['lohn'] != '') {
echo number_format((($tmp = @$_smarty_tpl->tpl_vars['row']->value['lohn'])===null||$tmp==='' ? 0 : $tmp),2,",",".");
}?></td>
                                    </tr>
                                <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

                            </tbody>
                        </table>
                    <?php }?>

                    <?php if ($_smarty_tpl->tpl_vars['smarty_vars']->value['current_user']['usergroup']->hasRight('tagessoll')) {?>
                        <h4 class="mt-4">Tagessoll</h4>
                        <?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['tagessollliste'])) {?>
                            <table class="table table-striped table-bordered table-hover dt-responsive nowrap mt-3" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <td class="text-right font-weight-bold">Jahr</td>
                                        <td class="text-right font-weight-bold">Monat</td>
                                        <td class="text-right font-weight-bold">Tagessoll</td>
                                        <?php if ($_smarty_tpl->tpl_vars['smarty_vars']->value['company'] != 'tps') {?>
                                            <td class="text-right font-weight-bold">Montag</td>
                                            <td class="text-right font-weight-bold">Dienstag</td>
                                            <td class="text-right font-weight-bold">Mittwoch</td>
                                            <td class="text-right font-weight-bold">Donnerstag</td>
                                            <td class="text-right font-weight-bold">Freitag</td>
                                            <td class="text-right font-weight-bold">Samstag</td>
                                            <td class="text-right font-weight-bold">Sonntag</td>
                                        <?php }?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['smarty_vars']->value['tagessollliste'], 'row');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['row']->value) {
?>
                                        <tr class="black-links">
                                            <td class="text-right"><a href="/tagessoll/bearbeiten/<?php echo $_smarty_tpl->tpl_vars['row']->value['id'];?>
" target="_blank"><?php echo $_smarty_tpl->tpl_vars['row']->value['jahr'];?>
</a></td>
                                            <td class="text-right"><a href="/tagessoll/bearbeiten/<?php echo $_smarty_tpl->tpl_vars['row']->value['id'];?>
" target="_blank"><?php echo $_smarty_tpl->tpl_vars['row']->value['monat'];?>
</a></td>
                                            <td class="text-right"><a href="/tagessoll/bearbeiten/<?php echo $_smarty_tpl->tpl_vars['row']->value['id'];?>
" target="_blank"><?php echo number_format((($tmp = @$_smarty_tpl->tpl_vars['row']->value['tagessoll_allgemein'])===null||$tmp==='' ? 0 : $tmp),2,",",".");?>
</a></td>
                                            <?php if ($_smarty_tpl->tpl_vars['smarty_vars']->value['company'] != 'tps') {?>
                                                <td class="text-right"><a href="/tagessoll/bearbeiten/<?php echo $_smarty_tpl->tpl_vars['row']->value['id'];?>
" target="_blank"><?php echo number_format((($tmp = @$_smarty_tpl->tpl_vars['row']->value['tagessoll_montag'])===null||$tmp==='' ? 0 : $tmp),2,",",".");?>
</a></td>
                                                <td class="text-right"><a href="/tagessoll/bearbeiten/<?php echo $_smarty_tpl->tpl_vars['row']->value['id'];?>
" target="_blank"><?php echo number_format((($tmp = @$_smarty_tpl->tpl_vars['row']->value['tagessoll_dienstag'])===null||$tmp==='' ? 0 : $tmp),2,",",".");?>
</a></td>
                                                <td class="text-right"><a href="/tagessoll/bearbeiten/<?php echo $_smarty_tpl->tpl_vars['row']->value['id'];?>
" target="_blank"><?php echo number_format((($tmp = @$_smarty_tpl->tpl_vars['row']->value['tagessoll_mittwoch'])===null||$tmp==='' ? 0 : $tmp),2,",",".");?>
</a></td>
                                                <td class="text-right"><a href="/tagessoll/bearbeiten/<?php echo $_smarty_tpl->tpl_vars['row']->value['id'];?>
" target="_blank"><?php echo number_format((($tmp = @$_smarty_tpl->tpl_vars['row']->value['tagessoll_donnerstag'])===null||$tmp==='' ? 0 : $tmp),2,",",".");?>
</a></td>
                                                <td class="text-right"><a href="/tagessoll/bearbeiten/<?php echo $_smarty_tpl->tpl_vars['row']->value['id'];?>
" target="_blank"><?php echo number_format((($tmp = @$_smarty_tpl->tpl_vars['row']->value['tagessoll_freitag'])===null||$tmp==='' ? 0 : $tmp),2,",",".");?>
</a></td>
                                                <td class="text-right"><a href="/tagessoll/bearbeiten/<?php echo $_smarty_tpl->tpl_vars['row']->value['id'];?>
" target="_blank"><?php echo number_format((($tmp = @$_smarty_tpl->tpl_vars['row']->value['tagessoll_samstag'])===null||$tmp==='' ? 0 : $tmp),2,",",".");?>
</a></td>
                                                <td class="text-right"><a href="/tagessoll/bearbeiten/<?php echo $_smarty_tpl->tpl_vars['row']->value['id'];?>
" target="_blank"><?php echo number_format((($tmp = @$_smarty_tpl->tpl_vars['row']->value['tagessoll_sonntag'])===null||$tmp==='' ? 0 : $tmp),2,",",".");?>
</a></td>
                                            <?php }?>
                                        </tr>
                                    <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

                                </tbody>
                            </table>
                        <?php }?>
                    <?php }?>

                    <?php if ($_smarty_tpl->tpl_vars['smarty_vars']->value['company'] != 'tps') {?>
                        <?php if ($_smarty_tpl->tpl_vars['smarty_vars']->value['current_user']['usergroup']->hasRight('arbeitszeitkonto')) {?>
                            <h4 class="mt-4">Arbeitszeitkonto</h4>
                            <?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['arbeitszeitkontoliste'])) {?>
                                <table class="table table-striped table-bordered table-hover dt-responsive nowrap mt-3" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <td class="text-right font-weight-bold">Jahr</td>
                                            <td class="text-right font-weight-bold">Monat</td>
                                            <td class="text-right font-weight-bold">Stunden</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['smarty_vars']->value['arbeitszeitkontoliste'], 'row');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['row']->value) {
?>
                                            <tr class="black-links">
                                                <td class="text-right"><a href="/arbeitszeitkonto/bearbeiten/<?php echo $_smarty_tpl->tpl_vars['row']->value['id'];?>
" target="_blank"><?php echo $_smarty_tpl->tpl_vars['row']->value['jahr'];?>
</a></td>
                                                <td class="text-right"><a href="/arbeitszeitkonto/bearbeiten/<?php echo $_smarty_tpl->tpl_vars['row']->value['id'];?>
" target="_blank"><?php echo $_smarty_tpl->tpl_vars['row']->value['monat'];?>
</a></td>
                                                <td class="text-right"><a href="/arbeitszeitkonto/bearbeiten/<?php echo $_smarty_tpl->tpl_vars['row']->value['id'];?>
" target="_blank"><?php echo number_format((($tmp = @$_smarty_tpl->tpl_vars['row']->value['stunden'])===null||$tmp==='' ? 0 : $tmp),2,",",".");?>
</a></td>
                                            </tr>
                                        <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

                                    </tbody>
                                </table>
                            <?php }?>
                        <?php }?>
                    <?php }?>
                <?php } elseif (($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['tab'] == 'praeferenzen')) {?>
                    <form action="/mitarbeiter/bearbeiten/<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['personalnummer'])===null||$tmp==='' ? "???" : $tmp);?>
/praeferenzen" method="post">
                        <h4 class="mt-4">Arbeitszeiten</h4>
                        <div class="row mx-0">
	                        <div class="form-group col-6 col-sm-3 col-lg px-1 mb-lg-0<?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['montag_von_error'])) {
if ($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['montag_von_error']) {?> has-danger<?php }
}?>">
		                        <label class="form-control-label">Mo<span class="hidden-lg-up">ntag</span></label>
                                <input class="form-control rounded-0 text-center px-0 inputmask" name="montag_von" data-inputmask="'mask' : '99:99'" type="text" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['montag_von'])===null||$tmp==='' ? '' : $tmp);?>
">
	                        </div>
	                        <div class="form-group col-6 col-sm-3 col-lg px-1 mb-lg-0<?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['montag_bis_error'])) {
if ($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['montag_bis_error']) {?> has-danger<?php }
}?>">
		                        <label class="form-control-label">&nbsp;</label>
                                <input class="form-control rounded-0 text-center px-0 inputmask" name="montag_bis" data-inputmask="'mask' : '99:99'" type="text" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['montag_bis'])===null||$tmp==='' ? '' : $tmp);?>
">
	                        </div>
	                        <div class="form-group col-6 col-sm-3 col-lg px-1 mb-lg-0<?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['dienstag_von_error'])) {
if ($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['dienstag_von_error']) {?> has-danger<?php }
}?>">
		                        <label class="form-control-label">Di<span class="hidden-lg-up">enstag</span></label>
                                <input class="form-control rounded-0 text-center px-0 inputmask" name="dienstag_von" data-inputmask="'mask' : '99:99'" type="text" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['dienstag_von'])===null||$tmp==='' ? '' : $tmp);?>
">
	                        </div>
	                        <div class="form-group col-6 col-sm-3 col-lg px-1 mb-lg-0<?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['dienstag_bis_error'])) {
if ($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['dienstag_bis_error']) {?> has-danger<?php }
}?>">
		                        <label class="form-control-label">&nbsp;</label>
                                <input class="form-control rounded-0 text-center px-0 inputmask" name="dienstag_bis" data-inputmask="'mask' : '99:99'" type="text" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['dienstag_bis'])===null||$tmp==='' ? '' : $tmp);?>
">
	                        </div>
	                        <div class="form-group col-6 col-sm-3 col-lg px-1 mb-lg-0<?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['mittwoch_von_error'])) {
if ($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['mittwoch_von_error']) {?> has-danger<?php }
}?>">
		                        <label class="form-control-label">Mi<span class="hidden-lg-up">ttwoch</span></label>
                                <input class="form-control rounded-0 text-center px-0 inputmask" name="mittwoch_von" data-inputmask="'mask' : '99:99'" type="text" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['mittwoch_von'])===null||$tmp==='' ? '' : $tmp);?>
">
	                        </div>
	                        <div class="form-group col-6 col-sm-3 col-lg px-1 mb-lg-0<?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['mittwoch_bis_error'])) {
if ($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['mittwoch_bis_error']) {?> has-danger<?php }
}?>">
		                        <label class="form-control-label">&nbsp;</label>
                                <input class="form-control rounded-0 text-center px-0 inputmask" name="mittwoch_bis" data-inputmask="'mask' : '99:99'" type="text" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['mittwoch_bis'])===null||$tmp==='' ? '' : $tmp);?>
">
	                        </div>
	                        <div class="form-group col-6 col-sm-3 col-lg px-1 mb-lg-0<?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['donnerstag_von_error'])) {
if ($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['donnerstag_von_error']) {?> has-danger<?php }
}?>">
		                        <label class="form-control-label">Do<span class="hidden-lg-up">nnerstag</span></label>
                                <input class="form-control rounded-0 text-center px-0 inputmask" name="donnerstag_von" data-inputmask="'mask' : '99:99'" type="text" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['donnerstag_von'])===null||$tmp==='' ? '' : $tmp);?>
">
	                        </div>
	                        <div class="form-group col-6 col-sm-3 col-lg px-1 mb-lg-0<?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['donnerstag_bis_error'])) {
if ($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['donnerstag_bis_error']) {?> has-danger<?php }
}?>">
		                        <label class="form-control-label">&nbsp;</label>
                                <input class="form-control rounded-0 text-center px-0 inputmask" name="donnerstag_bis" data-inputmask="'mask' : '99:99'" type="text" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['donnerstag_bis'])===null||$tmp==='' ? '' : $tmp);?>
">
	                        </div>
	                        <div class="form-group col-6 col-sm-3 col-lg px-1 mb-lg-0<?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['freitag_von_error'])) {
if ($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['freitag_von_error']) {?> has-danger<?php }
}?>">
		                        <label class="form-control-label">Fr<span class="hidden-lg-up">eitag</span></label>
                                <input class="form-control rounded-0 text-center px-0 inputmask" name="freitag_von" data-inputmask="'mask' : '99:99'" type="text" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['freitag_von'])===null||$tmp==='' ? '' : $tmp);?>
">
	                        </div>
	                        <div class="form-group col-6 col-sm-3 col-lg px-1 mb-lg-0<?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['freitag_bis_error'])) {
if ($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['freitag_bis_error']) {?> has-danger<?php }
}?>">
		                        <label class="form-control-label">&nbsp;</label>
                                <input class="form-control rounded-0 text-center px-0 inputmask" name="freitag_bis" data-inputmask="'mask' : '99:99'" type="text" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['freitag_bis'])===null||$tmp==='' ? '' : $tmp);?>
">
	                        </div>
	                        <div class="form-group col-6 col-sm-3 col-lg px-1 mb-lg-0<?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['samstag_von_error'])) {
if ($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['samstag_von_error']) {?> has-danger<?php }
}?>">
		                        <label class="form-control-label">Sa<span class="hidden-lg-up">mstag</span></label>
                                <input class="form-control rounded-0 text-center px-0 inputmask" name="samstag_von" data-inputmask="'mask' : '99:99'" type="text" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['samstag_von'])===null||$tmp==='' ? '' : $tmp);?>
">
	                        </div>
	                        <div class="form-group col-6 col-sm-3 col-lg px-1 mb-lg-0<?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['samstag_bis_error'])) {
if ($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['samstag_bis_error']) {?> has-danger<?php }
}?>">
		                        <label class="form-control-label">&nbsp;</label>
                                <input class="form-control rounded-0 text-center px-0 inputmask" name="samstag_bis" data-inputmask="'mask' : '99:99'" type="text" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['samstag_bis'])===null||$tmp==='' ? '' : $tmp);?>
">
	                        </div>
	                        <div class="form-group col-6 col-sm-3 col-lg px-1 mb-lg-0<?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['sonntag_von_error'])) {
if ($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['sonntag_von_error']) {?> has-danger<?php }
}?>">
		                        <label class="form-control-label">So<span class="hidden-lg-up">nntag</span></label>
                                <input class="form-control rounded-0 text-center px-0 inputmask" name="sonntag_von" data-inputmask="'mask' : '99:99'" type="text" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['sonntag_von'])===null||$tmp==='' ? '' : $tmp);?>
">
	                        </div>
	                        <div class="form-group col-6 col-sm-3 col-lg px-1 mb-lg-0<?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['sonntag_bis_error'])) {
if ($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['sonntag_bis_error']) {?> has-danger<?php }
}?>">
		                        <label class="form-control-label">&nbsp;</label>
                                <input class="form-control rounded-0 text-center px-0 inputmask" name="sonntag_bis" data-inputmask="'mask' : '99:99'" type="text" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['sonntag_bis'])===null||$tmp==='' ? '' : $tmp);?>
">
	                        </div>
                        </div>

                        <h4 class="mt-4">Freigegeben für</h4>
                        <select class="selectable selectable_multiple form-control" tabindex="-1" name="abteilungsfreigaben[]" data-placeholder=" hier klicken..." multiple>
                            <?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['abteilungsliste'])) {?>
                                <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['smarty_vars']->value['abteilungsliste'], 'row');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['row']->value) {
?>
                                    <option value="<?php echo $_smarty_tpl->tpl_vars['row']->value['id'];?>
" <?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['abteilungsfreigaben'])) {?> <?php if (in_array($_smarty_tpl->tpl_vars['row']->value['id'],$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['abteilungsfreigaben'])) {?> selected<?php }
}?>><?php echo $_smarty_tpl->tpl_vars['row']->value['bezeichnung'];?>
</option>
                                <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

                            <?php }?>
                        </select>

                        <h4 class="mt-4">Stammmitarbeiter für</h4>
                        <select class="selectable selectable_multiple form-control" tabindex="-1" name="stamm[]" data-placeholder=" hier klicken..." multiple>
                            <?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['kundenliste'])) {?>
                                <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['smarty_vars']->value['kundenliste'], 'row');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['row']->value) {
?>
                                    <option value="<?php echo $_smarty_tpl->tpl_vars['row']->value['kundennummer'];?>
" <?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['stamm'])) {?> <?php if (in_array($_smarty_tpl->tpl_vars['row']->value['kundennummer'],$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['stamm'])) {?> selected<?php }
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

                        <h4 class="mt-4">Springer für</h4>
                        <select class="selectable selectable_multiple form-control" tabindex="-1" name="springer[]" data-placeholder=" hier klicken..." multiple>
                            <?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['kundenliste'])) {?>
                                <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['smarty_vars']->value['kundenliste'], 'row');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['row']->value) {
?>
                                    <option value="<?php echo $_smarty_tpl->tpl_vars['row']->value['kundennummer'];?>
" <?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['springer'])) {?> <?php if (in_array($_smarty_tpl->tpl_vars['row']->value['kundennummer'],$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['springer'])) {?> selected<?php }
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

                        <h4 class="mt-4">Gesperrt für</h4>
                        <select class="selectable selectable_multiple form-control" tabindex="-1" name="sperre[]" data-placeholder=" hier klicken..." multiple>
                            <?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['kundenliste'])) {?>
                                <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['smarty_vars']->value['kundenliste'], 'row');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['row']->value) {
?>
                                    <option value="<?php echo $_smarty_tpl->tpl_vars['row']->value['kundennummer'];?>
" <?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['sperre'])) {?> <?php if (in_array($_smarty_tpl->tpl_vars['row']->value['kundennummer'],$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['sperre'])) {?> selected<?php }
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

                        <input type="hidden" name="praeferenzen_submitted" value="true">
                        <button type="submit" class="btn btn-secondary form-control mt-3">Speichern</button>
                    </form>
                <?php } elseif (($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['tab'] == 'lohnbuchungen')) {?>
                    <a class="btn btn-secondary btn-block my-3" href="/lohnbuchung/erstellen/<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['personalnummer'])===null||$tmp==='' ? "???" : $tmp);?>
"><i class="fa fa-plus-circle mr-1"></i> Neue Lohnbuchung hinzufügen</a>
                    <?php $_smarty_tpl->_subTemplateRender("file:views/main/components/tables.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
?>

                    <?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['lohnbuchungsliste'])) {?>
                        <?php echo (($tmp = @$_smarty_tpl->tpl_vars['table_tag']->value)===null||$tmp==='' ? "<table>" : $tmp);?>

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
                                <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['smarty_vars']->value['lohnbuchungsliste'], 'row');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['row']->value) {
?>
                                    <tr>
                                        <td class="clickable" data-href="/lohnbuchung/bearbeiten/<?php echo $_smarty_tpl->tpl_vars['row']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['row']->value['id'];?>
</td>
                                        <td class="clickable" data-href="/lohnbuchung/bearbeiten/<?php echo $_smarty_tpl->tpl_vars['row']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['row']->value['buchungsmonat'];?>
</td>
                                        <td class="clickable" data-href="/lohnbuchung/bearbeiten/<?php echo $_smarty_tpl->tpl_vars['row']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['row']->value['lohnart'];?>
</td>
                                        <td class="clickable" data-href="/lohnbuchung/bearbeiten/<?php echo $_smarty_tpl->tpl_vars['row']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['row']->value['wert'];?>
</td>
                                        <td class="clickable" data-href="/lohnbuchung/bearbeiten/<?php echo $_smarty_tpl->tpl_vars['row']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['row']->value['faktor'];?>
</td>
                                        <td class="clickable" data-href="/lohnbuchung/bearbeiten/<?php echo $_smarty_tpl->tpl_vars['row']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['row']->value['bezeichnung'];?>
</td>
                                        <td class="clickable" data-href="/lohnbuchung/bearbeiten/<?php echo $_smarty_tpl->tpl_vars['row']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['row']->value['benutzer'];?>
</td>
                                        <td class="clickable" data-href="/lohnbuchung/bearbeiten/<?php echo $_smarty_tpl->tpl_vars['row']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['row']->value['zeit'];?>
</td>
                                    </tr>
                                <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

                            </tbody>
                        </table>
                    <?php }?>
                <?php }?>
            <?php }?>
        </div>
    </div>
</div>

<?php $_smarty_tpl->smarty->ext->_capture->open($_smarty_tpl, 'styles', null, null);
?>

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

    <?php echo (($tmp = @$_smarty_tpl->tpl_vars['css']->value)===null||$tmp==='' ? '' : $tmp);?>

<?php $_smarty_tpl->smarty->ext->_capture->close($_smarty_tpl);
?>


<?php $_smarty_tpl->_assignInScope('css', $_smarty_tpl->smarty->ext->_capture->getBuffer($_smarty_tpl, 'styles') ,false ,2);
?>

<?php $_smarty_tpl->smarty->ext->_capture->open($_smarty_tpl, 'scripts', null, null);
?>

    <!-- Autosize -->
    <?php echo '<script'; ?>
 src="/assets/vendors/autosize/dist/autosize.min.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
>
        $(document).ready(function() {
            autosize($(".resizable"));
        });
    <?php echo '</script'; ?>
>
    <!-- /Autosize -->

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

    <!-- jquery.inputmask -->
    <?php echo '<script'; ?>
 src="/assets/vendors/jquery.inputmask/dist/min/jquery.inputmask.bundle.min.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
>
        $(document).ready(function () {
            $(".inputmask").inputmask();
        });
    <?php echo '</script'; ?>
>
    <!-- /jquery.inputmask-->

    <!-- Mitarbeiterswitch -->
    <?php echo '<script'; ?>
>
        $maswitch = $('#mitarbeiterswitch');
        $maswitch.change(function () {
            if ($maswitch.val() != '') {
                window.location = '/mitarbeiter/bearbeiten/' + $maswitch.val();
            }
        });
    <?php echo '</script'; ?>
>
    <!-- /Mitarbeiterswitch -->

    <?php echo (($tmp = @$_smarty_tpl->tpl_vars['js']->value)===null||$tmp==='' ? '' : $tmp);?>

<?php $_smarty_tpl->smarty->ext->_capture->close($_smarty_tpl);
?>


<?php $_smarty_tpl->_assignInScope('js', $_smarty_tpl->smarty->ext->_capture->getBuffer($_smarty_tpl, 'scripts') ,false ,2);
}
}
