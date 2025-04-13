<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $categories common\models\ProductCategory[] */
/* @var $products common\models\Product[] */
/* @var $recentOrders common\models\Order[] */

$this->title = 'Bán hàng (POS)';
$this->params['breadcrumbs'][] = $this->title;

$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css');
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js', ['depends' => [\yii\web\JqueryAsset::class]]);

$this->registerCss("
    .product-card {
        cursor: pointer;
        margin-bottom: 15px;
        transition: all 0.3s;
    }
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    .product-image {
        height: 120px;
        object-fit: contain;
    }
    .cart-item {
        padding: 10px;
        border-bottom: 1px solid #f0f0f0;
    }
    .cart-item:hover {
        background-color: #f9f9f9;
    }
    .cart-empty {
        text-align: center;
        padding: 30px;
        color: #999;
    }
    .category-btn {
        margin-bottom: 10px;
        white-space: nowrap;
    }
    .cart-scroll {
        max-height: 400px;
        overflow-y: auto;
    }
    #loading-indicator {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(255,255,255,0.8);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }
    #payment-modal .modal-dialog {
        max-width: 800px;
    }
");
?>

<div id="loading-indicator" style="display: none;">
    <div class="spinner-border text-primary" role="status">
        <span class="sr-only">Loading...</span>
    </div>
</div>

<div class="pos-index">
    <div class="row">
        <!-- Phần trái: Sản phẩm -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="input-group">
                                <input type="text" id="product-search" class="form-control" placeholder="Tìm sản phẩm...">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" id="search-btn">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" id="customer-search" class="form-control" placeholder="Tìm khách hàng...">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" id="customer-search-btn">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-outline-primary btn-block" data-toggle="modal" data-target="#recent-orders-modal">
                                <i class="fas fa-history"></i> Gần đây
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Danh mục sản phẩm -->
                    <div class="categories-container mb-3">
                        <div class="d-flex overflow-auto pb-2">
                            <button class="btn btn-outline-primary category-btn mr-2" data-id="">Tất cả</button>
                            <?php foreach ($categories as $category): ?>
                            <button class="btn btn-outline-secondary category-btn mr-2" data-id="<?= $category->id ?>"><?= $category->name ?></button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <!-- Thông tin khách hàng đã chọn -->
                    <div id="selected-customer-info" class="alert alert-info" style="display: none;">
                        <button type="button" class="close" id="clear-customer">
                            <span>&times;</span>
                        </button>
                        <h5 id="customer-name">Tên khách hàng</h5>
                        <div class="row">
                            <div class="col-md-4">
                                <strong>Mã KH:</strong> <span id="customer-code"></span>
                            </div>
                            <div class="col-md-4">
                                <strong>SĐT:</strong> <span id="customer-phone"></span>
                            </div>
                            <div class="col-md-4">
                                <strong>Điểm:</strong> <span id="customer-points" class="badge badge-primary"></span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Danh sách sản phẩm -->
                    <div id="products-container" class="row">
                        <?php foreach ($products as $product): ?>
                        <div class="col-md-3">
                            <div class="card product-card" data-id="<?= $product->id ?>">
                                <img src="<?= $product->getImageUrl() ?>" class="card-img-top product-image" alt="<?= $product->name ?>">
                                <div class="card-body p-2">
                                    <h6 class="card-title mb-1"><?= $product->name ?></h6>
                                    <p class="card-text mb-0">
                                        <small class="text-muted"><?= $product->code ?></small>
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-primary font-weight-bold"><?= number_format($product->selling_price, 0, ',', '.') ?></span>
                                        <span class="badge <?= $product->current_stock > 0 ? 'badge-success' : 'badge-danger' ?>">
                                            <?= $product->current_stock ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Phần phải: Giỏ hàng -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-shopping-cart"></i> Giỏ hàng
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div id="cart-items" class="cart-scroll">
                        <div class="cart-empty">
                            <i class="fas fa-shopping-cart fa-3x mb-3"></i>
                            <p>Giỏ hàng trống</p>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="mb-2">
                        <div class="d-flex justify-content-between">
                            <span>Tổng tiền hàng:</span>
                            <span id="cart-subtotal">0</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Giảm giá:</span>
                            <span id="cart-discount">0</span>
                        </div>
                        <div class="d-flex justify-content-between font-weight-bold">
                            <span>Thành tiền:</span>
                            <span id="cart-total">0</span>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-6">
                            <button id="clear-cart-btn" class="btn btn-secondary btn-block">
                                <i class="fas fa-trash"></i> Xóa
                            </button>
                        </div>
                        <div class="col-6">
                            <button id="checkout-btn" class="btn btn-success btn-block" disabled>
                                <i class="fas fa-money-bill-wave"></i> Thanh toán
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal tìm kiếm khách hàng -->
<div class="modal fade" id="customer-search-modal" tabindex="-1" role="dialog" aria-labelledby="customer-search-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="customer-search-modal-label">Tìm kiếm khách hàng</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="input-group mb-3">
                    <input type="text" id="customer-search-input" class="form-control" placeholder="Nhập tên, SĐT hoặc mã khách hàng...">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button" id="do-customer-search">
                            <i class="fas fa-search"></i> Tìm
                        </button>
                    </div>
                </div>
                
                <div id="customer-search-results">
                    <div class="text-center text-muted">
                        Nhập thông tin để tìm kiếm khách hàng
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal đơn hàng gần đây -->
<div class="modal fade" id="recent-orders-modal" tabindex="-1" role="dialog" aria-labelledby="recent-orders-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="recent-orders-modal-label">Đơn hàng gần đây</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Mã đơn hàng</th>
                            <th>Khách hàng</th>
                            <th>Tổng tiền</th>
                            <th>Thời gian</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentOrders as $order): ?>
                        <tr>
                            <td><?= $order->code ?></td>
                            <td><?= $order->customer ? $order->customer->full_name : 'Khách lẻ' ?></td>
                            <td><?= number_format($order->final_amount, 0, ',', '.') ?></td>
                            <td><?= date('d/m/Y H:i', $order->created_at) ?></td>
                            <td>
                                <a href="<?= Url::to(['order/view', 'id' => $order->id]) ?>" class="btn btn-sm btn-primary" target="_blank">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="<?= Url::to(['pos/print-receipt', 'id' => $order->id]) ?>" class="btn btn-sm btn-info" target="_blank">
                                    <i class="fas fa-print"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal thanh toán -->
