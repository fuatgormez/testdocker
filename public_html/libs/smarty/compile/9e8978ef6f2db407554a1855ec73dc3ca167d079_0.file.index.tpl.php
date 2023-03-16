<?php
/* Smarty version 3.1.30, created on 2019-07-12 16:52:42
  from "/usr/local/www/apache24/noexec/ttact-intern-software/views/main/Abteilungen/index.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5d289ebab26397_52556813',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '9e8978ef6f2db407554a1855ec73dc3ca167d079' => 
    array (
      0 => '/usr/local/www/apache24/noexec/ttact-intern-software/views/main/Abteilungen/index.tpl',
      1 => 1562525865,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:views/main/components/tables.tpl' => 1,
    'file:views/main/Abteilungen/components/form.tpl' => 1,
  ),
),false)) {
function content_5d289ebab26397_52556813 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_assignInScope('title', "Abteilungen" ,false ,2);
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


            <?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['abteilungsliste'])) {?>
                <?php echo (($tmp = @$_smarty_tpl->tpl_vars['table_tag']->value)===null||$tmp==='' ? "<table>" : $tmp);?>

                    <thead>
                        <tr>
                            <th>Abteilungs-ID</th>
                            <th>Bezeichnung</th>
                            <th>In Rechnung stellen</th>
                            <?php if ($_smarty_tpl->tpl_vars['smarty_vars']->value['company'] == 'tps') {?>
                                <th>Palettenabteilung</th>
                            <?php }?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['smarty_vars']->value['abteilungsliste'], 'row');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['row']->value) {
?>
                            <tr>
                                <th role="row"><?php echo $_smarty_tpl->tpl_vars['row']->value['id'];?>
</th>
                                <td class="clickable" data-href="/abteilungen/bearbeiten/<?php echo $_smarty_tpl->tpl_vars['row']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['row']->value['bezeichnung'];?>
</td>
                                <td class="clickable" data-href="/abteilungen/bearbeiten/<?php echo $_smarty_tpl->tpl_vars['row']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['row']->value['in_rechnung_stellen'];?>
</td>
                                <?php if ($_smarty_tpl->tpl_vars['smarty_vars']->value['company'] == 'tps') {?>
                                    <td class="clickable" data-href="/abteilungen/bearbeiten/<?php echo $_smarty_tpl->tpl_vars['row']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['row']->value['palettenabteilung'];?>
</td>
                                <?php }?>
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
            <h3 class="content-box-title">Neue Abteilung</h3>
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

            <form action="/abteilungen" method="post">
                <?php $_smarty_tpl->_subTemplateRender("file:views/main/Abteilungen/components/form.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
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
