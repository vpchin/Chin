<?php
namespace Chin\StockStatus\Block\Adminhtml\Renderer;

class Available extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\Text
{

    protected $_orderCollectionFactory;
    protected $_stockItemRepository;

    public function __construct(
        \Magento\CatalogInventory\Model\Stock\StockItemRepository $stockItemRepository,
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory)
    {
        $this->_orderCollectionFactory = $orderCollectionFactory;
        $this->_stockItemRepository = $stockItemRepository;
    }

    public function getStockQty($productId)
    {
        $total = $this->_stockItemRepository->get($productId)->getQty();
        $sold = 0;

        $orderCollection = $this->_orderCollectionFactory->create();

        $orderCollection->getSelect()
                ->join(
                    'sales_order_item',
                    'main_table.entity_id = sales_order_item.order_id'
                )->where('product_id = '.$productId)
                ->where('status IN ("Pending", "Processing")');

        $orderCollection->getSelect()->group('main_table.entity_id');
        foreach ($orderCollection as $order) {
            $sold += (int)$order->getQtyOrdered();
        }

        $remain = $total - $sold;
        return $remain;
    }

    /**
     * Render product qty field
     *
     * @param \Magento\Framework\DataObject $row
     * @return string
     */
    public function render(\Magento\Framework\DataObject $row)
    {
        $id = $row->getData('entity_id');
        $qty = $this->getStockQty($id);
        return (String)$qty;
    }
}