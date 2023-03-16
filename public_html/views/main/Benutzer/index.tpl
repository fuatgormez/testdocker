{{$container_class="container" scope=parent}}

{{$title="Benutzer | Alle anzeigen" scope=parent}}

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

            <a class="btn btn-secondary btn-block mb-3" href="/benutzer/erstellen"><i class="fa fa-plus-circle mr-1"></i> Neuen Benutzer hinzufügen</a>
            {{include file='views/main/components/tables.tpl'}}
            {{if isset($smarty_vars.benutzerliste)}}
                {{$table_tag|default:"<table>"}}
                    <thead>
                        <tr>
                            <td>ID</td>
                            <td>Aktiv</td>
                            <td>Benutzername</td>
                            <td>Name</td>
                            <td>Stufe</td>
                        </tr>
                    </thead>
                    <tbody>
                        {{foreach from=$smarty_vars.benutzerliste item='row'}}
                            <tr{{if $row.aktiv == 'nein'}} class="bg-danger text-white"{{/if}}>
                                <td class="clickable" data-href="/benutzer/bearbeiten/{{$row.id}}">{{$row.id}}</td>
                                <td class="clickable" data-href="/benutzer/bearbeiten/{{$row.id}}">{{$row.aktiv}}</td>
                                <td class="clickable" data-href="/benutzer/bearbeiten/{{$row.id}}">{{$row.benutzername}}</td>
                                <td class="clickable" data-href="/benutzer/bearbeiten/{{$row.id}}">{{$row.name}}</td>
                                <td class="clickable" data-href="/benutzer/bearbeiten/{{$row.id}}">{{$row.stufe}}</td>
                            </tr>
                        {{/foreach}}
                    </tbody>
                </table>
            {{/if}}
        </div>
    </div>
</div>

{{capture name='styles'}}
    <!-- General -->
    <style>
        a.btn:hover, a.btn:link, a.btn:visited, a.btn:active {
            color: black;
        }
    </style>
    <!-- /General-->

    {{$css|default:""}}
{{/capture}}

{{$css=$smarty.capture.styles scope=parent}}

{{capture name='scripts'}}
    {{$js|default:""}}
{{/capture}}

{{$js=$smarty.capture.scripts scope=parent}}