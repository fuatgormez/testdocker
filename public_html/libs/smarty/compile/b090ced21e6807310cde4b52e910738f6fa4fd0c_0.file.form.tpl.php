<?php
/* Smarty version 3.1.30, created on 2019-07-12 16:01:30
  from "/usr/local/www/apache24/noexec/ttact-intern-software/views/main/Kundenkondition/components/form.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5d2892ba184a48_77202307',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'b090ced21e6807310cde4b52e910738f6fa4fd0c' => 
    array (
      0 => '/usr/local/www/apache24/noexec/ttact-intern-software/views/main/Kundenkondition/components/form.tpl',
      1 => 1562525872,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5d2892ba184a48_77202307 (Smarty_Internal_Template $_smarty_tpl) {
?>

<div class="row">
    <div class="form-group col-12 col-sm-6">
        <label class="form-control-label" for="gueltig_ab">Gültig ab</label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-calendar fa-fw"></i></span>
            <input name="gueltig_ab" type="text" class="form-control" id="gueltig_ab" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['gueltig_ab'])===null||$tmp==='' ? '' : $tmp);?>
" placeholder="TT.MM.JJJJ">
        </div>
    </div>
    <div class="form-group col-12 col-sm-6">
        <label class="form-control-label" for="gueltig_ab">Gültig bis</label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-calendar fa-fw"></i></span>
            <input name="gueltig_bis" type="text" class="form-control" id="gueltig_bis" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['gueltig_bis'])===null||$tmp==='' ? '' : $tmp);?>
" placeholder="TT.MM.JJJJ">
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-12<?php if ($_smarty_tpl->tpl_vars['smarty_vars']->value['company'] == 'tps') {
if ($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['palettenabteilung']) {?> col-sm-6<?php }
}?>"<?php if ($_smarty_tpl->tpl_vars['smarty_vars']->value['company'] == 'tps') {?> id="abteilung-container"<?php }?>>
        <label class="form-control-label" for="abteilung">Abteilung <span class="required">*</span></label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-tasks fa-fw"></i></span>
            <select name="abteilung" class="form-control" id="abteilung">
                <?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['abteilungsliste'])) {?>
                    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['smarty_vars']->value['abteilungsliste'], 'row');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['row']->value) {
?>
                        <option value="<?php echo $_smarty_tpl->tpl_vars['row']->value['id'];?>
"<?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['abteilung'])) {
if ($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['abteilung'] == $_smarty_tpl->tpl_vars['row']->value['id']) {?> selected<?php }
}
if ($_smarty_tpl->tpl_vars['smarty_vars']->value['company'] == 'tps') {?> data-palettenabteilung="<?php echo $_smarty_tpl->tpl_vars['row']->value['palettenabteilung'];?>
"<?php }?>><?php echo $_smarty_tpl->tpl_vars['row']->value['bezeichnung'];?>
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
    <?php if ($_smarty_tpl->tpl_vars['smarty_vars']->value['company'] == 'tps') {?>
        <div class="form-group col-12 col-sm-6" id="zeit_pro_palette-container"<?php if (!$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['palettenabteilung']) {?> style="display: none;"<?php }?>>
            <label class="form-control-label" for="zeit_pro_palette">Zeit pro Palette (h) <span class="required">*</span></label>
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-clock-o fa-fw"></i></span>
                <input name="zeit_pro_palette" type="text" data-inputmask="'mask' : '99:99'" class="form-control inputmask" id="zeit_pro_palette" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['zeit_pro_palette'])===null||$tmp==='' ? '' : $tmp);?>
" placeholder="01:30">
            </div>
        </div>
    <?php }?>
</div>
<div class="row">
    <div class="form-group col-12 col-sm-6">
        <label class="form-control-label" for="preis">Preis <span class="required">*</span></label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-eur fa-fw"></i></span>
            <input name="preis" type="text" class="form-control" id="preis" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['preis'])===null||$tmp==='' ? '' : $tmp);?>
" placeholder="z.B. 17.90">
        </div>
    </div>
    <div class="form-group col-12 col-sm-6">
        <label class="form-control-label" for="sonntagszuschlag">Sonntagszuschlag (%) <span class="required">*</span></label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-percent fa-fw"></i></span>
            <input name="sonntagszuschlag" type="text" class="form-control" id="sonntagszuschlag" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['sonntagszuschlag'])===null||$tmp==='' ? '' : $tmp);?>
" placeholder="z.B. 50">
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-12 col-sm-6">
        <label class="form-control-label" for="feiertagszuschlag">Feiertagszuschlag (%) <span class="required">*</span></label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-percent fa-fw"></i></span>
            <input name="feiertagszuschlag" type="text" class="form-control" id="feiertagszuschlag" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['feiertagszuschlag'])===null||$tmp==='' ? '' : $tmp);?>
" placeholder="z.B. 100">
        </div>
    </div>
    <div class="form-group col-12 col-sm-6">
        <label class="form-control-label" for="nachtzuschlag">Nachtzuschlag (%) <span class="required">*</span></label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-percent fa-fw"></i></span>
            <input name="nachtzuschlag" type="text" class="form-control" id="nachtzuschlag" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['nachtzuschlag'])===null||$tmp==='' ? '' : $tmp);?>
" placeholder="z.B. 25">
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-12 col-sm-6">
        <label class="form-control-label" for="nacht_von">Nacht von <span class="required">*</span></label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-clock-o fa-fw"></i></span>
            <input name="nacht_von" type="text" data-inputmask="'mask' : '99:99'" class="form-control inputmask" id="nacht_von" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['nacht_von'])===null||$tmp==='' ? '' : $tmp);?>
" placeholder="23:00">
        </div>
    </div>
    <div class="form-group col-12 col-sm-6">
        <label class="form-control-label" for="nacht_bis">Nacht bis <span class="required">*</span></label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-clock-o fa-fw"></i></span>
            <input name="nacht_bis" type="text" data-inputmask="'mask' : '99:99'" class="form-control inputmask" id="nacht_bis" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['nacht_bis'])===null||$tmp==='' ? '' : $tmp);?>
" placeholder="06:00">
        </div>
    </div>
</div>
<button type="submit" class="btn btn-secondary form-control">Speichern</button>

<?php $_smarty_tpl->smarty->ext->_capture->open($_smarty_tpl, 'scripts', null, null);
?>

    <?php if ($_smarty_tpl->tpl_vars['smarty_vars']->value['company'] == 'tps') {?>
        <!-- Custom JS -->
        <?php echo '<script'; ?>
>
            $(document).ready(function () {
                var abteilung = $('#abteilung');

                abteilung.change(function () {
                    if (abteilung.find(':selected').data('palettenabteilung') == 'ja') {
                        if (!$('#abteilung-container').hasClass('col-sm-6')) {
                            $('#abteilung-container').addClass('col-sm-6');
                        }
                        $('#zeit_pro_palette-container').show();
                    } else {
                        if ($('#abteilung-container').hasClass('col-sm-6')) {
                            $('#abteilung-container').removeClass('col-sm-6');
                        }
                        $('#zeit_pro_palette-container').hide();
                    }
                });
            });
        <?php echo '</script'; ?>
>
        <!-- /Custom JS-->
    <?php }?>

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
<?php $_smarty_tpl->smarty->ext->_capture->close($_smarty_tpl);
?>


<?php $_smarty_tpl->_assignInScope('js', $_smarty_tpl->smarty->ext->_capture->getBuffer($_smarty_tpl, 'scripts') ,false ,2);
}
}
