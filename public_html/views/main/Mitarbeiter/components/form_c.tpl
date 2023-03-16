<div class="row">
    <div class="form-group col-12 col-sm-6">
        <label class="form-control-label" for="telefon1">Telefon 1</label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-phone fa-fw"></i></span>
            <input type="text" class="form-control" id="telefon1" name="telefon1" placeholder="030 123456 01" value="{{$smarty_vars.values.telefon1|default:""}}">
        </div>
    </div>
    <div class="form-group col-12 col-sm-6">
        <label class="form-control-label" for="telefon2">Telefon 2</label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-phone fa-fw"></i></span>
            <input type="text" class="form-control" id="telefon2" name="telefon2" placeholder="030 123456 02" value="{{$smarty_vars.values.telefon2|default:""}}">
        </div>
    </div>
</div>

<div class="form-group">
    <label class="form-control-label" for="emailadresse">E-Mail-Adresse</label>
    <div class="input-group">
        <span class="input-group-addon"><i class="fa fa-envelope fa-fw"></i></span>
        <input type="email" class="form-control" id="emailadresse" name="emailadresse" placeholder="beispiel@domain.de" value="{{$smarty_vars.values.emailadresse|default:""}}">
    </div>
</div>
<button type="submit" class="btn btn-secondary form-control">Speichern</button>