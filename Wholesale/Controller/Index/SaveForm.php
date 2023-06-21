<?php

namespace Dev\Wholesale\Controller\Index;

use Dev\Wholesale\Model\ContactFactory;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;

class SaveForm extends \Magento\Framework\App\Action\Action
{
    protected $contactFactory;
    protected $resultFactory;

    public function __construct(
        Context $context,
        ContactFactory $contactFactory,
        ResultFactory $resultFactory
    )
    {
        $this->contactFactory = $contactFactory;
        $this->resultFactory = $resultFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $postData = $this->getRequest()->getPostValue();

        // Check if any required field is empty
        if (empty($postData['customer_name']) || empty($postData['email']) || empty($postData['product_name']) || empty($postData['phone_number'])) {
            $this->messageManager->addErrorMessage(__('Please fill in all required fields.'));
            $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
            $resultRedirect->setUrl($this->_redirect->getRefererUrl());
            return $resultRedirect;
        }

        // Create an instance of the ContactFactory model
        $contactForm = $this->contactFactory->create();

        // Set values from the form data to the Contact model
        $contactForm->setCustomerName($postData['customer_name']);
        $contactForm->setCustomerEmail($postData['email']);
        $contactForm->setProductName($postData['product_name']);
        $contactForm->setPhone($postData['phone_number']);
        $contactForm->setMessage($postData['message']);
        $contactForm->setProductId($postData['product_id']);
        $contactForm->setCustomerId($postData['customer_id']);

        // Save the Contact model
        $contactForm->save();

        // Display success message
        $this->messageManager->addSuccessMessage(__('Form submitted successfully.'));

        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('wholesale/customer');
        return $resultRedirect;
    }
}
