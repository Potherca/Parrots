<?php

namespace Potherca\Parrots;

interface DataInterface
{
    /** @return string */
    public function getBackgroundColor();

    /** @return string */
    public function getColor();

    /** @return string */
    public function getPrefix();

    /** @return string */
    public function getSubject();

    /** @return string */
    public function getText();

    /** @return string */
    public function getType();

    /**
     * @param array $p_aData
     */
    public function setFromArray(array $p_aData);
}
