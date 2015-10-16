<?php
use Migrations\AbstractMigration;

class CreateUsers extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        $table = $this->table('users');
        $table->addColumn('username', 'string')
              ->addColumn('password', 'string')
              ->addColumn('email', 'string')
              ->addColumn('first_name', 'string')
              ->addColumn('last_name', 'string')
              ->addColumn('active', 'boolean', ['default' => true])
              ->addColumn('personal_key', 'string')
              ->addColumn('created', 'datetime')
              ->addColumn('modified', 'datetime')
              ->addIndex(['username', 'email'])
              ->create();
    }
}
