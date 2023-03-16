<?php
/* Smarty version 3.1.30, created on 2019-08-12 10:25:40
  from "/usr/local/www/apache24/noexec/ttact-intern-software/views/main/Arbeitszeitkonto/bearbeiten.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5d51228447aaa9_89165400',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '23221cac9d5bfc82277253bdf728cca4c6f15b84' => 
    array (
      0 => '/usr/local/www/apache24/noexec/ttact-intern-software/views/main/Arbeitszeitkonto/bearbeiten.tpl',
      1 => 1562525862,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5d51228447aaa9_89165400 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_assignInScope('container_class', "container" ,false ,2);
?>

<?php $_smarty_tpl->_assignInScope('title', "Arbeitszeitkonto | bearbeiten" ,false ,2);
?>

<div class="row mb-4">
    <div class="col">
        <div class="content-box">
            <h3 class="content-box-title">für <?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['vorname'])===null||$tmp==='' ? "???" : $tmp);?>
 <?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['nachname'])===null||$tmp==='' ? "???" : $tmp);?>
 (<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['personalnummer'])===null||$tmp==='' ? "???" : $tmp);?>
) - <?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['monatsname'])===null||$tmp==='' ? "???" : $tmp);?>
 <?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['jahr'])===null||$tmp==='' ? "???" : $tmp);?>
</h3>

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

            <form action="/arbeitszeitkonto/bearbeiten/<?php echo $_smarty_tpl->tpl_vars['smarty_vars']->value['values']['id'];?>
" method="post">
                <div class="row">
                    <div class="form-group col-12">
                        <label class="form-control-label" for="stunden">Stunden</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-fw fa-calculator"></i></span>
                            <input type="text" class="form-control text-right" name="stunden" id="stunden" placeholder="0,00" value="<?php echo number_format((($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['stunden'])===null||$tmp==='' ? 0 : $tmp),2,',','');?>
" required>
                        </div>
                    </div>
                </div>
                <button type="submit" name="speichern" value="ja" class="btn btn-secondary form-control">Speichern</button>
            </form>
        </div>
    </div>
</div>
<?php }
}
