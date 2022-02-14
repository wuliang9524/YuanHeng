<?php
declare(strict_types=1);

use Logan\Yuanheng\DeviceDirect;
use PHPUnit\Framework\TestCase;

class DeviceTest extends TestCase
{
    protected $domain         = 'http://ccsmz.fsyzt.cn:8091';
    protected $domainIntranet = 'http://192.168.101.2:8095';
    protected $token          = '';

    public function testAddDeviceWorker(): void
    {
        // config
        $domain = 'http://ccsmz.fsyzt.cn:8091';
        $host = 'http://183.239.33.103:4250';
        $token = '';

        // params
        $wanIp   = '10.0.0.0';
        $port    = 8090;
        $devCode = 'ttyghjukl';
        $code    = 'tyuio';
        $idCode  = '1514565160';
        $name    = '测试';

        $instance = new DeviceDirect($domain, $host, $token);
        $res = $instance->addDeviceWorker(
            $wanIp,
            $port,
            $devCode,
            $code,
            $idCode,
            $name
        );
    }
}
