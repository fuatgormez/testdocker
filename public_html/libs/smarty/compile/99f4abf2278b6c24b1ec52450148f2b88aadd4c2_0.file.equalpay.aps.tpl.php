<?php
/* Smarty version 3.1.30, created on 2019-07-12 10:28:30
  from "/usr/local/www/apache24/noexec/ttact-intern-software/views/main/Mitarbeiter/equalpay.aps.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5d2844ae56d178_44203043',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '99f4abf2278b6c24b1ec52450148f2b88aadd4c2' => 
    array (
      0 => '/usr/local/www/apache24/noexec/ttact-intern-software/views/main/Mitarbeiter/equalpay.aps.tpl',
      1 => 1562525868,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5d2844ae56d178_44203043 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_assignInScope('container_class', "container" ,false ,2);
?>

<?php $_smarty_tpl->_assignInScope('title', "Mitarbeiter | Equal Pay" ,false ,2);
?>

<?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['liste'])) {?>
    <div class="row mb-4">
        <div class="col">
            <div class="content-box">
                <table class="table table-striped table-bordered table-hover dt-responsive nowrap mb-0" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            
                            <th>Mitarbeiter</th>
                            <th>Kunde</th>
                            <th class="text-right">Prozentsatz</th>
                            <th>Equal Pay</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['smarty_vars']->value['liste'], 'row');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['row']->value) {
?>
                            <tr<?php if ($_smarty_tpl->tpl_vars['row']->value['class'] != '') {?> class="table-<?php echo $_smarty_tpl->tpl_vars['row']->value['class'];?>
"<?php }?>>
                                
                                <td><?php echo (($tmp = @$_smarty_tpl->tpl_vars['row']->value['personalnummer'])===null||$tmp==='' ? "???" : $tmp);?>
 - <?php echo (($tmp = @$_smarty_tpl->tpl_vars['row']->value['nachname'])===null||$tmp==='' ? "???" : $tmp);?>
, <?php echo (($tmp = @$_smarty_tpl->tpl_vars['row']->value['vorname'])===null||$tmp==='' ? "???" : $tmp);?>
</td>
                                <td><?php echo (($tmp = @$_smarty_tpl->tpl_vars['row']->value['kunde'])===null||$tmp==='' ? "???" : $tmp);?>
</td>
                                <td class="text-right"><?php echo (($tmp = @$_smarty_tpl->tpl_vars['row']->value['prozentsatz'])===null||$tmp==='' ? "???" : $tmp);?>
 %</td>
                                <td><?php echo (($tmp = @$_smarty_tpl->tpl_vars['row']->value['equalpay'])===null||$tmp==='' ? '' : $tmp);?>
</td></td>
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

    
<?php }?>

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
