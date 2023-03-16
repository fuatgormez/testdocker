{{$table_tag = '<table id="datatable-responsive" class="table table-striped table-bordered table-hover dt-responsive nowrap" cellspacing="0" width="100%">' scope=parent}}

<div class="row" id="tools" style="display:none;">
    <div class="form-group col-12 col-sm-6">
        <div class="input-group" id="zeilen">
            <span class="input-group-addon"><i class="fa fa-table fa-fw"></i></span>

        </div>
    </div>
    <div class="form-group col-12 col-sm-6">
        <div class="input-group" id="suche">
            <span class="input-group-addon"><i class="fa fa-search fa-fw"></i></span>

        </div>
    </div>
</div>

{{$css = '
    <!-- Datatables -->
    <link href="/assets/vendors/DataTables/datatables.min.css" rel="stylesheet">

    <!-- Datatables -->
    <style>
        tbody .clickable:hover {
            cursor: pointer;
        }
    </style>
' scope=parent}}

{{$js = '
    <!-- Datatables -->
    <script src="/assets/vendors/DataTables/datatables.min.js"></script>

    <!-- Datatables -->
    <script>
        $(document).ready(function() {
            $("tbody .clickable").click(function () {
                window.location = $(this).attr("data-href");
            });
            $("#datatable-responsive").DataTable(
                {
                    stateSave: true,
                    colReorder: true,
                    "lengthMenu": [[10, 50, 100, -1], ["10 Zeilen pro Seite", "50 Zeilen pro Seite", "100 Zeilen pro Seite", "Alle Zeilen anzeigen"]],
                    "language": {
                        "decimal":        "",
                        "emptyTable":     "Keine Einträge vorhanden.",
                        "info":           "Zeige _START_ bis _END_ von _TOTAL_ Zeilen",
                        "infoEmpty":      "Zeige 0 bis 0 von 0 Zeilen",
                        "infoFiltered":   "(gefiltert von insgesamt _MAX_ Zeilen)",
                        "infoPostFix":    "",
                        "thousands":      "",
                        "lengthMenu":     "_MENU_",
                        "loadingRecords": "Lädt...",
                        "processing":     "Wird bearbeitet...",
                        "search":         "",
                        "zeroRecords":    "Keine passenden Zeilen gefunden.",
                        "paginate": {
                            "first":      "Erste",
                            "last":       "Letzte",
                            "next":       "Nächste",
                            "previous":   "Vorherige"
                        },
                        "aria": {
                            "sortAscending":  ": aktivieren um die Spalte aufsteigend zu sortieren",
                            "sortDescending": ": aktivieren um die Spalte herabsteigend zu sortieren"
                        }
                    }
                }
            );

            $("#tools").show();
            $("#datatable-responsive_filter > label > input").attr("class", "form-control");
            $("#datatable-responsive_filter > label > input").attr("placeholder", "Suchtext eingeben...");
            $("#datatable-responsive_filter > label > input").appendTo($("#suche"));
            $("#datatable-responsive_length > label > select").attr("class", "form-control");
            $("#datatable-responsive_length > label > select").appendTo($("#zeilen"));
            $("#datatable-responsive_length").remove();
            $("#datatable-responsive_filter").remove();
        });
    </script>
' scope=parent}}