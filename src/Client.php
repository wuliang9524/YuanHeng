<?php

namespace Logan\Yuanheng;

use finfo;
use RuntimeException;
use GuzzleHttp\Client as HttpClient;
use Logan\Yuanheng\exceptions\InitRuntimeException;
use Logan\Yuanheng\exceptions\UnknownFileTypeException;
use Logan\Yuanheng\exceptions\ExtensionNotExistException;

class Client
{
    /**
     * 平台域名
     *
     * @var string
     */
    protected $domain;

    /**
     * 平台 4G 网络内网地址
     *
     * @var string
     */
    protected $domainIntranet;

    /**
     * 平台集成商 token
     *
     * @var string
     */
    protected $token;

    /**
     * 是否使用内网地址
     *
     * @var bool
     */
    protected $isIntranet = false;

    /**
     * 使用的接口地址
     *
     * @var string
     */
    protected $host;

    /**
     * GuzzleHttp 实例
     *
     * @var GuzzleHttp\Client
     */
    protected $httpClient = null;

    public function __construct(string $domain, string $domainIntranet, string $token)
    {
        $token          = trim($token);
        $domain         = rtrim($domain, '/');
        $domainIntranet = rtrim($domainIntranet, '/');

        if (empty($token)) {
            throw new InitRuntimeException(['code' => 0, 'msg' => 'token is empty']);
        }
        if (empty($domain)) {
            throw new InitRuntimeException(['code' => 0, 'msg' => 'domain is empty']);
        }
        if (empty($domainIntranet)) {
            throw new InitRuntimeException(['code' => 0, 'msg' => 'domainIntranet is empty']);
        }

        $this->token          = $token;
        $this->domain         = $domain;
        $this->host           = $domain;
        $this->domainIntranet = $domainIntranet;
        $this->httpClient     = new HttpClient();
    }

    /**
     * 设置使用内网地址
     *
     * @param bool $value   true Or false
     * @return void
     * @author LONG <1121116451@qq.com>
     * @version version
     * @date 2022-01-20
     */
    public function setIntranet(bool $value = false)
    {
        $this->isIntranet = $value;
        $this->host       = $this->domainIntranet;
        return $this;
    }

    /**
     * 
     *
     * @param string $name      
     * @param string $proUid    
     * @param string $companyLicense    
     * @param [type] $headName  
     * @param [type] $headPhone
     * @param [type] $headIdCard
     * @param [type] $emName
     * @param [type] $emPhone
     * @param [type] $proContent
     * @param [type] $amount
     * @param [type] $contractId
     * @return array
     * @author LONG <1121116451@qq.com>
     * @version version
     * @date 2022-01-20
     */

    /**
     * 添加班组
     *
     * @param string $name             班组名称
     * @param string $proUid           项目唯一标识码
     * @param string $companyLicense   对应的五方公司营业执照编号
     * @param string|null $headName    班组长姓名
     * @param string|null $headPhone   班组长联系方式
     * @param string|null $headIdCard  班组长身份证号
     * @param string|null $emName      紧急联系人
     * @param string|null $emPhone     紧急联系人电话
     * @param string|null $proContent  工程承包内容
     * @param int|null $amount         承包合同金额
     * @param string|null $contractId  合同文件在文件系统上的ID
     * @return void
     * @author LONG <1121116451@qq.com>
     * @version version
     * @date 2022-02-28
     */
    public function addGroup(
        string $name,
        string $proUid,
        string $companyLicense,
        ?string $headName   = null,
        ?string $headPhone  = null,
        ?string $headIdCard = null,
        ?string $emName     = null,
        ?string $emPhone    = null,
        ?string $proContent = null,
        ?int    $amount     = null,
        ?string $contractId = null
    ) {
        $url = $this->host . '/API/Project/AddProjectTeam';

        $req = [
            'Token'         => $this->token,      // 账号验证
            'ProjectNumber' => $proUid,           // 项目唯一标识码，可在区平台项目详细信息处获取
            'CompanyNumber' => $companyLicense,   // 五方公司之一的公司营业执照编号
            'TeamName'      => $name,             // 班组名称
            'ProjectPart'   => $proContent,       // 工程承包内容
            'ContractSum'   => $amount,           // 承包合同金额
            'Name'          => $headName,         // 班组长姓名
            'Tel'           => $headPhone,        // 班组长联系方式
            'IDNum'         => $headIdCard,       // 班组长身份证号
            'EmerPeople'    => $emName,           // 紧急联系人
            'EmerTel'       => $emPhone,          // 紧急联系人电话
            'ContractID'    => $contractId,       // 合同文件在文件系统上的ID（调用接口3.10的返回结果），必须上传合同
        ];

        $response = $this->httpClient->request('POST', $url, [
            'form_params' => $req
        ])
            ->getBody()
            ->getContents();

        return json_decode($response, true);
    }

