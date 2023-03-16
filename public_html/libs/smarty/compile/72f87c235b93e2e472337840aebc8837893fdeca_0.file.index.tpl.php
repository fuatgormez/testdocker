<?php
/* Smarty version 3.1.30, created on 2019-08-05 14:11:15
  from "/usr/local/www/apache24/noexec/ttact-intern-software/views/main/Tarife/index.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5d481ce30cfe75_93907746',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '72f87c235b93e2e472337840aebc8837893fdeca' => 
    array (
      0 => '/usr/local/www/apache24/noexec/ttact-intern-software/views/main/Tarife/index.tpl',
      1 => 1562525872,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:views/main/components/tables.tpl' => 1,
    'file:views/main/Tarife/components/form.tpl' => 1,
  ),
),false)) {
function content_5d481ce30cfe75_93907746 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_assignInScope('title', "Tarife" ,false ,2);
?>

<?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['warning'])) {?>
    <div class="row mb-4">
        <div class="col">
            <div class="content-box bg-danger text-white">
                <?php echo $_smarty_tpl->tpl_vars['smarty_vars']->value['warning'];?>

            </div>
        </div>
    </div>
<?php }?>

<div class="row">
    <div class="col-12 col-lg-8 mb-4">
        <div class="content-box">
            <h3 class="content-box-title">Alle anzeigen</h3>

            <?php $_smarty_tpl->_subTemplateRender("file:views/main/components/tables.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>


            <?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['tarifliste'])) {?>
                <?php echo (($tmp = @$_smarty_tpl->tpl_vars['table_tag']->value)===null||$tmp==='' ? "<table>" : $tmp);?>

                    <thead>
                        <tr>
                            <th>Tarif-ID</th>
                            <th>Bezeichnung</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['smarty_vars']->value['tarifliste'], 'row');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['row']->value) {
?>
                            <tr>
                                <th role="row"><?php echo $_smarty_tpl->tpl_vars['row']->value['id'];?>
</th>
                                <td class="clickable" data-href="/tarife/bearbeiten/<?php echo $_smarty_tpl->tpl_vars['row']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['row']->value['bezeichnung'];?>
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
    <div class="col-12 col-lg-4 mb-4">
        <div class="content-box">
            <h3 class="content-box-title">Neuer Tarif</h3>
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

            <form action="/tarife" method="post">
                <?php $_smarty_tpl->_subTemplateRender("file:views/main/Tarife/components/form.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

            </form>
        </div>
    </div>
</div>

<?php $_smarty_tpl->_assignInScope('css', $_smarty_tpl->tpl_vars['css']->value ,false ,2);
?>

<?php $_smarty_tpl->_assignInScope('js', $_smarty_tpl->tpl_vars['js']->value ,false ,2);
}
}
