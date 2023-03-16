<?php
/* Smarty version 3.1.30, created on 2019-07-19 17:56:20
  from "/usr/local/www/apache24/noexec/ttact-intern-software/views/main/Berechnungen/uebersicht.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5d31e8248992e3_72119493',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'c60e6c8e39130283f3529611329382205239268d' => 
    array (
      0 => '/usr/local/www/apache24/noexec/ttact-intern-software/views/main/Berechnungen/uebersicht.tpl',
      1 => 1562525865,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:views/main/components/tables.tpl' => 1,
  ),
),false)) {
function content_5d31e8248992e3_72119493 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_assignInScope('title', "Übersicht" ,false ,2);
?>

<form action="/berechnungen/uebersicht" method="post">
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
        <div class="row mb-4">
            <div class="col">
                <div class="content-box">
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
                    <button type="submit" name="action" value="anzeigen" class="btn btn-secondary form-control">Anzeigen</button>
                </div>
            </div>
        </div>
    </div>

    <?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['liste'])) {?>
        <div class="row mb-4">
            <div class="col">
                <div class="content-box">
                    <?php $_smarty_tpl->_subTemplateRender("file:views/main/components/tables.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>


                    <?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['liste'])) {?>
                        <?php echo (($tmp = @$_smarty_tpl->tpl_vars['table_tag']->value)===null||$tmp==='' ? "<table>" : $tmp);?>

                            <thead>
                                <tr>
                                    <th>&nbsp;</th>
                                    <th class="text-right">P.Nr.</th>
                                    <th>Name</th>
                                    <th class="text-right">Eintritt</th>
                                    <th class="text-right">Austritt</th>
                                    <th class="text-right">Wochenstd.</th>
                                    <th class="text-right">Krankheitsstd.</th>
                                    <th class="text-right">Urlaubsstd.</th>
                                    <th class="text-right">Feiertagsstd.</th>
                                    <th class="text-right">Importstd.</th>
                                    <th class="text-right">Schichtstd.</th>
                                    <th class="text-right">Gesamtstd.</th>
                                    <th class="text-right">Sollstd.</th>
                                    <th class="text-right">AZK Vormonat</th>
                                    <th class="text-right">AZK Aktuell</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['smarty_vars']->value['liste'], 'row');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['row']->value) {
?>
                                    <tr>
                                        <td><input type="checkbox" name="export[]" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['row']->value['export'])===null||$tmp==='' ? '' : $tmp);?>
" checked></td>
                                        <td class="text-right"><?php echo $_smarty_tpl->tpl_vars['row']->value['personalnummer'];?>
</td>
                                        <td><?php echo $_smarty_tpl->tpl_vars['row']->value['vorname'];?>
 <?php echo $_smarty_tpl->tpl_vars['row']->value['nachname'];?>
</td>
                                        <td class="text-right"><?php echo $_smarty_tpl->tpl_vars['row']->value['eintritt'];?>
</td>
                                        <td class="text-right"><?php echo $_smarty_tpl->tpl_vars['row']->value['austritt'];?>
</td>
                                        <td class="text-right"><?php echo number_format((($tmp = @$_smarty_tpl->tpl_vars['row']->value['wochenstunden'])===null||$tmp==='' ? 0 : $tmp),2,",",".");?>
</td>
                                        <td class="text-right"><?php echo number_format((($tmp = @$_smarty_tpl->tpl_vars['row']->value['stunden_krankheit'])===null||$tmp==='' ? 0 : $tmp),2,",",".");?>
</td>
                                        <td class="text-right"><?php echo number_format((($tmp = @$_smarty_tpl->tpl_vars['row']->value['stunden_urlaub'])===null||$tmp==='' ? 0 : $tmp),2,",",".");?>
</td>
                                        <td class="text-right"><?php echo number_format((($tmp = @$_smarty_tpl->tpl_vars['row']->value['stunden_feiertag'])===null||$tmp==='' ? 0 : $tmp),2,",",".");?>
</td>
                                        <td class="text-right"><?php echo number_format((($tmp = @$_smarty_tpl->tpl_vars['row']->value['stunden_import'])===null||$tmp==='' ? 0 : $tmp),2,",",".");?>
</td>
                                        <td class="text-right"><?php echo number_format((($tmp = @$_smarty_tpl->tpl_vars['row']->value['stunden_schichten'])===null||$tmp==='' ? 0 : $tmp),2,",",".");?>
</td>
                                        <td class="text-right"><?php echo number_format((($tmp = @$_smarty_tpl->tpl_vars['row']->value['stunden_insgesamt'])===null||$tmp==='' ? 0 : $tmp),2,",",".");?>
</td>
                                        <td class="text-right"><?php echo number_format((($tmp = @$_smarty_tpl->tpl_vars['row']->value['stunden_soll'])===null||$tmp==='' ? 0 : $tmp),2,",",".");?>
</td>
                                        <td class="text-right"><?php echo number_format((($tmp = @$_smarty_tpl->tpl_vars['row']->value['azk_vormonat'])===null||$tmp==='' ? 0 : $tmp),2,",",".");?>
</td>
                                        <td class="text-right"><?php echo number_format((($tmp = @$_smarty_tpl->tpl_vars['row']->value['azk_aktuell'])===null||$tmp==='' ? 0 : $tmp),2,",",".");?>
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
                </div>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col">
                <div class="content-box">
                    <input type="hidden" name="filename" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['filename'])===null||$tmp==='' ? "???" : $tmp);?>
">
                    <button type="submit" name="action" value="exportieren" class="btn btn-secondary btn-block" onclick='this.form.target="_blank";'>Exportieren</button>
                </div>
            </div>
        </div>

        <?php $_smarty_tpl->_assignInScope('css', $_smarty_tpl->tpl_vars['css']->value ,true ,2);
?>

        <?php $_smarty_tpl->_assignInScope('js', $_smarty_tpl->tpl_vars['js']->value ,true ,2);
?>
    <?php }?>
</form><?php }
}