    /**
     * 添加/编辑 工人信息
     *
     * @param string $idCard            身份证号
     * @param string $name              姓名
     * @param int $sex                  性别
     * @param string $nation            民族
     * @param string $birthDate         出生日期(只须上传日期)
     * @param string $address           住址
     * @param string $census            户籍
     * @param string $phone             电话
     * @param string $emName            紧急联系人
     * @param string $emPhone           紧急联系人电话
     * @param int $cultrue              文化程度
     * @param int $health               健康状况
     * @param int $workerType           工种
     * @param string $companyName       公司名称
     * @param string $companyLicense    社会统一信用代码
     * @param string|null $groupName    班组名称
     * @return void
     * @author LONG <1121116451@qq.com>
     * @version version
     * @date 2022-02-28
     */
    public function addWorkerInfo(
        string $idCard,
        string $name,
        int $sex,
        string $nation,
        string $birthDate,
        string $address,
        string $census,
        string $phone,
        string $emName,
        string $emPhone,
        int $cultrue,
        int $health,
        int $workerType,
        string $companyName,
        string $companyLicense,
        ?string $groupName = null
    ) {
        $url = $this->host . '/API/People/AddAttendancePeople';

        $params = [
            'IdNum'         => $idCard,           // 身份证号
            'Name'          => $name,             // 姓名
            'Sex'           => $sex,              // 性别
            'Nation'        => $nation,           // 民族
            'Birthday'      => $birthDate,        // 出生日期(只须上传日期)
            'Address'       => $address,          // 住址
            'Native'        => $census,           // 户籍
            'Phone'         => $phone,            // 电话
            'EmerPeople'    => $emName,           // 紧急联系人
            'EmerPhone'     => $emPhone,          // 紧急联系人电话
            'Culture'       => $cultrue,          // 文化程度
            'Health'        => $health,           // 健康状况
            'WorkerType'    => $workerType,       // 工种
            'CompanyName'   => $companyName,      // 公司名称
            'CompanyNumber' => $companyLicense,   // 社会统一信用代码
            'TeamName'      => $groupName,        // （非必填）班组名称
        ];

        $req = [
            'Token'    => $this->token,
            'PostJson' => json_encode($params, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        ];

        $response = $this->httpClient->request('POST', $url, [
            'form_params' => $req
        ])
            ->getBody()
            ->getContents();

        return json_decode($response, true);
    }

    /**
     * 补充工人信息
     * 旧 编辑人员信息接口
     *
     * @param string $idCard            身份证号
     * @param string $name              姓名
     * @param int $sex                  性别
     * @param string $nation            民族
     * @param string $birthDate         出生日期(只须上传日期)
     * @param string $address           住址
     * @param string $census            户籍
     * @param string $phone             电话
     * @param string $emName            紧急联系人
     * @param string $emPhone           紧急联系人电话
     * @param int $cultrue              文化程度
     * @param int $health               健康状况
     * @param int $issue                签发机关
     * @param int $faceId               人脸照片文件编号
     * @param int $cardFrontId          身份证正面文件编号
     * @param int $cardBackId           身份证反面文件编号
     * @param int $startDate            身份证有效期(开始日期) Y-m-d
     * @param int $endDate              身份证有效期(结束日期) Y-m-d 或 "长期"
     * @param int $workerType           工种
     * @param string $companyName       公司名称
     * @param string $companyLicense    社会统一信用代码
     * @param string|null $groupName    班组名称
     * @return void
     * @author LONG <1121116451@qq.com>
     * @version version
     * @date 2022-02-28
     */
    public function addWorkerInfoOld(
        string $idCard,
        string $name,
        int $sex,
        string $nation,
        string $birthDate,
        string $address,
        string $census,
        string $phone,
        string $emName,
        string $emPhone,
        int $cultrue,
        int $health,
        string $issue,
        int $faceId,
        int $cardFrontId,
        int $cardBackId,
        string $startDate,
        string $endDate,
        int $workerType,
        string $companyName,
        string $companyLicense,
        ?string $groupName = null
    ) {
        $url = $this->host . '/API/People/AddAttendancePeople_Old';

        $params = [
            'IdNum'               => $idCard,           // 身份证号
            'Name'                => $name,             // 姓名
            'Sex'                 => $sex,              // 性别
            'Nation'              => $nation,           // 民族
            'Birthday'            => $birthDate,        // 出生日期(只须上传日期)
            'Address'             => $address,          // 住址
            'Native'              => $census,           // 户籍
            'Phone'               => $phone,            // 电话
            'EmerPeople'          => $emName,           // 紧急联系人
            'EmerPhone'           => $emPhone,          // 紧急联系人电话
            'Culture'             => $cultrue,          // 文化程度
            'Health'              => $health,           // 健康状况
            'QianFaJiGuan'        => $issue,            //签发机关
            'DocumentID'          => $faceId,           // 人脸照片文件编号
            'Photo'               => $cardFrontId,      // 身份证正面文件编号
            'IDcardNumPicFanMian' => $cardBackId,       // 身份证反面文件编号
            'QiXianQiShi'         => $startDate,        // 身份证有效期(开始日期)
            'QiXianShiXiao'       => $endDate,          // 身份证有效期(结束日期)
            'WorkerType'          => $workerType,       // 工种
            'CompanyName'         => $companyName,      // 公司名称
            'CompanyNumber'       => $companyLicense,   // 社会统一信用代码
            'TeamName'            => $groupName,        // （非必填）班组名称
        ];

        $req = [
            'Token'    => $this->token,
            'PostJson' => json_encode($params, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        ];

        $response = $this->httpClient->request('POST', $url, [
            'form_params' => $req
        ])
            ->getBody()
            ->getContents();

        return json_decode($response, true);
    }

    /**
     * 更新工人信息头像
     * 住建平台的人员信息详情头像
     *
     * @param string $idCard
     * @param string $headImgId
     * @return void
     * @author LONG <1121116451@qq.com>
     * @version version
     * @date 2022-01-24
     */
    public function updateHeadImg(
        string $idCard,
        string $headImgId
    ) {
        $url = $this->host . '/API/People/UpdateEmployeePhotoID';

        $req = [
            'IDcardNum' => $idCard,
            'PhotoID'   => $headImgId,
        ];

        $response = $this->httpClient->request('POST', $url, [
            'form_params' => $req
        ])
            ->getBody()
            ->getContents();

        return json_decode($response, true);
    }


    /**
     * 添加/更新人员下发到设备的人脸库头像
     *
     * @param string $idCard
     * @param string $faceLibImg
     * @param int $sort
     * @return void
     * @author LONG <1121116451@qq.com>
     * @version version
     * @date 2022-01-24
     */
    public function updateFaceLibImg(
        string $idCard,
        string $faceLibImg,
        int $sort = 1
    ) {
        $url = $this->host . '/API/People/UploadCollectedPhoto';

        $req = [
            'Token'     => $this->token,
            'IDCardNum' => $idCard,
            'FaceID'    => $sort,
            'imgBase64' => $faceLibImg,
        ];

        $response = $this->httpClient->request('POST', $url, [
            'form_params' => $req
        ])
            ->getBody()
            ->getContents();

        return json_decode($response, true);
    }

    /**
     * 查询项目工人信息
     *
     * @param string $code  项目编号
     * @param [type] $idCode    工人身份编号
     * @return void
     * @author LONG <1121116451@qq.com>
     * @version version
     * @date 2022-02-10
     */
    public function queryProjectWorker(string $code, string $idCode)
    {
        $url = $this->host . "/API/AppInterface/GetProjectEmployee";

        $url .= "?ProjectID={$code}";
        $url .= "&Token={$this->token}";
        $url .= "&IDcardNum={$code}";

        $response = $this->httpClient->request('POST', $url)
            ->getBody()
            ->getContents();

        return json_decode($response, true);
    }

    /**
     * 工人进场
     *
     * @param [type] $proUid
     * @param [type] $idCard
     * @param [type] $dateTime
     * @param [type] $emName
     * @param [type] $emPhone
     * @param [type] $bank
     * @param [type] $bankAccount
     * @param [type] $contractId
     * @param [type] $groupName
     * @param [type] $proContent
     * @param bool $isIn
     * @return void
     * @author LONG <1121116451@qq.com>
     * @version version
     * @date 2022-01-20
     */
    public function addProjectWorker(
        $proUid,
        $idCard,
        $dateTime,
        $emName,
        $emPhone,
        $bank,
        $bankAccount,
        $contractId,
        $groupName,
        $proContent,
        bool $isIn = true
    ) {
        $url = $this->host . '/API/People/AddAttendancePeople';

        $req = [
            'ProjectPart'   => $proContent,    // 工程承包内容
            'ProjectNumber' => $proUid,        // 项目唯一标识码，可在区平台项目详细信息处获取
            'IDcardNum'     => $idCard,        // 人员身份证号
            'IsIn'          => $isIn,          // 进退场标识（true:进场，false:退场）
            'HappenTime'    => $dateTime,      // 进/退场时间
            'EmerPeople'    => $emName,        // 紧急联系人
            'EmerTel'       => $emPhone,       // 紧急联系人电话
            'Bank'          => $bank,          // 开户银行
            'Account'       => $bankAccount,   // 银行卡账号
            'ContractID'    => $contractId,    // 合同文件在文件系统上的ID
            'TeamName'      => $groupName,     // 人员所在单位名称/班组名称。
        ];

        $response = $this->httpClient->request('POST', $url, [
            'form_params' => $req
        ])
            ->getBody()
            ->getContents();

        return json_decode($response, true);
    }

    /**
     * 工人退场
     *
     * @param [type] $proUid
     * @param [type] $idCard
     * @param [type] $dateTime
     * @param [type] $emName
     * @param [type] $emPhone
     * @param [type] $bank
     * @param [type] $bankAccount
     * @param [type] $contractId
     * @param [type] $groupName
     * @param [type] $proContent
     * @param bool $isIn
     * @return void
     * @author LONG <1121116451@qq.com>
     * @version version
     * @date 2022-01-20
     */
    public function exitProjectWorker(
        $proUid,
        $idCard,
        $dateTime,
        $emName,
        $emPhone,
        $bank,
        $bankAccount,
        $contractId,
        $groupName,
        $proContent,
        bool $isIn = false
    ) {
        $url = $this->host . '/API/People/AddAttendancePeople';

        $req = [
            'ProjectPart'   => $proContent,    // 工程承包内容
            'ProjectNumber' => $proUid,        // 项目唯一标识码，可在区平台项目详细信息处获取
            'IDcardNum'     => $idCard,        // 人员身份证号
            'IsIn'          => $isIn,          // 进退场标识（true:进场，false:退场）
            'HappenTime'    => $dateTime,      // 进/退场时间
            'EmerPeople'    => $emName,        // 紧急联系人
            'EmerTel'       => $emPhone,       // 紧急联系人电话
            'Bank'          => $bank,          // 开户银行
            'Account'       => $bankAccount,   // 银行卡账号
            'ContractID'    => $contractId,    // 合同文件在文件系统上的ID
            'TeamName'      => $groupName,     // 人员所在单位名称/班组名称。
        ];

        $response = $this->httpClient->request('POST', $url, [
            'form_params' => $req
        ])
            ->getBody()
            ->getContents();

        return json_decode($response, true);
    }


    /**
     * 上传文件
     *
     * @param string $path  文件绝对路径 仅支持 pdf/jpg/png/jpeg/xls/DOC 格式文件
     * @return void
     * @author LONG <1121116451@qq.com>
     * @version version
     * @date 2022-01-21
     */
    public function uploadFile($path)
    {
        $url = $this->host . '/API/Folder/Upload';

        $response = $this->httpClient->request('POST', $url, [
            'multipart' => [
                [
                    'name' => 'file',
                    'contents' => fopen($path, 'r'),
                ]
            ]
        ])
            ->getBody()
            ->getContents();

        return json_decode($response, true);
    }

    /**
     * 下载文件
     * 使用此方法必须安装 fileinfo 扩展
     *
     * @param int $docId    文件 ID , 上传文件时返回
     * @param string $path  下载到的文件夹路径,文件夹路径需要保证存在
     * @return string|bool  返回文件路径 | false
     * @author LONG <1121116451@qq.com>
     * @version version
     * @date 2022-01-21
     */
    public function downloadFile(int $docId, string $path): bool
    {
        $path = rtrim($path, '/') . '/';

        $url = $this->host . '/API/Folder/DownLoad';
        $req = [
            'DocumentID' => $docId,
        ];

        $response = $this->httpClient->request('POST', $url, [
            'form_params' => $req
        ])
            ->getBody()
            ->getContents();

        if (!extension_loaded('fileinfo')) {
            throw new ExtensionNotExistException("fileinfo extension not load", 0);
        }

        $finfo = new finfo(FILEINFO_EXTENSION);
        $type  = $finfo->buffer($response);

        if ($type === "???") throw new UnknownFileTypeException("unknown file type", 0);

        // 有的文件类型具有多种扩展名,以斜杠分隔,比如"jpeg/jpg/jpe/jfif"
        if (strpos($type, '/') !== false) $type = explode('/', $type)[0];

        $name = $path . date('YmdHis') . '_' . mt_rand() . '.' . $type;

        $res = file_put_contents($name, $response);

        if ($res) {
            return $name;
        } else {
            return false;
        }
    }

    /**
     * 获取文件 base64 
     *
     * @param int $docId    文件 ID , 上传文件返回
     * @return string   返回 base64 字符串
     * @author LONG <1121116451@qq.com>
     * @version version
     * @date 2022-01-21
     */
    public function getBase64(int $docId)
    {
        $url = $this->host . "/API/AppInterface/GetBase64ByID";

        $response = $this->httpClient->request('GET', $url, [
            'query' => ['DocumentID' => $docId],
        ])
            ->getBody()
            ->getContents();

        return $response;
    }

    /**
     * 添加考勤设备
     *
     * @param string $proUid
     * @param string $name
     * @param string $ip
     * @param int $port
     * @param string $devCode
     * @param int $status
     * @param string $mac
     * @param int $simType
     * @param bool $isIn
     * @return void
     * @author LONG <1121116451@qq.com>
     * @version version
     * @date 2022-01-21
     */
    public function addDevice(
        string $proUid,
        string $name,
        string $ip,
        int $port,
        string $devCode,
        int $status,
        string $mac,
        int $simType,
        bool $isIn
    ) {
        $url = $this->host . '/API/AttendanceMachine/SaveOrUpdate';

        $req = [
            'ProjectNumber' => $proUid,    // 项目唯一标识码，可在区平台项目详细信息处获取
            'Name'          => $name,      // 考勤机名称
            'IP'            => $ip,        // 考勤机IP地址
            'Port'          => $port,      // 考勤机IP端口，没有固定端口则填0
            'SN'            => $devCode,   // 考勤机SN码
            'State'         => $status,    // 在线情况，对应数据字典10
            'MAC'           => $mac,       // 考勤机MAC地址
            'SimType'       => $simType,   // 4G卡类型，对应数据字典12
            'IsInMachine'   => $isIn,      // 出入考勤机标识，对应数据字典13

        ];

        $response = $this->httpClient->request('POST', $url, [
            'form_params' => $req
        ])
            ->getBody()
            ->getContents();

        return json_decode($response, true);
    }


    /**
     * 实时添加考勤记录
     *
     * @param string $proUid    项目唯一标识码
     * @param string $deviceCode    设备唯一标识码
     * @param string $idCard    人员身份证
     * @param string $dateTime  考勤日期时间 Y-m-d H:i:s
     * @param int $type 考勤类型
     * @param bool $isIn    是否为进场考勤,考勤方向 true->进场
     * @param string $attendaceImg  考勤捉拍图片
     * @return void
     * @author LONG <1121116451@qq.com>
     * @version version
     * @date 2022-01-24
     */
    public function addAttendace(
        string $proUid,
        string $deviceCode,
        string $idCard,
        string $dateTime,
        int $type,
        bool $isIn,
        ?string $attendaceImg
    ) {
        $url = $this->host . '/API/Attendance/DeviceUploadAttendanceItem';

        $params = [
            'IDcardNum'      => $idCard,         // 人员身份证号
            'AttendanceTime' => $dateTime,       // 考勤时间 yyyy-MM-dd HH:mm:ss
            'AttendanceType' => $type,           // 考勤类型
            'IsInAttendance' => $isIn ? 1 : 0,   // 是否为进入工地的考勤记录
            'imgBase64'      => $attendaceImg,   // 考勤时的实时图片，采用base64编码，不需要编码头，小于 20K
        ];

        $response = $this->httpClient->request('POST', $url, [
            'form_params' => [
                'Token'      => $this->token,
                'deviceKey'  => $deviceCode,
                'ProjectNum' => $proUid,
                'Data'       => json_encode($params, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            ]
        ])
            ->getBody()
            ->getContents();

        return json_decode($response, true);
    }


    /**
     * 延时批量添加考勤记录
     *
     * @param string $proUid
     * @param array $attendaces
     * @return void
     * @author LONG <1121116451@qq.com>
     * @version version
     * @date 2022-01-24
     */
    public function addAttendaces(string $proUid, array $attendaces)
    {
        $url = $this->host . '/API/Attendance/DeviceUploadAttendanceItemMultiple';

        $response = $this->httpClient->request('POST', $url, [
            'form_params' => [
                'Token'      => $this->token,
                'ProjectNum' => $proUid,
                'Data'       => json_encode($attendaces, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            ]
        ])
            ->getBody()
            ->getContents();

        return json_decode($response, true);
    }


    /**
     * 设备心跳
     *
     * @param string $deviceCode
     * @return void
     * @author LONG <1121116451@qq.com>
     * @version version
     * @date 2022-01-24
     */
    public function deviceHeart(string $deviceCode)
    {
        $url = $this->host . '/API/AttendanceMachine/DeviceHeardBead';

        $req = [
            'Token'     => $this->token,
            'deviceKey' => $deviceCode,
        ];

        $response = $this->httpClient->request('POST', $url, [
            'form_params' => $req
        ])
            ->getBody()
            ->getContents();

        return json_decode($response, true);
    }
}
