<?php
declare(strict_types=1);

namespace Magelearn\CategoryProductEdit\Block\Adminhtml\Category\Tab\Product\Grid\Renderer;

use Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer;
use Magento\Catalog\Helper\Image as ImageHelper;
use Magento\Framework\DataObject;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Escaper;

class Image extends AbstractRenderer
{
    /**
     * @var ImageHelper
     */
    private ImageHelper $imageHelper;

    /**
     * @var StoreManagerInterface
     */
    private StoreManagerInterface $storeManager;

    /**
     * @var Escaper
     */
    private Escaper $escaper;

    /**
     * @param \Magento\Backend\Block\Context $context
     * @param ImageHelper $imageHelper
     * @param StoreManagerInterface $storeManager
     * @param Escaper $escaper
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Context $context,
        ImageHelper $imageHelper,
        StoreManagerInterface $storeManager,
        Escaper $escaper,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->imageHelper = $imageHelper;
        $this->storeManager = $storeManager;
        $this->escaper = $escaper;
    }

    /**
     * Render product thumbnail with fallback logic
     *
     * @param DataObject $row
     * @return string
     */
    public function render(DataObject $row): string
    {
        $imageUrl = $this->getProductImageUrl($row);
        $altText = $this->escaper->escapeHtmlAttr($row->getName());
        
        return sprintf(
            '<img src="%s" alt="%s" width="50" height="50" loading="lazy" class="admin__control-thumbnail"/>',
            $this->escaper->escapeUrl($imageUrl),
            $altText
        );
    }

    /**
     * Get product image URL with fallback logic
     *
     * @param DataObject $row
     * @return string
     */
    private function getProductImageUrl(DataObject $row): string
    {
        // Try thumbnail first
        if ($row->getThumbnail() && $row->getThumbnail() !== 'no_selection') {
            return $this->imageHelper->init($row, 'product_listing_thumbnail')
                ->setImageFile($row->getThumbnail())
                ->getUrl();
        }

        // Try small_image as fallback
        if ($row->getSmallImage() && $row->getSmallImage() !== 'no_selection') {
            return $this->imageHelper->init($row, 'product_listing_thumbnail')
                ->setImageFile($row->getSmallImage())
                ->getUrl();
        }

        // Try base image as last resort
        if ($row->getImage() && $row->getImage() !== 'no_selection') {
            return $this->imageHelper->init($row, 'product_listing_thumbnail')
                ->setImageFile($row->getImage())
                ->getUrl();
        }

        // Return placeholder if no images found
        return $this->imageHelper->getDefaultPlaceholderUrl('small_image');
    }
}