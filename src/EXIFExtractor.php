<?php

namespace Rafiki23\MetadataExtractor;

class EXIFExtractor {
    public static function extract($path) {
        $exifData = @exif_read_data($path, 'ANY_TAG', true);
        if (!$exifData) {
            return null;
        }

        return $exifData;
    }
}
