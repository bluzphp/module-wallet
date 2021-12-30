<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class WalletsPermissions extends AbstractMigration
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
                'privilege' => 'View'
            ],
            [
                'roleId' => 3,
                'module' => 'wallet',
                'privilege' => 'View'
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
