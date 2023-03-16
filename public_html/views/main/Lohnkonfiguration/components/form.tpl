<div class="row">
    <div class="form-group col-12{{if $smarty_vars.company != 'tps'}} col-sm-6{{/if}}">
        <label class="form-control-label" for="gueltig_ab">Gültig ab <span class="required">*</span></label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-calendar fa-fw"></i></span>
            <input name="gueltig_ab" type="text" class="form-control" id="gueltig_ab" value="{{$smarty_vars.values.gueltig_ab|default:""}}" placeholder="TT.MM.JJJJ">
        </div>
    </div>
    {{if $smarty_vars.company != 'tps'}}
        <div class="form-group col-12 col-sm-6">
            <label class="form-control-label" for="tarif">Tarif <span class="required">*</span></label>
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-calculator fa-fw"></i></span>
                <select name="tarif" class="form-control" id="tarif">
                    <option value="">kein Tarif</option>
                    {{if isset($smarty_vars.tarifliste)}}
                        {{foreach from=$smarty_vars.tarifliste item='row'}}
                            <option value="{{$row.id}}"{{if isset($smarty_vars.values.tarif)}}{{if $smarty_vars.values.tarif == $row.id}} selected{{/if}}{{/if}}>{{$row.bezeichnung}}</option>
                        {{/foreach}}
                    {{/if}}
                </select>
            </div>
        </div>
    {{/if}}
</div>
<div class="row">
    <div class="form-group col-12 col-sm-6">
        <label class="form-control-label" for="wochenstunden">Wochenstunden</label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-clock-o fa-fw"></i></span>
            <input name="wochenstunden" type="text" class="form-control" id="wochenstunden" value="{{$smarty_vars.values.wochenstunden|default:""}}" placeholder="z.B. 35 oder 11.5">
        </div>
    </div>
    <div class="form-group col-12 col-sm-6">
        <label class="form-control-label" for="lohn">Gesamtlohn/Std{{if $smarty_vars.company == 'tps'}} <span class="required">*</span>{{/if}}</label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-eur fa-fw"></i></span>
            <input name="lohn" type="text" class="form-control" id="lohn" value="{{$smarty_vars.values.lohn|default:""}}" placeholder="z.B. 9 oder 10.07">
        </div>
    </div>
</div>
<button type="submit" class="btn btn-secondary form-control">Speichern</button>