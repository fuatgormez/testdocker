{{$container_class="container" scope=parent}}

{{$title="Mitarbeiter | Datenabgleich" scope=parent}}

<div class="row mb-4">
    <div class="col">
        <div class="content-box">
            {{if isset($smarty_vars.success)}}
            <div class="alert alert-success">
                {{$smarty_vars.success}}
            </div>
            {{/if}}
            {{if isset($smarty_vars.error)}}
            <div class="alert alert-danger">
                {{$smarty_vars.error}}
            </div>
            {{/if}}

            <div class="alert alert-info">Bitte exportieren Sie die .csv-Datei aus Agenda Lohn und laden Sie sie hier hoch.</div>

            <form action="/temp/datenabgleich" method="post" enctype="multipart/form-data">
                <input class="form-control mb-3" type="file" name="datei">
                <button type="submit" class="btn btn-secondary btn-block">Hochladen</button>
            </form>
        </div>
    </div>
</div>