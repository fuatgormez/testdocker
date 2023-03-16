<?php

namespace ttact\Models;

class ArbeitszeitkontoModel extends Model
{
    /**
     * properties
     */
    protected $arbeitszeitkonto_id;
    protected $mitarbeiter_id;
    protected $jahr;
    protected $monat;
    protected $stunden;

    /**
     * logic methods
     */
    public static function findByID(\ttact\Database $db, $id)
    {
        $id = (int) $id;
        $model_data = $db->getFirstRow('arbeitszeitkonto', ['arbeitszeitkonto_id' => $id]);
        if (isset($model_data['arbeitszeitkonto_id'])) {
            return new self($db, $model_data);
        }
        return null;
    }

    public static function findByYearMonthMitarbeiter(\ttact\Database $db, $year, $month, $mitarbeiter)
    {
        $model_data = $db->getFirstRow('arbeitszeitkonto', ['mitarbeiter_id' => $mitarbeiter, 'jahr' => $year, 'monat' => $month]);
        if (isset($model_data['arbeitszeitkonto_id'])) {
            return new self($db, $model_data);
        }
        return null;
    }

    public static function findAll(\ttact\Database $db)
    {
        $return = [];

        $all = $db->getRows('arbeitszeitkonto');
        foreach ($all as $model_data) {
            if (isset($model_data['arbeitszeitkonto_id'])) {
                $return[] = new self($db, $model_data);
            }
        }

        return $return;
    }

    public static function findByMitarbeiter(\ttact\Database $db, $mitarbeiter)
    {
        $return = [];

        $all = $db->getRows('arbeitszeitkonto', [], ['mitarbeiter_id' => $mitarbeiter]);
        foreach ($all as $model_data) {
            if (isset($model_data['arbeitszeitkonto_id'])) {
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
        foreach ($db->getFieldNames('arbeitszeitkonto') as $field_name) {
            if (isset($data[$field_name]) && $field_name != 'arbeitszeitkonto_id') {
                $insert_data[$field_name] = $data[$field_name];
            }
        }

        // insert $insert_data into the mysql table
        $insert_id = $db->insert('arbeitszeitkonto', $insert_data);
        if ($insert_id > 0) {
            return self::findByID($db, $insert_id);
        }

        return null;
    }

    private function setAttribute(string $attribute, string $value)
    {
        if($this->db->update('arbeitszeitkonto', $this->getID(), [$attribute => $value])) {
            $this->{$attribute} = $value;
            return true;
        }

        return false;
    }

    public function delete()
    {
        if ($this->db->delete('arbeitszeitkonto', $this->getID())) {
            return true;
        }

        return false;
    }

    /**
     * getter methods
     */
    public function getID()
    {
        return $this->arbeitszeitkonto_id;
    }

    public function getMitarbeiter()
    {
        return MitarbeiterModel::findByID($this->db, $this->mitarbeiter_id);
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getJahr()
    {
        return $this->jahr;
    }

    public function getMonat()
    {
        return $this->monat;
    }

    public function getStunden()
    {
        return $this->stunden;
    }

    /**
     * setter methods
     */
    public function setMitarbeiterID(string $value)
    {
        return $this->setAttribute('mitarbeiter_id', $value);
    }

    public function setJahr(string $value)
    {
        return $this->setAttribute('jahr', $value);
    }

    public function setMonat(string $value)
    {
        return $this->setAttribute('monat', $value);
    }

    public function setStunden(string $value)
    {
        return $this->setAttribute('stunden', $value);
    }
}
