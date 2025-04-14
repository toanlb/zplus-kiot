<?php

/* @var $this yii\web\View */
/* @var $hasActiveSession boolean */
/* @var $activeSession pos\models\PosSession */
/* @var $categories array */
/* @var $topProducts array */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'POS Bán Hàng';
$csrfToken = Yii::$app->request->csrfToken;
?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<div class="pos-main-container">
    <?php if (!$hasActiveSession): ?>
        <div class="container py-5">
            <div class="card">
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-exclamation-triangle fa-4x text-warning"></i>
                    </div>
                    <h4 class="mb-3">Bạn chưa mở ca làm việc</h4>
                    <p class="text-muted mb-4">Vui lòng mở ca làm việc trước khi sử dụng POS.</p>
                    <button type="button" class="btn btn-success btn-lg" id="btnOpenSession">
                        <i class="fas fa-door-open mr-2"></i> Mở ca làm việc
                    </button>
                </div>
            </div>
        </div>

        <script>
            $(document).ready(function() {
                $('#btnOpenSession').on('click', function() {
                    $('#modalOpenSession').modal('show');
                });
                $('#btnConfirmOpenSession').on('click', function() {
                    const cashAmount = $('#cashAmount').val();
                    const note = $('#note').val();
                    
                    $.ajax({
                        url: '<?= Url::to(['pos/open-session']) ?>',
                        type: 'POST',
                        data: {
                            cashAmount: cashAmount,
                            note: note,
                            _csrf: '<?= $csrfToken ?>'
                        },
                        success: function(response) {
                            if (response.success) {
                                window.location.reload();
                            } else {
                                alert(response.message);
                            }
                        },
                        error: function() {
                            alert('Có lỗi xảy ra, vui lòng thử lại.');
                        }
                    });
                });
            });
        </script>
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
    <?php else: ?>
        <div class="row no-gutters">
            <!-- Cột trái: Danh mục -->
            <div class="col-md-2 pos-categories-column">
                <div class="pos-categories">
                    <div class="category-search mb-2">
                        <div class="input-group">
                            <input type="text" class="form-control" id="searchCategory" placeholder="Tìm danh mục">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="category-list">
                        <div class="list-group" id="categoryList">
                            <a href="#" class="list-group-item list-group-item-action active" data-id="0">
                                <i class="fas fa-th-large mr-2"></i> Tất cả sản phẩm
                            </a>
                            
                            <?php foreach ($categories as $category): ?>
                            <a href="#" class="list-group-item list-group-item-action" data-id="<?= $category->id ?>">
                                <i class="<?= $category->icon ?? 'fas fa-folder' ?> mr-2"></i> <?= Html::encode($category->name) ?>
                            </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                
                <div class="pos-search mt-2">
                    <div class="input-group">
                        <input type="text" class="form-control" id="searchProduct" placeholder="Tìm sản phẩm">
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Cột giữa: Sản phẩm -->
            <div class="col-md-7 pos-products-column">
                <!-- Thanh công cụ nhanh -->
                <div class="pos-tools bg-light p-2 mb-2">
                    <div class="btn-toolbar" role="toolbar">
                        <div class="btn-group mr-2" role="group">
                            <button type="button" class="btn btn-outline-primary btn-sm" id="btnShowTopProducts">
                                <i class="fas fa-star"></i> Top bán chạy
                            </button>
                            <button type="button" class="btn btn-outline-info btn-sm" id="btnShowNewProducts">
                                <i class="fas fa-bolt"></i> Mới nhất
                            </button>
                        </div>
                        <div class="btn-group mr-2" role="group">
                            <button type="button" class="btn btn-outline-success btn-sm" id="btnShowPromoProducts">
                                <i class="fas fa-tags"></i> Khuyến mãi
                            </button>
                            <button type="button" class="btn btn-outline-warning btn-sm" id="btnShowFavoriteProducts">
                                <i class="fas fa-heart"></i> Yêu thích
                            </button>
                        </div>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-outline-secondary btn-sm" id="btnGridView">
                                <i class="fas fa-th"></i>
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" id="btnListView">
                                <i class="fas fa-list"></i>
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Danh sách sản phẩm -->
                <div class="pos-products-container">
                    <div class="row" id="productGrid">
                        <!-- Products will be loaded here via AJAX -->
                        <div class="col-12 text-center py-5">
                            <i class="fas fa-spinner fa-spin fa-2x"></i>
                            <p class="mt-2">Đang tải sản phẩm...</p>
                        </div>
                    </div>
                </div>
                
                <!-- Phân trang -->
                <div class="pos-pagination mt-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted small">
                            Hiển thị <span id="productsCount">0</span> sản phẩm
                        </div>
                        <div>
                            <nav aria-label="Page navigation">
                                <ul class="pagination pagination-sm m-0" id="pagination">
                                    <!-- Pagination will be generated dynamically -->
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Cột phải: Giỏ hàng -->
            <div class="col-md-3 pos-cart-column">
                <div class="pos-cart">
                    <div class="cart-header bg-primary p-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="m-0 text-white">
                                <i class="fas fa-shopping-cart mr-2"></i> Giỏ hàng
                            </h5>
                            <div>
                                <button type="button" class="btn btn-light btn-sm" id="btnClearCart" title="Xóa giỏ hàng">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <button type="button" class="btn btn-light btn-sm" id="btnHoldOrder" title="Lưu đơn tạm">
                                    <i class="fas fa-pause"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="cart-customer bg-light p-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="cart-customer-info">
                                <i class="fas fa-user-circle mr-1"></i> 
                                <span id="selectedCustomerName">Khách lẻ</span>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary" id="btnAddCustomer">
                                <i class="fas fa-user-plus"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="cart-items">
                        <div class="cart-empty text-center p-4" id="cartEmpty">
                            <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                            <p>Giỏ hàng trống</p>
                        </div>
                        
                        <div class="table-responsive" id="cartItems" style="display: none;">
                            <table class="table table-sm table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th style="width: 45%">Sản phẩm</th>
                                        <th style="width: 20%">Giá</th>
                                        <th style="width: 20%">SL</th>
                                        <th style="width: 15%"></th>
                                    </tr>
                                </thead>
                                <tbody id="cartItemsList">
                                    <!-- Cart items will be added dynamically -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <div class="cart-totals bg-light">
                        <table class="table table-sm mb-0">
                            <tr>
                                <td>Tạm tính:</td>
                                <td class="text-right" id="cartSubtotal">0đ</td>
                            </tr>
                            <tr>
                                <td>Giảm giá:</td>
                                <td class="text-right" id="cartDiscount">0đ</td>
                            </tr>
                            <tr>
                                <td>Thuế:</td>
                                <td class="text-right" id="cartTax">0đ</td>
                            </tr>
                            <tr class="font-weight-bold">
                                <td>Tổng cộng:</td>
                                <td class="text-right" id="cartTotal">0đ</td>
                            </tr>
                        </table>
                    </div>
                    
                    <div class="cart-actions p-2">
                        <div class="row">
                            <div class="col-6">
                                <button type="button" class="btn btn-warning btn-block" id="btnDiscount">
                                    <i class="fas fa-percent mr-1"></i> Giảm giá
                                </button>
                            </div>
                            <div class="col-6">
                                <button type="button" class="btn btn-info btn-block" id="btnNote">
                                    <i class="fas fa-sticky-note mr-1"></i> Ghi chú
                                </button>
                            </div>
                        </div>
                        
                        <button type="button" class="btn btn-success btn-lg btn-block mt-2" id="btnPayment" disabled>
                            <i class="fas fa-money-bill-wave mr-1"></i> Thanh toán (<span id="cartTotalItems">0</span>)
                        </button>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Modal Product Details -->
