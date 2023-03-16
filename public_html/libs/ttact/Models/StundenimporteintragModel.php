<?php

namespace ttact\Models;

class StundenimporteintragModel extends Model
{
    /**
     * properties
     */
    protected $stundenimporteintrag_id;
    protected $kunde_id;
    protected $mitarbeiter_id;
    protected $von;
    protected $bis;
    protected $pause;

    /**
     * logic methods
     */
    public static function findByID(\ttact\Database $db, $id)
    {
        $id = (int) $id;
        $model_data = $db->getFirstRow('stundenimporteintrag', ['stundenimporteintrag_id' => $id]);
        if (isset($model_data['stundenimporteintrag_id'])) {
            return new self($db, $model_data);
        }
        return null;
    }

    public static function findByMitarbeiter(\ttact\Database $db, $mitarbeiter)
    {
        $return = [];

        $all = $db->getRows('stundenimporteintrag', [], ['mitarbeiter_id' => $mitarbeiter]);
        foreach ($all as $model_data) {
            if (isset($model_data['stundenimporteintrag_id'])) {
                $return[] = new self($db, $model_data);
            }
        }

        return $return;
    }

    public static function findByStartEndMitarbeiter(\ttact\Database $db, \DateTimeInterface $inclusive_start, \DateTimeInterface $inclusive_end, int $mitarbeiter)
    {
        $return = [];

        $a = $inclusive_start->format('Y-m-d H:i:s');
        $b = $inclusive_end->format('Y-m-d H:i:s');

        $selection = $db->getRowsQuery("SELECT * FROM stundenimporteintrag WHERE mitarbeiter_id = '$mitarbeiter' AND von >= '$a' AND von <= '$b' ORDER BY von");

        foreach ($selection as $model_data) {
            if (isset($model_data['stundenimporteintrag_id'])) {
                $return[] = new self($db, $model_data);
            }
        }

        return $return;
    }

    public static function findAll(\ttact\Database $db)
    {
        $return = [];

        $all = $db->getRows('stundenimporteintrag');
        foreach ($all as $model_data) {
            if (isset($model_data['stundenimporteintrag_id'])) {
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
        foreach ($db->getFieldNames('stundenimporteintrag') as $field_name) {
            if (isset($data[$field_name]) && $field_name != 'stundenimporteintrag_id') {
                $insert_data[$field_name] = $data[$field_name];
            }
        }

        // insert $insert_data into the mysql table
        $insert_id = $db->insert('stundenimporteintrag', $insert_data);
        if ($insert_id > 0) {
            return self::findByID($db, $insert_id);
        }

        return null;
    }

    private function setAttribute(string $attribute, string $value)
    {
        if($this->db->update('stundenimporteintrag', $this->getID(), [$attribute => $value])) {
            $this->{$attribute} = $value;
            return true;
        }

        return false;
    }

    public function delete()
    {
        if ($this->db->delete('stundenimporteintrag', $this->getID())) {
            return true;
        }

        return false;
    }

    /**
     * getter methods
     */
    public function getID()
    {
        return $this->stundenimporteintrag_id;
    }

    public function getKunde()
    {
        return KundeModel::findByID($this->db, $this->kunde_id);
    }

    public function getMitarbeiter()
    {
        return MitarbeiterModel::findByID($this->db, $this->mitarbeiter_id);
    }

    public function getVon()
    {
        return new \DateTime($this->von);
    }

    public function getBis()
    {
        return new \DateTime($this->bis);
    }

    public function getPause()
    {
        return new \DateInterval("P0000-00-00T" . $this->pause);
    }

    public function getPauseSeconds()
    {
        if ($this->pause == '00:00:00') {
            return 0;
        }
        $a = new \DateTime("now");
        $b = clone $a;
        $b->add($this->getPause());
        return $b->getTimestamp() - $a->getTimestamp();
    }

    /**
     * @return integer Duration of Schicht with subtracted Pause in seconds
     */
    public function getSeconds()
    {
        return $this->getBis()->getTimestamp() - $this->getVon()->getTimestamp() - $this->getPauseSeconds();
    }

    /**
     * @return integer Duration of Schicht with subtracted Pause in hours (rounded to two decimals)
     */
    public function getHours()
    {
        return round(($this->getBis()->getTimestamp() - $this->getVon()->getTimestamp() - $this->getPauseSeconds()) / 3600, 2);
    }

    /**
     * setter methods
     */
    public function setKundeID(string $value)
    {
        return $this->setAttribute('kunde_id', $value);
    }

    public function setMitarbeiterID(string $value)
    {
        return $this->setAttribute('mitarbeiter_id', $value);
    }

    public function setVon(string $value)
    {
        return $this->setAttribute('von', $value);
    }

    public function setBis(string $value)
    {
        return $this->setAttribute('bis', $value);
    }

    public function setPause(string $value)
    {
        return $this->setAttribute('pause', $value);
    }
}
