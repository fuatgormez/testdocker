{{$container_class="container" scope=parent}}

{{$title="Tarife | bearbeiten" scope=parent}}

<div class="row mb-4">
    <div class="col">
        <div class="content-box">
            <h3 class="content-box-title">Tarif Nr. {{$smarty_vars.values.id|default:"???"}}: <strong>{{$smarty_vars.values.bezeichnung|default:"???"}}</strong></h3>

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

            <h4>Bezeichnung</h4>
            <form action="/tarife/bearbeiten/{{$smarty_vars.values.id}}" method="post">
                {{include file='views/main/Tarife/components/form.tpl'}}
            </form>

            <h4 class="mt-5">Tariflohnbeträge</h4>
            <a class="btn btn-secondary btn-block my-3" href="/tariflohnbetrag/erstellen/{{$smarty_vars.values.id|default:"???"}}"><i class="fa fa-plus-circle mr-1"></i> Neuen Tariflohnbetrag hinzufügen</a>
            {{include file='views/main/components/tables.tpl'}}
            {{if isset($smarty_vars.tariflohnbetragsliste)}}
                {{$table_tag|default:"<table>"}}
                <thead>
                <tr>
                    <td>Gültig ab</td>
                    <td>Lohn/Std (€)</td>
                </tr>
                </thead>
                <tbody>
                {{foreach from=$smarty_vars.tariflohnbetragsliste item='row'}}
                    <tr>
                        <td class="clickable" data-href="/tariflohnbetrag/bearbeiten/{{$row.id}}">{{$row.gueltig_ab}}</td>
                        <td class="clickable" data-href="/tariflohnbetrag/bearbeiten/{{$row.id}}">{{$row.lohn}}</td>
                    </tr>
                {{/foreach}}
                </tbody>
                </table>
            {{/if}}
        </div>
    </div>
</div>

{{$css=$css scope=parent}}

{{$js=$js scope=parent}}