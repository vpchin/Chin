<?php
namespace Chin\StockStatus\Block\Adminhtml\Renderer;

/**
 * Renderer for Available Qty column in stock status grid
 *
 * @author     
 */
class Available extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\Text
{

    /**
     * @var \Magento\Sales\Model\ResourceModel\Order\CollectionFactory
     */
    protected $_orderCollectionFactory;

    /**
     * @var \Magento\CatalogInventory\Model\Stock\StockItemRepository
     */
    protected $_stockItemRepository;

    /**
     * Constructor
     *
     * @param \Magento\CatalogInventory\Model\Stock\StockItemRepository $stockItemRepository
     * @param \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory
     *
     */
    public function __construct(
        \Magento\CatalogInventory\Model\Stock\StockItemRepository $stockItemRepository,
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory)
    {
        $this->_orderCollectionFactory = $orderCollectionFactory;
        $this->_stockItemRepository = $stockItemRepository;
    }

    /**
     * Get Available Quantity by Product Id
     *
     * @param $productId
     * @return int $remain
     */
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
     * Render available quantity column
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