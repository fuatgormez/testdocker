<?php

namespace ttact\Models;

class AbteilungModel extends Model
{
    /**
     * properties
     */
    protected $abteilung_id;
    protected $bezeichnung;
    protected $in_rechnung_stellen;

    /**
     * logic methods
     */
    public static function findByID(\ttact\Database $db, $id)
    {
        $id = (int) $id;
        $model_data = $db->getFirstRow('abteilung', ['abteilung_id' => $id]);
        if (isset($model_data['abteilung_id'])) {
            return new self($db, $model_data);
        }
        return null;
    }

    public static function findAll(\ttact\Database $db)
    {
        $return = [];

        $all = $db->getRows('abteilung');
        foreach ($all as $model_data) {
            if (isset($model_data['abteilung_id'])) {
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
        foreach ($db->getFieldNames('abteilung') as $field_name) {
            if (isset($data[$field_name]) && $field_name != 'abteilung_id') {
                $insert_data[$field_name] = $data[$field_name];
            }
        }

        // insert $insert_data into the mysql table
        $insert_id = $db->insert('abteilung', $insert_data);
        if ($insert_id > 0) {
            return self::findByID($db, $insert_id);
        }

        return null;
    }

    private function setAttribute(string $attribute, string $value)
    {
        if($this->db->update('abteilung', $this->getID(), [$attribute => $value])) {
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
        return $this->abteilung_id;
    }

    public function getBezeichnung()
    {
        return $this->bezeichnung;
    }

    public function getInRechnungStellen()
    {
        return $this->in_rechnung_stellen == 1;
    }

    /**
     * setter methods
     */

    public function setBezeichnung(string $value)
    {
        return $this->setAttribute('bezeichnung', $value);
    }

    public function setInRechnungStellen(string $value)
    {
        return $this->setAttribute('in_rechnung_stellen', $value);
    }
}
