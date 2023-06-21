<?php
namespace Dev\Wholesale\Model;

use Magento\Framework\Model\AbstractModel;

class Contact extends AbstractModel
{
    protected function _construct()
    {
        $this->_init(\Dev\Wholesale\Model\ResourceModel\Contact::class);
    }
}
