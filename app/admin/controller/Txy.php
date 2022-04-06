<?php
/**
 * @Notes:
 * @Author: HeLei
 * @Date: 2022/3/29 15:23
 *  ┌─────────────────────────────────────────────────────────────┐
 *  │┌───┬───┬───┬───┬───┬───┬───┬───┬───┬───┬───┬───┬───┬───┬───┐│
 *  ││Esc│!1 │@2 │#3 │$4 │%5 │^6 │&7 │*8 │(9 │)0 │_- │+= │|\ │`~ ││
 *  │├───┴─┬─┴─┬─┴─┬─┴─┬─┴─┬─┴─┬─┴─┬─┴─┬─┴─┬─┴─┬─┴─┬─┴─┬─┴─┬─┴───┤│
 *  ││ Tab │ Q │ W │ E │ R │ T │ Y │ U │ I │ O │ P │{[ │}] │ BS  ││
 *  │├─────┴┬──┴┬──┴┬──┴┬──┴┬──┴┬──┴┬──┴┬──┴┬──┴┬──┴┬──┴┬──┴─────┤│
 *  ││ Ctrl │ A │ S │ D │ F │ G │ H │ J │ K │ L │: ;│" '│ Enter  ││
 *  │├──────┴─┬─┴─┬─┴─┬─┴─┬─┴─┬─┴─┬─┴─┬─┴─┬─┴─┬─┴─┬─┴─┬─┴────┬───┤│
 *  ││ Shift  │ Z │ X │ C │ V │ B │ N │ M │< ,│> .│? /│Shift │Fn ││
 *  │└─────┬──┴┬──┴──┬┴───┴───┴───┴───┴───┴──┬┴───┴┬──┴┬─────┴───┘│
 *  │      │Fn │ Alt │         Space         │ Alt │Win│   HHKB   │
 *  │      └───┴─────┴───────────────────────┴─────┴───┘          │
 *  └─────────────────────────────────────────────────────────────┘
 */

namespace app\admin\controller;
use think\App;
use think\Exception;
use think\Response;
use TencentCloud\Common\Credential;
use TencentCloud\Common\Profile\ClientProfile;
use TencentCloud\Common\Profile\HttpProfile;
use TencentCloud\Common\Exception\TencentCloudSDKException;
use TencentCloud\Lighthouse\V20200324\LighthouseClient;
use TencentCloud\Lighthouse\V20200324\Models\DescribeInstancesRequest;
use TencentCloud\Lighthouse\V20200324\Models\DescribeBlueprintsRequest;
use TencentCloud\Lighthouse\V20200324\Models\StartInstancesRequest;
use TencentCloud\Lighthouse\V20200324\Models\StopInstancesRequest;
use TencentCloud\Lighthouse\V20200324\Models\DescribeFirewallRulesRequest;
use TencentCloud\Lighthouse\V20200324\Models\DeleteFirewallRulesRequest;
use TencentCloud\Lighthouse\V20200324\Models\CreateFirewallRulesRequest;

