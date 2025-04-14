<?php

/* @var $this yii\web\View */
/* @var $hasActiveSession boolean */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Quản lý POS';
$csrfToken = Yii::$app->request->csrfToken;
?>
<div class="site-index">
    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header bg-primary">
                    <h3 class="card-title">
                        <i class="fas fa-cash-register mr-2"></i> Quản lý ca làm việc
                    </h3>
                </div>
                <div class="card-body">
                    <?php if ($hasActiveSession): ?>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i> 
                            Bạn đang có ca làm việc đang mở. Bạn có thể tiếp tục bán hàng.
                        </div>
                        <a href="<?= Url::to(['/pos/index']) ?>" class="btn btn-primary btn-lg btn-block">
                            <i class="fas fa-cash-register mr-2"></i> Tiếp tục bán hàng
                        </a>
                    <?php else: ?>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i> 
                            Bạn chưa mở ca làm việc. Vui lòng mở ca để bắt đầu bán hàng.
                        </div>
                        <button type="button" class="btn btn-success btn-lg btn-block" id="btnOpenSession">
                            <i class="fas fa-door-open mr-2"></i> Mở ca làm việc
                        </button>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="card mt-4">
                <div class="card-header bg-info">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle mr-2"></i> Thông tin người dùng
                    </h3>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <img src="<?= Url::to(['/images/user-avatar.png']) ?>" class="img-circle elevation-2" alt="User Image" style="width: 100px; height: 100px;">
                        <h4 class="mt-2"><?= Yii::$app->user->identity->username ?></h4>
                        <p class="text-muted">Nhân viên bán hàng</p>
                    </div>
                    
                    <div class="list-group">
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="fas fa-user mr-2"></i> Thông tin cá nhân
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="fas fa-key mr-2"></i> Đổi mật khẩu
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-success">
                    <h3 class="card-title">
                        <i class="fas fa-tachometer-alt mr-2"></i> Tổng quan hệ thống
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>0</h3>
                                    <p>Đơn hàng hôm nay</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                                <a href="#" class="small-box-footer">
                                    Xem chi tiết <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                        
                        <div class="col-lg-4">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>0<sup style="font-size: 20px">đ</sup></h3>
                                    <p>Doanh thu hôm nay</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-money-bill"></i>
                                </div>
                                <a href="#" class="small-box-footer">
                                    Xem chi tiết <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                        
                        <div class="col-lg-4">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>0</h3>
                                    <p>Sản phẩm đã bán</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-box"></i>
                                </div>
                                <a href="#" class="small-box-footer">
                                    Xem chi tiết <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-header border-0">
                                    <h3 class="card-title">Sản phẩm bán chạy</h3>
                                </div>
                                <div class="card-body table-responsive p-0">
                                    <table class="table table-striped table-valign-middle">
                                        <thead>
                                            <tr>
                                                <th>Sản phẩm</th>
                                                <th>Giá</th>
                                                <th>Đã bán</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <img src="<?= Url::to(['/images/product-default.png']) ?>" alt="Product" class="img-circle img-size-32 mr-2">
                                                    Sản phẩm mẫu 1
                                                </td>
                                                <td>100,000đ</td>
                                                <td>0</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <img src="<?= Url::to(['/images/product-default.png']) ?>" alt="Product" class="img-circle img-size-32 mr-2">
                                                    Sản phẩm mẫu 2
                                                </td>
                                                <td>200,000đ</td>
                                                <td>0</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <img src="<?= Url::to(['/images/product-default.png']) ?>" alt="Product" class="img-circle img-size-32 mr-2">
                                                    Sản phẩm mẫu 3
                                                </td>
                                                <td>150,000đ</td>
                                                <td>0</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-header border-0">
                                    <h3 class="card-title">Đơn hàng gần đây</h3>
                                </div>
                                <div class="card-body table-responsive p-0">
                                    <table class="table table-striped table-valign-middle">
                                        <thead>
                                            <tr>
                                                <th>Mã đơn</th>
                                                <th>Khách hàng</th>
                                                <th>Tổng tiền</th>
                                                <th>Trạng thái</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>ORD000001</td>
                                                <td>Khách lẻ</td>
                                                <td>100,000đ</td>
                                                <td><span class="badge badge-success">Hoàn thành</span></td>
                                            </tr>
                                            <tr>
                                                <td>ORD000002</td>
                                                <td>Khách lẻ</td>
                                                <td>200,000đ</td>
                                                <td><span class="badge badge-success">Hoàn thành</span></td>
                                            </tr>
                                            <tr>
                                                <td>ORD000003</td>
                                                <td>Khách lẻ</td>
                                                <td>150,000đ</td>
                                                <td><span class="badge badge-success">Hoàn thành</span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card mt-4">
                <div class="card-header bg-primary">
                    <h3 class="card-title">
                        <i class="fas fa-link mr-2"></i> Truy cập nhanh
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-3 col-6">
                            <a href="<?= Url::to(['/pos/index']) ?>" class="btn btn-app bg-primary">
                                <i class="fas fa-cash-register"></i> POS
                            </a>
                        </div>
                        <div class="col-lg-3 col-6">
                            <a href="#" class="btn btn-app bg-success">
                                <i class="fas fa-chart-bar"></i> Báo cáo
                            </a>
                        </div>
                        <div class="col-lg-3 col-6">
                            <a href="#" class="btn btn-app bg-warning">
                                <i class="fas fa-box"></i> Sản phẩm
                            </a>
                        </div>
                        <div class="col-lg-3 col-6">
                            <a href="#" class="btn btn-app bg-info">
                                <i class="fas fa-users"></i> Khách hàng
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Mở ca làm việc -->
<div class="modal fade" id="modalOpenSession" tabindex="-1" role="dialog" aria-labelledby="modalOpenSessionLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title" id="modalOpenSessionLabel">Mở ca làm việc</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formOpenSession">
                    <div class="form-group">
                        <label for="cashAmount">Số tiền đầu ca:</label>
                        <input type="number" class="form-control" id="cashAmount" name="cashAmount" min="0" step="1000" required>
                    </div>
                    <div class="form-group">
                        <label for="note">Ghi chú:</label>
                        <textarea class="form-control" id="note" name="note" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-success" id="btnConfirmOpenSession">Xác nhận mở ca</button>
            </div>
        </div>
    </div>
</div>

<?php
$js = <<<JS
$(document).ready(function() {
    // Xử lý sự kiện click nút mở ca làm việc
    $('#btnOpenSession').on('click', function() {
        $('#modalOpenSession').modal('show');
    });
    
    // Xử lý sự kiện xác nhận mở ca
    $('#btnConfirmOpenSession').on('click', function() {
        if (!$('#formOpenSession')[0].checkValidity()) {
            $('#formOpenSession')[0].reportValidity();
            return;
        }
        
        var cashAmount = $('#cashAmount').val();
        var note = $('#note').val();
        
        $.ajax({
            url: 'pos/open-session',
            type: 'POST',
            data: {
                cashAmount: cashAmount,
                note: note,
                _csrf: '<?= $csrfToken ?>'
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    $('#modalOpenSession').modal('hide');
                    
                    // Chuyển đến trang POS sau 1 giây
                    setTimeout(function() {
                        window.location.href = 'pos/index';
                    }, 1000);
                } else {
                    toastr.error(response.message);
                }
            },
            error: function() {
                toastr.error('Có lỗi xảy ra. Vui lòng thử lại sau.');
            }
        });
    });
});
JS;

$this->registerJs($js);
?>