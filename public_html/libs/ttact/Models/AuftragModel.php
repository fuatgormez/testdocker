<?php

namespace ttact\Models;

class AuftragModel extends Model
{
    /**
     * properties
     */
    protected $auftrag_id;
    protected $kunde_id;
    protected $abteilung_id;
    protected $mitarbeiter_id;
    protected $status;
    protected $von;
    protected $bis;
    protected $pause;
    protected $zusatzschicht;

    protected $current_user;

    /**
     * logic methods
     */
    public function __construct(\ttact\Database $db, UserModel $current_user, array $data)
    {
        parent::__construct($db, $data);
        $this->current_user = $current_user;
    }

    public static function findByID(\ttact\Database $db, UserModel $current_user, $id)
    {
        $id = (int) $id;
        $model_data = $db->getFirstRow('auftrag', ['auftrag_id' => $id]);
        if (isset($model_data['auftrag_id'])) {
            return new self($db, $current_user, $model_data);
        }
        return null;
    }

    public static function findAll(\ttact\Database $db, UserModel $current_user)
    {
        $return = [];

        $all = $db->getRows('auftrag');
        foreach ($all as $model_data) {
            if (isset($model_data['auftrag_id'])) {
                $return[] = new self($db, $current_user, $model_data);
            }
        }

        return $return;
    }

    public static function findByMitarbeiter(\ttact\Database $db, UserModel $current_user, $mitarbeiter)
    {
        $return = [];

        $all = $db->getRows('auftrag', [], ['mitarbeiter_id' => $mitarbeiter]);
        foreach ($all as $model_data) {
            if (isset($model_data['auftrag_id'])) {
                $return[] = new self($db, $current_user, $model_data);
            }
        }

        return $return;
    }

    public static function findByYearWeekKunden(\ttact\Database $db, UserModel $current_user, int $year, int $week, array $kunden)
    {
        $return = [];

        $first_day = new \DateTime();
        $first_day->setISODate($year, $week, 1);
        $first_day->setTime(0, 0, 0);

        $last_day = new \DateTime();
        $last_day->setISODate($year, $week, 7);
        $last_day->setTime(23, 59, 59);

        if (count($kunden) > 0) {
            $selection = $db->getRows('auftrag', [], [['von', '>=', $first_day->format('Y-m-d H:i:s')], ['von', '<=', $last_day->format('Y-m-d H:i:s')], ['kunde_id', $kunden]], ['kunde_id', 'abteilung_id', 'von']);
        } else {
            $selection = $db->getRows('auftrag', [], [['von', '>=', $first_day->format('Y-m-d H:i:s')], ['von', '<=', $last_day->format('Y-m-d H:i:s')]], ['kunde_id', 'abteilung_id', 'von']);
        }

        foreach ($selection as $model_data) {
            if (isset($model_data['auftrag_id'])) {
                $return[] = new self($db, $current_user, $model_data);
            }
        }

        return $return;
    }

    public static function findByYearMonth(\ttact\Database $db, UserModel $current_user, int $year, int $month)
    {
        $return = [];

        $first_day = new \DateTime();
        $first_day->setDate($year, $month, 1);
        $first_day->setTime(0, 0, 0);

        $last_day = new \DateTime();
        $last_day->setDate($year, $month, (int) $first_day->format("t"));
        $last_day->setTime(23, 59, 59);

        $selection = $db->getRows('auftrag', [], [['von', '>=', $first_day->format('Y-m-d H:i:s')], ['von', '<=', $last_day->format('Y-m-d H:i:s')]], ['kunde_id', 'abteilung_id', 'von']);

        foreach ($selection as $model_data) {
            if (isset($model_data['auftrag_id'])) {
                $return[] = new self($db, $current_user, $model_data);
            }
        }

        return $return;
    }

    public static function findByYearMonthKunde(\ttact\Database $db, UserModel $current_user, int $year, int $month, $kunde)
    {
        $return = [];

        $first_day = new \DateTime();
        $first_day->setDate($year, $month, 1);
        $first_day->setTime(0, 0, 0);

        $last_day = new \DateTime();
        $last_day->setDate($year, $month, (int) $first_day->format("t"));
        $last_day->setTime(23, 59, 59);

        $selection = $db->getRows('auftrag', [], [['von', '>=', $first_day->format('Y-m-d H:i:s')], ['von', '<=', $last_day->format('Y-m-d H:i:s')], 'kunde_id' => $kunde], ['kunde_id', 'abteilung_id', 'von']);

        foreach ($selection as $model_data) {
            if (isset($model_data['auftrag_id'])) {
                $return[] = new self($db, $current_user, $model_data);
            }
        }

        return $return;
    }

