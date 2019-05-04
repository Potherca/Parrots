<?php

namespace Potherca\Parrots;

use Negotiation\Negotiator;
use PHPTAL;
use Potherca\Parrots\Transformers\HtmlTransformer;
use Potherca\Parrots\Transformers\ImageTransformer;
use Potherca\Parrots\Transformers\JsonTransformer;
use Potherca\Parrots\Transformers\SlackTransformer;
use Potherca\Parrots\Transformers\TextTransformer;
use Potherca\Parrots\Transformers\TransformerInterface;
use Potherca\Parrots\Utilities\ColorConverter;
use Potherca\Parrots\Utilities\TextSplitter;

/* @FIXME: This file contains too much logic. Most of it should be moved to a separate file/class BMP/2016/02/24 */
/* @FIXME: The code to find `vendor` is _very_ brittle. What abou COMPOSER_VENDOR_DIR and config.vendor-dir? */

$sRootPath = getRootPath(__DIR__);

if (is_file($sRootPath . '/vendor/autoload.php')) {
    require $sRootPath . '/vendor/autoload.php';
} else {
    throw new \UnexpectedValueException('Could not find composer autoload file');
}

/* Get Config from file */
$sDomain = 'localhost';
if (array_key_exists('HTTP_HOST', $_SERVER)) {
    $sDomain = $_SERVER['HTTP_HOST'];
}
$sConfigFile = getConfigFileForDomain($sDomain, $sRootPath);
$aData = getDataFromConfigFile($sConfigFile);

/* Get Type from HEADER */
if (array_key_exists('HTTP_ACCEPT', $_SERVER)) {
    $oNegotiator = new Negotiator();
    $aPriorities   = array('text/html; charset=UTF-8', 'application/json');
    $aData[Parrots::PROPERTY_TYPE] = $oNegotiator->getBest($_SERVER['HTTP_ACCEPT'], $aPriorities)->getValue();
}

/* Get Subject from URL */
if(isset($_SERVER['PATH_INFO'])) {
    /* Strip leading slash */
    $aData[Parrots::PROPERTY_SUBJECT] = substr($_SERVER['PATH_INFO'], 1);
}

/* POST variables take precedence over the PATH_INFO */
if (isset($_POST['text'], $_POST['trigger_word'])) {
    // Slackbot 1.0 (+https://api.slack.com/robots)
    //text=googlebot: What is the air-speed velocity of an unladen swallow?
    //trigger_word=googlebot:
    $aData[Parrots::PROPERTY_TYPE] = 'application/slack';
    $aData[Parrots::PROPERTY_SUBJECT] = trim(substr($_POST['text'], strlen($_POST['trigger_word'])));
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
$oTransformer = getTransformerFor($oParrot->getType(), $sRootPath);
$oParrot->setTransformer($oTransformer);

/* Send Output from Parrot to the Browser*/
$sOutput = $oParrot->parrot();
header('Content-Type: ' . $oParrot->getType(), true, 200);
die($sOutput);

/*EOF*/

/**
 * @param $sPath
 * @return mixed
 */
function getRootPath($sPath)
{
    $iVendorPosition = strrpos($sPath, 'vendor');
    if ($iVendorPosition !== false) {
        // minus one to remove directory separator at the end of the path
        $iLength = $iVendorPosition - 1;
        $sRootPath = substr($sPath, 0, $iLength);
        return $sRootPath;
    } else {
        $sRootPath = dirname($sPath);
        return $sRootPath;
    }
}

/**
 * @param $p_sType
 *
 * @return TransformerInterface
 *
 * @throws \Exception
 */
function getTransformerFor($p_sType, $p_sRootPath)
{
    switch ($p_sType) {
        case 'application/json':
        case 'text/javascript':
        case 'text/json':
            $oTransformer = new JsonTransformer();
            break;

        case 'image/png':
            $oTransformer = new ImageTransformer();
            $oTransformer->setConverter(new ColorConverter());
            $oTransformer->setSplitter(new TextSplitter());
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

            /* See if there is a project specific override */
            if (is_file($p_sRootPath . '/src/Templates/template.html')) {
                $sTemplatePath = $p_sRootPath . '/src/Templates/template.html';
            }

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
 * @param $p_sDomain
 *
 * @return string
 */
function getConfigFileForDomain($p_sDomain, $p_sRootPath)
{
    if (file_exists($p_sRootPath.'/config/' . $p_sDomain . '.json')) {
        $sConfigFile = $p_sRootPath.'/config/' . $p_sDomain . '.json';
    } elseif (file_exists($p_sRootPath.'/config/default.json')) {
        $sConfigFile = $p_sRootPath.'/config/default.json';
    } elseif (file_exists(__DIR__ . '/../config/default.json')) {
        $sConfigFile = __DIR__ . '/../config/default.json';
    } else {
        throw new \UnexpectedValueException('Could not find configuration file');
    }
    return $sConfigFile;
}
