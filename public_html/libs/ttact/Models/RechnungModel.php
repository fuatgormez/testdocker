<?php

namespace ttact\Models;

class RechnungModel extends Model
{
    /**
     * properties
     */
    protected $rechnung_id;
    protected $kunde_id;
    protected $stornierungsdatum;
    protected $bezahlt_am;
    protected $nettobetrag;
    protected $mehrwertsteuer;
    protected $bruttobetrag;
    protected $rechnungsdatum;
    protected $rechnungsnummer;
    protected $zeitraum_von;
    protected $zeitraum_bis;
    protected $zahlungsziel;
    protected $kassendifferenz;
    protected $alternative_anrede;
    protected $kommentar;
    protected $alte_rechnung_id;

    /**
     * logic methods
     */
    public static function findByID(\ttact\Database $db, $id)
    {
        $id = (int) $id;
        $model_data = $db->getFirstRow('rechnung', ['rechnung_id' => $id]);
        if (isset($model_data['rechnung_id'])) {
            return new self($db, $model_data);
        }
        return null;
    }

    public static function findAll(\ttact\Database $db)
    {
        $return = [];

        $all = $db->getRows('rechnung');
        foreach ($all as $model_data) {
            if (isset($model_data['rechnung_id'])) {
                $return[] = new self($db, $model_data);
            }
        }

        return $return;
    }

    public static function findAllByMonth(\ttact\Database $db, \DateTime $month)
    {
        $return = [];

        $all = $db->getRowsQuery("SELECT * FROM rechnung WHERE  zeitraum_von LIKE '".$month->format("Y-m")."%' ORDER BY rechnung_id DESC");

        foreach ($all as $model_data) {
            if (isset($model_data['rechnung_id'])) {
                $return[] = new self($db, $model_data);
            }
        }
        return $return;
    }

	public static function findBezahltamByMonth(\ttact\Database $db, \DateTime $month)
    {
        $return = [];

        $all = $db->getRowsQuery("SELECT * FROM rechnung WHERE bezahlt_am != '0000-00-00' AND zeitraum_von LIKE '".$month->format("Y-m")."%' ORDER BY rechnung_id DESC");

        foreach ($all as $model_data) {
            if (isset($model_data['rechnung_id'])) {
                $return[] = new self($db, $model_data);
            }
        }
        return $return;
    }

	public static function findUnBezahltamByMonth(\ttact\Database $db, \DateTime $month)
    {
        $return = [];

        $all = $db->getRowsQuery("SELECT * FROM rechnung WHERE bezahlt_am = '0000-00-00' AND zeitraum_von LIKE '".$month->format("Y-m")."%' ORDER BY rechnung_id DESC");

        foreach ($all as $model_data) {
            if (isset($model_data['rechnung_id'])) {
                $return[] = new self($db, $model_data);
            }
        }
        return $return;
    }

	public static function findStornoByMonth(\ttact\Database $db, \DateTime $month)
    {
        $return = [];

        $all = $db->getRowsQuery("SELECT * FROM rechnung WHERE stornierungsdatum != '0000-00-00' AND zeitraum_von LIKE '".$month->format("Y-m")."%' ORDER BY rechnung_id DESC");

        foreach ($all as $model_data) {
            if (isset($model_data['rechnung_id'])) {
                $return[] = new self($db, $model_data);
            }
        }
        return $return;
    }

    public static function findLastByYear(\ttact\Database $db, \DateTime $year)
    {
        $model_data = $db->getRowsQuery("SELECT * FROM rechnung WHERE stornierungsdatum = '0000-00-00' AND zeitraum_von LIKE '".$year->format("Y")."%' ORDER BY rechnungsnummer DESC LIMIT 1");
        if (isset($model_data[0]['rechnung_id'])) {
            return new self($db, $model_data[0]);
        }
        return null;
    }

    public static function createNew(\ttact\Database $db, array $data)
    {
        // the data that will be inserted into the mysql table
        $insert_data = [];

        // copy all data from parameter $data into $insert_data if the respective field really exists
        foreach ($db->getFieldNames('rechnung') as $field_name) {
            if (isset($data[$field_name]) && $field_name != 'rechnung_id') {
                $insert_data[$field_name] = $data[$field_name];
            }
        }

        // insert $insert_data into the mysql table
        $insert_id = $db->insert('rechnung', $insert_data);
        if ($insert_id > 0) {
            return self::findByID($db, $insert_id);
        }

        return null;
    }

