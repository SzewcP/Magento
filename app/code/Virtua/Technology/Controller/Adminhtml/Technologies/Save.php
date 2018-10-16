<?php

namespace Virtua\Technology\Controller\Adminhtml\Technologies;

use Virtua\Technology\Model\ItemFactory;
use Virtua\Technology\Model\ResourceModel\Item\CollectionFactory;

class Save extends \Magento\Backend\App\Action
{
    private $itemFactory;
    protected $collection;

    /**
     * Save constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param ItemFactory $itemFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        ItemFactory $itemFactory,
        CollectionFactory $collectionFactory
    )
    {
        $this->collection = $collectionFactory;
        $this->itemFactory = $itemFactory;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Redirect|\Magento\Framework\Controller\ResultInterface
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     */
    public function execute()
    {

        $data = $this->getRequest()->getParam('general');
        $url = str_replace(" ", "-", $data['name']);

        if ((isset($data['entity_id']))) {
            $this->update(($data['entity_id']), $data, $url);
            return $this->resultRedirectFactory->create()->setPath('technology/technologies/index');
        }
        $dataToDb = array(
            'name' => $data['name'],
            'description' => $data['description'],
            'url' => $url
        );
        if (isset($data['logo'][0]['name'])) {
            $dataToDb['logo'] = $data['logo'][0]['name'];
        }
        $items = $this->itemFactory->create()
            ->setData($dataToDb);
        $this->collection->create()->addItem($items)->save();

        return $this->resultRedirectFactory->create()->setPath('technology/technologies/index');
    }

    /**
     * @param $id
     * @param $data
     * @param $url
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     */
    public function update($id, $data, $url)
    {
        $collection = $this->collection->create();
        $items = $collection->getItems();
        /** @var ItemFactory $item */
        foreach ($items as $item) {
            if ($id == $item->getData('entity_id')) {
                $dataToDb = array(
                    'entity_id' => $data['entity_id'],
                    'name' => $data['name'],
                    'description' => $data['description'],
                    'url' => $url
                );
                if (isset($data['logo'][0]['name'])) {
                    $dataToDb['logo'] = $data['logo'][0]['name'];
                }
                $item->setData($dataToDb);
                $collection->getResource()->save($item);
            }
        }
    }
}
