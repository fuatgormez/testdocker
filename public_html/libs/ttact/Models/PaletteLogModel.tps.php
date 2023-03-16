<?php

namespace ttact\Models;

class PaletteLogModel extends Model
{
    /**
     * properties
     */
    protected $palette_log_id;
    protected $action;
    protected $zeitpunkt;
    protected $palette_id;
    protected $user_id;
    protected $update_field;
    protected $update_old_value;
    protected $update_new_value;
    protected $delete_kunde_id;
    protected $delete_abteilung_id;
    protected $delete_datum;
    protected $delete_anzahl;

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
        $model_data = $db->getFirstRow('palette_log', ['palette_log_id' => $id]);
        if (isset($model_data['palette_log_id'])) {
            return new self($db, $current_user, $model_data);
        }
        return null;
    }

    public static function findLastBypaletteID(\ttact\Database $db, UserModel $current_user, int $id)
    {
        $model_data = $db->getFirstRow('palette_log', ['palette_id' => $id], [], ['zeitpunkt'], 'DESC');
        if (isset($model_data['palette_log_id'])) {
            return new self($db, $current_user, $model_data);
        }
        return null;
    }

    public static function findAllAfter(\ttact\Database $db, UserModel $current_user, \DateTime $datetime)
    {
        $return = [];

        $all = $db->getRows('palette_log', [], [['zeitpunkt', '>', $datetime->format("Y-m-d H:i:s")]], ['zeitpunkt']);
        foreach ($all as $model_data) {
            if (isset($model_data['palette_log_id'])) {
                $return[] = new self($db, $current_user, $model_data);
            }
        }

        return $return;
    }

    public static function findAll(\ttact\Database $db, UserModel $current_user)
    {
        $return = [];

        $all = $db->getRows('palette_log');
        foreach ($all as $model_data) {
            if (isset($model_data['palette_log_id'])) {
                $return[] = new self($db, $current_user, $model_data);
            }
        }

        return $return;
    }

    private function setAttribute(string $attribute, string $value)
    {
        if($this->db->update('palette_log', $this->getID(), [$attribute => $value])) {
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
        return $this->palette_log_id;
    }

    public function getAction()
    {
        return $this->action;
    }

    public function getZeitpunkt()
    {
        return $this->obj_zeitpunkt;
    }

    public function getPalette()
    {
        return PaletteModel::findByID($this->db, $this->palette_id);
    }

    public function getPaletteID()
    {
        return $this->palette_id;
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

    public function getDeleteDatum()
    {
        if ($this->delete_datum != "0000-00-00") {
            return new \DateTime($this->delete_datum . ' 00:00:00');
        }
        return null;
    }

    public function getDeleteAnzahl()
    {
        return $this->delete_anzahl;
    }

    /**
     * setter methods
     */
    public function setUserID(string $value)
    {
        return $this->setAttribute('user_id', $value);
    }
}
