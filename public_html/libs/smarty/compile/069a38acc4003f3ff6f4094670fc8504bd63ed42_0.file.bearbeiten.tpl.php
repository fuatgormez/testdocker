<?php
/* Smarty version 3.1.30, created on 2019-07-11 11:39:14
  from "/usr/local/www/apache24/noexec/ttact-intern-software/views/main/Lohnkonfiguration/bearbeiten.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5d2703c2a82286_26041671',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '069a38acc4003f3ff6f4094670fc8504bd63ed42' => 
    array (
      0 => '/usr/local/www/apache24/noexec/ttact-intern-software/views/main/Lohnkonfiguration/bearbeiten.tpl',
      1 => 1562525864,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:views/main/Lohnkonfiguration/components/form.tpl' => 1,
  ),
),false)) {
function content_5d2703c2a82286_26041671 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_assignInScope('container_class', "container" ,false ,2);
?>

<?php $_smarty_tpl->_assignInScope('title', "Lohnkonfiguration | bearbeiten" ,false ,2);
?>

<div class="row mb-4">
    <div class="col">
        <div class="content-box">
            <h3 class="content-box-title">für <strong><?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['vorname'])===null||$tmp==='' ? "???" : $tmp);?>
 <?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['nachname'])===null||$tmp==='' ? "???" : $tmp);?>
 (<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['personalnummer'])===null||$tmp==='' ? "???" : $tmp);?>
)</strong></h3>

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

            <?php if ($_smarty_tpl->tpl_vars['smarty_vars']->value['company'] != 'tps') {?>
                <div class="alert alert-info mb-4">Bitte beachten Sie, dass der Gesamtstundenlohn nur in zwei Fällen eingetragen werden muss: <strong>1.</strong> Wenn der/die Mitarbeiter/in keinem Tarif zugeordnet wird. <strong>2.</strong> Wenn der/die Mitarbeiter/in einen übertariflichen Zuschlag erhalten soll. In diesem Fall ist der übertarifliche Zuschlag die Differenz zwischen dem hier eingegebenen Gesamtlohn und dem Tariflohn.</div>
            <?php }?>

            <form action="/lohnkonfiguration/bearbeiten/<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['id'])===null||$tmp==='' ? "???" : $tmp);?>
" method="post">
                <?php $_smarty_tpl->_subTemplateRender("file:views/main/Lohnkonfiguration/components/form.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

            </form>
        </div>
    </div>
</div><?php }
}
