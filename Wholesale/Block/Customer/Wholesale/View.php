<?php

namespace Dev\Wholesale\Block\Customer\Wholesale;

use Dev\Wholesale\Model\Contact;
use Magento\Catalog\Model\Product;

/**
 * Customer Wholesale detailed view block
 *
 */
class View extends \Magento\Catalog\Block\Product\AbstractProduct
{
    /**
     * Customer view template name
     *
     * @var string
     */
    protected $_template = 'Dev_Wholesale::customer/wholesale/view.phtml';

    /**
     * Catalog product model
     *
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * Review model
     *
     * @var \Dev\Wholesale\Model\ContactFactory
     */
    protected $_wholesaleFactory;

    /**
     * @var \Magento\Customer\Helper\Session\CurrentCustomer
     */
    protected $currentCustomer;

    /**
     * @param \Magento\Catalog\Block\Product\Context $context
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     * @param \Dev\Wholesale\Model\ContactFactory $wholesaleFactory
     * @param \Magento\Customer\Helper\Session\CurrentCustomer $currentCustomer
     * @param array $data
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context           $context,
        \Magento\Catalog\Api\ProductRepositoryInterface  $productRepository,
        \Dev\Wholesale\Model\ContactFactory              $wholesaleFactory,
        \Magento\Customer\Helper\Session\CurrentCustomer $currentCustomer,
        array                                            $data = []
    )
    {
        $this->productRepository = $productRepository;
        $this->_wholesaleFactory = $wholesaleFactory;
        $this->currentCustomer = $currentCustomer;
        parent::__construct(
            $context,
            $data
        );
    }

    /**
     * Initialize contact wholesale id
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setContactId($this->getRequest()->getParam('id', false));
    }

    /**
     * Get product data
     *
     * @return Product
     */
    public function getProductData()
    {
        if ($this->getContactId() && !$this->getProductCacheData()) {
            $product = $this->productRepository->getById($this->getWholesaleData()->getProductId());
            $this->setProductCacheData($product);
        }
        return $this->getProductCacheData();
    }

    /**
     * Get wholesale data
     *
     * @return Contact
     */
    public function getWholesaleData()
    {
        if ($this->getContactId() && !$this->getWholesaleCachedData()) {
            $this->setWholesaleCachedData($this->_wholesaleFactory->create()->load($this->getContactId()));
        }
        return $this->getWholesaleCachedData();
    }

    /**
     * Return wholesale customer url
     *
     * @return string
     */
    public function getBackUrl()
    {
        return $this->getUrl('wholesale/customer');
    }

    /**
     * @inheritDoc
     */
    protected function _toHtml()
    {
        return $this->currentCustomer->getCustomerId() ? parent::_toHtml() : '';
    }
}
