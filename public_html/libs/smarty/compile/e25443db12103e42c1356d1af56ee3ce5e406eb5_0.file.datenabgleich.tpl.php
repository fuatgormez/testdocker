<?php
/* Smarty version 3.1.30, created on 2019-07-12 16:43:00
  from "/usr/local/www/apache24/noexec/ttact-intern-software/views/main/Mitarbeiter/datenabgleich.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5d289c7477de44_96293572',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'e25443db12103e42c1356d1af56ee3ce5e406eb5' => 
    array (
      0 => '/usr/local/www/apache24/noexec/ttact-intern-software/views/main/Mitarbeiter/datenabgleich.tpl',
      1 => 1562525866,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5d289c7477de44_96293572 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_assignInScope('container_class', "container" ,false ,2);
?>

<?php $_smarty_tpl->_assignInScope('title', "Mitarbeiter | Datenabgleich" ,false ,2);
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

            <div class="alert alert-info">Bitte exportieren Sie die .csv-Datei aus Agenda Lohn und laden Sie sie hier hoch.</div>

            <form action="/mitarbeiter/datenabgleich" method="post" enctype="multipart/form-data">
                <input class="form-control mb-3" type="file" name="datei">
                <button type="submit" class="btn btn-secondary btn-block">Hochladen</button>
            </form>
        </div>
    </div>
</div><?php }
}
