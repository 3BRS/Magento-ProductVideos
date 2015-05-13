<?php
/**
 * Helper with methods to parsing URLs from product attribute and generating
 * code with embeds from its.
 *
 * @package     Keyup_ProductVideos
 * @author      Jan Kuchař <jan.kuchar@keyup.eu>
 * @copyright   Copyright (c) 2015 Keyup IT Services (http://www.keyup.eu)
 * @license     http://keyup.mit-license.org/2012 MIT License
 */

class Keyup_ProductVideos_Helper_Data extends Mage_Core_Helper_Abstract {
    private static $parsedData = false;
    
    private static function cleanUrl($string)
    {
        return trim($string, ' /');
    }
    
    private static function parseTextarea($_product)
    {
        if (self::$parsedData===false) {
            self::$parsedData = array();
            $urls = Mage::getResourceModel('catalog/product')
                    ->getAttributeRawValue(
                            $_product->getId(),
                            Keyup_ProductVideos_Model_Config::ATTR_URLS,
                            Mage::app()->getStore()
                            );
            foreach (explode("\n", (string)$urls) as $i) {
                if ($i) {
                    self::$parsedData[] = self::parseVideoInput($i);
                }
            }
        }
        return self::$parsedData;
    }
    
    private static function parseVideoInput($input)
    {
        $purl = parse_url(trim($input));
        $pdomain = explode('.', mb_strtolower($purl['host']));
        $cleardomain = sprintf('%s.%s', $pdomain[count($pdomain)-2], $pdomain[count($pdomain)-1]);
        if ($cleardomain=='youtube.com') {
            $ppath = explode('/', self::cleanUrl($purl['path'])); //parse embed link
            parse_str($purl['query'], $pquery); //parse browser link
            if ($ppath[0]=='embed' && $ppath[1]) {
                $t['type'] = 'youtube';
                $t['id'] = $ppath[1];
                $t['thumb'] = sprintf('http://img.youtube.com/vi/%s/default.jpg', $t['id']);
            } elseif (isset($pquery['v'])) {
                $t['type'] = 'youtube';
                $t['id'] = $pquery['v'];
                $t['thumb'] = sprintf('http://img.youtube.com/vi/%s/default.jpg', $t['id']);
            }
        } elseif ($cleardomain=='vimeo.com') {
            $ppath = explode('/',self::cleanUrl($purl['path'])); //parse link
            $t['type'] = 'vimeo';
            $t['id'] = end($ppath);
            //get XML info
            $xmlinfo = @simplexml_load_string(file_get_contents(sprintf('vimeo.com/api/v2/video/%s.xml', $t['id'])));
            if ($xmlinfo) $t['thumb'] = $xmlinfo->user_portrait_large; //TODO if not, get dummy img
        } elseif ($cleardomain=='dailymotion.com') {
            $ppath = explode('/',self::cleanUrl($purl['path'])); //parse link
            $vname = explode('_',end($ppath));
            $t['type'] = 'dailymotion';
            $t['id'] = $vname[0];
            $t['thumb'] = sprintf('http://www.dailymotion.com/thumbnail/video/%s', $t['id']);
        } elseif ($cleardomain=='liveleak.com') {
            parse_str($purl['query'], $pquery);
            if (isset($pquery['i'])) {
                $t['type'] = 'liveleak';
                $t['id'] = $pquery['i'];
                //TODO get dummy img
            } elseif (isset($pquery['f'])) {
                $t['type'] = 'liveleak';
                $t['id'] = $pquery['f'];
                //TODO get dummy img
            }
        } elseif (trim($input[0])=='<') {
            $t['type'] = 'embed';
            $t['content'] = trim($input);
            //TODO get dummy img
        } else $t = false;
        return $t;
    }
    
    private static function createEmbed($video)
    {
        $width = Mage::getModel('keyup_productvideos/config')->getVideoWidth();
        $height = Mage::getModel('keyup_productvideos/config')->getVideoHeight();
        switch ($video['type']) {
            case 'youtube':
                return sprintf('<iframe width="%1$s" height="%2$s" src="https://www.youtube.com/embed/%3$s?showinfo=0" frameborder="0" allowfullscreen></iframe>',
                        $width,
                        $height,
                        $video['id']);
            case 'vimeo':
                return sprintf('<iframe src="https://player.vimeo.com/video/%3$s" width="%1$s" height="%2$s" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>',
                        $width,
                        $height,
                        $video['id']);
            case 'dailymotion':
                return sprintf('<iframe frameborder="0" width="%1$s" height="%2$s" src="//www.dailymotion.com/embed/video/%3$s" allowfullscreen></iframe>',
                        $width,
                        $height,
                        $video['id']);
            case 'liveleak.com':
                return sprintf('<iframe width="%1$s" height="%2$s" src="http://www.liveleak.com/ll_embed?f=%3$s" frameborder="0" allowfullscreen></iframe>',
                        $width,
                        $height,
                        $video['id']);
            default:
                return $video['content'];
        }
        return '#video provider is unsupported';
    }
    
    function buildEmbeds(Mage_Catalog_Model_Product $_product)
    {
        $result = array();
        foreach (self::parseTextarea($_product) as $i) {
            $result[] = self::createEmbed($i);
        }
        return $result;
    }
    
    function buildThumbs(Mage_Catalog_Model_Product $_product)
    {
        $result = array();
        foreach (self::parseTextarea($_product) as $i) {
            $result[] = $i['thumb'];
        }
        return $result;
    }
    
    function getCount(Mage_Catalog_Model_Product $_product)
    {
        return count(self::parseTextarea($_product));
    }
    
}