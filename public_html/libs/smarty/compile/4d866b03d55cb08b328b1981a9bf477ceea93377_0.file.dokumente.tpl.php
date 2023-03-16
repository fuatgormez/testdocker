<?php
/* Smarty version 3.1.30, created on 2019-07-11 14:24:29
  from "/usr/local/www/apache24/noexec/ttact-intern-software/views/main/Kunden/dokumente.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5d272a7da6cb91_87516535',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '4d866b03d55cb08b328b1981a9bf477ceea93377' => 
    array (
      0 => '/usr/local/www/apache24/noexec/ttact-intern-software/views/main/Kunden/dokumente.tpl',
      1 => 1562525858,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:views/main/components/tables.tpl' => 1,
  ),
),false)) {
function content_5d272a7da6cb91_87516535 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_assignInScope('container_class', "container" ,false ,2);
?>

<?php $_smarty_tpl->_assignInScope('title', "Kunden | Dokumente" ,false ,2);
?>

<?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['error'])) {?>
    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['smarty_vars']->value['error'], 'row');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['row']->value) {
?>
        <div class="row mb-4">
            <div class="col">
                <div class="content-box bg-danger text-white">
                    <?php echo $_smarty_tpl->tpl_vars['row']->value;?>

                </div>
            </div>
        </div>
    <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

<?php }?>

<?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['success'])) {?>
    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['smarty_vars']->value['success'], 'row');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['row']->value) {
?>
        <div class="row mb-4">
            <div class="col">
                <div class="content-box bg-success text-white">
                    <?php echo $_smarty_tpl->tpl_vars['row']->value;?>

                </div>
            </div>
        </div>
    <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

<?php }?>

<div class="row mb-4">
    <?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['kundenliste'])) {?>
        <div class="col-12">
            <div class="content-box">
                <h4 class="mb-4">Kunden auswählen</h4>
                <div class="input-group">
                    <?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['prev_kunde'])) {?>
                        <div class="input-group-btn">
                            <a id="kunde_prev" type="button" class="btn btn-secondary" href="/kunden/dokumente/<?php echo $_smarty_tpl->tpl_vars['smarty_vars']->value['prev_kunde'];?>
">
                                <i class="fa fa-angle-left"></i>
                            </a>
                        </div>
                    <?php } else { ?>
                        <span class="input-group-addon">
                            <i class="fa fa-user fa-fw"></i>
                        </span>
                    <?php }?>

                    <select class="form-control selectable" id="kundenswitch" tabindex="-1" required>
                        <option value="">-- bitte auswählen</option>
                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['smarty_vars']->value['kundenliste'], 'row');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['row']->value) {
?>
                            <option value="<?php echo $_smarty_tpl->tpl_vars['row']->value['kundennummer'];?>
" <?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['kundennummer'])) {
if ($_smarty_tpl->tpl_vars['smarty_vars']->value['kundennummer'] == $_smarty_tpl->tpl_vars['row']->value['kundennummer']) {?> selected<?php }
}?>><?php echo $_smarty_tpl->tpl_vars['row']->value['kundennummer'];?>
 - <?php echo $_smarty_tpl->tpl_vars['row']->value['name'];?>
</option>
                        <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

                    </select>

                    <?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['next_kunde'])) {?>
                        <div class="input-group-btn">
                            <a id="kunde_next" type="button" class="btn btn-secondary" href="/kunden/dokumente/<?php echo $_smarty_tpl->tpl_vars['smarty_vars']->value['next_kunde'];?>
">
                                <i class="fa fa-angle-right"></i>
                            </a>
                        </div>
                    <?php }?>
                </div>
            </div>
        </div>
    <?php }?>
</div>

<?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['dokumentenliste'])) {?>
    <div class="row mb-4">
        <div class="col-12">
            <div class="content-box">
                <h4 class="mb-4">Dokumente anzeigen</h4>
                <?php $_smarty_tpl->_subTemplateRender("file:views/main/components/tables.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>


                <?php echo (($tmp = @$_smarty_tpl->tpl_vars['table_tag']->value)===null||$tmp==='' ? "<table>" : $tmp);?>

                    <thead>
                        <tr>
                            <th>Name</th>
                            <th class="text-right">Größe</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['smarty_vars']->value['dokumentenliste'], 'row');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['row']->value) {
?>
                            <tr>
                                <td class="clickable" data-href="/dokument/<?php if ($_smarty_tpl->tpl_vars['smarty_vars']->value['current_user']['usergroup']->hasRight('dokumente_alle_kunden')) {?>bearbeiten<?php } else { ?>anzeigen<?php }?>/<?php echo $_smarty_tpl->tpl_vars['row']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['row']->value['name'];?>
</td>
                                <td class="clickable text-right" data-href="/dokument/<?php if ($_smarty_tpl->tpl_vars['smarty_vars']->value['current_user']['usergroup']->hasRight('dokumente_alle_kunden')) {?>bearbeiten<?php } else { ?>anzeigen<?php }?>/<?php echo $_smarty_tpl->tpl_vars['row']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['row']->value['size'];?>
</td>
                            </tr>
                        <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php }?>

<?php if ($_smarty_tpl->tpl_vars['smarty_vars']->value['current_user']['usergroup']->hasRight('dokumente_alle_kunden')) {?>
    <?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['kundennummer'])) {?>
        <div class="row mb-4">
            <div class="col-12">
                <div class="content-box">
                    <h4 class="mb-4">Dokumente hochladen</h4>
                    <form action="/kunden/dokumente/<?php echo $_smarty_tpl->tpl_vars['smarty_vars']->value['kundennummer'];?>
" method="post" enctype="multipart/form-data">
                        <input class="form-control mb-3" type="file" name="datei[]" multiple>
                        <button type="submit" class="btn btn-secondary btn-block">Hochladen</button>
                    </form>
                </div>
            </div>
        </div>
    <?php }
}?>

<?php $_smarty_tpl->smarty->ext->_capture->open($_smarty_tpl, 'styles', null, null);
?>

    <?php echo (($tmp = @$_smarty_tpl->tpl_vars['css']->value)===null||$tmp==='' ? '' : $tmp);?>


    <!-- Select2 -->
    <link href="/assets/vendors/select2/dist/css/select2.min.css" rel="stylesheet">
    <style>
        .select2-container .select2-selection--multiple {
            min-height: 100px;
        }

        @media (max-width: 575px) {
            .select2-selection__rendered {
                padding-right: 0 !important;
            }
        }

        .select2-selection--multiple {
            border: 1px solid rgba(0,0,0,.15) !important;
            border-radius: 0 .25rem .25rem 0 !important;
        }

        .select2-selection--single .select2-selection__arrow {
            height: calc(2rem + 6px) !important;
        }
    </style>
    <!-- /Select2 -->
<?php $_smarty_tpl->smarty->ext->_capture->close($_smarty_tpl);
?>


<?php $_smarty_tpl->_assignInScope('css', $_smarty_tpl->smarty->ext->_capture->getBuffer($_smarty_tpl, 'styles') ,false ,2);
?>

<?php $_smarty_tpl->smarty->ext->_capture->open($_smarty_tpl, 'scripts', null, null);
?>

    <?php echo (($tmp = @$_smarty_tpl->tpl_vars['js']->value)===null||$tmp==='' ? '' : $tmp);?>


    <!-- Select2 -->
    <?php echo '<script'; ?>
 src="/assets/vendors/select2/dist/js/select2.full.min.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
>
        $(document).ready(function() {
            $(".selectable").select2({
                allowClear: false,
                language: {
                    "noResults": function() {
                        return "Keine Ergebnisse gefunden.";
                    }
                },
                width: "100%"
            });
        });
    <?php echo '</script'; ?>
>
    <!-- /Select2 -->

    <!-- Kundenswitch -->
    <?php echo '<script'; ?>
>
        $kundenswitch = $('#kundenswitch');
        $kundenswitch.change(function () {
            if ($kundenswitch.val() != '') {
                window.location = '/kunden/dokumente/' + $kundenswitch.val();
            }
        });
    <?php echo '</script'; ?>
>
    <!-- /Kundenswitch -->
<?php $_smarty_tpl->smarty->ext->_capture->close($_smarty_tpl);
?>


<?php $_smarty_tpl->_assignInScope('js', $_smarty_tpl->smarty->ext->_capture->getBuffer($_smarty_tpl, 'scripts') ,false ,2);
}
}
