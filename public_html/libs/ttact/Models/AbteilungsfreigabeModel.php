<?php

namespace ttact\Models;

class AbteilungsfreigabeModel extends Model
{
    /**
     * properties
     */
    protected $abteilungsfreigabe_id;
    protected $mitarbeiter_id;
    protected $abteilung_id;

    /**
     * logic methods
     */
    public static function findByID(\ttact\Database $db, $id)
    {
        $id = (int) $id;
        $model_data = $db->getFirstRow('abteilungsfreigabe', ['abteilungsfreigabe_id' => $id]);
        if (isset($model_data['abteilungsfreigabe_id'])) {
            return new self($db, $model_data);
        }
        return null;
    }

    public static function findAll(\ttact\Database $db)
    {
        $return = [];

        $all = $db->getRows('abteilungsfreigabe');
        foreach ($all as $model_data) {
            if (isset($model_data['abteilungsfreigabe_id'])) {
                $return[] = new self($db, $model_data);
            }
        }

        return $return;
    }

    public static function findByMitarbeiter(\ttact\Database $db, $mitarbeiter)
    {
        $return = [];

        $all = $db->getRows('abteilungsfreigabe', [], ['mitarbeiter_id' => $mitarbeiter]);
        foreach ($all as $model_data) {
            if (isset($model_data['abteilungsfreigabe_id'])) {
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
        foreach ($db->getFieldNames('abteilungsfreigabe') as $field_name) {
            if (isset($data[$field_name]) && $field_name != 'abteilungsfreigabe_id') {
                $insert_data[$field_name] = $data[$field_name];
            }
        }

        // insert $insert_data into the mysql table
        $insert_id = $db->insert('abteilungsfreigabe', $insert_data);
        if ($insert_id > 0) {
            return self::findByID($db, $insert_id);
        }

        return null;
    }

    private function setAttribute(string $attribute, string $value)
    {
        if($this->db->update('abteilungsfreigabe', $this->getID(), [$attribute => $value])) {
            $this->{$attribute} = $value;
            return true;
        }

        return false;
    }

    public function delete()
    {
        return $this->db->delete('abteilungsfreigabe', $this->getID());
    }

    /**
     * getter methods
     */
    public function getID()
    {
        return $this->abteilungsfreigabe_id;
    }

    public function getMitarbeiter()
    {
        return MitarbeiterModel::findByID($this->db, $this->mitarbeiter_id);
    }

    public function getAbteilung()
    {
        return AbteilungModel::findByID($this->db, $this->abteilung_id);
    }

    /**
     * setter methods
     */
    public function setMitarbeiterID(string $value)
    {
        return $this->setAttribute('mitarbeiter_id', $value);
    }

    public function setAbteilungID(string $value)
    {
        return $this->setAttribute('abteilung_id', $value);
    }


}
