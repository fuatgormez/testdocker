<?php

namespace ttact\Models;

class KundeModel extends Model
{
    /**
     * properties
     */
    protected $kunde_id;
    protected $kundennummer;
    protected $name;
    protected $strasse;
    protected $postleitzahl;
    protected $ort;
    protected $ansprechpartner;
    protected $telefon1;
    protected $telefon2;
    protected $fax;
    protected $emailadresse;
    protected $rechnungsanschrift;
    protected $rechnungszusatz;
    protected $unterzeichnungsdatum_rahmenvertrag;

    /**
     * logic methods
     */
    public static function findByID(\ttact\Database $db, $id)
    {
        $id = (int) $id;
        $model_data = $db->getFirstRow('kunde', ['kunde_id' => $id]);
        if (isset($model_data['kunde_id'])) {
            return new self($db, $model_data);
        }
        return null;
    }

    public static function findByKundennummer(\ttact\Database $db, $kundennummer)
    {
        $kundennummer = (int) $kundennummer;
        $model_data = $db->getFirstRow('kunde', ['kundennummer' => $kundennummer]);
        if (isset($model_data['kunde_id'])) {
            return new self($db, $model_data);
        }
        return null;
    }

    public static function findLastByKundennummer(\ttact\Database $db)
    {
        $model_data = $db->getFirstRow('kunde', [], [], ['kundennummer'], 'DESC');
        if (isset($model_data['kunde_id'])) {
            return new self($db, $model_data);
        }
        return null;
    }

    public static function findAll(\ttact\Database $db)
    {
        $return = [];

        $all = $db->getRows('kunde', [], [], ['kundennummer']);
        foreach ($all as $model_data) {
            if (isset($model_data['kunde_id'])) {
                $return[] = new self($db, $model_data);
            }
        }

        return $return;
    }

    public static function findPrev(\ttact\Database $db, $kundennummer)
    {
        $kundennummer = (int) $kundennummer;

        $model_data = $db->getRowsQuery("SELECT * FROM kunde WHERE kundennummer < '$kundennummer' ORDER BY kundennummer DESC LIMIT 1");
        if (isset($model_data[0])) {
            if (isset($model_data[0]['kunde_id'])) {
                return new self($db, $model_data[0]);
            }
        }
        return null;
    }

    public static function findNext(\ttact\Database $db, $kundennummer)
    {
        $kundennummer = (int) $kundennummer;

        $model_data = $db->getRowsQuery("SELECT * FROM kunde WHERE kundennummer > '$kundennummer' ORDER BY kundennummer LIMIT 1");
        if (isset($model_data[0])) {
            if (isset($model_data[0]['kunde_id'])) {
                return new self($db, $model_data[0]);
            }
        }
        return null;
    }

    public static function createNew(\ttact\Database $db, array $data)
    {
        // the data that will be inserted into the mysql table
        $insert_data = [];

        // copy all data from parameter $data into $insert_data if the respective field really exists
        foreach ($db->getFieldNames('kunde') as $field_name) {
            if (isset($data[$field_name]) && $field_name != 'kunde_id') {
                $insert_data[$field_name] = $data[$field_name];
            }
        }

        // insert $insert_data into the mysql table
        $insert_id = $db->insert('kunde', $insert_data);
        if ($insert_id > 0) {
            return self::findByID($db, $insert_id);
        }

        return null;
    }

    private function setAttribute(string $attribute, string $value)
    {
        if($this->db->update('kunde', $this->getID(), [$attribute => $value])) {
            $this->{$attribute} = $value;
            return true;
        }

        return false;
    }

    /**
     * getter methods
     */
    public function getID()
    {
        return $this->kunde_id;
    }

    public function getKundennummer()
    {
        return $this->kundennummer;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getStrasse()
    {
        return $this->strasse;
    }

    public function getPostleitzahl()
    {
        return $this->postleitzahl;
    }

    public function getOrt()
    {
        return $this->ort;
    }

    public function getAnsprechpartner()
    {
        return $this->ansprechpartner;
    }

    public function getTelefon1()
    {
        return $this->telefon1;
    }

    public function getTelefon2()
    {
        return $this->telefon2;
    }

    public function getFax()
    {
        return $this->fax;
    }

    public function getEmailadresse()
    {
        return $this->emailadresse;
    }

    public function getRechnungsanschrift()
    {
        return $this->rechnungsanschrift;
    }

    public function getRechnungszusatz()
    {
        return $this->rechnungszusatz;
    }

    public function getUnterzeichnungsdatumRahmenvertrag()
    {
        if ($this->unterzeichnungsdatum_rahmenvertrag != '0000-00-00') {
            return new \DateTime($this->unterzeichnungsdatum_rahmenvertrag. " 00:00:00");
        }
        return null;
    }

    /**
     * setter methods
     */

    public function setKundennummer(string $value)
    {
        return $this->setAttribute('kundennummer', $value);
    }

    public function setName(string $value)
    {
        return $this->setAttribute('name', $value);
    }

    public function setStrasse(string $value)
    {
        return $this->setAttribute('strasse', $value);
    }

    public function setPostleitzahl(string $value)
    {
        return $this->setAttribute('postleitzahl', $value);
    }

    public function setOrt(string $value)
    {
        return $this->setAttribute('ort', $value);
    }

    public function setAnsprechpartner(string $value)
    {
        return $this->setAttribute('ansprechpartner', $value);
    }

    public function setTelefon1(string $value)
    {
        return $this->setAttribute('telefon1', $value);
    }

    public function setTelefon2(string $value)
    {
        return $this->setAttribute('telefon2', $value);
    }

    public function setFax(string $value)
    {
        return $this->setAttribute('fax', $value);
    }

    public function setEmailadresse(string $value)
    {
        return $this->setAttribute('emailadresse', $value);
    }

    public function setRechnungsanschrift(string $value)
    {
        return $this->setAttribute('rechnungsanschrift', $value);
    }

    public function setRechnungszusatz(string $value)
    {
        return $this->setAttribute('rechnungszusatz', $value);
    }

    public function setUnterzeichnungsdatumRahmenvertrag(string $value)
    {
        return $this->setAttribute('unterzeichnungsdatum_rahmenvertrag', $value);
    }
}
