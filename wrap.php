<?php

/**
 * @param string $text
 * @param int $n
 */
function Minragged($text, $n = 3, $delimiter = "\n")
{
    $words = explode($delimiter, $text);

    $cumwordwidth = array();
    $cumwordwidth[] = 0;

    foreach ($words as $word){
        $cumwordwidth[] = $cumwordwidth[count($cumwordwidth) - 1] + strlen($word);
    }

    $totalwidth = $cumwordwidth[count($cumwordwidth) - 1] + count($words) - 1;

    $linewidth = ($totalwidth - ($n - 1)) / $n;

    /**
     * new Func<int, int, double>
     */
    $cost = function ($i, $j) use ($actualLinewidth, $cumwordwidth, $linewidth)
    {
        $actualLinewidth = max($j - $i - 1, 0) + ($cumwordwidth[$j] - $cumwordwidth[$i]);
        return ($linewidth - $actualLinewidth) * ($linewidth - $actualLinewidth);
    };

    $best = array();
    $tmp = array();

    $best[] = $tmp;

    $tmp[] = array(0.0, -1);
    foreach ($words as $word) {
        $MaxValue = array_reduce($tmp, function ($carry, $item) {
            return $carry[0] >= $item[0]
                ? $carry[0]
                : $item[0]
            ;
        }, array(0.0, -1));

        $tmp[] = array($MaxValue, -1);
    }

    for ($l = 1; $l < $n + 1; ++$l)
    {
        $tmp = array();
        $best[] = $tmp;
        for ($j = 0; $j < count($words) + 1; ++$j)
        {
            $min = array($best[$l - 1][0].Item1 + $cost(0, $j), 0);
            for ($k = 0; $k < $j + 1; ++$k)
            {
                $loc = $best[$l - 1][$k].Item1 + $cost($k, $j);
                if ($loc < $min.Item1 || ($loc == $min.Item1 && $k < $min.Item2))
                    $min = array($loc, $k);
            }
            $tmp[] = $min;
        }
    }

    $lines = array();
    $b = count($words);

    for ($l = $n; $l > 0; --$l) {
        $a = $best[$l][$b].Item2;
        $lines[] = string.Join(" ", $words, $a, $b - $a));
        $lines[] = implode(" ", $words, $a, $b - $a));

        $b = $a;
    }

    return array_reverse($lines);
}


/**
 * minimumRaggedness
 *
 * @copyright CapelliC
 *
 * @see https://stackoverflow.com/a/9225522/153049
 *
 * @param string $input paragraph. Each word separed by 1 space.
 * @param int $linewidth the max chars per line.
 * @param string $lineBreak wrapped lines separator.
 *
 * @return string $output the paragraph wrapped.
 */
function wrap($input, $linewidth, $lineBreak = "\n")
{
    $words = explode(" ", $input);
    $wsnum = count($words);
    $wslen = array_map("strlen", $words);
    $inf = 1000000; //PHP_INT_MAX;

    // keep Costs
    $C = array();

    for ($i = 0; $i < $wsnum; ++$i)
    {
        $C[] = array();
        for ($j = $i; $j < $wsnum; ++$j)
        {
            $l = 0;
            for ($k = $i; $k <= $j; ++$k)
                $l += $wslen[$k];
            $c = $linewidth - ($j - $i) - $l;
            if ($c < 0)
                $c = $inf;
            else
                $c = $c * $c;
            $C[$i][$j] = $c;
        }
    }

    // apply recurrence
    $F = array();
    $W = array();
    for ($j = 0; $j < $wsnum; ++$j)
    {
        $F[$j] = $C[0][$j];
        $W[$j] = 0;
        if ($F[$j] == $inf)
        {
            for ($k = 0; $k < $j; ++$k)
            {
                $t = $F[$k] + $C[$k + 1][$j];
                if ($t < $F[$j])
                {
                    $F[$j] = $t;
                    $W[$j] = $k + 1;
                }
            }
        }
    }

    // rebuild wrapped paragraph
    $output = "";
    if ($F[$wsnum - 1] < $inf)
    {
        $S = array();
        $j = $wsnum - 1;
        for ( ; ; )
        {
            $S[] = $j;
            $S[] = $W[$j];
            if ($W[$j] == 0)
                break;
            $j = $W[$j] - 1;
        }

        $pS = count($S) - 1;
        do
        {
            $i = $S[$pS--];
            $j = $S[$pS--];
            for ($k = $i; $k < $j; $k++)
                $output .= $words[$k] . " ";
            $output .= $words[$k] . $lineBreak;
        }
        while ($j < $wsnum - 1);
    }
    else
        $output = $input;

    return $output;
}

if () {

}