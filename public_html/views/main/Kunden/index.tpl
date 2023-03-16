{{$title="Kunden | Alle anzeigen" scope=parent}}

<div class="row mb-4">
    <div class="col">
        <div class="content-box">
            {{if isset($smarty_vars.error)}}
                <div class="alert alert-danger">
                    {{$smarty_vars.error}}
                </div>
            {{/if}}
            
            {{include file='views/main/components/tables.tpl'}}

            {{if isset($smarty_vars.kundenliste)}}
                {{$table_tag|default:"<table>"}}
                    <thead>
                        <tr>
                            <th>Kundennr.</th>
                            <th>Name</th>
                            <th>Adresse</th>
                            <th>Ansprechpartner</th>
                            <th>Telefon</th>
                            <th>Fax</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{foreach from=$smarty_vars.kundenliste item='row'}}
                            <tr>
                                <th role="row">{{$row.kundennummer}}</th>
                                <td class="clickable" data-href="/kunden/bearbeiten/{{$row.kundennummer}}">{{$row.name}}</td>
                                <td class="clickable" data-href="/kunden/bearbeiten/{{$row.kundennummer}}">{{$row.strasse}}{{if $row.postleitzahl != ''}}, {{$row.postleitzahl}}{{/if}}{{if $row.ort != ''}} {{$row.ort}}{{/if}}</td>
                                <td class="clickable" data-href="/kunden/bearbeiten/{{$row.kundennummer}}">{{$row.ansprechpartner}}</td>
                                <td class="clickable" data-href="/kunden/bearbeiten/{{$row.kundennummer}}">{{$row.telefon1}}{{if $row.telefon2 != ''}}, {{$row.telefon2}}{{/if}}</td>
                                <td class="clickable" data-href="/kunden/bearbeiten/{{$row.kundennummer}}">{{$row.fax}}</td>
                                <td class="clickable" data-href="/kunden/bearbeiten/{{$row.kundennummer}}">{{$row.emailadresse}}</td>
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