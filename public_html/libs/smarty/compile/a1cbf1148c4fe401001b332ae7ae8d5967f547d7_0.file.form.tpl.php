<?php
/* Smarty version 3.1.30, created on 2019-07-11 09:42:07
  from "/usr/local/www/apache24/noexec/ttact-intern-software/views/main/Kunden/components/form.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5d26e84fed62a0_27549482',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'a1cbf1148c4fe401001b332ae7ae8d5967f547d7' => 
    array (
      0 => '/usr/local/www/apache24/noexec/ttact-intern-software/views/main/Kunden/components/form.tpl',
      1 => 1562525872,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5d26e84fed62a0_27549482 (Smarty_Internal_Template $_smarty_tpl) {
?>
<div class="row">
    <div class="form-group col-12 col-sm-6">
        <label class="form-control-label" for="kundennummer">Kundennummer <span class="required">*</span></label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-link fa-fw"></i></span>
            <input type="number" min="1" class="form-control" id="kundennummer" name="kundennummer" placeholder="Kundennummer" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['kundennummer'])===null||$tmp==='' ? '' : $tmp);?>
" required>
        </div>
    </div>
    <div class="form-group col-12 col-sm-6">
        <label class="form-control-label" for="name">Name <span class="required">*</span></label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-institution fa-fw"></i></span>
            <input type="text" class="form-control" id="name" name="name" placeholder="Musterwaren GmbH" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['name'])===null||$tmp==='' ? '' : $tmp);?>
" required>
        </div>
    </div>
</div>


<div class="form-group">
    <label class="form-control-label" for="strasse">Straße und Hausnummer</label>
    <div class="input-group">
        <span class="input-group-addon"><i class="fa fa-map-marker fa-fw"></i></span>
        <input type="text" class="form-control" id="strasse" name="strasse" placeholder="Beispielstraße 12" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['strasse'])===null||$tmp==='' ? '' : $tmp);?>
">
    </div>
</div>

<div class="row">
    <div class="form-group col-12 col-sm-6">
        <label class="form-control-label" for="postleitzahl">Postleitzahl</label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-map-marker fa-fw"></i></span>
            <input type="number" min="10000" max="99999" class="form-control" id="postleitzahl" name="postleitzahl" placeholder="12345" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['postleitzahl'])===null||$tmp==='' ? '' : $tmp);?>
">
        </div>
    </div>
    <div class="form-group col-12 col-sm-6">
        <label class="form-control-label" for="ort">Ort</label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-map-marker fa-fw"></i></span>
            <input type="text" class="form-control" id="ort" name="ort" placeholder="Musterstadt" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['ort'])===null||$tmp==='' ? '' : $tmp);?>
">
        </div>
    </div>
</div>

<div class="form-group">
    <label class="form-control-label" for="ansprechpartner">Ansprechpartner</label>
    <div class="input-group">
        <span class="input-group-addon"><i class="fa fa-user fa-fw"></i></span>
        <input type="text" class="form-control" id="ansprechpartner" name="ansprechpartner" placeholder="Max Mustermann" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['ansprechpartner'])===null||$tmp==='' ? '' : $tmp);?>
">
    </div>
</div>
<div class="row">
    <div class="form-group col-12 col-sm-6">
        <label class="form-control-label" for="telefon1">Telefon 1</label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-phone fa-fw"></i></span>
            <input type="text" class="form-control" id="telefon1" name="telefon1" placeholder="030 123456 01" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['telefon1'])===null||$tmp==='' ? '' : $tmp);?>
">
        </div>
    </div>
    <div class="form-group col-12 col-sm-6">
        <label class="form-control-label" for="telefon2">Telefon 2</label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-phone fa-fw"></i></span>
            <input type="text" class="form-control" id="telefon2" name="telefon2" placeholder="030 123456 02" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['telefon2'])===null||$tmp==='' ? '' : $tmp);?>
">
        </div>
    </div>
</div>

<div class="form-group">
    <label class="form-control-label" for="fax">Fax</label>
    <div class="input-group">
        <span class="input-group-addon"><i class="fa fa-fax fa-fw"></i></span>
        <input type="text" class="form-control" id="fax" name="fax" placeholder="030 123456 99" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['fax'])===null||$tmp==='' ? '' : $tmp);?>
">
    </div>
</div>
<div class="form-group">
    <label class="form-control-label" for="emailadresse">E-Mail-Adresse</label>
    <div class="input-group">
        <span class="input-group-addon"><i class="fa fa-envelope fa-fw"></i></span>
        <input type="email" class="form-control" id="emailadresse" name="emailadresse" placeholder="beispiel@domain.de" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['emailadresse'])===null||$tmp==='' ? '' : $tmp);?>
">
    </div>
</div>
<div class="form-group">
    <label class="form-control-label" for="rechnungsanschrift">Rechnungsanschrift</label>
    <div class="input-group">
        <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
        <textarea class="form-control resizable" id="rechnungsanschrift" name="rechnungsanschrift" placeholder="Mit Zeilenumbrüchen, wie bei einem Briefkopf."><?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['rechnungsanschrift'])===null||$tmp==='' ? '' : $tmp);?>
</textarea>
    </div>
</div>
<?php if ($_smarty_tpl->tpl_vars['smarty_vars']->value['company'] == 'tps') {?>
    <div class="form-group">
        <label class="form-control-label" for="rechnungszusatz">Rechnungszusatz</label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
            <input type="text" class="form-control" id="rechnungszusatz" name="rechnungszusatz" placeholder="Kostenstelle: XYZ" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['rechnungszusatz'])===null||$tmp==='' ? '' : $tmp);?>
">
        </div>
    </div>
<?php } else { ?>
    <div class="row">
        <div class="form-group col-12 col-sm-6">
            <label class="form-control-label" for="rechnungszusatz">Rechnungszusatz</label>
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                <input type="text" class="form-control" id="rechnungszusatz" name="rechnungszusatz" placeholder="Kostenstelle: XYZ" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['rechnungszusatz'])===null||$tmp==='' ? '' : $tmp);?>
">
            </div>
        </div>
        <div class="form-group col-12 col-sm-6">
            <label class="form-control-label" for="unterzeichnungsdatum_rahmenvertrag">Rahmenvertrag vom</label>
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-calendar fa-fw"></i></span>
                <input name="unterzeichnungsdatum_rahmenvertrag" type="text" class="form-control" id="unterzeichnungsdatum_rahmenvertrag" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['unterzeichnungsdatum_rahmenvertrag'])===null||$tmp==='' ? '' : $tmp);?>
" placeholder="TT.MM.JJJJ">
            </div>
        </div>
    </div>
<?php }?>
<button type="submit" class="btn btn-secondary form-control" name="submitted" value="true">Speichern</button>

<?php $_smarty_tpl->_assignInScope('js', '
    <!-- Autosize -->
    <script src="/assets/vendors/autosize/dist/autosize.min.js"></script>

    <!-- Autosize -->
    <script>
        $(document).ready(function() {
            autosize($(".resizable"));
        });
    </script>
' ,false ,2);
}
}
