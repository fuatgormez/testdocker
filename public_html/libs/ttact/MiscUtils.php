<?php

namespace ttact;

/**
 * MiscUtils short summary.
 *
 * MiscUtils description.
 *
 * @version 1.0
 * @author Mian
 */
class MiscUtils
{
    private $host_name;

    public function __construct(string $host_name)
    {
        $this->host_name = $host_name;
    }

    public function getKalenderwochen(\DateTime $year)
    {
        $return = [];

        $interval_1day = new \DateInterval("P0000-00-01T00:00:00");
        $interval_6days = new \DateInterval("P0000-00-06T00:00:00");
        $interval_7days = new \DateInterval("P0000-00-07T00:00:00");

        $tag = new \DateTime(($year->format("Y") - 1)."-12-28 00:00:00"); // see https://en.wikipedia.org/wiki/ISO_week_date#Last_week

        while ($tag->format("W") != 1) {
            $tag->add($interval_1day);
        }

        while (($tag->format("Y") == $year->format("Y")) || ($tag->format("Y") == ($year->format("Y") - 1))) {
            $bis = clone $tag;
            $bis->add($interval_6days);

            $return[] = [
                "kw" => $tag->format("W"),
                "von" => $tag->format("d.m."),
                "bis" => $bis->format("d.m.y")
            ];

            $tag->add($interval_7days);

            if ($tag->format("W") == 1) {
                break;
            }
        }

        return $return;
    }

    public function getTarifliste(Database $db)
    {
        $return = [];

        $tarife = \ttact\Models\TarifModel::findAll($db);
        foreach ($tarife as $tarif) {
            $return[] = [
                'id' => $tarif->getID(),
                'bezeichnung' => $tarif->getBezeichnung()
            ];
        }

        return $return;
    }

    public function redirect(...$url_parts)
    {
        $location = '';

        foreach ($url_parts as $part) {
            $location .= $part . '/';
        }

        header('Location: http://' . $this->host_name . '/' . $location);
        exit;
    }

    public function redirectURL(string $url)
    {
        header('Location: ' . $url);
        exit;
    }

    public function sendCSVHeader(string $filename = 'tabelle.csv')
    {
        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=" . $filename);
        header("Pragma: no-cache");
        header("Expires: 0");
    }

    public function sendTextHeader(string $filename = 'daten.txt')
    {
        header("Content-type: plain/text");
        header("Content-Disposition: attachment; filename=" . $filename);
        header("Pragma: no-cache");
        header("Expires: 0");
    }
}
