<?php

namespace Dev\Wholesale\Block\Frontend;

use Magento\Customer\Model\Session as CustomerSession;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\View\Element\Template;
use Magento\Catalog\Model\ProductRepository;
use Magento\Framework\App\RequestInterface;
use Dev\Wholesale\Model\Contact;


class ContactForm extends Template
{
    protected $customerSession;
    protected $customerRepository;
    protected $productRepository;
    protected $request;
    protected $contactModel;


    public function __construct(
        Template\Context            $context,
        CustomerSession             $customerSession,
        CustomerRepositoryInterface $customerRepository,
        ProductRepository           $productRepository,
        RequestInterface            $request,
        Contact                     $contactModel,
        array                       $data = []
    )
    {
        parent::__construct($context, $data);
        $this->customerSession = $customerSession;
        $this->customerRepository = $customerRepository;
        $this->productRepository = $productRepository;
        $this->contactModel = $contactModel;
        $this->request = $request;
    }

    public function getCurrentCustomer()
    {
        if ($this->customerSession->isLoggedIn()) {
            return $this->customerSession->getCustomer();
        }
        return null;
    }

    public function getCurrentProduct()
    {
        $productId = $this->request->getParam('product_id');
        $product = '';
        if ($productId) {
            $product = $this->productRepository->getById($productId);
        }
        return $product;
    }

    public function getProductId()
    {
        return $this->request->getParam('productId');
    }

    /**
     * Compare the wholesale attributes of the customer and product
     *
     * @return bool
     */
    public function compareAttributes()
    {
        $currentCustomer = $this->getCurrentCustomer();
        $customerAttribute = $currentCustomer ? $currentCustomer->getCusWholesale() : '';
        $customerId = $currentCustomer ? $currentCustomer->getId() : '';

        $productAttribute = $this->getCurrentProduct()->getData('pro_wholesale');
        $productId = $this->getCurrentProduct()->getId();

        $contactCollection = $this->contactModel->getCollection()
            ->addFieldToFilter('customer_id', $customerId)
            ->addFieldToFilter('product_id', $productId);

        return $customerAttribute == 1 && $productAttribute == 1 && $contactCollection->getSize() == 0;
    }
}
