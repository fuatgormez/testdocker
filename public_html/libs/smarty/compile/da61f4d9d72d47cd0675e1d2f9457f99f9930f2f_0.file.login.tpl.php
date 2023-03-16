<?php
/* Smarty version 3.1.30, created on 2019-07-11 04:43:15
  from "/usr/local/www/apache24/noexec/ttact-intern-software/views/login.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5d26a243eb99c0_60382743',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'da61f4d9d72d47cd0675e1d2f9457f99f9930f2f' => 
    array (
      0 => '/usr/local/www/apache24/noexec/ttact-intern-software/views/login.tpl',
      1 => 1562525853,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5d26a243eb99c0_60382743 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title><?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['software_name'])===null||$tmp==='' ? "XXX" : $tmp);?>
 | Login</title>

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

    <?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['path_to_company_css'])) {?>
        <!-- Custom CSS -->
        <link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['smarty_vars']->value['path_to_company_css'];?>
">
    <?php }?>
</head>
<body>
    <div class="container-fluid height-100-percent">
        <div class="row align-items-center height-100-percent">
            <div class="col mb-sm-5 pb-sm-5 text-center">
                <div class="text-center mb-5">
                    <?php if ($_smarty_tpl->tpl_vars['smarty_vars']->value['company'] == 'aps') {?>
                        <h1 class="software_name"><strong style="color:blue;">[</strong> APS <strong style="color:blue;">]</strong></h1>
                    <?php } elseif ($_smarty_tpl->tpl_vars['smarty_vars']->value['company'] == 'tps') {?>
                        <h1 class="software_name"><strong style="color:red;">[</strong> TPS <strong style="color:red;">]</strong></h1>
                    <?php } else { ?>
                        <h1 class="software_name"><strong>[</strong> <?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['software_name'])===null||$tmp==='' ? "XXX" : $tmp);?>
 <strong>]</strong></h1>
                    <?php }?>
                </div>
                <?php if (isset($_smarty_tpl->tpl_vars['smarty_vars']->value['error'])) {?>
                    <div class="alert alert-danger width-300-px mx-auto font-size-09-rem text-left">
                        <?php echo $_smarty_tpl->tpl_vars['smarty_vars']->value['error'];?>

                    </div>
                <?php }?>
                <form method="post" action="/">
                    <div class="form-group width-300-px mx-auto">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-user fa-fw"></i></span>
                            <input type="text" class="form-control form-control-sm" name="username" placeholder="Benutzername" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['values']['username'])===null||$tmp==='' ? '' : $tmp);?>
">
                        </div>
                    </div>
                    <div class="form-group width-300-px mx-auto">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-lock fa-fw"></i></span>
                            <input type="password" class="form-control form-control-sm" name="password" placeholder="Passwort">
                        </div>
                    </div>
                    <div class="text-center mb-4 py-2">
                        <button type="submit" class="btn btn-secondary mx-auto btn-sm px-3 py-2">Anmelden</button>
                    </div>
                    <div class="pt-4 text-center border-top-1px-ccc width-300-px mx-auto">
                        <small>&copy; <?php echo (($tmp = @$_smarty_tpl->tpl_vars['smarty_vars']->value['datum'])===null||$tmp==='' ? "???" : $tmp);?>
 | tt act GmbH | <a href="http://ttact.de">www.ttact.de</a></small>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html><?php }
}
