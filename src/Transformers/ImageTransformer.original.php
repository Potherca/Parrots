<?php

namespace Potherca\Parrots\Transformers;

use Potherca\Parrots\AbstractData;
use Potherca\Parrots\Utilities\ConverterInterface;
use Potherca\Parrots\Utilities\SplitterInterface;
use GDText\Box;
use GDText\Color;
use UnexpectedValueException;

class ImageTransformer extends AbstractData implements TransformerInterface
{
    const IMAGE_WIDTH = 1200;
    const IMAGE_HEIGHT = 630;
    const ERROR_PROPERTY_NOT_SET = 'Can not get %s before it has been set';
    const ERROR_TYPE_NOT_SUPPORTED = 'Type "%s" is not supported';

    /** @var Resource */
    private $m_rImage;
    /* @var ConverterInterface */
    private $m_oColorConverter;
    /* @var SplitterInterface */
    private $m_oTextSplitter;

    /**
     * @return ConverterInterface
     */
    private function getConverter()
    {
        if ($this->m_oColorConverter === null) {
            throw $this->newLogicException(ConverterInterface::class);
        } else {
            return $this->m_oColorConverter;
        }
    }

    final public function setConverter(ConverterInterface $p_oConverter)
    {
        $this->m_oColorConverter = $p_oConverter;
    }

    /**
     * @return SplitterInterface
     */
    private function getSplitter()
    {
        if ($this->m_oTextSplitter === null) {
            throw $this->newLogicException(SplitterInterface::class);
        } else {
            return $this->m_oTextSplitter;
        }
    }

    final public function setSplitter(SplitterInterface $p_oSplitter)
    {
        $this->m_oTextSplitter = $p_oSplitter;
    }

    /**
     * @return string
     *
     * @throws \Exception
     */
    final public function transform()
    {
        $oConverter = $this->getConverter();
        $oSplitterInterface = $this->getSplitter();

        $this->validateProperties();

        $aBackgroundColor = $oConverter->convert($this->getBackgroundColor());
        $aColor = $oConverter->convert($this->getColor());
        $sText = $oSplitterInterface->split($this->getText());

        $this->buildImage($sText, $aColor, $aBackgroundColor);

        return $this->output();
    }

    private function buildImage($p_sText, $p_aColor, $p_aBackgroundColor)
    {
        $this->createImage($p_aColor, $p_aBackgroundColor);

        if (! empty($p_sText)) {
            $iWidth = self::IMAGE_WIDTH;
            $iHeight = self::IMAGE_HEIGHT;

            $sText = strtoupper($p_sText);

            $aLines = explode("\n", $sText);

            $iLongestLine = 0;
            foreach ($aLines as $t_sLine) {
                $iLineLength = strlen($t_sLine);
                if ($iLineLength > $iLongestLine) {
                    $iLongestLine = $iLineLength;
                }
            }

            $iFontSize = 1500 / $iLongestLine;

            $box = new Box($this->m_rImage);
            $box->setFontFace(__DIR__ . '/../../fonts/OpenSans-Bold.ttf');
            $box->setFontColor(new Color($p_aColor['red'], $p_aColor['green'], $p_aColor['blue']));
            //$box->setTextShadow(new Color(0, 0, 0, 50), 2, 2);

            $box->setFontSize($iFontSize);

            $box->setBox(0, 0, $iWidth, $iHeight);
            $box->setTextAlign('center', 'center');
            $box->draw($sText);
        }
    }

    /**
     * @return int
     */
    private function getQuality()
    {
        $iQuality = 0;

        switch ($this->getType()) {
            case 'image/gif':
                /* 0=no compression, 100=max compression */
                $iQuality = 100;
                break;
            case 'image/png':
                /* 0=no compression, 9=max compression */
                $iQuality = 9;
                break;
        }#switch

        return $iQuality;
    }

    private function output()
    {
        $sImage = null;
        $sType = $this->inferImageTypeFromMimeType();

        ob_start();
        imagesavealpha($this->m_rImage, true);
        call_user_func(
            'image' . $sType,
            $this->m_rImage,
            null, // if filename is included an actual file is created
            $this->getQuality()
        );
        $sImage = ob_get_clean();

        imagedestroy($this->m_rImage);

        return $sImage;
    }

    /**
     * @param string $p_sClassName
     *
     * @return \LogicException
     */
    private function newLogicException($p_sClassName)
    {
        return new \LogicException(sprintf(self::ERROR_PROPERTY_NOT_SET, $p_sClassName));
    }

    /**
     * @param $p_aColor
     * @param $p_aBackgroundColor
     */
    private function createImage($p_aColor, $p_aBackgroundColor)
    {
        $iWidth = self::IMAGE_WIDTH;
        $iHeight = self::IMAGE_HEIGHT;

        $this->m_rImage = imagecreatetruecolor($iWidth, $iHeight);

        imagealphablending($this->m_rImage, false);
        imagefilledrectangle(
            $this->m_rImage,
            0,
            0,
            $iWidth,
            $iHeight,
            imagecolorallocatealpha(
                $this->m_rImage,
                $p_aBackgroundColor['red'],
                $p_aBackgroundColor['green'],
                $p_aBackgroundColor['blue'],
                1
            )
        );
        imagealphablending($this->m_rImage, true);

        imagecolorallocatealpha(
            $this->m_rImage,
            $p_aColor['red'],
            $p_aColor['green'],
            $p_aColor['blue'],
            0
        );
    }

    private function validateProperties()
    {
        $aUnset = [];

        if (is_callable('image' . $this->inferImageTypeFromMimeType()) === false) {
            throw new UnexpectedValueException(sprintf(self::ERROR_TYPE_NOT_SUPPORTED, $this->getType()));
        }// else {

        if (empty($this->getBackgroundColor())) {
            $aUnset[] = self::PROPERTY_BACKGROUND_COLOR;
        }

        if (empty($this->getColor())) {
            $aUnset[] = self::PROPERTY_COLOR;
        }

        if (empty($this->getText())) {
            $aUnset[] = 'Text';
        }

        if (empty($aUnset) === false) {
            throw $this->newLogicException(implode(' or ', $aUnset));
        }
    }

    /**
     * @return string
     */
    private function inferImageTypeFromMimeType()
    {
        $sType = substr($this->getType(), strpos($this->getType(), '/') + 1);

        if ($sType === 'jpg') {
            $sType = 'jpeg';
        }

        return $sType;
    }
}

/*EOF*/
