<?php
declare(strict_types=1);

namespace Magelearn\CategoryProductEdit\Block\Adminhtml\Category\Tab;

use Magento\Framework\Data\Collection;
use Magelearn\CategoryProductEdit\Block\Adminhtml\Category\Tab\Product\Grid\Renderer\Image;
use Magento\Framework\Exception\LocalizedException;

class Product extends \Magento\Catalog\Block\Adminhtml\Category\Tab\Product
{
    /**
     * @var array
     */
    private array $thumbnailAttributes = ['thumbnail', 'small_image', 'image'];
    
    /**
     * Set collection object adding product thumbnail
     *
     * @param Collection $collection
     * @return void
     */
    public function setCollection($collection): void
    {
        // Add multiple image attributes for better fallback
        foreach ($this->thumbnailAttributes as $attribute) {
            $collection->addAttributeToSelect($attribute);
        }
        $this->_collection = $collection;
    }
    
    /**
     * Add column image with a custom renderer and after column entity_id
     *
     * @return Product
     * @throws LocalizedException
     */
    protected function _prepareColumns(): Product
    {
        parent::_prepareColumns();
        
        $this->addColumnAfter(
            'product_thumbnail',  // Changed from 'image' to be more specific
            [
                'header' => __('Thumbnail'),
                'index' => 'thumbnail',
                'renderer' => Image::class,
                'filter' => false,
                'sortable' => false,
                'column_css_class' => 'data-grid-thumbnail-cell',
                'header_css_class' => 'col-thumbnail'
            ],
            'entity_id'
            );
        
        $this->sortColumnsByOrder();
        
        return $this;
    }
}