<?php
/* Smarty version 3.1.30, created on 2019-07-11 12:44:32
  from "/usr/local/www/apache24/noexec/ttact-intern-software/views/main/Mitarbeiter/inaktiv.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5d271310c63233_96678169',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '4f0deba7b09cc95ad419183a413ead7155b0e6cf' => 
    array (
      0 => '/usr/local/www/apache24/noexec/ttact-intern-software/views/main/Mitarbeiter/inaktiv.tpl',
      1 => 1562525866,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:views/main/components/tables.tpl' => 1,
  ),
),false)) {
function content_5d271310c63233_96678169 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_assignInScope('title', "Mitarbeiter | Inaktive anzeigen" ,false ,2);
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


            <?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['mitarbeiterliste'])) {?>
                <?php echo (($tmp = @$_smarty_tpl->tpl_vars['table_tag']->value)===null||$tmp==='' ? "<table>" : $tmp);?>

                    <thead>
                        <tr>
                            <th>Pers. #</th>
                            <th>Vorname</th>
                            <th>Nachname</th>
                            <th>Telefon</th>
                            <th>E-Mail</th>
                            <th>Adresse</th>
                            <th>Geburtsd.</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['smarty_vars']->value['mitarbeiterliste'], 'row');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['row']->value) {
?>
                            <tr>
                                <th role="row"><?php echo $_smarty_tpl->tpl_vars['row']->value['personalnummer'];?>
</th>
                                <td class="clickable" data-href="/mitarbeiter/bearbeiten/<?php echo $_smarty_tpl->tpl_vars['row']->value['personalnummer'];?>
"><?php echo $_smarty_tpl->tpl_vars['row']->value['vorname'];?>
</td>
                                <td class="clickable" data-href="/mitarbeiter/bearbeiten/<?php echo $_smarty_tpl->tpl_vars['row']->value['personalnummer'];?>
"><?php echo $_smarty_tpl->tpl_vars['row']->value['nachname'];?>
</td>
                                <td class="clickable" data-href="/mitarbeiter/bearbeiten/<?php echo $_smarty_tpl->tpl_vars['row']->value['personalnummer'];?>
"><?php echo $_smarty_tpl->tpl_vars['row']->value['telefon1'];
if ($_smarty_tpl->tpl_vars['row']->value['telefon2'] != '') {
if ($_smarty_tpl->tpl_vars['row']->value['telefon1'] != '') {?>, <?php }
echo $_smarty_tpl->tpl_vars['row']->value['telefon2'];
}?></td>
                                <td class="clickable" data-href="/mitarbeiter/bearbeiten/<?php echo $_smarty_tpl->tpl_vars['row']->value['personalnummer'];?>
"><?php echo $_smarty_tpl->tpl_vars['row']->value['emailadresse'];?>
</td>
                                <td class="clickable" data-href="/mitarbeiter/bearbeiten/<?php echo $_smarty_tpl->tpl_vars['row']->value['personalnummer'];?>
"><?php echo $_smarty_tpl->tpl_vars['row']->value['strasse'];
if ($_smarty_tpl->tpl_vars['row']->value['hausnummer'] != '') {?> <?php echo $_smarty_tpl->tpl_vars['row']->value['hausnummer'];
}
if ($_smarty_tpl->tpl_vars['row']->value['adresszusatz'] != '') {?>, <?php echo $_smarty_tpl->tpl_vars['row']->value['adresszusatz'];
}
if ($_smarty_tpl->tpl_vars['row']->value['postleitzahl'] != '') {?>, <?php echo $_smarty_tpl->tpl_vars['row']->value['postleitzahl'];
}
if ($_smarty_tpl->tpl_vars['row']->value['ort'] != '') {?> <?php echo $_smarty_tpl->tpl_vars['row']->value['ort'];
}?></td>
                                <td class="clickable" data-href="/mitarbeiter/bearbeiten/<?php echo $_smarty_tpl->tpl_vars['row']->value['personalnummer'];?>
"><?php echo $_smarty_tpl->tpl_vars['row']->value['geburtsdatum'];?>
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
