<?php

namespace ttact\Models;

class KundenbeschraenkungModel extends Model
{
    /**
     * properties
     */
    protected $kundenbeschraenkung_id;
    protected $user_id;
    protected $kunde_id;

    /**
     * logic methods
     */
    public static function findAll(\ttact\Database $db)
    {
        $return = [];

        $all = $db->getRows('kundenbeschraenkung', [], [], ['kundenbeschraenkung_id']);
        foreach ($all as $model_data) {
            if (isset($model_data['kundenbeschraenkung_id'])) {
                $return[] = new self($db, $model_data);
            }
        }

        return $return;
    }

    public static function findAllByUserID(\ttact\Database $db, $user_id)
    {
        $return = [];

        $user_id = (int) $user_id;

        $all = $db->getRows('kundenbeschraenkung', [], ['user_id' => $user_id], ['kundenbeschraenkung_id']);
        foreach ($all as $model_data) {
            if (isset($model_data['kundenbeschraenkung_id'])) {
                $return[] = new self($db, $model_data);
            }
        }

        return $return;
    }

    public static function findAllByKundeID(\ttact\Database $db, $kunde_id)
    {
        $return = [];

        $kunde_id = (int) $kunde_id;

        $all = $db->getRows('kundenbeschraenkung', [], ['kunde_id' => $kunde_id], ['kundenbeschraenkung_id']);
        foreach ($all as $model_data) {
            if (isset($model_data['kundenbeschraenkung_id'])) {
                $return[] = new self($db, $model_data);
            }
        }

        return $return;
    }

    public static function findByID(\ttact\Database $db, $id)
    {
        $id = (int) $id;
        $model_data = $db->getFirstRow('kundenbeschraenkung', ['kundenbeschraenkung_id' => $id]);
        if (isset($model_data['kundenbeschraenkung_id'])) {
            return new self($db, $model_data);
        }
        return null;
    }

    private function setAttribute(string $attribute, string $value)
    {
        if ($this->db->update('kundenbeschraenkung', $this->getID(), [$attribute => $value])) {
            $this->{$attribute} = $value;
            return true;
        }

        return false;
    }

    public static function createNew(\ttact\Database $db, array $data)
    {
        // the data that will be inserted into the mysql table
        $insert_data = [];

        // copy all data from parameter $data into $insert_data if the respective field really exists
        foreach ($db->getFieldNames('kundenbeschraenkung') as $field_name) {
            if (isset($data[$field_name]) && $field_name != 'kundenbeschraenkung_id') {
                $insert_data[$field_name] = $data[$field_name];
            }
        }

        // insert $insert_data into the mysql table
        $insert_id = $db->insert('kundenbeschraenkung', $insert_data);
        if ($insert_id > 0) {
            return self::findByID($db, $insert_id);
        }

        return null;
    }

    public function delete()
    {
        return $this->db->delete('kundenbeschraenkung', $this->getID());
    }

    /**
     * getter methods
     */
    public function getID()
    {
        return $this->kundenbeschraenkung_id;
    }

    public function getUser()
    {
        return UserModel::findByID($this->db, $this->user_id);
    }

    public function getKunde()
    {
        return KundeModel::findByID($this->db, $this->kunde_id);
    }

    /**
     * setter methods
     */
    public function setUserID(string $value)
    {
        return $this->setAttribute('user_id', $value);
    }

    public function setKundeID(string $value)
    {
        return $this->setAttribute('kunde_id', $value);
    }
}
