{{$container_class="container" scope=parent}}

{{$title="Lohnbuchung | erstellen" scope=parent}}

<div class="row mb-4">
    <div class="col">
        <div class="content-box">
            <h3 class="content-box-title">für <strong>{{$smarty_vars.values.vorname|default:"???"}} {{$smarty_vars.values.nachname|default:"???"}} ({{$smarty_vars.values.personalnummer|default:"???"}})</strong></h3>

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

            <form action="/lohnbuchung/erstellen/{{$smarty_vars.values.personalnummer|default:"???"}}" method="post">
                <div class="row">
                    <div class="form-group col-12 col-sm-6">
                        <label class="form-control-label" for="jahr">Jahr <span class="required">*</span></label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-calendar fa-fw"></i></span>
                            <input name="jahr" type="text" class="form-control" id="jahr" value="{{$smarty_vars.values.jahr|default:"2023"}}" placeholder="JJJJ">
                        </div>
                    </div>
                    <div class="form-group col-12 col-sm-6">
                        <label class="form-control-label" for="monat">Monat <span class="required">*</span></label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-calendar fa-fw"></i></span>
                            <select name="monat" class="form-control" id="monat">
                                <option value="">-- bitte auswählen</option>
                                <option value="1"{{if isset($smarty_vars.values.monat)}}{{if $smarty_vars.values.monat == 1}} selected{{/if}}{{/if}}>Januar</option>
                                <option value="2"{{if isset($smarty_vars.values.monat)}}{{if $smarty_vars.values.monat == 2}} selected{{/if}}{{/if}}>Februar</option>
                                <option value="3"{{if isset($smarty_vars.values.monat)}}{{if $smarty_vars.values.monat == 3}} selected{{/if}}{{/if}}>März</option>
                                <option value="4"{{if isset($smarty_vars.values.monat)}}{{if $smarty_vars.values.monat == 4}} selected{{/if}}{{/if}}>April</option>
                                <option value="5"{{if isset($smarty_vars.values.monat)}}{{if $smarty_vars.values.monat == 5}} selected{{/if}}{{/if}}>Mai</option>
                                <option value="6"{{if isset($smarty_vars.values.monat)}}{{if $smarty_vars.values.monat == 6}} selected{{/if}}{{/if}}>Juni</option>
                                <option value="7"{{if isset($smarty_vars.values.monat)}}{{if $smarty_vars.values.monat == 7}} selected{{/if}}{{/if}}>Juli</option>
                                <option value="8"{{if isset($smarty_vars.values.monat)}}{{if $smarty_vars.values.monat == 8}} selected{{/if}}{{/if}}>August</option>
                                <option value="9"{{if isset($smarty_vars.values.monat)}}{{if $smarty_vars.values.monat == 9}} selected{{/if}}{{/if}}>September</option>
                                <option value="10"{{if isset($smarty_vars.values.monat)}}{{if $smarty_vars.values.monat == 10}} selected{{/if}}{{/if}}>Oktober</option>
                                <option value="11"{{if isset($smarty_vars.values.monat)}}{{if $smarty_vars.values.monat == 11}} selected{{/if}}{{/if}}>November</option>
                                <option value="12"{{if isset($smarty_vars.values.monat)}}{{if $smarty_vars.values.monat == 12}} selected{{/if}}{{/if}}>Dezember</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-12">
                        <label class="form-control-label" for="lohnart">Lohnart <span class="required">*</span></label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-calculator fa-fw"></i></span>
                            <input name="lohnart" type="text" class="form-control" id="lohnart" value="{{$smarty_vars.values.lohnart|default:""}}" placeholder="z.B. 203 oder 8413">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-12 col-sm-6">
                        <label class="form-control-label" for="wert">Wert <span class="required">*</span></label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-eur fa-fw"></i></span>
                            <input name="wert" type="text" class="form-control" id="wert" value="{{$smarty_vars.values.wert|default:""}}" placeholder="z.B. 300 oder 8.50">
                        </div>
                    </div>
                    <div class="form-group col-12 col-sm-6">
                        <label class="form-control-label" for="faktor">Faktor</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-times fa-fw"></i></span>
                            <input name="faktor" type="text" class="form-control" id="faktor" value="{{$smarty_vars.values.faktor|default:""}}" placeholder="z.B. (leer) oder 5">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-12">
                        <label class="form-control-label" for="bezeichnung">Bezeichnung</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                            <input name="bezeichnung" type="text" class="form-control" id="bezeichnung" value="{{$smarty_vars.values.bezeichnung|default:""}}" placeholder="z.B. Vorschuss oder Vergessene Stunden">
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-secondary form-control">Speichern</button>
            </form>
        </div>
    </div>
</div>