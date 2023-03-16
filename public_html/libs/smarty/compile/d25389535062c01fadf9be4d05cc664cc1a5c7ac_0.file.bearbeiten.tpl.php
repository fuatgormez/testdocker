<?php
/* Smarty version 3.1.30, created on 2019-07-18 15:23:58
  from "/usr/local/www/apache24/noexec/ttact-intern-software/views/main/Abteilungen/bearbeiten.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5d3072ee6cc2e1_71947803',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'd25389535062c01fadf9be4d05cc664cc1a5c7ac' => 
    array (
      0 => '/usr/local/www/apache24/noexec/ttact-intern-software/views/main/Abteilungen/bearbeiten.tpl',
      1 => 1562525864,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:views/main/Abteilungen/components/form.tpl' => 1,
  ),
),false)) {
function content_5d3072ee6cc2e1_71947803 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_assignInScope('container_class', "container" ,false ,2);
?>

<?php $_smarty_tpl->_assignInScope('title', "Abteilungen | bearbeiten" ,false ,2);
?>

<div class="row mb-4">
    <div class="col">
        <div class="content-box">
            <h3 class="content-box-title">Abteilung Nr. <?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['id'])===null||$tmp==='' ? "???" : $tmp);?>
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

            <form action="/abteilungen/bearbeiten/<?php echo $_smarty_tpl->tpl_vars['smarty_vars']->value['values']['id'];?>
" method="post">
                <?php $_smarty_tpl->_subTemplateRender("file:views/main/Abteilungen/components/form.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

            </form>
        </div>
    </div>
</div>
<?php }
}
