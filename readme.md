# Laravel Metadata Extractor
 A Laravel package for extracting IPTC and EXIF data from images

![Packagist Version (custom server)](https://img.shields.io/packagist/v/rafiki23/metadata-extractor)
![Packagist PHP Version](https://img.shields.io/packagist/dependency-v/rafiki23/metadata-extractor/php)
![GitHub License](https://img.shields.io/github/license/Rafiki23/Laravel-MetadataExtractor)


## Installation

Use Composer to install the package:

```bash
composer require rafiki23/metadata-extractor
```

### Usage

To extract IPTC data:

```
use rafiki23\metadata-extractor\MetadataExtractor;

$iptcData = MetadataExtractor::extractIPTC('path/to/image.jpg');

```

To extract EXIF data:

```
use rafiki23\metadata-extractor\MetadataExtractor;

$exifData = MetadataExtractor::extractEXIF('path/to/image.jpg');

```


#### License


This package is made available under the [MIT License](https://opensource.org/licenses/MIT).
