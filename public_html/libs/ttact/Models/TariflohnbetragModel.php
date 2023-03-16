<?php

namespace ttact\Models;

class TariflohnbetragModel extends Model
{
    /**
     * properties
     */
    protected $tariflohnbetrag_id;
    protected $tarif_id;
    protected $gueltig_ab;
    protected $lohn;

    /**
     * logic methods
     */
    public static function findByID(\ttact\Database $db, $id)
    {
        $id = (int) $id;
        $model_data = $db->getFirstRow('tariflohnbetrag', ['tariflohnbetrag_id' => $id]);
        if (isset($model_data['tariflohnbetrag_id'])) {
            return new self($db, $model_data);
        }
        return null;
    }

    public static function findAll(\ttact\Database $db)
    {
        $return = [];

        $all = $db->getRows('tariflohnbetrag');
        foreach ($all as $model_data) {
            if (isset($model_data['tariflohnbetrag_id'])) {
                $return[] = new self($db, $model_data);
            }
        }

        return $return;
    }

    public static function findAllByTarifID(\ttact\Database $db, int $tarif_id)
    {
        $return = [];

        $all = $db->getRows('tariflohnbetrag', [], ['tarif_id' => $tarif_id]);
        foreach ($all as $model_data) {
            if (isset($model_data['tariflohnbetrag_id'])) {
                $return[] = new self($db, $model_data);
            }
        }

        return $return;
    }

    public static function findByTarifAndDatum(\ttact\Database $db, int $tarif_id, \DateTime $date)
    {
        $model_data = $db->getRowsQuery("SELECT * FROM tariflohnbetrag WHERE tarif_id = '".$tarif_id."' AND (gueltig_ab = '0000-00-00' OR gueltig_ab <= '".$date->format("Y-m-d")."') ORDER BY gueltig_ab DESC LIMIT 1");
        if (isset($model_data[0])) {
            if (isset($model_data[0]['tariflohnbetrag_id'])) {
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
        foreach ($db->getFieldNames('tariflohnbetrag') as $field_name) {
            if (isset($data[$field_name]) && $field_name != 'tariflohnbetrag_id') {
                $insert_data[$field_name] = $data[$field_name];
            }
        }

        // insert $insert_data into the mysql table
        $insert_id = $db->insert('tariflohnbetrag', $insert_data);
        if ($insert_id > 0) {
            return self::findByID($db, $insert_id);
        }

        return null;
    }

    private function setAttribute(string $attribute, string $value)
    {
        if($this->db->update('tariflohnbetrag', $this->getID(), [$attribute => $value])) {
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
        return $this->tariflohnbetrag_id;
    }

    public function getTarif(\ttact\Database $db)
    {
        return TarifModel::findByID($db, $this->tarif_id);
    }

    public function getGueltigAb()
    {
        if ($this->gueltig_ab != '0000-00-00') {
            return new \DateTime($this->gueltig_ab . " 00:00:00");
        }
        return null;
    }

    public function getLohn()
    {
        return $this->lohn;
    }

    /**
     * setter methods
     */
    public function setTarifID(string $value)
    {
        return $this->setAttribute('tarif_id', $value);
    }

    public function setGueltigAb(string $value)
    {
        return $this->setAttribute('gueltig_ab', $value);
    }

    public function setLohn(string $value)
    {
        return $this->setAttribute('lohn', $value);
    }
}
