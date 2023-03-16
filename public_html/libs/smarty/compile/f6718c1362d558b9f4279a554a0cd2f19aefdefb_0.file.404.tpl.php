<?php
/* Smarty version 3.1.30, created on 2019-07-11 04:43:31
  from "/usr/local/www/apache24/noexec/ttact-intern-software/views/404.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5d26a2532ecfd5_22238086',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'f6718c1362d558b9f4279a554a0cd2f19aefdefb' => 
    array (
      0 => '/usr/local/www/apache24/noexec/ttact-intern-software/views/404.tpl',
      1 => 1562525851,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5d26a2532ecfd5_22238086 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title><?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['software_name'])===null||$tmp==='' ? "XXX" : $tmp);?>
 | Seite nicht gefunden</title>

    <?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['path_to_favicon'])) {?>
        <!-- Favicon -->
        <link rel="shortcut icon" type="image/png" href="<?php echo $_smarty_tpl->tpl_vars['smarty_vars']->value['path_to_favicon'];?>
">
    <?php }?>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/assets/vendors/bootstrap-4.0.0-alpha.6-dist/css/bootstrap.min.css">

    <!-- FontAwesome CSS -->
    <link rel="stylesheet" href="/assets/vendors/font-awesome-4.7.0/css/font-awesome.min.css">

    <style>
        html, body, .height-100-percent {
            height: 100%;
        }

        body {
            background: #eee;
        }

        .width-300-px {
            max-width: 300px;
        }

        .border-top-1px-ccc {
            border-top: 1px solid #ccc;
        }

        .font-size-09-rem {
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="container-fluid height-100-percent">
        <div class="row align-items-center height-100-percent">
            <div class="col mb-sm-5 pb-sm-5 text-center">
                <div class="mb-5">
                    <h1>404</h1>
                    <h2>Seite nicht gefunden</h2>
                </div>
                <p>Leider existiert die angeforderte Seite nicht. Haben Sie sich vielleicht vertippt?</p>
            </div>
        </div>
    </div>
</body>
</html><?php }
}
