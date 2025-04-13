<?php
use yii\db\Migration;

class m250413_023113_init_rbac extends Migration
{
    public function safeUp()
    {
        $auth = Yii::$app->authManager;

        // Tạo các quyền (permissions)
        
        // Sản phẩm
        $viewProduct = $auth->createPermission('viewProduct');
        $viewProduct->description = 'Xem sản phẩm';
        $auth->add($viewProduct);

        $createProduct = $auth->createPermission('createProduct');
        $createProduct->description = 'Thêm sản phẩm';
        $auth->add($createProduct);

        $updateProduct = $auth->createPermission('updateProduct');
        $updateProduct->description = 'Cập nhật sản phẩm';
        $auth->add($updateProduct);

        $deleteProduct = $auth->createPermission('deleteProduct');
        $deleteProduct->description = 'Xóa sản phẩm';
        $auth->add($deleteProduct);

        // Danh mục sản phẩm
        $viewCategory = $auth->createPermission('viewCategory');
        $viewCategory->description = 'Xem danh mục sản phẩm';
        $auth->add($viewCategory);

        $createCategory = $auth->createPermission('createCategory');
        $createCategory->description = 'Thêm danh mục sản phẩm';
        $auth->add($createCategory);

        $updateCategory = $auth->createPermission('updateCategory');
        $updateCategory->description = 'Cập nhật danh mục sản phẩm';
        $auth->add($updateCategory);

        $deleteCategory = $auth->createPermission('deleteCategory');
        $deleteCategory->description = 'Xóa danh mục sản phẩm';
        $auth->add($deleteCategory);

        // Đơn vị tính
        $viewUnit = $auth->createPermission('viewUnit');
        $viewUnit->description = 'Xem đơn vị tính';
        $auth->add($viewUnit);

        $createUnit = $auth->createPermission('createUnit');
        $createUnit->description = 'Thêm đơn vị tính';
        $auth->add($createUnit);

        $updateUnit = $auth->createPermission('updateUnit');
        $updateUnit->description = 'Cập nhật đơn vị tính';
        $auth->add($updateUnit);

        $deleteUnit = $auth->createPermission('deleteUnit');
        $deleteUnit->description = 'Xóa đơn vị tính';
        $auth->add($deleteUnit);

        // Khách hàng
        $viewCustomer = $auth->createPermission('viewCustomer');
        $viewCustomer->description = 'Xem khách hàng';
        $auth->add($viewCustomer);

        $createCustomer = $auth->createPermission('createCustomer');
        $createCustomer->description = 'Thêm khách hàng';
        $auth->add($createCustomer);

        $updateCustomer = $auth->createPermission('updateCustomer');
        $updateCustomer->description = 'Cập nhật khách hàng';
        $auth->add($updateCustomer);

        $deleteCustomer = $auth->createPermission('deleteCustomer');
        $deleteCustomer->description = 'Xóa khách hàng';
        $auth->add($deleteCustomer);

        // Đơn hàng
        $viewOrder = $auth->createPermission('viewOrder');
        $viewOrder->description = 'Xem đơn hàng';
        $auth->add($viewOrder);

        $createOrder = $auth->createPermission('createOrder');
        $createOrder->description = 'Tạo đơn hàng';
        $auth->add($createOrder);

        $updateOrder = $auth->createPermission('updateOrder');
        $updateOrder->description = 'Cập nhật đơn hàng';
        $auth->add($updateOrder);

        $deleteOrder = $auth->createPermission('deleteOrder');
        $deleteOrder->description = 'Xóa đơn hàng';
        $auth->add($deleteOrder);

        // Bán hàng (POS)
        $accessPos = $auth->createPermission('accessPos');
        $accessPos->description = 'Truy cập màn hình bán hàng';
        $auth->add($accessPos);

        // Bảo hành
        $viewWarranty = $auth->createPermission('viewWarranty');
        $viewWarranty->description = 'Xem bảo hành';
        $auth->add($viewWarranty);

        $createWarranty = $auth->createPermission('createWarranty');
        $createWarranty->description = 'Thêm bảo hành';
        $auth->add($createWarranty);

        $updateWarranty = $auth->createPermission('updateWarranty');
        $updateWarranty->description = 'Cập nhật bảo hành';
        $auth->add($updateWarranty);

        $deleteWarranty = $auth->createPermission('deleteWarranty');
        $deleteWarranty->description = 'Xóa bảo hành';
        $auth->add($deleteWarranty);

        // Nhà cung cấp
        $viewSupplier = $auth->createPermission('viewSupplier');
        $viewSupplier->description = 'Xem nhà cung cấp';
        $auth->add($viewSupplier);

        $createSupplier = $auth->createPermission('createSupplier');
        $createSupplier->description = 'Thêm nhà cung cấp';
        $auth->add($createSupplier);

        $updateSupplier = $auth->createPermission('updateSupplier');
        $updateSupplier->description = 'Cập nhật nhà cung cấp';
        $auth->add($updateSupplier);

        $deleteSupplier = $auth->createPermission('deleteSupplier');
        $deleteSupplier->description = 'Xóa nhà cung cấp';
        $auth->add($deleteSupplier);

        // Báo cáo
        $viewReport = $auth->createPermission('viewReport');
        $viewReport->description = 'Xem báo cáo';
        $auth->add($viewReport);

        // Người dùng
        $viewUser = $auth->createPermission('viewUser');
        $viewUser->description = 'Xem người dùng';
        $auth->add($viewUser);

        $createUser = $auth->createPermission('createUser');
        $createUser->description = 'Thêm người dùng';
        $auth->add($createUser);

        $updateUser = $auth->createPermission('updateUser');
        $updateUser->description = 'Cập nhật người dùng';
        $auth->add($updateUser);

        $deleteUser = $auth->createPermission('deleteUser');
        $deleteUser->description = 'Xóa người dùng';
        $auth->add($deleteUser);

        // Phân quyền
        $manageRbac = $auth->createPermission('manageRbac');
        $manageRbac->description = 'Quản lý phân quyền';
        $auth->add($manageRbac);

        // Tạo các vai trò (roles)
        
        // Vai trò: Thu ngân (Cashier)
        $cashier = $auth->createRole('cashier');
        $cashier->description = 'Thu ngân';
        $auth->add($cashier);
        $auth->addChild($cashier, $viewProduct);
        $auth->addChild($cashier, $viewCategory);
        $auth->addChild($cashier, $viewUnit);
        $auth->addChild($cashier, $viewCustomer);
        $auth->addChild($cashier, $createCustomer);
        $auth->addChild($cashier, $updateCustomer);
        $auth->addChild($cashier, $viewOrder);
        $auth->addChild($cashier, $createOrder);
        $auth->addChild($cashier, $accessPos);
        $auth->addChild($cashier, $viewWarranty);

        // Vai trò: Nhân viên (Staff)
        $staff = $auth->createRole('staff');
        $staff->description = 'Nhân viên';
        $auth->add($staff);
        $auth->addChild($staff, $cashier); // Kế thừa tất cả quyền từ Cashier
        $auth->addChild($staff, $updateOrder);
        $auth->addChild($staff, $createWarranty);
        $auth->addChild($staff, $updateWarranty);
        $auth->addChild($staff, $viewSupplier);

        // Vai trò: Quản lý (Manager)
        $manager = $auth->createRole('manager');
        $manager->description = 'Quản lý';
        $auth->add($manager);
        $auth->addChild($manager, $staff); // Kế thừa tất cả quyền từ Staff
        $auth->addChild($manager, $createProduct);
        $auth->addChild($manager, $updateProduct);
        $auth->addChild($manager, $createCategory);
        $auth->addChild($manager, $updateCategory);
        $auth->addChild($manager, $createUnit);
        $auth->addChild($manager, $updateUnit);
        $auth->addChild($manager, $deleteCustomer);
        $auth->addChild($manager, $deleteOrder);
        $auth->addChild($manager, $deleteWarranty);
        $auth->addChild($manager, $createSupplier);
        $auth->addChild($manager, $updateSupplier);
        $auth->addChild($manager, $deleteSupplier);
        $auth->addChild($manager, $viewReport);
        $auth->addChild($manager, $viewUser);

        // Vai trò: Quản trị viên (Admin)
        $admin = $auth->createRole('admin');
        $admin->description = 'Quản trị viên';
        $auth->add($admin);
        $auth->addChild($admin, $manager); // Kế thừa tất cả quyền từ Manager
        $auth->addChild($admin, $deleteProduct);
        $auth->addChild($admin, $deleteCategory);
        $auth->addChild($admin, $deleteUnit);
        $auth->addChild($admin, $createUser);
        $auth->addChild($admin, $updateUser);
        $auth->addChild($admin, $deleteUser);
        $auth->addChild($admin, $manageRbac);

        // Tạo quy tắc truy cập đặc biệt
        $rule = new \common\rbac\AuthorRule;
        $auth->add($rule);

        $updateOwnPost = $auth->createPermission('updateOwnOrder');
        $updateOwnPost->description = 'Cập nhật đơn hàng của chính mình';
        $updateOwnPost->ruleName = $rule->name;
        $auth->add($updateOwnPost);
        $auth->addChild($updateOwnPost, $updateOrder);
        $auth->addChild($cashier, $updateOwnPost);

        // Gán vai trò cho người dùng mặc định (ID 1 là admin)
        $auth->assign($admin, 1);
    }

    public function safeDown()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll();

        return true;
    }
}