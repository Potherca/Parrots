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
     * @uses Potherca\Parrots\Transformers\ImageTransformer::transform
     */
    final public function testTransformerShouldProtestWhenItIsCalledBeforeItHasBeenGivenColorConverter()
    {
        $transformer = $this->transformer;

        $this->setExpectedException(
            LogicException::class,
            sprintf(ImageTransformer::ERROR_CLASS_NOT_SET, ConverterInterface::class)
        );

        $transformer->transform();
    }

    /**
     * @uses Potherca\Parrots\Transformers\ImageTransformer::setConverter
     * @uses Potherca\Parrots\Transformers\ImageTransformer::transform
     */
    final public function testTransformerShouldProtestWhenItIsCalledBeforeItHasBeenGivenTextSplitter()
    {
        $transformer = $this->transformer;

        $oMockConverter = $this->getMockConverter();
        $transformer->setConverter($oMockConverter);

        $this->setExpectedException(
            LogicException::class,
            sprintf(ImageTransformer::ERROR_CLASS_NOT_SET, SplitterInterface::class)
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
            ImageTransformer::PROPERTY_TYPE => $p_sType
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
            ImageTransformer::PROPERTY_TYPE => $p_sType
        ]);

        $oTransformer->transform();
    }

    /**
     * @covers ::transform
     * @covers ::setConverter
     */
    final public function testTransformerShouldConvertDefaultColorsWhenNoColorsGiven()
    {
        $oTransformer = $this->transformer;

        $this->setMockDependencies();

        $this->m_oMockConverter->expects($this->exactly(2))
            ->method('convert')
            ->withConsecutive(
                [ImageTransformer::DEFAULT_COLOR],
                [ImageTransformer::DEFAULT_BACKGROUND_COLOR]
            )
        ;

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

        $sMockBackgroundColor = 'mockBackgroundColor';
        $sMockColor = 'mockColor';

        $this->m_oMockConverter->expects($this->exactly(2))
            ->method('convert')
            ->withConsecutive(
                [$sMockBackgroundColor],
                [$sMockColor]
            )
        ;

        $oTransformer->setFromArray([
            ImageTransformer::PROPERTY_BACKGROUND_COLOR => $sMockBackgroundColor,
            ImageTransformer::PROPERTY_COLOR => $sMockColor,
        ]);
        $oTransformer->transform();
    }

    /**
     * @covers ::transform
     */
    final public function testTransformerShouldConvertGivenForegroundColorWhenAskedToTransform()
    {

    }

    /**
     * @covers ::transform
     */
    final public function testTransformerShouldSplitGivenTextWhenAskedToTransform()
    {

    }

    /**
     * @covers ::transform
     */
    final public function testTransformerShouldReturnAnImageWhenAskedToTransform()
    {

    }

    /**
     * @covers ::transform
     */
    final public function testTransformerShouldUseExpectedDimensionsWhenImageIsReturned()
    {

    }

    /**
     * @covers ::transform
     */
    final public function testTransformerShouldUseGivenColorWhenImageIsReturned()
    {

    }

    /**
     * @covers ::transform
     */
    final public function testTransformerShouldUseGivenBackgroundColorWhenImageIsReturned()
    {

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
            ['image/jpg'],
            ['image/jpeg'],
            ['image/png'],
            ['image/gif'],
        ];
    }

    final public function provideUnsupportedTypes()
    {
        return [
            [ImageTransformer::DEFAULT_TYPE],
            ['text/plain'],
            ['png'],
            [false],
            [true],
            [null],
        ];
    }

}

/*EOF*/
