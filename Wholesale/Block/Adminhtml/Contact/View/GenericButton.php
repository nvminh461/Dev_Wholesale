<?php
namespace Dev\Wholesale\Block\Adminhtml\Contact\View;

use Magento\Backend\Block\Widget\Context;

/**
 * Class GenericButton
 */
class GenericButton
{
    protected $context;

    /**
     * GenericButton constructor.
     *
     * @param Context $context
     */
    public function __construct(
        Context $context,
    ) {
        $this->context = $context;
    }

    /**
     * Get the contract ID from the request parameters
     *
     * @return int|null
     */
    public function getContractId()
    {
        $contractId = $this->context->getRequest()->getParam('contact_id');
        if ($contractId) {
            return $contractId;
        }
        return null;
    }

    /**
     * Get the URL for the given route and parameters
     *
     * @param string $route
     * @param array $params
     * @return string
     */
    public function getUrl($route = '', $params = [])
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }
}
