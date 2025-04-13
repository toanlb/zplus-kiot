<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Tạo các bảng cần thiết cho module POS
 */
class m250414_000000_create_pos_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Tạo bảng pos_session để quản lý ca làm việc nếu chưa tồn tại
        if (!$this->tableExists('{{%pos_session}}')) {
            $this->createTable('{{%pos_session}}', [
                'id' => $this->primaryKey(),
                'user_id' => $this->integer()->notNull(),
                'start_time' => $this->integer()->notNull(),
                'end_time' => $this->integer()->null(),
                'start_amount' => $this->decimal(12, 2)->notNull()->defaultValue(0),
                'end_amount' => $this->decimal(12, 2)->null(),
                'expected_amount' => $this->decimal(12, 2)->null(),
                'difference' => $this->decimal(12, 2)->null(),
                'cash_sales' => $this->decimal(12, 2)->notNull()->defaultValue(0),
                'card_sales' => $this->decimal(12, 2)->notNull()->defaultValue(0),
                'bank_transfer_sales' => $this->decimal(12, 2)->notNull()->defaultValue(0),
                'other_sales' => $this->decimal(12, 2)->notNull()->defaultValue(0),
                'total_sales' => $this->decimal(12, 2)->notNull()->defaultValue(0),
                'current_amount' => $this->decimal(12, 2)->notNull()->defaultValue(0),
                'note' => $this->text(),
                'close_note' => $this->text(),
                'status' => $this->smallInteger()->notNull()->defaultValue(1),
                'created_at' => $this->integer()->notNull(),
            ]);
            
            // Thêm khóa ngoại cho bảng pos_session
            $this->addForeignKey(
                'fk-pos_session-user_id',
                '{{%pos_session}}',
                'user_id',
                '{{%user}}',
                'id',
                'CASCADE'
            );
            
            // Tạo index cho bảng pos_session
            $this->createIndex(
                'idx-pos_session-user_id',
                '{{%pos_session}}',
                'user_id'
            );
            
            $this->createIndex(
                'idx-pos_session-status',
                '{{%pos_session}}',
                'status'
            );
            
            echo "Đã tạo bảng pos_session\n";
        } else {
            echo "Bảng pos_session đã tồn tại, bỏ qua\n";
        }
        
        // Thêm trường pos_session_id vào bảng orders nếu bảng này tồn tại và chưa có trường này
        if ($this->tableExists('{{%orders}}')) {
            if (!$this->columnExists('{{%orders}}', 'pos_session_id')) {
                $this->addColumn('{{%orders}}', 'pos_session_id', $this->integer()->null()->after('customer_id'));
                
                // Thêm khóa ngoại cho trường pos_session_id
                $this->addForeignKey(
                    'fk-orders-pos_session_id',
                    '{{%orders}}',
                    'pos_session_id',
                    '{{%pos_session}}',
                    'id',
                    'SET NULL'
                );
                
                // Tạo index cho trường pos_session_id
                $this->createIndex(
                    'idx-orders-pos_session_id',
                    '{{%orders}}',
                    'pos_session_id'
                );
                
                echo "Đã thêm trường pos_session_id vào bảng orders\n";
            } else {
                echo "Trường pos_session_id đã tồn tại trong bảng orders, bỏ qua\n";
            }
        } else {
            echo "Bảng orders không tồn tại, bỏ qua thêm trường\n";
        }
        
        // Tạo bảng pos_offline_transactions nếu chưa tồn tại
        if (!$this->tableExists('{{%pos_offline_transactions}}')) {
            $this->createTable('{{%pos_offline_transactions}}', [
                'id' => $this->primaryKey(),
                'offline_id' => $this->string(50)->notNull(),
                'user_id' => $this->integer()->notNull(),
                'transaction_data' => $this->text()->notNull(),
                'status' => $this->smallInteger()->notNull()->defaultValue(1), // 1: Pending, 2: Processed, 3: Failed
                'error_message' => $this->text(),
                'created_at' => $this->integer()->notNull(),
                'processed_at' => $this->integer(),
            ]);
            
            // Tạo index cho bảng pos_offline_transactions
            $this->createIndex(
                'idx-pos_offline_transactions-offline_id',
                '{{%pos_offline_transactions}}',
                'offline_id'
            );
            
            $this->createIndex(
                'idx-pos_offline_transactions-user_id',
                '{{%pos_offline_transactions}}',
                'user_id'
            );
            
            $this->createIndex(
                'idx-pos_offline_transactions-status',
                '{{%pos_offline_transactions}}',
                'status'
            );
            
            // Thêm khóa ngoại cho bảng pos_offline_transactions
            $this->addForeignKey(
                'fk-pos_offline_transactions-user_id',
                '{{%pos_offline_transactions}}',
                'user_id',
                '{{%user}}',
                'id',
                'CASCADE'
            );
            
            echo "Đã tạo bảng pos_offline_transactions\n";
        } else {
            echo "Bảng pos_offline_transactions đã tồn tại, bỏ qua\n";
        }
        
        // Thêm cột PIN cho bảng user nếu chưa có
        if (!$this->columnExists('{{%user}}', 'pin')) {
            $this->addColumn('{{%user}}', 'pin', $this->string(255)->null()->after('password_hash'));
            echo "Đã thêm trường pin vào bảng user\n";
        } else {
            echo "Trường pin đã tồn tại trong bảng user, bỏ qua\n";
        }
        
        // Tạo bảng pos_user_preferences nếu chưa tồn tại
        if (!$this->tableExists('{{%pos_user_preferences}}')) {
            $this->createTable('{{%pos_user_preferences}}', [
                'id' => $this->primaryKey(),
                'user_id' => $this->integer()->notNull(),
                'preference_key' => $this->string(100)->notNull(),
                'preference_value' => $this->text(),
                'created_at' => $this->integer()->notNull(),
                'updated_at' => $this->integer()->notNull(),
            ]);
            
            // Tạo khóa duy nhất cho user_id và preference_key
            $this->createIndex(
                'idx-pos_user_preferences-user_id-preference_key',
                '{{%pos_user_preferences}}',
                ['user_id', 'preference_key'],
                true
            );
            
            // Thêm khóa ngoại cho bảng pos_user_preferences
            $this->addForeignKey(
                'fk-pos_user_preferences-user_id',
                '{{%pos_user_preferences}}',
                'user_id',
                '{{%user}}',
                'id',
                'CASCADE'
            );
            
            echo "Đã tạo bảng pos_user_preferences\n";
        } else {
            echo "Bảng pos_user_preferences đã tồn tại, bỏ qua\n";
        }
        
        // Thêm các trường cấu hình cho bảng user_profile nếu tồn tại và chưa có các trường này
        if ($this->tableExists('{{%user_profile}}')) {
            if (!$this->columnExists('{{%user_profile}}', 'default_pos_printer')) {
                $this->addColumn('{{%user_profile}}', 'default_pos_printer', $this->string(255)->null());
                echo "Đã thêm trường default_pos_printer vào bảng user_profile\n";
            } else {
                echo "Trường default_pos_printer đã tồn tại trong bảng user_profile, bỏ qua\n";
            }
            
            if (!$this->columnExists('{{%user_profile}}', 'pos_theme')) {
                $this->addColumn('{{%user_profile}}', 'pos_theme', $this->string(50)->null());
                echo "Đã thêm trường pos_theme vào bảng user_profile\n";
            } else {
                echo "Trường pos_theme đã tồn tại trong bảng user_profile, bỏ qua\n";
            }
            
            if (!$this->columnExists('{{%user_profile}}', 'pos_layout')) {
                $this->addColumn('{{%user_profile}}', 'pos_layout', $this->string(50)->null());
                echo "Đã thêm trường pos_layout vào bảng user_profile\n";
            } else {
                echo "Trường pos_layout đã tồn tại trong bảng user_profile, bỏ qua\n";
            }
        } else {
            echo "Bảng user_profile không tồn tại, bỏ qua thêm trường\n";
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Xóa khóa ngoại và index từ bảng orders nếu tồn tại
        if ($this->tableExists('{{%orders}}') && $this->columnExists('{{%orders}}', 'pos_session_id')) {
            // Kiểm tra xem khóa ngoại có tồn tại không trước khi xóa
            if ($this->foreignKeyExists('fk-orders-pos_session_id', '{{%orders}}')) {
                $this->dropForeignKey('fk-orders-pos_session_id', '{{%orders}}');
            }
            
            // Kiểm tra xem index có tồn tại không trước khi xóa
            if ($this->indexExists('idx-orders-pos_session_id', '{{%orders}}')) {
                $this->dropIndex('idx-orders-pos_session_id', '{{%orders}}');
            }
            
            // Xóa cột pos_session_id từ bảng orders
            $this->dropColumn('{{%orders}}', 'pos_session_id');
        }
        
        // Xóa bảng pos_session nếu tồn tại
        if ($this->tableExists('{{%pos_session}}')) {
            // Kiểm tra xem khóa ngoại có tồn tại không trước khi xóa
            if ($this->foreignKeyExists('fk-pos_session-user_id', '{{%pos_session}}')) {
                $this->dropForeignKey('fk-pos_session-user_id', '{{%pos_session}}');
            }
            
            // Kiểm tra xem index có tồn tại không trước khi xóa
            if ($this->indexExists('idx-pos_session-user_id', '{{%pos_session}}')) {
                $this->dropIndex('idx-pos_session-user_id', '{{%pos_session}}');
            }
            
            if ($this->indexExists('idx-pos_session-status', '{{%pos_session}}')) {
                $this->dropIndex('idx-pos_session-status', '{{%pos_session}}');
            }
            
            // Xóa bảng pos_session
            $this->dropTable('{{%pos_session}}');
        }
        
        // Xóa bảng pos_offline_transactions nếu tồn tại
        if ($this->tableExists('{{%pos_offline_transactions}}')) {
            // Kiểm tra xem khóa ngoại có tồn tại không trước khi xóa
            if ($this->foreignKeyExists('fk-pos_offline_transactions-user_id', '{{%pos_offline_transactions}}')) {
                $this->dropForeignKey('fk-pos_offline_transactions-user_id', '{{%pos_offline_transactions}}');
            }
            
            // Kiểm tra xem index có tồn tại không trước khi xóa
            if ($this->indexExists('idx-pos_offline_transactions-offline_id', '{{%pos_offline_transactions}}')) {
                $this->dropIndex('idx-pos_offline_transactions-offline_id', '{{%pos_offline_transactions}}');
            }
            
            if ($this->indexExists('idx-pos_offline_transactions-user_id', '{{%pos_offline_transactions}}')) {
                $this->dropIndex('idx-pos_offline_transactions-user_id', '{{%pos_offline_transactions}}');
            }
            
            if ($this->indexExists('idx-pos_offline_transactions-status', '{{%pos_offline_transactions}}')) {
                $this->dropIndex('idx-pos_offline_transactions-status', '{{%pos_offline_transactions}}');
            }
            
            // Xóa bảng pos_offline_transactions
            $this->dropTable('{{%pos_offline_transactions}}');
        }
        
        // Xóa cột PIN từ bảng user nếu tồn tại
        if ($this->columnExists('{{%user}}', 'pin')) {
            $this->dropColumn('{{%user}}', 'pin');
        }
        
        // Xóa bảng pos_user_preferences nếu tồn tại
        if ($this->tableExists('{{%pos_user_preferences}}')) {
            // Kiểm tra xem khóa ngoại có tồn tại không trước khi xóa
            if ($this->foreignKeyExists('fk-pos_user_preferences-user_id', '{{%pos_user_preferences}}')) {
                $this->dropForeignKey('fk-pos_user_preferences-user_id', '{{%pos_user_preferences}}');
            }
            
            // Kiểm tra xem index có tồn tại không trước khi xóa
            if ($this->indexExists('idx-pos_user_preferences-user_id-preference_key', '{{%pos_user_preferences}}')) {
                $this->dropIndex('idx-pos_user_preferences-user_id-preference_key', '{{%pos_user_preferences}}');
            }
            
            // Xóa bảng pos_user_preferences
            $this->dropTable('{{%pos_user_preferences}}');
        }
        
        // Xóa các trường cấu hình từ bảng user_profile nếu tồn tại
        if ($this->tableExists('{{%user_profile}}')) {
            if ($this->columnExists('{{%user_profile}}', 'default_pos_printer')) {
                $this->dropColumn('{{%user_profile}}', 'default_pos_printer');
            }
            
            if ($this->columnExists('{{%user_profile}}', 'pos_theme')) {
                $this->dropColumn('{{%user_profile}}', 'pos_theme');
            }
            
            if ($this->columnExists('{{%user_profile}}', 'pos_layout')) {
                $this->dropColumn('{{%user_profile}}', 'pos_layout');
            }
        }
    }
    
    /**
     * Kiểm tra xem bảng có tồn tại hay không
     * @param string $tableName Tên bảng cần kiểm tra
     * @return bool true nếu bảng tồn tại, false nếu không
     */
    protected function tableExists($tableName)
    {
        try {
            $tableSchema = Yii::$app->db->schema->getTableSchema($tableName);
            return $tableSchema !== null;
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Kiểm tra xem cột có tồn tại trong bảng hay không
     * @param string $tableName Tên bảng
     * @param string $columnName Tên cột
     * @return bool true nếu cột tồn tại, false nếu không
     */
    protected function columnExists($tableName, $columnName)
    {
        try {
            $tableSchema = Yii::$app->db->schema->getTableSchema($tableName);
            return $tableSchema !== null && isset($tableSchema->columns[$columnName]);
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Kiểm tra xem khóa ngoại có tồn tại trên bảng hay không
     * @param string $name Tên khóa ngoại
     * @param string $tableName Tên bảng
     * @return bool true nếu khóa ngoại tồn tại, false nếu không
     */
    protected function foreignKeyExists($name, $tableName)
    {
        $db = Yii::$app->db;
        $tableSchema = $db->schema->getTableSchema($tableName);
        
        if ($tableSchema === null) {
            return false;
        }
        
        foreach ($tableSchema->foreignKeys as $keyName => $foreignKey) {
            if ($keyName === $name) {
                return true;
            }
        }
        
        return (bool) $db->createCommand("SHOW CREATE TABLE " . $db->quoteTableName($tableName))
            ->queryOne()[1];
    }
    
    /**
     * Kiểm tra xem index có tồn tại trên bảng hay không
     * @param string $name Tên index
     * @param string $tableName Tên bảng
     * @return bool true nếu index tồn tại, false nếu không
     */
    protected function indexExists($name, $tableName)
    {
        return (bool) Yii::$app->db->createCommand("SHOW INDEX FROM " . Yii::$app->db->quoteTableName($tableName) . " WHERE key_name = '{$name}'")
            ->queryOne();
    }
}