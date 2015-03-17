<?php

namespace Potherca\Parrots\Transformers;

use Potherca\Parrots\AbstractData;
use Potherca\Parrots\Utilities\ColorConverter;
use Potherca\Parrots\Utilities\TextSplitter;
use GDText\Box;
use GDText\Color;

class ImageTransformer extends AbstractData implements TransformerInterface
{
    /** @var Resource */
    private $m_rImage;
    /* @var ColorConverter */
    private $m_oColorConverter;
    /* @var TextSplitter */
    private $m_oTextSplitter;
       final public function setColorConverter(ColorConverter $p_oColorConverter)
    {
        $this->m_oColorConverter = $p_oColorConverter;
    }

    final public function setTextSplitter(TextSplitter $p_oTextSplitter)
    {
        $this->m_oTextSplitter = $p_oTextSplitter;
    }

    final public function transform()
    {
        $oConverter = $this->m_oColorConverter;

        $aColor = $oConverter->convert($this->getColor());
        $aBackgroundColor = $oConverter->convert($this->getBackgroundColor());
        $sText = $this->m_oTextSplitter->split($this->getText());

        $this->createImage($sText, $aColor, $aBackgroundColor);

        return $this->output();
    }

    final public function createImage($p_sText, $p_aColor, $p_aBackgroundColor)
    {
        
        $sText = strtoupper($p_sText);
        
        $aLines = explode("\n", $sText);
        $iLines = count($aLines);
        
        $iLongestLine = 0;
        foreach($aLines as $t_sLine) {
            $iLineLength = strlen($t_sLine);
            if ($iLineLength > $iLongestLine) {
                $iLongestLine = $iLineLength;
            }
        }
        $iFontSize = 1500/$iLongestLine;

        $iWidth = 1200;
        $iHeight = 630;

        $this->m_rImage = imagecreatetruecolor($iWidth, $iHeight);

        imagealphablending($this->m_rImage, false);
        imagefilledrectangle($this->m_rImage
            , 0, 0
            , $iWidth, $iHeight
            , imagecolorallocatealpha(
                $this->m_rImage,
                $p_aBackgroundColor['red'],
                $p_aBackgroundColor['green'],
                $p_aBackgroundColor['blue'],
                1
            )
        );
        imagealphablending($this->m_rImage, true);

        $iColor = imagecolorallocatealpha(
            $this->m_rImage,
            $p_aColor['red'],
            $p_aColor['green'],
            $p_aColor['blue'],
            0
        );

        $box = new Box($this->m_rImage);
        $box->setFontFace(__DIR__ . '/../../fonts/OpenSans-Bold.ttf');
        //$box->setFontColor(new Color(255, 75, 140));
        //$box->setTextShadow(new Color(0, 0, 0, 50), 2, 2);
        
        $box->setFontSize($iFontSize);
        
        $box->setBox(0, 0, $iWidth, $iHeight);
        $box->setTextAlign('center', 'center');
        $box->draw($sText);
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

        ob_start();
        imagesavealpha($this->m_rImage, true);
        call_user_func('image' . substr($this->getType(), strpos($this->getType(), '/')+1)
            , $this->m_rImage
            , null// if filename is include an actual file is created
            , $this->getQuality()
        );
        $sImage = ob_get_clean();

        //imagedestroy($this->m_rImage);

        return $sImage;
    }
}

/*EOF*/
