{{$container_class="container" scope=parent}}

{{$title="Abteilungen | bearbeiten" scope=parent}}

<div class="row mb-4">
    <div class="col">
        <div class="content-box">
            <h3 class="content-box-title">Abteilung Nr. {{$smarty_vars.values.id|default:"???"}}: <strong>{{$smarty_vars.values.bezeichnung|default:"???"}}</strong></h3>

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

            <form action="/abteilungen/bearbeiten/{{$smarty_vars.values.id}}" method="post">
                {{include file='views/main/Abteilungen/components/form.tpl'}}
            </form>
        </div>
    </div>
</div>
