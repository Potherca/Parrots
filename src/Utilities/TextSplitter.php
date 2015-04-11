<?php

namespace Potherca\Parrots\Utilities;

class TextSplitter implements SplitterInterface
{
    /** @var array */
    private $m_aWords = [];
    /** @var string */
    private $m_sText = '';
    /** @var int */
    private $m_iWordCount = 0;
    /** @var int */
    private $m_iCharacters = 0;
    /** @var int */
    private $m_iCharactersPerWord = 0;
    /** @var  int */
    private $m_iWordsPerLine = 0;
    /** @var int */
    private $m_iLineLength = 0;

    final public function split($p_sText)
    {
        $this->parse($p_sText);

        if ($this->containsWordLongerThanAverageLineLength()) {
            $sSplitText = $this->splitByLineLength($p_sText);
        } else {
            $sSplitText = $this->splitByWordCount($p_sText);
        }

        return $sSplitText;
    }

    private function parse($p_sText)
    {
        $this->m_aWords = $this->splitTextToWords($p_sText);

        $this->countWords();
        $this->countCharacters($p_sText);

        $this->calculateAverageCharactersPerWord();
        $this->calculateAverageWordsPerLine();
        $this->calculateAverageLineLength();
    }

    private function calculateAverageWordsPerLine()
    {
        $this->m_iWordsPerLine = 0;
        $iLines = 0;
        while ($iLines <= $this->m_iWordsPerLine) {
            $iLines++;
            $this->m_iWordsPerLine = ceil($this->m_iWordCount / $iLines);
        }
    }

    private function countWords()
    {
        $this->m_iWordCount = count($this->m_aWords);
    }

    private function countCharacters($p_sText)
    {
        $this->m_iCharacters = strlen($p_sText) - $this->m_iWordCount + 1;
    }

    private function calculateAverageCharactersPerWord()
    {
        $this->m_iCharactersPerWord = $this->m_iCharacters / $this->m_iWordCount;
    }

    private function calculateAverageLineLength()
    {
        $this->m_iLineLength = (int) ceil(
            $this->m_iWordsPerLine
            * $this->m_iCharactersPerWord
            + ($this->m_iWordsPerLine - 1)
        );
    }

    private function splitTextToWords($p_sText)
    {
        return explode(' ', $p_sText);
    }

    /**
     * @return string
     */
    private function splitByWordCount()
    {
        $aWords = $this->m_aWords;
        $aLines = [];

        while ($this->containsWords($aWords)) {
            $sLine = '';
            while ($this->containsWords($aWords)
                && $this->containsLessWordsThanAverage($sLine)
            ) {
                $sLine .= array_shift($aWords) . ' ';
            }
            $aLines[] = trim($sLine);
        }
        $sResult = implode("\n", $aLines);

        return $sResult;
    }

    /**
     * @return string
     */
    private function splitByLineLength($p_sText)
    {
        return wordwrap($p_sText, $this->m_iLineLength, "\n", true);
    }

    private function containsWordLongerThanAverageLineLength()
    {
        $bLongerThanAverage = false;
        foreach ($this->m_aWords as $t_sWord) {
            if (strlen($t_sWord) > $this->m_iLineLength) {
                $bLongerThanAverage = true;
                break;
            }
        }

        return $bLongerThanAverage;
    }

    /**
     * @param string $p_sLine
     *
     * @return bool
     */
    private function containsLessWordsThanAverage($p_sLine)
    {
        $aWords = $this->splitTextToWords($p_sLine);
        $iWordCount = count($aWords);
        return ($iWordCount <= $this->m_iWordsPerLine);
    }

    /**
     * @param $aWords
     *
     * @return bool
     */
    private function containsWords($aWords)
    {
        return count($aWords) > 0;
    }
}


/*EOF*/
