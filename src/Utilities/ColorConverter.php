<?php

namespace Potherca\Parrots\Utilities;

class ColorConverter
{
    private $m_aColorNames  =  [ 
        'aliceblue' => ['red' => 0xF0, 'green' => 0xF8, 'blue' => 0xFF],
        'antiquewhite' => ['red' => 0xFA, 'green' => 0xEB, 'blue' => 0xD7],
        'aqua' => ['red' => 0x00, 'green' => 0xFF, 'blue' => 0xFF],
        'aquamarine' => ['red' => 0x7F, 'green' => 0xFF, 'blue' => 0xD4],
        'azure' => ['red' => 0xF0, 'green' => 0xFF, 'blue' => 0xFF],
        'beige' => ['red' => 0xF5, 'green' => 0xF5, 'blue' => 0xDC],
        'black' => ['red' => 0x00, 'green' => 0x00, 'blue' => 0x00],
        'blue' => ['red' => 0x00, 'green' => 0x00, 'blue' => 0xFF],
        'blueviolet' => ['red' => 0x8A, 'green' => 0x2B, 'blue' => 0xE2],
        'brown' => ['red' => 0xA5, 'green' => 0x2A, 'blue' => 0x2A],
        'burlywood' => ['red' => 0xDE, 'green' => 0xB8, 'blue' => 0x87],
        'cadetblue' => ['red' => 0x5F, 'green' => 0x9E, 'blue' => 0xA0],
        'chartreuse' => ['red' => 0x7F, 'green' => 0xFF, 'blue' => 0x00],
        'chocolate' => ['red' => 0xD2, 'green' => 0x69, 'blue' => 0x1E],
        'coral' => ['red' => 0xFF, 'green' => 0x7F, 'blue' => 0x50],
        'cornflowerblue' => ['red' => 0x64, 'green' => 0x95, 'blue' => 0xED],
        'cornsilk' => ['red' => 0xFF, 'green' => 0xF8, 'blue' => 0xDC],
        'crimson' => ['red' => 0xDC, 'green' => 0x14, 'blue' => 0x3C],
        'darkblue' => ['red' => 0x00, 'green' => 0x00, 'blue' => 0x8B],
        'darkcyan' => ['red' => 0x00, 'green' => 0x8B, 'blue' => 0x8B],
        'darkgoldenrod' => ['red' => 0xB8, 'green' => 0x86, 'blue' => 0x0B],
        'darkgray' => ['red' => 0xA9, 'green' => 0xA9, 'blue' => 0xA9],
        'darkgreen' => ['red' => 0x00, 'green' => 0x64, 'blue' => 0x00],
        'darkkhaki' => ['red' => 0xBD, 'green' => 0xB7, 'blue' => 0x6B],
        'darkmagenta' => ['red' => 0x8B, 'green' => 0x00, 'blue' => 0x8B],
        'darkolivegreen' => ['red' => 0x55, 'green' => 0x6B, 'blue' => 0x2F],
        'darkorange' => ['red' => 0xFF, 'green' => 0x8C, 'blue' => 0x00],
        'darkorchid' => ['red' => 0x99, 'green' => 0x32, 'blue' => 0xCC],
        'darkred' => ['red' => 0x8B, 'green' => 0x00, 'blue' => 0x00],
        'darksalmon' => ['red' => 0xE9, 'green' => 0x96, 'blue' => 0x7A],
        'darkseagreen' => ['red' => 0x8F, 'green' => 0xBC, 'blue' => 0x8F],
        'darkslateblue' => ['red' => 0x48, 'green' => 0x3D, 'blue' => 0x8B],
        'darkslategray' => ['red' => 0x2F, 'green' => 0x4F, 'blue' => 0x4F],
        'darkturquoise' => ['red' => 0x00, 'green' => 0xCE, 'blue' => 0xD1],
        'darkviolet' => ['red' => 0x94, 'green' => 0x00, 'blue' => 0xD3],
        'deeppink' => ['red' => 0xFF, 'green' => 0x14, 'blue' => 0x93],
        'deepskyblue' => ['red' => 0x00, 'green' => 0xBF, 'blue' => 0xFF],
        'dimgray' => ['red' => 0x69, 'green' => 0x69, 'blue' => 0x69],
        'dodgerblue' => ['red' => 0x1E, 'green' => 0x90, 'blue' => 0xFF],
        'firebrick' => ['red' => 0xB2, 'green' => 0x22, 'blue' => 0x22],
        'floralwhite' => ['red' => 0xFF, 'green' => 0xFA, 'blue' => 0xF0],
        'forestgreen' => ['red' => 0x22, 'green' => 0x8B, 'blue' => 0x22],
        'fuchsia' => ['red' => 0xFF, 'green' => 0x00, 'blue' => 0xFF],
        'gainsboro' => ['red' => 0xDC, 'green' => 0xDC, 'blue' => 0xDC],
        'ghostwhite' => ['red' => 0xF8, 'green' => 0xF8, 'blue' => 0xFF],
        'gold' => ['red' => 0xFF, 'green' => 0xD7, 'blue' => 0x00],
        'goldenrod' => ['red' => 0xDA, 'green' => 0xA5, 'blue' => 0x20],
        'gray' => ['red' => 0x80, 'green' => 0x80, 'blue' => 0x80],
        'green' => ['red' => 0x00, 'green' => 0x80, 'blue' => 0x00],
        'greenyellow' => ['red' => 0xAD, 'green' => 0xFF, 'blue' => 0x2F],
        'honeydew' => ['red' => 0xF0, 'green' => 0xFF, 'blue' => 0xF0],
        'hotpink' => ['red' => 0xFF, 'green' => 0x69, 'blue' => 0xB4],
        'indianred' => ['red' => 0xCD, 'green' => 0x5C, 'blue' => 0x5C],
        'indigo' => ['red' => 0x4B, 'green' => 0x00, 'blue' => 0x82],
        'ivory' => ['red' => 0xFF, 'green' => 0xFF, 'blue' => 0xF0],
        'khaki' => ['red' => 0xF0, 'green' => 0xE6, 'blue' => 0x8C],
        'lavender' => ['red' => 0xE6, 'green' => 0xE6, 'blue' => 0xFA],
        'lavenderblush' => ['red' => 0xFF, 'green' => 0xF0, 'blue' => 0xF5],
        'lawngreen' => ['red' => 0x7C, 'green' => 0xFC, 'blue' => 0x00],
        'lemonchiffon' => ['red' => 0xFF, 'green' => 0xFA, 'blue' => 0xCD],
        'lightblue' => ['red' => 0xAD, 'green' => 0xD8, 'blue' => 0xE6],
        'lightcoral' => ['red' => 0xF0, 'green' => 0x80, 'blue' => 0x80],
        'lightcyan' => ['red' => 0xE0, 'green' => 0xFF, 'blue' => 0xFF],
        'lightgoldenrodyellow' => ['red' => 0xFA, 'green' => 0xFA, 'blue' => 0xD2],
        'lightgreen' => ['red' => 0x90, 'green' => 0xEE, 'blue' => 0x90],
        'lightgrey' => ['red' => 0xD3, 'green' => 0xD3, 'blue' => 0xD3],
        'lightpink' => ['red' => 0xFF, 'green' => 0xB6, 'blue' => 0xC1],
        'lightsalmon' => ['red' => 0xFF, 'green' => 0xA0, 'blue' => 0x7A],
        'lightseagreen' => ['red' => 0x20, 'green' => 0xB2, 'blue' => 0xAA],
        'lightskyblue' => ['red' => 0x87, 'green' => 0xCE, 'blue' => 0xFA],
        'lightslategray' => ['red' => 0x77, 'green' => 0x88, 'blue' => 0x99],
        'lightsteelblue' => ['red' => 0xB0, 'green' => 0xC4, 'blue' => 0xDE],
        'lightyellow' => ['red' => 0xFF, 'green' => 0xFF, 'blue' => 0xE0],
        'lime' => ['red' => 0x00, 'green' => 0xFF, 'blue' => 0x00],
        'limegreen' => ['red' => 0x32, 'green' => 0xCD, 'blue' => 0x32],
        'linen' => ['red' => 0xFA, 'green' => 0xF0, 'blue' => 0xE6],
        'maroon' => ['red' => 0x80, 'green' => 0x00, 'blue' => 0x00],
        'mediumaquamarine' => ['red' => 0x66, 'green' => 0xCD, 'blue' => 0xAA],
        'mediumblue' => ['red' => 0x00, 'green' => 0x00, 'blue' => 0xCD],
        'mediumorchid' => ['red' => 0xBA, 'green' => 0x55, 'blue' => 0xD3],
        'mediumpurple' => ['red' => 0x93, 'green' => 0x70, 'blue' => 0xD0],
        'mediumseagreen' => ['red' => 0x3C, 'green' => 0xB3, 'blue' => 0x71],
        'mediumslateblue' => ['red' => 0x7B, 'green' => 0x68, 'blue' => 0xEE],
        'mediumspringgreen' => ['red' => 0x00, 'green' => 0xFA, 'blue' => 0x9A],
        'mediumturquoise' => ['red' => 0x48, 'green' => 0xD1, 'blue' => 0xCC],
        'mediumvioletred' => ['red' => 0xC7, 'green' => 0x15, 'blue' => 0x85],
        'midnightblue' => ['red' => 0x19, 'green' => 0x19, 'blue' => 0x70],
        'mintcream' => ['red' => 0xF5, 'green' => 0xFF, 'blue' => 0xFA],
        'mistyrose' => ['red' => 0xFF, 'green' => 0xE4, 'blue' => 0xE1],
        'moccasin' => ['red' => 0xFF, 'green' => 0xE4, 'blue' => 0xB5],
        'navajowhite' => ['red' => 0xFF, 'green' => 0xDE, 'blue' => 0xAD],
        'navy' => ['red' => 0x00, 'green' => 0x00, 'blue' => 0x80],
        'oldlace' => ['red' => 0xFD, 'green' => 0xF5, 'blue' => 0xE6],
        'olive' => ['red' => 0x80, 'green' => 0x80, 'blue' => 0x00],
        'olivedrab' => ['red' => 0x6B, 'green' => 0x8E, 'blue' => 0x23],
        'orange' => ['red' => 0xFF, 'green' => 0xA5, 'blue' => 0x00],
        'orangered' => ['red' => 0xFF, 'green' => 0x45, 'blue' => 0x00],
        'orchid' => ['red' => 0xDA, 'green' => 0x70, 'blue' => 0xD6],
        'palegoldenrod' => ['red' => 0xEE, 'green' => 0xE8, 'blue' => 0xAA],
        'palegreen' => ['red' => 0x98, 'green' => 0xFB, 'blue' => 0x98],
        'paleturquoise' => ['red' => 0xAF, 'green' => 0xEE, 'blue' => 0xEE],
        'palevioletred' => ['red' => 0xDB, 'green' => 0x70, 'blue' => 0x93],
        'papayawhip' => ['red' => 0xFF, 'green' => 0xEF, 'blue' => 0xD5],
        'peachpuff' => ['red' => 0xFF, 'green' => 0xDA, 'blue' => 0xB9],
        'peru' => ['red' => 0xCD, 'green' => 0x85, 'blue' => 0x3F],
        'pink' => ['red' => 0xFF, 'green' => 0xC0, 'blue' => 0xCB],
        'plum' => ['red' => 0xDD, 'green' => 0xA0, 'blue' => 0xDD],
        'powderblue' => ['red' => 0xB0, 'green' => 0xE0, 'blue' => 0xE6],
        'purple' => ['red' => 0x80, 'green' => 0x00, 'blue' => 0x80],
        'red' => ['red' => 0xFF, 'green' => 0x00, 'blue' => 0x00],
        'rosybrown' => ['red' => 0xBC, 'green' => 0x8F, 'blue' => 0x8F],
        'royalblue' => ['red' => 0x41, 'green' => 0x69, 'blue' => 0xE1],
        'saddlebrown' => ['red' => 0x8B, 'green' => 0x45, 'blue' => 0x13],
        'salmon' => ['red' => 0xFA, 'green' => 0x80, 'blue' => 0x72],
        'sandybrown' => ['red' => 0xF4, 'green' => 0xA4, 'blue' => 0x60],
        'seagreen' => ['red' => 0x2E, 'green' => 0x8B, 'blue' => 0x57],
        'seashell' => ['red' => 0xFF, 'green' => 0xF5, 'blue' => 0xEE],
        'sienna' => ['red' => 0xA0, 'green' => 0x52, 'blue' => 0x2D],
        'silver' => ['red' => 0xC0, 'green' => 0xC0, 'blue' => 0xC0],
        'skyblue' => ['red' => 0x87, 'green' => 0xCE, 'blue' => 0xEB],
        'slateblue' => ['red' => 0x6A, 'green' => 0x5A, 'blue' => 0xCD],
        'slategray' => ['red' => 0x70, 'green' => 0x80, 'blue' => 0x90],
        'snow' => ['red' => 0xFF, 'green' => 0xFA, 'blue' => 0xFA],
        'springgreen' => ['red' => 0x00, 'green' => 0xFF, 'blue' => 0x7F],
        'steelblue' => ['red' => 0x46, 'green' => 0x82, 'blue' => 0xB4],
        'tan' => ['red' => 0xD2, 'green' => 0xB4, 'blue' => 0x8C],
        'teal' => ['red' => 0x00, 'green' => 0x80, 'blue' => 0x80],
        'thistle' => ['red' => 0xD8, 'green' => 0xBF, 'blue' => 0xD8],
        'tomato' => ['red' => 0xFF, 'green' => 0x63, 'blue' => 0x47],
        'turquoise' => ['red' => 0x40, 'green' => 0xE0, 'blue' => 0xD0],
        'violet' => ['red' => 0xEE, 'green' => 0x82, 'blue' => 0xEE],
        'wheat' => ['red' => 0xF5, 'green' => 0xDE, 'blue' => 0xB3],
        'white' => ['red' => 0xFF, 'green' => 0xFF, 'blue' => 0xFF], 
        'whitesmoke' => ['red' => 0xF5, 'green' => 0xF5, 'blue' => 0xF5],
        'yellow' => ['red' => 0xFF, 'green' => 0xFF, 'blue' => 0x00],
        'yellowgreen' => ['red' => 0x9A, 'green' => 0xCD, 'blue' => 0x32]
    ];
    
    /**
     * @return array
     */
    final public function convert($p_sColor)
    {
        $aColors = [
            'red' => 0x00,
            'green' => 0x00,
            'blue' => 0x00,
        ];
    
        if(array_key_exists($p_sColor, $this->m_aColorNames)) {
            // Named Color
            $aColors = $this->m_aColorNames[$p_sColor];
        } elseif (preg_match('/#?[a-z0-9]{3}/i', $p_sColor) === 1) {
            // HEX Shorthand
            list($aColors['red'], $aColors['green'], $aColors['blue']) = sscanf(ltrim($p_sColor), '%1x%1x%1x');
        } elseif (preg_match('/#?[a-z0-9]{6}/i', $p_sColor) === 1) {
            // HEX
            list($aColors['red'], $aColors['green'], $aColors['blue']) = sscanf(ltrim($p_sColor), '%2x%2x%2x');
        } elseif(preg_match('/rgba?\\([a-fx0-9%]\\)/i') === 1) {
            // RGB
            throw new \Exception('RGB values are not (yet) supported');
        } else {
            throw new \Exception('Unknow color "' . $p_sColor . '"');
        }

        return $aColors;
    }
}

/*EOF*/
