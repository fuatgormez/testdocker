<div class="row">
    <div class="form-group col-12 col-sm-6">
        <label class="form-control-label" for="personalnummer">Personalnummer <span class="required">*</span></label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-link fa-fw"></i></span>
            <input type="number" min="1" class="form-control" id="personalnummer" name="personalnummer" placeholder="Personalnummer" value="{{$smarty_vars.values.personalnummer|default:""}}" required>
        </div>
    </div>
    <div class="form-group col-12 col-sm-6">
        <label class="form-control-label" for="anrede">Anrede <span class="required">*</span></label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-venus-mars fa-fw"></i></span>
            <select name="geschlecht" class="form-control" id="geschlecht" required>
                <option value="" {{if (isset($smarty_vars.values.geschlecht))}} {{if ($smarty_vars.values.geschlecht == '')}} selected{{/if}}{{/if}}>Bitte auswählen</option>
                <option value="männlich" {{if (isset($smarty_vars.values.geschlecht))}} {{if ($smarty_vars.values.geschlecht == 'männlich')}} selected{{/if}}{{/if}}>Herr</option>
                <option value="weiblich" {{if (isset($smarty_vars.values.geschlecht))}} {{if ($smarty_vars.values.geschlecht == 'weiblich')}} selected{{/if}}{{/if}}>Frau</option>
            </select>
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-12 col-sm-6">
        <label class="form-control-label" for="vorname">Vorname <span class="required">*</span></label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-user fa-fw"></i></span>
            <input type="text" class="form-control" id="vorname" name="vorname" placeholder="Max" value="{{$smarty_vars.values.vorname|default:""}}" required>
        </div>
    </div>
    <div class="form-group col-12 col-sm-6">
        <label class="form-control-label" for="nachname">Nachname <span class="required">*</span></label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-user fa-fw"></i></span>
            <input type="text" class="form-control" id="nachname" name="nachname" placeholder="Mustermann" value="{{$smarty_vars.values.nachname|default:""}}" required>
        </div>
    </div>
</div>