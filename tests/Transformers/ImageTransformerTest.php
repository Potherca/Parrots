<?php

namespace Potherca\Parrots\Transformers;

use LogicException;
use Potherca\Parrots\Utilities\ConverterInterface;
use Potherca\Parrots\Utilities\SplitterInterface;
use UnexpectedValueException;

/**
 * @coversDefaultClass Potherca\Parrots\Transformers\ImageTransformer
 * @covers ::<!public>
 * @uses Potherca\Parrots\AbstractData
 * @uses Potherca\Parrots\Transformers\ImageTransformer::setConverter
 * @uses Potherca\Parrots\Transformers\ImageTransformer::setSplitter
 */
class ImageTransformerTest extends \PHPUnit_Framework_TestCase
{
    ////////////////////////////////// FIXTURES \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    const MOCK_BACKGROUND_COLOR = 'mockBackgroundColor';
    const MOCK_COLOR = 'mockColor';
    const MOCK_TEXT = 'Mock Text';

    private $m_aExpectedBackgroundColor = [
        'red' => 255,
        'green' => 0,
        'blue' => 0,
        'alpha' => 1,
    ];
    private $m_aExpectedFontColor = [
        'red' => 0,
        'green' => 0,
        'blue' => 255,
        'alpha' => 0,
    ];

    /** @var ImageTransformer */
    private $transformer;
    /** @var \PHPUnit_Framework_MockObject_MockObject|SplitterInterface */
    private $m_oMockSplitter;
    /** @var \PHPUnit_Framework_MockObject_MockObject|ConverterInterface */
    private $m_oMockConverter;

    protected function setUp()
    {
        $this->transformer = new ImageTransformer();
    }
    /////////////////////////////////// TESTS \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    /**
     * @covers Potherca\Parrots\Transformers\ImageTransformer::setSplitter
     *
     * @uses Potherca\Parrots\Transformers\ImageTransformer::transform
     */
    final public function testTransformerShouldProtestWhenItIsCalledBeforeItHasBeenGivenColorConverter()
    {
        $transformer = $this->transformer;

        $this->setExpectedException(
            LogicException::class,
            sprintf(ImageTransformer::ERROR_PROPERTY_NOT_SET, ConverterInterface::class)
        );

        $transformer->transform();
    }

    /**
     * @covers Potherca\Parrots\Transformers\ImageTransformer::setConverter
     * @uses Potherca\Parrots\Transformers\ImageTransformer::transform
     */
    final public function testTransformerShouldProtestWhenItIsCalledBeforeItHasBeenGivenTextSplitter()
    {
        $transformer = $this->transformer;

        $oMockConverter = $this->getMockConverter();
        $transformer->setConverter($oMockConverter);

        $this->setExpectedException(
            LogicException::class,
            sprintf(ImageTransformer::ERROR_PROPERTY_NOT_SET, SplitterInterface::class)
        );

        $transformer->transform();
    }

    /**
     * @covers ::transform
     * @param string $p_sType
     *
     * @dataProvider provideSupportedTypes
     */
    final public function testTransformerShouldReturnAnImageWhenGivenTypeIsSupported($p_sType)
    {
        $oTransformer = $this->transformer;

        $this->setMockDependencies();

        $oTransformer->setFromArray([
            ImageTransformer::PROPERTY_TYPE => $p_sType,
            ImageTransformer::PROPERTY_COLOR => self::MOCK_COLOR,
            ImageTransformer::PROPERTY_BACKGROUND_COLOR => self::MOCK_BACKGROUND_COLOR,
        ]);

        $sImage = $oTransformer->transform();

        $rImage = imagecreatefromstring($sImage);

        $this->assertInternalType('resource', $rImage);
    }

