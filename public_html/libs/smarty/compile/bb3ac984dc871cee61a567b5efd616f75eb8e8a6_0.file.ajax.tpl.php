<?php
/* Smarty version 3.1.30, created on 2019-07-11 02:12:38
  from "/usr/local/www/apache24/noexec/ttact-intern-software/views/ajax.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5d267ef6ea6e03_24865613',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'bb3ac984dc871cee61a567b5efd616f75eb8e8a6' => 
    array (
      0 => '/usr/local/www/apache24/noexec/ttact-intern-software/views/ajax.tpl',
      1 => 1562525851,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5d267ef6ea6e03_24865613 (Smarty_Internal_Template $_smarty_tpl) {
echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['data'])===null||$tmp==='' ? '{"status":"error"}' : $tmp);
}
}
