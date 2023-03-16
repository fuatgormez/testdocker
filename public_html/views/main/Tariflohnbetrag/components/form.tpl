
<div class="row">
    <div class="form-group col-12 col-sm-6">
        <label class="form-control-label" for="gueltig_ab">Gültig ab <span class="required">*</span></label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-calendar fa-fw"></i></span>
            <input name="gueltig_ab" type="text" class="form-control" id="gueltig_ab" value="{{$smarty_vars.values.gueltig_ab|default:""}}" placeholder="TT.MM.JJJJ">
        </div>
    </div>
    <div class="form-group col-12 col-sm-6">
        <label class="form-control-label" for="lohn">Stundenlohn</label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-eur fa-fw"></i></span>
            <input name="lohn" type="text" class="form-control" id="lohn" value="{{$smarty_vars.values.lohn|default:""}}" placeholder="z.B. 9 oder 10.07">
        </div>
    </div>
</div>
<button type="submit" class="btn btn-secondary form-control">Speichern</button>
