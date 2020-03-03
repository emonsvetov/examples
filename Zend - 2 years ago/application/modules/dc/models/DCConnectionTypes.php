<?php

class Dc_Model_DCConnectionTypes extends SJX_Model_Abstract
{
    CONST ETHERNET = 'N';
    CONST POWER = 'P';
    CONST KWM = 'C';

    public function __construct()
    {
        $this->_table = new Dc_Model_DbTable_DCConnectionTypes();
    }
}
