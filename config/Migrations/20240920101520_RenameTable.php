<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class RenameTable extends AbstractMigration
{
    /**
     * Up Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-up-method
     * @return void
     */
    public function change()
    {
        $this->table('messages')->rename('cake_notifications_messages')->update();
        $this->table('notifications')->rename('cake_notifications_notifications')->update();
    }
}
