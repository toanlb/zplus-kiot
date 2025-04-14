<?php

use \yii\db\Migration;

class m190124_110200_add_verification_token_column_to_user_table extends Migration
{
    public function up()
    {
        // Check if the table exists before modifying it
        $tableExists = $this->db->schema->getTableSchema('{{%user}}', true) !== null;
        
        if (!$tableExists) {
            echo "User table does not exist, cannot add verification_token column.\n";
            return false;
        }
        
        // Check if the column already exists
        $columnExists = $this->db->schema->getTableSchema('{{%user}}', true)->getColumn('verification_token') !== null;
        
        if (!$columnExists) {
            $this->addColumn('{{%user}}', 'verification_token', $this->string()->defaultValue(null));
            echo "Added verification_token column to user table.\n";
        } else {
            echo "verification_token column already exists in user table, skipping.\n";
        }
    }

    public function down()
    {
        // Check if the table and column exist before dropping
        $tableExists = $this->db->schema->getTableSchema('{{%user}}', true) !== null;
        
        if ($tableExists) {
            $columnExists = $this->db->schema->getTableSchema('{{%user}}', true)->getColumn('verification_token') !== null;
            
            if ($columnExists) {
                $this->dropColumn('{{%user}}', 'verification_token');
            }
        }
    }
}