class Txy extends BaseController
{
    private $secretId;
    private $secretKey;
    private $cred;
    private $clientProfile;

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->secretId=config("txy.helei.secretId");
        $this->secretKey=config("txy.helei.secretKey");
        $this->cred = new Credential($this->secretId, $this->secretKey);
        $httpProfile = new HttpProfile();
        $httpProfile->setEndpoint("lighthouse.tencentcloudapi.com");
        $this->clientProfile = new ClientProfile();
        $this->clientProfile->setHttpProfile($httpProfile);

    }

    /**
     * @Notes: 获取实例列表
     * @return Response
     * @Author: HeLei
     * @Date: 2022/3/31 10:37
     */
    public function hostList(): Response {
        $request = $this->request;
        $param = $request->param();
//        $InstanceId=$param['InstanceId'];
        $Region=$param['Region'];
        $code    = 20000;
        $message = 'SUCCESS';
        try {
            $client = new LighthouseClient($this->cred, $Region, $this->clientProfile);
            $req = new DescribeInstancesRequest();
            $params = array();
            $req->fromJsonString(json_encode($params));
            $data = $client->DescribeInstances($req);

        }
        catch(Exception $e) {
//            echo $e;
            $data    = null;
            $message = $e->getMessage();
        }

        return json(['code' => $code, 'message' => $message, 'data' => $data]);

    }

    /**
     * @Notes: 获取可重装的系统
     * @return Response
     * @Author: HeLei
     * @Date: 2022/3/31 10:36
     */
    public function osList(): Response {

        $request = $this->request;
        $param = $request->param();
        $InstanceId=$param['InstanceId'];
        $Region=$param['Region'];

        $code    = 20000;
        $message = 'SUCCESS';
        try {
            $client = new LighthouseClient($this->cred, $Region, $this->clientProfile);
            $req = new DescribeBlueprintsRequest();
            $params = array();
            $req->fromJsonString(json_encode($params));

            $data = $client->DescribeBlueprints($req);
        }
        catch(Exception $e) {
//            echo $e;
            $data    = null;
            $message = $e->getMessage();
        }

        return json(['code' => $code, 'message' => $message, 'data' => $data]);
    }

    /**
     * @Notes: 关机
     * @return Response
     * @Author: HeLei
     * @Date: 2022/3/31 10:37
     */
    public function osClose(): Response {
        $request = $this->request;
        $param = $request->param();
        $InstanceId=$param['InstanceId'];
        $Region=$param['Region'];
        $code    = 20000;
        $message = 'SUCCESS';
        try {
            $client = new LighthouseClient($this->cred, $Region, $this->clientProfile);
            $req = new StopInstancesRequest();
            $params = array(
                "InstanceIds"=>array($InstanceId)
            );
            $req->fromJsonString(json_encode($params));
            $data = $client->StopInstances($req);
        }
        catch(Exception $e) {
//            echo $e;
            $data    = null;
            $message = $e->getMessage();
        }

        return json(['code' => $code, 'message' => $message, 'data' => $data]);
    }

    /**
     * @Notes: 开机
     * @return Response
     * @Author: HeLei
     * @Date: 2022/3/31 11:15
     */
    public function osOpen(): Response {
        $request = $this->request;
        $param = $request->param();
        $InstanceId=$param['InstanceId'];
        $Region=$param['Region'];
        $code    = 20000;
        $message = 'SUCCESS';
        try {
            $client = new LighthouseClient($this->cred, $Region, $this->clientProfile);
            $req = new StartInstancesRequest();
            $params = array(
                "InstanceIds"=>array($InstanceId)
            );
            $req->fromJsonString(json_encode($params));

            $data = $client->StartInstances($req);
        }
        catch(Exception $e) {
//            echo $e;
            $data    = null;
            $message = $e->getMessage();
        }

        return json(['code' => $code, 'message' => $message, 'data' => $data]);
    }

    /**
     * @Notes: 当前开启的防火墙端口列表
     * @return Response
     * @Author: HeLei
     * @Date: 2022/3/31 12:33
     */
    public function firewallList(): Response{
        $request = $this->request;
        $param = $request->param();
        $InstanceId=$param['InstanceId'];
        $Region=$param['Region'];
        $code    = 20000;
        $message = 'SUCCESS';
        try {
            $client = new LighthouseClient($this->cred, $Region, $this->clientProfile);
            $req = new DescribeFirewallRulesRequest();
            $params = array(
                "InstanceId" => $InstanceId
            );
            $req->fromJsonString(json_encode($params));
            $data = $client->DescribeFirewallRules($req);
        }
        catch(Exception $e) {
            $data    = null;
            $message = $e->getMessage();
        }

        return json(['code' => $code, 'message' => $message, 'data' => $data]);
    }

    /**
     * @Notes: 删除防火墙规则
     * @return Response
     * @Author: HeLei
     * @Date: 2022/3/31 16:23
     */
    public function firewallDel(): Response {
        $request = $this->request;
        $param = $request->param();
        $InstanceId=$param['InstanceId'];
        $FirewallRules=$param['FirewallRules'];
        $FirewallRules=json_decode($FirewallRules,true);
        $Region=$param['Region'];
        $code    = 20000;
        $message = 'SUCCESS';
        try {
            $client = new LighthouseClient($this->cred, $Region, $this->clientProfile);
            $req = new DeleteFirewallRulesRequest();
            $params = array(
                "InstanceId" => $InstanceId,
                "FirewallRules" => array(
                    array(
                        "Protocol" => $FirewallRules['Protocol'],
                        "Port" => $FirewallRules['Port']
                    )
                )
            );
            $req->fromJsonString(json_encode($params));
            $data = $client->DeleteFirewallRules($req);
        }
        catch(Exception $e) {
            $data    = null;
            $message = $e->getMessage();
        }
        return json(['code' => $code, 'message' => $message, 'data' => $data]);

    }

    /**
     * @Notes:  添加防火墙规则
     * @return Response
     * @Author: HeLei
     * @Date: 2022/3/31 16:24
     */
    public function createFirewall() :Response {
        $request = $this->request;
        $param = $request->param();
        $InstanceId=$param['InstanceId'];
        $FirewallRules=$param['FirewallRules'];
        $FirewallRules=json_decode($FirewallRules,true);
        $Region=$param['Region'];
        $code    = 20000;
        $message = 'SUCCESS';
        try {
            $client = new LighthouseClient($this->cred, $Region, $this->clientProfile);
            $req = new CreateFirewallRulesRequest();
            $params = array(
                "InstanceId" => $InstanceId,
                "FirewallRules" => array(
                    array(
                        "Protocol" => $FirewallRules['Protocol'],
                        "Port" => $FirewallRules['Port']
                    )
                )
            );
            $req->fromJsonString(json_encode($params));
            $data = $client->CreateFirewallRules($req);
        }
        catch(Exception $e) {
            $data    = null;
            $message = $e->getMessage();
        }

        return json(['code' => $code, 'message' => $message, 'data' => $data]);
    }

}