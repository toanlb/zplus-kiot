/**
 * POS main script
 * Xử lý chính cho giao diện POS
 */

// Khai báo namespace POS để chứa tất cả các biến và hàm
window.POS = {
    // Variables for cart and products
    currentCategoryId: 0,
    currentPage: 1,
    currentSearch: '',
    productsPerPage: 24,
    viewMode: 'grid',
    selectedProductId: null,
    cart: {},
    subtotal: 0,
    discount: 0,
    tax: 0,
    grandTotal: 0,
    selectedCustomerId: null,
    selectedCustomerName: 'Khách lẻ',
    orderNote: '',
    csrfToken: '',

    /**
     * Initialize POS system
     * @param {string} csrfToken - CSRF token for AJAX requests
     */
    init: function(csrfToken) {
        this.csrfToken = csrfToken;
        console.log('POS initialized with token:', csrfToken);
        
        // Initialize features
        this.setupEventListeners();
        this.initializeToastr();
        
        // Load initial data
        this.loadProducts();
        this.loadCart();
        
        // Setup online/offline detection
        window.addEventListener('online', this.updateOnlineStatus);
        window.addEventListener('offline', this.updateOnlineStatus);
        this.updateOnlineStatus();
    },
    
    /**
     * Setup event listeners for POS UI
     */
    setupEventListeners: function() {
        const self = this;
        
        // Category click
        $('#categoryList').on('click', 'a', function(e) {
            e.preventDefault();
            $('#categoryList a').removeClass('active');
            $(this).addClass('active');
            self.currentCategoryId = $(this).data('id');
            self.currentPage = 1;
            self.loadProducts();
        });
        
        // Search product
        let searchTimeout;
        $('#searchProduct').on('keyup', function(e) {
            clearTimeout(searchTimeout);
            const query = $(this).val();
            
            if (e.keyCode === 13) { // Enter key
                self.currentSearch = query;
                self.currentPage = 1;
                self.loadProducts();
                return;
            }
            
            searchTimeout = setTimeout(function() {
                self.currentSearch = query;
                self.currentPage = 1;
                self.loadProducts();
            }, 500);
        });
        
        // View mode buttons
        $('#btnGridView').on('click', function() {
            self.viewMode = 'grid';
            self.loadProducts();
        });
        
        $('#btnListView').on('click', function() {
            self.viewMode = 'list';
            self.loadProducts();
        });
        
        // Product click - show details
        $('#productGrid').on('click', '.product-card', function() {
            const productId = $(this).data('id');
            self.showProductDetails(productId);
        });
        
        // Product add to cart
        $('#productGrid').on('click', '.btn-add-to-cart', function(e) {
            e.stopPropagation();
            const productId = $(this).closest('.product-card').data('id');
            self.addToCart(productId, 1);
        });
        
        // Modal add to cart
        $('#btnAddToCartFromModal').on('click', function() {
            if (self.selectedProductId) {
                const quantity = parseInt($('#productDetailsQuantity').val()) || 1;
                self.addToCart(self.selectedProductId, quantity);
                $('#modalProductDetails').modal('hide');
            }
        });
        
        // Cart quantity change
        $('#cartItemsList').on('click', '.btn-quantity-minus', function() {
            const itemKey = $(this).closest('tr').data('key');
            self.updateCartItemQuantity(itemKey, -1);
        });
        
        $('#cartItemsList').on('click', '.btn-quantity-plus', function() {
            const itemKey = $(this).closest('tr').data('key');
            self.updateCartItemQuantity(itemKey, 1);
        });
        
        $('#cartItemsList').on('change', '.cart-quantity', function() {
            const itemKey = $(this).closest('tr').data('key');
            const quantity = parseInt($(this).val()) || 1;
            self.updateCartItemQuantity(itemKey, quantity, true);
        });
        
        // Remove from cart
        $('#cartItemsList').on('click', '.btn-remove-item', function() {
            const itemKey = $(this).closest('tr').data('key');
            self.removeFromCart(itemKey);
        });
        
        // Clear cart
        $('#btnClearCart').on('click', function() {
            if (Object.keys(self.cart).length === 0) return;
            
            if (confirm('Bạn có chắc chắn muốn xóa tất cả sản phẩm trong giỏ hàng?')) {
                self.clearCart();
            }
        });
        
        // Customer search
        $('#btnAddCustomer').on('click', function() {
            $('#modalCustomerSearch').modal('show');
        });
        
        $('#btnSearchCustomer').on('click', function() {
            self.searchCustomers();
        });
        
        $('#customerSearchInput').on('keyup', function(e) {
            if (e.keyCode === 13) { // Enter key
                self.searchCustomers();
            }
        });
        
        // New customer form
        $('#btnShowNewCustomerForm').on('click', function() {
            $('#newCustomerForm').slideDown();
        });
        
        $('#formNewCustomer').on('submit', function(e) {
            e.preventDefault();
            self.addNewCustomer();
        });
        
        // Select customer
        $('#customerSearchResults').on('click', '.btn-select-customer', function() {
            const customerId = $(this).data('id');
            const customerName = $(this).data('name');
            self.selectCustomer(customerId, customerName);
        });
        
        // Discount
        $('#btnDiscount').on('click', function() {
            if (Object.keys(self.cart).length === 0) {
                toastr.warning('Giỏ hàng trống, không thể áp dụng giảm giá');
                return;
            }
            
            $('#discountSubtotal').text(self.formatCurrency(self.subtotal));
            $('#discountAmount').text(self.formatCurrency(0));
            $('#discountTotal').text(self.formatCurrency(self.subtotal));
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
            self.updateDiscountPreview();
        });
        
        $('#discountValue').on('input', function() {
            self.updateDiscountPreview();
        });
        
        $('#btnApplyDiscount').on('click', function() {
            const discountType = $('input[name="discountType"]:checked').val();
            const discountValue = parseFloat($('#discountValue').val()) || 0;
            
            if (discountValue <= 0) {
                toastr.warning('Vui lòng nhập giá trị giảm giá hợp lệ.');
                return;
            }
            
            self.applyDiscount(discountType, discountValue);
        });
        
        // Order note
        $('#btnNote').on('click', function() {
            $('#orderNote').val(self.orderNote);
            $('#modalNote').modal('show');
        });
        
        $('#btnSaveNote').on('click', function() {
            self.orderNote = $('#orderNote').val();
            $('#modalNote').modal('hide');
            toastr.success('Đã lưu ghi chú đơn hàng');
        });
        
        // Payment
        $('#btnPayment').on('click', function() {
            if (Object.keys(self.cart).length === 0) {
                toastr.warning('Giỏ hàng trống, không thể thanh toán');
                return;
            }
            
            // Use AJAX to navigate to payment page
            var url = $('#get-payment-url').data('url');
            $.ajax({
                url: url,
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
            if (Object.keys(self.cart).length === 0) {
                toastr.warning('Giỏ hàng trống, không thể lưu tạm');
                return;
            }
            
            $('#holdOrderNote').val(self.orderNote);
            $('#modalHoldOrder').modal('show');
        });
        
        $('#btnSaveHoldOrder').on('click', function() {
            const note = $('#holdOrderNote').val();
            self.holdOrder(note);
        });
        
        // Keyboard shortcuts
        $(document).on('keydown', function(e) {
            // F2 - Open new sale
            if (e.keyCode === 113) {
                e.preventDefault();
                self.clearCart();
            }
            
            // F3 - Focus on search
            if (e.keyCode === 114) {
                e.preventDefault();
                $('#searchProduct').focus();
            }
            
            // F4 - Go to payment
            if (e.keyCode === 115 && !$('#btnPayment').prop('disabled')) {
                e.preventDefault();
                $('#btnPayment').click();
            }
            
            // ESC - Close modal if open
            if (e.keyCode === 27) {
                $('.modal').modal('hide');
            }
        });
    },
    
    /**
     * Initialize Toastr notification library
     */
    initializeToastr: function() {
        toastr.options = {
            closeButton: true,
            progressBar: true,
            positionClass: "toast-bottom-right",
            timeOut: 3000
        };
    },
    
    /**
     * Update online/offline status
     */
    updateOnlineStatus: function() {
        const condition = navigator.onLine ? "online" : "offline";
        console.log('Connection status: ' + condition);
        
        if (condition === 'offline') {
            toastr.warning('Mất kết nối internet. Đang chuyển sang chế độ ngoại tuyến.');
            $('.network-status').removeClass('text-success').addClass('text-danger')
                .html('<i class="fas fa-wifi-slash"></i> Ngoại tuyến');
        } else {
            toastr.success('Đã kết nối internet.');
            $('.network-status').removeClass('text-danger').addClass('text-success')
                .html('<i class="fas fa-wifi"></i> Trực tuyến');
            
            // Sync any pending data
            POS.syncOfflineData();
        }
    },
    
    /**
     * Sync offline data when coming back online
     */
    syncOfflineData: function() {
        // TODO: Implement syncing of offline data
        const pendingTransactions = localStorage.getItem('pos_pending_transactions');
        if (pendingTransactions) {
            console.log('Syncing pending transactions...');
            // Implement sync logic here
        }
    },
    
    /**
     * Format currency display
     * @param {number} amount - Amount to format
     * @return {string} Formatted currency string
     */
    formatCurrency: function(amount) {
        return new Intl.NumberFormat('vi-VN', { 
            style: 'currency', 
            currency: 'VND',
            maximumFractionDigits: 0 
        }).format(amount);
    },
    
    /**
     * Load products from server
     */
    loadProducts: function() {
        const self = this;
        $('#productGrid').html('<div class="col-12 text-center py-5"><i class="fas fa-spinner fa-spin fa-2x"></i><p class="mt-2">Đang tải sản phẩm...</p></div>');
        
        $.ajax({
            url: 'pos/get-products',
            type: 'GET',
            data: {
                categoryId: self.currentCategoryId,
                search: self.currentSearch,
                page: self.currentPage
            },
            success: function(response) {
                if (response.success) {
                    self.renderProducts(response.products, response.totalCount, response.currentPage, response.pages);
                } else {
                    toastr.error(response.message);
                }
            },
            error: function() {
                toastr.error('Có lỗi xảy ra khi tải sản phẩm.');
                
                // Show error and retry button
                $('#productGrid').html(`
                    <div class="col-12 text-center py-4">
                        <i class="fas fa-exclamation-triangle fa-2x text-warning"></i>
                        <p class="mt-2">Không thể tải sản phẩm.</p>
                        <button class="btn btn-primary mt-2" onclick="POS.loadProducts()">
                            <i class="fas fa-sync"></i> Thử lại
                        </button>
                    </div>
                `);
                
                // Check if we have cached products
                const cachedProducts = sessionStorage.getItem('cached_products_' + self.currentCategoryId);
                if (cachedProducts) {
                    try {
                        const data = JSON.parse(cachedProducts);
                        self.renderProducts(data.products, data.totalCount, data.currentPage, data.pages);
                        toastr.info('Đang hiển thị dữ liệu đã lưu trong bộ nhớ tạm.');
                    } catch (e) {
                        console.error('Error loading cached products', e);
                    }
                }
            }
        });
    },
    
    /**
     * Render products to grid
     */
    renderProducts: function(products, totalCount, currentPage, totalPages) {
        const self = this;
        
        // Cache products for offline use
        sessionStorage.setItem('cached_products_' + self.currentCategoryId, JSON.stringify({
            products: products,
            totalCount: totalCount,
            currentPage: currentPage,
            pages: totalPages
        }));
        
        $('#productsCount').text(totalCount);
        
        if (products.length === 0) {
            $('#productGrid').html('<div class="col-12 text-center py-4"><i class="fas fa-search fa-2x text-muted"></i><p class="mt-2">Không tìm thấy sản phẩm nào</p></div>');
            self.renderPagination(currentPage, totalPages);
            return;
        }
        
        let html = '';
        
        if (self.viewMode === 'grid') {
            products.forEach(function(product) {
                let priceHtml = '';
                if (product.discount_price > 0) {
                    priceHtml = `
                        <span class="product-price">${self.formatCurrency(product.discount_price)}</span>
                        <span class="product-original-price">${self.formatCurrency(product.price)}</span>
                    `;
                } else {
                    priceHtml = `<span class="product-price">${self.formatCurrency(product.price)}</span>`;
                }
                
                // Check if product is out of stock
                const isOutOfStock = product.in_stock <= 0;
                const outOfStockClass = isOutOfStock ? 'bg-light text-muted' : '';
                const outOfStockBadge = isOutOfStock ? '<span class="badge badge-danger position-absolute" style="top: 5px; right: 5px;">Hết hàng</span>' : '';
                const disabledBtn = isOutOfStock ? 'disabled' : '';
                
                html += `
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="card product-card ${outOfStockClass}" data-id="${product.id}">
                            ${outOfStockBadge}
                            <div class="card-body p-2">
                                <h5 class="card-title">${product.name}</h5>
                                <p class="card-text">
                                    ${priceHtml}
                                    <br>
                                    <small class="text-muted">Còn ${product.in_stock} ${product.unit}</small>
                                </p>
                                <button class="btn btn-sm btn-primary btn-block btn-add-to-cart" ${disabledBtn}>
                                    <i class="fas fa-plus"></i> Thêm vào giỏ
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            });
        } else {
            // List view
            html = '<div class="col-12"><div class="list-group">';
            
            products.forEach(function(product) {
                let priceHtml = '';
                if (product.discount_price > 0) {
                    priceHtml = `
                        <span class="product-price">${self.formatCurrency(product.discount_price)}</span>
                        <span class="product-original-price">${self.formatCurrency(product.price)}</span>
                    `;
                } else {
                    priceHtml = `<span class="product-price">${self.formatCurrency(product.price)}</span>`;
                }
                
                // Check if product is out of stock
                const isOutOfStock = product.in_stock <= 0;
                const outOfStockClass = isOutOfStock ? 'bg-light text-muted' : '';
                const disabledBtn = isOutOfStock ? 'disabled' : '';
                
                html += `
                    <div class="list-group-item list-group-item-action product-card ${outOfStockClass}" data-id="${product.id}">
                        <div class="d-flex w-100 justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-1">${product.name} ${isOutOfStock ? '<span class="badge badge-danger">Hết hàng</span>' : ''}</h5>
                                <small>Mã: ${product.code} | Còn ${product.in_stock} ${product.unit}</small>
                            </div>
                            <div class="text-right">
                                <div>${priceHtml}</div>
                                <button class="btn btn-sm btn-primary mt-2 btn-add-to-cart" ${disabledBtn}>
                                    <i class="fas fa-plus"></i> Thêm vào giỏ
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            });
            
            html += '</div></div>';
        }
        
        $('#productGrid').html(html);
        self.renderPagination(currentPage, totalPages);
    },
    
    /**
     * Render pagination controls
     */
    renderPagination: function(currentPage, totalPages) {
        const self = this;
        
        if (totalPages <= 1) {
            $('#pagination').html('');
            return;
        }
        
        let html = '';
        
        // Previous button
        html += `
            <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                <a class="page-link" href="#" data-page="${currentPage - 1}" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
        `;
        
        // Pages
        const startPage = Math.max(1, currentPage - 2);
        const endPage = Math.min(totalPages, startPage + 4);
        
        for (let i = startPage; i <= endPage; i++) {
            html += `
                <li class="page-item ${i === currentPage ? 'active' : ''}">
                    <a class="page-link" href="#" data-page="${i}">${i}</a>
                </li>
            `;
        }
        
        // Next button
        html += `
            <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
                <a class="page-link" href="#" data-page="${currentPage + 1}" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        `;
        
        $('#pagination').html(html);
        
        // Unbind previous events to prevent duplication
        $('#pagination').off('click', '.page-link');
        
        // Page click
        $('#pagination').on('click', '.page-link', function(e) {
            e.preventDefault();
            const page = $(this).data('page');
            if (page < 1 || page > totalPages || page === currentPage) return;
            
            self.currentPage = page;
            self.loadProducts();
        });
    },
    
    /**
     * Show product details in modal
     */
    showProductDetails: function(productId) {
        const self = this;
        self.selectedProductId = productId;
        
        $('#modalProductDetails').modal('show');
        $('#productDetailsContent').html('<div class="text-center py-4"><i class="fas fa-spinner fa-spin fa-2x"></i><p class="mt-2">Đang tải thông tin sản phẩm...</p></div>');
        
        $.ajax({
            url: 'pos/get-product-details',
            type: 'GET',
            data: {
                id: productId
            },
            success: function(response) {
                if (response.success) {
                    self.renderProductDetails(response.product);
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
    },
    
    /**
     * Render product details in modal
     */
    renderProductDetails: function(product) {
        const self = this;
        
        let priceHtml = '';
        if (product.discount_price > 0) {
            priceHtml = `
                <span class="text-success font-weight-bold">${self.formatCurrency(product.discount_price)}</span>
                <span class="text-danger text-strikethrough ml-2">${self.formatCurrency(product.price)}</span>
            `;
        } else {
            priceHtml = `<span class="text-success font-weight-bold">${self.formatCurrency(product.price)}</span>`;
        }
        
        // Check if product is out of stock
        const isOutOfStock = product.in_stock <= 0;
        const stockStatus = isOutOfStock 
            ? '<span class="badge badge-danger">Hết hàng</span>' 
            : `<span class="badge badge-success">Còn hàng</span>`;
        
        let html = `
            <div class="row">
                <div class="col-md-5">
                    <img src="${product.image_url || '/images/no-image.png'}" class="img-fluid rounded" alt="${product.name}">
                </div>
                <div class="col-md-7">
                    <h5>${product.name}</h5>
                    <p class="text-muted">Mã: ${product.code}</p>
                    
                    <div class="mb-3">
                        ${priceHtml}
                    </div>
                    
                    <table class="table table-sm table-bordered">
                        <tr>
                            <th>Danh mục</th>
                            <td>${product.category}</td>
                        </tr>
                        <tr>
                            <th>Đơn vị</th>
                            <td>${product.unit}</td>
                        </tr>
                        <tr>
                            <th>Tồn kho</th>
                            <td>${product.in_stock} ${stockStatus}</td>
                        </tr>
                    </table>
                    
                    <div class="form-group">
                        <label for="productDetailsQuantity">Số lượng:</label>
                        <input type="number" class="form-control" id="productDetailsQuantity" value="1" min="1" max="${product.in_stock}" ${isOutOfStock ? 'disabled' : ''}>
                    </div>
                    
                    <div class="product-description mt-3">
                        <h6>Mô tả sản phẩm</h6>
                        <p>${product.description || 'Không có mô tả'}</p>
                    </div>
                </div>
            </div>
        `;
        
        $('#productDetailsContent').html(html);
        $('#modalProductDetailsLabel').text(product.name);
        
        // Disable add to cart button if out of stock
        $('#btnAddToCartFromModal').prop('disabled', isOutOfStock);
    },
    
    /**
     * Add product to cart
     */
    addToCart: function(productId, quantity) {
        const self = this;
        
        $.ajax({
            url: 'pos/add-to-cart',
            type: 'POST',
            data: {
                productId: productId,
                quantity: quantity,
                _csrf: self.csrfToken
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    self.updateCart(response);
                } else {
                    toastr.error(response.message);
                }
            },
            error: function() {
                toastr.error('Có lỗi xảy ra khi thêm vào giỏ hàng.');
            }
        });
    },
    
    /**
     * Update cart item quantity
     */
    updateCartItemQuantity: function(itemKey, quantity, isAbsolute = false) {
        const self = this;
        
        $.ajax({
            url: 'pos/update-cart',
            type: 'POST',
            data: {
                itemKey: itemKey,
                quantity: quantity,
                setQuantity: isAbsolute ? 1 : 0,
                _csrf: self.csrfToken
            },
            success: function(response) {
                if (response.success) {
                    self.updateCart(response);
                } else {
                    toastr.error(response.message);
                }
            },
            error: function() {
                toastr.error('Có lỗi xảy ra khi cập nhật giỏ hàng.');
            }
        });
    },
    
    /**
     * Remove item from cart
     */
    removeFromCart: function(itemKey) {
        const self = this;
        
        $.ajax({
            url: 'pos/remove-from-cart',
            type: 'POST',
            data: {
                itemKey: itemKey,
                _csrf: self.csrfToken
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    self.updateCart(response);
                } else {
                    toastr.error(response.message);
                }
            },
            error: function() {
                toastr.error('Có lỗi xảy ra khi xóa sản phẩm khỏi giỏ hàng.');
            }
        });
    },
    
    /**
     * Clear all items from cart
     */
    clearCart: function() {
        const self = this;
        
        if (Object.keys(self.cart).length === 0) return;
        
        $.ajax({
            url: 'pos/clear-cart',
            type: 'POST',
            data: {
                _csrf: self.csrfToken
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    self.cart = {};
                    self.updateCartDisplay();
                    $('#btnPayment').prop('disabled', true);
                    self.selectedCustomerId = null;
                    self.selectedCustomerName = 'Khách lẻ';
                    $('#selectedCustomerName').text(self.selectedCustomerName);
                    self.orderNote = '';
                } else {
                    toastr.error(response.message);
                }
            },
            error: function() {
                toastr.error('Có lỗi xảy ra khi xóa giỏ hàng.');
            }
        });
    },
    
    /**
     * Load and update cart data
     */
    loadCart: function() {
        const self = this;
        
        $.ajax({
            url: 'pos/get-cart',
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    self.updateCart(response);
                    
                    // Update customer info if available
                    if (response.customer) {
                        self.selectedCustomerId = response.customer.id;
                        self.selectedCustomerName = response.customer.name;
                        $('#selectedCustomerName').text(self.selectedCustomerName);
                    }
                } else {
                    toastr.error(response.message);
                }
            },
            error: function() {
                toastr.error('Có lỗi xảy ra khi tải giỏ hàng.');
            }
        });
    },
    
    /**
     * Update cart from server response
     */
    updateCart: function(response) {
        const self = this;
        
        self.cart = response.cart;
        self.subtotal = response.subtotal;
        self.discount = response.discount;
        self.tax = response.tax;
        self.grandTotal = response.grandTotal;
        
        self.updateCartDisplay();
        
        // Enable/disable payment button
        $('#btnPayment').prop('disabled', Object.keys(self.cart).length === 0);
    },
    
    /**
     * Update cart display in UI
     */
    updateCartDisplay: function() {
        const self = this;
        
        if (Object.keys(self.cart).length === 0) {
            $('#cartEmpty').show();
            $('#cartItems').hide();
            $('#cartTotalItems').text(0);
            $('#cartSubtotal').text(self.formatCurrency(0));
            $('#cartDiscount').text(self.formatCurrency(0));
            $('#cartTax').text(self.formatCurrency(0));
            $('#cartTotal').text(self.formatCurrency(0));
            return;
        }
        
        $('#cartEmpty').hide();
        $('#cartItems').show();
        
        let totalItems = 0;
        let html = '';
        
        for (const itemKey in self.cart) {
            if (self.cart.hasOwnProperty(itemKey)) {
                const item = self.cart[itemKey];
                totalItems += item.quantity;
                
                html += `
                    <tr class="cart-item-row" data-key="${itemKey}">
                        <td>
                            <div class="d-flex">
                                <div class="cart-item-info">
                                    <div class="font-weight-bold">${item.name}</div>
                                    <small class="text-muted">${item.code}</small>
                                </div>
                            </div>
                        </td>
                        <td class="text-right">${self.formatCurrency(item.price)}</td>
                        <td>
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <button class="btn btn-outline-secondary btn-quantity-minus" type="button">-</button>
                                </div>
                                <input type="text" class="form-control cart-quantity" value="${item.quantity}">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary btn-quantity-plus" type="button">+</button>
                                </div>
                            </div>
                        </td>
                        <td class="text-right">
                            <button class="btn btn-sm btn-outline-danger btn-remove-item">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
            }
        }
        
        $('#cartItemsList').html(html);
        $('#cartTotalItems').text(totalItems);
        $('#cartSubtotal').text(self.formatCurrency(self.subtotal));
        $('#cartDiscount').text(self.formatCurrency(self.discount));
        $('#cartTax').text(self.formatCurrency(self.tax));
        $('#cartTotal').text(self.formatCurrency(self.grandTotal));
    },
    
    /**
     * Search customers
     */
    searchCustomers: function() {
        const search = $('#customerSearchInput').val();
        
        $('#customerSearchResults').html('<div class="text-center py-4"><i class="fas fa-spinner fa-spin fa-2x"></i><p class="mt-2">Đang tìm kiếm khách hàng...</p></div>');
        
        $.ajax({
            url: 'pos/search-customers',
            type: 'GET',
            data: {
                search: search
            },
            success: function(response) {
                if (response.success) {
                    POS.renderCustomers(response.customers);
                } else {
                    toastr.error(response.message);
                }
            },
            error: function() {
                toastr.error('Có lỗi xảy ra khi tìm kiếm khách hàng.');
            }
        });
    },
    
    /**
     * Render customers in search results
     */
    renderCustomers: function(customers) {
        const self = this;
        
        if (customers.length === 0) {
            $('#customerSearchResults').html('<div class="alert alert-info">Không tìm thấy khách hàng nào.</div>');
            return;
        }
        
        let html = '<div class="list-group">';
        
        customers.forEach(function(customer) {
            html += `
                <div class="list-group-item list-group-item-action">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1">${customer.name}</h5>
                        <button class="btn btn-sm btn-primary btn-select-customer" 
                            data-id="${customer.id}" 
                            data-name="${customer.name}">
                            <i class="fas fa-check"></i> Chọn
                        </button>
                    </div>
                    <p class="mb-1">
                        <i class="fas fa-phone mr-1"></i> ${customer.phone || 'N/A'}
                        <br>
                        <i class="fas fa-envelope mr-1"></i> ${customer.email || 'N/A'}
                    </p>
                    <small>
                        <span class="badge badge-info">Điểm: ${customer.points || 0}</span>
                        <span class="badge badge-warning">Công nợ: ${self.formatCurrency(customer.debt || 0)}</span>
                    </small>
                </div>
            `;
        });
        
        html += '</div>';
        
        $('#customerSearchResults').html(html);
    },
    
    /**
     * Add new customer
     */
    addNewCustomer: function() {
        const self = this;
        
        const formData = {
            name: $('#customerName').val(),
            phone: $('#customerPhone').val(),
            email: $('#customerEmail').val(),
            address: $('#customerAddress').val(),
            _csrf: self.csrfToken
        };
        
        $.ajax({
            url: 'pos/add-customer',
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    
                    // Select the newly added customer
                    self.selectCustomer(response.customer.id, response.customer.name);
                    
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
    },
    
    /**
     * Select customer
     */
    selectCustomer: function(customerId, customerName) {
        const self = this;
        
        self.selectedCustomerId = customerId;
        self.selectedCustomerName = customerName;
        
        $('#selectedCustomerName').text(customerName);
        $('#modalCustomerSearch').modal('hide');
        
        toastr.success('Đã chọn khách hàng: ' + customerName);
    },
    
    /**
     * Update discount preview
     */
    updateDiscountPreview: function() {
        const self = this;
        
        const discountType = $('input[name="discountType"]:checked').val();
        const discountValue = parseFloat($('#discountValue').val()) || 0;
        
        let discountAmount = 0;
        if (discountType === 'percent') {
            discountAmount = self.subtotal * (discountValue / 100);
        } else {
            discountAmount = discountValue;
        }
        
        // Limit discount to subtotal
        if (discountAmount > self.subtotal) {
            discountAmount = self.subtotal;
        }
        
        const total = self.subtotal - discountAmount;
        
        $('#discountAmount').text(self.formatCurrency(discountAmount));
        $('#discountTotal').text(self.formatCurrency(total));
    },
    
    /**
     * Apply discount
     */
    applyDiscount: function(type, value) {
        const self = this;
        
        $.ajax({
            url: 'pos/apply-discount',
            type: 'POST',
            data: {
                type: type,
                value: value,
                _csrf: self.csrfToken
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    
                    // Update cart totals
                    self.subtotal = response.subtotal;
                    self.discount = response.discount;
                    self.tax = response.tax;
                    self.grandTotal = response.grandTotal;
                    
                    $('#cartSubtotal').text(self.formatCurrency(self.subtotal));
                    $('#cartDiscount').text(self.formatCurrency(self.discount));
                    $('#cartTax').text(self.formatCurrency(self.tax));
                    $('#cartTotal').text(self.formatCurrency(self.grandTotal));
                    
                    $('#modalDiscount').modal('hide');
                } else {
                    toastr.error(response.message);
                }
            },
            error: function() {
                toastr.error('Có lỗi xảy ra khi áp dụng giảm giá.');
            }
        });
    },
    
    /**
     * Hold order for later
     */
    holdOrder: function(note) {
        const self = this;
        
        $.ajax({
            url: 'pos/hold-order',
            type: 'POST',
            data: {
                note: note,
                _csrf: self.csrfToken
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    
                    // Clear cart display
                    self.cart = {};
                    self.updateCartDisplay();
                    $('#btnPayment').prop('disabled', true);
                    
                    // Reset customer and note
                    self.selectedCustomerId = null;
                    self.selectedCustomerName = 'Khách lẻ';
                    $('#selectedCustomerName').text(self.selectedCustomerName);
                    self.orderNote = '';
                    
                    $('#modalHoldOrder').modal('hide');
                } else {
                    toastr.error(response.message);
                }
            },
            error: function() {
                toastr.error('Có lỗi xảy ra khi lưu đơn hàng tạm.');
                
                // Store in local storage for offline mode
                if (!navigator.onLine) {
                    const heldOrder = {
                        cart: self.cart,
                        customer: {
                            id: self.selectedCustomerId,
                            name: self.selectedCustomerName
                        },
                        note: note,
                        timestamp: new Date().getTime()
                    };
                    
                    let heldOrders = JSON.parse(localStorage.getItem('pos_held_orders') || '[]');
                    heldOrders.push(heldOrder);
                    localStorage.setItem('pos_held_orders', JSON.stringify(heldOrders));
                    
                    toastr.info('Đơn hàng đã được lưu tạm trong chế độ ngoại tuyến.');
                    
                    // Clear cart
                    self.cart = {};
                    self.updateCartDisplay();
                    $('#btnPayment').prop('disabled', true);
                    
                    // Reset customer and note
                    self.selectedCustomerId = null;
                    self.selectedCustomerName = 'Khách lẻ';
                    $('#selectedCustomerName').text(self.selectedCustomerName);
                    self.orderNote = '';
                    
                    $('#modalHoldOrder').modal('hide');
                }
            }
        });
    }
};