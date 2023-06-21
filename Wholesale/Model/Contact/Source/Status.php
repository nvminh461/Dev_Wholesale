<?php
namespace Dev\Wholesale\Model\Contact\Source;

use Magento\Framework\Data\OptionSourceInterface;

class Status implements OptionSourceInterface
{
    protected $status;

    /**
     * Status constructor.
     *
     * @param \Dev\Wholesale\Model\Status $status
     */
    public function __construct(\Dev\Wholesale\Model\Status $status)
    {
        $this->status = $status;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $availableOptions = $this->status->getAvailableStatuses();
        $options = [];
        foreach ($availableOptions as $key => $value) {
            $options[] = [
                'label' => $value,
                'value' => $key,
            ];
        }
        return $options;
    }
}
