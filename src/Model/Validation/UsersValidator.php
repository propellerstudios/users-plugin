<?php

namespace Propeller\Users\Model\Validation;

use Cake\Validation\Validator;
use Cake\ORM\RulesChecker;
use Cake\ORM\TableRegistry;
use Cake\Core\Configure;

class UsersValidator extends Validator
{
    public function __construct()
    {
        parent::__construct();
        
        $usersTable = TableRegistry::get('Users');
        
        $this->requirePresence('username', 'create')
             ->requirePresence('password', 'create')
             ->requirePresence('first_name', 'create')
             ->requirePresence('last_name', 'create');
        
        if (Configure::read('Users.emailAsUsername')) {
            $this->add('username', 'validFormat', [
                'rule' => 'email',
                'message' => __('Must be a valid email address.')
            ]);
        } else {
            $this->requirePresence('email', 'create')
                 ->add('email', 'validFormat', [
                    'rule' => 'email',
                    'message' => __('Must be a valid email address.')
                 ]);
        }
        
        $this->add('confirm_password', 'compare', [
            'rule' => ['compareWith', 'password'],
            'message' => __('Passwords do not match.')
        ]);
        
        $this->add('username', 'unique', [
                'rule' => function ($value, $context) use ($usersTable) {
                    $user = $usersTable->findByUsername($value);
                    
                    return ($user->count() > 0) ? false : true;
                },
                'message' => __('This username is already in use.')
             ])
             ->add('email', 'unique', [
                'rule' => function ($value, $context) use ($usersTable) {
                    $user = $usersTable->findByEmail($value);
                    
                    return ($user->count() > 0) ? false : true;
                },
                'message' => __('This email is already in use.')
             ]);
    }
}