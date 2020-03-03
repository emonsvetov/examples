<?php

class Dc_Model_ControlPanels extends SJX_Model_Abstract
{
    public function __construct()
    {
        $this->_table = new Dc_Model_DbTable_ControlPanels();
    }
}