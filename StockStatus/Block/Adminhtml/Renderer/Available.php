<?php
namespace Chin\StockStatus\Block\Adminhtml\Renderer;

class Available extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\Text
{

    protected $_stockRegistry;

    public function __construct(
        \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry)
    {
        $this->_stockRegistry = $stockRegistry;
    }

    public function getStockQty($productId)
    {
        $stockitem = $this->_stockRegistry->getStockItem($productId);
        $qtyStock = $stockitem->getQtyOrdered();
        return $qtyStock;
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
        $availQty = $this->getStockQty($id);
        return $availQty;
    }
}