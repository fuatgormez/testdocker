<?php
/* Smarty version 3.1.30, created on 2019-07-11 09:42:05
  from "/usr/local/www/apache24/noexec/ttact-intern-software/views/main/Kunden/index.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5d26e84de605c6_60449827',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'c547ea9196a57f6e76cd0c59db2c0def679c2fd9' => 
    array (
      0 => '/usr/local/www/apache24/noexec/ttact-intern-software/views/main/Kunden/index.tpl',
      1 => 1562525856,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:views/main/components/tables.tpl' => 1,
  ),
),false)) {
function content_5d26e84de605c6_60449827 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_assignInScope('title', "Kunden | Alle anzeigen" ,false ,2);
?>

<div class="row mb-4">
    <div class="col">
        <div class="content-box">
            <?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['error'])) {?>
                <div class="alert alert-danger">
                    <?php echo $_smarty_tpl->tpl_vars['smarty_vars']->value['error'];?>

                </div>
            <?php }?>
            
            <?php $_smarty_tpl->_subTemplateRender("file:views/main/components/tables.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>


            <?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['kundenliste'])) {?>
                <?php echo (($tmp = @$_smarty_tpl->tpl_vars['table_tag']->value)===null||$tmp==='' ? "<table>" : $tmp);?>

                    <thead>
                        <tr>
                            <th>Kundennr.</th>
                            <th>Name</th>
                            <th>Adresse</th>
                            <th>Ansprechpartner</th>
                            <th>Telefon</th>
                            <th>Fax</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['smarty_vars']->value['kundenliste'], 'row');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['row']->value) {
?>
                            <tr>
                                <th role="row"><?php echo $_smarty_tpl->tpl_vars['row']->value['kundennummer'];?>
</th>
                                <td class="clickable" data-href="/kunden/bearbeiten/<?php echo $_smarty_tpl->tpl_vars['row']->value['kundennummer'];?>
"><?php echo $_smarty_tpl->tpl_vars['row']->value['name'];?>
</td>
                                <td class="clickable" data-href="/kunden/bearbeiten/<?php echo $_smarty_tpl->tpl_vars['row']->value['kundennummer'];?>
"><?php echo $_smarty_tpl->tpl_vars['row']->value['strasse'];
if ($_smarty_tpl->tpl_vars['row']->value['postleitzahl'] != '') {?>, <?php echo $_smarty_tpl->tpl_vars['row']->value['postleitzahl'];
}
if ($_smarty_tpl->tpl_vars['row']->value['ort'] != '') {?> <?php echo $_smarty_tpl->tpl_vars['row']->value['ort'];
}?></td>
                                <td class="clickable" data-href="/kunden/bearbeiten/<?php echo $_smarty_tpl->tpl_vars['row']->value['kundennummer'];?>
"><?php echo $_smarty_tpl->tpl_vars['row']->value['ansprechpartner'];?>
</td>
                                <td class="clickable" data-href="/kunden/bearbeiten/<?php echo $_smarty_tpl->tpl_vars['row']->value['kundennummer'];?>
"><?php echo $_smarty_tpl->tpl_vars['row']->value['telefon1'];
if ($_smarty_tpl->tpl_vars['row']->value['telefon2'] != '') {?>, <?php echo $_smarty_tpl->tpl_vars['row']->value['telefon2'];
}?></td>
                                <td class="clickable" data-href="/kunden/bearbeiten/<?php echo $_smarty_tpl->tpl_vars['row']->value['kundennummer'];?>
"><?php echo $_smarty_tpl->tpl_vars['row']->value['fax'];?>
</td>
                                <td class="clickable" data-href="/kunden/bearbeiten/<?php echo $_smarty_tpl->tpl_vars['row']->value['kundennummer'];?>
"><?php echo $_smarty_tpl->tpl_vars['row']->value['emailadresse'];?>
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

<?php $_smarty_tpl->_assignInScope('css', $_smarty_tpl->tpl_vars['css']->value ,false ,2);
?>

<?php $_smarty_tpl->_assignInScope('js', $_smarty_tpl->tpl_vars['js']->value ,false ,2);
}
}
