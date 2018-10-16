<?php

namespace Virtua\Technology\Setup;

use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Virtua\Technology\Model\ResourceModel\Item\CollectionFactory;

class InstallData implements InstallDataInterface
{
    private $eavSetupFactory;

    /**
     * InstallData constructor.
     * @param EavSetupFactory $eavSetupFactory
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(EavSetupFactory $eavSetupFactory, CollectionFactory $collectionFactory)
    {
        $this->collectionFactory = $collectionFactory;
        $this->eavSetupFactory = $eavSetupFactory;
    }

    /**
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        $items = $this->collectionFactory->create()->setOrder('name', 'ASC')->getItems();
        $technologyNames = array();
        foreach ($items as $item) {
            $technologyNames[] = $item->getName();
        }
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'technology',
            [
                'group' => 'General',
                'type' => 'text',
                'frontend' => '',
                'label' => 'technology',
                'input' => 'multiselect',
                'class' => '',
                'source' => 'Virtua\Technology\Model\Attribute\Source\Technology',
                'backend' => 'Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend',
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => false,
                'user_defined' => false,
                'searchable' => true,
                'filterable' => true,
                'comparable' => false,
                'visible_on_front' => true,
                'used_in_product_listing' => true,
                'unique' => false,
            ]);
        $setup->endSetup();
    }
}