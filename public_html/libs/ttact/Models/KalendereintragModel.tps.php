<?php

namespace ttact\Models;

class KalendereintragModel extends Model
{
    /**
     * properties
     */
    protected $kalendereintrag_id;
    protected $mitarbeiter_id;
    protected $von;
    protected $bis;
    protected $titel;
    protected $type;

    /**
     * logic methods
     */
    public static function findByID(\ttact\Database $db, $id)
    {
        $id = (int) $id;
        $model_data = $db->getFirstRow('kalendereintrag', ['kalendereintrag_id' => $id]);
        if (isset($model_data['kalendereintrag_id'])) {
            return new self($db, $model_data);
        }
        return null;
    }

    public static function findAll(\ttact\Database $db)
    {
        $return = [];

        $all = $db->getRows('kalendereintrag');
        foreach ($all as $model_data) {
            if (isset($model_data['kalendereintrag_id'])) {
                $return[] = new self($db, $model_data);
            }
        }

        return $return;
    }

    public static function findByMitarbeiter(\ttact\Database $db, $mitarbeiter)
    {
        $return = [];

        $all = $db->getRows('kalendereintrag', [], ['mitarbeiter_id' => $mitarbeiter]);
        foreach ($all as $model_data) {
            if (isset($model_data['kalendereintrag_id'])) {
                $return[] = new self($db, $model_data);
            }
        }

        return $return;
    }

    public static function findByYearWeekMitarbeiter(\ttact\Database $db, int $year, int $week, int $mitarbeiter)
    {
        $return = [];

        $first_day = new \DateTime();
        $first_day->setISODate($year, $week, 1);

        $last_day = new \DateTime();
        $last_day->setISODate($year, $week, 7);

        $a = $first_day->format('Y-m-d');
        $b = $last_day->format('Y-m-d');

        $selection = $db->getRowsQuery("SELECT * FROM kalendereintrag WHERE mitarbeiter_id = '$mitarbeiter' AND ((von >= '$a' AND von <= '$b') OR (bis >= '$a' AND bis <= '$b') OR (von < '$a' AND bis > '$b')) ORDER BY von");

        foreach ($selection as $model_data) {
            if (isset($model_data['kalendereintrag_id'])) {
                $return[] = new self($db, $model_data);
            }
        }

        return $return;
    }

    public static function findByStartEndMitarbeiter(\ttact\Database $db, \DateTimeInterface $inclusive_start, \DateTimeInterface $inclusive_end, int $mitarbeiter)
    {
        $return = [];

        $a = $inclusive_start->format('Y-m-d');
        $b = $inclusive_end->format('Y-m-d');

        $selection = $db->getRowsQuery("SELECT * FROM kalendereintrag WHERE mitarbeiter_id = '$mitarbeiter' AND ((von >= '$a' AND von <= '$b') OR (bis >= '$a' AND bis <= '$b') OR (von < '$a' AND bis > '$b')) ORDER BY von");

        foreach ($selection as $model_data) {
            if (isset($model_data['kalendereintrag_id'])) {
                $return[] = new self($db, $model_data);
            }
        }

        return $return;
    }

    public static function findByStartEndMitarbeiterLohnrelevant(\ttact\Database $db, \DateTimeInterface $inclusive_start, \DateTimeInterface $inclusive_end, int $mitarbeiter)
    {
        $return = [];

        $a = $inclusive_start->format('Y-m-d');
        $b = $inclusive_end->format('Y-m-d');

        $selection = $db->getRowsQuery("SELECT * FROM kalendereintrag WHERE mitarbeiter_id = '$mitarbeiter' AND type IN ('krank_bezahlt', 'urlaub_bezahlt') AND ((von >= '$a' AND von <= '$b') OR (bis >= '$a' AND bis <= '$b') OR (von < '$a' AND bis > '$b')) ORDER BY von");

        foreach ($selection as $model_data) {
            if (isset($model_data['kalendereintrag_id'])) {
                $return[] = new self($db, $model_data);
            }
        }

        return $return;
    }

    public static function findByStartEndTypeMitarbeiter(\ttact\Database $db, \DateTimeInterface $inclusive_start, \DateTimeInterface $inclusive_end, int $mitarbeiter, string $type)
    {
        $return = [];

        $a = $inclusive_start->format('Y-m-d');
        $b = $inclusive_end->format('Y-m-d');

        $selection = $db->getRowsQuery("SELECT * FROM kalendereintrag WHERE mitarbeiter_id = '$mitarbeiter' AND type = '$type' AND ((von >= '$a' AND von <= '$b') OR (bis >= '$a' AND bis <= '$b') OR (von < '$a' AND bis > '$b')) ORDER BY von");

        foreach ($selection as $model_data) {
            if (isset($model_data['kalendereintrag_id'])) {
                $return[] = new self($db, $model_data);
            }
        }

        return $return;
    }

    public static function findByTypeDateMitarbeiter(\ttact\Database $db, string $type, \DateTimeInterface $date, int $mitarbeiter)
    {
        $d = $date->format('Y-m-d');

        $selection = $db->getRowsQuery("SELECT * FROM kalendereintrag WHERE mitarbeiter_id = '$mitarbeiter' AND type = '$type' AND von <= '$d' AND bis >= '$d' LIMIT 1");

        foreach ($selection as $model_data) {
            if (isset($model_data['kalendereintrag_id'])) {
                return new self($db, $model_data);
            }
        }

        return null;
    }

    public static function findAllByTypeDateMitarbeiter(\ttact\Database $db, string $type, \DateTimeInterface $date, int $mitarbeiter)
    {
        $return = [];

        $d = $date->format('Y-m-d');

        $selection = $db->getRowsQuery("SELECT * FROM kalendereintrag WHERE mitarbeiter_id = '$mitarbeiter' AND type = '$type' AND von <= '$d' AND bis >= '$d' LIMIT 1");

        foreach ($selection as $model_data) {
            if (isset($model_data['kalendereintrag_id'])) {
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
        foreach ($db->getFieldNames('kalendereintrag') as $field_name) {
            if (isset($data[$field_name]) && $field_name != 'kalendereintrag_id') {
                $insert_data[$field_name] = $data[$field_name];
            }
        }

        // insert $insert_data into the mysql table
        $insert_id = $db->insert('kalendereintrag', $insert_data);
        if ($insert_id > 0) {
            return self::findByID($db, $insert_id);
        }

        return null;
    }

    private function setAttribute(string $attribute, string $value)
    {
        if($this->db->update('kalendereintrag', $this->getID(), [$attribute => $value])) {
            $this->{$attribute} = $value;
            return true;
        }

        return false;
    }

    public function delete()
    {
        if ($this->db->delete('kalendereintrag', $this->getID())) {
            return true;
        }

        return false;
    }

    /**
     * getter methods
     */
    public function getID()
    {
        return $this->kalendereintrag_id;
    }

    public function getMitarbeiter()
    {
        return MitarbeiterModel::findByID($this->db, $this->mitarbeiter_id);
    }

    public function getVon()
    {
        return new \DateTime($this->von . " 00:00:00");
    }

    public function getBis()
    {
        return new \DateTime($this->bis . " 23:59:59");
    }

    public function getTitel()
    {
        return $this->titel;
    }

    public function getType()
    {
        return $this->type;
    }

    /**
     * setter methods
     */
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

    public function setTitel(string $value)
    {
        return $this->setAttribute('titel', $value);
    }

    public function setType(string $value)
    {
        return $this->setAttribute('type', $value);
    }
}