<div class="modal fade" id="payment-modal" tabindex="-1" role="dialog" aria-labelledby="payment-modal-label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="payment-modal-label">Thanh toán</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="payment-total">Tổng tiền</label>
                            <input type="text" class="form-control" id="payment-total" readonly>
                        </div>
                        
                        <div class="form-group">
                            <label for="payment-cash">Tiền mặt</label>
                            <input type="number" class="form-control payment-method" id="payment-cash" value="0">
                        </div>
                        
                        <div class="form-group">
                            <label for="payment-card">Thẻ</label>
                            <input type="number" class="form-control payment-method" id="payment-card" value="0">
                        </div>
                        
                        <div class="form-group">
                            <label for="payment-bank">Chuyển khoản</label>
                            <input type="number" class="form-control payment-method" id="payment-bank" value="0">
                        </div>
                        
                        <div class="form-group">
                            <label for="payment-ewallet">Ví điện tử</label>
                            <input type="number" class="form-control payment-method" id="payment-ewallet" value="0">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group" id="points-group" style="display: none;">
                            <label for="payment-points">Điểm sử dụng (1 điểm = 1.000đ)</label>
                            <input type="number" class="form-control" id="payment-points" value="0" min="0">
                            <small class="text-muted">Điểm hiện có: <span id="available-points">0</span></small>
                        </div>
                        
                        <div class="form-group">
                            <label for="payment-voucher">Mã giảm giá</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="payment-voucher">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" id="apply-voucher">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="payment-voucher-amount">Giảm giá từ voucher</label>
                            <input type="number" class="form-control" id="payment-voucher-amount" value="0" readonly>
                        </div>
                        
                        <div class="form-group">
                            <label for="payment-note">Ghi chú</label>
                            <textarea class="form-control" id="payment-note" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                
                <div class="alert alert-info">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Tổng thanh toán:</strong> <span id="payment-paid">0</span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Còn thiếu:</strong> <span id="payment-remaining">0</span></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-success" id="complete-payment">
                    <i class="fas fa-check-circle"></i> Hoàn tất
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal thông báo thành công -->
<div class="modal fade" id="success-modal" tabindex="-1" role="dialog" aria-labelledby="success-modal-label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="success-modal-label">Thành công</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                <h4>Đơn hàng đã được tạo thành công!</h4>
                <p>Mã đơn hàng: <strong id="success-order-code"></strong></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                <a href="#" id="print-receipt" class="btn btn-info" target="_blank">
                    <i class="fas fa-print"></i> In hóa đơn
                </a>
                <button type="button" class="btn btn-primary" id="new-order">
                    <i class="fas fa-plus"></i> Tạo đơn hàng mới
                </button>
            </div>
        </div>
    </div>
