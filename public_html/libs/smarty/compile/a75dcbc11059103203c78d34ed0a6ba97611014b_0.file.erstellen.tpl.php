<?php
/* Smarty version 3.1.30, created on 2019-07-11 12:03:13
  from "/usr/local/www/apache24/noexec/ttact-intern-software/views/main/Mitarbeiter/erstellen.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5d27096132ca75_93201008',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'a75dcbc11059103203c78d34ed0a6ba97611014b' => 
    array (
      0 => '/usr/local/www/apache24/noexec/ttact-intern-software/views/main/Mitarbeiter/erstellen.tpl',
      1 => 1562525866,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:views/main/Mitarbeiter/components/form_a.tpl' => 1,
    'file:views/main/Mitarbeiter/components/form_c.tpl' => 1,
  ),
),false)) {
function content_5d27096132ca75_93201008 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_assignInScope('container_class', "container" ,false ,2);
?>

<?php $_smarty_tpl->_assignInScope('title', "Mitarbeiter | erstellen" ,false ,2);
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

            <form action="/mitarbeiter/erstellen" method="post">
                <?php $_smarty_tpl->_subTemplateRender("file:views/main/Mitarbeiter/components/form_a.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

                <?php $_smarty_tpl->_subTemplateRender("file:views/main/Mitarbeiter/components/form_c.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

            </form>
        </div>
    </div>
</div><?php }
}
