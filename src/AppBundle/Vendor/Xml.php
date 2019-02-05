<?php

namespace AppBundle\Vendor;

use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Document\Properties;
use PhpOffice\PhpSpreadsheet\RichText\RichText;
use PhpOffice\PhpSpreadsheet\Settings;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Shared\File;
use PhpOffice\PhpSpreadsheet\Shared\StringHelper;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Font;

class Xml extends \PhpOffice\PhpSpreadsheet\Reader\Xml
{
    /**
     * Check if the file is a valid SimpleXML.
     *
     * @param string $pFilename
     *
     * @throws Exception
     *
     * @return false|\SimpleXMLElement
     */
    public function trySimpleXMLLoadString($pFilename)
    {
        $fileContent = file_get_contents($pFilename);
        $fileContent = str_replace("&", "", $fileContent);

        try {
            $xml = simplexml_load_string(
                $this->securityScan($fileContent),
                'SimpleXMLElement',
                Settings::getLibXmlLoaderOptions()
            );
        } catch (\Exception $e) {
            throw new Exception('Cannot load invalid XML file: ' . $pFilename, 0, $e);
        }

        return $xml;
    }
}