<?php
/* Smarty version 3.1.30, created on 2019-07-18 13:20:56
  from "/usr/local/www/apache24/noexec/ttact-intern-software/views/main/Rechnungen/bearbeiten.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5d305618211f75_67393204',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'bca9132f2c3fcd3fd5710071f0a7c68533298991' => 
    array (
      0 => '/usr/local/www/apache24/noexec/ttact-intern-software/views/main/Rechnungen/bearbeiten.tpl',
      1 => 1562525855,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5d305618211f75_67393204 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_assignInScope('container_class', "container" ,false ,2);
?>

<?php $_smarty_tpl->_assignInScope('title', "Rechnungen | bearbeiten" ,false ,2);
?>

<div class="row mb-4">
    <div class="col">
        <div class="content-box">
            <h2 class="mb-0">Rechnung Nr. <strong><?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['rechnungsnummer'])===null||$tmp==='' ? "???" : $tmp);?>
</strong></h2>
        </div>
    </div>
</div>

<?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['rechnungsposten'])) {?>
    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['smarty_vars']->value['rechnungsposten'], 'row');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['row']->value) {
?>
        <div class="row mb-4">
            <div class="col">
                <div class="content-box">
                    <form action="/rechnungen/bearbeiten" method="post">
                        <div class="row">
                            <div class="form-group col-12 col-md-6">
                                <label class="form-control-label">Leistungsart</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                    <input type="text" class="form-control" name="leistungsart" placeholder="Kasse" value="<?php echo $_smarty_tpl->tpl_vars['row']->value['leistungsart'];?>
" required>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-3">
                                <label class="form-control-label">Menge</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calculator fa-fw"></i></span>
                                    <input type="text" class="form-control" name="menge" placeholder="25" value="<?php echo $_smarty_tpl->tpl_vars['row']->value['menge'];?>
" required>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-3">
                                <label class="form-control-label">Einzelpreis</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calculator fa-fw"></i></span>
                                    <input type="text" class="form-control" name="einzelpreis" placeholder="15,95" value="<?php echo $_smarty_tpl->tpl_vars['row']->value['einzelpreis'];?>
" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <button type="submit" name="delete_and_update" value="true" class="btn btn-danger form-control">Löschen & Aktualisieren</button>
                            </div>
                            <div class="col-12 mt-3 col-md-6 mt-md-0">
                                <button type="submit" name="update" value="true" class="btn btn-secondary form-control">Aktualisieren</button>
                            </div>
                        </div>
                        <input type="hidden" name="rechnungsposten_id" value="<?php echo $_smarty_tpl->tpl_vars['row']->value['rechnungsposten_id'];?>
">
                        <input type="hidden" name="rechnung_data" value="<?php echo $_smarty_tpl->tpl_vars['smarty_vars']->value['values']['rechnung_data'];?>
">
                        <input type="hidden" name="rechnungsposten_data" value="<?php echo $_smarty_tpl->tpl_vars['smarty_vars']->value['values']['rechnungsposten_data'];?>
">
                    </form>
                </div>
            </div>
        </div>
    <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

<?php }?>

<div class="row mb-4">
    <div class="col">
        <div class="content-box">
            <form action="/rechnungen/bearbeiten" method="post">
                <div class="row">
                    <div class="form-group col-12 col-md-6">
                        <label class="form-control-label">Leistungsart</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                            <input type="text" class="form-control" name="leistungsart" placeholder="Kasse" required>
                        </div>
                    </div>
                    <div class="form-group col-12 col-md-3">
                        <label class="form-control-label">Menge</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-calculator fa-fw"></i></span>
                            <input type="text" class="form-control" name="menge" placeholder="25" required>
                        </div>
                    </div>
                    <div class="form-group col-12 col-md-3">
                        <label class="form-control-label">Einzelpreis</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-calculator fa-fw"></i></span>
                            <input type="text" class="form-control" name="einzelpreis" placeholder="15,95" required>
                        </div>
                    </div>
                </div>
                <button type="submit" name="create_and_update" value="true" class="btn btn-secondary form-control">Aktualisieren</button>
                <input type="hidden" name="rechnung_data" value="<?php echo $_smarty_tpl->tpl_vars['smarty_vars']->value['values']['rechnung_data'];?>
">
                <input type="hidden" name="rechnungsposten_data" value="<?php echo $_smarty_tpl->tpl_vars['smarty_vars']->value['values']['rechnungsposten_data'];?>
">
            </form>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col">
        <div class="content-box">
            <form action="/rechnungen/anzeigen" method="post">
                <div class="row">
                    <div class="form-group col-12 mb-0">
                        <input type="hidden" name="rechnung_data" value="<?php echo $_smarty_tpl->tpl_vars['smarty_vars']->value['values']['rechnung_data'];?>
">
                        <input type="hidden" name="rechnungsposten_data" value="<?php echo $_smarty_tpl->tpl_vars['smarty_vars']->value['values']['rechnungsposten_data'];?>
">
                        <button type="submit" class="btn btn-secondary form-control">Rechnung speichern</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $_smarty_tpl->smarty->ext->_capture->open($_smarty_tpl, 'css', null, null);
?>

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
