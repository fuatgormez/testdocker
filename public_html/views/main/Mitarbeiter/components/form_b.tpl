<div class="row">
    <div class="form-group col-12 col-sm-6">
        <label class="form-control-label" for="strasse">Straße</label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-map-marker fa-fw"></i></span>
            <input type="text" class="form-control" id="strasse" value="{{$smarty_vars.values.strasse|default:""}}" readonly>
        </div>
    </div>
    <div class="form-group col-12 col-sm-6">
        <label class="form-control-label" for="hausnummer">Hausnummer</label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-map-marker fa-fw"></i></span>
            <input type="text" class="form-control" id="hausnummer" value="{{$smarty_vars.values.hausnummer|default:""}}" readonly>
        </div>
    </div>
</div>
<div class="form-group">
    <label class="form-control-label" for="adresszusatz">Adresszusatz</label>
    <div class="input-group">
        <span class="input-group-addon"><i class="fa fa-map-marker fa-fw"></i></span>
        <input type="text" class="form-control" id="adresszusatz" value="{{$smarty_vars.values.adresszusatz|default:""}}" readonly>
    </div>
</div>
<div class="row">
    <div class="form-group col-12 col-sm-6">
        <label class="form-control-label" for="postleitzahl">Postleitzahl</label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-map-marker fa-fw"></i></span>
            <input type="text" class="form-control" id="postleitzahl" value="{{$smarty_vars.values.postleitzahl|default:""}}" readonly>
        </div>
    </div>
    <div class="form-group col-12 col-sm-6">
        <label class="form-control-label" for="ort">Ort</label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-map-marker fa-fw"></i></span>
            <input type="text" class="form-control" id="ort" value="{{$smarty_vars.values.ort|default:""}}" readonly>
        </div>
    </div>
</div>

<div class="form-group">
    <label class="form-control-label" for="geburtsdatum">Geburtsdatum</label>
    <div class="input-group">
        <span class="input-group-addon"><i class="fa fa-map-marker fa-fw"></i></span>
        <input type="text" class="form-control" id="geburtsdatum" value="{{$smarty_vars.values.geburtsdatum|default:""}}" readonly>
    </div>
</div>

<div class="row">
    <div class="form-group col-12 col-sm-6">
        <label class="form-control-label" for="iban">IBAN</label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-map-marker fa-fw"></i></span>
            <input type="text" class="form-control" id="iban" value="{{$smarty_vars.values.iban|default:""}}" readonly>
        </div>
    </div>
    <div class="form-group col-12 col-sm-6">
        <label class="form-control-label" for="bic">BIC</label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-map-marker fa-fw"></i></span>
            <input type="text" class="form-control" id="bic" value="{{$smarty_vars.values.bic|default:""}}" readonly>
        </div>
    </div>
</div>