    public static function createDummyObjectWithoutSaving(\ttact\Database $db, array $data)
    {
        $data['rechnung_id'] = -1;
        return new self($db, $data);
    }

    private function setAttribute(string $attribute, string $value)
    {
        if ($this->db->update('rechnung', $this->getID(), [$attribute => $value])) {
            $this->{$attribute} = $value;
            return true;
        }

        return false;
    }

    public function delete()
    {
        if ($this->db->delete('rechnung', $this->getID())) {
            return true;
        }

        return false;
    }

    /**
     * getter methods
     */
    public function getID()
    {
        return $this->rechnung_id;
    }

    public function getKunde()
    {
        return KundeModel::findByID($this->db, $this->kunde_id);
    }

    public function getStornierungsdatum()
    {
        if ($this->stornierungsdatum != '0000-00-00') {
            return new \DateTime($this->stornierungsdatum . " 00:00:00");
        }
        return null;
    }

    public function getBezahltAm()
    {
        if ($this->bezahlt_am != '0000-00-00') {
            return new \DateTime($this->bezahlt_am . " 00:00:00");
        }
        return null;
    }

    public function getNettobetrag()
    {
        return $this->nettobetrag;
    }

    public function getMehrwertsteuer()
    {
        return $this->mehrwertsteuer;
    }

    public function getBruttobetrag()
    {
        return $this->bruttobetrag;
    }

    public function getRechnungsdatum()
    {
        if ($this->rechnungsdatum != '0000-00-00') {
            return new \DateTime($this->rechnungsdatum . " 00:00:00");
        }
        return null;
    }

    public function getRechnungsnummer()
    {
        return $this->rechnungsnummer;
    }

	public function getRechnungsnummerWithYear()
    {
        return $this->getZeitraumVon()->format('Y') . ' - ' . $this->getRechnungsnummer();
    }

    public function getZeitraumVon()
    {
        if ($this->zeitraum_von != '0000-00-00') {
            return new \DateTime($this->zeitraum_von . " 00:00:00");
        }
        return null;
    }

    public function getZeitraumBis()
    {
        if ($this->zeitraum_bis != '0000-00-00') {
            return new \DateTime($this->zeitraum_bis . " 00:00:00");
        }
        return null;
    }

    public function getZahlungsziel()
    {
        if ($this->zahlungsziel != '0000-00-00') {
            return new \DateTime($this->zahlungsziel . " 00:00:00");
        }
        return null;
    }

    public function getKassendifferenz()
    {
        return $this->kassendifferenz;
    }

    public function getAlternativeAnrede()
    {
        return $this->alternative_anrede;
    }

    public function getKommentar()
    {
        return $this->kommentar;
    }

    public function getAlteRechnungID()
    {
        return $this->alte_rechnung_id;
    }

    /**
     * setter methods
     */
    public function setKundeID(string $value)
    {
        return $this->setAttribute('kunde_id', $value);
    }

    public function setStornierungsdatum(string $value)
    {
        return $this->setAttribute('stornierungsdatum', $value);
    }

    public function setBezahltAm(string $value)
    {
        return $this->setAttribute('bezahlt_am', $value);
    }

    public function setNettobetrag(string $value)
    {
        return $this->setAttribute('nettobetrag', $value);
    }

    public function setMehrwertsteuer(string $value)
    {
        return $this->setAttribute('mehrwertsteuer', $value);
    }

    public function setBruttobetrag(string $value)
    {
        return $this->setAttribute('bruttobetrag', $value);
    }

    public function setRechnungsdatum(string $value)
    {
        return $this->setAttribute('rechnungsdatum', $value);
    }

    public function setRechnungsnummer(string $value)
    {
        return $this->setAttribute('rechnungsnummer', $value);
    }

    public function setZeitraumVon(string $value)
    {
        return $this->setAttribute('zeitraum_von', $value);
    }

    public function setZeitraumBis(string $value)
    {
        return $this->setAttribute('zeitraum_bis', $value);
    }

    public function setZahlungsziel(string $value)
    {
        return $this->setAttribute('zahlungsziel', $value);
    }

    public function setKassendifferenz(string $value)
    {
        return $this->setAttribute('kassendifferenz', $value);
    }

    public function setAlternativeAnrede(string $value)
    {
        return $this->setAttribute('alternative_anrede', $value);
    }

    public function setKommentar(string $value)
    {
        return $this->setAttribute('kommentar', $value);
    }

    public function setAlteRechnungID(string $value)
    {
        return $this->setAttribute('alte_rechnung_id', $value);
    }
}
