<?php

namespace Potherca\Parrots\Transformers;

use Exception;
use PHPTAL;
use Potherca\Parrots\AbstractData;

class HtmlTransformer extends AbstractData implements TransformerInterface
{
    /** @var PHPTAL */
    private $m_oTemplate;

    final public function setTemplate(PHPTAL $p_oTemplate)
    {
        $this->m_oTemplate = $p_oTemplate;
    }

    final public function transform()
    {
        $oTemplate = $this->m_oTemplate;

        $oTemplate->set('sBackgroundColor', $this->getBackgroundColor());
        $oTemplate->set('sColor', $this->getColor());
        $oTemplate->set('sPrefix', $this->getPrefix());
        $oTemplate->set('sSubject', $this->getSubject());
        $oTemplate->set('sText', $this->getText());

        try {
            $result = $oTemplate->execute();
        } catch (Exception $e) {
            $result = 'ERROR: ' . $e->getMessage();
        }
        return $result;
    }
}

/*EOF*/
