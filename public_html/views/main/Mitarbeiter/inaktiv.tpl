{{$title="Mitarbeiter | Inaktive anzeigen" scope=parent}}

<div class="row mb-4">
    <div class="col">
        <div class="content-box">
            {{if isset($smarty_vars.error)}}
                <div class="alert alert-danger">
                    {{$smarty_vars.error}}
                </div>
            {{/if}}
            
            {{include file='views/main/components/tables.tpl'}}

            {{if isset($smarty_vars.mitarbeiterliste)}}
                {{$table_tag|default:"<table>"}}
                    <thead>
                        <tr>
                            <th>Pers. #</th>
                            <th>Vorname</th>
                            <th>Nachname</th>
                            <th>Telefon</th>
                            <th>E-Mail</th>
                            <th>Adresse</th>
                            <th>Geburtsd.</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{foreach from=$smarty_vars.mitarbeiterliste item='row'}}
                            <tr>
                                <th role="row">{{$row.personalnummer}}</th>
                                <td class="clickable" data-href="/mitarbeiter/bearbeiten/{{$row.personalnummer}}">{{$row.vorname}}</td>
                                <td class="clickable" data-href="/mitarbeiter/bearbeiten/{{$row.personalnummer}}">{{$row.nachname}}</td>
                                <td class="clickable" data-href="/mitarbeiter/bearbeiten/{{$row.personalnummer}}">{{$row.telefon1}}{{if $row.telefon2 != ''}}{{if $row.telefon1 != ''}}, {{/if}}{{$row.telefon2}}{{/if}}</td>
                                <td class="clickable" data-href="/mitarbeiter/bearbeiten/{{$row.personalnummer}}">{{$row.emailadresse}}</td>
                                <td class="clickable" data-href="/mitarbeiter/bearbeiten/{{$row.personalnummer}}">{{$row.strasse}}{{if $row.hausnummer != ''}} {{$row.hausnummer}}{{/if}}{{if $row.adresszusatz != ''}}, {{$row.adresszusatz}}{{/if}}{{if $row.postleitzahl != ''}}, {{$row.postleitzahl}}{{/if}}{{if $row.ort != ''}} {{$row.ort}}{{/if}}</td>
                                <td class="clickable" data-href="/mitarbeiter/bearbeiten/{{$row.personalnummer}}">{{$row.geburtsdatum}}</td>
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