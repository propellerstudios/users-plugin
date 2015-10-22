<?php

namespace Propeller\Users\Model\Entity;

use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;
use Cake\Core\Configure;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Utility\Text;
use Cake\Mailer\Email;

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
     * This will send the Password Reset link to the email address registered.
     */
    public function emailPasswordReset()
    {
        $email = $this->getEmailInstance();
        $email->subject('Requested Password Reset')
              ->template('Propeller/Users.reset_password')
              ->send();
    }
    
    /**
     * This will send the verification link to the email address registered.
     */
    public function emailVerification()
    {
        $email = $this->getEmailInstance();
        $email->subject('Thank you for registering!')
              ->template('Propeller/Users.verification')
              ->send();
    }
    
    /**
     * Builds an instance of the Email object with some default settings applied
     * from the model data.
     *
     * @return \Cake\Mailer\Email
     */
    private function getEmailInstance()
    {
        $email = new Email();
        $email->to($this->email)
              ->viewVars(['key' => $this->personal_key])
              ->emailFormat('both');
        
        return $email;
    }
    
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
        if (Configure::read('Users.email_as_username')) {
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
        if (Configure::read('Users.send_email_verification')) {
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