<?php

namespace Potherca\Parrots;

abstract class AbstractData implements DataInterface
{
    ////////////////////////////// CLASS PROPERTIES \\\\\\\\\\\\\\\\\\\\\\\\\\\\
    const DEFAULT_BACKGROUND_COLOR = 'black';
    const DEFAULT_COLOR = 'white';
    const DEFAULT_TYPE = 'text/plain';
    const DEFAULT_URL = 'http://localhost';

    const PROPERTY_BACKGROUND_COLOR = 'background-color';
    const PROPERTY_COLOR = 'color';
    const PROPERTY_PREFIX = 'prefix';
    const PROPERTY_SUBJECT = 'subject';
    const PROPERTY_TYPE = 'type';
    const PROPERTY_URL = 'url';

    /** @var string */
    protected $m_sBackgroundColor = self::DEFAULT_COLOR;
    /** @var string */
    protected $m_sColor = self::DEFAULT_BACKGROUND_COLOR;
    /** @var string */
    protected $m_sPrefix = '';
    /** @var string */
    protected $m_sSubject = '';
    /** @var string */
    protected $m_sType = self::DEFAULT_TYPE;
    /** @var string */
    protected $m_sUrl = self::DEFAULT_URL;

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

    /** @return string */
    public function getUrl()
    {
        return $this->m_sUrl;
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

        if (isset($p_aData[self::PROPERTY_URL])) {
            $this->m_sUrl = $p_aData[self::PROPERTY_URL];
        }
    }
}

/*EOF*/
