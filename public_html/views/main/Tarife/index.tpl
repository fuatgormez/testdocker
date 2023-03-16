{{$title="Tarife" scope=parent}}

{{if isset($smarty_vars.warning)}}
    <div class="row mb-4">
        <div class="col">
            <div class="content-box bg-danger text-white">
                {{$smarty_vars.warning}}
            </div>
        </div>
    </div>
{{/if}}

<div class="row">
    <div class="col-12 col-lg-8 mb-4">
        <div class="content-box">
            <h3 class="content-box-title">Alle anzeigen</h3>

            {{include file='views/main/components/tables.tpl'}}

            {{if isset($smarty_vars.tarifliste)}}
                {{$table_tag|default:"<table>"}}
                    <thead>
                        <tr>
                            <th>Tarif-ID</th>
                            <th>Bezeichnung</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{foreach from=$smarty_vars.tarifliste item='row'}}
                            <tr>
                                <th role="row">{{$row.id}}</th>
                                <td class="clickable" data-href="/tarife/bearbeiten/{{$row.id}}">{{$row.bezeichnung}}</td>
                            </tr>
                        {{/foreach}}
                    </tbody>
                </table>
            {{/if}}
        </div>
    </div>
    <div class="col-12 col-lg-4 mb-4">
        <div class="content-box">
            <h3 class="content-box-title">Neuer Tarif</h3>
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

            <form action="/tarife" method="post">
                {{include file='views/main/Tarife/components/form.tpl'}}
            </form>
        </div>
    </div>
</div>

{{$css=$css scope=parent}}

{{$js=$js scope=parent}}