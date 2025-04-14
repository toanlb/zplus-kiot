<?php

use yii\db\Migration;

/**
 * Class m250414_000001_create_admin_user
 * 
 * Migration để tạo tài khoản admin đầu tiên
 */
class m250414_000001_create_admin_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Kiểm tra xem bảng user có tồn tại không
        $tableExists = $this->db->schema->getTableSchema('{{%user}}', true) !== null;
        
        if (!$tableExists) {
            echo "Bảng user không tồn tại. Vui lòng chạy migration tạo bảng user trước.\n";
            return false;
        }
        
        // Kiểm tra xem đã có người dùng admin nào chưa
        $userExists = (new \yii\db\Query())
            ->from('{{%user}}')
            ->where(['username' => 'admin'])
            ->exists();
            
        if ($userExists) {
            echo "Người dùng admin đã tồn tại, bỏ qua việc tạo mới.\n";
            return true;
        }
        
        // Tạo người dùng admin
        $time = time();
        $this->insert('{{%user}}', [
            'username' => 'admin',
            'auth_key' => Yii::$app->security->generateRandomString(),
            'password_hash' => Yii::$app->security->generatePasswordHash('admin123'), // Mật khẩu mặc định: admin123
            'email' => 'admin@example.com',
            'status' => 10, // Người dùng đã kích hoạt
            'created_at' => $time,
            'updated_at' => $time,
        ]);
        
        $adminId = Yii::$app->db->getLastInsertID();
        
        // Gán vai trò admin nếu RBAC đã được thiết lập
        $authManager = Yii::$app->authManager;
        if ($authManager !== null) {
            try {
                $adminRole = $authManager->getRole('admin');
                if ($adminRole !== null) {
                    $authManager->assign($adminRole, $adminId);
                    echo "Đã gán vai trò admin cho người dùng admin.\n";
                } else {
                    echo "Vai trò admin không tồn tại, không thể gán vai trò.\n";
                }
            } catch (\Exception $e) {
                echo "Lỗi khi gán vai trò admin: " . $e->getMessage() . "\n";
                echo "Có thể RBAC chưa được thiết lập. Hãy chạy migration RBAC sau đó.\n";
            }
        } else {
            echo "AuthManager không khả dụng, không thể gán vai trò.\n";
        }
        
        echo "Đã tạo người dùng admin thành công với ID: $adminId\n";
        echo "Tên đăng nhập: admin\n";
        echo "Mật khẩu: admin123\n";
        echo "Hãy thay đổi mật khẩu này ngay sau khi đăng nhập lần đầu.\n";
        
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Kiểm tra xem bảng user có tồn tại không
        $tableExists = $this->db->schema->getTableSchema('{{%user}}', true) !== null;
        
        if (!$tableExists) {
            echo "Bảng user không tồn tại, không thể xóa admin.\n";
            return false;
        }
        
        // Kiểm tra xem người dùng admin có tồn tại không
        $user = (new \yii\db\Query())
            ->from('{{%user}}')
            ->where(['username' => 'admin'])
            ->one();
            
        if (!$user) {
            echo "Người dùng admin không tồn tại, không cần xóa.\n";
            return true;
        }
        
        // Xóa gán vai trò nếu RBAC đang hoạt động
        $authManager = Yii::$app->authManager;
        if ($authManager !== null) {
            try {
                $authManager->revokeAll($user['id']);
                echo "Đã xóa tất cả vai trò của người dùng admin.\n";
            } catch (\Exception $e) {
                echo "Lỗi khi xóa vai trò: " . $e->getMessage() . "\n";
            }
        }
        
        // Xóa người dùng admin
        $this->delete('{{%user}}', ['username' => 'admin']);
        echo "Đã xóa người dùng admin.\n";
        
        return true;
    }
}