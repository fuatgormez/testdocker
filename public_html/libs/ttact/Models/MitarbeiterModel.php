<?php

namespace ttact\Models;

class MitarbeiterModel extends Model
{
    /**
     * properties
     */
    protected $mitarbeiter_id;
    protected $personalnummer;
    protected $geschlecht;
    protected $vorname;
    protected $nachname;
    protected $telefon1;
    protected $telefon2;
    protected $emailadresse;
    protected $strasse;
    protected $hausnummer;
    protected $adresszusatz;
    protected $postleitzahl;
    protected $ort;
    protected $geburtsdatum;
    protected $iban;
    protected $bic;
    protected $abweichender_kontoinhaber;
    protected $eintritt;
    protected $austritt;
    protected $befristung;
    protected $befristung1;
    protected $befristung2;
    protected $befristung3;
    protected $jahresurlaub;
    protected $resturlaub_vorjahr;
    protected $sozialversicherungsnummer;
    protected $montag_von;
    protected $montag_bis;
    protected $dienstag_von;
    protected $dienstag_bis;
    protected $mittwoch_von;
    protected $mittwoch_bis;
    protected $donnerstag_von;
    protected $donnerstag_bis;
    protected $freitag_von;
    protected $freitag_bis;
    protected $samstag_von;
    protected $samstag_bis;
    protected $sonntag_von;
    protected $sonntag_bis;
    protected $notizen_allgemein;
    protected $notizen_januar;
    protected $notizen_februar;
    protected $notizen_maerz;
    protected $notizen_april;
    protected $notizen_mai;
    protected $notizen_juni;
    protected $notizen_juli;
    protected $notizen_august;
    protected $notizen_september;
    protected $notizen_oktober;
    protected $notizen_november;
    protected $notizen_dezember;

    /**
     * logic methods
     */
    public static function findByID(\ttact\Database $db, $id)
    {
        $id = (int) $id;
        $model_data = $db->getFirstRow('mitarbeiter', ['mitarbeiter_id' => $id]);
        if (isset($model_data['mitarbeiter_id'])) {
            return new self($db, $model_data);
        }
        return null;
    }


    public static function findByPersonalnummer(\ttact\Database $db, $personalnummer)
    {
        $personalnummer = (int) $personalnummer;
        $model_data = $db->getFirstRow('mitarbeiter', ['personalnummer' => $personalnummer]);
        if (isset($model_data['mitarbeiter_id'])) {
            return new self($db, $model_data);
        }
        return null;
    }

    public static function findLastByPersonalnummer(\ttact\Database $db)
    {
        $model_data = $db->getFirstRow('mitarbeiter', [], [], ['personalnummer'], 'DESC');
        if (isset($model_data['mitarbeiter_id'])) {
            return new self($db, $model_data);
        }
        return null;
    }

    public static function findAll(\ttact\Database $db)
    {
        $return = [];

        $all = $db->getRows('mitarbeiter');
        foreach ($all as $model_data) {
            if (isset($model_data['mitarbeiter_id'])) {
                $return[] = new self($db, $model_data);
            }
        }

        return $return;
    }

    public static function findAllLohnberechnungEintrittBefore(\ttact\Database $db, \DateTimeInterface $monatsanfang, \DateTimeInterface $eintritt_before)
    {
        $return = [];

        $x = $monatsanfang->format("Y-m-d");
        $y = $eintritt_before->format("Y-m-d");

        $all = $db->getRowsQuery("SELECT * FROM mitarbeiter WHERE eintritt != '0000-00-00' AND (austritt = '0000-00-00' OR austritt >= '$x') AND eintritt <= '$y'");
        foreach ($all as $model_data) {
            if (isset($model_data['mitarbeiter_id'])) {
                $return[] = new self($db, $model_data);
            }
        }

        return $return;
    }

