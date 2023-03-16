<div class="row">
    <div class="form-group col-12 col-sm-6">
        <label class="form-control-label" for="kundennummer">Kundennummer <span class="required">*</span></label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-link fa-fw"></i></span>
            <input type="number" min="1" class="form-control" id="kundennummer" name="kundennummer" placeholder="Kundennummer" value="{{$smarty_vars.values.kundennummer|default:""}}" required>
        </div>
    </div>
    <div class="form-group col-12 col-sm-6">
        <label class="form-control-label" for="name">Name <span class="required">*</span></label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-institution fa-fw"></i></span>
            <input type="text" class="form-control" id="name" name="name" placeholder="Musterwaren GmbH" value="{{$smarty_vars.values.name|default:""}}" required>
        </div>
    </div>
</div>


<div class="form-group">
    <label class="form-control-label" for="strasse">Straße und Hausnummer</label>
    <div class="input-group">
        <span class="input-group-addon"><i class="fa fa-map-marker fa-fw"></i></span>
        <input type="text" class="form-control" id="strasse" name="strasse" placeholder="Beispielstraße 12" value="{{$smarty_vars.values.strasse|default:""}}">
    </div>
</div>

<div class="row">
    <div class="form-group col-12 col-sm-6">
        <label class="form-control-label" for="postleitzahl">Postleitzahl</label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-map-marker fa-fw"></i></span>
            <input type="number" min="10000" max="99999" class="form-control" id="postleitzahl" name="postleitzahl" placeholder="12345" value="{{$smarty_vars.values.postleitzahl|default:""}}">
        </div>
    </div>
    <div class="form-group col-12 col-sm-6">
        <label class="form-control-label" for="ort">Ort</label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-map-marker fa-fw"></i></span>
            <input type="text" class="form-control" id="ort" name="ort" placeholder="Musterstadt" value="{{$smarty_vars.values.ort|default:""}}">
        </div>
    </div>
</div>

<div class="form-group">
    <label class="form-control-label" for="ansprechpartner">Ansprechpartner</label>
    <div class="input-group">
        <span class="input-group-addon"><i class="fa fa-user fa-fw"></i></span>
        <input type="text" class="form-control" id="ansprechpartner" name="ansprechpartner" placeholder="Max Mustermann" value="{{$smarty_vars.values.ansprechpartner|default:""}}">
    </div>
</div>
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
    <label class="form-control-label" for="fax">Fax</label>
    <div class="input-group">
        <span class="input-group-addon"><i class="fa fa-fax fa-fw"></i></span>
        <input type="text" class="form-control" id="fax" name="fax" placeholder="030 123456 99" value="{{$smarty_vars.values.fax|default:""}}">
    </div>
</div>
<div class="form-group">
    <label class="form-control-label" for="emailadresse">E-Mail-Adresse</label>
    <div class="input-group">
        <span class="input-group-addon"><i class="fa fa-envelope fa-fw"></i></span>
        <input type="email" class="form-control" id="emailadresse" name="emailadresse" placeholder="beispiel@domain.de" value="{{$smarty_vars.values.emailadresse|default:""}}">
    </div>
</div>
<div class="form-group">
    <label class="form-control-label" for="rechnungsanschrift">Rechnungsanschrift</label>
    <div class="input-group">
        <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
        <textarea class="form-control resizable" id="rechnungsanschrift" name="rechnungsanschrift" placeholder="Mit Zeilenumbrüchen, wie bei einem Briefkopf.">{{$smarty_vars.values.rechnungsanschrift|default:""}}</textarea>
    </div>
</div>
{{if $smarty_vars.company == 'tps'}}
    <div class="form-group">
        <label class="form-control-label" for="rechnungszusatz">Rechnungszusatz</label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
            <input type="text" class="form-control" id="rechnungszusatz" name="rechnungszusatz" placeholder="Kostenstelle: XYZ" value="{{$smarty_vars.values.rechnungszusatz|default:""}}">
        </div>
    </div>
{{else}}
    <div class="row">
        <div class="form-group col-12 col-sm-6">
            <label class="form-control-label" for="rechnungszusatz">Rechnungszusatz</label>
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                <input type="text" class="form-control" id="rechnungszusatz" name="rechnungszusatz" placeholder="Kostenstelle: XYZ" value="{{$smarty_vars.values.rechnungszusatz|default:""}}">
            </div>
        </div>
        <div class="form-group col-12 col-sm-6">
            <label class="form-control-label" for="unterzeichnungsdatum_rahmenvertrag">Rahmenvertrag vom</label>
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-calendar fa-fw"></i></span>
                <input name="unterzeichnungsdatum_rahmenvertrag" type="text" class="form-control" id="unterzeichnungsdatum_rahmenvertrag" value="{{$smarty_vars.values.unterzeichnungsdatum_rahmenvertrag|default:""}}" placeholder="TT.MM.JJJJ">
            </div>
        </div>
    </div>
{{/if}}
<button type="submit" class="btn btn-secondary form-control" name="submitted" value="true">Speichern</button>

{{$js = '
    <!-- Autosize -->
    <script src="/assets/vendors/autosize/dist/autosize.min.js"></script>

    <!-- Autosize -->
    <script>
        $(document).ready(function() {
            autosize($(".resizable"));
        });
    </script>
' scope = parent}}