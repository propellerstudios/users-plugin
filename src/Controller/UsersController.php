<?php

namespace Propeller\Users\Controller;

use Propeller\Users\Controller\AppController;
use Cake\Core\Configure;
use Cake\Mailer\Email;

class UsersController extends AppController
{
    public function beforeFilter(\Cake\Event\Event $event)
    {
        parent::beforeFilter($event);
        
        $whiteList = Configure::read('Users.white_list');
        
        if (Configure::read('Users.open_registration')) {
            $whiteList[] = 'register';
        }
        
        $this->Auth->allow($whiteList);
    }
    
    /**
     * Very basic right now, this just lists all the users. Eventually it will
     * be a dashboard for the user.
     */
    public function index()
    {
        if (!$this->request->session()->check('Auth.User')) {
            return $this->redirect(['action' => 'login']);
        }
    }
    
    /**
     * Edits the information about the user.
     */
    public function edit($id = null)
    {
        if (is_null($id)) {
            $id = $this->Auth->user('id');
        } else {
            if ($this->Auth->user('id') !== $id) {
                if (!$this->Auth->user('admin')) {
                    $this->Flash->error(__('You are not authorized to edit this user.'));
                    return $this->redirect(['action' => 'index']);
                }
            }
        }
        
        $user = $this->Users->findById($id)->first();
        
        if (!$user) {
            $this->Flash->error(__('This user does not exist.'));
            return $this->redirect(['action' => 'index']);
        }
        
        if ($this->request->is(['post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->data);
            
            if ($user) {
                $this->Flash->success(__('Successfully edited your profile.'));
            }
        }
        
        $this->set([
            'user' => $user,
            'useEmailAsUsername' => $this->Users->usernameIsEmail()
        ]);
    }
    
    public function view($id)
    {
        $user = $this->Users->findById($id)->first();
        
        $this->set([
            'user' => $user,
            'useEmailAsUsername' => $this->Users->usernameIsEmail()
        ]);
    }
    
    /**
     * Registers a new user.
     */
    public function register()
    {
        $user = $this->Users->newEntity();
        
        if ($this->request->is(['post'])) {   
            $user = $this->Users->patchEntity($user, $this->request->data);
            
            if ($this->Users->save($user)) {
                $this->Flash->success(__('Successfully registered user.'));
                return $this->redirect(['action' => 'index']);
            }
            
            $this->Flash->error(__('Unable to register this user.'));
            if (Configure::read('debug')) {
                $this->set(['errors' => $user->errors()]);
            }
        }
        
        $this->set([
            'user' => $user,
            'useEmailAsUsername' => $this->Users->usernameIsEmail()
        ]);
    }
    
    /**
     * Logs a user into the application.
     */
    public function login()
    {
        if ($this->request->is(['post'])) {
            $user = $this->Auth->identify();
            
            if ($user) {
                if($user['verified'] === false) {
                    $this->Flash->error(__('This user is not yet verified.'));
                    return $this->redirect(['action' => 'index']);
                }
                
                $user['admin'] = $this->Users->isAdmin($user['id']);
                
                $this->Auth->setUser($user);
                return $this->redirect($this->Auth->redirectUrl());
            }
            
            $this->Flash->error(__('Invalid username or password.'));
        }
    }
    
    /**
     * Logs a user out.
     */
    public function logout()
    {
        return $this->redirect($this->Auth->logout());
    }
    
    /**
     * Verifies newly registered users, or users who are requesting their
     * password to be changed.
     */
    public function verify()
    {
        if (!$this->request->query['key']) {
            $this->Flash->error(__('What are you doing here?'));
            return $this->redirect(['action' => 'index']);
        }
        
        $key = $this->request->query['key'];
        $user = $this->Users->findByPersonalKey($key);
            
        if ($user->count() === 0) {
            $this->Flash->error(__('This user does not exist.'));
            return $this->redirect(['action' => 'index']);
        }
            
        $action = (isset($this->request->query['action']))
                ? $this->request->query['action'] : 'index';
            
        $this->request->session()->write('Users.verified', true);
        $this->request->session()->write('Users.key', $key);
        return $this->redirect(['action' => $action]);
    }
    
    /**
     * Resets the password of a user without having them authenticated.
     */
    public function reset()
    {
        if (!$this->request->session()->read('Users.verified')) {
            $this->Flash->error(__('You have not been verified to do this.'));
            return $this->redirect(['action' => 'index']);
        }
        
        $key = $this->request->session()->read('Users.key');
        
        if ($this->request->is('put')) {
            $this->request->session()->delete('Users.verified');
            
            $user = $this->Users->findByPersonalKey($key)->first();
            $user = $this->Users->patchEntity($user, $this->request->data);
            
            if ($this->Users->save($user)) {
                $this->request->session()->delete('Users.key');
                $this->Flash->success(__('Successfully updated this user\'s password.'));
                return $this->redirect(['action' => 'index']);
            }
            
            $this->Flash->error(__('Error resetting the password.'));
            return $this->redirect(['action' => 'index']);
        }
    }

    /**
     * Verifies new users and sets their 'active' field in the database to true.
     */
    public function verifyNew()
    {
        if ($this->request->session()->read('Users.verified')) {
            $key = $this->request->session()->read('Users.key');
            $user = $this->Users->findByPersonalKey($key)->first();
            
            $user->set('active', true);
            
            if ($this->Users->save($user)) {
                $this->request->session()->delete('Users.verified');
                $this->request->session()->delete('Users.key');
                $this->Flash->success(__('You have been verified!'));
                return $this->redirect(['action' => 'login']);
            }
        }
        
        $this->Flash->error(__('You are not verified to be here.'));
        return $this->redirect(['action' => 'index']);
    }
    
    /**
     * When you forget your password, you probably want to reset it. This will
     * email you with a link to reset your password.
     */
    public function requestPassword()
    {
        if ($this->request->is('post')) {
            $user = $this->Users->findByEmail($this->request->data['email'])->first();
            
            if ($user) {
                $user->emailPasswordReset();
                
                $this->Flash->success(
                    __('An email has been sent with a link to reset your password.')
                );
                return $this->redirect(['action' => 'index']);
            }
            
            $this->Flash->error(__('No username is associated with this email address.'));
            return $this->redirect(['action' => 'index']);
        }
    }
    
}