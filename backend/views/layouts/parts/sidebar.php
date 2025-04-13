<?php
use hail812\adminlte\widgets\Menu;
?>

<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?= Yii::$app->homeUrl ?>" class="brand-link">
        <img src="<?= $assetDir ?>/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Quản lý Bán hàng</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="<?= $assetDir ?>/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block"><?= Yii::$app->user->identity->username ?? 'Guest' ?></a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <?= Menu::widget([
                'items' => [
                    ['label' => 'Dashboard', 'icon' => 'tachometer-alt', 'url' => ['/site/index']],
                    ['label' => 'Sản phẩm', 
                     'icon' => 'box', 
                     'url' => '#',
                     'items' => [
                        ['label' => 'Danh sách sản phẩm', 'url' => ['/product/index'], 'iconStyle' => 'far', 'icon' => 'circle'],
                        ['label' => 'Danh mục sản phẩm', 'url' => ['/product-category/index'], 'iconStyle' => 'far', 'icon' => 'circle'],
                        ['label' => 'Đơn vị tính', 'url' => ['/product-unit/index'], 'iconStyle' => 'far', 'icon' => 'circle'],
                     ],
                    ],
                    ['label' => 'Khách hàng', 'icon' => 'users', 'url' => ['/customer/index']],
                    ['label' => 'Bán hàng (POS)', 'icon' => 'cash-register', 'url' => ['/pos/index']],
                    ['label' => 'Đơn hàng', 'icon' => 'shopping-cart', 'url' => ['/order/index']],
                    ['label' => 'Bảo hành', 'icon' => 'tools', 'url' => ['/warranty/index']],
                    ['label' => 'Nhà cung cấp', 'icon' => 'truck', 'url' => ['/supplier/index']],
                    ['label' => 'Báo cáo', 
                     'icon' => 'chart-bar', 
                     'url' => '#',
                     'items' => [
                        ['label' => 'Doanh thu', 'url' => ['/report/revenue'], 'iconStyle' => 'far', 'icon' => 'chart-line'],
                        ['label' => 'Tồn kho', 'url' => ['/report/inventory'], 'iconStyle' => 'far', 'icon' => 'warehouse'],
                        ['label' => 'Khách hàng', 'url' => ['/report/customers'], 'iconStyle' => 'far', 'icon' => 'user-friends'],
                     ],
                    ],
                    ['label' => 'Người dùng', 'icon' => 'user', 'url' => ['/user/index']],
                    ['label' => 'Phân quyền', 'icon' => 'user-shield', 'url' => ['/auth-manager/index']],
                ]
            ]) ?>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>