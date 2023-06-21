<?php
class jdapi
{
    private $app_key;
    private $app_secret;
    private $access_token = '';
    private $uninid = '';
    private $timestamp;
    public $format = 'json';
    public $v = '1.0';
    private $sign_method = 'md5';
    public $serverUrl = "http://router.jd.com/api";
    public function __construct($app_key, $app_secret)
    {
        $this->app_key = $app_key;
        $this->app_secret = $app_secret;
        $this->timestamp = date('Y-m-d H:i:s');
    }
    public function RequestJdApi($config)
    {
        if (empty($config)) {
            return false;
        }
        $arr = $this->GroutIngArr($config);
        if (empty($arr)) {
            return false;
        }
        $jd_sign = $this->JdSign($arr);
        if (empty($jd_sign)) {
            return false;
        }
        $arr['sign'] = $jd_sign;
        $url = $this->serverUrl;
        $data = $this->CurlPost($url, $arr);
        if (empty($data)) {
            return false;
        }
        if (is_null(json_decode($data))) {
            return false;
        }
        $json_data = json_decode($data, true);
        return $json_data;
    }
     
    public function GroutIngArr($config)
    {
        if (empty($config)) {
            return false;
        }
        $config['app_key'] = $this->app_key;
        $config['timestamp'] = $this->timestamp;
        $config['format'] = $this->format;
        $config['v'] = $this->v;
        $config['sign_method'] = $this->sign_method;
        return $config;
    }
     
    protected function JdSign($arr)
    {
        if (!empty($arr)) {
            $p = ksort($arr);
            if ($p) {
                $str = '';

                foreach ($arr as $k => $val) {
                    $str .= $k . $val;
                }
                $strs = trim($str);
                $sign = strtoupper(md5($this->app_secret . $strs . $this->app_secret));
                return $sign;
            }
        }
        return false;
    }
     
    public function CurlPost($url, $post_data)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post_data));
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($curl, CURLOPT_TIMEOUT, 60);
        $data = curl_exec($curl);
        curl_close($curl);
        return $data;
    }
    /*
    *jd.union.open.goods.promotiongoodsinfo.query 根据skuid查询商品信息接口
    */
    public function getGoodsInfo($skuid)
    {
        $arr = array(
            'skuIds' => $skuid,
        );
        $arr = json_encode($arr);
        $config = array(
            'method' => 'jd.union.open.goods.promotiongoodsinfo.query',
            'param_json' => $arr,
        );
        $goods = $this->RequestJdApi($config);
        if (empty($goods['jd_union_open_goods_promotiongoodsinfo_query_response'])) {
            return [];
        }
        $goods_info = json_decode($goods['jd_union_open_goods_promotiongoodsinfo_query_response']['result'], true);
        if (empty($goods_info['data'])) {
            return [];
        }
        return $goods_info['data'];
    }
    /*
    * jd.union.open.goods.jingfen.query 京粉精选商品查询接口
    */
    public function jingfenQuery($goodsReq)
    {
        $arr = array(
            'goodsReq' => $goodsReq,
        );
        $arr = json_encode($arr);
        $config = array(
            'method' => 'jd.union.open.goods.jingfen.query',
            'param_json' => $arr,
        );
        $goods = $this->RequestJdApi($config);
        
        if (empty($goods['jd_union_open_goods_jingfen_query_response'])) {
            return [];
        }
        $goods_info = json_decode($goods['jd_union_open_goods_jingfen_query_response']['result'], true);
         
        if (empty($goods_info['data'])) {
            return [];
        }
        return $goods_info['data'];
    }
}
