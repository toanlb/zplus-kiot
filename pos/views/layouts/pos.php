<?php

/* @var $this \yii\web\View */
/* @var $content string */

use pos\assets\PosAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use pos\models\PosSession;
PosAsset::register($this);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <style>
        body {
            overflow: hidden;
            background-color: #f4f6f9;
        }
        .pos-wrapper {
            display: flex;
            flex-direction: column;
            height: 100vh;
        }
        .pos-header {
            background-color: #007bff;
            color: white;
            padding: 0.5rem 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 60px;
        }
        .pos-header-left {
            display: flex;
            align-items: center;
        }
        .pos-logo {
            height: 40px;
            margin-right: 15px;
        }
        .pos-header-title {
            font-size: 1.5rem;
            font-weight: 600;
        }
        .pos-header-right {
            display: flex;
            align-items: center;
        }
        .pos-user-info {
            margin-right: 15px;
            text-align: right;
        }
        .pos-content {
            flex: 1;
            overflow: auto;
            padding: 0;
        }
        .btn-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: 10px;
        }
        .pos-session-info {
            background-color: rgba(255, 255, 255, 0.1);
            padding: 5px 10px;
            border-radius: 4px;
            margin-right: 15px;
        }
    </style>
</head>
<body>
<?php $this->beginBody() ?>

<div class="pos-wrapper">
    <!-- POS Header -->
    <div class="pos-header">
        <div class="pos-header-left">
            <img src="<?= Url::to(['/images/logo.png']) ?>" alt="Logo" class="pos-logo">
            <span class="pos-header-title">POS Bán Hàng</span>
            
            <?php if (PosSession::hasActiveSession()): ?>
            <?php $session = PosSession::getActiveSession(); ?>
            <div class="pos-session-info">
                <i class="fas fa-clock"></i> Ca làm việc: 
                <?= Yii::$app->formatter->asRelativeTime($session->start_time) ?>
            </div>
            <?php endif; ?>
        </div>
        <div class="pos-header-right">
            <div class="pos-user-info">
                <div><?= Yii::$app->user->identity->username ?></div>
                <small><?= date('d/m/Y H:i') ?></small>
            </div>
            
            <a href="<?= Url::to(['/site/index']) ?>" class="btn btn-info btn-circle" title="Trang chủ">
                <i class="fas fa-home"></i>
            </a>
            
            <button type="button" class="btn btn-info btn-circle" id="btnSessionInfo" title="Thông tin ca làm việc">
                <i class="fas fa-cash-register"></i>
            </button>
            
            <div class="dropdown">
                <button class="btn btn-info btn-circle dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Menu">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="#" id="btnHeldOrders">
                        <i class="fas fa-pause-circle"></i> Đơn hàng tạm
                    </a>
                    <a class="dropdown-item" href="#" id="btnReports">
                        <i class="fas fa-chart-bar"></i> Báo cáo
                    </a>
                    <div class="dropdown-divider"></div>
                    <?= Html::beginForm(['/site/logout'], 'post') 
                        . Html::submitButton(
                            '<i class="fas fa-sign-out-alt"></i> Đăng xuất',
                            ['class' => 'dropdown-item']
                        )
                        . Html::endForm() ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- POS Content -->
    <div class="pos-content">
        <?= $content ?>
    </div>
</div>

<!-- Modal Session Info -->
<div class="modal fade" id="modalSessionInfo" tabindex="-1" role="dialog" aria-labelledby="modalSessionInfoLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title" id="modalSessionInfoLabel">Thông tin ca làm việc</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="sessionInfoContent">
                <div class="text-center py-4">
                    <i class="fas fa-spinner fa-spin fa-2x"></i>
                    <p class="mt-2">Đang tải thông tin...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-danger" id="btnCloseSession">Đóng ca</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Held Orders -->
