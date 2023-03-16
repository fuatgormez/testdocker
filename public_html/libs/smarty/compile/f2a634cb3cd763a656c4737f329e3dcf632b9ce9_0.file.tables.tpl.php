<?php
/* Smarty version 3.1.30, created on 2019-07-11 08:49:28
  from "/usr/local/www/apache24/noexec/ttact-intern-software/views/main/components/tables.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5d26dbf80e86c5_61344531',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'f2a634cb3cd763a656c4737f329e3dcf632b9ce9' => 
    array (
      0 => '/usr/local/www/apache24/noexec/ttact-intern-software/views/main/components/tables.tpl',
      1 => 1562525864,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5d26dbf80e86c5_61344531 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_assignInScope('table_tag', '<table id="datatable-responsive" class="table table-striped table-bordered table-hover dt-responsive nowrap" cellspacing="0" width="100%">' ,false ,2);
?>

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

<?php $_smarty_tpl->_assignInScope('css', '
    <!-- Datatables -->
    <link href="/assets/vendors/DataTables/datatables.min.css" rel="stylesheet">

    <!-- Datatables -->
    <style>
        tbody .clickable:hover {
            cursor: pointer;
        }
    </style>
' ,false ,2);
?>

<?php $_smarty_tpl->_assignInScope('js', '
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
' ,false ,2);
}
}