<div class="modal fade" id="modalProductDetails" tabindex="-1" role="dialog" aria-labelledby="modalProductDetailsLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalProductDetailsLabel">Chi tiết sản phẩm</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="productDetailsContent">
                <div class="text-center py-4">
                    <i class="fas fa-spinner fa-spin fa-2x"></i>
                    <p class="mt-2">Đang tải thông tin sản phẩm...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-primary" id="btnAddToCartFromModal">Thêm vào giỏ hàng</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Customer Search -->
<div class="modal fade" id="modalCustomerSearch" tabindex="-1" role="dialog" aria-labelledby="modalCustomerSearchLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalCustomerSearchLabel">Tìm kiếm khách hàng</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <div class="input-group">
                        <input type="text" class="form-control" id="customerSearchInput" placeholder="Nhập tên, số điện thoại hoặc email">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="button" id="btnSearchCustomer">
                                <i class="fas fa-search"></i> Tìm kiếm
                            </button>
                        </div>
                    </div>
                </div>
                
                <div id="customerSearchResults">
                    <div class="customer-list-placeholder">
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-users fa-3x mb-3"></i>
                            <p>Tìm kiếm khách hàng bằng cách nhập thông tin vào ô tìm kiếm</p>
                        </div>
                    </div>
                </div>
                
                <hr>
                
                <div class="text-center">
                    <p>Không tìm thấy khách hàng?</p>
                    <button type="button" class="btn btn-success" id="btnShowNewCustomerForm">
                        <i class="fas fa-user-plus"></i> Thêm khách hàng mới
                    </button>
                </div>
                
                <div id="newCustomerForm" style="display: none;" class="mt-3">
                    <h5>Thêm khách hàng mới</h5>
                    <form id="formNewCustomer">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="customerName">Tên khách hàng <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="customerName" name="name" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="customerPhone">Số điện thoại <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control" id="customerPhone" name="phone" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="customerEmail">Email</label>
                                <input type="email" class="form-control" id="customerEmail" name="email">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="customerAddress">Địa chỉ</label>
                                <input type="text" class="form-control" id="customerAddress" name="address">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary" id="btnSaveNewCustomer">Lưu khách hàng</button>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Discount -->
