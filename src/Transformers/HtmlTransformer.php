<?php

namespace Potherca\Parrots\Transformers;

use Exception;
use PHPTAL;

class HtmlTransformer implements TransformerInterface
{
    /** @var PHPTAL */
    private $m_oTemplate;

    final public function setTemplate(PHPTAL $p_oTemplate)
    {
        $this->m_oTemplate = $p_oTemplate;
    }

    final public function transform(array $p_aData)
    {
        $oTemplate = $this->m_oTemplate;

        $sPrefix = '';
        $sSubject = '';
        $sColor = 'black';
        $sBackgroundColor = 'white';

        if (isset($p_aData['color'])) {
            $sColor = $p_aData['color'];
        }

        if (isset($p_aData['background-color'])) {
            $sBackgroundColor = $p_aData['background-color'];
        }

        if (isset($p_aData['prefix'])) {
            $sPrefix = $p_aData['prefix'];
        }

        if (isset($p_aData['subject'])) {
            $sSubject = $p_aData['subject'];
        }

        $oTemplate->set('sBackgroundColor', $sBackgroundColor);
        $oTemplate->set('sColor', $sColor);
        $oTemplate->set('sPrefix', $sPrefix);
        $oTemplate->set('sSubject', $sSubject);

        try {
            $result = $oTemplate->execute();
        } catch (Exception $e) {
            $result = 'ERROR: ' . $e->getMessage();
        }
        return $result;
    }
}

/*EOF*/
