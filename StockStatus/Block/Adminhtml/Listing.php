<?php
namespace Chin\StockStatus\Block\Adminhtml;

class Listing extends \Magento\Backend\Block\Widget\Grid\Extended
{

	/**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $productCollectionFactory;

	/**
     *
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        array $data = []
    ) {
        $this->productCollectionFactory = $productCollectionFactory;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * _construct
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setFilterVisibility(false);
    }

    /**
     * prepare collection
     */
    protected function _prepareCollection()
    {
        $collection = $this->productCollectionFactory->create();
        $collection->addAttributeToSelect('name');
        $collection->addAttributeToSelect('sku');
        $collection->addAttributeToSelect('price');
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * @return $this
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'id',
            [
                'header' => __('ID'),
                'index' => 'entity_id',
                'class' => 'xxx',
                'width' => '50px',
            ]
        );
        $this->addColumn(
            'name',
            [
                'header' => __('Name'),
                'index' => 'name',
                'class' => 'xxx',
                'width' => '50px',
            ]
        );
        $this->addColumn(
            'sku',
            [
                'header' => __('Sku'),
                'index' => 'sku',
                'class' => 'xxx',
                'width' => '50px',
            ]
        );
        $this->addColumn(
            'total_quantity',
            [
                'header' => __('Total Quantity'),
                'renderer' => 'Chin\StockStatus\Block\Adminhtml\Renderer\Quantity',
                'type' => 'text'
            ]
        );
        $this->addColumn(
            'available_quantity',
            [
                'header' => __('Available Quantity'),
                'renderer' => 'Chin\StockStatus\Block\Adminhtml\Renderer\Available',
                'type' => 'text'
            ]
        );

        return parent::_prepareColumns();
    }
}

