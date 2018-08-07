<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 12/2/15
 * Time: 14:46
 */

namespace App\Core\HtmlParser;


use PHPHtmlParser\Dom;

class PhpHtmlParser implements ParserContract
{
    /** @var Dom */
    private $parsed;

    /**
     * PhpHtmlParser constructor.
     */
    public function __construct( $string = "")
    {
        $this->parsed = new Dom();
        if($string != ''){
            $this->parsed->load($string);
        }
    }

    public function load($string)
    {
        $this->parsed->load($string);
        return $this;
    }

    public function stripTags($allowed_tags)
    {
//        foreach($this->parsed)
    }

    public function addNofollowToLink($external_only = true, $accepted_domain = [])
    {
        if(empty($accepted_domain)){
            $accepted_domain = config('seo.follow_accepted_domains');
        }
        $links = $this->parsed->getElementsByTag('a');
        if($links && $external_only){
            foreach($links as $link){
                /** @var Dom\HtmlNode $link */

                $href = $link->getAttribute('href');

                $url_info = parse_url($href);

                $host = !isset($url_info['host']) ? "" : $url_info['host'];

                if($host != '' && !in_array($host, $accepted_domain)){
                    $link->setAttribute('rel', "nofollow");
                }
            }
        }elseif($links){
            foreach($links as $link){
                /** @var Dom\HtmlNode $link */
                $link->setAttribute('rel', "nofollow");
            }
        }
        return $this;
    }

    public function removeAttributes(array $attributes){
        $this->removeAttributesRecursively($this->parsed->root, $attributes);
    }

    private function removeAttributesRecursively(Dom\HtmlNode &$node, array $attributes){
        foreach($attributes as $attr){
            if($node->getAttribute($attr)){
                $node->setAttribute($attr, null);
            }
        }
        foreach($node->getChildren() as $a_child){
            if($a_child instanceof Dom\HtmlNode){
                $this->removeAttributesRecursively($a_child, $attributes);
            }
        }

    }

    public function toString()
    {
        return $this->parsed->root->outerHtml();
    }
}