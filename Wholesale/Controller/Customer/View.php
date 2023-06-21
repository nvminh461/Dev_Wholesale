<?php

namespace Dev\Wholesale\Controller\Customer;

use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Review\Controller\Customer as CustomerController;
use Dev\Wholesale\Model\ContactFactory;

class View extends CustomerController
{
    /**
     * @var \Magento\Review\Model\ReviewFactory
     */
    protected $reviewFactory;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Dev\Wholesale\Model\ContactFactory $reviewFactory
     */
    public function __construct(
        Context         $context,
        CustomerSession $customerSession,
        ContactFactory  $wholesaleFactory
    )
    {
        $this->wholesaleFactory = $wholesaleFactory;
        parent::__construct($context, $customerSession);
    }

    /**
     * Render review details
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $wholesale = $this->wholesaleFactory->create()->load($this->getRequest()->getParam('id'));
        if ($wholesale->getCustomerId() != $this->customerSession->getCustomerId()) {
            /** @var \Magento\Framework\Controller\Result\Forward $resultForward */
            $resultForward = $this->resultFactory->create(ResultFactory::TYPE_FORWARD);
            $resultForward->forward('noroute');
            return $resultForward;
        }
        /** @var \Magento\Framework\View\Result\Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        if ($navigationBlock = $resultPage->getLayout()->getBlock('customer_account_navigation')) {
            $navigationBlock->setActive('wholesale/customer');
        }
        $resultPage->getConfig()->getTitle()->set(__('Wholesale Details'));
        return $resultPage;
    }
}
