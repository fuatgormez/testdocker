<?php
/* Smarty version 3.1.30, created on 2019-10-10 13:12:53
  from "/usr/local/www/apache24/noexec/ttact-intern-software/views/main/Tariflohnbetrag/erstellen.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5d9f1235ea9962_95634411',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '6315ff530d79072ca8c243bcfadfd1d2e881e0ef' => 
    array (
      0 => '/usr/local/www/apache24/noexec/ttact-intern-software/views/main/Tariflohnbetrag/erstellen.tpl',
      1 => 1562525871,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:views/main/Tariflohnbetrag/components/form.tpl' => 1,
  ),
),false)) {
function content_5d9f1235ea9962_95634411 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_assignInScope('container_class', "container" ,false ,2);
?>

<?php $_smarty_tpl->_assignInScope('title', "Tariflohnbetrag | erstellen" ,false ,2);
?>

<div class="row mb-4">
    <div class="col">
        <div class="content-box">
            <h3 class="content-box-title">für <strong><?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['tarifbezeichnung'])===null||$tmp==='' ? "???" : $tmp);?>
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

            <form action="/tariflohnbetrag/erstellen/<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['tarifid'])===null||$tmp==='' ? "???" : $tmp);?>
" method="post">
                <?php $_smarty_tpl->_subTemplateRender("file:views/main/Tariflohnbetrag/components/form.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

            </form>
        </div>
    </div>
</div><?php }
}
