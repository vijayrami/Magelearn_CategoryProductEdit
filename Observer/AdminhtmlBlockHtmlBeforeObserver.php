<?php

declare(strict_types=1);

namespace Magelearn\CategoryProductEdit\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Event\Observer;
use Magento\Catalog\Block\Adminhtml\Category\Tab\Product;

class AdminhtmlBlockHtmlBeforeObserver implements ObserverInterface
{
    /**
     * Request instance
     *
     * @var RequestInterface
     */
    protected $request;

    /**
     * @param RequestInterface $request
     */
    public function __construct(
        RequestInterface $request
    ) {
        $this->request = $request;
    }

    /**
     * Add edit product column
     *
     * @param Observer $observer
     * @return void
     * @throws \Exception
     */
    public function execute(Observer $observer)
    {
        $block = $observer->getEvent()->getBlock();
        if (false === ($block instanceof Product)) {
            return;
        }
        
        $block->addColumn(
            'action',
            [
                'header' => __('Action'),
                'type' => 'action',
                'getter' => 'getId',
                'actions' => [
                    [
                        'caption' => __('Edit'),
                        'url' => [
                            'base' => 'catalog/product/edit',
                            'params' => ['store' => $this->request->getParam('store')]

                        ],
                        'field' => 'id'
                    ]
                ],
                'filter' => false,
                'sortable' => false,
                'index' => 'stores',
                'header_css_class' => 'col-action',
                'column_css_class' => 'col-action'
            ]
        );
    }
}
