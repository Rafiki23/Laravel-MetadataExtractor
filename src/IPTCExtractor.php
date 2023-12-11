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
        '2#095' => 'provinceState',
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
                }
            }
        }
        return $iptcData;
    }
}
