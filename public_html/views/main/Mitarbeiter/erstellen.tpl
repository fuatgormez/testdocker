{{$container_class="container" scope=parent}}

{{$title="Mitarbeiter | erstellen" scope=parent}}

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

            <form action="/mitarbeiter/erstellen" method="post">
                {{include file = 'views/main/Mitarbeiter/components/form_a.tpl'}}
                {{include file = 'views/main/Mitarbeiter/components/form_c.tpl'}}
            </form>
        </div>
    </div>
</div>