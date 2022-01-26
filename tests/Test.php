<?php

declare(strict_types=1);

use Logan\Yuanheng\Client;
use PHPUnit\Framework\TestCase;

class Test extends TestCase
{
    protected $domain         = 'http://ccsmz.fsyzt.cn:8091';
    protected $domainIntranet = 'http://192.168.101.2:8095';
    protected $token          = 'bbc0e8f84505098ececf97de77698bd4';

    public function testAddGroupRequest(): void
    {
        // config
        $domain = 'http://ccsmz.fsyzt.cn:8091';
        $domainIntranet = 'http://192.168.101.2:8095';
        $token = 'bbc0e8f84505098ececf97de77698bd4';

        // params
        $name       = "水电安装专业分包";
        $proUid     = "C4D25D237C1A2C5BBEE436019F09A267";
        $companyLicense = "91440000214401707F";
        $headName   = "高建雄";
        $headPhone  = "18682097379";
        $headIdCard = "440923199007290773";
        $emName     = "黄素娟";
        $emPhone    = "15976570986";
        $proContent = "水电安装专业分包";
        $amount     = "19000000";
        $contractId = '';

        // $instance = new Client($domain, $domainIntranet, $token);
        // $res = $instance->addGroup(
        //     $name,
        //     $proUid,
        //     $companyLicense,
        //     $headName,
        //     $headPhone,
        //     $headIdCard,
        //     $emName,
        //     $emPhone,
        //     $proContent,
        //     $amount,
        //     $contractId
        // );

        // $this->assertArrayHasKey('Success', $res);
        // $this->assertArrayHasKey('ErrMsg', $res);
    }

    public function testAddWorkerInfoRequest()
    {
    }

    public function testUploadFileRequest()
    {
        // $path = 'http://file.global8.cn/UserApp/IdCardPic/61ea12927c167.png';
        $path = '/mnt/c/Users/ASUS/Pictures/61e13e4a1ef86.png';

        // $instance = new Client($this->domain, $this->domainIntranet, $this->token);
        // $res = $instance->uploadFile($path);
    }

    public function testDownloadFileRequest()
    {
        $docId = 5371507;
        $path = '/www/wwwroot/ubuntu/yuanheng/download';

        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }

        $instance = new Client($this->domain, $this->domainIntranet, $this->token);
        $res = $instance->downloadFile($docId, $path);
    }

    public function testGetBase64Request()
    {
        $docId = 5371507;

        // $instance = new Client($this->domain, $this->domainIntranet, $this->token);
        // $res = $instance->getBase64($docId);
    }

    public function testGetDevicesRequest()
    {
        $proUid = 'C4D25D237C1A2C5BBEE436019F09A267';

        $instance = new Client($this->domain, $this->domainIntranet, $this->token);
        // $res = $instance->getDevices($proUid);
    }
}
