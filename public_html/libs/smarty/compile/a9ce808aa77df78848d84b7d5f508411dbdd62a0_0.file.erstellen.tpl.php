<?php
/* Smarty version 3.1.30, created on 2019-07-18 14:15:57
  from "/usr/local/www/apache24/noexec/ttact-intern-software/views/main/Kunden/erstellen.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5d3062fdef35f9_15812246',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'a9ce808aa77df78848d84b7d5f508411dbdd62a0' => 
    array (
      0 => '/usr/local/www/apache24/noexec/ttact-intern-software/views/main/Kunden/erstellen.tpl',
      1 => 1562525855,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:views/main/Kunden/components/form.tpl' => 1,
  ),
),false)) {
function content_5d3062fdef35f9_15812246 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_assignInScope('container_class', "container" ,false ,2);
?>

<?php $_smarty_tpl->_assignInScope('title', "Kunden | erstellen" ,false ,2);
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

            <form action="/kunden/erstellen" method="post">
                <?php $_smarty_tpl->_subTemplateRender("file:views/main/Kunden/components/form.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

            </form>
        </div>
    </div>
</div>

<?php $_smarty_tpl->_assignInScope('js', $_smarty_tpl->tpl_vars['js']->value ,false ,2);
}
}