<div class="modal fade" id="modalDiscount" tabindex="-1" role="dialog" aria-labelledby="modalDiscountLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title" id="modalDiscountLabel">Áp dụng giảm giá</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formDiscount">
                    <div class="form-group">
                        <label>Loại giảm giá:</label>
                        <div class="btn-group btn-group-toggle w-100" data-toggle="buttons">
                            <label class="btn btn-outline-primary active">
                                <input type="radio" name="discountType" id="discountTypePercent" value="percent" checked> Theo %
                            </label>
                            <label class="btn btn-outline-primary">
                                <input type="radio" name="discountType" id="discountTypeAmount" value="amount"> Theo số tiền
                            </label>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="discountValue">Giá trị giảm:</label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="discountValue" name="discountValue" min="0" required>
                            <div class="input-group-append">
                                <span class="input-group-text" id="discountValueUnit">%</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="discountReason">Lý do giảm giá:</label>
                        <input type="text" class="form-control" id="discountReason" name="discountReason">
                    </div>
                </form>
                
                <div class="discount-preview mt-3">
                    <table class="table table-sm">
                        <tr>
                            <td>Tạm tính:</td>
                            <td class="text-right" id="discountSubtotal">0đ</td>
                        </tr>
                        <tr>
                            <td>Giảm giá:</td>
                            <td class="text-right text-danger" id="discountAmount">0đ</td>
                        </tr>
                        <tr class="font-weight-bold">
                            <td>Sau giảm giá:</td>
                            <td class="text-right" id="discountTotal">0đ</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-primary" id="btnApplyDiscount">Áp dụng</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Order Note -->
<div class="modal fade" id="modalNote" tabindex="-1" role="dialog" aria-labelledby="modalNoteLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="modalNoteLabel">Ghi chú đơn hàng</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="orderNote">Ghi chú:</label>
                    <textarea class="form-control" id="orderNote" rows="4"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-primary" id="btnSaveNote">Lưu ghi chú</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Hold Order -->
<div class="modal fade" id="modalHoldOrder" tabindex="-1" role="dialog" aria-labelledby="modalHoldOrderLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title" id="modalHoldOrderLabel">Lưu đơn hàng tạm</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="holdOrderNote">Ghi chú:</label>
                    <textarea class="form-control" id="holdOrderNote" rows="3" placeholder="Nhập ghi chú để dễ nhận biết đơn hàng tạm"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-primary" id="btnSaveHoldOrder">Lưu tạm</button>
            </div>
        </div>
    </div>
</div>

<style>
.pos-main-container {
    height: calc(100vh - 60px);
    overflow: hidden;
}

.pos-categories-column, .pos-products-column, .pos-cart-column {
    height: calc(100vh - 60px);
    overflow: hidden;
}

.pos-categories {
    height: calc(100% - 50px);
    overflow: hidden;
    display: flex;
    flex-direction: column;
}

.category-list {
    overflow-y: auto;
    flex: 1;
}

.pos-products-container {
    height: calc(100% - 95px);
    overflow-y: auto;
    padding: 10px;
}

.pos-cart {
    height: 100%;
    display: flex;
    flex-direction: column;
    border-left: 1px solid #dee2e6;
}

.cart-items {
    flex: 1;
    overflow-y: auto;
}

.product-card {
    cursor: pointer;
    transition: all 0.2s;
    margin-bottom: 15px;
}

.product-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.product-card .card-img-top {
    height: 120px;
    object-fit: cover;
}

.product-card .card-title {
    font-size: 14px;
    height: 40px;
    overflow: hidden;
    margin-bottom: 5px;
}

.product-card .card-text {
    font-size: 13px;
}

.product-price {
    font-weight: bold;
    color: #28a745;
}

.product-original-price {
    text-decoration: line-through;
    color: #dc3545;
    font-size: 12px;
}

.cart-item-row:hover {
    background-color: #f8f9fa;
}

.cart-quantity {
    width: 40px;
    text-align: center;
    border: 1px solid #ced4da;
    border-radius: 4px;
}

.btn-quantity {
    padding: 0;
    width: 20px;
    height: 20px;
    line-height: 1;
    font-size: 10px;
}

@media (max-width: 768px) {
    .pos-main-container, .pos-categories-column, .pos-products-column, .pos-cart-column {
        height: auto;
        overflow: auto;
    }
    
    .pos-cart-column {
        height: 60vh;
    }
}
</style>

<script>
// Định nghĩa hàm formatCurrency ở ngoài để có thể được sử dụng bất kỳ đâu
function formatCurrency(amount) {
    return new Intl.NumberFormat('vi-VN').format(amount);
}
</script>

<?php
$js = <<<JS
// Variables for cart and products
let currentCategoryId = 0;
let currentPage = 1;
let currentSearch = '';
let productsPerPage = 24;
let viewMode = 'grid';
let selectedProductId = null;
let cart = [];
let subtotal = 0;
let discount = 0;
let tax = 0;
let grandTotal = 0;
let selectedCustomerId = null;
let selectedCustomerName = 'Khách lẻ';
let orderNote = '';

