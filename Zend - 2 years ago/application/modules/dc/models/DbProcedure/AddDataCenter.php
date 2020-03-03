<?php

/**
 * Created by SAJAX
 * Comment:
 */
class Dc_Model_DbProcedure_AddDataCenter extends SJX_Procedure_Abstract
{
    protected $_name = 'AddDataCenter';
    protected $_usePDO = true;

    protected $_fields = [
        'name'                          => ['type' => 'string', 'name' => 'XName'],
        'prefcodeour'                   => ['type' => 'string', 'name' => 'XPrefCodeOur'],
        'prefcodedc'                    => ['type' => 'string', 'name' => 'XPrefCodeDC'],
        'domainofthehost'               => ['type' => 'string', 'name' => 'XDomainOfTheHost'],
        'dcemail'                       => ['type' => 'string', 'name' => 'XDCEmail'],
        'runeturl'                      => ['type' => 'string', 'name' => 'XRuNetUrl'],
        'dns1'                          => ['type' => 'string', 'name' => 'XDNS1'],
        'dns2'                          => ['type' => 'string', 'name' => 'XDNS2'],
        'dns3'                          => ['type' => 'string', 'name' => 'XDNS3'],
        'dns4'                          => ['type' => 'string', 'name' => 'XDNS4'],
        'dns5'                          => ['type' => 'string', 'name' => 'XDNS5'],
        'emailtemplt'                   => ['type' => 'string', 'name' => 'XEmailTemplt'],
        'showinleftmenu'                => ['type' => 'string', 'name' => 'XShowInLeftMenu'],
        'sortpos'                       => ['type' => 'string', 'name' => 'XSortPos'],
        'notifyonunitrelease'           => ['type' => 'string', 'name' => 'XNotifyOnUnitRelease'],
        'unitaccessemails'              => ['type' => 'string', 'name' => 'XUnitAccessEmails'],
        'visibletosorm'                 => ['type' => 'string', 'name' => 'XVisibleToSORM'],
        'filialcode'                    => ['type' => 'string', 'name' => 'XFilialCode'],
    ];
}