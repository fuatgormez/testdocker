{{$container_class="container" scope=parent}}

{{$title="Rechnungen | bearbeiten" scope=parent}}

<div class="row mb-4">
    <div class="col">
        <div class="content-box">
            <h2 class="mb-0">Rechnung Nr. <strong>{{$smarty_vars.values.rechnungsnummer|default:"???"}}</strong></h2>
        </div>
    </div>
</div>

{{if isset($smarty_vars.rechnungsposten)}}
    {{foreach from=$smarty_vars.rechnungsposten item='row'}}
        <div class="row mb-4">
            <div class="col">
                <div class="content-box">
                    <form action="/rechnungen/bearbeiten" method="post">
                        <div class="row">
                            <div class="form-group col-12 col-md-6">
                                <label class="form-control-label">Leistungsart</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                    <input type="text" class="form-control" name="leistungsart" placeholder="Kasse" value="{{$row.leistungsart}}" required>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-3">
                                <label class="form-control-label">Menge</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calculator fa-fw"></i></span>
                                    <input type="text" class="form-control" name="menge" placeholder="25" value="{{$row.menge}}" required>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-3">
                                <label class="form-control-label">Einzelpreis</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calculator fa-fw"></i></span>
                                    <input type="text" class="form-control" name="einzelpreis" placeholder="15,95" value="{{$row.einzelpreis}}" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <button type="submit" name="delete_and_update" value="true" class="btn btn-danger form-control">Löschen & Aktualisieren</button>
                            </div>
                            <div class="col-12 mt-3 col-md-6 mt-md-0">
                                <button type="submit" name="update" value="true" class="btn btn-secondary form-control">Aktualisieren</button>
                            </div>
                        </div>
                        <input type="hidden" name="rechnungsposten_id" value="{{$row.rechnungsposten_id}}">
                        <input type="hidden" name="rechnung_data" value="{{$smarty_vars.values.rechnung_data}}">
                        <input type="hidden" name="rechnungsposten_data" value="{{$smarty_vars.values.rechnungsposten_data}}">
                    </form>
                </div>
            </div>
        </div>
    {{/foreach}}
{{/if}}

<div class="row mb-4">
    <div class="col">
        <div class="content-box">
            <form action="/rechnungen/bearbeiten" method="post">
                <div class="row">
                    <div class="form-group col-12 col-md-6">
                        <label class="form-control-label">Leistungsart</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                            <input type="text" class="form-control" name="leistungsart" placeholder="Kasse" required>
                        </div>
                    </div>
                    <div class="form-group col-12 col-md-3">
                        <label class="form-control-label">Menge</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-calculator fa-fw"></i></span>
                            <input type="text" class="form-control" name="menge" placeholder="25" required>
                        </div>
                    </div>
                    <div class="form-group col-12 col-md-3">
                        <label class="form-control-label">Einzelpreis</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-calculator fa-fw"></i></span>
                            <input type="text" class="form-control" name="einzelpreis" placeholder="15,95" required>
                        </div>
                    </div>
                </div>
                <button type="submit" name="create_and_update" value="true" class="btn btn-secondary form-control">Aktualisieren</button>
                <input type="hidden" name="rechnung_data" value="{{$smarty_vars.values.rechnung_data}}">
                <input type="hidden" name="rechnungsposten_data" value="{{$smarty_vars.values.rechnungsposten_data}}">
            </form>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col">
        <div class="content-box">
            <form action="/rechnungen/anzeigen" method="post">
                <div class="row">
                    <div class="form-group col-12 mb-0">
                        <input type="hidden" name="rechnung_data" value="{{$smarty_vars.values.rechnung_data}}">
                        <input type="hidden" name="rechnungsposten_data" value="{{$smarty_vars.values.rechnungsposten_data}}">
                        <button type="submit" class="btn btn-secondary form-control">Rechnung speichern</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

{{capture name='css'}}
{{/capture}}

{{$css=$smarty.capture.css scope=parent}}

{{capture name='scripts'}}
{{/capture}}

{{$js=$smarty.capture.scripts scope=parent}}