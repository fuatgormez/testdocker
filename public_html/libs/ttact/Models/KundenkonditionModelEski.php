<?php

namespace ttact\Models;

class KundenkonditionModel extends Model
{
    /**
     * properties
     */
    protected $kundenkondition_id;
    protected $gueltig_ab;
    protected $gueltig_bis;
    protected $kunde_id;
    protected $abteilung_id;
    protected $preis;
    protected $sonntagszuschlag;
    protected $feiertagszuschlag;
    protected $nachtzuschlag;
    protected $nacht_von;
    protected $nacht_bis;

    /**
     * logic methods
     */
    public static function findByID(\ttact\Database $db, $id)
    {
        $id = (int) $id;
        $model_data = $db->getFirstRow('kundenkondition', ['kundenkondition_id' => $id]);
        if (isset($model_data['kundenkondition_id'])) {
            return new self($db, $model_data);
        }
        return null;
    }

    public static function findAll(\ttact\Database $db)
    {
        $return = [];

        $all = $db->getRows('kundenkondition');
        foreach ($all as $model_data) {
            if (isset($model_data['kundenkondition_id'])) {
                $return[] = new self($db, $model_data);
            }
        }

        return $return;
    }

    public static function findAllByKunde(\ttact\Database $db, int $kunde_id)
    {
        $return = [];

        $all = $db->getRows('kundenkondition', [], ['kunde_id' => $kunde_id]);
        foreach ($all as $model_data) {
            if (isset($model_data['kundenkondition_id'])) {
                $return[] = new self($db, $model_data);
            }
        }

        return $return;
    }

    public static function findAllByKundeForDate(\ttact\Database $db, int $kunde_id, \DateTime $date)
    {
        $return = [];

        $all = $db->getRowsQuery("SELECT * FROM kundenkondition WHERE kunde_id = '".$kunde_id."' AND (gueltig_ab = '0000-00-00' OR gueltig_ab <= '".$date->format("Y-m-d")."') AND (gueltig_bis = '0000-00-00' OR gueltig_bis >= '".$date->format("Y-m-d")."') ORDER BY gueltig_ab DESC");
        foreach ($all as $model_data) {
            if (isset($model_data['kundenkondition_id'])) {
                $return[] = new self($db, $model_data);
            }
        }

        return $return;
    }

    public static function findForKundeAbteilungDate(\ttact\Database $db, int $kunde, int $abteilung, \DateTime $date)
    {
        $model_data = $db->getRowsQuery("SELECT * FROM kundenkondition WHERE kunde_id = '".$kunde."' AND abteilung_id = '".$abteilung."' AND (gueltig_ab = '0000-00-00' OR gueltig_ab <= '".$date->format("Y-m-d")."') AND (gueltig_bis = '0000-00-00' OR gueltig_bis >= '".$date->format("Y-m-d")."') ORDER BY gueltig_ab DESC LIMIT 1");
        if (isset($model_data[0])) {
            if (isset($model_data[0]['kundenkondition_id'])) {
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
        foreach ($db->getFieldNames('kundenkondition') as $field_name) {
            if (isset($data[$field_name]) && $field_name != 'kundenkondition_id') {
                $insert_data[$field_name] = $data[$field_name];
            }
        }

        // insert $insert_data into the mysql table
        $insert_id = $db->insert('kundenkondition', $insert_data);
        if ($insert_id > 0) {
            return self::findByID($db, $insert_id);
        }

        return null;
    }

    private function setAttribute(string $attribute, string $value)
    {
        if($this->db->update('kundenkondition', $this->getID(), [$attribute => $value])) {
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
        return $this->kundenkondition_id;
    }

    public function getGueltigAb()
    {
        if ($this->gueltig_ab != '0000-00-00') {
            return new \DateTime($this->gueltig_ab . " 00:00:00");
        }
        return null;
    }

    public function getGueltigBis()
    {
        if ($this->gueltig_bis != '0000-00-00') {
            return new \DateTime($this->gueltig_bis . " 00:00:00");
        }
        return null;
    }

    public function getKunde()
    {
        return KundeModel::findByID($this->db, $this->kunde_id);
    }

    public function getAbteilung()
    {
        return AbteilungModel::findByID($this->db, $this->abteilung_id);
    }

    public function getPreis()
    {
        return $this->preis;
    }

    public function getSonntagszuschlag()
    {
        return $this->sonntagszuschlag;
    }

    public function getFeiertagszuschlag()
    {
        return $this->feiertagszuschlag;
    }

    public function getNachtzuschlag()
    {
        return $this->nachtzuschlag;
    }

    public function getNachtVon()
    {
        if ($this->nacht_von == '00:00:00') {
            if ($this->nacht_bis != '00:00:00') {
                return new \DateTime("0000-00-00 " . $this->nacht_von);
            }
        } else {
            return new \DateTime("0000-00-00 " . $this->nacht_von);
        }
        return null;
    }

    public function getNachtBis()
    {
        if ($this->nacht_bis == '00:00:00') {
            if ($this->nacht_von != '00:00:00') {
                return new \DateTime("0000-00-00 " . $this->nacht_bis);
            }
        } else {
            return new \DateTime("0000-00-00 " . $this->nacht_bis);
        }
        return null;
    }

    /**
     * setter methods
     */
    public function setGueltigAb(string $value)
    {
        return $this->setAttribute('gueltig_ab', $value);
    }

    public function setGueltigBis(string $value)
    {
        return $this->setAttribute('gueltig_bis', $value);
    }

    public function setKundeID(string $value)
    {
        return $this->setAttribute('kunde_id', $value);
    }

    public function setAbteilungID(string $value)
    {
        return $this->setAttribute('abteilung_id', $value);
    }

    public function setPreis(string $value)
    {
        return $this->setAttribute('preis', $value);
    }

    public function setSonntagszuschlag(string $value)
    {
        return $this->setAttribute('sonntagszuschlag', $value);
    }

    public function setFeiertagszuschlag(string $value)
    {
        return $this->setAttribute('feiertagszuschlag', $value);
    }

    public function setNachtzuschlag(string $value)
    {
        return $this->setAttribute('nachtzuschlag', $value);
    }

    public function setNachtVon(string $value)
    {
        return $this->setAttribute('nacht_von', $value);
    }

    public function setNachtBis(string $value)
    {
        return $this->setAttribute('nacht_bis', $value);
    }
}
