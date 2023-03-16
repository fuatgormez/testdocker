
<div class="form-group">
    <div class="input-group">
        <span class="input-group-addon"><i class="fa fa-fw fa-tasks"></i></span>
        <input type="text" class="form-control" name="bezeichnung" placeholder="Beispieltarif" value="{{$smarty_vars.values.bezeichnung|default:""}}" required>
    </div>
</div>

<button type="submit" class="btn btn-secondary form-control">Speichern</button>