    /**
     * @covers ::transform
     * @param string $p_sType
     *
     * @dataProvider provideUnsupportedTypes
     */
    final public function testTransformerShouldComplainWhenGivenTypesIsUnsupported($p_sType)
    {
        $oTransformer = $this->transformer;

        $this->setExpectedException(
            UnexpectedValueException::class,
            sprintf(ImageTransformer::ERROR_TYPE_NOT_SUPPORTED, $p_sType)
        );

        $this->setMockDependencies();

        $oTransformer->setFromArray([
            ImageTransformer::PROPERTY_TYPE => $p_sType,
            ImageTransformer::PROPERTY_COLOR => self::MOCK_COLOR,
            ImageTransformer::PROPERTY_BACKGROUND_COLOR => self::MOCK_BACKGROUND_COLOR,
        ]);

        $oTransformer->transform();
    }

    /**
     * @covers ::transform
     * @covers ::setConverter
     */
    final public function testTransformerShouldRefuseToConvertWhenNoColorsGiven()
    {
        $oTransformer = $this->transformer;

        $this->setMockDependencies();

        $this->setExpectedException(
            LogicException::class,
            sprintf(
                ImageTransformer::ERROR_PROPERTY_NOT_SET,
                ImageTransformer::PROPERTY_BACKGROUND_COLOR . ' or ' . ImageTransformer::PROPERTY_COLOR
            )
        );

        $this->m_oMockConverter->expects($this->never())
            ->method('convert')
        ;

        $oTransformer->setFromArray([
            ImageTransformer::PROPERTY_TYPE => 'image/png',
        ]);

        $oTransformer->transform();
    }

    /**
     * @covers ::transform
     * @covers ::setConverter
     */
    final public function testTransformerShouldConvertGivenColorsWhenGivenColors()
    {
        $oTransformer = $this->transformer;

        $this->setMockDependencies();

        $this->m_oMockConverter->expects($this->exactly(2))
            ->method('convert')
            ->withConsecutive(
                [self::MOCK_BACKGROUND_COLOR],
                [self::MOCK_COLOR]
            )
        ;

        $oTransformer->setFromArray([
            ImageTransformer::PROPERTY_TYPE => 'image/png',
            ImageTransformer::PROPERTY_BACKGROUND_COLOR => self::MOCK_BACKGROUND_COLOR,
            ImageTransformer::PROPERTY_COLOR => self::MOCK_COLOR,
        ]);
        $oTransformer->transform();
    }

    /**
     * @covers ::transform
     */
    final public function testTransformerShouldSplitGivenTextWhenAskedToTransform()
    {
        $oTransformer = $this->transformer;

        $this->setMockDependencies();

        $this->m_oMockSplitter->expects($this->exactly(1))
            ->method('split')
            ->with(self::MOCK_TEXT)
        ;

        $oTransformer->setFromArray([
            ImageTransformer::PROPERTY_TYPE => 'image/png',
            ImageTransformer::PROPERTY_BACKGROUND_COLOR => self::MOCK_BACKGROUND_COLOR,
            ImageTransformer::PROPERTY_COLOR => self::MOCK_COLOR,
            ImageTransformer::PROPERTY_PREFIX => 'Mock',
            ImageTransformer::PROPERTY_SUBJECT => 'Text',
        ]);

        $oTransformer->transform();
    }

    /**
     * @covers ::transform
     */
    final public function testTransformerShouldReturnAnImageWhenAskedToTransform()
    {
        $oTransformer = $this->transformer;

        $this->setMockDependencies();

        $this->m_oMockSplitter->expects($this->exactly(1))
            ->method('split')
            ->with(self::MOCK_TEXT)
            ->willReturn('X')
        ;

        $this->m_oMockConverter->expects($this->exactly(2))
            ->method('convert')
            ->withConsecutive(
                [self::MOCK_BACKGROUND_COLOR],
                [self::MOCK_COLOR]
            )
            ->willReturnOnConsecutiveCalls(
                $this->m_aExpectedBackgroundColor,
                $this->m_aExpectedFontColor
            )
        ;

        $oTransformer->setFromArray([
            ImageTransformer::PROPERTY_TYPE => 'image/png',
            ImageTransformer::PROPERTY_BACKGROUND_COLOR => self::MOCK_BACKGROUND_COLOR,
            ImageTransformer::PROPERTY_COLOR => self::MOCK_COLOR,
            ImageTransformer::PROPERTY_PREFIX => 'Mock',
            ImageTransformer::PROPERTY_SUBJECT => 'Text',
        ]);

        $sImage = $oTransformer->transform();

        $rImage = imagecreatefromstring($sImage);

        $this->assertInternalType('resource', $rImage, 'Image was not created');

        return $sImage;
    }

