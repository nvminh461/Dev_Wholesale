<?php

namespace Dev\Wholesale\Model;

/**
 * The Status model represents the status of a contact.
 */
class Status extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Status constant for "Sent" status.
     */
    const STATUS_SENT = 1;

    /**
     * Status constant for "Unsent" status.
     */
    const STATUS_UNSENT = 0;

    /**
     * Initialize the model and its resource model.
     */
    protected function _construct()
    {
        $this->_init('Dev\Wholesale\Model\ResourceModel\Contact');
    }

    /**
     * Get the available statuses as an associative array.
     *
     * @return array
     */
    public function getAvailableStatuses()
    {
        return [
            self::STATUS_SENT => __('Sent'),
            self::STATUS_UNSENT => __('Unsent')
        ];
    }
}
