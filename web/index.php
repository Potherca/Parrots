<?php

namespace Potherca\Parrots;

use Negotiation\FormatNegotiator;
use PHPTAL;
use Potherca\Parrots\Transformers\HtmlTransformer;
use Potherca\Parrots\Transformers\JsonTransformer;
use Potherca\Parrots\Transformers\TextTransformer;

require '../vendor/autoload.php';

$aSupportedExtensions = array(
    'html' => 'text/html',
    'json' => 'application/json',
    'txt' => 'text/plain',
);

$sDomain = $_SERVER['HTTP_HOST'];
$sType = 'text/html';
$sSubject = null;

if (isset($_SERVER['HTTP_ACCEPT'])) {
    $oNegotiator = new FormatNegotiator();
    $sType = $oNegotiator->getBest($_SERVER['HTTP_ACCEPT'])->getValue();
}

if (isset($_GET['subject'])) {
    $sSubject = $_GET['subject'];

    $aParts = explode('.', $sSubject);
    $iCount = count($aParts);
    if ($iCount > 1
        && in_array($aParts[$iCount-1], array_keys($aSupportedExtensions))
    ) {
        $sType = $aSupportedExtensions[array_pop($aParts)];
        $sSubject = implode('.', $aParts);
    }
}

if (file_exists('../config/' . $sDomain . '.json')) {
    $sConfigFile = '../config/' . $sDomain . '.json';
} else {
    $sConfigFile = '../config/default.json';
}

if (file_exists($sConfigFile) === false) {
    $aData = [
        'background-color' => 'crimson',
        'color' => 'white',
        'prefix' => 'CONFIG FILE ERROR:',
    ];
    $sSubject = 'File ' . $sConfigFile . ' does not exist';
} else {
    $sConfig = file_get_contents($sConfigFile);
    $aData = json_decode($sConfig, true);
}

if (is_array($aData) === false) {
    $aData = [
        'background-color' => 'crimson',
        'color' => 'white',
        'prefix' => 'JSON CONFIG ERROR:',
        'subject' => json_last_error_msg()
    ];
} elseif ($sSubject !== null) {
    $aData['subject'] = $sSubject;
} else {
    // All done.
}

$aData['text'] = $aData['prefix'] . ' ' . $aData['subject'];

switch ($sType) {
    case 'application/json':
    case 'text/javascript':
        $oTransformer = new JsonTransformer();
        break;

    case 'text/html':
        $sTemplatePath = __DIR__ . '/../src/Templates/template.html';
        $oTemplate = new PHPTAL($sTemplatePath);
        $oTransformer = new HtmlTransformer();
        $oTransformer->setTemplate($oTemplate);
        break;

    case 'text/plain':
        $oTransformer = new TextTransformer();
        break;

    default:
        throw new \Exception('Unsupported type "' . $sType . '"');
        break;
}

$sData = $oTransformer->transform($aData);

header('Content-Type: ' . $sType, true, 200);
echo $sData;

/*EOF*/
