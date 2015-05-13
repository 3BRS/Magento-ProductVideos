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

class Keyup_ProductVideos_Model_Source_VideoBehavior {
   public function toOptionArray() {
       $h = Mage::helper('keyup_productvideos');
       $vals = array(
           array('value' => Keyup_ProductVideos_Model_Config::MOVIES_FISRT, 'label' => $h->__('Movies & Pictures, placed with pictures')),
           array('value' => Keyup_ProductVideos_Model_Config::PICTURE_FIRST, 'label' => $h->__('Pictures & Movies, placed with pictures')),
           array('value' => Keyup_ProductVideos_Model_Config::MOVIES_UNDER_DESCRIPTION, 'label' => $h->__('Movies are alone after content of Product description'))
       );
       return $vals;
   }
}