    public static function findByYearWeekKundenMitarbeiter(\ttact\Database $db, UserModel $current_user, int $year, int $week, int $kunde, array $mitarbeiter)
    {
        $return = [];

        $first_day = new \DateTime();
        $first_day->setISODate($year, $week, 1);
        $first_day->setTime(0, 0, 0);

        $last_day = new \DateTime();
        $last_day->setISODate($year, $week, 7);
        $last_day->setTime(23, 59, 59);

        $selection = $db->getRows('auftrag', [], [['von', '>=', $first_day->format('Y-m-d H:i:s')], ['von', '<=', $last_day->format('Y-m-d H:i:s')], 'kunde_id' => $kunde, ['mitarbeiter_id', $mitarbeiter]], ['von']);

        foreach ($selection as $model_data) {
            if (isset($model_data['auftrag_id'])) {
                $return[] = new self($db, $current_user, $model_data);
            }
        }

        return $return;
    }

    public static function findByYearWeekKundenMitarbeiterIDs(\ttact\Database $db, UserModel $current_user, int $year, int $week, string $kunde, array $mitarbeiter, array $ids)
    {
        $return = [];

        $first_day = new \DateTime();
        $first_day->setISODate($year, $week, 1);
        $first_day->setTime(0, 0, 0);

        $last_day = new \DateTime();
        $last_day->setISODate($year, $week, 7);
        $last_day->setTime(23, 59, 59);

        $selection = $db->getRows('auftrag', [], [['von', '>=', $first_day->format('Y-m-d H:i:s')], ['von', '<=', $last_day->format('Y-m-d H:i:s')], 'kunde_id' => $kunde, ['mitarbeiter_id', $mitarbeiter], ['auftrag_id', $ids]], ['von']);

        foreach ($selection as $model_data) {
            if (isset($model_data['auftrag_id'])) {
                $return[] = new self($db, $current_user, $model_data);
            }
        }

        return $return;
    }

    public static function findByMitarbeiterIDs(\ttact\Database $db, UserModel $current_user, int $mitarbeiter, array $ids)
    {
        $return = [];

        $selection = $db->getRows('auftrag', [], ['mitarbeiter_id' => $mitarbeiter, ['auftrag_id', $ids]], ['von']);

        foreach ($selection as $model_data) {
            if (isset($model_data['auftrag_id'])) {
                $return[] = new self($db, $current_user, $model_data);
            }
        }

        return $return;
    }

    public static function findByYearWeekMitarbeiter(\ttact\Database $db, UserModel $current_user, int $year, int $week, array $mitarbeiter)
    {
        $return = [];

        $first_day = new \DateTime();
        $first_day->setISODate($year, $week, 1);
        $first_day->setTime(0, 0, 0);

        $last_day = new \DateTime();
        $last_day->setISODate($year, $week, 7);
        $last_day->setTime(23, 59, 59);

        $selection = $db->getRows('auftrag', [], [['von', '>=', $first_day->format('Y-m-d H:i:s')], ['von', '<=', $last_day->format('Y-m-d H:i:s')], ['mitarbeiter_id', $mitarbeiter]], ['von']);

        foreach ($selection as $model_data) {
            if (isset($model_data['auftrag_id'])) {
                $return[] = new self($db, $current_user, $model_data);
            }
        }

        return $return;
    }

    public static function countByYearWeekKunden(\ttact\Database $db, int $year, int $week, array $kunden)
    {
        $return = -1;

        $first_day = new \DateTime();
        $first_day->setISODate($year, $week, 1);
        $first_day->setTime(0, 0, 0);

        $last_day = new \DateTime();
        $last_day->setISODate($year, $week, 7);
        $last_day->setTime(23, 59, 59);

        if (count($kunden) > 0) {
            $return = $db->getNumOfRows('auftrag', [['von', '>=', $first_day->format('Y-m-d H:i:s')], ['von', '<=', $last_day->format('Y-m-d H:i:s')], ['kunde_id', $kunden]]);
        } else {
            $return = $db->getNumOfRows('auftrag', [['von', '>=', $first_day->format('Y-m-d H:i:s')], ['von', '<=', $last_day->format('Y-m-d H:i:s')]]);
        }

        return $return;
    }

