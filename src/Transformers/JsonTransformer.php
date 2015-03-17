<?php

namespace Potherca\Parrots\Transformers;

use Potherca\Parrots\AbstractData;

class JsonTransformer extends AbstractData implements TransformerInterface
{
    final public function transform()
    {
        $aData = array(
            'message' => 'ok',
            'status' => 200,
            'data' => array(
                'text' => $this->getText(),
            ),
        );

        return $this->jsonEncode($aData);
    }

    /**
     * @param $aData
     *
     * @return string
     */
    private function jsonEncode($aData)
    {
        return json_encode(
            $aData,
            JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_FORCE_OBJECT
            | JSON_HEX_QUOT | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_TAG
        );
    }
}

/*EOF*/