$(document).ready(function() {
    // Initialize
    loadProducts();
    loadCart();
    
    // Category click
    $('#categoryList').on('click', 'a', function(e) {
        e.preventDefault();
        $('#categoryList a').removeClass('active');
        $(this).addClass('active');
        currentCategoryId = $(this).data('id');
        currentPage = 1;
        loadProducts();
    });
    
    // Search product
    $('#searchProduct').on('keyup', function(e) {
        if (e.keyCode === 13) { // Enter key
            currentSearch = $(this).val();
            currentPage = 1;
            loadProducts();
        }
    });
    
    // Filter buttons
    $('#btnShowTopProducts').on('click', function() {
        // TODO: Load top products
        toastr.info('Đang tải sản phẩm bán chạy...');
    });
    
    $('#btnShowNewProducts').on('click', function() {
        // TODO: Load new products
        toastr.info('Đang tải sản phẩm mới...');
    });
    
    $('#btnShowPromoProducts').on('click', function() {
        // TODO: Load promo products
        toastr.info('Đang tải sản phẩm khuyến mãi...');
    });
    
    $('#btnShowFavoriteProducts').on('click', function() {
        // TODO: Load favorite products
        toastr.info('Đang tải sản phẩm yêu thích...');
    });
    
    // View mode
    $('#btnGridView').on('click', function() {
        viewMode = 'grid';
        loadProducts();
    });
    
    $('#btnListView').on('click', function() {
        viewMode = 'list';
        loadProducts();
    });
    
    // Product click - show details
    $('#productGrid').on('click', '.product-card', function() {
        const productId = $(this).data('id');
        showProductDetails(productId);
    });
    
    // Product add to cart
    $('#productGrid').on('click', '.btn-add-to-cart', function(e) {
        e.stopPropagation();
        const productId = $(this).closest('.product-card').data('id');
        addToCart(productId, 1);
    });
    
    // Modal add to cart
    $('#btnAddToCartFromModal').on('click', function() {
        if (selectedProductId) {
            const quantity = parseInt($('#productDetailsQuantity').val()) || 1;
            addToCart(selectedProductId, quantity);
            $('#modalProductDetails').modal('hide');
        }
    });
    
    // Cart quantity change
    $('#cartItemsList').on('click', '.btn-quantity-minus', function() {
        const itemKey = $(this).closest('tr').data('key');
        updateCartItemQuantity(itemKey, -1);
    });
    
    $('#cartItemsList').on('click', '.btn-quantity-plus', function() {
        const itemKey = $(this).closest('tr').data('key');
        updateCartItemQuantity(itemKey, 1);
    });
    
    $('#cartItemsList').on('change', '.cart-quantity', function() {
        const itemKey = $(this).closest('tr').data('key');
        const quantity = parseInt($(this).val()) || 1;
        updateCartItemQuantity(itemKey, quantity, true);
    });
    
    // Remove from cart
    $('#cartItemsList').on('click', '.btn-remove-item', function() {
        const itemKey = $(this).closest('tr').data('key');
        removeFromCart(itemKey);
    });
    
    // Clear cart
    $('#btnClearCart').on('click', function() {
        if (cart.length === 0) return;
        
        if (confirm('Bạn có chắc chắn muốn xóa tất cả sản phẩm trong giỏ hàng?')) {
            clearCart();
        }
    });
    
    // Customer search
    $('#btnAddCustomer').on('click', function() {
        $('#modalCustomerSearch').modal('show');
    });
    
    $('#btnSearchCustomer').on('click', function() {
        searchCustomers();
    });
    
    $('#customerSearchInput').on('keyup', function(e) {
        if (e.keyCode === 13) { // Enter key
            searchCustomers();
        }
    });
    
    // New customer form
    $('#btnShowNewCustomerForm').on('click', function() {
        $('#newCustomerForm').slideDown();
    });
    
    $('#formNewCustomer').on('submit', function(e) {
        e.preventDefault();
        addNewCustomer();
    });
    
    // Select customer
    $('#customerSearchResults').on('click', '.btn-select-customer', function() {
        const customerId = $(this).data('id');
        const customerName = $(this).data('name');
        selectCustomer(customerId, customerName);
    });
    
    // Discount
    $('#btnDiscount').on('click', function() {
        if (cart.length === 0) {
            toastr.warning('Giỏ hàng trống, không thể áp dụng giảm giá');
            return;
        }
        
        $('#discountSubtotal').text(formatCurrency(subtotal));
        $('#discountAmount').text(formatCurrency(0));
        $('#discountTotal').text(formatCurrency(subtotal));
        $('#modalDiscount').modal('show');
    });
    
    $('input[name="discountType"]').on('change', function() {
        const discountType = $('input[name="discountType"]:checked').val();
        if (discountType === 'percent') {
            $('#discountValueUnit').text('%');
            $('#discountValue').attr('max', 100);
        } else {
            $('#discountValueUnit').text('đ');
            $('#discountValue').removeAttr('max');
        }
        updateDiscountPreview();
    });
    
    $('#discountValue').on('input', function() {
        updateDiscountPreview();
    });
    
    $('#btnApplyDiscount').on('click', function() {
        applyDiscount();
    });
    
    // Order note
    $('#btnNote').on('click', function() {
        $('#orderNote').val(orderNote);
        $('#modalNote').modal('show');
    });
    
    $('#btnSaveNote').on('click', function() {
        orderNote = $('#orderNote').val();
        $('#modalNote').modal('hide');
        toastr.success('Đã lưu ghi chú đơn hàng');
    });
    
    // Payment
    $('#btnPayment').on('click', function() {
    if (cart.length === 0) {
        toastr.warning('Giỏ hàng trống, không thể thanh toán');
        return;
    }
    
    // Use AJAX to navigate to payment page
    $.ajax({
            url: '/pos/get-payment-url',
            type: 'GET',
            success: function(response) {
                if (response.success && response.url) {
                    window.location.href = response.url;
                } else {
                    toastr.error('Không thể chuyển đến trang thanh toán');
                }
            },
            error: function() {
                toastr.error('Có lỗi xảy ra khi chuyển đến trang thanh toán');
            }
        });
    });
        
    // Hold order
    $('#btnHoldOrder').on('click', function() {
        if (cart.length === 0) {
            toastr.warning('Giỏ hàng trống, không thể lưu tạm');
            return;
        }
        
        $('#holdOrderNote').val(orderNote);
        $('#modalHoldOrder').modal('show');
    });
    
    $('#btnSaveHoldOrder').on('click', function() {
        const note = $('#holdOrderNote').val();
        holdOrder(note);
    });
    
    // Open session
    $('#btnOpenSession').on('click', function() {
        alert('aaaaaaaaaaaaaaaaaa');
        $('#modalOpenSession').modal('show');
    });
    
    $('#btnConfirmOpenSession').on('click', function() {
        if (!$('#formOpenSession')[0].checkValidity()) {
            $('#formOpenSession')[0].reportValidity();
            return;
        }
        
        const cashAmount = $('#cashAmount').val();
        const note = $('#note').val();
        
        $.ajax({
            url: '<?= Url::to(['/pos/open-session']) ?>',
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
                    
                    // Reload page after 1 second
                    setTimeout(function() {
                        window.location.reload();
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
    
    // Functions
    
    // Load products
    function loadProducts() {
        $('#productGrid').html('<div class="col-12 text-center py-5"><i class="fas fa-spinner fa-spin fa-2x"></i><p class="mt-2">Đang tải sản phẩm...</p></div>');
        
        $.ajax({
            url: '<?= Url::to(['/pos/get-products']) ?>',
            type: 'GET',
            data: {
                categoryId: currentCategoryId,
                search: currentSearch,
                page: currentPage
            },
            success: function(response) {
                if (response.success) {
                    renderProducts(response.products, response.totalCount, response.currentPage, response.pages);
                } else {
                    toastr.error(response.message);
                }
            },
            error: function() {
                toastr.error('Có lỗi xảy ra khi tải sản phẩm.');
                $('#productGrid').html('<div class="col-12 text-center py-4"><i class="fas fa-exclamation-triangle fa-2x text-warning"></i><p class="mt-2">Không thể tải sản phẩm. Vui lòng thử lại sau.</p></div>');
            }
        });
    }
    
    // Render products
    function renderProducts(products, totalCount, currentPage, totalPages) {
        $('#productsCount').text(totalCount);
        
        if (products.length === 0) {
            $('#productGrid').html('<div class="col-12 text-center py-4"><i class="fas fa-search fa-2x text-muted"></i><p class="mt-2">Không tìm thấy sản phẩm nào</p></div>');
            renderPagination(currentPage, totalPages);
            return;
        }
        
        let html = '';
        
        if (viewMode === 'grid') {
            for (let i = 0; i < products.length; i++) {
                let product = products[i];
                let priceHtml = '';
                if (product.discount_price > 0) {
                    let discountPrice = formatCurrency(product.discount_price);
                    let originalPrice = formatCurrency(product.price);
                    priceHtml = '<span class="product-price">' + discountPrice + '</span>' +
                                '<span class="product-original-price">' + originalPrice + '</span>';
                } else {
                    let normalPrice = formatCurrency(product.price);
                    priceHtml = '<span class="product-price">' + normalPrice + '</span>';
                }
                
                html += '<div class="col-lg-3 col-md-4 col-sm-6">' +
                        '<div class="card product-card" data-id="' + product.id + '">' +
                        '<img src="' + product.image_url + '" class="card-img-top" alt="' + product.name + '">' +
                        '<div class="card-body p-2">' +
                        '<h5 class="card-title">' + product.name + '</h5>' +
                        '<p class="card-text">' +
                        priceHtml +
                        '<br>' +
                        '<small class="text-muted">Còn ' + product.in_stock + ' ' + product.unit + '</small>' +
                        '</p>' +
                        '<button class="btn btn-sm btn-primary btn-block btn-add-to-cart">' +
                        '<i class="fas fa-plus"></i> Thêm vào giỏ' +
                        '</button>' +
                        '</div>' +
                        '</div>' +
                        '</div>';
            }
        } else {
            // List view
            html = '<div class="col-12"><div class="list-group">';
            
            for (let i = 0; i < products.length; i++) {
                let product = products[i];
                let priceHtml = '';
                if (product.discount_price > 0) {
                    let discountPrice = formatCurrency(product.discount_price);
                    let originalPrice = formatCurrency(product.price);
                    priceHtml = '<span class="product-price">' + discountPrice + '</span>' +
                                '<span class="product-original-price">' + originalPrice + '</span>';
                } else {
                    let normalPrice = formatCurrency(product.price);
                    priceHtml = '<span class="product-price">' + normalPrice + '</span>';
                }
                
                html += '<div class="list-group-item list-group-item-action product-card" data-id="' + product.id + '">' +
                        '<div class="d-flex w-100 justify-content-between align-items-center">' +
                        '<div>' +
                        '<h5 class="mb-1">' + product.name + '</h5>' +
                        '<small>Mã: ' + product.code + ' | Còn ' + product.in_stock + ' ' + product.unit + '</small>' +
                        '</div>' +
                        '<div class="text-right">' +
                        '<div>' + priceHtml + '</div>' +
                        '<button class="btn btn-sm btn-primary mt-2 btn-add-to-cart">' +
                        '<i class="fas fa-plus"></i> Thêm vào giỏ' +
                        '</button>' +
                        '</div>' +
                        '</div>' +
                        '</div>';
            }
            
            html += '</div></div>';
        }
        
        $('#productGrid').html(html);
        renderPagination(currentPage, totalPages);
    }
    
    // Render pagination
    function renderPagination(currentPage, totalPages) {
        if (totalPages <= 1) {
            $('#pagination').html('');
            return;
        }
        
        let html = '';
        
        // Previous button
        html += '<li class="page-item ' + (currentPage === 1 ? 'disabled' : '') + '">' +
                '<a class="page-link" href="#" data-page="' + (currentPage - 1) + '" aria-label="Previous">' +
                '<span aria-hidden="true">&laquo;</span>' +
                '</a>' +
                '</li>';
        
        // Pages
        const startPage = Math.max(1, currentPage - 2);
        const endPage = Math.min(totalPages, startPage + 4);
        
        for (let i = startPage; i <= endPage; i++) {
            html += '<li class="page-item ' + (i === currentPage ? 'active' : '') + '">' +
                    '<a class="page-link" href="#" data-page="' + i + '">' + i + '</a>' +
                    '</li>';
        }
        
        // Next button
        html += '<li class="page-item ' + (currentPage === totalPages ? 'disabled' : '') + '">' +
                '<a class="page-link" href="#" data-page="' + (currentPage + 1) + '" aria-label="Next">' +
                '<span aria-hidden="true">&raquo;</span>' +
                '</a>' +
                '</li>';
        
        $('#pagination').html(html);
        
        // Page click
        $('#pagination').on('click', '.page-link', function(e) {
            e.preventDefault();
            const page = $(this).data('page');
            if (page < 1 || page > totalPages || page === currentPage) return;
            
            currentPage = page;
            loadProducts();
        });
    }
    
    // Show product details
    function showProductDetails(productId) {
        selectedProductId = productId;
        
        $('#modalProductDetails').modal('show');
        $('#productDetailsContent').html('<div class="text-center py-4"><i class="fas fa-spinner fa-spin fa-2x"></i><p class="mt-2">Đang tải thông tin sản phẩm...</p></div>');
        
        $.ajax({
            url: '<?= Url::to(['/pos/get-product-details']) ?>',
            type: 'GET',
            data: {
                id: productId
            },
            success: function(response) {
                if (response.success) {
                    renderProductDetails(response.product);
                } else {
                    toastr.error(response.message);
                    $('#modalProductDetails').modal('hide');
                }
            },
            error: function() {
                toastr.error('Có lỗi xảy ra khi tải thông tin sản phẩm.');
                $('#modalProductDetails').modal('hide');
            }
        });
    }
    
    // Render product details
    function renderProductDetails(product) {
        let priceHtml = '';
        if (product.discount_price > 0) {
            let discountPrice = formatCurrency(product.discount_price);
            let originalPrice = formatCurrency(product.price);
            priceHtml = '<span class="text-success font-weight-bold">' + discountPrice + '</span>' +
                        '<span class="text-danger text-strikethrough ml-2">' + originalPrice + '</span>';
        } else {
            let normalPrice = formatCurrency(product.price);
            priceHtml = '<span class="text-success font-weight-bold">' + normalPrice + '</span>';
        }
        
        let html = '<div class="row">' +
                '<div class="col-md-5">' +
                '<img src="' + product.image_url + '" class="img-fluid rounded" alt="' + product.name + '">' +
                '</div>' +
                '<div class="col-md-7">' +
                '<h5>' + product.name + '</h5>' +
                '<p class="text-muted">Mã: ' + product.code + '</p>' +
                
                '<div class="mb-3">' +
                priceHtml +
                '</div>' +
                
                '<table class="table table-sm table-bordered">' +
                '<tr>' +
                '<th>Danh mục</th>' +
                '<td>' + product.category + '</td>' +
                '</tr>' +
                '<tr>' +
                '<th>Đơn vị</th>' +
                '<td>' + product.unit + '</td>' +
                '</tr>' +
                '<tr>' +
                '<th>Tồn kho</th>' +
                '<td>' + product.in_stock + '</td>' +
                '</tr>' +
                '</table>' +
                
                '<div class="form-group">' +
                '<label for="productDetailsQuantity">Số lượng:</label>' +
                '<input type="number" class="form-control" id="productDetailsQuantity" value="1" min="1" max="' + product.in_stock + '">' +
                '</div>' +
                
                '<div class="product-description mt-3">' +
                '<h6>Mô tả sản phẩm</h6>' +
                '<p>' + (product.description || 'Không có mô tả') + '</p>' +
                '</div>' +
                '</div>' +
                '</div>';
        
        $('#productDetailsContent').html(html);
        $('#modalProductDetailsLabel').text(product.name);
    }
    
    // Add to cart
    function addToCart(productId, quantity) {
        $.ajax({
            url: '<?= Url::to(['/pos/add-to-cart']) ?>',
            type: 'POST',
            data: {
                productId: productId,
                quantity: quantity,
                _csrf: '<?= $csrfToken ?>'
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    updateCart(response);
                } else {
                    toastr.error(response.message);
                }
            },
            error: function() {
                toastr.error('Có lỗi xảy ra khi thêm vào giỏ hàng.');
            }
        });
    }
    
    // Update cart item quantity
    function updateCartItemQuantity(itemKey, quantity, isAbsolute = false) {
        $.ajax({
            url: '<?= Url::to(['/pos/update-cart']) ?>',
            type: 'POST',
            data: {
                itemKey: itemKey,
                quantity: isAbsolute ? quantity : null,
                _csrf: '<?= $csrfToken ?>'
            },
            success: function(response) {
                if (response.success) {
                    updateCart(response);
                } else {
                    toastr.error(response.message);
                }
            },
            error: function() {
                toastr.error('Có lỗi xảy ra khi cập nhật giỏ hàng.');
            }
        });
    }
    
    // Remove from cart
    function removeFromCart(itemKey) {
        $.ajax({
            url: '<?= Url::to(['/pos/remove-from-cart']) ?>',
            type: 'POST',
            data: {
                itemKey: itemKey,
                _csrf: '<?= $csrfToken ?>'
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    updateCart(response);
                } else {
                    toastr.error(response.message);
                }
            },
            error: function() {
                toastr.error('Có lỗi xảy ra khi xóa sản phẩm khỏi giỏ hàng.');
            }
        });
    }
    
    // Clear cart
    function clearCart() {
        $.ajax({
            url: '<?= Url::to(['/pos/clear-cart']) ?>',
            type: 'POST',
            data: {
                _csrf: '<?= $csrfToken ?>'
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    cart = [];
                    updateCartDisplay();
                    $('#btnPayment').prop('disabled', true);
                    selectedCustomerId = null;
                    selectedCustomerName = 'Khách lẻ';
                    $('#selectedCustomerName').text(selectedCustomerName);
                    orderNote = '';
                } else {
                    toastr.error(response.message);
                }
            },
            error: function() {
                toastr.error('Có lỗi xảy ra khi xóa giỏ hàng.');
            }
        });
    }
    
    // Load cart
    function loadCart() {
        $.ajax({
            url: '<?= Url::to(['/pos/get-cart']) ?>',
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    updateCart(response);
                    
                    // Update customer info if available
                    if (response.customer) {
                        selectedCustomerId = response.customer.id;
                        selectedCustomerName = response.customer.name;
                        $('#selectedCustomerName').text(selectedCustomerName);
                    }
                } else {
                    toastr.error(response.message);
                }
            },
            error: function() {
                toastr.error('Có lỗi xảy ra khi tải giỏ hàng.');
            }
        });
    }
    
    // Update cart from response
    function updateCart(response) {
        cart = response.cart;
        subtotal = response.subtotal;
        discount = response.discount;
        tax = response.tax;
        grandTotal = response.grandTotal;
        
        updateCartDisplay();
        
        // Enable/disable payment button
        $('#btnPayment').prop('disabled', cart.length === 0);
    }
    
    // Update cart display
    function updateCartDisplay() {
        if (Object.keys(cart).length === 0) {
            $('#cartEmpty').show();
            $('#cartItems').hide();
            $('#cartTotalItems').text(0);
            $('#cartSubtotal').text(formatCurrency(0));
            $('#cartDiscount').text(formatCurrency(0));
            $('#cartTax').text(formatCurrency(0));
            $('#cartTotal').text(formatCurrency(0));
            return;
        }
        
        $('#cartEmpty').hide();
        $('#cartItems').show();
        
        let totalItems = 0;
        let html = '';
        
        for (const itemKey in cart) {
            if (cart.hasOwnProperty(itemKey)) {
                const item = cart[itemKey];
                totalItems += item.quantity;
                
                html += '<tr class="cart-item-row" data-key="' + itemKey + '">' +
                        '<td>' +
                        '<div class="d-flex">' +
                        '<div class="cart-item-info">' +
                        '<div class="font-weight-bold">' + item.name + '</div>' +
                        '<small class="text-muted">' + item.code + '</small>' +
                        '</div>' +
                        '</div>' +
                        '</td>' +
                        '<td class="text-right">' + formatCurrency(item.price) + '</td>' +
                        '<td>' +
                        '<div class="input-group input-group-sm">' +
                        '<div class="input-group-prepend">' +
                        '<button class="btn btn-outline-secondary btn-quantity-minus" type="button">-</button>' +
                        '</div>' +
                        '<input type="text" class="form-control cart-quantity" value="' + item.quantity + '">' +
                        '<div class="input-group-append">' +
                        '<button class="btn btn-outline-secondary btn-quantity-plus" type="button">+</button>' +
                        '</div>' +
                        '</div>' +
                        '</td>' +
                        '<td class="text-right">' +
                        '<button class="btn btn-sm btn-outline-danger btn-remove-item">' +
                        '<i class="fas fa-trash"></i>' +
                        '</button>' +
                        '</td>' +
                        '</tr>';
            }
        }
        
        $('#cartItemsList').html(html);
        $('#cartTotalItems').text(totalItems);
        $('#cartSubtotal').text(formatCurrency(subtotal));
        $('#cartDiscount').text(formatCurrency(discount));
        $('#cartTax').text(formatCurrency(tax));
        $('#cartTotal').text(formatCurrency(grandTotal));
    }
    
    // Search customers
    function searchCustomers() {
        const search = $('#customerSearchInput').val();
        
        $('#customerSearchResults').html('<div class="text-center py-4"><i class="fas fa-spinner fa-spin fa-2x"></i><p class="mt-2">Đang tìm kiếm khách hàng...</p></div>');
        
        $.ajax({
            url: '<?= Url::to(['/pos/search-customers']) ?>',
            type: 'GET',
            data: {
                search: search
            },
            success: function(response) {
                if (response.success) {
                    renderCustomers(response.customers);
                } else {
                    toastr.error(response.message);
                }
            },
            error: function() {
                toastr.error('Có lỗi xảy ra khi tìm kiếm khách hàng.');
            }
        });
    }
    
    // Render customers
    function renderCustomers(customers) {
        if (customers.length === 0) {
            $('#customerSearchResults').html('<div class="alert alert-info">Không tìm thấy khách hàng nào.</div>');
            return;
        }
        
        let html = '<div class="list-group">';
        
        for (let i = 0; i < customers.length; i++) {
            let customer = customers[i];
            html += '<div class="list-group-item list-group-item-action">' +
                    '<div class="d-flex w-100 justify-content-between">' +
                    '<h5 class="mb-1">' + customer.name + '</h5>' +
                    '<button class="btn btn-sm btn-primary btn-select-customer" ' +
                    'data-id="' + customer.id + '" ' +
                    'data-name="' + customer.name + '">' +
                    '<i class="fas fa-check"></i> Chọn' +
                    '</button>' +
                    '</div>' +
                    '<p class="mb-1">' +
                    '<i class="fas fa-phone mr-1"></i> ' + (customer.phone || 'N/A') +
                    '<br>' +
                    '<i class="fas fa-envelope mr-1"></i> ' + (customer.email || 'N/A') +
                    '</p>' +
                    '<small>' +
                    '<span class="badge badge-info">Điểm: ' + (customer.points || 0) + '</span>' +
                    '<span class="badge badge-warning">Công nợ: ' + formatCurrency(customer.debt || 0) + '</span>' +
                    '</small>' +
                    '</div>';
        }
        
        html += '</div>';
        
        $('#customerSearchResults').html(html);
    }
    
    // Add new customer
    function addNewCustomer() {
        const formData = {
            name: $('#customerName').val(),
            phone: $('#customerPhone').val(),
            email: $('#customerEmail').val(),
            address: $('#customerAddress').val(),
            _csrf: '<?= $csrfToken ?>'
        };
        
        $.ajax({
            url: '<?= Url::to(['/pos/add-customer']) ?>',
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    
                    // Select the newly added customer
                    selectCustomer(response.customer.id, response.customer.name);
                    
                    // Reset form
                    $('#formNewCustomer')[0].reset();
                    $('#newCustomerForm').slideUp();
                } else {
                    toastr.error(response.message);
                }
            },
            error: function() {
                toastr.error('Có lỗi xảy ra khi thêm khách hàng mới.');
            }
        });
    }
    
    // Select customer
    function selectCustomer(customerId, customerName) {
        selectedCustomerId = customerId;
        selectedCustomerName = customerName;
        
        $('#selectedCustomerName').text(customerName);
        $('#modalCustomerSearch').modal('hide');
        
        toastr.success('Đã chọn khách hàng: ' + customerName);
    }
    
    // Update discount preview
    function updateDiscountPreview() {
        const discountType = $('input[name="discountType"]:checked').val();
        const discountValue = parseFloat($('#discountValue').val()) || 0;
        
        let discountAmount = 0;
        if (discountType === 'percent') {
            discountAmount = subtotal * (discountValue / 100);
        } else {
            discountAmount = discountValue;
        }
        
        // Limit discount to subtotal
        if (discountAmount > subtotal) {
            discountAmount = subtotal;
        }
        
        const total = subtotal - discountAmount;
        
        $('#discountAmount').text(formatCurrency(discountAmount));
        $('#discountTotal').text(formatCurrency(total));
    }
    
    // Apply discount
    function applyDiscount() {
        const discountType = $('input[name="discountType"]:checked').val();
        const discountValue = parseFloat($('#discountValue').val()) || 0;
        
        if (discountValue <= 0) {
            toastr.warning('Vui lòng nhập giá trị giảm giá hợp lệ.');
            return;
        }
        
        $.ajax({
            url: '<?= Url::to(['/pos/apply-discount']) ?>',
            type: 'POST',
            data: {
                type: discountType,
                value: discountValue,
                _csrf: '<?= $csrfToken ?>'
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    
                    // Update cart totals
                    subtotal = response.subtotal;
                    discount = response.discount;
                    grandTotal = response.grandTotal;
                    
                    $('#cartSubtotal').text(formatCurrency(subtotal));
                    $('#cartDiscount').text(formatCurrency(discount));
                    $('#cartTotal').text(formatCurrency(grandTotal));
                    
                    $('#modalDiscount').modal('hide');
                } else {
                    toastr.error(response.message);
                }
            },
            error: function() {
                toastr.error('Có lỗi xảy ra khi áp dụng giảm giá.');
            }
        });
    }
    
    // Hold order
    function holdOrder(note) {
        $.ajax({
            url: '<?= Url::to(['/pos/hold-order']) ?>',
            type: 'POST',
            data: {
                note: note,
                _csrf: '<?= $csrfToken ?>'
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    
                    // Clear cart display
                    cart = [];
                    updateCartDisplay();
                    $('#btnPayment').prop('disabled', true);
                    
                    // Reset customer and note
                    selectedCustomerId = null;
                    selectedCustomerName = 'Khách lẻ';
                    $('#selectedCustomerName').text(selectedCustomerName);
                    orderNote = '';
                    
                    $('#modalHoldOrder').modal('hide');
                } else {
                    toastr.error(response.message);
                }
            },
            error: function() {
                toastr.error('Có lỗi xảy ra khi lưu đơn hàng tạm.');
            }
        });
    }
});
JS;

$this->registerJs($js);
?>