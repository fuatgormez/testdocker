<?php

namespace ttact\Models;

class SessionModel extends Model
{
    /**
     * properties
     */
    protected $session_id;
    protected $session;
    protected $user_id;
    protected $create_time;
    protected $last_update;

    /**
     * logic methods
     */
    public static function findByID(\ttact\Database $db, $id)
    {
        $id = (int) $id;
        $model_data = $db->getFirstRow('session', ['session_id' => $id]);
        if (isset($model_data['session_id'])) {
            return new self($db, $model_data);
        }
        return null;
    }

    public static function findCurrentSession(\ttact\Database $db, string $session_id)
    {
        $model_data = $db->getFirstRow('session', ['session' => $session_id]);
        if (isset($model_data['session_id'])) {
            return new self($db, $model_data);
        }
        return null;
    }

    public static function createNew(\ttact\Database $db, array $data)
    {
        // the data that will be inserted into the mysql table
        $insert_data = [];

        // copy all data from parameter $data into $insert_data if the respective field really exists
        foreach ($db->getFieldNames('session') as $field_name) {
            if (isset($data[$field_name]) && $field_name != 'session_id') {
                $insert_data[$field_name] = $data[$field_name];
            }
        }

        // insert $insert_data into the mysql table
        $insert_id = $db->insert('session', $insert_data);
        if ($insert_id > 0) {
            return self::findByID($db, $insert_id);
        }

        return null;
    }

    public function isRecent()
    {
        // set expiration time to 30 minutes
        $expiration_time = new \DateInterval("P0000-00-00T00:30:00");
        // expires at last_update + expiration time
        $expires_at = new \DateTime($this->last_update);
        $expires_at->add($expiration_time);
        // now
        $now = new \DateTime("now");
        // return
        return $expires_at > $now;
    }

    public function updateLastUpdate()
    {
        $now = new \DateTime("now");
        return $this->db->update('session', $this->getID(), ['last_update' => $now->format("Y-m-d H:i:s")]);
    }

    public function delete()
    {
        return $this->db->delete('session', $this->getID());
    }

    /**
     * getter methods
     */
    public function getID()
    {
        return $this->session_id;
    }

    public function getSession()
    {
        return $this->session;
    }

    public function getUserID()
    {
        return $this->user_id;
    }

    public function getCreateTime()
    {
        return $this->create_time;
    }

    public function getLastUpdate()
    {
        return $this->last_update;
    }

    /**
     * setter methods
     */

        //...
}