    public static function findAllLohnberechnung(\ttact\Database $db, \DateTimeInterface $monatsanfang)
    {
        $return = [];

        $x = $monatsanfang->format("Y-m-d");

        $all = $db->getRowsQuery("SELECT * FROM mitarbeiter WHERE eintritt != '0000-00-00' AND (austritt = '0000-00-00' OR austritt >= '$x')");
        foreach ($all as $model_data) {
            if (isset($model_data['mitarbeiter_id'])) {
                $return[] = new self($db, $model_data);
            }
        }

        return $return;
    }

    public static function findPrevLohnberechnung(\ttact\Database $db, $personalnummer, \DateTimeInterface $monatsanfang)
    {
        $personalnummer = (int) $personalnummer;

        $x = $monatsanfang->format("Y-m-d");

        $model_data = $db->getRowsQuery("SELECT * FROM mitarbeiter WHERE personalnummer < '$personalnummer' AND eintritt != '0000-00-00' AND (austritt = '0000-00-00' OR austritt >= '$x') ORDER BY personalnummer DESC LIMIT 1");
        if (isset($model_data[0])) {
            if (isset($model_data[0]['mitarbeiter_id'])) {
                return new self($db, $model_data[0]);
            }
        }
        return null;
    }

    public static function findNextLohnberechnung(\ttact\Database $db, $personalnummer, \DateTimeInterface $monatsanfang)
    {
        $personalnummer = (int) $personalnummer;

        $x = $monatsanfang->format("Y-m-d");

        $model_data = $db->getRowsQuery("SELECT * FROM mitarbeiter WHERE personalnummer > '$personalnummer' AND eintritt != '0000-00-00' AND (austritt = '0000-00-00' OR austritt >= '$x') ORDER BY personalnummer LIMIT 1");
        if (isset($model_data[0])) {
            if (isset($model_data[0]['mitarbeiter_id'])) {
                return new self($db, $model_data[0]);
            }
        }
        return null;
    }

    public static function findActives(\ttact\Database $db, $monatsanfang = '')
    {
        $return = [];

        if ($monatsanfang == '') {
            $monatsanfang = new \DateTime('now');
        }

        $all = $db->getRowsQuery("SELECT * FROM mitarbeiter WHERE austritt = '0000-00-00' OR austritt >= '" . $monatsanfang->format("Y-m-d") . "'");
        foreach ($all as $model_data) {
            if (isset($model_data['mitarbeiter_id'])) {
                $return[] = new self($db, $model_data);
            }
        }

        return $return;
    }

    public static function findActivesSchichtplaner(\ttact\Database $db, \DateTime $montag, int $abteilung)
    {
        $return = [];

        $all = $db->getRowsQuery("SELECT * FROM mitarbeiter, abteilungsfreigabe WHERE (austritt = '0000-00-00' OR austritt >= '".$montag->format("Y-m-d")."') AND mitarbeiter.mitarbeiter_id = abteilungsfreigabe.mitarbeiter_id AND abteilung_id = '".$abteilung."'");
        foreach ($all as $model_data) {
            if (isset($model_data['mitarbeiter_id'])) {
                $return[] = new self($db, $model_data);
            }
        }

        return $return;
    }

    public static function findInactives(\ttact\Database $db)
    {
        $return = [];

        $now = new \DateTime("now");
        $all = $db->getRowsQuery("SELECT * FROM mitarbeiter WHERE austritt < '".$now->format("Y-m-d")."'");
        foreach ($all as $model_data) {
            if (isset($model_data['mitarbeiter_id'])) {
                $return[] = new self($db, $model_data);
            }
        }

        return $return;
    }

    public static function createNew(\ttact\Database $db, array $data)
    {
        // the data that will be inserted into the mysql table
        $insert_data = [];

        // copy all data from parameter $data into $insert_data if the respective field really exists
        foreach ($db->getFieldNames('mitarbeiter') as $field_name) {
            if (isset($data[$field_name]) && $field_name != 'mitarbeiter_id') {
                $insert_data[$field_name] = $data[$field_name];
            }
        }

        // insert $insert_data into the mysql table
        $insert_id = $db->insert('mitarbeiter', $insert_data);
        if ($insert_id > 0) {
            return self::findByID($db, $insert_id);
        }

        return null;
    }

