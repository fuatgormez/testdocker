<?php

namespace ttact\Models;

class UserModel extends Model
{
    /**
     * properties
     */
    protected $user_id;
    protected $username;
    protected $password;
    protected $name;
    protected $usergroup_id;
    protected $enabled;

    public $kunde_id;

    /**
     * logic methods
     */
    public static function findAll(\ttact\Database $db)
    {
        $return = [];

        $all = $db->getRows('user', [], [], ['user_id']);
        foreach ($all as $model_data) {
            if (isset($model_data['user_id'])) {
                $return[] = new self($db, $model_data);
            }
        }

        return $return;
    }

    public static function findByID(\ttact\Database $db, $id)
    {
        $id = (int) $id;
        $model_data = $db->getFirstRow('user', ['user_id' => $id]);
        if (isset($model_data['user_id'])) {
            return new self($db, $model_data);
        }
        return null;
    }

    public static function findByUsername(\ttact\Database $db, $username)
    {
        $model_data = $db->getFirstRow('user', ['username' => $username]);
        if (isset($model_data['user_id'])) {
            return new self($db, $model_data);
        }
        return null;
    }

    public static function findCurrentUser(\ttact\Database $db, SessionModel $current_session)
    {
        $model_data = $db->getFirstRow('user', ['user_id' => $current_session->getUserID()]);
        if (isset($model_data['user_id'])) {
            if ($model_data['enabled'] == '1') {
                return new self($db, $model_data);
            }
        }
        return null;
    }

    public static function findByCredentials(\ttact\Database $db, \ttact\PasswordUtils $password_utils, string $username, string $password)
    {
        $model_data = $db->getFirstRow('user', ['username' => $username]);
        if (isset($model_data['user_id'])) {
            if ($password_utils->isCorrect($password, $model_data['password']) && $model_data['enabled'] == '1') {
                return new self($db, $model_data);
            }
        }
        return null;
    }

    private function setAttribute(string $attribute, string $value)
    {
        if ($this->db->update('user', $this->getID(), [$attribute => $value])) {
            $this->{$attribute} = $value;
            return true;
        }

        return false;
    }

    public static function createNew(\ttact\Database $db, array $data)
    {
        // the data that will be inserted into the mysql table
        $insert_data = [];

        // copy all data from parameter $data into $insert_data if the respective field really exists
        foreach ($db->getFieldNames('user') as $field_name) {
            if (isset($data[$field_name]) && $field_name != 'user_id') {
                $insert_data[$field_name] = $data[$field_name];
            }
        }

        // insert $insert_data into the mysql table
        $insert_id = $db->insert('user', $insert_data);
        if ($insert_id > 0) {
            return self::findByID($db, $insert_id);
        }

        return null;
    }

    /**
     * getter methods
     */
    public function getID()
    {
        return $this->user_id;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getUsergroup()
    {
        if ($this->usergroup_id != '') {
            return \ttact\Models\UsergroupModel::findByID($this->db, $this->usergroup_id);
        }
        return null;
    }

    public function getKundenbeschraenkungen()
    {
        return KundenbeschraenkungModel::findAllByUserID($this->db, $this->getID());
    }

    public function isEnabled()
    {
        return $this->enabled == '1';
    }

    /**
     * setter methods
     */

    public function setUsername(string $value)
    {
        return $this->setAttribute('username', $value);
    }

    public function setPassword(string $value)
    {
        return $this->setAttribute('password', $value);
    }

    public function setName(string $value)
    {
        return $this->setAttribute('name', $value);
    }

    public function setUsergroupID(string $value)
    {
        return $this->setAttribute('usergroup_id', $value);
    }

    /**
     * @param array $kunden An array of KundeModel instances.
     * @return bool Success.
     */
    public function setKundenbeschraenkungen(array $kunden)
    {
        $success = true;

        foreach ($this->getKundenbeschraenkungen() as $kundenbeschraenkung) {
            if (!$kundenbeschraenkung->delete()) {
                $success = false;
            }
        }

        foreach ($kunden as $kunde) {
            $data = [
                'user_id' => $this->getID(),
                'kunde_id' => $kunde->getID()
            ];
            $kundenbeschraenkung_model = KundenbeschraenkungModel::createNew($this->db, $data);
            if (!$kundenbeschraenkung_model instanceof KundenbeschraenkungModel) {
                $success = false;
            }
        }

        return $success;
    }

    public function setEnabled(string $value)
    {
        return $this->setAttribute('enabled', $value);
    }
}
