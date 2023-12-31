<?php

namespace Dev\Wholesale\Block;

use Magento\Framework\View\Element\Template;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Catalog\Model\ProductFactory;
use Magento\Catalog\Block\Product\View as ProductViewBlock;
use Magento\Catalog\Model\ProductRepository;
use Magento\Framework\App\ResponseInterface;
use Dev\Wholesale\Model\Contact;

class ContactWholesaleButton extends Template
{
    protected $customerSession;
    protected $productFactory;
    protected $productViewBlock;
    protected $productRepository;
    protected $messageManager;
    protected $urlInterface;
    protected $responseInterface;
    protected $contactModel;

    /**
     * ContactWholesaleButton constructor.
     *
     * @param Template\Context $context
     * @param CustomerSession $customerSession
     * @param ProductFactory $productFactory
     * @param ProductViewBlock $productViewBlock
     * @param ProductRepository $productRepository
     * @param ManagerInterface $messageManager
     * @param ResponseInterface $responseInterface
     * @param UrlInterface $urlInterface
     * @param Contact $contactModel
     * @param array $data
     */
    public function __construct(
        Template\Context  $context,
        CustomerSession   $customerSession,
        ProductFactory    $productFactory,
        ProductViewBlock  $productViewBlock,
        ProductRepository $productRepository,
        ResponseInterface $responseInterface,
        Contact           $contactModel,
        array             $data = []
    )
    {
        $this->customerSession = $customerSession;
        $this->productFactory = $productFactory;
        $this->productViewBlock = $productViewBlock;
        $this->productRepository = $productRepository;
        $this->responseInterface = $responseInterface;
        $this->contactModel = $contactModel;
        parent::__construct($context, $data);
    }

    /**
     * Get the URL for the wholesale button
     *
     * @return string|null
     */
    public function getButtonUrl()
    {
        $pathUrl = '';
        $productId = $this->getIdProduct();
        if ($this->compareAttributes()) {
            $pathUrl = 'wholesale/index/index/';
        }
        return $this->getUrl($pathUrl, ['product_id' => $productId]);
    }

    /**
     * Get the id of the product
     *
     * @return int|null
     */
    public function getIdProduct()
    {
        $productId = $this->productViewBlock->getProduct()->getId();
        return $productId;
    }

    /**
     * Check the wholesale attribute of the customer
     *
     * @return int
     */
    public function checkAttributeCustomer()
    {
        $customerAttribute = '';

        if ($this->customerSession->isLoggedIn()) {
            $customerAttribute = $this->customerSession->getCustomer();
        }
        return $customerAttribute;
    }

    /**
     * Check the wholesale attribute of the product
     *
     * @return int|null
     */
    public function checkAttributeProduct()
    {
        $productId = $this->getIdProduct();
        try {
            $product = $this->productRepository->getById($productId);
            $productAttribute = $product->getData('pro_wholesale');
        } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
            return null;
        }
        return $productAttribute;
    }

    /**
     * Compare the wholesale attributes of the customer and product
     *
     * @return bool
     */
    public function compareAttributes()
    {
        $customer = $this->checkAttributeCustomer();
        $customerAttribute = $customer ? $customer->getCusWholesale() : '';
        $customerId = $customer ? $customer->getId() : '';

        $productAttribute = $this->checkAttributeProduct();
        $productId = $this->getIdProduct();

        $contactCollection = $this->contactModel->getCollection()
            ->addFieldToFilter('customer_id', $customerId)
            ->addFieldToFilter('product_id', $productId);

        return ($customerAttribute == 1 && $productAttribute == 1 && $contactCollection->getSize() == 0);
    }
}