<div class="modal fade" id="modalHeldOrders" tabindex="-1" role="dialog" aria-labelledby="modalHeldOrdersLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title" id="modalHeldOrdersLabel">Đơn hàng tạm</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="heldOrdersContent">
                <div class="text-center py-4">
                    <i class="fas fa-spinner fa-spin fa-2x"></i>
                    <p class="mt-2">Đang tải danh sách đơn hàng tạm...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Close Session -->
<div class="modal fade" id="modalCloseSession" tabindex="-1" role="dialog" aria-labelledby="modalCloseSessionLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title" id="modalCloseSessionLabel">Đóng ca làm việc</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formCloseSession">
                    <div class="form-group">
                        <label for="cashAmount">Số tiền mặt cuối ca:</label>
                        <input type="number" class="form-control" id="cashAmount" name="cashAmount" min="0" step="1000" required>
                    </div>
                    <div class="form-group">
                        <label for="note">Ghi chú:</label>
                        <textarea class="form-control" id="note" name="note" rows="3"></textarea>
                    </div>
                </form>
                
                <div class="alert alert-warning mt-3">
                    <i class="fas fa-exclamation-triangle"></i> Lưu ý: Sau khi đóng ca, bạn sẽ không thể tiếp tục bán hàng cho đến khi mở ca mới.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-danger" id="btnConfirmCloseSession">Xác nhận đóng ca</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Xử lý hiển thị thông tin ca làm việc
    $('#btnSessionInfo').on('click', function() {
        $('#modalSessionInfo').modal('show');
        loadSessionInfo();
    });
    
    // Xử lý hiển thị đơn hàng tạm
    $('#btnHeldOrders').on('click', function() {
        $('#modalHeldOrders').modal('show');
        loadHeldOrders();
    });
    
    // Xử lý đóng ca
    $('#btnCloseSession').on('click', function() {
        $('#modalSessionInfo').modal('hide');
        $('#modalCloseSession').modal('show');
    });
    
    // Xác nhận đóng ca
    $('#btnConfirmCloseSession').on('click', function() {
        if (!$('#formCloseSession')[0].checkValidity()) {
            $('#formCloseSession')[0].reportValidity();
            return;
        }
        
        var cashAmount = $('#cashAmount').val();
        var note = $('#note').val();
        
        $.ajax({
            url: '<?= Url::to(['/pos/close-session']) ?>',
            type: 'POST',
            data: {
                cashAmount: cashAmount,
                note: note,
                _csrf: '<?= Yii::$app->request->csrfToken ?>'
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    $('#modalCloseSession').modal('hide');
                    
                    // Chuyển về trang chủ sau 2 giây
                    setTimeout(function() {
                        window.location.href = '<?= Url::to(['/site/index']) ?>';
                    }, 2000);
                } else {
                    toastr.error(response.message);
                }
            },
            error: function() {
                toastr.error('Có lỗi xảy ra. Vui lòng thử lại sau.');
            }
        });
    });
    
    // Hàm tải thông tin ca làm việc
    function loadSessionInfo() {
        $.ajax({
            url: '<?= Url::to(['/pos/get-session-info']) ?>',
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    var session = response.session;
                    var html = `
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-subtitle mb-2 text-muted">Thông tin chung</h6>
                                <table class="table table-bordered">
                                    <tr>
                                        <th>Nhân viên:</th>
                                        <td><?= Yii::$app->user->identity->username ?></td>
                                    </tr>
                                    <tr>
                                        <th>Thời gian bắt đầu:</th>
                                        <td>${session.start_time}</td>
                                    </tr>
                                    <tr>
                                        <th>Thời gian làm việc:</th>
                                        <td>${session.duration}</td>
                                    </tr>
                                    <tr>
                                        <th>Số tiền đầu ca:</th>
                                        <td>${formatCurrency(session.start_amount)}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        
                        <div class="card mt-3">
                            <div class="card-body">
                                <h6 class="card-subtitle mb-2 text-muted">Thống kê bán hàng</h6>
                                <table class="table table-bordered">
                                    <tr>
                                        <th>Tổng doanh số:</th>
                                        <td>${formatCurrency(session.total_sales)}</td>
                                    </tr>
                                    <tr>
                                        <th>Doanh số tiền mặt:</th>
                                        <td>${formatCurrency(session.cash_sales)}</td>
                                    </tr>
                                    <tr>
                                        <th>Doanh số thẻ:</th>
                                        <td>${formatCurrency(session.card_sales)}</td>
                                    </tr>
                                    <tr>
                                        <th>Doanh số chuyển khoản:</th>
                                        <td>${formatCurrency(session.bank_transfer_sales)}</td>
                                    </tr>
                                    <tr>
                                        <th>Doanh số khác:</th>
                                        <td>${formatCurrency(session.other_sales)}</td>
                                    </tr>
                                    <tr>
                                        <th>Số lượng đơn hàng:</th>
                                        <td>${session.orders_count}</td>
                                    </tr>
                                    <tr class="table-info">
                                        <th>Số tiền mặt hiện tại:</th>
                                        <td><strong>${formatCurrency(session.current_amount)}</strong></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    `;
                    
                    $('#sessionInfoContent').html(html);
                } else {
                    toastr.error(response.message);
                }
            },
            error: function() {
                toastr.error('Có lỗi xảy ra. Vui lòng thử lại sau.');
            }
        });
    }
    
    // Hàm tải danh sách đơn hàng tạm
    function loadHeldOrders() {
        $.ajax({
            url: '<?= Url::to(['/pos/get-held-orders']) ?>',
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    var orders = response.orders;
                    if (orders.length === 0) {
                        $('#heldOrdersContent').html('<div class="alert alert-info">Không có đơn hàng tạm nào.</div>');
                        return;
                    }
                    
                    var html = `
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Mã đơn</th>
                                        <th>Khách hàng</th>
                                        <th>Tổng tiền</th>
                                        <th>Sản phẩm</th>
                                        <th>Thời gian</th>
                                        <th>Ghi chú</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                    `;
                    
                    orders.forEach(function(order) {
                        html += `
                            <tr>
                                <td>${order.code}</td>
                                <td>${order.customer_name}</td>
                                <td>${formatCurrency(order.total)}</td>
                                <td>${order.items_count} sản phẩm</td>
                                <td>${order.created_at}</td>
                                <td>${order.note || ''}</td>
                                <td>
                                    <button class="btn btn-sm btn-primary btn-resume-order" data-id="${order.id}">
                                        <i class="fas fa-play"></i> Tiếp tục
                                    </button>
                                </td>
                            </tr>
                        `;
                    });
                    
                    html += `
                                </tbody>
                            </table>
                        </div>
                    `;
                    
                    $('#heldOrdersContent').html(html);
                    
                    // Xử lý sự kiện tiếp tục đơn hàng
                    $('.btn-resume-order').on('click', function() {
                        var orderId = $(this).data('id');
                        resumeOrder(orderId);
                    });
                } else {
                    toastr.error(response.message);
                }
            },
            error: function() {
                toastr.error('Có lỗi xảy ra. Vui lòng thử lại sau.');
            }
        });
    }
    
    // Hàm tiếp tục đơn hàng tạm
    function resumeOrder(orderId) {
        $.ajax({
            url: '<?= Url::to(['/pos/resume-order']) ?>',
            type: 'GET',
            data: {
                id: orderId
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    $('#modalHeldOrders').modal('hide');
                    
                    // Tải lại trang POS
                    window.location.reload();
                } else {
                    toastr.error(response.message);
                }
            },
            error: function() {
                toastr.error('Có lỗi xảy ra. Vui lòng thử lại sau.');
            }
        });
    }
    
    // Hàm định dạng tiền tệ
    function formatCurrency(amount) {
        return new Intl.NumberFormat('vi-VN', { 
            style: 'currency', 
            currency: 'VND' 
        }).format(amount);
    }
});
</script>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>