    private function setAttribute(string $attribute, string $value)
    {
        if($this->db->update('mitarbeiter', $this->getID(), [$attribute => $value])) {
            $this->{$attribute} = $value;
            return true;
        }

        return false;
    }

    private function getDateTimeObjectForDates(string $attribute, string $zeit = '00:00:00')
    {
        if ($this->$attribute != '0000-00-00') {
            return new \DateTime($this->$attribute. " " . $zeit);
        }
        return null;
    }

    private function getDateTimeObjectForVonTime(string $attribute)
    {
        $von = $attribute . "_von";

        if ($this->$von == '00:00:00') {
            $bis = $attribute . "_bis";
            if ($this->$bis != '00:00:00') {
                return new \DateTime("0000-00-00 " . $this->$von);
            }
        } else {
            return new \DateTime("0000-00-00 " . $this->$von);
        }
        return null;
    }

    private function getDateTimeObjectForBisTime(string $attribute)
    {
        $bis = $attribute . "_bis";

        if ($this->$bis == '00:00:00') {
            $von = $attribute . "_von";
            if ($this->$von != '00:00:00') {
                return new \DateTime("0000-00-00 " . $this->$bis);
            }
        } else {
            return new \DateTime("0000-00-00 " . $this->$bis);
        }
        return null;
    }

    public function delete()
    {
        return $this->db->delete('mitarbeiter', $this->getID());
    }

    /**
     * getter methods
     */
    public function getID()
    {
        return $this->mitarbeiter_id;
    }

    public function getPersonalnummer()
    {
        return $this->personalnummer;
    }

    public function getGeschlecht()
    {
        return $this->geschlecht;
    }

    public function getVorname()
    {
        return $this->vorname;
    }

    public function getNachname()
    {
        return $this->nachname;
    }

    public function getTelefon1()
    {
        return $this->telefon1;
    }

    public function getTelefon2()
    {
        return $this->telefon2;
    }

    public function getEmailadresse()
    {
        return $this->emailadresse;
    }

    public function getStrasse()
    {
        return $this->strasse;
    }

    public function getHausnummer()
    {
        return $this->hausnummer;
    }

    public function getAdresszusatz()
    {
        return $this->adresszusatz;
    }

    public function getPostleitzahl()
    {
        return $this->postleitzahl;
    }

    public function getOrt()
    {
        return $this->ort;
    }

    public function getGeburtsdatum()
    {
        return $this->getDateTimeObjectForDates('geburtsdatum');
    }

    public function getIBAN()
    {
        return $this->iban;
    }

    public function getBIC()
    {
        return $this->bic;
    }

    public function getAbweichenderKontoinhaber()
    {
        return $this->abweichender_kontoinhaber;
    }

    public function getEintritt()
    {
        return $this->getDateTimeObjectForDates('eintritt');
    }

    public function getAustritt()
    {
        return $this->getDateTimeObjectForDates('austritt', '23:59:59');
    }

    public function getBefristung()
    {
        return $this->getDateTimeObjectForDates('befristung');
    }

    public function getBefristung1()
    {
        return $this->getDateTimeObjectForDates('befristung1');
    }

    public function getBefristung2()
    {
        return $this->getDateTimeObjectForDates('befristung2');
    }

    public function getBefristung3()
    {
        return $this->getDateTimeObjectForDates('befristung3');
    }

    public function getJahresurlaub()
    {
        return $this->jahresurlaub;
    }

    public function getResturlaubVorjahr()
    {
        return $this->resturlaub_vorjahr;
    }

    public function getSozialversicherungsnummer()
    {
        return $this->sozialversicherungsnummer;
    }

    public function getMontagVon()
    {
        return $this->getDateTimeObjectForVonTime('montag');
    }

    public function getMontagBis()
    {
        return $this->getDateTimeObjectForBisTime('montag');
    }

    public function getDienstagVon()
    {
        return $this->getDateTimeObjectForVonTime('dienstag');
    }

