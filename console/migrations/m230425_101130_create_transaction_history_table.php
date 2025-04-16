<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%transaction_history}}`.
 */
class m230425_101130_create_transaction_history_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%transaction_history}}', [
            'id' => $this->primaryKey(),
            'transaction_code' => $this->string(50)->notNull()->unique(),
            'order_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'pos_session_id' => $this->integer(),
            'customer_id' => $this->integer(),
            'total_amount' => $this->decimal(15, 2)->notNull(),
            'discount_amount' => $this->decimal(15, 2)->defaultValue(0),
            'final_amount' => $this->decimal(15, 2)->notNull(),
            'paid_amount' => $this->decimal(15, 2)->defaultValue(0),
            'cash_amount' => $this->decimal(15, 2)->defaultValue(0),
            'card_amount' => $this->decimal(15, 2)->defaultValue(0),
            'ewallet_amount' => $this->decimal(15, 2)->defaultValue(0),
            'bank_transfer_amount' => $this->decimal(15, 2)->defaultValue(0),
            'payment_status' => $this->string(20)->notNull()->defaultValue('pending'),
            'transaction_type' => $this->string(20)->notNull()->defaultValue('sale'),
            'notes' => $this->text(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        // Tạo index
        $this->createIndex(
            'idx-transaction_history-order_id',
            '{{%transaction_history}}',
            'order_id'
        );
        
        $this->createIndex(
            'idx-transaction_history-user_id',
            '{{%transaction_history}}',
            'user_id'
        );
        
        $this->createIndex(
            'idx-transaction_history-pos_session_id',
            '{{%transaction_history}}',
            'pos_session_id'
        );
        
        $this->createIndex(
            'idx-transaction_history-customer_id',
            '{{%transaction_history}}',
            'customer_id'
        );
        
        $this->createIndex(
            'idx-transaction_history-created_at',
            '{{%transaction_history}}',
            'created_at'
        );
        
        $this->createIndex(
            'idx-transaction_history-transaction_type',
            '{{%transaction_history}}',
            'transaction_type'
        );
        
        $this->createIndex(
            'idx-transaction_history-payment_status',
            '{{%transaction_history}}',
            'payment_status'
        );

        // Tạo foreign keys
        $this->addForeignKey(
            'fk-transaction_history-order_id',
            '{{%transaction_history}}',
            'order_id',
            '{{%orders}}',
            'id',
            'CASCADE'
        );
        
        $this->addForeignKey(
            'fk-transaction_history-user_id',
            '{{%transaction_history}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );
        
        $this->addForeignKey(
            'fk-transaction_history-pos_session_id',
            '{{%transaction_history}}',
            'pos_session_id',
            '{{%pos_session}}',
            'id',
            'SET NULL'
        );
        
        // Sửa tên bảng từ 'customer' thành 'customers'
        $this->addForeignKey(
            'fk-transaction_history-customer_id',
            '{{%transaction_history}}',
            'customer_id',
            '{{%customers}}',  // Đã sửa từ 'customer' thành 'customers'
            'id',
            'SET NULL'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Drop foreign keys first
        $this->dropForeignKey('fk-transaction_history-order_id', '{{%transaction_history}}');
        $this->dropForeignKey('fk-transaction_history-user_id', '{{%transaction_history}}');
        $this->dropForeignKey('fk-transaction_history-pos_session_id', '{{%transaction_history}}');
        $this->dropForeignKey('fk-transaction_history-customer_id', '{{%transaction_history}}');
        
        // Drop table
        $this->dropTable('{{%transaction_history}}');
    }
}