<!DOCTYPE html>
<html lang="de">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>{{$smarty_vars.software_name|default:"XXX"}} | Seite nicht gefunden</title>

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
</html>