    public static function countByYearWeekKundenStatus(\ttact\Database $db, int $year, int $week, array $kunden, string $status)
    {
        $return = -1;

        $first_day = new \DateTime();
        $first_day->setISODate($year, $week, 1);
        $first_day->setTime(0, 0, 0);

        $last_day = new \DateTime();
        $last_day->setISODate($year, $week, 7);
        $last_day->setTime(23, 59, 59);

        if (count($kunden) > 0) {
            $return = $db->getNumOfRows('auftrag', ['status' => $status, ['von', '>=', $first_day->format('Y-m-d H:i:s')], ['von', '<=', $last_day->format('Y-m-d H:i:s')], ['kunde_id', $kunden]]);
        } else {
            $return = $db->getNumOfRows('auftrag', ['status' => $status, ['von', '>=', $first_day->format('Y-m-d H:i:s')], ['von', '<=', $last_day->format('Y-m-d H:i:s')]]);
        }

        return $return;
    }

    public static function findByStartEndMitarbeiter(\ttact\Database $db, \ttact\Models\UserModel $current_user, \DateTimeInterface $inclusive_start, \DateTimeInterface $inclusive_end, int $mitarbeiter)
    {
        $return = [];

        $a = $inclusive_start->format('Y-m-d H:i:s');
        $b = $inclusive_end->format('Y-m-d H:i:s');

        $selection = $db->getRowsQuery("SELECT * FROM auftrag WHERE mitarbeiter_id = '$mitarbeiter' AND ((von >= '$a' AND von <= '$b') OR (bis >= '$a' AND bis <= '$b') OR (von < '$a' AND bis > '$b')) ORDER BY von");

        foreach ($selection as $model_data) {
            if (isset($model_data['auftrag_id'])) {
                $return[] = new self($db, $current_user, $model_data);
            }
        }

        return $return;
    }

    public static function findByStartEndKundeRechnungen(\ttact\Database $db, \ttact\Models\UserModel $current_user, \DateTimeInterface $start, \DateTimeInterface $end, int $kunde)
    {
        $return = [];

        $a = $start->format('Y-m-d') . " 00:00:00";
        $b = $end->format('Y-m-d') . " 23:59:59";

        $selection = $db->getRowsQuery("SELECT auftrag.* FROM auftrag, abteilung WHERE auftrag.abteilung_id = abteilung.abteilung_id AND auftrag.kunde_id = '$kunde' AND auftrag.von >= '$a' AND auftrag.von <= '$b' AND abteilung.in_rechnung_stellen = 1 ORDER BY auftrag.von");

        foreach ($selection as $model_data) {
            if (isset($model_data['auftrag_id'])) {
                $return[] = new self($db, $current_user, $model_data);
            }
        }

        return $return;
    }

    public static function findMitarbeiterLohnberechnungByStartEndKunde(\ttact\Database $db, \DateTimeInterface $inclusive_start, \DateTimeInterface $inclusive_end, int $kunde = -1)
    {
        $return = [];

        $a = $inclusive_start->format('Y-m-d') . " 00:00:00";
        $b = $inclusive_end->format('Y-m-d') . " 23:59:59";

        if ($kunde > 0) {
            $selection = $db->getRowsQuery("SELECT auftrag.mitarbeiter_id FROM auftrag, mitarbeiter, abteilung WHERE auftrag.mitarbeiter_id = mitarbeiter.mitarbeiter_id AND auftrag.abteilung_id = abteilung.abteilung_id AND mitarbeiter.eintritt != '0000-00-00' AND auftrag.von >= '$a' AND auftrag.von <= '$b' AND abteilung.in_rechnung_stellen = 1 AND auftrag.kunde_id = '$kunde' GROUP BY auftrag.mitarbeiter_id");
        } else {
            $selection = $db->getRowsQuery("SELECT auftrag.mitarbeiter_id FROM auftrag, mitarbeiter, abteilung WHERE auftrag.mitarbeiter_id = mitarbeiter.mitarbeiter_id AND auftrag.abteilung_id = abteilung.abteilung_id AND mitarbeiter.eintritt != '0000-00-00' AND auftrag.von >= '$a' AND auftrag.von <= '$b' AND abteilung.in_rechnung_stellen = 1 GROUP BY auftrag.mitarbeiter_id");
        }

        foreach ($selection as $row) {
            $mitarbeiter_model = MitarbeiterModel::findByID($db, $row['mitarbeiter_id']);
            if ($mitarbeiter_model instanceof MitarbeiterModel) {
                $return[] = $mitarbeiter_model;
            }
        }

        return $return;
    }

