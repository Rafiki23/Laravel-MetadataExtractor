<?php 

namespace Rafiki23\MetadataExtractor;

class MetadataExtractor {
    public static function extractIPTC($path) {
        return IPTCExtractor::extract($path);
    }

    public static function extractEXIF($path) {
        return EXIFExtractor::extract($path);
    }
}
