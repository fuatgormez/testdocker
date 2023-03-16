{{$container_class="container" scope=parent}}

{{$title="Kundenkondition | erstellen" scope=parent}}

<div class="row mb-4">
    <div class="col">
        <div class="content-box">
            <h3 class="content-box-title">für Kunde <strong>{{$smarty_vars.values.kundennummer|default:"???"}}</strong> | <strong>{{$smarty_vars.values.bezeichnung|default:"???"}}</strong></h3>

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

            <form action="/kundenkondition/erstellen/{{$smarty_vars.values.kundennummer|default:"???"}}" method="post">
                {{include file = 'views/main/Kundenkondition/components/form.tpl'}}
            </form>
        </div>
    </div>
</div>

{{capture name='styles'}}
{{/capture}}

{{$css=$smarty.capture.styles scope=parent}}

{{capture name='scripts'}}
    {{$js|default:""}}
{{/capture}}

{{$js=$smarty.capture.scripts scope=parent}}