<?php
namespace Chin\StockStatus\Block\Adminhtml\Renderer;

/**
 * Renderer for Total Qty column in stock status grid
 *
 * @author     
 */
class Quantity extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\Text
{

    /**
     * @var \Magento\CatalogInventory\Model\Stock\StockItemRepository
     */
    protected $_stockItemRepository;

    /**
     * Constructor
     *
     * @param \Magento\CatalogInventory\Model\Stock\StockItemRepository $stockItemRepository
     *
     */
    public function __construct(     
        \Magento\CatalogInventory\Model\Stock\StockItemRepository $stockItemRepository
    )
    {
        $this->_stockItemRepository = $stockItemRepository;

    }

    /**
     * Get Total Quantity by Product Id
     *
     * @param $productId
     * @return int $qty
     */
    public function getStockItem($productId)
    {
        return $this->_stockItemRepository->get($productId)->getQty();
    }

    /**
     * Render product qty column
     *
     * @param \Magento\Framework\DataObject $row
     * @return string
     */
    public function render(\Magento\Framework\DataObject $row)
    {
        $id = $row->getData('entity_id');
        $qty = $this->getStockItem($id);
        return (String)$qty;
    }
}