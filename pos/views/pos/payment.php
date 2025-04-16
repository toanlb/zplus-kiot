<?php

/* @var $this yii\web\View */
/* @var $cart array */
/* @var $totalQuantity int */
/* @var $subtotal float */
/* @var $discount float */
/* @var $tax float */
/* @var $grandTotal float */
/* @var $customer common\models\Customer */
/* @var $paymentMethods array */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Thanh toán';
?>
<div id="get-session-info" data-url="<?= Url::to(['pos/get-session-info']) ?>"></div>
<div class="pos-payment-container">
    <div class="row no-gutters">
        <!-- Cột trái: Thông tin đơn hàng -->
        <div class="col-md-7 payment-info-column">
            <div class="payment-info p-3">
                <div class="card mb-3">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-shopping-cart mr-2"></i> Thông tin đơn hàng</h5>
                    </div>
                    <div class="card-body">
                        <div class="customer-info mb-3">
                            <h6>Thông tin khách hàng</h6>
                            
                            <?php if ($customer): ?>
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <th>Tên khách hàng:</th>
                                            <td><?= Html::encode($customer->name) ?></td>
                                        </tr>
                                        <tr>
                                            <th>Số điện thoại:</th>
                                            <td><?= Html::encode($customer->phone) ?></td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <th>Điểm tích lũy:</th>
                                            <td><?= $customer->points ?? 0 ?></td>
                                        </tr>
                                        <tr>
                                            <th>Công nợ:</th>
                                            <td><?= Yii::$app->formatter->asCurrency($customer->debt ?? 0) ?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <?php else: ?>
                            <p>Khách lẻ</p>
                            <?php endif; ?>
                        </div>
                        
                        <div class="order-items mb-3">
                            <h6>Chi tiết đơn hàng</h6>
                            <div class="table-responsive">
                                <table class="table table-sm table-hover">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Sản phẩm</th>
                                            <th class="text-center">Số lượng</th>
                                            <th class="text-right">Đơn giá</th>
                                            <th class="text-right">Thành tiền</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($cart as $itemKey => $item): ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex">
                                                    <div>
                                                        <div class="font-weight-bold"><?= Html::encode($item['name']) ?></div>
                                                        <small class="text-muted"><?= Html::encode($item['code']) ?></small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center"><?= $item['quantity'] ?> <?= Html::encode($item['unit']) ?></td>
                                            <td class="text-right"><?= Yii::$app->formatter->asCurrency($item['price']) ?></td>
                                            <td class="text-right"><?= Yii::$app->formatter->asCurrency($item['price'] * $item['quantity']) ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <div class="order-summary mb-3">
                            <h6>Tổng cộng</h6>
                            <div class="card bg-light">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-7">
                                            <table class="table table-sm table-borderless">
                                                <tr>
                                                    <th>Tạm tính:</th>
                                                    <td class="text-right"><?= Yii::$app->formatter->asCurrency($subtotal) ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Giảm giá:</th>
                                                    <td class="text-right">- <?= Yii::$app->formatter->asCurrency($discount) ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Thuế:</th>
                                                    <td class="text-right"><?= Yii::$app->formatter->asCurrency($tax) ?></td>
                                                </tr>
                                                <tr class="font-weight-bold">
                                                    <th>Tổng cộng:</th>
                                                    <td class="text-right"><?= Yii::$app->formatter->asCurrency($grandTotal) ?></td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-md-5 text-center">
                                            <div class="total-box bg-primary text-white p-3 rounded">
                                                <h3 class="mb-0"><?= Yii::$app->formatter->asCurrency($grandTotal) ?></h3>
                                                <small><?= $totalQuantity ?> sản phẩm</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="actions mt-4">
                    <div class="row">
                        <div class="col-6">
                            <a href="<?= Url::to(['/pos/index']) ?>" class="btn btn-secondary btn-lg btn-block">
                                <i class="fas fa-arrow-left mr-2"></i> Quay lại
                            </a>
                        </div>
                        <div class="col-6">
                            <button type="button" class="btn btn-success btn-lg btn-block" id="btnPrintInvoice">
                                <i class="fas fa-print mr-2"></i> In hóa đơn
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Cột phải: Phương thức thanh toán -->
        <div class="col-md-5 payment-method-column">
            <div class="payment-method p-3">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-money-bill-wave mr-2"></i> Thanh toán</h5>
                    </div>
                    <div class="card-body">
                        <div class="payment-methods mb-4">
                            <h6>Chọn phương thức thanh toán</h6>
                            
                            <div class="row">
                                <div class="col-6 mb-3">
                                    <div class="payment-method-item active" data-method="cash">
                                        <i class="fas fa-money-bill-wave fa-2x mb-2"></i>
                                        <div>Tiền mặt</div>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="payment-method-item" data-method="bank_transfer">
                                        <i class="fas fa-university fa-2x mb-2"></i>
                                        <div>Chuyển khoản</div>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="payment-method-item" data-method="credit">
                                        <i class="fas fa-handshake fa-2x mb-2"></i>
                                        <div>Công nợ</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div id="cashPaymentForm" class="payment-form">
                            <div class="form-group">
                                <label for="amountTendered">Khách đưa:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">VND</span>
                                    </div>
                                    <input type="number" class="form-control form-control-lg" id="amountTendered" min="0" step="1000">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="changeAmount">Tiền thừa:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">VND</span>
                                    </div>
                                    <input type="text" class="form-control form-control-lg" id="changeAmount" readonly>
                                </div>
                            </div>
                            
                            <div class="quick-amounts mt-3 mb-4">
                                <div class="btn-toolbar" role="toolbar">
                                    <div class="btn-group mr-2 mb-2" role="group">
                                        <button type="button" class="btn btn-outline-secondary btn-quick-amount" data-amount="<?= $grandTotal ?>">Đủ tiền</button>
                                    </div>
                                    <div class="btn-group mr-2 mb-2" role="group">
                                        <button type="button" class="btn btn-outline-secondary btn-quick-amount" data-amount="50000">50,000</button>
                                        <button type="button" class="btn btn-outline-secondary btn-quick-amount" data-amount="100000">100,000</button>
                                    </div>
                                    <div class="btn-group mb-2" role="group">
                                        <button type="button" class="btn btn-outline-secondary btn-quick-amount" data-amount="200000">200,000</button>
                                        <button type="button" class="btn btn-outline-secondary btn-quick-amount" data-amount="500000">500,000</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div id="bankTransferPaymentForm" class="payment-form" style="display: none;">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle mr-2"></i> Vui lòng chuyển khoản đến tài khoản sau:
                            </div>
                            
                            <div class="card bg-light mb-3">
                                <div class="card-body">
                                    <p><strong>Tên ngân hàng:</strong> Vietcombank</p>
                                    <p><strong>Số tài khoản:</strong> 1234567890</p>
                                    <p><strong>Chủ tài khoản:</strong> CÔNG TY TNHH ABC</p>
                                    <p><strong>Nội dung chuyển khoản:</strong> Thanh toán đơn hàng</p>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="transactionId">Mã giao dịch:</label>
                                <input type="text" class="form-control" id="transactionId">
                            </div>
                        </div>
                        
                        
                        <div id="creditPaymentForm" class="payment-form" style="display: none;">
                            <?php if ($customer): ?>
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle mr-2"></i> Đơn hàng sẽ được ghi vào công nợ của khách hàng <strong><?= Html::encode($customer->name) ?></strong>.
                                </div>
                                
                                <div class="card bg-light mb-3">
                                    <div class="card-body">
                                        <p><strong>Công nợ hiện tại:</strong> <?= Yii::$app->formatter->asCurrency($customer->debt ?? 0) ?></p>
                                        <p><strong>Công nợ sau đơn hàng này:</strong> <?= Yii::$app->formatter->asCurrency(($customer->debt ?? 0) + $grandTotal) ?></p>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-danger">
                                    <i class="fas fa-exclamation-circle mr-2"></i> Vui lòng chọn khách hàng để sử dụng phương thức thanh toán này.
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="form-group">
                            <label for="paymentNote">Ghi chú thanh toán:</label>
                            <textarea class="form-control" id="paymentNote" rows="2"></textarea>
                        </div>
                        
                        <button type="button" class="btn btn-success btn-lg btn-block mt-4" id="btnCompletePayment">
                            <i class="fas fa-check-circle mr-2"></i> Hoàn tất thanh toán
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Payment Success -->
<div class="modal fade" id="modalPaymentSuccess" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="modalPaymentSuccessLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="modalPaymentSuccessLabel">Thanh toán thành công</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="mb-4">
                    <i class="fas fa-check-circle fa-5x text-success"></i>
                </div>
                <h4 class="mb-3">Thanh toán thành công!</h4>
                <p class="lead">Mã đơn hàng: <strong id="successOrderCode"></strong></p>
                <p>Tổng tiền: <strong id="successTotalAmount"></strong></p>
                <div id="successChangeContainer">
                    <p>Tiền thừa: <strong id="successChangeAmount"></strong></p>
                </div>
            </div>
            <div class="modal-footer justify-content-center">
                <a href="<?= Url::to(['/pos/index']) ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-2"></i> Quay lại POS
                </a>
                <button type="button" class="btn btn-success" id="btnPrintSuccessInvoice">
                    <i class="fas fa-print mr-2"></i> In hóa đơn
                </button>
                <a href="<?= Url::to(['/pos/payment']) ?>" class="btn btn-primary" id="btnNewOrder">
                    <i class="fas fa-plus mr-2"></i> Đơn hàng mới
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.pos-payment-container {
    height: calc(100vh - 60px);
    overflow: hidden;
}

