<?php

namespace ttact\Models;

class RechnungspostenModel extends Model
{
    /**
     * properties
     */
    protected $rechnungsposten_id;
    protected $rechnung_id;
    protected $leistungsart;
    protected $menge;
    protected $einzelpreis;
    protected $gesamtpreis;

    /**
     * logic methods
     */
    public static function findByID(\ttact\Database $db, $id)
    {
        $id = (int) $id;
        $model_data = $db->getFirstRow('rechnungsposten', ['rechnungsposten_id' => $id]);
        if (isset($model_data['rechnungsposten_id'])) {
            return new self($db, $model_data);
        }
        return null;
    }

    public static function findAllByRechnung(\ttact\Database $db, int $rechnung_id)
    {
        $return = [];

        $all = $db->getRows('rechnungsposten', [], ['rechnung_id' => $rechnung_id]);
        foreach ($all as $model_data) {
            if (isset($model_data['rechnungsposten_id'])) {
                $return[] = new self($db, $model_data);
            }
        }

        return $return;
    }

    public static function findAll(\ttact\Database $db)
    {
        $return = [];

        $all = $db->getRows('rechnungsposten');
        foreach ($all as $model_data) {
            if (isset($model_data['rechnungsposten_id'])) {
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
        foreach ($db->getFieldNames('rechnungsposten') as $field_name) {
            if (isset($data[$field_name]) && $field_name != 'rechnungsposten_id') {
                $insert_data[$field_name] = $data[$field_name];
            }
        }

        // insert $insert_data into the mysql table
        $insert_id = $db->insert('rechnungsposten', $insert_data);
        if ($insert_id > 0) {
            return self::findByID($db, $insert_id);
        }

        return null;
    }

    public static function createDummyObjectWithoutSaving(\ttact\Database $db, array $data)
    {
        $data['rechnungsposten_id'] = -1;
        return new self($db, $data);
    }

    private function setAttribute(string $attribute, string $value)
    {
        if ($this->db->update('rechnungsposten', $this->getID(), [$attribute => $value])) {
            $this->{$attribute} = $value;
            return true;
        }

        return false;
    }

    public function delete()
    {
        if ($this->db->delete('rechnungsposten', $this->getID())) {
            return true;
        }

        return false;
    }

    /**
     * getter methods
     */
    public function getID()
    {
        return $this->rechnungsposten_id;
    }

    public function getRechnung()
    {
        return RechnungModel::findByID($this->db, $this->rechnung_id);
    }

    public function getLeistungsart()
    {
        return $this->leistungsart;
    }

    public function getMenge()
    {
        return $this->menge;
    }

    public function getEinzelpreis()
    {
        return $this->einzelpreis;
    }

    public function getGesamtpreis()
    {
        return $this->gesamtpreis;
    }

    /**
     * setter methods
     */
    public function setRechnungID(string $value)
    {
        return $this->setAttribute('rechnung_id', $value);

    }

    public function setLeistungsart(string $value)
    {
        return $this->setAttribute('leistungsart', $value);
    }

    public function setMenge(string $value)
    {
        return $this->setAttribute('menge', $value);
    }

    public function setEinzelpreis(string $value)
    {
        return $this->setAttribute('einzelpreis', $value);
    }

    public function setGesamtpreis(string $value)
    {
        return $this->setAttribute('gesamtpreis', $value);
    }
}
