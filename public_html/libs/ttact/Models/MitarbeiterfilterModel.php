<?php

namespace ttact\Models;

class MitarbeiterfilterModel extends Model
{
    /**
     * properties
     */
    protected $mitarbeiterfilter_id;
    protected $type;
    protected $kunde_id;
    protected $mitarbeiter_id;

    /**
     * logic methods
     */
    public static function findByID(\ttact\Database $db, $id)
    {
        $id = (int) $id;
        $model_data = $db->getFirstRow('mitarbeiterfilter', ['mitarbeiterfilter_id' => $id]);
        if (isset($model_data['mitarbeiterfilter_id'])) {
            return new self($db, $model_data);
        }
        return null;
    }

    public static function findAll(\ttact\Database $db)
    {
        $return = [];

        $all = $db->getRows('mitarbeiterfilter');
        foreach ($all as $model_data) {
            if (isset($model_data['mitarbeiterfilter_id'])) {
                $return[] = new self($db, $model_data);
            }
        }

        return $return;
    }

    public static function findByMitarbeiter(\ttact\Database $db, int $mitarbeiter)
    {
        $return = [];

        $all = $db->getRows('mitarbeiterfilter', [], ['mitarbeiter_id' => $mitarbeiter]);
        foreach ($all as $model_data) {
            if (isset($model_data['mitarbeiterfilter_id'])) {
                $return[] = new self($db, $model_data);
            }
        }

        return $return;
    }

    public static function findStammByMitarbeiter(\ttact\Database $db, int $mitarbeiter)
    {
        $return = [];

        $all = $db->getRows('mitarbeiterfilter', [], ['mitarbeiter_id' => $mitarbeiter, 'type' => 'stamm']);
        foreach ($all as $model_data) {
            if (isset($model_data['mitarbeiterfilter_id'])) {
                $return[] = new self($db, $model_data);
            }
        }

        return $return;
    }

    public static function findSpringerByMitarbeiter(\ttact\Database $db, int $mitarbeiter)
    {
        $return = [];

        $all = $db->getRows('mitarbeiterfilter', [], ['mitarbeiter_id' => $mitarbeiter, 'type' => 'springer']);
        foreach ($all as $model_data) {
            if (isset($model_data['mitarbeiterfilter_id'])) {
                $return[] = new self($db, $model_data);
            }
        }

        return $return;
    }

    public static function findSperreByMitarbeiter(\ttact\Database $db, int $mitarbeiter)
    {
        $return = [];

        $all = $db->getRows('mitarbeiterfilter', [], ['mitarbeiter_id' => $mitarbeiter, 'type' => 'sperre']);
        foreach ($all as $model_data) {
            if (isset($model_data['mitarbeiterfilter_id'])) {
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
        foreach ($db->getFieldNames('mitarbeiterfilter') as $field_name) {
            if (isset($data[$field_name]) && $field_name != 'mitarbeiterfilter_id') {
                $insert_data[$field_name] = $data[$field_name];
            }
        }

        // insert $insert_data into the mysql table
        $insert_id = $db->insert('mitarbeiterfilter', $insert_data);
        if ($insert_id > 0) {
            return self::findByID($db, $insert_id);
        }

        return null;
    }

    private function setAttribute(string $attribute, string $value)
    {
        if($this->db->update('mitarbeiterfilter', $this->getID(), [$attribute => $value])) {
            $this->{$attribute} = $value;
            return true;
        }

        return false;
    }

    public function delete()
    {
        return $this->db->delete('mitarbeiterfilter', $this->getID());
    }

    /**
     * getter methods
     */
    public function getID()
    {
        return $this->mitarbeiterfilter_id;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getKunde()
    {
        return KundeModel::findByID($this->db, $this->kunde_id);
    }

    public function getMitarbeiter()
    {
        return MitarbeiterModel::findByID($this->db, $this->mitarbeiter_id);
    }

    /**
     * setter methods
     */
    public function setType(string $value)
    {
        return $this->setAttribute('type', $value);
    }

    public function setKundeID(string $value)
    {
        return $this->setAttribute('kunde_id', $value);
    }

    public function setMitarbeiterID(string $value)
    {
        return $this->setAttribute('mitarbeiter_id', $value);
    }
}
