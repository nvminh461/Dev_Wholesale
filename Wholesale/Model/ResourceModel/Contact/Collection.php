<?php

namespace Dev\Wholesale\Model\ResourceModel\Contact;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Dev\Wholesale\Model\Contact as ContactModel;
use Dev\Wholesale\Model\ResourceModel\Contact as ContactResource;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(ContactModel::class, ContactResource::class);
    }
}
