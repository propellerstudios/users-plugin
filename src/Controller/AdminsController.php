<?php

namespace Propeller\Users\Controller;

use Propeller\Users\Controller\AppController;
use Cake\ORM\TableRegistry;

class AdminsController extends AppController
{
    public function beforeFilter(\Cake\Event\Event $event)
    {
        parent::beforeFilter($event);
        
        if (!$this->Auth->user('admin')) {
            return $this->redirect([
                'controller' => 'Users',
                'action' => 'index'
            ]);
        }
    }
    
    public function index()
    {
        $admins = $this->Admins->find()->contain([
            'Users' => function ($q) {
                return $q->select(['username']);
            }
        ]);
        
        $this->set('admins', $admins);
    }
    
    public function delete($id)
    {
        if ($this->Auth->user('id') === $id) {
            $this->Flash->error(__('You cannot delete yourself.'));
            return $this->redirect(['action' => 'index']);
        }
        
        $usersTable = TableRegistry::get('Users');
        $user = $usersTable->findById($id)->first();
        
        if ($usersTable->delete($user)) {
            $this->Flash->success(__('Successfully deleted user.'));
            return $this->redirect(['action' => 'index']);
        }
        
        $this->Flash->error(__('Error deleting the user.'));
    }
}