<?php
use Migrations\AbstractMigration;

class CreateAdmins extends AbstractMigration
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
        $table = $this->table('admins');
        $table->addColumn('user_id', 'integer')
              ->addColumn('created', 'datetime')
              ->addColumn('modified', 'datetime')
              ->addIndex(['user_id'])
              ->create();
    }
}
