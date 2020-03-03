<?php

class Dc_Model_DbProcedure_AddDCUnit extends SJX_Procedure_Abstract
{
    protected $_name = 'AddDCUnit';
    protected $_usePDO = true;
    protected $_authority = 'edit_dcunits';

    protected $_fields = [
        'rackid'             => ['type' => 'integer',   'name' => 'XRackId'],
        'unittype'           => ['type' => 'int8',      'name' => 'XUnitType'],
        'servertype'         => ['type' => 'int8',      'name' => 'XServerType'],
        'devicetypeid'       => ['type' => 'integer',   'name' => 'XDeviceTypeId'],
        'netports'           => ['type' => 'integer',   'name' => 'XNetPorts'],
        'powerports'         => ['type' => 'integer',   'name' => 'XPowerPorts'],
        'conports'           => ['type' => 'integer',   'name' => 'XConPorts'],
        'isblade'            => ['type' => 'flag',      'name' => 'XIsBlade'],
        'qtyblades'          => ['type' => 'integer',   'name' => 'XQtyBlades'],
        'chassisunitid'      => ['type' => 'integer',   'name' => 'XChassisUnitId'],
        'bladepos'           => ['type' => 'integer',   'name' => 'XBladePos'],
        'unitn'              => ['type' => 'integer',   'name' => 'XUnitN'],
        'unitsize'           => ['type' => 'integer',   'name' => 'XUnitSize'],
        'codeour'            => ['type' => 'string',    'name' => 'XCodeOur'],
        'codedc'             => ['type' => 'string',    'name' => 'XCodeDC'],
        'codecli'            => ['type' => 'string',    'name' => 'XCodeCli'],
        'model'              => ['type' => 'string',    'name' => 'XModel'],
        'rackside'           => ['type' => 'int8',      'name' => 'XRackSide'],
        'vlan'               => ['type' => 'integer',   'name' => 'XVlan'],
        'cfg'                => ['type' => 'lvarchar',  'name' => 'Xcfg'],
        'osid'               => ['type' => 'string',    'name' => 'XOSId'],
        'tech_login'         => ['type' => 'string',    'name' => 'XTech_Login'],
        'tech_pass'          => ['type' => 'string',    'name' => 'XTech_Pass'],
        'swenblpass'         => ['type' => 'string',    'name' => 'XSwEnblPass'],
        'ipmilogin'          => ['type'      => 'string', 'name' => ' XIPMILogin',
                                 'authority' => ['view_dcunits_ipmi', 'edit_dcunits_ipmi']],
        'ipmipass'           => ['type'      => 'string', 'name' => ' XIPMIPass',
                                 'authority' => ['view_dcunits_ipmi', 'edit_dcunits_ipmi']],
        'ctrl_svc'           => ['type' => 'int8',      'name' => 'Xctrl_svc'],
        'ctrl_port'          => ['type' => 'integer',   'name' => 'Xctrl_port'],
        'ctrl_prot'          => ['type' => 'int8',      'name' => 'Xctrl_prot'],
        'maintn'             => ['type' => 'int8',      'name' => 'XMaintn'],
        'ipmi'               => ['type' => 'int8',      'name' => 'XIPMI'],
        'whoadmins'          => ['type' => 'int8',      'name' => 'XWhoAdmins'],
        'status'             => ['type' => 'int8',      'name' => 'XStatus'],
        'purpose'            => ['type' => 'string',    'name' => 'XPurpose'],
        'cmnt'               => ['type' => 'lvarchar',  'name' => 'XCmnt'],
        'dtinstall'          => ['type' => 'date',      'name' => 'XDtInstall'],
        'firmware'           => ['type' => 'string',    'name' => 'XFirmware'],
        'cons_uid'           => ['type' => 'integer',   'name' => 'XCons_uid'],
        'powerwatts'         => ['type' => 'integer',   'name' => 'XPowerWatts'],
        //'ismonitoring'       => ['type' => 'int8',      'name' => 'XIsMonitoring'],
        'unitusage'          => ['type' => 'integer',   'name' => 'XUnitUsage'],
        'assumedblock'       => ['type' => 'string',    'name' => 'XAssumedBlock'],
        'lanadmblock'        => ['type' => 'int8',      'name' => 'XLanAdmBlock'],
        'stategrant'         => ['type' => 'flag',      'name' => 'XStateGrant'],
        'usbinfo'            => ['type' => 'lvarchar',  'name' => 'XUsbInfo'],
        'usechassisipmiport' => ['type' => 'flag',      'name' => 'XUseChassisIpmiPort'],
        'createdbysupport'   => ['type' => 'flag',      'name' => 'XCreatedBySupport'],
        'useterminalchars'   => ['type' => 'flag',      'name' => 'XUseTerminalChars'],
        'adminuserid'        => ['type' => 'integer',   'name' => 'XAdminUserId'],
        'igtid'              => ['type' => 'integer',   'name' => 'XIGTid'],
        'hypervisorunitid'   => ['type' => 'integer',   'name' => 'XHypervisorUnitId'],
        'hypervisor'         => ['type' => 'flag',      'name' => 'XHypervisor'],
        'vserverid'          => ['type' => 'integer',   'name' => 'XVserverId']
    ];

    public function __construct($unitType = null)
    {
        if ($unitType == Dc_Model_UnitTypes::ETHERNET_SWITCH) {
            $this->setAuthority(['tech_login', 'tech_pass', 'swenblpass'], ['edit_eth_sw_pass']);
        } elseif ($unitType == Dc_Model_UnitTypes::KVM) {
            $this->setAuthority(['tech_login', 'tech_pass', 'swenblpass'], ['edit_kvm_sw_pass']);
        } elseif ($unitType == Dc_Model_UnitTypes::POWER_SWITCH) {
            $this->setAuthority(['tech_login', 'tech_pass', 'swenblpass'], ['edit_pwr_sw_pass']);
        } elseif ($unitType == Dc_Model_UnitTypes::SERVER) {
            $this->setAuthority(['tech_login', 'tech_pass', 'swenblpass'], ['edit_srv_sw_pass']);
        }
    }
}
