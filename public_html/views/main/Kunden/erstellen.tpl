{{$container_class="container" scope=parent}}

{{$title="Kunden | erstellen" scope=parent}}

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

            <form action="/kunden/erstellen" method="post">
                {{include file = 'views/main/Kunden/components/form.tpl'}}
            </form>
        </div>
    </div>
</div>

{{$js=$js scope=parent}}