<?php

namespace ttact\Models;

class UsergroupModel extends Model
{
    /**
     * properties
     */
    protected $usergroup_id;
    protected $bezeichnung;
    protected $schichtplaner_bestimmte_kunden;
    protected $schichtplaner_alle_kunden;
    protected $schichtplaner_aenderungen;
    protected $auftraege_bestimmte_kunden;
    protected $auftraege_alle_kunden;
    protected $eigenes_passwort_aendern;
    protected $kundendaten;
    protected $preise;
    protected $mitarbeiterliste;
    protected $mitarbeiter;
    protected $lohnrelevante_stunden;
    protected $notizen;
    protected $vertragliches;
    protected $tagessoll;
    protected $arbeitszeitkonto;
    protected $lohnbuchungen;
    protected $austritt_befristungen;
    protected $berechnungen_lohn;
    protected $berechnungen_stunden_bestimmte_kunden;
    protected $berechnungen_stunden_alle_kunden;
    protected $rechnungen;
    protected $benutzer_stufe0;
    protected $benutzer_stufe1;
    protected $benutzer_stufe2;
    protected $benutzer_stufe3;
    protected $benutzer_stufe4;
    protected $benutzer_stufe5;
    protected $abteilungen;
    protected $konditionen;
    protected $tarife;
    protected $dokumente_alle_kunden;
    protected $dokumente_einsehen_bestimmte_kunden;

    /**
     * logic methods
     */
    public static function findAll(\ttact\Database $db)
    {
        $return = [];

        $all = $db->getRows('usergroup', [], [], ['usergroup_id']);
        foreach ($all as $model_data) {
            if (isset($model_data['usergroup_id'])) {
                $return[] = new self($db, $model_data);
            }
        }

        return $return;
    }

    public static function findByID(\ttact\Database $db, $id)
    {
        $id = (int) $id;
        $model_data = $db->getFirstRow('usergroup', ['usergroup_id' => $id]);
        if (isset($model_data['usergroup_id'])) {
            return new self($db, $model_data);
        }
        return null;
    }

    private function setAttribute(string $attribute, string $value)
    {
        if ($this->db->update('usergroup', $this->getID(), [$attribute => $value])) {
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
        foreach ($db->getFieldNames('usergroup') as $field_name) {
            if (isset($data[$field_name]) && $field_name != 'usergroup_id') {
                $insert_data[$field_name] = $data[$field_name];
            }
        }

        // insert $insert_data into the mysql table
        $insert_id = $db->insert('usergroup', $insert_data);
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
        return $this->usergroup_id;
    }

    public function getBezeichnung()
    {
        return $this->bezeichnung;
    }

    public function hasRight($right_name)
    {
        if (property_exists($this, $right_name)) {
            return $this->$right_name == '1';
        }
        return false;
    }

    /**
     * setter methods
     */

    public function setBezeichnung(string $value)
    {
        return $this->setAttribute('bezeichnung', $value);
    }

    public function setRight($right_name, $value)
    {
        if (is_bool($value)) {
            $new_value = $value ? '1' : '0';
            if (property_exists($this, $right_name)) {
                return $this->setAttribute($right_name, $new_value);
            }
        }
        return false;
    }
}
