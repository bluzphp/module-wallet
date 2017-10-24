<?php


use Phinx\Migration\AbstractMigration;

class WalletsPermissions extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $data = [
            [
                'roleId' => 2,
                'module' => 'wallet',
                'privilege' => 'Management'
            ],
            [
                'roleId' => 2,
                'module' => 'wallet',
                'privilege' => 'ViewTransactions'
            ],
            [
                'roleId' => 3,
                'module' => 'wallet',
                'privilege' => 'ViewTransactions'
            ],
        ];

        $privileges = $this->table('acl_privileges');
        $privileges->insert($data)
            ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->execute('DELETE FROM acl_privileges WHERE module = "wallet"');
    }
}
