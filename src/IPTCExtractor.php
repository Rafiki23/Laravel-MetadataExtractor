<?php

namespace Rafiki23\MetadataExtractor;

class IPTCExtractor {
    protected static $iptcTags = [
        '2#005' => 'title',
        '2#010' => 'urgency',
        '2#015' => 'category',
        '2#020' => 'supplementalCategories',
        '2#025' => 'keywords',
        '2#040' => 'specialInstructions',
        '2#055' => 'creationDate',
        '2#080' => 'author',
        '2#085' => 'authorTitle',
        '2#090' => 'city',
        '2#092' => 'sublocation',
        '2#095' => 'provinceState',
        '2#100' => 'countryISOcode',
        '2#101' => 'countryName',
        '2#103' => 'originalTransmissionReference',
        '2#105' => 'headline',
        '2#110' => 'credit',
        '2#115' => 'source',
        '2#116' => 'copyrightNotice',
        '2#120' => 'caption',
        '2#122' => 'captionWriter',
    ];

    public static function extract($path) {
        $iptcData = [];
        getimagesize($path, $info);
        if (isset($info['APP13'])) {
            $iptc = iptcparse($info['APP13']);
            if ($iptc) {
                foreach (self::$iptcTags as $code => $name) {
                    if (isset($iptc[$code]) && $code != '2#025') {
                        $iptcData[$name] = $iptc[$code][0];
                    }
                    if (isset($iptc[$code]) && $code == '2#025') {
                        $keywords = '';
                        $keywordcount = count($iptc["2#025"]);

                        for ($i=0; $i < $keywordcount; $i++) {
                            $keywords .= $iptc['2#025'][$i].', ';
                        }
                        $iptcData[$name] = rtrim($keywords,', ');
                    }
                    if (isset($iptc[$code]) && $code == '2#020') {
                        $supplementalCategories = '';
                        $catcount = count($iptc["2#020"]);

                        for ($i=0; $i < $catcount; $i++) {
                            $supplementalCategories .= $iptc['2#020'][$i].', ';
                        }
                        $iptcData[$name] = rtrim($supplementalCategories,', ');
                    }
                }
            }
        }
        return $iptcData;
    }


        /**
     * Zapisuje dane IPTC do obrazu.
     *
     * @param string $path Ścieżka do pliku obrazu.
     * @param array $data Dane IPTC do zapisania.
     * @return bool Wynik operacji zapisu.
     */
    public static function saveIptcData($path, $data) {
        // Odczytanie istniejących danych IPTC
        $existingIptcData = Self::extract($path); // Pobranie istniejących tagów IPTC

       // dd($existingIptcData);
        // Przygotowanie nowych danych IPTC do zapisu
        $iptcData = '';
        foreach (self::$iptcTags as $code => $name) {
            if (isset($data[$name])) {
                // Użyj nowej wartości
                $value = $data[$name];
            } elseif (isset($existingIptcData[$name])) {
                // Użyj istniejącej wartości
                $value = $existingIptcData[$name];
            } else {
                continue; // Jeśli brak zarówno nowej, jak i istniejącej wartości, kontynuuj
            }

            $iptcData .= self::iptcMakeTag(substr($code, 0, 1), substr($code, 2), $value);
        }

        //dd($iptcData);
        //dd($path);
        // Pobranie zawartości obrazu
        //$content = file_get_contents($path);
        //dd($content);
        // Zapisanie danych IPTC
        $content = iptcembed($iptcData, $path);

        if ($content === false) {
            return false;
        }

        // Zapisanie zmodyfikowanego obrazu
        return file_put_contents($path, $content) !== false;
    }

    /**
     * Tworzy tag IPTC.
     */
    private static function iptcMakeTag($rec, $data, $value) {
        $length = strlen($value);
        $retval = chr(0x1C) . chr($rec) . chr($data);

        if ($length < 0x8000) {
            $retval .= chr($length >> 8) .  chr($length & 0xFF);
        } else {
            $retval .= chr(0x80) .
                       chr(0x04) .
                       chr(($length >> 24) & 0xFF) .
                       chr(($length >> 16) & 0xFF) .
                       chr(($length >> 8) & 0xFF) .
                       chr($length & 0xFF);
        }

        return $retval . $value;
    }

}
