<?php
/* Smarty version 3.1.30, created on 2019-08-05 14:11:17
  from "/usr/local/www/apache24/noexec/ttact-intern-software/views/main/Tarife/bearbeiten.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5d481ce52a80b7_39110764',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '620baf74b46568dfbefcf4e2ae03a3c8492114ce' => 
    array (
      0 => '/usr/local/www/apache24/noexec/ttact-intern-software/views/main/Tarife/bearbeiten.tpl',
      1 => 1562525871,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:views/main/Tarife/components/form.tpl' => 1,
    'file:views/main/components/tables.tpl' => 1,
  ),
),false)) {
function content_5d481ce52a80b7_39110764 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_assignInScope('container_class', "container" ,false ,2);
?>

<?php $_smarty_tpl->_assignInScope('title', "Tarife | bearbeiten" ,false ,2);
?>

<div class="row mb-4">
    <div class="col">
        <div class="content-box">
            <h3 class="content-box-title">Tarif Nr. <?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['id'])===null||$tmp==='' ? "???" : $tmp);?>
: <strong><?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['bezeichnung'])===null||$tmp==='' ? "???" : $tmp);?>
</strong></h3>

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

            <h4>Bezeichnung</h4>
            <form action="/tarife/bearbeiten/<?php echo $_smarty_tpl->tpl_vars['smarty_vars']->value['values']['id'];?>
" method="post">
                <?php $_smarty_tpl->_subTemplateRender("file:views/main/Tarife/components/form.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

            </form>

            <h4 class="mt-5">Tariflohnbeträge</h4>
            <a class="btn btn-secondary btn-block my-3" href="/tariflohnbetrag/erstellen/<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['id'])===null||$tmp==='' ? "???" : $tmp);?>
"><i class="fa fa-plus-circle mr-1"></i> Neuen Tariflohnbetrag hinzufügen</a>
            <?php $_smarty_tpl->_subTemplateRender("file:views/main/components/tables.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

            <?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['tariflohnbetragsliste'])) {?>
                <?php echo (($tmp = @$_smarty_tpl->tpl_vars['table_tag']->value)===null||$tmp==='' ? "<table>" : $tmp);?>

                <thead>
                <tr>
                    <td>Gültig ab</td>
                    <td>Lohn/Std (€)</td>
                </tr>
                </thead>
                <tbody>
                <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['smarty_vars']->value['tariflohnbetragsliste'], 'row');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['row']->value) {
?>
                    <tr>
                        <td class="clickable" data-href="/tariflohnbetrag/bearbeiten/<?php echo $_smarty_tpl->tpl_vars['row']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['row']->value['gueltig_ab'];?>
</td>
                        <td class="clickable" data-href="/tariflohnbetrag/bearbeiten/<?php echo $_smarty_tpl->tpl_vars['row']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['row']->value['lohn'];?>
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
