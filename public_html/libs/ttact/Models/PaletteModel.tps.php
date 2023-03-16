<?php

namespace ttact\Models;

class PaletteModel extends Model
{
    /**
     * properties
     */
    protected $palette_id;
    protected $kunde_id;
    protected $abteilung_id;
    protected $datum;
    protected $anzahl;

    /**
     * logic methods
     */
    public static function findByID(\ttact\Database $db, $id)
    {
        $id = (int) $id;
        $model_data = $db->getFirstRow('palette', ['palette_id' => $id]);
        if (isset($model_data['palette_id'])) {
            return new self($db, $model_data);
        }
        return null;
    }

    public static function findAll(\ttact\Database $db)
    {
        $return = [];

        $all = $db->getRows('palette');
        foreach ($all as $model_data) {
            if (isset($model_data['palette_id'])) {
                $return[] = new self($db, $model_data);
            }
        }

        return $return;
    }

    public static function findByYearWeekKunden(\ttact\Database $db, int $year, int $week, array $kunden)
    {
        $return = [];

        $first_day = new \DateTime();
        $first_day->setISODate($year, $week, 1);
        $first_day->setTime(0, 0, 0);

        $last_day = new \DateTime();
        $last_day->setISODate($year, $week, 7);
        $last_day->setTime(23, 59, 59);

        if (count($kunden) > 0) {
            $selection = $db->getRows('palette', [], [['datum', '>=', $first_day->format('Y-m-d')], ['datum', '<=', $last_day->format('Y-m-d')], ['kunde_id', $kunden]], ['kunde_id', 'abteilung_id', 'datum']);
        } else {
            $selection = $db->getRows('palette', [], [['datum', '>=', $first_day->format('Y-m-d')], ['datum', '<=', $last_day->format('Y-m-d')]], ['kunde_id', 'abteilung_id', 'datum']);
        }

        foreach ($selection as $model_data) {
            if (isset($model_data['palette_id'])) {
                $return[] = new self($db, $model_data);
            }
        }

        return $return;
    }

    public static function findAllByYearWeekDayKundeAbteilung(\ttact\Database $db, int $year, int $week, int $day, int $kunde, int $abteilung)
    {
        $return = [];

        $date = new \DateTime();
        $date->setISODate($year, $week, $day);
        $date->setTime(0, 0, 0);

        $selection = $db->getRows('palette', [], [['datum', '=', $date->format('Y-m-d')], ['kunde_id', '=', $kunde], ['abteilung_id', '=', $abteilung]], ['datum']);

        foreach ($selection as $model_data) {
            if (isset($model_data['palette_id'])) {
                $return[] = new self($db, $model_data);
            }
        }

        return $return;
    }

    public static function findByStartEndKunde(\ttact\Database $db, \DateTime $start, \DateTime $end, int $kunde)
    {
        $return = [];

        $selection = $db->getRows('palette', [], [['datum', '>=', $start->format('Y-m-d')], ['datum', '<=', $end->format('Y-m-d')], ['kunde_id', '=', $kunde]], ['kunde_id', 'abteilung_id', 'datum']);

        foreach ($selection as $model_data) {
            if (isset($model_data['palette_id'])) {
                $return[] = new self($db, $model_data);
            }
        }

        return $return;
    }

    public static function findByYearWeekDayKundenAbteilungen(\ttact\Database $db, int $year, int $week, int $day, array $kunden, array $abteilungen)
    {
        $return = [];

        $date = new \DateTime();
        $date->setISODate($year, $week, $day);
        $date->setTime(0, 0, 0);

        $selection = $db->getRows('palette', [], [['datum', '=', $date->format('Y-m-d')], ['kunde_id', $kunden], ['abteilung_id', $abteilungen]], ['kunde_id', 'abteilung_id', 'datum']);

        foreach ($selection as $model_data) {
            if (isset($model_data['palette_id'])) {
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
        foreach ($db->getFieldNames('palette') as $field_name) {
            if (isset($data[$field_name]) && $field_name != 'palette_id') {
                $insert_data[$field_name] = $data[$field_name];
            }
        }

        // insert $insert_data into the mysql table
        $insert_id = $db->insert('palette', $insert_data);
        if ($insert_id > 0) {
            return self::findByID($db, $insert_id);
        }

        return null;
    }

    private function setAttribute(string $attribute, string $value)
    {
        if($this->db->update('palette', $this->getID(), [$attribute => $value])) {
            $this->{$attribute} = $value;
            return true;
        }

        return false;
    }

    public function delete()
    {
        if ($this->db->delete('palette', $this->getID())) {
            return true;
        }

        return false;
    }

    /**
     * getter methods
     */
    public function getID()
    {
        return $this->palette_id;
    }

    public function getKunde()
    {
        return \ttact\Models\KundeModel::findByID($this->db, $this->kunde_id);
    }

    public function getAbteilung()
    {
        return \ttact\Models\AbteilungModel::findByID($this->db, $this->abteilung_id);
    }

    public function getDatum()
    {
        return new \DateTime($this->datum . ' 00:00:00');
    }

    public function getAnzahl()
    {
        return $this->anzahl;
    }

    /**
     * setter methods
     */

    public function setKundeID(string $value)
    {
        return $this->setAttribute('kunde_id', $value);
    }

    public function setAbteilungID(string $value)
    {
        return $this->setAttribute('abteilung_id', $value);
    }

    public function setDatum(string $value)
    {
        return $this->setAttribute('datum', $value);
    }

    public function setAnzahl(string $value)
    {
        return $this->setAttribute('anzahl', $value);
    }
}
