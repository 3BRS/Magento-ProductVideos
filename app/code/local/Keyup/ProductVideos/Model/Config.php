<?php
/**
 * Creating a model with select options. Describes video players placement in
 * templates.
 *
 * @package     Keyup_ProductVideos
 * @author      Jan Kuchař <jan.kuchar@keyup.eu>
 * @copyright   Copyright (c) 2015 Keyup IT Services (http://www.keyup.eu)
 * @license     http://keyup.mit-license.org/2012 MIT License
 */

class Keyup_ProductVideos_Model_Config extends Mage_Core_Model_Abstract {
    const   PICTURE_FIRST = 'PV_PM',
            MOVIES_FISRT = 'PV_MP',
            MOVIES_UNDER_DESCRIPTION = 'PV_DES';
    const   XML_PATH_VIDEO_BEHAVIOR = 'design/productvideos/pvbehavior',
            XML_PATH_VIDEO_WIDTH = 'design/productvideos/pvwidth',
            XML_PATH_VIDEO_HEIGHT = 'design/productvideos/pvheight';
    const   ATTR_URLS = 'productvideos_ta';
    
    private static function getVideoBehavior($storeId = null)
    {
        return Mage::getStoreConfig(self::XML_PATH_VIDEO_BEHAVIOR, $storeId);
    }
    
    function isBehaviorPicturesFirst($storeId = null)
    {
        return (self::getVideoBehavior($storeId) == self::PICTURE_FIRST) ? true : false;
    }
    
    function isBehaviorMoviesFirst($storeId = null)
    {
        return (self::getVideoBehavior($storeId) == self::MOVIES_FISRT) ? true : false;
    }
    
    function isBehaviorMoviesUnderDescription($storeId = null)
    {
        return (self::getVideoBehavior($storeId) == self::MOVIES_UNDER_DESCRIPTION) ? true : false;
    }
    
    function getVideoWidth($storeId = null)
    {
        return Mage::getStoreConfig(Keyup_ProductVideos_Model_Config::XML_PATH_VIDEO_WIDTH, $storeId);
    }
    
    function getVideoHeight($storeId = null)
    {
        return Mage::getStoreConfig(Keyup_ProductVideos_Model_Config::XML_PATH_VIDEO_HEIGHT, $storeId);
    }
    
}