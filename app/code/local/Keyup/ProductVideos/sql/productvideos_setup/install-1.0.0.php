<?php
/**
 * Creating textarea EAV for product with list of video URLs.
 *
 * @package     Keyup_ProductVideos
 * @author      Jan Kuchař <jan.kuchar@keyup.eu>
 * @copyright   Copyright (c) 2015 Keyup IT Services (http://www.keyup.eu)
 * @license     http://keyup.mit-license.org/2012 MIT License
 */

$setup = Mage::getResourceModel('catalog/setup','catalog_setup');
$setup->removeAttribute('catalog_product','videoembeder_ta');
$setup->removeAttribute('catalog_product','videoembedder_ta');
$setup->removeAttribute('catalog_product','productvideos_ta');

$installer = Mage::getResourceModel('catalog/setup', 'catalog_setup');
$installer->startSetup();

if (!$installer->getAttributeId(Mage_Catalog_Model_Product::ENTITY, Keyup_ProductVideos_Model_Config::ATTR_URLS)) {
    
    $setup = new Mage_Catalog_Model_Resource_Setup('core_setup');
    $setup->addAttribute(Mage_Catalog_Model_Product::ENTITY, Keyup_ProductVideos_Model_Config::ATTR_URLS, array (
      'group' => 'General',
      'attribute_model' => NULL,
      'backend' => NULL,
      'type' => 'text',
      'table' => NULL,
      'frontend' => NULL,
      'input' => 'textarea',
      'label' => 'Video URLs',
      'frontend_class' => NULL,
      'source' => NULL,
      'required' => '0',
      'user_defined' => '1',
      'default' => NULL,
      'unique' => '0',
      'input_renderer' => NULL,
      'global' => '0',
      'visible' => '1',
      'searchable' => '0',
      'filterable' => '0',
      'comparable' => '0',
      'visible_on_front' => '0',
      'is_html_allowed_on_front' => '1',
      'is_used_for_price_rules' => '0',
      'filterable_in_search' => '0',
      'used_in_product_listing' => '0',
      'used_for_sort_by' => '0',
      'is_configurable' => '0',
      'apply_to' => NULL,
      'visible_in_advanced_search' => '0',
      'position' => '0',
      'wysiwyg_enabled' => '0',
      'used_for_promo_rules' => '0',
      'note' => Mage::helper('keyup_productvideos')->__('Insert only one URL on every line of textarea or full HTML code flatted as one row.')
    ));

    $attribute = Mage::getModel('eav/entity_attribute')->loadByCode('catalog_product', Keyup_ProductVideos_Model_Config::ATTR_URLS);
    $attribute->setStoreLabels(array());
    $attribute->save();
}

$installer->endSetup();