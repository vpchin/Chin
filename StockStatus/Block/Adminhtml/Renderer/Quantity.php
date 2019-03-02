<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Chin\StockStatus\Block\Adminhtml\Renderer;

/**
 * Renderer for Remain Qty field in sales create new order search grid
 *
 * @author     
 */
class Quantity extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\Text
{

    protected $_stockItemRepository;

    /**
     * Type config
     *
     * @var \Magento\Catalog\Model\ProductTypes\ConfigInterface
     */
    public function __construct(     
        \Magento\CatalogInventory\Model\Stock\StockItemRepository $stockItemRepository
    )
    {
        $this->_stockItemRepository = $stockItemRepository;

    }

    public function getStockItem($productId)
    {
        return $this->_stockItemRepository->get($productId)->getQty();
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
        $qty = $this->getStockItem($id);
        return (String)$qty;
    }
}