    public static function findAbteilungenByStartEndMitarbeiterKunde(\ttact\Database $db, \DateTimeInterface $inclusive_start, \DateTimeInterface $inclusive_end, int $mitarbeiter = -1, int $kunde = -1)
    {
        $return = [];

        $a = $inclusive_start->format('Y-m-d') . " 00:00:00";
        $b = $inclusive_end->format('Y-m-d') . " 23:59:59";

        if ($kunde > 0) {
            $selection = $db->getRowsQuery("SELECT auftrag.abteilung_id FROM auftrag, abteilung WHERE auftrag.abteilung_id = abteilung.abteilung_id AND auftrag.von >= '$a' AND auftrag.von <= '$b' AND abteilung.in_rechnung_stellen = 1 AND auftrag.mitarbeiter_id = '$mitarbeiter' AND auftrag.kunde_id = '$kunde' GROUP BY auftrag.abteilung_id");
        } else {
            $selection = $db->getRowsQuery("SELECT auftrag.abteilung_id FROM auftrag, abteilung WHERE auftrag.abteilung_id = abteilung.abteilung_id AND auftrag.von >= '$a' AND auftrag.von <= '$b' AND abteilung.in_rechnung_stellen = 1 AND auftrag.mitarbeiter_id = '$mitarbeiter' GROUP BY auftrag.abteilung_id");
        }

        foreach ($selection as $row) {
            $abteilung_model = AbteilungModel::findByID($db, $row['abteilung_id']);
            if ($abteilung_model instanceof AbteilungModel) {
                $return[] = $abteilung_model;
            }
        }

        return $return;
    }

    public static function findCountByStartEndMitarbeiter(\ttact\Database $db, \DateTimeInterface $inclusive_start, \DateTimeInterface $inclusive_end, int $mitarbeiter)
    {
        $return = [];

        $a = $inclusive_start->format('Y-m-d') . " 00:00:00";
        $b = $inclusive_end->format('Y-m-d') . " 23:59:59";

        $selection = $db->getRowsQuery("SELECT auftrag.kunde_id, COUNT(auftrag.kunde_id) AS count FROM auftrag, abteilung WHERE auftrag.abteilung_id = abteilung.abteilung_id AND auftrag.von >= '$a' AND auftrag.von <= '$b' AND abteilung.in_rechnung_stellen = 1 AND auftrag.mitarbeiter_id = '$mitarbeiter' GROUP BY auftrag.kunde_id ORDER BY count DESC");

        foreach ($selection as $row) {
            $kunde_model = \ttact\Models\KundeModel::findByID($db, $row['kunde_id']);
            $return[] = [$row['count'], $kunde_model];
        }

        return $return;
    }

    /***************************/
    public static function findByStartEndLohnberechnung(\ttact\Database $db, \ttact\Models\UserModel $current_user, \DateTimeInterface $inclusive_start, \DateTimeInterface $inclusive_end)
    {
        $return = [];

        $a = $inclusive_start->format('Y-m-d H:i:s');
        $b = $inclusive_end->format('Y-m-d H:i:s');

        $selection = $db->getRowsQuery("SELECT * FROM auftrag WHERE von >= '$a' AND von <= '$b' ORDER BY mitarbeiter_id, von");

        foreach ($selection as $model_data) {
            if (isset($model_data['auftrag_id'])) {
                if ($model_data['status'] == 'archiviert') {

                }
                $return[] = new self($db, $current_user, $model_data);
            }
        }

        return $return;
    }

    public static function findByStartEndKundenLohnberechnung(\ttact\Database $db, \ttact\Models\UserModel $current_user, \DateTimeInterface $start, \DateTimeInterface $end, array $kunden_ids)
    {
        $return = [];

        $a = $start->format('Y-m-d') . " 00:00:00";
        $b = $end->format('Y-m-d') . " 23:59:59";

        $selection = $db->getRowsQuery("SELECT * FROM auftrag WHERE kunde_id IN (" . implode($kunden_ids, ', ') . ") AND von >= '$a' AND von <= '$b' ORDER BY mitarbeiter_id, von");

        foreach ($selection as $model_data) {
            if (isset($model_data['auftrag_id'])) {
                $return[] = new self($db, $current_user, $model_data);
            }
        }

        return $return;
    }

