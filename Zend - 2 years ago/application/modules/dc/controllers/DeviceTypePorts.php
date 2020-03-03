<?php
/**
 * Created by PhpStorm.
 * User: e.monsvetov
 * Date: 23/05/16
 * Time: 15:26
 */

class Dc_Controller_DeviceTypePorts extends SJX_Controller_Abstract
{
    public function portsAction()
    {
        $deviceTypeId = $this->getRequest()->getParam('devicetypeid', null);

        if ($deviceTypeId) {
            $deviceTypePortsModel = new Dc_Model_DeviceTypePorts();
            $ports = $deviceTypePortsModel->getAllByDeviceTypeId($deviceTypeId);

            $this->_returnJson([
                'success' => true,
                'items'   => $ports,
            ]);
        }
    }
}