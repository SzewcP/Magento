<?php

namespace Virtua\CategoryVisible\Setup;

use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements InstallDataInterface
{
    protected $eavSetupFactory;

    /**
     * InstallData constructor.
     * @param EavSetupFactory $eavSetupFactory
     */
    public function __construct(EavSetupFactory $eavSetupFactory)
    {
        $this->eavSetupFactory = $eavSetupFactory;
    }

    /**
     * Installs data for a module
     *
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY,
            'category_visible',
            [
                'type' => 'text',
                'label' => 'Visible For User Groups',
                'input' => 'multiselect',
                'source' => 'Virtua\CategoryVisible\Model\Attribute\Source\Visible',
                'required' => false,
                'visible' => true,
                'sort_order' => 1000,
                'backend' => 'Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend',

            ]);
        $setup->endSetup();
    }
}