<!DOCTYPE html>
<html lang="de">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>{{$smarty_vars.software_name|default:"XXX"}} | Login</title>

    {{if isset($smarty_vars.path_to_favicon)}}
        <!-- Favicon -->
        <link rel="shortcut icon" type="image/png" href="{{$smarty_vars.path_to_favicon}}">
    {{/if}}

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

    {{if isset($smarty_vars.path_to_company_css)}}
        <!-- Custom CSS -->
        <link rel="stylesheet" href="{{$smarty_vars.path_to_company_css}}">
    {{/if}}
</head>
<body>
    <div class="container-fluid height-100-percent">
        <div class="row align-items-center height-100-percent">
            <div class="col mb-sm-5 pb-sm-5 text-center">
                <div class="text-center mb-5">
                    {{if $smarty_vars.company == 'aps'}}
                        <h1 class="software_name"><strong style="color:blue;">[</strong> APS <strong style="color:blue;">]</strong></h1>
                    {{elseif $smarty_vars.company == 'tps'}}
                        <h1 class="software_name"><strong style="color:red;">[</strong> TPS <strong style="color:red;">]</strong></h1>
                    {{else}}
                        <h1 class="software_name"><strong>[</strong> {{$smarty_vars.software_name|default:"XXX"}} <strong>]</strong></h1>
                    {{/if}}
                </div>
                {{if isset($smarty_vars.error) }}
                    <div class="alert alert-danger width-300-px mx-auto font-size-09-rem text-left">
                        {{$smarty_vars.error}}
                    </div>
                {{/if}}
                <form method="post" action="/">
                    <div class="form-group width-300-px mx-auto">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-user fa-fw"></i></span>
                            <input type="text" class="form-control form-control-sm" name="username" placeholder="Benutzername" value="{{$smarty_vars.values.username|default:""}}">
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
                        <small>&copy; {{$smarty_vars.datum|default:"???"}} | tt act GmbH | <a href="http://ttact.de">www.ttact.de</a></small>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>