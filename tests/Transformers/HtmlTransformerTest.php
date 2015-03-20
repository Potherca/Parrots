<?php

namespace Potherca\Parrots\Transformers;

/**
 * @coversDefaultClass Potherca\Parrots\Transformers\HtmlTransformer
 * @covers ::<!public>
 */
class HtmlTransformerTest extends \PHPUnit_Framework_TestCase
{
    ////////////////////////////////// FIXTURES \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    /** @var HtmlTransformer */
    private $transformer;

    protected function setUp()
    {
        $this->transformer = new HtmlTransformer();
    }
    /////////////////////////////////// TESTS \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    /**
     * @covers ::transform
     */
    final public function testTransformerShouldProtestWhenItIsCalledBeforeItHasBeenGivenColorConverter()
    {
        $transformer = $this->transformer;
        $transformer->transform();
    }

    /**
     * @covers ::transform
     */
    final public function testTransformerShouldProtestWhenItIsCalledBeforeItHasBeenGivenTextSplitter()
    {
    }

    /**
     * @covers ::transform
     */
    final public function testTransformerShouldConvertGivenBackgroundColorWhenAskedToTransform()
    {

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

    /**
     * @covers ::transform
     * @param string $p_sType
     *
     * @dataProvider provideSupportedTypes
     */
    final public function testTransformerShouldReturnAnImageWhenGivenTypeIsSupported($p_sType)
    {

    }

    /**
     * @covers ::transform
     * @param string $p_sType
     *
     * @dataProvider provideSupportedTypes
     */
    final public function testTransformerShouldComplainWhenGivenTypesIsUnsupported($p_sType)
    {

    }
    ////////////////////////////// MOCKS AND STUBS \\\\\\\\\\\\\\\\\\\\\\\\\\\\\
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

    }
}

/*EOF*/
