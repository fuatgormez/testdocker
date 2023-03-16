{{$container_class="container" scope=parent}}

{{$title="Schichten" scope=parent}}

<div class="row mb-4" id="error_container" style="display:none;">
    <div class="col">
        <div class="content-box bg-danger text-white" id="error_content"></div>
    </div>
</div>

{{if isset($smarty_vars.error)}}
    <div class="row mb-4">
        <div class="col">
            <div class="content-box bg-danger text-white">
                {{$smarty_vars.error}}
            </div>
        </div>
    </div>
{{/if}}

<form action="/schichten" method="post">
    <div class="row mb-4">
        <div class="col">
            <div class="content-box overflow-x-unset">
                <h3 class="content-box-title">Kalenderwoche auswählen</h3>
                <div class="row">
                    <div class="col-12 mb-3 col-md-auto mb-md-0">
                        <div class="btn-group d-flex w-100" role="group">
                            <button type="button" class="btn btn-secondary" id="vorige_kw"><i class="fa fa-angle-left"></i></button>
                            <button type="button" class="btn btn-secondary w-100" id="aktuelle_kw">Aktuelle K<span class="hidden-sm-up">W</span><span class="hidden-xs-down hidden-md-up">alenderwoche</span><span class="hidden-sm-down">W</span></button>
                            <button type="button" class="btn btn-secondary" id="naechste_kw"><i class="fa fa-angle-right"></i></button>
                        </div>
                    </div>
                    <div class="col-12 col-md">
                        <div class="input-group">
                            <div class="input-group-btn">
                                <button type="button" class="btn btn-secondary dropdown-toggle p-xs-down-1" data-toggle="dropdown">
                                    <span class="hidden-xs-down" id="jahresauswahl_button">{{$smarty_vars.values.jahr|default:"???"}}</span>
                                </button>
                                <div class="dropdown-menu" id="jahresauswahl">
                                    {{if isset($smarty_vars.jahresliste)}}
                                        {{foreach from=$smarty_vars.jahresliste item='row'}}
                                            <a class="dropdown-item" href="javascript:;" data-jahr="{{$row}}">{{$row}}</a>
                                        {{/foreach}}
                                    {{/if}}
                                </div>
                            </div>

                            <select class="selectable form-control" id="kalenderwoche" tabindex="-1" name="kalenderwoche" data-placeholder="-- bitte auswählen" required>
                                <option value="">-- bitte auswählen</option>
                                {{if isset($smarty_vars.kalenderwochen)}}
                                    {{foreach from=$smarty_vars.kalenderwochen item='row'}}
                                        <option value="{{$row.kw}}" {{if isset($smarty_vars.values.kalenderwoche)}} {{if $smarty_vars.values.kalenderwoche == $row.kw}} selected{{/if}}{{/if}}>KW {{$row.kw}} | {{$row.von}} - {{$row.bis}}</option>
                                    {{/foreach}}
                                {{/if}}
                            </select>

                            <input type="hidden" name="jahr" id="jahr" value="{{$smarty_vars.values.jahr|default:""}}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{if $smarty_vars.kunden_auswaehlen_anzeigen}}
        <div class="row mb-4">
            <div class="col">
                <div class="content-box">
                    <h3 class="content-box-title">Kunden auswählen</h3>
                    <select class="selectable selectable_multiple form-control" id="kunden" tabindex="-1" name="kunden[]" data-placeholder=" hier klicken..." multiple>
                        {{if isset($smarty_vars.kundenliste)}}
                            {{foreach from=$smarty_vars.kundenliste item='row'}}
                                <option value="{{$row.kundennummer}}" {{if isset($smarty_vars.values.kunde)}} {{if $smarty_vars.values.kunde == $row.kundennummer}} selected{{/if}}{{/if}}>{{$row.kundennummer}} - {{$row.name}}</option>
                            {{/foreach}}
                        {{/if}}
                    </select>
                </div>
            </div>
        </div>
    {{/if}}
    <div class="row mb-4">
        <div class="col">
            <div class="content-box">
                <button type="submit" class="btn btn-secondary form-control">Schichtplaner öffnen</button>
            </div>
        </div>
    </div>
</form>

