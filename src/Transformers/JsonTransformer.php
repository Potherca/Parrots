<?php

namespace Potherca\Parrots\Transformers;

class JsonTransformer
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

        unset($p_aData['prefix'], $p_aData['subject']);

        $aData = array(
            'message' => 'ok',
            'status' => 200,
            'data' => array(
                'text' => $sPrefix . ' ' . $sSubject,
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
