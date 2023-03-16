<?php

namespace ttact\Models;

class AuftragLogModel extends Model
{
    /**
     * properties
     */
    protected $auftrag_log_id;
    protected $action;
    protected $zeitpunkt;
    protected $auftrag_id;
    protected $user_id;
    protected $update_field;
    protected $update_old_value;
    protected $update_new_value;
    protected $delete_kunde_id;
    protected $delete_abteilung_id;
    protected $delete_mitarbeiter_id;
    protected $delete_status;
    protected $delete_von;
    protected $delete_bis;
    protected $delete_pause;

    protected $current_user;

    protected $obj_zeitpunkt;

    /**
     * logic methods
     */
    public function __construct(\ttact\Database $db, UserModel $current_user, array $data)
    {
        parent::__construct($db, $data);
        $this->current_user = $current_user;
        $this->obj_zeitpunkt = new \DateTime($this->zeitpunkt);

    }

    public static function findByID(\ttact\Database $db, UserModel $current_user, $id)
    {
        $id = (int) $id;
        $model_data = $db->getFirstRow('auftrag_log', ['auftrag_log_id' => $id]);
        if (isset($model_data['auftrag_log_id'])) {
            return new self($db, $current_user, $model_data);
        }
        return null;
    }

    public static function findLastByAuftragID(\ttact\Database $db, UserModel $current_user, int $id)
    {
        $model_data = $db->getFirstRow('auftrag_log', ['auftrag_id' => $id], [], ['zeitpunkt'], 'DESC');
        if (isset($model_data['auftrag_log_id'])) {
            return new self($db, $current_user, $model_data);
        }
        return null;
    }

    public static function findAllAfter(\ttact\Database $db, UserModel $current_user, \DateTime $datetime)
    {
        $return = [];

        $all = $db->getRows('auftrag_log', [], [['zeitpunkt', '>', $datetime->format("Y-m-d H:i:s")]], ['zeitpunkt']);
        foreach ($all as $model_data) {
            if (isset($model_data['auftrag_log_id'])) {
                $return[] = new self($db, $current_user, $model_data);
            }
        }

        return $return;
    }

    public static function findTemp(\ttact\Database $db, UserModel $current_user, int $auftrag)
    {
        $model_data = $db->getRowsQuery("SELECT * FROM auftrag_log WHERE user_id != 1 AND auftrag_id = '$auftrag' AND action = 'insert' LIMIT 1");
        if (isset($model_data[0])) {
            $model_data = $model_data[0];
        }
        if (isset($model_data['auftrag_log_id'])) {
            return new self($db, $current_user, $model_data);
        }
        return null;
    }

    public static function findAll(\ttact\Database $db, UserModel $current_user)
    {
        $return = [];

        $all = $db->getRows('auftrag_log');
        foreach ($all as $model_data) {
            if (isset($model_data['auftrag_log_id'])) {
                $return[] = new self($db, $current_user, $model_data);
            }
        }

        return $return;
    }

    public static function findByDeleteMitarbeiter(\ttact\Database $db, UserModel $current_user, $mitarbeiter)
    {
        $return = [];

        $all = $db->getRows('auftrag_log', [], ['delete_mitarbeiter_id' => $mitarbeiter]);
        foreach ($all as $model_data) {
            if (isset($model_data['auftrag_log_id'])) {
                $return[] = new self($db, $current_user, $model_data);
            }
        }

        return $return;
    }

    private function setAttribute(string $attribute, string $value)
    {
        if($this->db->update('auftrag_log', $this->getID(), [$attribute => $value])) {
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
        return $this->auftrag_log_id;
    }

    public function getAction()
    {
        return $this->action;
    }

    public function getZeitpunkt()
    {
        return $this->obj_zeitpunkt;
    }

    public function getAuftrag()
    {
        return AuftragModel::findByID($this->db, $this->current_user, $this->auftrag_id);
    }

    public function getAuftragID()
    {
        return $this->auftrag_id;
    }

    public function getUser()
    {
        return UserModel::findByID($this->db, $this->user_id);
    }

    public function getUpdateField()
    {
        return $this->update_field;
    }

    public function getUpdateOldValue()
    {
        return $this->update_old_value;
    }

    public function getUpdateNewValue()
    {
        return $this->update_new_value;
    }

    public function getDeleteKunde()
    {
        return KundeModel::findByID($this->db, $this->delete_kunde_id);
    }

    public function getDeleteAbteilung()
    {
        return AbteilungModel::findByID($this->db, $this->delete_abteilung_id);
    }

    public function getDeleteMitarbeiter()
    {
        return MitarbeiterModel::findByID($this->db, $this->delete_mitarbeiter_id);
    }

    public function getDeleteStatus()
    {
        return $this->delete_status;
    }

    public function getDeleteVon()
    {
        if ($this->delete_von != "0000-00-00 00:00:00") {
            return new \DateTime($this->delete_von);
        }
        return null;
    }

    public function getDeleteBis()
    {
        if ($this->delete_bis != '0000-00-00 00:00:00') {
            return new \DateTime($this->delete_bis);
        }
        return null;
    }

    public function getDeletePause()
    {
        return new \DateInterval("P0000-00-00T" . $this->delete_pause);
    }

    /**
     * setter methods
     */
    public function setUserID(string $value)
    {
        return $this->setAttribute('user_id', $value);
    }

    public function setDeleteMitarbeiterID(string $value)
    {
        return $this->setAttribute('delete_mitarbeiter_id', $value);
    }
}
