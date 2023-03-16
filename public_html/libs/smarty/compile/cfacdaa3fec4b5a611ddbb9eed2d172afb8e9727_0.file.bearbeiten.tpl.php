<?php
/* Smarty version 3.1.30, created on 2019-07-11 09:42:07
  from "/usr/local/www/apache24/noexec/ttact-intern-software/views/main/Kunden/bearbeiten.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5d26e84fe968c0_32883575',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'cfacdaa3fec4b5a611ddbb9eed2d172afb8e9727' => 
    array (
      0 => '/usr/local/www/apache24/noexec/ttact-intern-software/views/main/Kunden/bearbeiten.tpl',
      1 => 1562525857,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:views/main/Kunden/components/form.tpl' => 1,
    'file:views/main/components/tables.tpl' => 1,
  ),
),false)) {
function content_5d26e84fe968c0_32883575 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_assignInScope('container_class', "container" ,false ,2);
?>

<?php $_smarty_tpl->_assignInScope('title', "Kunden | bearbeiten" ,false ,2);
?>

<div class="row mb-4">
    <div class="col">
        <div class="content-box">
            <h3 class="content-box-title">Kunde Nr. <?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['kundennummer'])===null||$tmp==='' ? "???" : $tmp);?>
: <strong><?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['name'])===null||$tmp==='' ? '' : $tmp);?>
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

            <form action="/kunden/bearbeiten/<?php echo $_smarty_tpl->tpl_vars['smarty_vars']->value['values']['kundennummer'];?>
" method="post">
                <?php $_smarty_tpl->_subTemplateRender("file:views/main/Kunden/components/form.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

            </form>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col">
        <div class="content-box">
            <?php if ($_smarty_tpl->tpl_vars['smarty_vars']->value['current_user']['usergroup']->hasRight('konditionen')) {?>
                <h4>Konditionen</h4>
                <a class="btn btn-secondary btn-block my-3" href="/kundenkondition/erstellen/<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['kundennummer'])===null||$tmp==='' ? "???" : $tmp);?>
"><i class="fa fa-plus-circle mr-1"></i> Neue Kundenkondition hinzufügen</a>
                <?php $_smarty_tpl->_subTemplateRender("file:views/main/components/tables.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

                <?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['kundenkonditionsliste'])) {?>
                    <?php echo (($tmp = @$_smarty_tpl->tpl_vars['table_tag']->value)===null||$tmp==='' ? "<table>" : $tmp);?>

                    <thead>
                        <tr>
                            <td>Gültig ab</td>
                            <td>Gültig bis</td>
                            <td>Abteilung</td>
                            <td class="text-right">Preis</td>
                            <td class="text-right">Sonntagsz.</td>
                            <td class="text-right">Feiertagsz.</td>
                            <td class="text-right">Nachtz.</td>
                            <td class="text-right">Nacht von</td>
                            <td class="text-right">Nacht bis</td>
                            <?php if ($_smarty_tpl->tpl_vars['smarty_vars']->value['company'] == 'tps') {?>
                                <td class="text-right">Zeit/Palette</td>
                            <?php }?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['smarty_vars']->value['kundenkonditionsliste'], 'row');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['row']->value) {
?>
                            <tr>
                                <td data-order="<?php echo $_smarty_tpl->tpl_vars['row']->value['gueltig_ab_ordering'];?>
"><?php echo $_smarty_tpl->tpl_vars['row']->value['gueltig_ab'];?>
</td>
                                <td data-order="<?php echo $_smarty_tpl->tpl_vars['row']->value['gueltig_bis_ordering'];?>
" class="clickable" data-href="/kundenkondition/bearbeiten/<?php echo $_smarty_tpl->tpl_vars['row']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['row']->value['gueltig_bis'];?>
</td>
                                <td class="clickable" data-href="/kundenkondition/bearbeiten/<?php echo $_smarty_tpl->tpl_vars['row']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['row']->value['abteilung'];?>
</td>
                                <td class="clickable text-right" data-href="/kundenkondition/bearbeiten/<?php echo $_smarty_tpl->tpl_vars['row']->value['id'];?>
"><?php echo number_format((($tmp = @$_smarty_tpl->tpl_vars['row']->value['preis'])===null||$tmp==='' ? '' : $tmp),2,",",".");?>
</td>
                                <td class="clickable text-right" data-href="/kundenkondition/bearbeiten/<?php echo $_smarty_tpl->tpl_vars['row']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['row']->value['sonntagszuschlag'];?>
</td>
                                <td class="clickable text-right" data-href="/kundenkondition/bearbeiten/<?php echo $_smarty_tpl->tpl_vars['row']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['row']->value['feiertagszuschlag'];?>
</td>
                                <td class="clickable text-right" data-href="/kundenkondition/bearbeiten/<?php echo $_smarty_tpl->tpl_vars['row']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['row']->value['nachtzuschlag'];?>
</td>
                                <td class="clickable text-right" data-href="/kundenkondition/bearbeiten/<?php echo $_smarty_tpl->tpl_vars['row']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['row']->value['nacht_von'];?>
</td>
                                <td class="clickable text-right" data-href="/kundenkondition/bearbeiten/<?php echo $_smarty_tpl->tpl_vars['row']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['row']->value['nacht_bis'];?>
</td>
                                <?php if ($_smarty_tpl->tpl_vars['smarty_vars']->value['company'] == 'tps') {?>
                                    <td class="clickable text-right" data-href="/kundenkondition/bearbeiten/<?php echo $_smarty_tpl->tpl_vars['row']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['row']->value['zeit_pro_palette'];?>
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
            <?php }?>
        </div>
    </div>
</div>

<?php $_smarty_tpl->smarty->ext->_capture->open($_smarty_tpl, 'styles', null, null);
?>

    <!-- General -->
    <style>
        a.btn:hover, a.btn:link, a.btn:visited, a.btn:active {
            color: black;
        }
    </style>
    <!-- /General-->

    <?php echo (($tmp = @$_smarty_tpl->tpl_vars['css']->value)===null||$tmp==='' ? '' : $tmp);?>

<?php $_smarty_tpl->smarty->ext->_capture->close($_smarty_tpl);
?>


<?php $_smarty_tpl->_assignInScope('css', $_smarty_tpl->smarty->ext->_capture->getBuffer($_smarty_tpl, 'styles') ,false ,2);
?>

<?php $_smarty_tpl->smarty->ext->_capture->open($_smarty_tpl, 'scripts', null, null);
?>

    <!-- Autosize -->
    <?php echo '<script'; ?>
 src="/assets/vendors/autosize/dist/autosize.min.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
>
        $(document).ready(function() {
            autosize($(".resizable"));
        });
    <?php echo '</script'; ?>
>
    <!-- /Autosize -->

    <?php echo (($tmp = @$_smarty_tpl->tpl_vars['js']->value)===null||$tmp==='' ? '' : $tmp);?>

<?php $_smarty_tpl->smarty->ext->_capture->close($_smarty_tpl);
?>


<?php $_smarty_tpl->_assignInScope('js', $_smarty_tpl->smarty->ext->_capture->getBuffer($_smarty_tpl, 'scripts') ,false ,2);
}
}
