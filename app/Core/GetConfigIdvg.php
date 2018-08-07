<?php
/**
 * Created by PhpStorm.
 * User: MyPC
 * Date: 28/11/2015
 * Time: 8:54 SA
 */

namespace App\Core;

class GetConfigIdvg
{
    protected $client_id;
    protected $service;
    protected $query;
    protected $query_sso;
    protected $publicKey;
    protected $secretKey;

    public function __construct()
    {
        $this->client_id = config('idvg.client_id');
        $this->service   = config('idvg.service');
        $this->publicKey = config('idvg.connections.publicKey');
        $this->secretKey = config('idvg.connections.secretKey');
        $this->query     = '&client_id=' . $this->client_id . '&service=' . $this->service;
        $this->query_sso = '&service=' . $this->service;
    }

    /**
     * Đăng nhập bình thường qua idvg.
     * @return url
     **/
    public function getUrlLogin($url_call_back)
    {
        return config('idvg.url_login').'_cont='.$url_call_back.$this->query;
    }

    /**
     * Đăng nhập qua idvg theo SSO.
     * @return url
     **/

    public function getUrlLoginSSO($url_call_back){
        return config('idvg.sso.url_login').'_cont='.$url_call_back.$this->query_sso;
    }

    /**
     * Đăng ký bình thường qua idvg.
     * @return url
     **/

    public function getUrlRegister($url_call_back)
    {
        return config('idvg.url_register').'_cont='.$url_call_back.$this->query;
    }

    public function getUrlRegisterSSO($url_call_back)
    {
        return config('idvg.sso.url_register').'_cont='.$url_call_back.$this->query_sso;
    }

    /**
     * Thoát idvg.
     * @return url
     **/
    public function getUrlLogout($url_call_back)
    {
        return config('idvg.url_logout').'_cont='.$url_call_back;
    }

    public function getUrlLogoutSSO($url_call_back){
        return config('idvg.sso.url_logout').'_cont='.$url_call_back.$this->query_sso;
    }

    public function getUrlSetting($url_call_back){
        return config('idvg.url_seting').'_cont='.$url_call_back;
    }

    /**
     * Lấy Url CallBack
     * @return url
     **/

    public function getUrlCallBack($uri)
    {
        return config('idvg.unibee.url_login_return').'?uri='.$uri;
    }

    public function getUrlCallBackLogout($uri)
    {
        return config('idvg.unibee.url_logout').'?uri='.$uri;
    }

    public function getAuthUnibeeName()
    {
        return config('idvg.unibee.http_user');
    }

    public function getAuthUnibeePass()
    {
        return config('idvg.unibee.http_pass');
    }

    public function getAuthIdvgName()
    {
       return config('idvg.http_user');
    }

    public function getAuthIdvgPass()
    {
        return config('idvg.http_pass');
    }

    /**
     * get list event change
     * @return []
     **/
    public function getEventIdvg()
    {
        return config('idvg.event');
    }

    /**
     * get token authen
     * @return []
     **/

    public function getSecretKey(){
        return $this->secretKey;
    }

    public function getPublickey(){
        return $this->publicKey;
    }

}