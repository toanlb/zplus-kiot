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
                        url: 'pos/open-session',
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
// Khởi tạo POS khi trang đã tải xong
$(document).ready(function() {
    // Khởi tạo POS với CSRF token
    POS.init('<?= $csrfToken ?>');
});
</script>
<?php
// Tất cả JS script được chuyển sang file pos.js riêng biệt
?>