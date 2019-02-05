<?php

namespace AppBundle\Service;


class HelperService
{
    public static function generateHash()
    {
        $date = new \DateTime();
        return sha1($date->format('YmdHisv'));
    }

    public static function getTransactionStatusForImport($status, $int = false)
    {
        if($int == false)
        {
            return ($status == 'Zaksięgowana') ? 'ACCEPTED' : 'REJECTED';
        }else{
            return ($status == 'Zaksięgowana') ? '000' : $status;
        }

    }

    public static function getCardStatus($status, $int = false)
    {
        if($int == true)
        {
            return (!in_array($status, array('BLOCKED', 'RETIRED', 'SPENT'))) ? 1 : 0;
        }else{
            return (!in_array($status, array('BLOCKED', 'RETIRED', 'SPENT'))) ? "AKTYWNA" : "NIEAKTYWNA";
        }
    }

    public static function sluggify($text) {
        // replace non letter or digits by -
        $text = preg_replace('~[^\\pL\d]+~u', '-', $text);

        // trim
        $text = trim($text, '-');

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // lowercase
        $text = strtolower($text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        if (empty($text)) {
            return NULL;
        }

        return $text;
    }
}