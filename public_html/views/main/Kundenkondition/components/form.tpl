
<div class="row">
    <div class="form-group col-12 col-sm-6">
        <label class="form-control-label" for="gueltig_ab">Gültig ab</label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-calendar fa-fw"></i></span>
            <input name="gueltig_ab" type="text" class="form-control" id="gueltig_ab" value="{{$smarty_vars.values.gueltig_ab|default:""}}" placeholder="TT.MM.JJJJ">
        </div>
    </div>
    <div class="form-group col-12 col-sm-6">
        <label class="form-control-label" for="gueltig_ab">Gültig bis</label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-calendar fa-fw"></i></span>
            <input name="gueltig_bis" type="text" class="form-control" id="gueltig_bis" value="{{$smarty_vars.values.gueltig_bis|default:""}}" placeholder="TT.MM.JJJJ">
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-12{{if $smarty_vars.company == 'tps'}}{{if $smarty_vars.values.palettenabteilung}} col-sm-6{{/if}}{{/if}}"{{if $smarty_vars.company == 'tps'}} id="abteilung-container"{{/if}}>
        <label class="form-control-label" for="abteilung">Abteilung <span class="required">*</span></label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-tasks fa-fw"></i></span>
            <select name="abteilung" class="form-control" id="abteilung">
                {{if isset($smarty_vars.abteilungsliste)}}
                    {{foreach from=$smarty_vars.abteilungsliste item='row'}}
                        <option value="{{$row.id}}"{{if isset($smarty_vars.values.abteilung)}}{{if $smarty_vars.values.abteilung == $row.id}} selected{{/if}}{{/if}}{{if $smarty_vars.company == 'tps'}} data-palettenabteilung="{{$row.palettenabteilung}}"{{/if}}>{{$row.bezeichnung}}</option>
                    {{/foreach}}
                {{/if}}
            </select>
        </div>
    </div>
    {{if $smarty_vars.company == 'tps'}}
        <div class="form-group col-12 col-sm-6" id="zeit_pro_palette-container"{{if !$smarty_vars.values.palettenabteilung}} style="display: none;"{{/if}}>
            <label class="form-control-label" for="zeit_pro_palette">Zeit pro Palette (h) <span class="required">*</span></label>
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-clock-o fa-fw"></i></span>
                <input name="zeit_pro_palette" type="text" data-inputmask="'mask' : '99:99'" class="form-control inputmask" id="zeit_pro_palette" value="{{$smarty_vars.values.zeit_pro_palette|default:""}}" placeholder="01:30">
            </div>
        </div>
    {{/if}}
</div>
<div class="row">
    <div class="form-group col-12 col-sm-6">
        <label class="form-control-label" for="preis">Preis <span class="required">*</span></label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-eur fa-fw"></i></span>
            <input name="preis" type="text" class="form-control" id="preis" value="{{$smarty_vars.values.preis|default:""}}" placeholder="z.B. 17.90">
        </div>
    </div>
    <div class="form-group col-12 col-sm-6">
        <label class="form-control-label" for="sonntagszuschlag">Sonntagszuschlag (%) <span class="required">*</span></label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-percent fa-fw"></i></span>
            <input name="sonntagszuschlag" type="text" class="form-control" id="sonntagszuschlag" value="{{$smarty_vars.values.sonntagszuschlag|default:""}}" placeholder="z.B. 50">
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-12 col-sm-6">
        <label class="form-control-label" for="feiertagszuschlag">Feiertagszuschlag (%) <span class="required">*</span></label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-percent fa-fw"></i></span>
            <input name="feiertagszuschlag" type="text" class="form-control" id="feiertagszuschlag" value="{{$smarty_vars.values.feiertagszuschlag|default:""}}" placeholder="z.B. 100">
        </div>
    </div>
    <div class="form-group col-12 col-sm-6">
        <label class="form-control-label" for="nachtzuschlag">Nachtzuschlag (%) <span class="required">*</span></label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-percent fa-fw"></i></span>
            <input name="nachtzuschlag" type="text" class="form-control" id="nachtzuschlag" value="{{$smarty_vars.values.nachtzuschlag|default:""}}" placeholder="z.B. 25">
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-12 col-sm-6">
        <label class="form-control-label" for="nacht_von">Nacht von <span class="required">*</span></label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-clock-o fa-fw"></i></span>
            <input name="nacht_von" type="text" data-inputmask="'mask' : '99:99'" class="form-control inputmask" id="nacht_von" value="{{$smarty_vars.values.nacht_von|default:""}}" placeholder="23:00">
        </div>
    </div>
    <div class="form-group col-12 col-sm-6">
        <label class="form-control-label" for="nacht_bis">Nacht bis <span class="required">*</span></label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-clock-o fa-fw"></i></span>
            <input name="nacht_bis" type="text" data-inputmask="'mask' : '99:99'" class="form-control inputmask" id="nacht_bis" value="{{$smarty_vars.values.nacht_bis|default:""}}" placeholder="06:00">
        </div>
    </div>
</div>
<button type="submit" class="btn btn-secondary form-control">Speichern</button>

{{capture name='scripts'}}
    {{if $smarty_vars.company == 'tps'}}
        <!-- Custom JS -->
        <script>
            $(document).ready(function () {
                var abteilung = $('#abteilung');

                abteilung.change(function () {
                    if (abteilung.find(':selected').data('palettenabteilung') == 'ja') {
                        if (!$('#abteilung-container').hasClass('col-sm-6')) {
                            $('#abteilung-container').addClass('col-sm-6');
                        }
                        $('#zeit_pro_palette-container').show();
                    } else {
                        if ($('#abteilung-container').hasClass('col-sm-6')) {
                            $('#abteilung-container').removeClass('col-sm-6');
                        }
                        $('#zeit_pro_palette-container').hide();
                    }
                });
            });
        </script>
        <!-- /Custom JS-->
    {{/if}}

    <!-- jquery.inputmask -->
    <script src="/assets/vendors/jquery.inputmask/dist/min/jquery.inputmask.bundle.min.js"></script>
    <script>
        $(document).ready(function () {
            $(".inputmask").inputmask();
        });
    </script>
    <!-- /jquery.inputmask-->
{{/capture}}

{{$js=$smarty.capture.scripts scope=parent}}