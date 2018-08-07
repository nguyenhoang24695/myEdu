<?php
/**
 * Created by PhpStorm.
 * User: MyPC
 * Date: 04/12/2015
 * Time: 10:03 SA
 */

namespace App\Core;


class HttpAuthData
{
    protected $nonce;
    protected $cnonce;
    protected $nc;
    protected $qop;
    protected $uri;
    protected $response;
    protected $method;
    protected $realm;
    protected $validUser;
    protected $validPass;

    public function __construct()
    {
        $this->nonce  = uniqid();
        $this->realm  = "Hi";
        $this->qop    = "auth";
        $this->method = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : "POST";
    }

    /**
     * Kiểm tra login auth
    **/
    public function checkAuthDigest($validUser='',$validPass='')
    {
        $realm = 'Hi';
        $nonce = uniqid();
        if($validUser == '' || $validPass == '') $this->requireLogin($realm,$nonce);
        $digest       =  $this->getDigest();
        if (is_null($digest)) $this->requireLogin($realm,$nonce);

        $digestParts  = $this->digestParse($digest);
        $this->validUser    = $validUser;
        $this->validPass    = $validPass;
        $this->nonce        = isset($digestParts['nonce']) ? $digestParts['nonce'] : '';
        $this->nc           = isset($digestParts['nc']) ? $digestParts['nc'] : "";
        $this->cnonce       = isset($digestParts['cnonce']) ? $digestParts['cnonce'] : "";
        $this->qop          = isset($digestParts['qop']) ? $digestParts['qop'] : "";
        $this->uri          = isset($digestParts['uri']) ? $digestParts['uri'] : "";
        $this->response     = isset($digestParts['response']) ? $digestParts['response'] : "";
        $A1                 = md5("{$this->validUser}:{$this->realm}:{$this->validPass}");
        $A2                 = md5("{$this->method}:{$this->uri}");
        $validResponse      = md5("{$A1}:{$this->nonce}:{$this->nc}:{$this->cnonce}:{$this->qop}:{$A2}");
        if ($this->response != $validResponse) {
            $this->requireLogin($realm,$nonce);
        } else {
            $this->done();
        }
    }

    /**
     * Lấy header digest
     * @return $digest
     **/
    public function getDigest()
    {
        // mod_php
        $digest = "";
        if (isset($_SERVER['PHP_AUTH_DIGEST'])) {
            $digest = $_SERVER['PHP_AUTH_DIGEST'];
            // most other servers
        } elseif (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            if (strpos(strtolower($_SERVER['HTTP_AUTHORIZATION']),'digest')===0)
                $digest = substr($_SERVER['HTTP_AUTHORIZATION'], 7);
        }
        return $digest;
    }

    /**
     * Parse header lấy được
     * @return $data
     **/
    public function digestParse($digest)
    {
        // protect against missing data
        $needed_parts = array('nonce'=>1, 'nc'=>1, 'cnonce'=>1, 'qop'=>1, 'username'=>1, 'uri'=>1, 'response'=>1);
        $data = array();

        preg_match_all('@(\w+)=(?:(?:")([^"]+)"|([^\s,$]+))@', $digest, $matches, PREG_SET_ORDER);

        foreach ($matches as $m) {
            $data[$m[1]] = $m[2] ? $m[2] : $m[3];
            unset($needed_parts[$m[1]]);
        }

        return $needed_parts ? false : $data;
    }

    /**
     * yêu cầu đăng nhập
    **/
    public function requireLogin($realm,$nonce)
    {
        header('WWW-Authenticate: Digest realm="' . $realm . '",qop="auth",nonce="' . $nonce . '",opaque="' . md5($realm) . '"');
        header('HTTP/1.0 401 Unauthorized');
        echo 'Not authenticated';
        die();
    }

    /**
     * Trả về lỗi xác thực không thành công
     * @return json
    **/
    public function error()
    {
        return response()->json([
            'error' => true,
            'message' => 'Not authenticated',
            'code' => 401],
            401
        );
    }

    /**
     * Trả về xác thực thành công
     * @return json
     **/
    public function done()
    {
        return response()->json([
            'error' => false,
            'message' => 'ok',
            'code' => 200],
            200
        );
    }

    /**
     * Post data
    **/
    public function Post_Data($type='basic',$arr_login=array(),$url,$arr_data=array())
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_USERPWD, $arr_login['name'] . ":" . $arr_login['pass']);
        switch ($type){
            case "basic":
                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                break;
            case "digest":
                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
                break;
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$arr_data);
        $data			= curl_exec($ch);

        preg_match('/{.*}/', $data, $matches);
        if(isset($matches[0])){
            $data   = json_decode($matches[0],true);
        }

        $httpCode	    = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if(curl_errno($ch)){
            curl_close($ch);
            return response()->json([
                'error' => true,
                'message' => 'Lỗi rồi',
                'code' => 401],
                401
            );
        }

        if(is_array($data)) $data['httpStatusCode']	= $httpCode;
        return response()->json([$data]);
    }
}