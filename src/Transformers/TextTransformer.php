<?php

namespace Potherca\Parrots\Transformers;

class TextTransformer
{
    final public function transform(array $p_aData)
    {
        $sPrefix = '';
        $sSubject = '';

        if (isset($p_aData['prefix'])) {
            $sPrefix = $p_aData['prefix'];
        }

        if (isset($p_aData['subject'])) {
            $sSubject = $p_aData['subject'];
        }

        return $sPrefix . ' ' . $sSubject;
    }
}

/*EOF*/
