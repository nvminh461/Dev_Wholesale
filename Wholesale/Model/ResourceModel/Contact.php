<?php
namespace Dev\Wholesale\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Contact extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('dev_wholesale_contact', 'contact_id');
    }
}
