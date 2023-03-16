<?php
/* Smarty version 3.1.30, created on 2019-07-23 21:32:31
  from "/usr/local/www/apache24/noexec/ttact-intern-software/views/main/Benutzer/passwort.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5d3760cfc06007_93379359',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'a95bad78900ef9cb3ba9848a71ac3f9332789faf' => 
    array (
      0 => '/usr/local/www/apache24/noexec/ttact-intern-software/views/main/Benutzer/passwort.tpl',
      1 => 1562525870,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5d3760cfc06007_93379359 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_assignInScope('container_class', "container" ,false ,2);
?>

<?php $_smarty_tpl->_assignInScope('title', "Benutzer | Passwort ändern" ,false ,2);
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

            <form action="/benutzer/bearbeiten" method="post">
                <div class="row">
                    <div class="form-group col-12 col-sm-6">
                        <label class="form-control-label" for="passwort_neu">Neues Passwort <span class="required">*</span></label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-key fa-fw"></i></span>
                            <input type="password" class="form-control" id="passwort_neu" name="passwort_neu" placeholder="Passwort" autocomplete="off" required>
                        </div>
                    </div>
                    <div class="form-group col-12 col-sm-6">
                        <label class="form-control-label" for="passwort_neu_bestaetigen">bestätigen <span class="required">*</span></label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-key fa-fw"></i></span>
                            <input type="password" class="form-control" id="passwort_neu_bestaetigen" name="passwort_neu_bestaetigen" placeholder="bestätigen" autocomplete="off" required>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-secondary form-control">Speichern</button>
            </form>
        </div>
    </div>
</div><?php }
}
