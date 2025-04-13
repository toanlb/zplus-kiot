<?php
/* @var $this yii\web\View */
$this->title = 'Dashboard';
?>
<div class="site-index">
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3><?= $totalProducts ?? 0 ?></h3>
                    <p>Sản phẩm</p>
                </div>
                <div class="icon">
                    <i class="fas fa-box"></i>
                </div>
                <a href="<?= Yii::$app->urlManager->createUrl(['product/index']) ?>" class="small-box-footer">
                    Xem chi tiết <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3><?= $totalCustomers ?? 0 ?></h3>
                    <p>Khách hàng</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
                <a href="<?= Yii::$app->urlManager->createUrl(['customer/index']) ?>" class="small-box-footer">
                    Xem chi tiết <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3><?= $totalOrders ?? 0 ?></h3>
                    <p>Đơn hàng</p>
                </div>
                <div class="icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <a href="<?= Yii::$app->urlManager->createUrl(['order/index']) ?>" class="small-box-footer">
                    Xem chi tiết <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3><?= $totalSuppliers ?? 0 ?></h3>
                    <p>Nhà cung cấp</p>
                </div>
                <div class="icon">
                    <i class="fas fa-truck"></i>
                </div>
                <a href="<?= Yii::$app->urlManager->createUrl(['supplier/index']) ?>" class="small-box-footer">
                    Xem chi tiết <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Thống kê hệ thống</h3>
                </div>
                <div class="card-body">
                    <p>Chào mừng đến với hệ thống quản lý bán hàng.</p>
                    <p>Từ dashboard này, bạn có thể truy cập vào các chức năng quản lý sản phẩm, khách hàng, đơn hàng, bảo hành và nhiều tính năng khác.</p>
                </div>
            </div>
        </div>
    </div>
</div>