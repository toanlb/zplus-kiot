<?php

use yii\db\Migration;

/**
 * Class m240414_120000_create_user_login_history_table
 */
class m240414_120000_create_user_login_history_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_login_history}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'login_time' => $this->integer()->notNull(),
            'ip_address' => $this->string(50),
            'user_agent' => $this->text(),
            'status' => $this->integer(),
        ]);

        // Tạo index cho user_id để tối ưu tìm kiếm
        $this->createIndex(
            'idx-user_login_history-user_id',
            '{{%user_login_history}}',
            'user_id'
        );

        // Tạo khóa ngoại liên kết với bảng user
        $this->addForeignKey(
            'fk-user_login_history-user_id',
            '{{%user_login_history}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
        
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Xóa khóa ngoại
        $this->dropForeignKey(
            'fk-user_login_history-user_id',
            '{{%user_login_history}}'
        );

        // Xóa index
        $this->dropIndex(
            'idx-user_login_history-user_id',
            '{{%user_login_history}}'
        );

        // Xóa bảng
        $this->dropTable('{{%user_login_history}}');
        
        return true;
    }
}