{{$css = '
    <!-- Select2 -->
    <link href="/assets/vendors/select2/dist/css/select2.min.css" rel="stylesheet">

    <!-- Select2 -->
    <style>
        .select2-container .select2-selection--multiple {
            min-height: 100px;
        }

        @media (max-width: 575px) {
            .dropdown-toggle::after {
                margin-left: 0;
            }

            .p-xs-down-1 {
                padding: .5rem .5rem;
            }

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

    <!-- General -->
    <style>
        .overflow-x-unset {
            overflow-x: unset !important;
        }
    </style>
' scope=parent}}

{{capture name='js'}}
    <!-- Select2 -->
    <script src="/assets/vendors/select2/dist/js/select2.full.min.js"></script>

    <!-- Select2 -->
    <script>
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
    </script>
    <!-- /Select2 -->

    <!-- General -->
    <script>
        $kalenderwochen_are_being_loaded = false;

        $(document).ready(function () {
            // minimize DOM access by saving objects in vars
            $kalenderwoche = $("#kalenderwoche");
            $kunden = $("#kunden");
            $jahr = $("#jahr");
            $vorige_kw = $("#vorige_kw");
            $aktuelle_kw = $("#aktuelle_kw");
            $naechste_kw = $("#naechste_kw");
            $jahresauswahl = $("#jahresauswahl").find("a");
            $error_container = $("#error_container");
            $error_content = $("#error_content");
            $jahresauswahl_button = $("#jahresauswahl_button");

            function showError(message) {
                $error_content.html(message);
                $error_container.show();
            }

            function changeJahr(jahr, callback) {
                if ($jahr.val() != jahr) {
                    $.ajax({
                        type: "POST",
                        url: "/schichten/ajax",
                        data: {type: 'kalenderwochen', year: jahr},
                        success: function (data) {
                            if (data.hasOwnProperty('status')) {
                                if (data.status == "success") {
                                    if (data.hasOwnProperty('kalenderwochen')) {
                                        $kalenderwoche.select2("destroy");
                                        $kalenderwoche.html("");
                                        $.each(data.kalenderwochen, function (index, obj) {
                                            var option = $('<option/>');
                                            option.attr("value", obj.kw);
                                            option.text("KW " + obj.kw + " | " + obj.von + " - " + obj.bis);
                                            $kalenderwoche.append(option);
                                        });
                                        $kalenderwoche.select2({
                                            allowClear: false,
                                            language: {
                                                "noResults": function() {
                                                    return "Keine Ergebnisse gefunden.";
                                                }
                                            },
                                            width: "100%"
                                        });
                                        $jahr.val(jahr);
                                        $jahresauswahl_button.html(jahr);
                                        callback();
                                    } else {
                                        showError("Es ist ein Fehler aufgetreten.");
                                    }
                                } else if (data.status == "not_logged_in") {
                                    location.reload();
                                } else {
                                    showError("Es ist ein Fehler aufgetreten.");
                                } 
                            } else {
                                showError("Es ist ein Fehler aufgetreten.");
                            }
                        },
                        error: function () {
                            showError("Es ist ein Fehler aufgetreten.");
                        }
                    });
                } else {
                    callback();
                }
            }

            // Jahresauswahl button
            $jahresauswahl.click(function () {
                if ($jahr.val() != $(this).data("jahr")) {
                    changeJahr($(this).data("jahr"), function () {});
                }
            });

            // Aktuelle KW
            $aktuelle_kw.click(function () {
                changeJahr({{$smarty.now|date_format:"Y"}}, function() {
                    $kalenderwoche.val({{$smarty.now|date_format:"W"}}).change();
                });
            });

            // Vorige KW
            $vorige_kw.click(function () {
                changeJahr({{$smarty_vars.prev.year}}, function() {
                    $kalenderwoche.val({{$smarty_vars.prev.week}}).change();
                });
            });

            // Nächste KW
            $naechste_kw.click(function () {
                changeJahr({{$smarty_vars.next.year}}, function() {
                    $kalenderwoche.val({{$smarty_vars.next.week}}).change();
                });
            });
        });
    </script>
{{/capture}}

{{$js=$smarty.capture.js scope=parent}}