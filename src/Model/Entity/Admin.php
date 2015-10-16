<?php

namespace Propeller\Users\Model\Entity;

use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;

class Admin extends Entity
{
    protected function _getUsers()
    {
        $usersTable = TableRegistry::get('Users');
        return $usersTable->findById($this->user_id)->toArray();
    }
}