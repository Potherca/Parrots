<?php

namespace Potherca\Parrots\Transformers;

use Potherca\Parrots\AbstractData;

class TextTransformer extends AbstractData implements TransformerInterface
{
    final public function transform()
    {
        return $this->getText();
    }
}

/*EOF*/
