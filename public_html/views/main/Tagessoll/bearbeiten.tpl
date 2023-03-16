{{$container_class="container" scope=parent}}

{{$title="Tagessoll | bearbeiten" scope=parent}}

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

            <form action="/tagessoll/bearbeiten/{{$smarty_vars.values.id}}" method="post">
                <div class="row">
                    <div class="form-group col-12{{if $smarty_vars.company != 'tps'}} col-sm-6{{/if}}">
                        <label class="form-control-label" for="tagessoll">Tagessoll</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-fw fa-calculator"></i></span>
                            <input type="text" class="form-control text-right" name="tagessoll" id="tagessoll" placeholder="0,00" value="{{$smarty_vars.values.tagessoll|default:0|number_format:2:',':''}}" required>
                        </div>
                    </div>
                    {{if $smarty_vars.company != 'tps'}}
                        <div class="form-group col-12{{if $smarty_vars.company != 'tps'}} col-sm-6{{/if}}">
                            <label class="form-control-label" for="tagessoll_montag">Montag</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-fw fa-calculator"></i></span>
                                <input type="text" class="form-control text-right" name="tagessoll_montag" id="tagessoll_montag" placeholder="0,00" value="{{$smarty_vars.values.tagessoll_montag|default:0|number_format:2:',':''}}" required>
                            </div>
                        </div>
                    {{/if}}
                </div>

                {{if $smarty_vars.company != 'tps'}}
                    <div class="row">
                        <div class="form-group col-12{{if $smarty_vars.company != 'tps'}} col-sm-6{{/if}}">
                            <label class="form-control-label" for="tagessoll_dienstag">Dienstag</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-fw fa-calculator"></i></span>
                                <input type="text" class="form-control text-right" name="tagessoll_dienstag" id="tagessoll_dienstag" placeholder="0,00" value="{{$smarty_vars.values.tagessoll_dienstag|default:0|number_format:2:',':''}}" required>
                            </div>
                        </div>
                        <div class="form-group col-12{{if $smarty_vars.company != 'tps'}} col-sm-6{{/if}}">
                            <label class="form-control-label" for="tagessoll_mittwoch">Mittwoch</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-fw fa-calculator"></i></span>
                                <input type="text" class="form-control text-right" name="tagessoll_mittwoch" id="tagessoll_mittwoch" placeholder="0,00" value="{{$smarty_vars.values.tagessoll_mittwoch|default:0|number_format:2:',':''}}" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-12{{if $smarty_vars.company != 'tps'}} col-sm-6{{/if}}">
                            <label class="form-control-label" for="tagessoll_donnerstag">Donnerstag</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-fw fa-calculator"></i></span>
                                <input type="text" class="form-control text-right" name="tagessoll_donnerstag" id="tagessoll_donnerstag" placeholder="0,00" value="{{$smarty_vars.values.tagessoll_donnerstag|default:0|number_format:2:',':''}}" required>
                            </div>
                        </div>
                        <div class="form-group col-12{{if $smarty_vars.company != 'tps'}} col-sm-6{{/if}}">
                            <label class="form-control-label" for="tagessoll_freitag">Freitag</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-fw fa-calculator"></i></span>
                                <input type="text" class="form-control text-right" name="tagessoll_freitag" id="tagessoll_freitag" placeholder="0,00" value="{{$smarty_vars.values.tagessoll_freitag|default:0|number_format:2:',':''}}" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-12{{if $smarty_vars.company != 'tps'}} col-sm-6{{/if}}">
                            <label class="form-control-label" for="tagessoll_samstag">Samstag</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-fw fa-calculator"></i></span>
                                <input type="text" class="form-control text-right" name="tagessoll_samstag" id="tagessoll_samstag" placeholder="0,00" value="{{$smarty_vars.values.tagessoll_samstag|default:0|number_format:2:',':''}}" required>
                            </div>
                        </div>
                        <div class="form-group col-12{{if $smarty_vars.company != 'tps'}} col-sm-6{{/if}}">
                            <label class="form-control-label" for="tagessoll_sonntag">Sonntag</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-fw fa-calculator"></i></span>
                                <input type="text" class="form-control text-right" name="tagessoll_sonntag" id="tagessoll_sonntag" placeholder="0,00" value="{{$smarty_vars.values.tagessoll_sonntag|default:0|number_format:2:',':''}}" required>
                            </div>
                        </div>
                    </div>
                {{/if}}

                <button type="submit" name="speichern" value="ja" class="btn btn-secondary form-control">Speichern</button>
            </form>
        </div>
    </div>
</div>