    public static function findByStartEndAbteilungLohnberechnung(\ttact\Database $db, \ttact\Models\UserModel $current_user, \DateTimeInterface $inclusive_start, \DateTimeInterface $inclusive_end, int $abteilung_id)
    {
        $return = [];

        $a = $inclusive_start->format('Y-m-d H:i:s');
        $b = $inclusive_end->format('Y-m-d H:i:s');

        $selection = $db->getRowsQuery("SELECT * FROM auftrag WHERE abteilung_id = '$abteilung_id' AND von >= '$a' AND von <= '$b' ORDER BY mitarbeiter_id, von");

        foreach ($selection as $model_data) {
            if (isset($model_data['auftrag_id'])) {
                $return[] = new self($db, $current_user, $model_data);
            }
        }

        return $return;
    }

    public static function findByStartEndMitarbeiterLohnberechnung(\ttact\Database $db, \ttact\Models\UserModel $current_user, \DateTimeInterface $inclusive_start, \DateTimeInterface $inclusive_end, int $mitarbeiter)
    {
        $return = [];

        $a = $inclusive_start->format('Y-m-d H:i:s');
        $b = $inclusive_end->format('Y-m-d H:i:s');

        $selection = $db->getRowsQuery("SELECT * FROM auftrag WHERE mitarbeiter_id = '$mitarbeiter' AND von >= '$a' AND von <= '$b' ORDER BY mitarbeiter_id, von");

        foreach ($selection as $model_data) {
            if (isset($model_data['auftrag_id'])) {
                $return[] = new self($db, $current_user, $model_data);
            }
        }

        return $return;
    }

    public static function findByStartEndKundenAbteilungLohnberechnung(\ttact\Database $db, \ttact\Models\UserModel $current_user, \DateTimeInterface $inclusive_start, \DateTimeInterface $inclusive_end, array $kunden_ids, int $abteilung_id)
    {
        $return = [];

        $a = $inclusive_start->format('Y-m-d H:i:s');
        $b = $inclusive_end->format('Y-m-d H:i:s');

        $selection = $db->getRowsQuery("SELECT * FROM auftrag WHERE kunde_id IN (" . implode($kunden_ids, ', ') . ") AND abteilung_id = '$abteilung_id' AND von >= '$a' AND von <= '$b' ORDER BY mitarbeiter_id, von");

        foreach ($selection as $model_data) {
            if (isset($model_data['auftrag_id'])) {
                $return[] = new self($db, $current_user, $model_data);
            }
        }

        return $return;
    }

    public static function findByStartEndKundenMitarbeiterLohnberechnung(\ttact\Database $db, \ttact\Models\UserModel $current_user, \DateTimeInterface $inclusive_start, \DateTimeInterface $inclusive_end, array $kunden_ids, int $mitarbeiter_id)
    {
        $return = [];

        $a = $inclusive_start->format('Y-m-d H:i:s');
        $b = $inclusive_end->format('Y-m-d H:i:s');

        $selection = $db->getRowsQuery("SELECT * FROM auftrag WHERE mitarbeiter_id = '$mitarbeiter_id' AND kunde_id IN (" . implode($kunden_ids, ', ') . ") AND von >= '$a' AND von <= '$b' ORDER BY mitarbeiter_id, von");

        foreach ($selection as $model_data) {
            if (isset($model_data['auftrag_id'])) {
                $return[] = new self($db, $current_user, $model_data);
            }
        }

        return $return;
    }

    public static function findByStartEndAbteilungMitarbeiterLohnberechnung(\ttact\Database $db, \ttact\Models\UserModel $current_user, \DateTimeInterface $inclusive_start, \DateTimeInterface $inclusive_end, int $abteilung_id, int $mitarbeiter_id)
    {
        $return = [];

        $a = $inclusive_start->format('Y-m-d H:i:s');
        $b = $inclusive_end->format('Y-m-d H:i:s');

        $selection = $db->getRowsQuery("SELECT * FROM auftrag WHERE mitarbeiter_id = '$mitarbeiter_id' AND abteilung_id = '$abteilung_id' AND von >= '$a' AND von <= '$b' ORDER BY mitarbeiter_id, von");

        foreach ($selection as $model_data) {
            if (isset($model_data['auftrag_id'])) {
                $return[] = new self($db, $current_user, $model_data);
            }
        }

        return $return;
    }

