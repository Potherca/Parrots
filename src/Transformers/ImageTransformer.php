<?php

namespace Potherca\Parrots\Transformers;

use Potherca\Parrots\AbstractData;
use Spatie\Browsershot\Browsershot;
use UnexpectedValueException;

class ImageTransformer extends AbstractData implements TransformerInterface
{
    const IMAGE_WIDTH = 1200;
    const IMAGE_HEIGHT = 630;
    const ERROR_PROPERTY_NOT_SET = 'Can not get %s before it has been set';
    const ERROR_TYPE_NOT_SUPPORTED = 'Type "%s" is not supported';

    /**
     * @return string
     *
     * @throws \Exception
     */
    final public function transform()
    {
        $this->validateProperties();

        return $this->output();
    }

    private function output()
    {
        $sType = $this->inferImageTypeFromMimeType();

        $sImagePath = tempnam(sys_get_temp_dir(), 'browsershot_').'.'.$sType;
        $sImagePath = '/home/ubuntu/workspace/Parrots/example.png';


        $query = http_build_query([
            self::PROPERTY_BACKGROUND_COLOR => $this->getBackgroundColor(),
            self::PROPERTY_COLOR => $this->getColor(),
            self::PROPERTY_PREFIX => $this->getPrefix(),
            self::PROPERTY_SUBJECT => $this->getSubject(),
        ]);

        $url = vsprintf('%s://%s/?%s', [
            'protocol' => 'https',
            'server' => 'www.jebentzelfeen.website',
            'query' => $query,
        ]);

        // @FIXME: Window-size needs to be calculated based on the amount of text/words
        $iWidth = self::IMAGE_WIDTH;
        $iHeight = self::IMAGE_HEIGHT;

        Browsershot::url($url)
            ->fullPage()
            // ->windowSize($iWidth, $iHeight)
            // ->setChromePath($pathToChrome) // get from env?
            // ->fit(Manipulations::FIT_CONTAIN, 200, 200)
            ->save($sImagePath)
        ;

        $sImage = file_get_contents($sImagePath);

        // unlink($sImagePath);

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

    private function validateProperties()
    {
        $aUnset = [];

        // if (is_callable('image' . $this->inferImageTypeFromMimeType()) === false) {
        //     throw new UnexpectedValueException(sprintf(self::ERROR_TYPE_NOT_SUPPORTED, $this->getType()));
        // }// else {

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