    public function getDienstagBis()
    {
        return $this->getDateTimeObjectForBisTime('dienstag');
    }

    public function getMittwochVon()
    {
        return $this->getDateTimeObjectForVonTime('mittwoch');
    }

    public function getMittwochBis()
    {
        return $this->getDateTimeObjectForBisTime('mittwoch');
    }

    public function getDonnerstagVon()
    {
        return $this->getDateTimeObjectForVonTime('donnerstag');
    }

    public function getDonnerstagBis()
    {
        return $this->getDateTimeObjectForBisTime('donnerstag');
    }

    public function getFreitagVon()
    {
        return $this->getDateTimeObjectForVonTime('freitag');
    }

    public function getFreitagBis()
    {
        return $this->getDateTimeObjectForBisTime('freitag');
    }

    public function getSamstagVon()
    {
        return $this->getDateTimeObjectForVonTime('samstag');
    }

    public function getSamstagBis()
    {
        return $this->getDateTimeObjectForBisTime('samstag');
    }

    public function getSonntagVon()
    {
        return $this->getDateTimeObjectForVonTime('sonntag');
    }

    public function getSonntagBis()
    {
        return $this->getDateTimeObjectForBisTime('sonntag');
    }

    public function getNotizenAllgemein()
    {
        return $this->notizen_allgemein;
    }

    public function getNotizenJanuar()
    {
        return $this->notizen_januar;
    }

    public function getNotizenFebruar()
    {
        return $this->notizen_februar;
    }

    public function getNotizenMaerz()
    {
        return $this->notizen_maerz;
    }

    public function getNotizenApril()
    {
        return $this->notizen_april;
    }

    public function getNotizenMai()
    {
        return $this->notizen_mai;
    }

    public function getNotizenJuni()
    {
        return $this->notizen_juni;
    }

    public function getNotizenJuli()
    {
        return $this->notizen_juli;
    }

    public function getNotizenAugust()
    {
        return $this->notizen_august;
    }

    public function getNotizenSeptember()
    {
        return $this->notizen_september;
    }

    public function getNotizenOktober()
    {
        return $this->notizen_oktober;
    }

    public function getNotizenNovember()
    {
        return $this->notizen_november;
    }

    public function getNotizenDezember()
    {
        return $this->notizen_dezember;
    }

    /**
     * setter methods
     */

    public function setPersonalnummer(string $value)
    {
        return $this->setAttribute('personalnummer', $value);
    }

    public function setGeschlecht(string $value)
    {
        return $this->setAttribute('geschlecht', $value);
    }

    public function setVorname(string $value)
    {
        return $this->setAttribute('vorname', $value);
    }

    public function setNachname(string $value)
    {
        return $this->setAttribute('nachname', $value);
    }

    public function setTelefon1(string $value)
    {
        return $this->setAttribute('telefon1', $value);
    }

    public function setTelefon2(string $value)
    {
        return $this->setAttribute('telefon2', $value);
    }

    public function setEmailadresse(string $value)
    {
        return $this->setAttribute('emailadresse', $value);
    }

    public function setStrasse(string $value)
    {
        return $this->setAttribute('strasse', $value);
    }

    public function setHausnummer(string $value)
    {
        return $this->setAttribute('hausnummer', $value);
    }

    public function setAdresszusatz(string $value)
    {
        return $this->setAttribute('adresszusatz', $value);
    }

    public function setPostleitzahl(string $value)
    {
        return $this->setAttribute('postleitzahl', $value);
    }

    public function setOrt(string $value)
    {
        return $this->setAttribute('ort', $value);
    }

    public function setGeburtsdatum(string $value)
    {
        return $this->setAttribute('geburtsdatum', $value);
    }

    public function setIBAN(string $value)
    {
        return $this->setAttribute('iban', $value);
    }

    public function setBIC(string $value)
    {
        return $this->setAttribute('bic', $value);
    }

    public function setAbweichenderKontoinhaber(string $value)
    {
        return $this->setAttribute('abweichender_kontoinhaber', $value);
    }