</div>

<?php
$posUrl = Url::to(['pos/index']);
$getProductsUrl = Url::to(['pos/get-products']);
$getProductInfoUrl = Url::to(['pos/get-product-info']);
$searchCustomerUrl = Url::to(['pos/search-customer']);
$createOrderUrl = Url::to(['pos/create-order']);
$printReceiptUrl = Url::to(['pos/print-receipt', 'id' => '']);

$script = <<<JS
// Biến global
let cart = [];
let selectedCustomer = null;

// Hàm khởi tạo
$(document).ready(function() {
    // Xử lý khi chọn danh mục
    $('.category-btn').click(function() {
        $('.category-btn').removeClass('btn-primary').addClass('btn-outline-secondary');
        $(this).removeClass('btn-outline-secondary').addClass('btn-primary');
        
        const categoryId = $(this).data('id');
        loadProducts(categoryId);
    });
    
    // Xử lý tìm kiếm sản phẩm
    $('#search-btn').click(function() {
        const keyword = $('#product-search').val();
        loadProducts(null, keyword);
    });
    
    $('#product-search').keypress(function(e) {
        if(e.which == 13) {
            const keyword = $(this).val();
            loadProducts(null, keyword);
        }
    });
    
    // Xử lý khi click vào sản phẩm
    $(document).on('click', '.product-card', function() {
        const productId = $(this).data('id');
        addToCart(productId);
    });
    
    // Xử lý khi click vào nút xóa trong giỏ hàng
    $(document).on('click', '.remove-item', function() {
        const index = $(this).data('index');
        removeFromCart(index);
    });
    
    // Xử lý khi thay đổi số lượng sản phẩm trong giỏ hàng
    $(document).on('change', '.item-quantity', function() {
        const index = $(this).data('index');
        const quantity = parseFloat($(this).val()) || 1;
        updateCartItemQuantity(index, quantity);
    });
    
    // Xử lý khi thay đổi giảm giá sản phẩm trong giỏ hàng
    $(document).on('change', '.item-discount', function() {
        const index = $(this).data('index');
        const discount = parseFloat($(this).val()) || 0;
        updateCartItemDiscount(index, discount);
    });
    
    // Xử lý nút xóa giỏ hàng
    $('#clear-cart-btn').click(function() {
        clearCart();
    });
    
    // Xử lý nút thanh toán
    $('#checkout-btn').click(function() {
        preparePayment();
    });
    
    // Xử lý tìm kiếm khách hàng
    $('#customer-search-btn').click(function() {
        $('#customer-search-modal').modal('show');
    });
    
    $('#customer-search').click(function() {
        $('#customer-search-modal').modal('show');
    });
    
    $('#do-customer-search').click(function() {
        searchCustomer();
    });
    
    $('#customer-search-input').keypress(function(e) {
        if(e.which == 13) {
            searchCustomer();
        }
    });
    
    // Xử lý khi chọn khách hàng
    $(document).on('click', '.select-customer', function() {
        const customerId = $(this).data('id');
        const customerName = $(this).data('name');
        const customerCode = $(this).data('code');
        const customerPhone = $(this).data('phone');
        const customerPoints = $(this).data('points');
        
        selectedCustomer = {
            id: customerId,
            name: customerName,
            code: customerCode,
            phone: customerPhone,
            points: customerPoints
        };
        
        $('#customer-name').text(customerName);
        $('#customer-code').text(customerCode);
        $('#customer-phone').text(customerPhone);
        $('#customer-points').text(customerPoints);
        
        $('#selected-customer-info').show();
        $('#customer-search-modal').modal('hide');
    });
    
    // Xử lý khi xóa khách hàng đã chọn
    $('#clear-customer').click(function() {
        selectedCustomer = null;
        $('#selected-customer-info').hide();
    });
    
    // Xử lý khi nhập phương thức thanh toán
    $('.payment-method').on('input', function() {
        calculatePayment();
    });
    
    // Xử lý khi nhập điểm sử dụng
    $('#payment-points').on('input', function() {
        const points = parseInt($(this).val()) || 0;
        const availablePoints = parseInt($('#available-points').text()) || 0;
        
        if (points > availablePoints) {
            $(this).val(availablePoints);
        }
        
        calculatePayment();
    });
    
    // Xử lý khi hoàn tất thanh toán
    $('#complete-payment').click(function() {
        completePayment();
    });
    
    // Xử lý khi tạo đơn hàng mới
    $('#new-order').click(function() {
        $('#success-modal').modal('hide');
        clearCart();
        selectedCustomer = null;
        $('#selected-customer-info').hide();
    });
});

