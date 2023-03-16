<?php
/* Smarty version 3.1.30, created on 2019-07-12 16:52:37
  from "/usr/local/www/apache24/noexec/ttact-intern-software/views/main/Mitarbeiter/kalenderuebersicht.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5d289eb5823483_35735905',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'c98dd8cbf67276599809b64e2113a974cc16c872' => 
    array (
      0 => '/usr/local/www/apache24/noexec/ttact-intern-software/views/main/Mitarbeiter/kalenderuebersicht.tpl',
      1 => 1562525866,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5d289eb5823483_35735905 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_assignInScope('title', "Mitarbeiter | Kalenderübersicht" ,false ,2);
?>

<div class="row mb-4">
    <div class="col">
        <div class="content-box">
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

            <h3><a class="change-month-button" href="\mitarbeiter\kalenderuebersicht\<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['prev_year'])===null||$tmp==='' ? "???" : $tmp);?>
\<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['prev_month'])===null||$tmp==='' ? "???" : $tmp);?>
"><i class="fa fa-chevron-circle-left mr-1"></i></a> <?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['monatsname'])===null||$tmp==='' ? "???" : $tmp);?>
 <?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['year'])===null||$tmp==='' ? "???" : $tmp);?>
 <a class="change-month-button" href="\mitarbeiter\kalenderuebersicht\<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['next_year'])===null||$tmp==='' ? "???" : $tmp);?>
\<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['next_month'])===null||$tmp==='' ? "???" : $tmp);?>
"><i class="fa fa-chevron-circle-right ml-1"></i></a></h3>

            <div class="mt-4"><strong>Kb</strong>: Krank (bezahlt), <strong>Ub</strong>: Urlaub (bezahlt), <strong>Kk</strong>: Kind krank (unbezahlt), <strong>F</strong>: Frei (unbezahlt), <strong>Ku</strong>: Krank (unbezahlt), <strong>uF</strong>: Unentschuldigt Fehlen (unbezahlt), <strong>FT</strong>: Feiertag (bezahlt), <strong>FZ</strong>: Fehlzeit (unbezahlt), <strong>Ug</strong>: Urlaub genehmigt (unbezahlt)</div>

            <?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['kalenderuebersicht'])) {?>
                <table class="table table-bordered mt-4">
                    <thead>
                        <tr>
                            <td>Mitarbeiter</td>
                            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['smarty_vars']->value['kalendertageliste'], 'col');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['col']->value) {
?>
                                <th class="text-center"><?php echo (($tmp = @$_smarty_tpl->tpl_vars['col']->value)===null||$tmp==='' ? "&nbsp;" : $tmp);?>
</th>
                            <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

                        </tr>
                    </thead>
                    <tbody>
                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['smarty_vars']->value['kalenderuebersicht'], 'row', false, 'personalnummer');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['personalnummer']->value => $_smarty_tpl->tpl_vars['row']->value) {
?>
                            <tr>
                                <th><?php echo $_smarty_tpl->tpl_vars['personalnummer']->value;?>
 - <?php echo $_smarty_tpl->tpl_vars['smarty_vars']->value['mitarbeiternamen'][$_smarty_tpl->tpl_vars['personalnummer']->value];?>
</th>
                                <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['row']->value, 'col');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['col']->value) {
?>
                                    <td class="text-center"<?php if ($_smarty_tpl->tpl_vars['col']->value != '') {?> style="background-color:#333; color:white;"<?php }?>><?php echo (($tmp = @$_smarty_tpl->tpl_vars['col']->value)===null||$tmp==='' ? "&nbsp;" : $tmp);?>
</td>
                                <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

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

<?php $_smarty_tpl->_assignInScope('css', '
    <style>
        a.change-month-button {
            color: black;
        }
        a.change-month-button:hover {
            color: #333;
        }
    </style>
' ,false ,2);
}
}
