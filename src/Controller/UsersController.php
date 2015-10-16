<?php

namespace Propeller\Users\Controller;

use Propeller\Users\Controller\AppController;
use Cake\Core\Configure;

class UsersController extends AppController
{
    public function beforeFilter(\Cake\Event\Event $event)
    {
        parent::beforeFilter($event);
        
        $whiteList = Configure::read('Users.whiteList');
        
        if (Configure::read('Users.openRegistration')) {
            $whiteList[] = 'register';
        }
        
        $this->Auth->allow($whiteList);
    }
    
    public function index()
    {
        $users = $this->Users->find('all');
        $this->set('users', $users);
    }
    
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
        
        $this->set([
            'user' => $user,
            'useEmailAsUsername' => Configure::read('Users.useEmailAsUsername')
        ]);
    }
    
    public function view($id)
    {
        $user = $this->Users->findById($id)->first();
        
        $this->set([
            'user' => $user,
            'useEmailAsUsername' => Configure::read('Users.useEmailAsUsername')
        ]);
    }
    
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
            'useEmailAsUsername' => Configure::read('Users.useEmailAsUsername')
        ]);
    }
    
    public function login()
    {
        if ($this->request->is(['post'])) {
            $user = $this->Auth->identify();
            
            if ($user) {
                $user['admin'] = $this->Users->isAdmin($user['id']);
                
                $this->Auth->setUser($user);
                return $this->redirect($this->Auth->redirectUrl());
            }
            
            $this->Flash->error(__('Invalid username or password.'));
        }
    }
    
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
        } else {
            $personalKey = $this->request->query['key'];
            $user = $this->Users->findByPersonalKey($personalKey);
            
            if ($user->count() === 0) {
                $this->Flash->error(__('This user does not exist.'));
                return $this->redirect(['action' => 'index']);
            }
            
            $action = (isset($this->request->query['action']))
                ? $this->request->query['action'] : 'index';
            
            return $this->redirect(['action' => $action]);
        }
    }
    
    /**
     * Resets the password of a user
     */
    public function reset()
    {
        
    }
}