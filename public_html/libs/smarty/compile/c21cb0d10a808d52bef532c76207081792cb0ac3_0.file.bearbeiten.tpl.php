<?php
/* Smarty version 3.1.30, created on 2019-07-18 12:17:24
  from "/usr/local/www/apache24/noexec/ttact-intern-software/views/main/Kundenkondition/bearbeiten.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5d3047341ced93_90957730',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'c21cb0d10a808d52bef532c76207081792cb0ac3' => 
    array (
      0 => '/usr/local/www/apache24/noexec/ttact-intern-software/views/main/Kundenkondition/bearbeiten.tpl',
      1 => 1562525851,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:views/main/Kundenkondition/components/form.tpl' => 1,
  ),
),false)) {
function content_5d3047341ced93_90957730 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_assignInScope('container_class', "container" ,false ,2);
?>

<?php $_smarty_tpl->_assignInScope('title', "Kundenkondition | bearbeiten" ,false ,2);
?>

<div class="row mb-4">
    <div class="col">
        <div class="content-box">
            <h3 class="content-box-title">für Kunde <strong><?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['kundennummer'])===null||$tmp==='' ? "???" : $tmp);?>
</strong> | <strong><?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['bezeichnung'])===null||$tmp==='' ? "???" : $tmp);?>
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

            <form action="/kundenkondition/bearbeiten/<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['id'])===null||$tmp==='' ? "???" : $tmp);?>
" method="post">
                <?php $_smarty_tpl->_subTemplateRender("file:views/main/Kundenkondition/components/form.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

            </form>
        </div>
    </div>
</div>

<?php $_smarty_tpl->smarty->ext->_capture->open($_smarty_tpl, 'styles', null, null);
?>

<?php $_smarty_tpl->smarty->ext->_capture->close($_smarty_tpl);
?>


<?php $_smarty_tpl->_assignInScope('css', $_smarty_tpl->smarty->ext->_capture->getBuffer($_smarty_tpl, 'styles') ,false ,2);
?>

<?php $_smarty_tpl->smarty->ext->_capture->open($_smarty_tpl, 'scripts', null, null);
?>

    <?php echo (($tmp = @$_smarty_tpl->tpl_vars['js']->value)===null||$tmp==='' ? '' : $tmp);?>

<?php $_smarty_tpl->smarty->ext->_capture->close($_smarty_tpl);
?>


<?php $_smarty_tpl->_assignInScope('js', $_smarty_tpl->smarty->ext->_capture->getBuffer($_smarty_tpl, 'scripts') ,false ,2);
}
}
