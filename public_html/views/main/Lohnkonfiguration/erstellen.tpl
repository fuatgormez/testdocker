{{$container_class="container" scope=parent}}

{{$title="Lohnkonfiguration | erstellen" scope=parent}}

<div class="row mb-4">
    <div class="col">
        <div class="content-box">
            <h3 class="content-box-title">für <strong>{{$smarty_vars.values.vorname|default:"???"}} {{$smarty_vars.values.nachname|default:"???"}} ({{$smarty_vars.values.personalnummer|default:"???"}})</strong></h3>

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

            {{if $smarty_vars.company != 'tps'}}
                <div class="alert alert-info mb-4">Bitte beachten Sie, dass der Gesamtstundenlohn nur in zwei Fällen eingetragen werden muss: <strong>1.</strong> Wenn der/die Mitarbeiter/in keinem Tarif zugeordnet wird. <strong>2.</strong> Wenn der/die Mitarbeiter/in einen übertariflichen Zuschlag erhalten soll. In diesem Fall ist der übertarifliche Zuschlag die Differenz zwischen dem hier eingegebenen Gesamtlohn und dem Tariflohn.</div>
            {{/if}}

            <form action="/lohnkonfiguration/erstellen/{{$smarty_vars.values.personalnummer|default:"???"}}" method="post">
                {{include file = 'views/main/Lohnkonfiguration/components/form.tpl'}}
            </form>
        </div>
    </div>
</div>