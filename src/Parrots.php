<?php

namespace Potherca\Parrots;

use Potherca\Parrots\Transformers\TransformerInterface;

class Parrots extends AbstractData
{
    ////////////////////////////// CLASS PROPERTIES \\\\\\\\\\\\\\\\\\\\\\\\\\\\
    /** @var TransformerInterface */
    private $m_oTransformer;
    /** @var array */
    private $m_aSupportedExtensions = array(
        'html' => 'text/html',
        'json' => 'application/json',
        'png' => 'image/png',
        'slack' => 'application/slack',
        'txt' => 'text/plain',
    );

    //////////////////////////// SETTERS AND GETTERS \\\\\\\\\\\\\\\\\\\\\\\\\\\
    /**
     * @param array $m_aSupportedExtensions
     */
    final public function setSupportedExtensions($m_aSupportedExtensions)
    {
        $this->m_aSupportedExtensions = $m_aSupportedExtensions;
    }

    /** @param TransformerInterface $p_oTransformer*/
    final public function setTransformer($p_oTransformer)
    {
        $this->m_oTransformer = $p_oTransformer;
    }

    //////////////////////////////// PUBLIC API \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    final public function parrot()
    {
        $transformer = $this->m_oTransformer;

        $transformer->setFromArray([
            self::PROPERTY_BACKGROUND_COLOR => $this->getBackgroundColor(),
            self::PROPERTY_COLOR => $this->getColor(),
            self::PROPERTY_PREFIX => $this->getPrefix(),
            self::PROPERTY_SUBJECT => $this->getSubject(),
            self::PROPERTY_TYPE => $this->getType(),
            self::PROPERTY_URL => $this->getUrl(),
        ]);

        $sResult = $transformer->transform();

        return $sResult;
    }

    /**
     * @return string
     */
    final public function resolveTypeFromSubject()
    {
        $aParts = explode('.', $this->m_sSubject);
        $iCount = count($aParts);
        if ($iCount > 1
            && in_array($aParts[$iCount-1], array_keys($this->m_aSupportedExtensions))
        ) {
            $this->m_sType = $this->m_aSupportedExtensions[array_pop($aParts)];
            $this->m_sSubject = implode('.', $aParts);
        }
    }
    ////////////////////////////// UTILITY METHODS \\\\\\\\\\\\\\\\\\\\\\\\\\\\\
}

/*EOF*/
