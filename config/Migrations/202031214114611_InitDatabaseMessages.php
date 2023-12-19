<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class InitDatabaseMessages extends AbstractMigration
{
    /**
     * Up Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-up-method
     * @return void
     */
    public function up()
    {
        $this->table('messages')
            ->addColumn('group_id', 'string', [
                'default' => null,
                'limit' => 34,
                'null' => true,
            ])
            ->addColumn('name', 'string', [
                'limit' => 45,
                'null' => false,
            ])
            ->addColumn('body', 'text', [
                'default' => null,
                'limit' => 4294967295,
                'null' => true,
            ])
            ->addColumn('message_read', 'boolean', [
                'default' => 0,
                'null' => false,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('created_by', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => true,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('modified_by', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => true,
            ])
            ->addIndex(
                [
                    'created_by',
                    'created',
                    'group_id'
                ]
            )
            ->addIndex(
                [
                    'created',
                    'group_id'
                ]
            )
            ->addIndex(
                [
                    'created_by',
                    'created',
                    'name',
                    'group_id',
                ]
            )
            ->addIndex(
                [
                    'name',
                    'group_id',
                ]
            )
            ->addIndex(
                [
                    'name'
                ],
                [
                    'type' => 'fulltext'
                ]
            )
            ->create();
    }

    /**
     * Down Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-down-method
     * @return void
     */
    public function down()
    {
        $this->table('messages')->drop()->save();
    }
}
