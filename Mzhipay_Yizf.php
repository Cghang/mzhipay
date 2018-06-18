<?php
/**
* type 类型：
* alipay.trade.precreate 支付宝支付
* qq.pay.native QQ支付
* wxpay.pay.unifiedorder 微信支付
* 此文件仅对易支付有效 其他方式请翻阅其他
* 易支付支付网关：https://api.muzhifu.cc/gateway.do
**/
$params =   array(
    'appid'        =>  'AppID',
    'appkey'       =>  'AppKey',
    'addtime'     =>  date('Y-m-d H:i:s'),
    'type'        =>  'alipay.trade.precreate',
    'mz_content'   => json_encode(array(
        'out_trade_no' => '001', // 交易编号
        'total_amount' => '0.01', // 交易金额 单位:元
        'name'=> 'mzhipay-online',// 付款时商品名称
        'notify_url' => 'https://www.mzhipay.com/notify_url', // 通知地址
        'return_url' => 'https://www.mzhipay.com/return_url', // 返回地址
    ))
);
// 获得签名
$params['sign'] = sign($params);
// 安全起见：app_key不作为请求参数
unset($params['appkey']);
// 发送请求获得结果
$res = http($params);
var_dump($res);

/**
 * Http请求
 * @param  array $params 请求参数
 * @return json         返回数据
 */
function http($params = array())
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://api.muzhifu.cc/gateway.do');
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
    return curl_exec($ch);
}

/**
 * 签名算法
 * @param  array $params     请求参数
 * @param  string $app_key AppKey
 * @return string             签名字符串
 */
 function sign($params)
{
    $appkey = $params['appkey'];
    unset($params['appkey'], $params['sign'], $params['sign_type']);
    ksort($params);
    return strtoupper(md5(urldecode(http_build_query($params)).$appkey));
}
