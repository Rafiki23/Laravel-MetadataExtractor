<?php

namespace Rafiki23\MetadataExtractor;

class EXIFExtractor {
    public static function extract($path) {
        $exifData = @exif_read_data($path, 'ANY_TAG', true);
        if (!$exifData) {
            return null; // Możesz tutaj obsłużyć błąd w inny sposób
        }

        return $exifData;
    }
}
