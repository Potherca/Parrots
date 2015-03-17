<?php

namespace Potherca\Parrots;

abstract class AbstractData implements DataInterface
{
    ////////////////////////////// CLASS PROPERTIES \\\\\\\\\\\\\\\\\\\\\\\\\\\\
    const PROPERTY_TYPE = 'type';
    const PROPERTY_SUBJECT = 'subject';
    const PROPERTY_PREFIX = 'prefix';
    const PROPERTY_BACKGROUND_COLOR = 'background-color';
    const PROPERTY_COLOR = 'color';

    /** @var string */
    protected $m_sBackgroundColor = 'white';
    /** @var string */
    protected $m_sColor = 'black';
    /** @var string */
    protected $m_sPrefix = '';
    /** @var string */
    protected $m_sSubject = '';
    /** @var string */
    protected $m_sType = 'text/plain';

    //////////////////////////// SETTERS AND GETTERS \\\\\\\\\\\\\\\\\\\\\\\\\\\
    /** @return string */
    public function getBackgroundColor()
    {
        return $this->m_sBackgroundColor;
    }

    /** @return string */
    public function getColor()
    {
        return $this->m_sColor;
    }

    /** @return string */
    public function getPrefix()
    {
        return $this->m_sPrefix;
    }

    /** @return string */
    public function getSubject()
    {
        return $this->m_sSubject;
    }

    /** @return string */
    public function getText()
    {
        return $this->getPrefix() . ' ' . $this->getSubject();
    }

    /** @return string */
    public function getType()
    {
        return $this->m_sType;
    }

    //////////////////////////////// PUBLIC API \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    final public function setFromArray(array $p_aData)
    {
        if (isset($p_aData[self::PROPERTY_COLOR])) {
            $this->m_sColor = $p_aData[self::PROPERTY_COLOR];
        }

        if (isset($p_aData[self::PROPERTY_BACKGROUND_COLOR])) {
            $this->m_sBackgroundColor = $p_aData[self::PROPERTY_BACKGROUND_COLOR];
        }

        if (isset($p_aData[self::PROPERTY_PREFIX])) {
            $this->m_sPrefix = $p_aData[self::PROPERTY_PREFIX];
        }

        if (isset($p_aData[self::PROPERTY_SUBJECT])) {
            $this->m_sSubject = $p_aData[self::PROPERTY_SUBJECT];
        }

        if (isset($p_aData[self::PROPERTY_TYPE])) {
            $this->m_sType = $p_aData[self::PROPERTY_TYPE];
        }
    }
}

/*EOF*/