    public static function findByStartEndKundenAbteilungMitarbeiterLohnberechnung(\ttact\Database $db, \ttact\Models\UserModel $current_user, \DateTimeInterface $inclusive_start, \DateTimeInterface $inclusive_end, array $kunden_ids, int $abteilung_id, int $mitarbeiter_id)
    {
        $return = [];

        $a = $inclusive_start->format('Y-m-d H:i:s');
        $b = $inclusive_end->format('Y-m-d H:i:s');

        $selection = $db->getRowsQuery("SELECT * FROM auftrag WHERE kunde_id IN (" . implode($kunden_ids, ', ') . ") AND abteilung_id = '$abteilung_id' AND mitarbeiter_id = '$mitarbeiter_id' AND von >= '$a' AND von <= '$b' ORDER BY mitarbeiter_id, von");

        foreach ($selection as $model_data) {
            if (isset($model_data['auftrag_id'])) {
                $return[] = new self($db, $current_user, $model_data);
            }
        }

        return $return;
    }
    /***************************/

    public static function findFirstByKundeMitarbeiter(\ttact\Database $db, \ttact\Models\UserModel $current_user, int $kunde_id, int $mitarbeiter_id)
    {
        $selection = $db->getRowsQuery("SELECT * FROM auftrag WHERE kunde_id = '" . $kunde_id . "' AND mitarbeiter_id = '$mitarbeiter_id' ORDER BY von LIMIT 1");

        if (isset($selection[0]['auftrag_id'])) {
            return new self($db, $current_user, $selection[0]);
        }

        return \null;
    }

    public static function createNew(\ttact\Database $db, UserModel $current_user, array $data)
    {
        // the data that will be inserted into the mysql table
        $insert_data = [];

        // copy all data from parameter $data into $insert_data if the respective field really exists
        foreach ($db->getFieldNames('auftrag') as $field_name) {
            if (isset($data[$field_name]) && $field_name != 'auftrag_id') {
                $insert_data[$field_name] = $data[$field_name];
            }
        }

        // insert $insert_data into the mysql table
        $insert_id = $db->insert('auftrag', $insert_data);
        if ($insert_id > 0) {
            $log = AuftragLogModel::findLastByAuftragID($db, $current_user, $insert_id);
            if ($log instanceof AuftragLogModel) {
                $log->setUserID($current_user->getID());
            }
            return self::findByID($db, $current_user, $insert_id);
        }

        return null;
    }

    private function setAttribute(string $attribute, string $value)
    {
        if($this->db->update('auftrag', $this->getID(), [$attribute => $value])) {
            $this->{$attribute} = $value;
            $log = AuftragLogModel::findLastByAuftragID($this->db, $this->current_user, $this->getID());
            if ($log instanceof AuftragLogModel) {
                $log->setUserID($this->current_user->getID());
            }
            return true;
        }

        return false;
    }

    public function delete()
    {
        if ($this->db->delete('auftrag', $this->getID())) {
            $log = AuftragLogModel::findLastByAuftragID($this->db, $this->current_user, $this->getID());
            if ($log instanceof AuftragLogModel) {
                $log->setUserID($this->current_user->getID());
            }
            return true;
        }

        return false;
    }

    /**
     * getter methods
     */
    public function getID()
    {
        return $this->auftrag_id;
    }

    public function getKunde()
    {
        return KundeModel::findByID($this->db, $this->kunde_id);
    }

    public function getAbteilung()
    {
        return AbteilungModel::findByID($this->db, $this->abteilung_id);
    }

    public function getMitarbeiter()
    {
        return MitarbeiterModel::findByID($this->db, $this->mitarbeiter_id);
    }

    public function getStatus()
    {
        return $this->status;
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

    public function getZusatzschicht()
    {
        return $this->zusatzschicht == '1';
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

    public function setMitarbeiterID(string $value)
    {
        return $this->setAttribute('mitarbeiter_id', $value);
    }

    public function setStatus(string $value)
    {
        return $this->setAttribute('status', $value);
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

    public function setZusatzschicht(string $value)
    {
        if ($value == '0' || $value == '1') {
            return $this->setAttribute('zusatzschicht', $value);
        }
        return false;
    }
}
