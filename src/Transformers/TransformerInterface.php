<?php

namespace Potherca\Parrots\Transformers;

use Potherca\Parrots\DataInterface;

interface TransformerInterface extends DataInterface
{
    //////////////////////////////// PUBLIC API \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    /**
     * @return string
     */
    public function transform();
}