// Hàm tải danh sách sản phẩm
function loadProducts(categoryId = null, keyword = null) {
    $('#loading-indicator').show();
    
    $.ajax({
        url: '$getProductsUrl',
        type: 'GET',
        data: {
            category_id: categoryId,
            keyword: keyword
        },
        success: function(response) {
            const products = JSON.parse(response);
            let html = '';
            
            if (products.length > 0) {
                products.forEach(function(product) {
                    html += `
                        <div class="col-md-3">
                            <div class="card product-card" data-id="\${product.id}">
                                <img src="\${product.image}" class="card-img-top product-image" alt="\${product.name}">
                                <div class="card-body p-2">
                                    <h6 class="card-title mb-1">\${product.name}</h6>
                                    <p class="card-text mb-0">
                                        <small class="text-muted">\${product.code}</small>
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-primary font-weight-bold">\${formatCurrency(product.price)}</span>
                                        <span class="badge \${product.stock > 0 ? 'badge-success' : 'badge-danger'}">
                                            \${product.stock}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                });
            } else {
                html = '<div class="col-12 text-center py-5"><p class="text-muted">Không tìm thấy sản phẩm</p></div>';
            }
            
            $('#products-container').html(html);
            $('#loading-indicator').hide();
        },
        error: function() {
            $('#products-container').html('<div class="col-12 text-center py-5"><p class="text-danger">Lỗi khi tải sản phẩm</p></div>');
            $('#loading-indicator').hide();
        }
    });
}

// Hàm thêm sản phẩm vào giỏ hàng
function addToCart(productId) {
    $('#loading-indicator').show();
    
    $.ajax({
        url: '$getProductInfoUrl',
        type: 'GET',
        data: {
            id: productId
        },
        success: function(response) {
            const product = JSON.parse(response);
            
            if (product.error) {
                alert(product.error);
                $('#loading-indicator').hide();
                return;
            }
            
            // Kiểm tra sản phẩm đã có trong giỏ hàng chưa
            const existingItem = cart.findIndex(item => item.id === product.id);
            
            if (existingItem !== -1) {
                // Nếu đã có, tăng số lượng
                cart[existingItem].quantity += 1;
                updateCartTotals();
            } else {
                // Nếu chưa có, thêm mới
                const cartItem = {
                    id: product.id,
                    code: product.code,
                    name: product.name,
                    unit: product.unit,
                    price: parseFloat(product.price),
                    quantity: 1,
                    discount: 0,
                    subtotal: parseFloat(product.price),
                    discount_amount: 0,
                    final_price: parseFloat(product.price)
                };
                
                cart.push(cartItem);
            }
            
            renderCart();
            updateCartTotals();
            $('#loading-indicator').hide();
        },
        error: function() {
            alert('Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng');
            $('#loading-indicator').hide();
        }
    });
}

// Hàm xóa sản phẩm khỏi giỏ hàng
function removeFromCart(index) {
    cart.splice(index, 1);
    renderCart();
    updateCartTotals();
}

// Hàm cập nhật số lượng sản phẩm trong giỏ hàng
function updateCartItemQuantity(index, quantity) {
    cart[index].quantity = quantity;
    
    // Cập nhật giá trị
    calculateItemTotal(index);
    renderCart();
    updateCartTotals();
}

// Hàm cập nhật giảm giá sản phẩm trong giỏ hàng
function updateCartItemDiscount(index, discount) {
    cart[index].discount = discount;
    
    // Cập nhật giá trị
    calculateItemTotal(index);
    renderCart();
    updateCartTotals();
}

// Hàm tính toán giá trị sản phẩm
function calculateItemTotal(index) {
    const item = cart[index];
    item.subtotal = item.price * item.quantity;
    item.discount_amount = item.subtotal * (item.discount / 100);
    item.final_price = item.subtotal - item.discount_amount;
}

// Hàm xóa giỏ hàng
function clearCart() {
    cart = [];
    renderCart();
    updateCartTotals();
}

// Hàm hiển thị giỏ hàng
function renderCart() {
    let html = '';
    
    if (cart.length > 0) {
        cart.forEach(function(item, index) {
            html += `
                <div class="cart-item">
                    <div class="d-flex justify-content-between mb-1">
                        <h6 class="mb-0">\${item.name}</h6>
                        <button type="button" class="close remove-item" data-index="\${index}">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <small class="text-muted">\${item.code}</small>
                        <small class="text-muted">\${item.unit}</small>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mb-1">
                                <label class="small mb-0">Số lượng</label>
                                <input type="number" class="form-control form-control-sm item-quantity" 
                                    data-index="\${index}" value="\${item.quantity}" min="1" step="1">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-1">
                                <label class="small mb-0">Đơn giá</label>
                                <input type="text" class="form-control form-control-sm" 
                                    value="\${formatCurrency(item.price)}" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-1">
                                <label class="small mb-0">Giảm giá (%)</label>
                                <input type="number" class="form-control form-control-sm item-discount" 
                                    data-index="\${index}" value="\${item.discount}" min="0" max="100">
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between mt-1">
                        <span>Thành tiền:</span>
                        <span class="font-weight-bold text-primary">\${formatCurrency(item.final_price)}</span>
                    </div>
                </div>
            `;
        });
    } else {
        html = `
            <div class="cart-empty">
                <i class="fas fa-shopping-cart fa-3x mb-3"></i>
                <p>Giỏ hàng trống</p>
            </div>
        `;
    }
    
    $('#cart-items').html(html);
    
    // Kích hoạt/vô hiệu hóa nút thanh toán
    if (cart.length > 0) {
        $('#checkout-btn').prop('disabled', false);
    } else {
        $('#checkout-btn').prop('disabled', true);
    }
}

// Hàm cập nhật tổng giá trị giỏ hàng
function updateCartTotals() {
    let subtotal = 0;
    let discount = 0;
    let total = 0;
    
    cart.forEach(function(item) {
        subtotal += item.subtotal;
        discount += item.discount_amount;
        total += item.final_price;
    });
    
    $('#cart-subtotal').text(formatCurrency(subtotal));
    $('#cart-discount').text(formatCurrency(discount));
    $('#cart-total').text(formatCurrency(total));
}

// Hàm định dạng tiền tệ
function formatCurrency(amount) {
    return new Intl.NumberFormat('vi-VN').format(amount);
}

// Hàm tìm kiếm khách hàng
function searchCustomer() {
    const keyword = $('#customer-search-input').val();
    
    if (keyword.trim() === '') {
        $('#customer-search-results').html('<div class="text-center text-muted">Vui lòng nhập từ khóa tìm kiếm</div>');
        return;
    }
    
    $('#customer-search-results').html('<div class="text-center"><div class="spinner-border spinner-border-sm" role="status"></div> Đang tìm kiếm...</div>');
    
    $.ajax({
        url: '$searchCustomerUrl',
        type: 'GET',
        data: {
            keyword: keyword
        },
        success: function(response) {
            const customers = JSON.parse(response);
            let html = '';
            
            if (customers.length > 0) {
                html = '<div class="list-group">';
                
                customers.forEach(function(customer) {
                    html += `
                        <a href="javascript:void(0)" class="list-group-item list-group-item-action select-customer" 
                           data-id="\${customer.id}" data-name="\${customer.name}" data-code="\${customer.code}" 
                           data-phone="\${customer.phone}" data-points="\${customer.points}">
                            <div class="d-flex justify-content-between">
                                <h6 class="mb-1">\${customer.name}</h6>
                                <span class="badge badge-\${customer.group == 'VIP' ? 'danger' : 'primary'}">\${customer.group}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <small>Mã KH: \${customer.code}</small>
                                <small>SĐT: \${customer.phone}</small>
                                <small>Điểm: \${customer.points}</small>
                            </div>
                        </a>
                    `;
                });
                
                html += '</div>';
            } else {
                html = '<div class="text-center text-muted">Không tìm thấy khách hàng nào</div>';
            }
            
            $('#customer-search-results').html(html);
        },
        error: function() {
            $('#customer-search-results').html('<div class="text-center text-danger">Có lỗi xảy ra khi tìm kiếm</div>');
        }
    });
}

// Hàm chuẩn bị thanh toán
function preparePayment() {
    if (cart.length === 0) {
        return;
    }
    
    // Tính tổng tiền hàng
    let total = 0;
    cart.forEach(function(item) {
        total += item.final_price;
    });
    
    // Hiển thị modal thanh toán
    $('#payment-total').val(formatCurrency(total));
    $('#payment-cash').val(total);
    $('#payment-card').val(0);
    $('#payment-bank').val(0);
    $('#payment-ewallet').val(0);
    $('#payment-points').val(0);
    $('#payment-voucher').val('');
    $('#payment-voucher-amount').val(0);
    $('#payment-note').val('');
    
    // Kiểm tra khách hàng có điểm không
    if (selectedCustomer) {
        $('#points-group').show();
        $('#available-points').text(selectedCustomer.points);
    } else {
        $('#points-group').hide();
    }
    
    $('#payment-paid').text(formatCurrency(total));
    $('#payment-remaining').text(formatCurrency(0));
    
    $('#payment-modal').modal('show');
}

// Hàm tính toán thanh toán
function calculatePayment() {
    const total = parseFloat($('#payment-total').val().replace(/[^0-9]/g, '')) || 0;
    const cash = parseFloat($('#payment-cash').val()) || 0;
    const card = parseFloat($('#payment-card').val()) || 0;
    const bank = parseFloat($('#payment-bank').val()) || 0;
    const ewallet = parseFloat($('#payment-ewallet').val()) || 0;
    const points = parseInt($('#payment-points').val()) || 0;
    const voucherAmount = parseFloat($('#payment-voucher-amount').val()) || 0;
    
    // Quy đổi điểm thành tiền (1 điểm = 1.000đ)
    const pointsValue = points * 1000;
    
    const paid = cash + card + bank + ewallet + pointsValue + voucherAmount;
    const remaining = total - paid;
    
    $('#payment-paid').text(formatCurrency(paid));
    $('#payment-remaining').text(formatCurrency(remaining));
}

// Hàm hoàn tất thanh toán
function completePayment() {
    if (cart.length === 0) {
        return;
    }
    
    $('#loading-indicator').show();
    
    // Tính tổng tiền hàng
    let totalAmount = 0;
    let discountAmount = 0;
    
    cart.forEach(function(item) {
        totalAmount += item.subtotal;
        discountAmount += item.discount_amount;
    });
    
    const finalAmount = totalAmount - discountAmount;
    
    // Lấy thông tin thanh toán
    const cashAmount = parseFloat($('#payment-cash').val()) || 0;
    const cardAmount = parseFloat($('#payment-card').val()) || 0;
    const bankAmount = parseFloat($('#payment-bank').val()) || 0;
    const ewalletAmount = parseFloat($('#payment-ewallet').val()) || 0;
    const pointsUsed = parseInt($('#payment-points').val()) || 0;
    const voucherCode = $('#payment-voucher').val();
    const voucherAmount = parseFloat($('#payment-voucher-amount').val()) || 0;
    const note = $('#payment-note').val();
    
    // Tính tổng tiền đã thanh toán
    const paidAmount = cashAmount + cardAmount + bankAmount + ewalletAmount + (pointsUsed * 1000) + voucherAmount;
    
    // Chuẩn bị dữ liệu gửi lên server
    const data = {
        customer_id: selectedCustomer ? selectedCustomer.id : null,
        total_amount: totalAmount,
        discount_amount: discountAmount,
        final_amount: finalAmount,
        paid_amount: paidAmount,
        cash_amount: cashAmount,
        card_amount: cardAmount,
        bank_transfer_amount: bankAmount,
        ewallet_amount: ewalletAmount,
        points_used: pointsUsed,
        voucher_code: voucherCode,
        voucher_amount: voucherAmount,
        note: note,
        products: cart
    };
    
    // Gửi dữ liệu lên server
    $.ajax({
        url: '$createOrderUrl',
        type: 'POST',
        data: data,
        success: function(response) {
            const result = JSON.parse(response);
            
            if (result.success) {
                // Hiển thị thông báo thành công
                $('#success-order-code').text(result.order_code);
                $('#print-receipt').attr('href', '$printReceiptUrl' + result.order_id);
                
                $('#payment-modal').modal('hide');
                $('#success-modal').modal('show');
            } else {
                alert('Lỗi: ' + result.message);
            }
            
            $('#loading-indicator').hide();
        },
        error: function() {
            alert('Có lỗi xảy ra khi tạo đơn hàng');
            $('#loading-indicator').hide();
        }
    });
}
JS;

$this->registerJs($script);
?>