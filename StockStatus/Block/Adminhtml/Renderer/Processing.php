<?php
namespace Chin\StockStatus\Block\Adminhtml\Renderer;

/**
 * Renderer for Qty in processing order column in stock status grid
 *
 * @author     
 */
class Processing extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\Text
{

    /**
     * @var \Magento\Sales\Model\ResourceModel\Order\CollectionFactory
     */
    protected $_orderCollectionFactory;

    /**
     * Constructor
     *
     * @param \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory
     *
     */
    public function __construct(
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory)
    {
        $this->_orderCollectionFactory = $orderCollectionFactory;
    }

    /**
     * Get Qty in processing orders by Product Id
     *
     * @param $productId
     * @return int $processing
     */
    public function getStockQty($productId)
    {
        $processing = 0;
        $orderCollection = $this->_orderCollectionFactory->create();

        $orderCollection->getSelect()
                ->join(
                    'sales_order_item',
                    'main_table.entity_id = sales_order_item.order_id'
                )->where('product_id = '.$productId)
                ->where('status = "Processing"');

        $orderCollection->getSelect()->group('main_table.entity_id');
        foreach ($orderCollection as $order) {
            $processing += (int)$order->getQtyOrdered();
        }

        return $processing;
    }

    /**
     * Render processing qty column
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