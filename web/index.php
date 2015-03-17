<?php

namespace Potherca\Parrots;

use Negotiation\FormatNegotiator;
use PHPTAL;
use Potherca\Parrots\Transformers\HtmlTransformer;
use Potherca\Parrots\Transformers\ImageTransformer;
use Potherca\Parrots\Transformers\JsonTransformer;
use Potherca\Parrots\Transformers\TextTransformer;
use Potherca\Parrots\Transformers\TransformerInterface;
use Potherca\Parrots\Utilities\ColorConverter;
use Potherca\Parrots\Utilities\TextSplitter;

require '../vendor/autoload.php';

/* Get Config from file */
$sDomain = $_SERVER['HTTP_HOST'];
$sConfigFile = getConfigFileForDomain($sDomain);
$aData = getDataFromConfigFile($sConfigFile);

/* Get Type from HEADER */
if (isset($_SERVER['HTTP_ACCEPT'])) {
    $oNegotiator = new FormatNegotiator();
    $aData['type'] = $oNegotiator->getBest($_SERVER['HTTP_ACCEPT'])->getValue();
}

/* Get Subject from GET parameter */
if (empty($aData['subject']) && isset($_GET['subject'])) {
    $aData['subject'] = $_GET['subject'];
}

/* Feed the Parrot data*/
$oParrot = new Parrots($aData);

/* Feed Transformer to Parrot */
$oTransformer = getTransformerFor($oParrot->getType());
$oParrot->setTransformer($oTransformer);

/* Send Output from Parrot */

$sOutput = $oParrot->parrot();
header('Content-Type: ' . $oParrot->getType(), true, 200);
die($sOutput);

/*EOF*/

/**
 * @param $sType
 *
 * @return TransformerInterface
 *
 * @throws \Exception
 */
function getTransformerFor($sType)
{
    switch ($sType) {
        case 'application/json':
        case 'text/javascript':
            $oTransformer = new JsonTransformer();
            break;

        case 'image/png':
            $oTransformer = new ImageTransformer();
            $oTransformer->setColorConverter(new ColorConverter());
            $oTransformer->setTextSplitter(new TextSplitter());
            break;

        case 'text/plain':
            $oTransformer = new TextTransformer();
            break;

        case 'text/html':
        default:
            $sTemplatePath = __DIR__ . '/../src/Templates/template.html';
            $oTemplate = new PHPTAL($sTemplatePath);
            $oTransformer = new HtmlTransformer();
            $oTransformer->setTemplate($oTemplate);
            break;
    }

    return $oTransformer;
}

/**
 * @param $sConfigFile
 *
 * @return array
 *
 */
function getDataFromConfigFile($sConfigFile)
{
    if (file_exists($sConfigFile)) {
        $sConfig = file_get_contents($sConfigFile);
        $aData = json_decode($sConfig, true);
        if ($aData === null) {
            $aData = [
                'background-color' => 'crimson',
                'color' => 'white',
                'prefix' => 'CONFIG PARSE ERROR:',
                'subject' => json_last_error_msg()
            ];
        }
    } else {
        $aData = [
            'background-color' => 'crimson',
            'color' => 'white',
            'prefix' => 'CONFIG FILE ERROR:',
            'subject' => 'File "' . $sConfigFile . '" does not exist',
        ];
    }

    return $aData;
}

/**
 * @param $sDomain
 *
 * @return string
 */
function getConfigFileForDomain($sDomain)
{
    if (file_exists('../config/' . $sDomain . '.json')) {
        $sConfigFile = '../config/' . $sDomain . '.json';

        return $sConfigFile;
    } else {
        $sConfigFile = '../config/default.json';

        return $sConfigFile;
    }
}
