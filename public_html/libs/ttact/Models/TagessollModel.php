<?php

namespace ttact\Models;

class TagessollModel extends Model
{
    /**
     * properties
     */
    protected $tagessoll_id;
    protected $mitarbeiter_id;
    protected $jahr;
    protected $monat;
    protected $tagessoll;
    protected $tagessoll_montag;
    protected $tagessoll_dienstag;
    protected $tagessoll_mittwoch;
    protected $tagessoll_donnerstag;
    protected $tagessoll_freitag;
    protected $tagessoll_samstag;
    protected $tagessoll_sonntag;

    /**
     * logic methods
     */
    public static function findByID(\ttact\Database $db, $id)
    {
        $id = (int) $id;
        $model_data = $db->getFirstRow('tagessoll', ['tagessoll_id' => $id]);
        if (isset($model_data['tagessoll_id'])) {
            return new self($db, $model_data);
        }
        return null;
    }

    public static function findByJahrMonatMitarbeiter(\ttact\Database $db, $jahr, $monat, $mitarbeiter)
    {
        $model_data = $db->getFirstRow('tagessoll', ['jahr' => $jahr, 'monat' => $monat, 'mitarbeiter_id' => $mitarbeiter]);
        if (isset($model_data['tagessoll_id'])) {
            return new self($db, $model_data);
        }
        return null;
    }

    public static function findAll(\ttact\Database $db)
    {
        $return = [];

        $all = $db->getRows('tagessoll');
        foreach ($all as $model_data) {
            if (isset($model_data['tagessoll_id'])) {
                $return[] = new self($db, $model_data);
            }
        }

        return $return;
    }

    public static function findByMitarbeiter(\ttact\Database $db, $mitarbeiter)
    {
        $return = [];

        $all = $db->getRows('tagessoll', [], ['mitarbeiter_id' => $mitarbeiter]);
        foreach ($all as $model_data) {
            if (isset($model_data['tagessoll_id'])) {
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
        foreach ($db->getFieldNames('tagessoll') as $field_name) {
            if (isset($data[$field_name]) && $field_name != 'tagessoll_id') {
                $insert_data[$field_name] = $data[$field_name];
            }
        }

        // insert $insert_data into the mysql table
        $insert_id = $db->insert('tagessoll', $insert_data);
        if ($insert_id > 0) {
            return self::findByID($db, $insert_id);
        }

        return null;
    }

    private function setAttribute(string $attribute, string $value)
    {
        if($this->db->update('tagessoll', $this->getID(), [$attribute => $value])) {
            $this->{$attribute} = $value;
            return true;
        }

        return false;
    }

    public function delete()
    {
        if ($this->db->delete('tagessoll', $this->getID())) {
            return true;
        }

        return false;
    }

    /**
     * getter methods
     */
    public function getID()
    {
        return $this->tagessoll_id;
    }

    public function getMitarbeiter()
    {
        return MitarbeiterModel::findByID($this->db, $this->mitarbeiter_id);
    }

    public function getJahr()
    {
        return $this->jahr;
    }

    public function getMonat()
    {
        return $this->monat;
    }

    public function getTagessoll()
    {
        return $this->tagessoll;
    }

    public function getTagessollMontag()
    {
        return $this->tagessoll_montag;
    }

    public function getTagessollDienstag()
    {
        return $this->tagessoll_dienstag;
    }

    public function getTagessollMittwoch()
    {
        return $this->tagessoll_mittwoch;
    }

    public function getTagessollDonnerstag()
    {
        return $this->tagessoll_donnerstag;
    }

    public function getTagessollFreitag()
    {
        return $this->tagessoll_freitag;
    }

    public function getTagessollSamstag()
    {
        return $this->tagessoll_samstag;
    }

    public function getTagessollSonntag()
    {
        return $this->tagessoll_sonntag;
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

    public function setTagessoll(string $value)
    {
        return $this->setAttribute('tagessoll', $value);
    }

    public function setTagessollMontag(string $value)
    {
        return $this->setAttribute('tagessoll_montag', $value);
    }

    public function setTagessollDienstag(string $value)
    {
        return $this->setAttribute('tagessoll_dienstag', $value);
    }

    public function setTagessollMittwoch(string $value)
    {
        return $this->setAttribute('tagessoll_mittwoch', $value);
    }

    public function setTagessollDonnerstag(string $value)
    {
        return $this->setAttribute('tagessoll_donnerstag', $value);
    }

    public function setTagessollFreitag(string $value)
    {
        return $this->setAttribute('tagessoll_freitag', $value);
    }

    public function setTagessollSamstag(string $value)
    {
        return $this->setAttribute('tagessoll_samstag', $value);
    }

    public function setTagessollSonntag(string $value)
    {
        return $this->setAttribute('tagessoll_sonntag', $value);
    }
}
