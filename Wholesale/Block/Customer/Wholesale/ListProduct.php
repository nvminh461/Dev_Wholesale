<?php

namespace Dev\Wholesale\Block\Customer\Wholesale;

use Magento\Catalog\Model\Product;
use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;

/**
 * Customer Wholesale list block
 *
 * @api
 * @since 100.0.2
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class ListProduct extends \Magento\Customer\Block\Account\Dashboard
{
    /**
     * Contact wholesale collection
     *
     * @var \Dev\Wholesale\Model\ResourceModel\Contact\Collection
     */
    protected $_collection;

    /**
     * Contact wholesale resource model
     *
     * @var \Dev\Wholesale\Model\ResourceModel\Contact\CollectionFactory
     */
    protected $_collectionFactory;

    /**
     * @var \Magento\Customer\Helper\Session\CurrentCustomer
     */
    protected $currentCustomer;

    /**
     * Catalog product model
     *
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Newsletter\Model\SubscriberFactory $subscriberFactory
     * @param CustomerRepositoryInterface $customerRepository
     * @param AccountManagementInterface $customerAccountManagement
     * @param \Dev\Wholesale\Model\ResourceModel\Contact\CollectionFactory $collectionFactory
     * @param \Magento\Customer\Helper\Session\CurrentCustomer $currentCustomer
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context             $context,
        \Magento\Customer\Model\Session                              $customerSession,
        \Magento\Newsletter\Model\SubscriberFactory                  $subscriberFactory,
        CustomerRepositoryInterface                                  $customerRepository,
        AccountManagementInterface                                   $customerAccountManagement,
        \Dev\Wholesale\Model\ResourceModel\Contact\CollectionFactory $collectionFactory,
        \Magento\Catalog\Api\ProductRepositoryInterface              $productRepository,
        \Magento\Customer\Helper\Session\CurrentCustomer             $currentCustomer,
        array                                                        $data = [],
    )
    {
        $this->_collectionFactory = $collectionFactory;
        parent::__construct(
            $context,
            $customerSession,
            $subscriberFactory,
            $customerRepository,
            $customerAccountManagement,
            $data
        );
        $this->productRepository = $productRepository;
        $this->currentCustomer = $currentCustomer;
    }

    /**
     * Get html code for toolbar
     *
     * @return string
     */
    public function getToolbarHtml()
    {
        return $this->getChildHtml('toolbar');
    }

    /**
     * Initializes toolbar
     *
     * @return \Magento\Framework\View\Element\AbstractBlock
     */
    protected function _prepareLayout()
    {
        if ($this->getWholesales()) {
            $toolbar = $this->getLayout()->createBlock(
                \Magento\Theme\Block\Html\Pager::class,
                'customer_wholesale_list.toolbar'
            )->setCollection(
                $this->getWholesales()
            );

            $this->setChild('toolbar', $toolbar);
        }
        return parent::_prepareLayout();
    }

    /**
     * Get Wholesales
     *
     * @return bool|\Dev\Wholesale\Model\ResourceModel\Contact\Collection
     */
    public function getWholesales()
    {
        if (!($customerId = $this->currentCustomer->getCustomerId())) {
            return false;
        }
        if (!$this->_collection) {
            $this->_collection = $this->_collectionFactory->create();
            $this->_collection
                ->addFieldToFilter('customer_id', $customerId);
        }
        return $this->_collection;
    }

    /**
     * Get Wholesale URL
     *
     * @param \Dev\Wholesale\Model\Contact $wholesale
     * @return string
     */
    public function getWholesaleUrl($wholesale)
    {
        return $this->getUrl('wholesale/customer/view', ['id' => $wholesale->getContactId()]);
    }

    /**
     * Get product data
     *
     * @return Product
     */
    public function getProductData($wholesale)
    {
        if ($wholesale->getContactId() && !$this->getProductCacheData()) {
            $product = $this->productRepository->getById($wholesale->getProductId());
            $this->setProductCacheData($product);
        }
        return $this->getProductCacheData();
    }
}
