
<div class="form-group">
    <label class="form-control-label" for="bezeichnung">Bezeichnung</label>
    <div class="input-group">
        <span class="input-group-addon"><i class="fa fa-fw fa-tasks"></i></span>
        <input type="text" class="form-control" name="bezeichnung" id="bezeichnung" placeholder="Beispielabteilung" value="{{$smarty_vars.values.bezeichnung|default:""}}" required>
    </div>
</div>

<div class="form-group">
    <label class="form-control-label" for="in_rechnung_stellen">In Rechnung stellen</label>
    <div class="input-group">
        <span class="input-group-addon"><i class="fa fa-fw fa-tasks"></i></span>
        <select name="in_rechnung_stellen" id="in_rechnung_stellen" class="form-control" required>
            <option value="ja"{{if isset($smarty_vars.values.in_rechnung_stellen)}}{{if $smarty_vars.values.in_rechnung_stellen == "ja"}} selected{{/if}}{{/if}}>ja</option>
            <option value="nein"{{if isset($smarty_vars.values.in_rechnung_stellen)}}{{if $smarty_vars.values.in_rechnung_stellen == "nein"}} selected{{/if}}{{/if}}>nein</option>
        </select>
    </div>
</div>

{{if $smarty_vars.company == 'tps'}}
    <div class="form-group">
        <label class="form-control-label" for="palettenabteilung">Palettenabteilung</label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-fw fa-tasks"></i></span>
            <select name="palettenabteilung" id="palettenabteilung" class="form-control" required>
                <option value="nein"{{if isset($smarty_vars.values.palettenabteilung)}}{{if $smarty_vars.values.palettenabteilung == "nein"}} selected{{/if}}{{/if}}>nein</option>
                <option value="ja"{{if isset($smarty_vars.values.palettenabteilung)}}{{if $smarty_vars.values.palettenabteilung == "ja"}} selected{{/if}}{{/if}}>ja</option>
            </select>
        </div>
    </div>
{{/if}}

<button type="submit" class="btn btn-secondary form-control">Speichern</button>