<?php
/* Smarty version 3.1.30, created on 2019-07-12 12:47:42
  from "/usr/local/www/apache24/noexec/ttact-intern-software/views/main/Dokument/bearbeiten.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5d28654e671515_80226351',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'c93719d773240fd059ea1f49a88f0a57ec27ab9a' => 
    array (
      0 => '/usr/local/www/apache24/noexec/ttact-intern-software/views/main/Dokument/bearbeiten.tpl',
      1 => 1562525866,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5d28654e671515_80226351 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_assignInScope('container_class', "container" ,false ,2);
?>

<?php $_smarty_tpl->_assignInScope('title', "Dokument | bearbeiten" ,false ,2);
?>

<form action="/dokument/bearbeiten/<?php echo $_smarty_tpl->tpl_vars['smarty_vars']->value['values']['id'];?>
" method="post">
    <div class="row mb-4">
        <div class="col">
            <div class="content-box">
                <h3 class="content-box-title"><strong><?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['name'])===null||$tmp==='' ? '' : $tmp);?>
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

                <div class="row">
                    <div class="form-group col-12 col-sm-6">
                        <label class="form-control-label" for="kunde">Kunde</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-user fa-fw"></i></span>
                            <select class="form-control selectable" name="kundennummer" id="kundennummer" tabindex="-1" required>
                                <option value="">-- bitte auswählen</option>
                                <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['smarty_vars']->value['kundenliste'], 'row');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['row']->value) {
?>
                                    <option value="<?php echo $_smarty_tpl->tpl_vars['row']->value['kundennummer'];?>
" <?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['kundennummer'])) {
if ($_smarty_tpl->tpl_vars['smarty_vars']->value['values']['kundennummer'] == $_smarty_tpl->tpl_vars['row']->value['kundennummer']) {?> selected<?php }
}?>><?php echo $_smarty_tpl->tpl_vars['row']->value['kundennummer'];?>
 - <?php echo $_smarty_tpl->tpl_vars['row']->value['name'];?>
</option>
                                <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

                            </select>
                        </div>
                    </div>

                    <div class="form-group col-12 col-sm-6">
                        <label class="form-control-label" for="name">Name</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-file fa-fw"></i></span>
                            <input type="text" class="form-control" id="name" name="name" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['name'])===null||$tmp==='' ? '' : $tmp);?>
" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col">
            <div class="content-box">
                <div class="row">
                    <div class="col-12 col-sm">
                        <a class="btn btn-secondary btn-block" href="/dokument/anzeigen/<?php echo $_smarty_tpl->tpl_vars['smarty_vars']->value['values']['id'];?>
">
                            Dokument anzeigen
                        </a>
                    </div>
                    <div class="col-12 col-sm">
                        <a class="btn btn-secondary btn-block" href="/dokument/loeschen/<?php echo $_smarty_tpl->tpl_vars['smarty_vars']->value['values']['id'];?>
">
                            Dokument löschen
                        </a>
                    </div>
                    <div class="col-12 col-sm">
                        <button type="submit" class="btn btn-secondary btn-block" name="submitted" value="true">Änderungen speichern</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<?php $_smarty_tpl->smarty->ext->_capture->open($_smarty_tpl, 'styles', null, null);
?>

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

    <!-- General -->
    <style>
        a.btn:hover, a.btn:link, a.btn:visited, a.btn:active {
            color: black;
            text-decoration: none;
            font-family: sans-serif;
            font-size: 100%;
            line-height: 1.15;
            margin: 0;
            cursor: default;
        }
    </style>
    <!-- /General -->

    <?php echo (($tmp = @$_smarty_tpl->tpl_vars['css']->value)===null||$tmp==='' ? '' : $tmp);?>

<?php $_smarty_tpl->smarty->ext->_capture->close($_smarty_tpl);
?>


<?php $_smarty_tpl->_assignInScope('css', $_smarty_tpl->smarty->ext->_capture->getBuffer($_smarty_tpl, 'styles') ,false ,2);
?>

<?php $_smarty_tpl->smarty->ext->_capture->open($_smarty_tpl, 'scripts', null, null);
?>

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

    <?php echo (($tmp = @$_smarty_tpl->tpl_vars['js']->value)===null||$tmp==='' ? '' : $tmp);?>

<?php $_smarty_tpl->smarty->ext->_capture->close($_smarty_tpl);
?>


<?php $_smarty_tpl->_assignInScope('js', $_smarty_tpl->smarty->ext->_capture->getBuffer($_smarty_tpl, 'scripts') ,false ,2);
}
}
