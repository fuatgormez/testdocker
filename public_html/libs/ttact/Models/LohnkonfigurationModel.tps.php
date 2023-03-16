<?php

namespace ttact\Models;

class LohnkonfigurationModel extends Model
{
    /**
     * properties
     */
    protected $lohnkonfiguration_id;
    protected $gueltig_ab;
    protected $mitarbeiter_id;
    protected $wochenstunden;
    protected $soll_lohn;

    /**
     * logic methods
     */
    public static function findByID(\ttact\Database $db, $id)
    {
        $id = (int) $id;
        $model_data = $db->getFirstRow('lohnkonfiguration', ['lohnkonfiguration_id' => $id]);
        if (isset($model_data['lohnkonfiguration_id'])) {
            return new self($db, $model_data);
        }
        return null;
    }

    public static function findAll(\ttact\Database $db)
    {
        $return = [];

        $all = $db->getRows('lohnkonfiguration');
        foreach ($all as $model_data) {
            if (isset($model_data['lohnkonfiguration_id'])) {
                $return[] = new self($db, $model_data);
            }
        }

        return $return;
    }

    public static function findByMitarbeiter(\ttact\Database $db, int $mitarbeiter)
    {
        $return = [];

        $all = $db->getRows('lohnkonfiguration', [], ["mitarbeiter_id" => $mitarbeiter], ['gueltig_ab']);
        foreach ($all as $model_data) {
            if (isset($model_data['lohnkonfiguration_id'])) {
                $return[] = new self($db, $model_data);
            }
        }

        return $return;
    }

    public static function findForMitarbeiterDate(\ttact\Database $db, int $mitarbeiter, \DateTime $date)
    {
        $model_data = $db->getRowsQuery("SELECT * FROM lohnkonfiguration WHERE mitarbeiter_id = '".$mitarbeiter."' AND (gueltig_ab = '0000-00-00' OR gueltig_ab <= '".$date->format("Y-m-d")."') ORDER BY gueltig_ab DESC LIMIT 1");
        if (isset($model_data[0])) {
            if (isset($model_data[0]['lohnkonfiguration_id'])) {
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
        foreach ($db->getFieldNames('lohnkonfiguration') as $field_name) {
            if (isset($data[$field_name]) && $field_name != 'lohnkonfiguration_id') {
                $insert_data[$field_name] = $data[$field_name];
            }
        }

        // insert $insert_data into the mysql table
        $insert_id = $db->insert('lohnkonfiguration', $insert_data);
        if ($insert_id > 0) {
            return self::findByID($db, $insert_id);
        }

        return null;
    }

    private function setAttribute(string $attribute, string $value)
    {
        if($this->db->update('lohnkonfiguration', $this->getID(), [$attribute => $value])) {
            $this->{$attribute} = $value;
            return true;
        }

        return false;
    }

    public function delete()
    {
        if ($this->db->delete('lohnkonfiguration', $this->getID())) {
            return true;
        }

        return false;
    }

    /**
     * getter methods
     */
    public function getID()
    {
        return $this->lohnkonfiguration_id;
    }

    public function getGueltigAb()
    {
        if ($this->gueltig_ab != '0000-00-00') {
            return new \DateTime($this->gueltig_ab . " 00:00:00");
        }
        return null;
    }

    public function getMitarbeiter()
    {
        return MitarbeiterModel::findByID($this->db, $this->mitarbeiter_id);
    }

    public function getWochenstunden()
    {
        return $this->wochenstunden == 0 ? '' : $this->wochenstunden;
    }

    public function getSollLohn()
    {
        return $this->soll_lohn == 0 ? '' : $this->soll_lohn;
    }

    /**
     * setter methods
     */
    public function setGueltigAb(string $value)
    {
        return $this->setAttribute('gueltig_ab', $value);
    }

    public function setMitarbeiterID(string $value)
    {
        return $this->setAttribute('mitarbeiter_id', $value);
    }

    public function setWochenstunden(string $value)
    {
        return $this->setAttribute('wochenstunden', $value);
    }

    public function setSollLohn(string $value)
    {
        return $this->setAttribute('soll_lohn', $value);
    }
}
