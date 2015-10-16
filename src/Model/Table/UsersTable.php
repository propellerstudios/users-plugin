<?php

namespace Propeller\Users\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\ORM\Entity;
use Cake\ORM\RulesChecker;
use Cake\Core\Configure;
use Cake\Event\Event;

class UsersTable extends Table
{
    public function initialize(array $config)
    {
        $this->addBehavior('Timestamp');
        $this->_validatorClass = '\Propeller\Users\Model\Validation\UsersValidator';
    }
    
    /**
     * When the first user registers, they are automatically assigned as an
     * Admin within the system. This is an option that can be set in the user
     * configuration file.
     */
    public function afterSave(Event $event, Entity $entity, \ArrayObject $options)
    {
        if (Configure::read('Users.firstUserIsAdmin')) {
            if ($this->find()->count() === 1) {
                $adminsTable = TableRegistry::get('Admins');
                $admin = $adminsTable->newEntity();
                
                $admin->user_id = $entity->id;
                $admin->created = new \DateTime('now');
                $admin->modified = new \DateTime('now');
                
                $adminsTable->save($admin);
            }            
        }
    }
    
    public function beforeSave(Event $event, Entity $entity)
    {
        $entity->set('personal_key');
    }
    
    /**
     * Given the ID of a user, determines if they are apart of the Admin table
     *
     * @param integer $id The ID of the user in question.
     * @return boolean True if the user is also an Admin.
     */
    public function isAdmin($id)
    {
        $adminsTable = TableRegistry::get('Admins');
        $admin = $adminsTable->findByUserId($id);
        
        return ($admin->count() > 0) ? true : false;
    }
}