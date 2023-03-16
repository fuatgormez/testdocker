<?php

namespace ttact\Models;

class DokumentModel extends Model
{
    /**
     * properties
     */
    protected $dokument_id;
    protected $kunde_id;
    protected $size;
    protected $name;
    protected $path;
    protected $user_id;
    protected $zeitpunkt;

    /**
     * logic methods
     */
    public static function findByID(\ttact\Database $db, $id)
    {
        $id = (int) $id;
        $model_data = $db->getFirstRow('dokument', ['dokument_id' => $id]);
        if (isset($model_data['dokument_id'])) {
            return new self($db, $model_data);
        }
        return null;
    }

    public static function findAll(\ttact\Database $db)
    {
        $return = [];

        $all = $db->getRows('dokument');
        foreach ($all as $model_data) {
            if (isset($model_data['dokument_id'])) {
                $return[] = new self($db, $model_data);
            }
        }

        return $return;
    }

    public static function findByKunde(\ttact\Database $db, int $kunde_id)
    {
        $return = [];

        $all = $db->getRows('dokument', [], ["kunde_id" => $kunde_id], ['zeitpunkt']);
        foreach ($all as $model_data) {
            if (isset($model_data['dokument_id'])) {
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
        foreach ($db->getFieldNames('dokument') as $field_name) {
            if (isset($data[$field_name]) && $field_name != 'dokument_id') {
                $insert_data[$field_name] = $data[$field_name];
            }
        }

        // insert $insert_data into the mysql table
        $insert_id = $db->insert('dokument', $insert_data);
        if ($insert_id > 0) {
            return self::findByID($db, $insert_id);
        }

        return null;
    }

    private function setAttribute(string $attribute, string $value)
    {
        if($this->db->update('dokument', $this->getID(), [$attribute => $value])) {
            $this->{$attribute} = $value;
            return true;
        }

        return false;
    }

    public function delete()
    {
        if ($this->db->delete('dokument', $this->getID())) {
            return true;
        }

        return false;
    }

    /**
     * getter methods
     */
    public function getID()
    {
        return $this->dokument_id;
    }

    public function getKunde()
    {
        return KundeModel::findByID($this->db, $this->kunde_id);
    }


    public function getSize()
    {
        return $this->size;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getPath()
    {
        return $this->path;
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
    public function setKundeID(string $value)
    {
        return $this->setAttribute('kunde_id', $value);
    }

    public function setSize(string $value)
    {
        return $this->setAttribute('size', $value);
    }

    public function setName(string $value)
    {
        return $this->setAttribute('name', $value);
    }

    public function setPath(string $value)
    {
        return $this->setAttribute('path', $value);
    }

    public function setUserID(string $value)
    {
        return $this->setAttribute('user_id', $value);
    }

    public function setZeitpunkt(string $value)
    {
        return $this->setAttribute('zeitpunkt', $value);
    }
}
