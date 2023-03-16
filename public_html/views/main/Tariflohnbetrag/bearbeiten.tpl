{{$container_class="container" scope=parent}}

{{$title="Tariflohnbetrag | bearbeiten" scope=parent}}

<div class="row mb-4">
    <div class="col">
        <div class="content-box">
            <h3 class="content-box-title">für <strong>{{$smarty_vars.values.tarifbezeichnung|default:"???"}}</strong></h3>

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

            <form action="/tariflohnbetrag/bearbeiten/{{$smarty_vars.values.id|default:"???"}}" method="post">
                {{include file='views/main/Tariflohnbetrag/components/form.tpl'}}
            </form>
        </div>
    </div>
</div>