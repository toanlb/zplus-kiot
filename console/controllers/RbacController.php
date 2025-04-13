<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;

class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll();

        // Tạo các quyền
        $manageProducts = $auth->createPermission('manageProducts');
        $manageProducts->description = 'Quản lý sản phẩm';
        $auth->add($manageProducts);

        $manageCustomers = $auth->createPermission('manageCustomers');
        $manageCustomers->description = 'Quản lý khách hàng';
        $auth->add($manageCustomers);

        $manageOrders = $auth->createPermission('manageOrders');
        $manageOrders->description = 'Quản lý đơn hàng';
        $auth->add($manageOrders);

        $managePOS = $auth->createPermission('managePOS');
        $managePOS->description = 'Sử dụng POS';
        $auth->add($managePOS);

        $manageWarranty = $auth->createPermission('manageWarranty');
        $manageWarranty->description = 'Quản lý bảo hành';
        $auth->add($manageWarranty);

        $manageSuppliers = $auth->createPermission('manageSuppliers');
        $manageSuppliers->description = 'Quản lý nhà cung cấp';
        $auth->add($manageSuppliers);

        $viewReports = $auth->createPermission('viewReports');
        $viewReports->description = 'Xem báo cáo';
        $auth->add($viewReports);

        $manageUsers = $auth->createPermission('manageUsers');
        $manageUsers->description = 'Quản lý người dùng';
        $auth->add($manageUsers);

        $manageRoles = $auth->createPermission('manageRoles');
        $manageRoles->description = 'Quản lý vai trò và quyền';
        $auth->add($manageRoles);

        // Tạo các vai trò
        $technician = $auth->createRole('technician');
        $auth->add($technician);
        $auth->addChild($technician, $manageWarranty);

        $warehouse = $auth->createRole('warehouse');
        $auth->add($warehouse);
        $auth->addChild($warehouse, $manageProducts);

        $sales = $auth->createRole('sales');
        $auth->add($sales);
        $auth->addChild($sales, $managePOS);
        $auth->addChild($sales, $manageOrders);
        $auth->addChild($sales, $manageCustomers);

        $manager = $auth->createRole('manager');
        $auth->add($manager);
        $auth->addChild($manager, $manageProducts);
        $auth->addChild($manager, $manageCustomers);
        $auth->addChild($manager, $manageOrders);
        $auth->addChild($manager, $managePOS);
        $auth->addChild($manager, $manageSuppliers);
        $auth->addChild($manager, $viewReports);

        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($admin, $manager);
        $auth->addChild($admin, $sales);
        $auth->addChild($admin, $warehouse);
        $auth->addChild($admin, $technician);
        $auth->addChild($admin, $manageUsers);

        $superAdmin = $auth->createRole('superAdmin');
        $auth->add($superAdmin);
        $auth->addChild($superAdmin, $admin);
        $auth->addChild($superAdmin, $manageRoles);

        // Gán vai trò superAdmin cho user ID 1 (nếu có)
        $auth->assign($superAdmin, 1);

        echo "RBAC initialization completed.\n";
    }
}