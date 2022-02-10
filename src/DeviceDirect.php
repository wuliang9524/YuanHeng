<?php

namespace Logan\Yuanheng;

use GuzzleHttp\Client as HttpClient;
use Logan\Yuanheng\exceptions\InitRuntimeException;

class DeviceDirect
{
    /**
     * 平台域名 带端口
     *
     * @var string
     */
    protected $domain;

    /**
     * 设备服务器地址 带端口
     *
     * @var string
     */
    protected $host;

    /**
     * 平台集成商 token
     *
     * @var string
     */
    protected $token;

    /**
     * GuzzleHttp 实例
     *
     * @var GuzzleHttp\Client
     */
    protected $httpClient = null;

    /**
     * 构造方法
     *
     * @param string $domain    平台站点域名
     * @param string $token     集成商 token
     * @author LONG <1121116451@qq.com>
     * @version version
     * @date 2022-02-10
     */
    public function __construct(string $domain, string $host, string $token)
    {
        $token  = trim($token);
        $host   = rtrim($host, '/');
        $domain = rtrim($domain, '/');

        if (empty($token)) {
            throw new InitRuntimeException(['code' => 0, 'msg' => 'token is empty']);
        }
        if (empty($domain)) {
            throw new InitRuntimeException(['code' => 0, 'msg' => 'domain is empty']);
        }
        if (empty($host)) {
            throw new InitRuntimeException(['code' => 0, 'msg' => 'host is empty']);
        }

        $this->token      = $token;
        $this->host       = $host;
        $this->domain     = $domain;
        $this->httpClient = new HttpClient();
    }

    /**
     * 下发人员到设备
     *
     * @param string $wanIp     4G 卡 WANIP
     * @param int $port         映射端口
     * @param string $devCode   设备编号
     * @param string $code      项目编号
     * @param string $idCode    人员身份证标号
     * @param string $name      人员名称
     * @return void
     * @author LONG <1121116451@qq.com>
     * @version version
     * @date 2022-02-10
     */
    public function addDeviceWorker(
        string $wanIp,
        int $port,
        string $devCode,
        string $code,
        string $idCode,
        string $name
    ) {
        $url = $this->host . "/AttendanceMachine/AddPersonToMachine";

        $response = $this->httpClient->request('GET', $url, [
            'query' => [
                'IP'        => $wanIp,
                'Port'      => $port,
                'deviceKey' => $devCode,
                'ProjectID' => $code,
                'IDcardNum' => $idCode,
                'Name'      => $name,
                'Token'     => $this->token,
                'SdUrlNew'  => $this->host . '/API/AppInterface/',
                '_'         => time() . '000'
            ],
        ])
            ->getBody()
            ->getContents();

        return json_decode($response, true);
    }


    /**
     * 下发人员照片到设备
     *
     * @param string $wanIp     4G 卡 WANIP
     * @param int $port         映射端口
     * @param string $devCode   设备编号
     * @param string $code      项目编号
     * @param string $idCode    人员身份证标号
     * @param string $name      人员名称
     * @param int $imageId      头像文件编号
     * @return void
     * @author LONG <1121116451@qq.com>
     * @version version
     * @date 2022-02-10
     */
    public function addDeviceWorkerImage(
        string $wanIp,
        int $port,
        string $devCode,
        string $code,
        string $idCode,
        string $name,
        int $imageId
    ) {
        $url = $this->host . "/AttendanceMachine/PushFaceToMachine";

        $response = $this->httpClient->request('GET', $url, [
            'query' => [
                'IP'         => $wanIp,
                'Port'       => $port,
                'deviceKey'  => $devCode,
                'ProjectID'  => $code,
                'IDcardNum'  => $idCode,
                'Name'       => $name,
                'Token'      => $this->token,
                'DocumentID' => $imageId,
                'SdUrlNew'   => $this->host . '/API/AppInterface/',
                '_'          => time() . '000'
            ],
        ])
            ->getBody()
            ->getContents();

        return json_decode($response, true);
    }

    /**
     * 将人员从设备删除
     *
     * @param string $wanIp
     * @param int $port
     * @param string $devCode
     * @param string $code
     * @param string $idCode
     * @param string $name
     * @return void
     * @author LONG <1121116451@qq.com>
     * @version version
     * @date 2022-02-10
     */
    public function deleteDeviceWorker(
        string $wanIp,
        int $port,
        string $devCode,
        string $code,
        string $idCode,
        string $name
    ) {
        $url = $this->host . "/AttendanceMachine/DeletePersonsToMachine";

        $response = $this->httpClient->request('GET', $url, [
            'query' => [
                'IP'         => $wanIp,
                'Port'       => $port,
                'deviceKey'  => $devCode,
                'ProjectID'  => $code,
                'IDcardNum'  => $idCode,
                'Name'       => $name,
                'Token'      => $this->token,
                'SdUrlNew'   => $this->host . '/API/AppInterface/',
                '_'          => time() . '000'
            ],
        ])
            ->getBody()
            ->getContents();

        return json_decode($response, true);
    }
}
