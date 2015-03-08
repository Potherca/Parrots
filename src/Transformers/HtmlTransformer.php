<?php

namespace Potherca\Parrots\Transformers;

class HtmlTransformer
{
    final public function transform(array $p_aData)
    {
        $sTemplate = $this->buildTemplate();

        $sPrefix = '';
        $sSubject = '';
        $sColor = 'black';
        $sBackgroundColor = 'white';

        if (isset($p_aData['color'])) {
            $sColor = $p_aData['color'];
        }

        if (isset($p_aData['background-color'])) {
            $sBackgroundColor = $p_aData['background-color'];
        }

        if (isset($p_aData['prefix'])) {
            $sPrefix = $p_aData['prefix'];
        }

        if (isset($p_aData['subject'])) {
            $sSubject = $p_aData['subject'];
        }

        return sprintf(
            $sTemplate,
            $sBackgroundColor,
            $sColor,
            htmlentities($sPrefix),
            htmlentities($sSubject)
        );
    }

    /**
     * @return string
     */
    private function buildTemplate()
    {
        $sTemplate = <<<HTML
<!DOCTYPE html><html>
<style>
    html, body {
        background-color: %s;
        color: %s;
        position: relative;
        text-transform: uppercase;
        height: 100%%;
    }

    h1 {
        font: bold 6em arial, helvetica, sans-serif;
        margin: 0;
        position: absolute;
        text-align: center;
        top: 50%%;
        transform: translateY(-50%%);
        width: 100%%;
    }
</style>
<h1 class="prefix">%s <span class="subject">%s</span></h1>
</html>
HTML;

        return $sTemplate;
    }
}

/*EOF*/
