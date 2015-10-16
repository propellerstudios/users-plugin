<?php

namespace Propeller\Users\Model\Entity;

use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;
use Cake\Core\Configure;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Utility\Text;

class User extends Entity
{
    /**
     * @var array List of virtual fields available to the entity.
     */
    protected $_virtual = [
        'name',
        'admin'
    ];
    
    /**
     * @var array List of fields to hide from the entity.
     */
    protected $_hidden = [
        'password'
    ];
    
    /**
     * @return string First and Last names as a single string.
     */
    protected function _getName()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
    
    /**
     * @return boolean True if the user is also an admin.
     */
    protected function _getAdmin()
    {
        $adminsTable = TableRegistry::get('Admins');
        $query = $adminsTable->findByUserId($this->id);
        
        return ($query->count() > 0) ? true : false;
    }

    /**
     * Setter for the email field that checks the configuration file to see if
     * the username and email fields are synonymous or not. If they are, the
     * setter will return the username as the email address.
     *
     * @param string $email A valid email address.
     * @return string An email address.
     */
    protected function _setEmail($email)
    {
        if (Configure::read('Users.emailAsUsername')) {
            return $this->username;
        }
        
        return $email;
    }
    
    /**
     * Assures a nicely hashed password in the database.
     * 
     * @param string $password The raw string of the password.
     * @return string A hashed version of the password.
     */
    protected function _setPassword($password)
    {
        return (new DefaultPasswordHasher())->hash($password);
    }
    
    /**
     * Sets the active field based on the configuration file. By default there
     * is no verification and the users are automatically active.
     *
     * @return bool Either active or inactive according to the configuration
     */
    protected function _setActive()
    {
        if (Configure::read('sendEmailVerification')) {
            
            return 0;
        }
        
        return 1;
    }
    
    /**
     * Each personal key is a UUID.
     *
     * @return string a UUID string
     */
    protected function _setPersonalKey()
    {
        return Text::uuid();
    }
}