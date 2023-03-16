{{$container_class="container" scope=parent}}

{{$title="Transfer" scope=parent}}

<div class="row mb-4">
    <div class="col">
        <div class="content-box">
            {{if isset($smarty_vars.info)}}
            <pre style="overflow:unset;">{{$smarty_vars.info}}</pre>
            {{/if}}
        </div>
    </div>
</div>