    public function setEintritt(string $value)
    {
        return $this->setAttribute('eintritt', $value);
    }

    public function setAustritt(string $value)
    {
        return $this->setAttribute('austritt', $value);
    }

    public function setBefristung(string $value)
    {
        return $this->setAttribute('befristung', $value);
    }

    public function setBefristung1(string $value)
    {
        return $this->setAttribute('befristung1', $value);
    }

    public function setBefristung2(string $value)
    {
        return $this->setAttribute('befristung2', $value);
    }

    public function setBefristung3(string $value)
    {
        return $this->setAttribute('befristung3', $value);
    }

    public function setJahresurlaub(string $value)
    {
        return $this->setAttribute('jahresurlaub', $value);
    }

    public function setResturlaubVorjahr(string $value)
    {
        return $this->setAttribute('resturlaub_vorjahr', $value);
    }

    public function setSozialversicherungsnummer(string $value)
    {
        return $this->setAttribute('sozialversicherungsnummer', $value);
    }

    public function setMontagVon(string $value)
    {
        return $this->setAttribute('montag_von', $value);
    }

    public function setMontagBis(string $value)
    {
        return $this->setAttribute('montag_bis', $value);
    }

    public function setDienstagVon(string $value)
    {
        return $this->setAttribute('dienstag_von', $value);
    }

    public function setDienstagBis(string $value)
    {
        return $this->setAttribute('dienstag_bis', $value);
    }

    public function setMittwochVon(string $value)
    {
        return $this->setAttribute('mittwoch_von', $value);
    }

    public function setMittwochBis(string $value)
    {
        return $this->setAttribute('mittwoch_bis', $value);
    }

    public function setDonnerstagVon(string $value)
    {
        return $this->setAttribute('donnerstag_von', $value);
    }

    public function setDonnerstagBis(string $value)
    {
        return $this->setAttribute('donnerstag_bis', $value);
    }

    public function setFreitagVon(string $value)
    {
        return $this->setAttribute('freitag_von', $value);
    }

    public function setFreitagBis(string $value)
    {
        return $this->setAttribute('freitag_bis', $value);
    }

    public function setSamstagVon(string $value)
    {
        return $this->setAttribute('samstag_von', $value);
    }

    public function setSamstagBis(string $value)
    {
        return $this->setAttribute('samstag_bis', $value);
    }

    public function setSonntagVon(string $value)
    {
        return $this->setAttribute('sonntag_von', $value);
    }

    public function setSonntagBis(string $value)
    {
        return $this->setAttribute('sonntag_bis', $value);
    }

    public function setNotizenAllgemein(string $value)
    {
        return $this->setAttribute('notizen_allgemein', $value);
    }

    public function setNotizenJanuar(string $value)
    {
        return $this->setAttribute('notizen_januar', $value);
    }

    public function setNotizenFebruar(string $value)
    {
        return $this->setAttribute('notizen_februar', $value);
    }

    public function setNotizenMaerz(string $value)
    {
        return $this->setAttribute('notizen_maerz', $value);
    }

    public function setNotizenApril(string $value)
    {
        return $this->setAttribute('notizen_april', $value);
    }

    public function setNotizenMai(string $value)
    {
        return $this->setAttribute('notizen_mai', $value);
    }

    public function setNotizenJuni(string $value)
    {
        return $this->setAttribute('notizen_juni', $value);
    }

    public function setNotizenJuli(string $value)
    {
        return $this->setAttribute('notizen_juli', $value);
    }

    public function setNotizenAugust(string $value)
    {
        return $this->setAttribute('notizen_august', $value);
    }

    public function setNotizenSeptember(string $value)
    {
        return $this->setAttribute('notizen_september', $value);
    }

    public function setNotizenOktober(string $value)
    {
        return $this->setAttribute('notizen_oktober', $value);
    }

    public function setNotizenNovember(string $value)
    {
        return $this->setAttribute('notizen_november', $value);
    }

    public function setNotizenDezember(string $value)
    {
        return $this->setAttribute('notizen_dezember', $value);
    }
}
