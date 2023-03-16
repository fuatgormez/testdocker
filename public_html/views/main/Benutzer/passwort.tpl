{{$container_class="container" scope=parent}}

{{$title="Benutzer | Passwort ändern" scope=parent}}

<div class="row mb-4">
    <div class="col">
        <div class="content-box">
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

            <form action="/benutzer/bearbeiten" method="post">
                <div class="row">
                    <div class="form-group col-12 col-sm-6">
                        <label class="form-control-label" for="passwort_neu">Neues Passwort <span class="required">*</span></label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-key fa-fw"></i></span>
                            <input type="password" class="form-control" id="passwort_neu" name="passwort_neu" placeholder="Passwort" autocomplete="off" required>
                        </div>
                    </div>
                    <div class="form-group col-12 col-sm-6">
                        <label class="form-control-label" for="passwort_neu_bestaetigen">bestätigen <span class="required">*</span></label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-key fa-fw"></i></span>
                            <input type="password" class="form-control" id="passwort_neu_bestaetigen" name="passwort_neu_bestaetigen" placeholder="bestätigen" autocomplete="off" required>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-secondary form-control">Speichern</button>
            </form>
        </div>
    </div>
</div>