.payment-info-column, .payment-method-column {
    height: calc(100vh - 60px);
    overflow-y: auto;
}

.payment-method-item {
    border: 1px solid #dee2e6;
    border-radius: 4px;
    padding: 15px 10px;
    text-align: center;
    cursor: pointer;
    transition: all 0.2s;
}

.payment-method-item:hover {
    border-color: #28a745;
    background-color: #f8f9fa;
}

.payment-method-item.active {
    border-color: #28a745;
    background-color: #e8f5e9;
}

.total-box {
    border-radius: 4px;
    padding: 15px;
}

#amountTendered, #changeAmount {
    font-size: 24px;
    font-weight: bold;
}

#btnCompletePayment {
    font-size: 18px;
    padding: 12px;
}

.btn-quick-amount {
    margin-right: 5px;
}

@media (max-width: 768px) {
    .pos-payment-container, .payment-info-column, .payment-method-column {
        height: auto;
        overflow: auto;
    }
}
</style>

<?php
$js = <<<JS
// Variables
let paymentMethod = 'cash';
let grandTotal = {$grandTotal};
let amountTendered = 0;
let changeAmount = 0;

$(document).ready(function() {
    // Initialize
    updateChangeAmount();
    
    // Payment Method Click
    $('.payment-method-item').on('click', function() {
        $('.payment-method-item').removeClass('active');
        $(this).addClass('active');
        
        paymentMethod = $(this).data('method');
        
        // Hide all payment forms
        $('.payment-form').hide();
        
        // Show selected payment form
        $('#' + paymentMethod + 'PaymentForm').show();
    });
    
    // Amount Tendered Input
    $('#amountTendered').on('input', function() {
        updateChangeAmount();
    });
    
    // Quick Amount Buttons
    $('.btn-quick-amount').on('click', function() {
        const amount = $(this).data('amount');
        $('#amountTendered').val(amount);
        updateChangeAmount();
    });
    
    // Complete Payment Button
    $('#btnCompletePayment').on('click', function() {
        // Validate payment method specific fields
        if (paymentMethod === 'cash') {
            if (parseFloat($('#amountTendered').val()) < grandTotal) {
                toastr.error('Số tiền khách đưa không đủ để thanh toán.');
                return;
            }
        } else if (paymentMethod === 'credit' && !$('#creditPaymentForm .alert-danger').length === 0) {
            // If credit payment and customer not selected
            toastr.error('Vui lòng chọn khách hàng để sử dụng phương thức thanh toán công nợ.');
            return;
        }
        
        completeOrder();
    });
    
    // Print Invoice Button
    $('#btnPrintInvoice').on('click', function() {
        printInvoice();
    });
    
    // Print Success Invoice Button
    $('#btnPrintSuccessInvoice').on('click', function() {
        printInvoice();
    });
    
    // Functions
    
    // Update Change Amount
    function updateChangeAmount() {
        amountTendered = parseFloat($('#amountTendered').val()) || 0;
        changeAmount = amountTendered - grandTotal;
        
        if (changeAmount >= 0) {
            $('#changeAmount').val(formatCurrency(changeAmount));
        } else {
            $('#changeAmount').val('Thiếu ' + formatCurrency(Math.abs(changeAmount)));
        }
    }
    
    // Complete Order
    function completeOrder() {
        const self = this;
        $.ajax({
            url: 'complete-order',
            type: 'GET',
            data: {
                paymentMethod: paymentMethod,
                amountTendered: amountTendered,
                note: $('#paymentNote').val(),
                _csrf: self.csrfToken
            },
            success: function(response) {
                console.log(response);
                if (response.success) {
                    // Show success modal
                    $('#successOrderCode').text(response.order.code);
                    $('#successTotalAmount').text(formatCurrency(response.order.total));
                    
                    if (paymentMethod === 'cash') {
                        $('#successChangeContainer').show();
                        $('#successChangeAmount').text(formatCurrency(response.order.change));
                    } else {
                        $('#successChangeContainer').hide();
                    }
                    
                    $('#modalPaymentSuccess').modal('show');
                } else {
                    toastr.error(response.message);
                }
            },
            error: function() {
                toastr.error('Có lỗi xảy ra khi hoàn tất thanh toán.');
            }
        });
    }
    
    // Print Invoice
    function printInvoice() {
        // TODO: Implement printing functionality
        toastr.info('Đang gửi lệnh in...');
        
        // For demo purposes, we'll just show a success message
        setTimeout(function() {
            toastr.success('Đã gửi lệnh in thành công!');
        }, 1000);
    }
    
    // Format Currency
    function formatCurrency(amount) {
        return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount);
    }
});
JS;

$this->registerJs($js);
?>