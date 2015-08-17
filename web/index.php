<?php

namespace Potherca\Parrots;

use Negotiation\FormatNegotiator;
use PHPTAL;
use Potherca\Parrots\Transformers\HtmlTransformer;
use Potherca\Parrots\Transformers\ImageTransformer;
use Potherca\Parrots\Transformers\JsonTransformer;
use Potherca\Parrots\Transformers\SlackTransformer;
use Potherca\Parrots\Transformers\TextTransformer;
use Potherca\Parrots\Transformers\TransformerInterface;
use Potherca\Parrots\Utilities\ColorConverter;
use Potherca\Parrots\Utilities\TextSplitter;

require '../vendor/autoload.php';

/* Get Config from file */
if (isset($_SERVER['HTTP_HOST'])) {
    $sDomain = $_SERVER['HTTP_HOST'];
} else {
    $sDomain = 'localhost';
}
$sConfigFile = getConfigFileForDomain($sDomain);
$aData = getDataFromConfigFile($sConfigFile);

/* Get Type from HEADER */
if (isset($_SERVER['HTTP_ACCEPT'])) {
    $oNegotiator = new FormatNegotiator();
    $aData[Parrots::PROPERTY_TYPE] = $oNegotiator->getBest($_SERVER['HTTP_ACCEPT'])->getValue();
}

if (isset($_POST['text'], $_POST['trigger_word'])) {
    // Slackbot 1.0 (+https://api.slack.com/robots)
    //text=googlebot: What is the air-speed velocity of an unladen swallow?
    //trigger_word=googlebot:
    $aData[Parrots::PROPERTY_TYPE] = 'application/slack';
    $aData[Parrots::PROPERTY_SUBJECT] = trim(substr($_POST['text'], strlen($_POST['trigger_word'])));
}

/* Get Subject from URL */
if (empty($aData[Parrots::PROPERTY_SUBJECT])) {
    if(isset($_SERVER['PATH_INFO'])) {
        /* Strip leading slash */
        $aData[Parrots::PROPERTY_SUBJECT] = substr($_SERVER['PATH_INFO'], 1);
    }
}

/* Construct the site URL */
$aData[Parrots::PROPERTY_URL] = $_SERVER['REQUEST_SCHEME'] . '://' . $sDomain;

$oParrot = new Parrots();
/* Feed Data to the Parrot */
$oParrot->setFromArray($aData);
/* set Properties from URL parameters */
$oParrot->setFromArray($_GET);
/* Feed Transformer to Parrot */
$oParrot->resolveTypeFromSubject();
$oTransformer = getTransformerFor($oParrot->getType());
$oParrot->setTransformer($oTransformer);

/* Send Output from Parrot to the Browser*/
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

        case 'application/slack':
            $oTransformer = new SlackTransformer();
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
                Parrots::PROPERTY_BACKGROUND_COLOR => 'crimson',
                Parrots::PROPERTY_COLOR => 'white',
                Parrots::PROPERTY_PREFIX => 'CONFIG PARSE ERROR:',
                Parrots::PROPERTY_SUBJECT => json_last_error_msg()
            ];
        }
    } else {
        $aData = [
            Parrots::PROPERTY_BACKGROUND_COLOR => 'crimson',
            Parrots::PROPERTY_COLOR => 'white',
            Parrots::PROPERTY_PREFIX => 'CONFIG FILE ERROR:',
            Parrots::PROPERTY_SUBJECT => 'File "' . $sConfigFile . '" does not exist',
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
    } else {
        $sConfigFile = '../config/default.json';
    }
    return $sConfigFile;
}
