<?php

namespace Dev\Wholesale\Block\Adminhtml\Contact\View;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Dev\Wholesale\Model\Contact;

class SendMail extends GenericButton implements ButtonProviderInterface
{
    protected $contact;

    /**
     * SendMail constructor.
     *
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param Contact $contact
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        Contact $contact,
        array $data = []
    ) {
        $this->contact = $contact;
        parent::__construct($context, $data);
    }

    /**
     * Get button data
     *
     * @return array
     */
    public function getButtonData()
    {
        $buttonData = [];
        $contactId = $this->getContractId();

        // Load the contact model to check the status
        $contactModel = $this->contact->load($contactId);

        // Get the status value
        $status = $contactModel->getStatus();

        // Check the status value and conditionally add the button data
        if ($status == 0) {
            $buttonData = [
                'label' => __('Send Mail'),
                'on_click' => sprintf("location.href = '%s';", $this->SendMailUrl()),
                'class' => 'save primary',
                'sort_order' => 20
            ];
        }
        return $buttonData;
    }

    /**
     * Get the URL for sending mail
     *
     * @return string
     */
    public function SendMailUrl()
    {
        $contactId = $this->getContractId();
        return $this->getUrl('*/*/SendMail', ['contact_id' => $contactId]);
    }
}
