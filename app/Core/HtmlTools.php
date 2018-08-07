<?php

namespace App\Core;

use App\Core\HtmlParser\PhpHtmlParser;

/**
 * @author : Thaodev
 * @description : Loại bỏ html, tag khi save content html
 **/

class HtmlTools
{

    protected $html;
	protected $parser;

	public function __construct($html)
	{
		$this->parser = new PhpHtmlParser($html);
	}

    /**
     * Loại bỏ html tag
     * @param string $profile
     * @return string
     */
	public function stripTags($profile = 'default'){
        $allowed_tag = config('seo.html_filters.' . $profile, '');
        if($allowed_tag != 'all'){
            return strip_tags($this->html);
        }else{
            // If allow all tag, only parse HTML
            return $this->parser->toString();
        }

	}

	public static function flyStripTags($html, $profile = 'default'){
		$html_tools = new HtmlTools($html);
		return $html_tools->strip_tag($profile);
	}

    public function addNofollowToLink($html, $only_external = true){
        return $this->parser->addNofollowToLink($only_external)->toString();
    }

    public static function flyAddNofollowToLink($html, $only_external = true){
        $instant = new HtmlTools($html);
        return $instant->addNofollowToLink($only_external);
    }

    public static function purify($html, $profile = 'default'){
        if(config('seo.html_filters.' . $profile, '') == ''){ // nếu không tồn tại profile thì dùng default
            $profile = 'default';
        }
        // mặc định không cho phép thẻ nào
        $default = [
            'allowed' => config('seo.html_filters.' . $profile . '.allowed', []),
            'denied' => config('seo.html_filters.' . $profile . '.denied', 'all'),
            'nofollow' => config('seo.html_filters.' . $profile . '.nofollow', true),
            'inline_css' => config('seo.html_filters.' . $profile . '.inline_css', false),
            'only_external_link' => config('seo.html_filters.' . $profile . '.only_external_link', true),
            'accepted_domain' => config('seo.html_filters.' . $profile . '.accepted_domain', []),
        ];

        // nếu không support thẻ nào thì striptags
        if($default['denied'] == 'all'){
            return trim(preg_replace('/\n/m', ' ', strip_tags($html)));
        }

        $return = '';

        // nếu có cài đặt denied, giá trị của allowed sẽ bị ghi đè
        if(!empty($default['denied'])){
            if(!is_array($default['denied'])){
                $default['denied'] = [$default['denied']];
            }
            $default['allowed'] = array_diff(config('html_tags.all'), $default['denied']);
        }else{
            $default['allowed'] = is_array($default['allowed']) ?
                $default['allowed'] : ($default['allowed'] == 'all' ? config('html_tags.all') : [$default['allowed']]);
        }
        //\Log::alert($default);

        $allowed_tags = implode('', array_map(function($a){return "<".$a.">";}, $default['allowed']));
        //\Log::alert($allowed_tags);

        $return = strip_tags($html, $allowed_tags);

        $return = trim($return);

        if(in_array('br', $default['allowed'])){
            $return = preg_replace('/([^>])\s*\n/', '$1<br/>', $return);
        }

        $instant = new HtmlTools($return);

        if($default['inline_css'] == false){
            $instant->parser->removeAttributes(['style']);
        }

        if(in_array('a', $default['allowed']) && $default['nofollow']){
            $instant->parser->addNofollowToLink($default['only_external_link'], $default['accepted_domain']);
        }

        return $instant->parser->toString();


    }

}
?>