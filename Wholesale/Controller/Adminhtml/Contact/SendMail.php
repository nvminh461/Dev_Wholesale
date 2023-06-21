<?php

namespace Dev\Wholesale\Controller\Adminhtml\Contact;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Backend\App\Action;
use Dev\Wholesale\Model\Contact;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Framework\Controller\ResultFactory;

class SendMail extends \Magento\Backend\App\Action implements HttpGetActionInterface
{
    protected $contact;
    protected $transportBuilder;
    protected $inlineTranslation;
    protected $resultFactory;


    /**
     * SendMail constructor.
     *
     * @param Action\Context $context
     * @param TransportBuilder $transportBuilder
     * @param StateInterface $inlineTranslation
     * @param ResultFactory $resultFactory
     * @param Contact $contact
     */
    public function __construct(
        Action\Context $context,
        TransportBuilder $transportBuilder,
        StateInterface $inlineTranslation,
        ResultFactory $resultFactory,
        Contact $contact
    ) {
        $this->contact = $contact;
        $this->transportBuilder = $transportBuilder;
        $this->inlineTranslation = $inlineTranslation;
        $this->resultFactory = $resultFactory;
        parent::__construct($context);
    }

    /**
     * Execute action
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        // 1. Get ID and create model
        $contactId = $this->getRequest()->getParam('contact_id');
        $contactInfo = $this->contact->load($contactId);

        // 2. Send email
        try {
            $this->inlineTranslation->suspend();
            $sender = [
                'name' => 'Smart OSC store',
                'email' => 'mn123733@gmail.com'
            ];
            $recipient = $contactInfo->getCustomer_email(); // Assuming you have an email field in the Contact model
            $customer = $contactInfo->getCustomer_name();
            $product = $contactInfo->getProduct_name();
            $transport = $this->transportBuilder
                ->setTemplateIdentifier('contact_email_wholesale_template') // Replace with your email template identifier
                ->setTemplateOptions(['area' => \Magento\Framework\App\Area::AREA_ADMINHTML, 'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID])
                ->setTemplateVars([
                    'customer' => $customer,
                    'product' => $product,
                ])
                ->setFrom($sender)
                ->addTo($recipient)
                ->getTransport();

            $transport->sendMessage();

            $this->inlineTranslation->resume();

            $contactInfo->setData('status', 1);
            $contactInfo->save();

            $this->messageManager->addSuccess(__('You sent the mail.'));

        } catch (\Exception $e) {
            $this->messageManager->addError(__('An error occurred while sending the email: %1', $e->getMessage()));
        }

        return $resultRedirect->setPath('*/*/');
    }
}
