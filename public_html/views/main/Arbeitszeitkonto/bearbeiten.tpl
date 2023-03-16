{{$container_class="container" scope=parent}}

{{$title="Arbeitszeitkonto | bearbeiten" scope=parent}}

<div class="row mb-4">
    <div class="col">
        <div class="content-box">
            <h3 class="content-box-title">für {{$smarty_vars.values.vorname|default:"???"}} {{$smarty_vars.values.nachname|default:"???"}} ({{$smarty_vars.values.personalnummer|default:"???"}}) - {{$smarty_vars.values.monatsname|default:"???"}} {{$smarty_vars.values.jahr|default:"???"}}</h3>

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

            <form action="/arbeitszeitkonto/bearbeiten/{{$smarty_vars.values.id}}" method="post">
                <div class="row">
                    <div class="form-group col-12">
                        <label class="form-control-label" for="stunden">Stunden</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-fw fa-calculator"></i></span>
                            <input type="text" class="form-control text-right" name="stunden" id="stunden" placeholder="0,00" value="{{$smarty_vars.values.stunden|default:0|number_format:2:',':''}}" required>
                        </div>
                    </div>
                </div>
                <button type="submit" name="speichern" value="ja" class="btn btn-secondary form-control">Speichern</button>
            </form>
        </div>
    </div>
</div>