    /**
     * @covers ::transform
     *
     * @depends testTransformerShouldReturnAnImageWhenAskedToTransform
     *
     * @param string $p_sImage
     */
    final public function testTransformerShouldUseExpectedDimensionsWhenImageIsReturned($p_sImage)
    {
        $aActual = getimagesizefromstring($p_sImage);

        $iWidth = ImageTransformer::IMAGE_WIDTH;
        $iHeight = ImageTransformer::IMAGE_HEIGHT;

        $aExpected = [
            0 => $iWidth,
            1 => $iHeight,
            2 => 3,
            3 => sprintf('width="%d" height="%d"', $iWidth, $iHeight),
            'bits' => 8,
            'mime' => 'image/png',
        ];
        $this->assertEquals($aExpected, $aActual);
    }

    /**
     * @covers ::transform
     * @depends testTransformerShouldReturnAnImageWhenAskedToTransform
     *
     * @param string $p_sImage
     */
    final public function testTransformerShouldUseGivenColorWhenImageIsReturned($p_sImage)
    {
        $rImage = imagecreatefromstring($p_sImage);

        $iColor = imagecolorat(
            $rImage,
            ImageTransformer::IMAGE_WIDTH/2,
            ImageTransformer::IMAGE_HEIGHT/2
        );
        $aActualFontColor = imagecolorsforindex($rImage, $iColor);

        $this->assertEquals($this->m_aExpectedFontColor, $aActualFontColor);
    }

    /**
     * @covers ::transform
     *
     * @depends testTransformerShouldReturnAnImageWhenAskedToTransform
     *
     * @param string $p_sImage
     */
    final public function testTransformerShouldUseGivenBackgroundColorWhenImageIsReturned($p_sImage)
    {
        $rImage = imagecreatefromstring($p_sImage);

        $iColor = imagecolorat($rImage, 10, 10);
        $aActualBackgroundColor = imagecolorsforindex($rImage, $iColor);

        $this->assertEquals($this->m_aExpectedBackgroundColor, $aActualBackgroundColor);
    }
    ////////////////////////////// MOCKS AND STUBS \\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|ConverterInterface
     */
    private function getMockConverter()
    {
        $this->m_oMockConverter = $this->getMock(ConverterInterface::class);

        return $this->m_oMockConverter;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|SplitterInterface
     */
    private function getMockSplitter()
    {
        $this->m_oMockSplitter = $this->getMock(SplitterInterface::class);

        return $this->m_oMockSplitter;
    }

    /**
     * @return array
     */
    private function setMockDependencies()
    {
        $oTransformer = $this->transformer;

        $oMockSplitter = $this->getMockSplitter();
        $oMockConverter = $this->getMockConverter();

        $oTransformer->setConverter($oMockConverter);
        $oTransformer->setSplitter($oMockSplitter);
    }

    /////////////////////////////// DATAPROVIDERS \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    final public function provideSupportedTypes()
    {
        return [
            'gif' => ['image/gif'],
            'jpeg' => ['image/jpeg'],
            'jpg' => ['image/jpg'],
            'png' => ['image/png'],
        ];
    }

    final public function provideUnsupportedTypes()
    {
        return [
            'text' => ['text/plain'],
            'png' => ['png'],
            'FALSE' => [false],
            'TRUE' => [true],
            'NULL' => [null],
        ];
    }

}

/*EOF*/
