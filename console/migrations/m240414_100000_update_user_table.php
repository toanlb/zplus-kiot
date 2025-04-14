<?php

use yii\db\Migration;

/**
 * Class m240414_100000_update_user_table
 */
class m240414_100000_update_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Thêm các cột mới vào bảng user
        $this->addColumn('{{%user}}', 'full_name', $this->string(255)->null()->after('username'));
        $this->addColumn('{{%user}}', 'phone', $this->string(20)->null()->after('email'));
        $this->addColumn('{{%user}}', 'position', $this->string(100)->null()->after('phone'));
        $this->addColumn('{{%user}}', 'avatar', $this->string(255)->null()->after('position'));
        $this->addColumn('{{%user}}', 'last_login_at', $this->integer()->null()->after('updated_at'));

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Xóa các cột khi rollback
        $this->dropColumn('{{%user}}', 'full_name');
        $this->dropColumn('{{%user}}', 'phone');
        $this->dropColumn('{{%user}}', 'position');
        $this->dropColumn('{{%user}}', 'avatar');
        $this->dropColumn('{{%user}}', 'last_login_at');

        return true;
    }
}