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
    
    /**
     * Every save event triggers a new personal_key. If the entity is new then
     * check the config if there needs to be an email verification.
     */
    public function beforeSave(Event $event, Entity $entity)
    {
        $entity->set('personal_key');
        
        if ($entity->isNew()) {
            if (Configure::read('Users.send_email_verification')) {
                $entity->emailVerification();
            }
        }
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
    
    /**
     * Basically just an alias method to read the configuration file to see if
     * the username and email address are synonymous.
     *
     * @return boolean True if the email and username are synonymous
     */
    public function usernameIsEmail()
    {
        return Configure::read('Users.use_email_as_username');
    }
}