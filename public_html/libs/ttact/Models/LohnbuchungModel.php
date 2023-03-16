<?php

namespace ttact\Models;

class LohnbuchungModel extends Model
{
    /**
     * properties
     */
    protected $lohnbuchung_id;
    protected $mitarbeiter_id;
    protected $datum;
    protected $lohnart;
    protected $wert;
    protected $faktor;
    protected $bezeichnung;
    protected $user_id;
    protected $zeitpunkt;

    /**
     * logic methods
     */
    public static function findByID(\ttact\Database $db, $id)
    {
        $id = (int) $id;
        $model_data = $db->getFirstRow('lohnbuchung', ['lohnbuchung_id' => $id]);
        if (isset($model_data['lohnbuchung_id'])) {
            return new self($db, $model_data);
        }
        return null;
    }

    public static function findAll(\ttact\Database $db)
    {
        $return = [];

        $all = $db->getRows('lohnbuchung');
        foreach ($all as $model_data) {
            if (isset($model_data['lohnbuchung_id'])) {
                $return[] = new self($db, $model_data);
            }
        }

        return $return;
    }

    public static function findByMitarbeiter(\ttact\Database $db, int $mitarbeiter)
    {
        $return = [];

        $all = $db->getRows('lohnbuchung', [], ["mitarbeiter_id" => $mitarbeiter], ['datum']);
        foreach ($all as $model_data) {
            if (isset($model_data['lohnbuchung_id'])) {
                $return[] = new self($db, $model_data);
            }
        }

        return $return;
    }

    public static function findByYearMonthMitarbeiter(\ttact\Database $db, string $jahr, string $monat, int $mitarbeiter)
    {
        $return = [];

        $all = $db->getRowsQuery("SELECT * FROM lohnbuchung WHERE mitarbeiter_id = '$mitarbeiter' AND datum LIKE '$jahr-$monat%' ORDER BY datum");
        foreach ($all as $model_data) {
            if (isset($model_data['lohnbuchung_id'])) {
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
        foreach ($db->getFieldNames('lohnbuchung') as $field_name) {
            if (isset($data[$field_name]) && $field_name != 'lohnbuchung_id') {
                $insert_data[$field_name] = $data[$field_name];
            }
        }

        // insert $insert_data into the mysql table
        $insert_id = $db->insert('lohnbuchung', $insert_data);
        if ($insert_id > 0) {
            return self::findByID($db, $insert_id);
        }

        return null;
    }

    private function setAttribute(string $attribute, string $value)
    {
        if($this->db->update('lohnbuchung', $this->getID(), [$attribute => $value])) {
            $this->{$attribute} = $value;
            return true;
        }

        return false;
    }

    public function delete()
    {
        if ($this->db->delete('lohnbuchung', $this->getID())) {
            return true;
        }

        return false;
    }

    /**
     * getter methods
     */
    public function getID()
    {
        return $this->lohnbuchung_id;
    }

    public function getMitarbeiter()
    {
        return MitarbeiterModel::findByID($this->db, $this->mitarbeiter_id);
    }


    public function getDatum()
    {
        if ($this->datum != '0000-00-00') {
            return new \DateTime($this->datum . " 00:00:00");
        }
        return null;
    }

    public function getLohnart()
    {
        return $this->lohnart;
    }

    public function getWert()
    {
        return $this->wert == 0 ? '' : $this->wert;
    }

    public function getFaktor()
    {
        return $this->faktor == 0 ? '' : $this->faktor;
    }

    public function getBezeichnung()
    {
        return $this->bezeichnung;
    }

    public function getUser()
    {
        return UserModel::findByID($this->db, $this->user_id);
    }

    public function getZeitpunkt()
    {
        if ($this->zeitpunkt != '0000-00-00 00:00:00') {
            return new \DateTime($this->zeitpunkt);
        }
        return null;
    }

    /**
     * setter methods
     */
    public function setMitarbeiterID(string $value)
    {
        return $this->setAttribute('mitarbeiter_id', $value);
    }

    public function setDatum(string $value)
    {
        return $this->setAttribute('datum', $value);
    }

    public function setLohnart(string $value)
    {
        return $this->setAttribute('lohnart', $value);
    }

    public function setWert(string $value)
    {
        return $this->setAttribute('wert', $value);
    }

    public function setFaktor(string $value)
    {
        return $this->setAttribute('faktor', $value);
    }

    public function setBezeichnung(string $value)
    {
        return $this->setAttribute('bezeichnung', $value);
    }
}
