<?php

namespace ttact\Models;

class TarifModel extends Model
{
    /**
     * properties
     */
    protected $tarif_id;
    protected $bezeichnung;

    /**
     * logic methods
     */
    public static function findByID(\ttact\Database $db, $id)
    {
        $id = (int) $id;
        $model_data = $db->getFirstRow('tarif', ['tarif_id' => $id]);
        if (isset($model_data['tarif_id'])) {
            return new self($db, $model_data);
        }
        return null;
    }

    public static function findAll(\ttact\Database $db)
    {
        $return = [];

        $all = $db->getRows('tarif');
        foreach ($all as $model_data) {
            if (isset($model_data['tarif_id'])) {
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
        foreach ($db->getFieldNames('tarif') as $field_name) {
            if (isset($data[$field_name]) && $field_name != 'tarif_id') {
                $insert_data[$field_name] = $data[$field_name];
            }
        }

        // insert $insert_data into the mysql table
        $insert_id = $db->insert('tarif', $insert_data);
        if ($insert_id > 0) {
            return self::findByID($db, $insert_id);
        }

        return null;
    }

    private function setAttribute(string $attribute, string $value)
    {
        if($this->db->update('tarif', $this->getID(), [$attribute => $value])) {
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
        return $this->tarif_id;
    }

    public function getBezeichnung()
    {
        return $this->bezeichnung;
    }

    /**
     * setter methods
     */
    public function setBezeichnung(string $value)
    {
        return $this->setAttribute('bezeichnung', $value);
    }
}
