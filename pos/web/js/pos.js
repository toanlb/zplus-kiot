/**
 * POS main script
 * Xử lý chính cho giao diện POS
 */

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

// Initialize when document is ready
$(document).ready(function() {
    console.log('POS initialized');
    
    // Offline mode detection
    window.addEventListener('online', updateOnlineStatus);
    window.addEventListener('offline', updateOnlineStatus);
    updateOnlineStatus();
    
    // Event Listeners Setup - To be called in page-specific scripts
    setupEventListeners();
    
    // Initialize notification system
    initializeToastr();
});

/**
 * Update online/offline status
 */
function updateOnlineStatus() {
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
        syncOfflineData();
    }
}

/**
 * Sync offline data when coming back online
 */
function syncOfflineData() {
    // TODO: Implement syncing of offline data
    const pendingTransactions = localStorage.getItem('pos_pending_transactions');
    if (pendingTransactions) {
        console.log('Syncing pending transactions...');
        // Implement sync logic here
    }
}

/**
 * Setup event listeners for POS
 */
function setupEventListeners() {
    // Search product with delay
    let searchTimeout;
    $('#searchProduct').on('keyup', function() {
        clearTimeout(searchTimeout);
        const query = $(this).val();
        
        searchTimeout = setTimeout(function() {
            currentSearch = query;
            currentPage = 1;
            loadProducts();
        }, 500);
    });
    
    // Keyboard shortcuts
    $(document).on('keydown', function(e) {
        // F2 - Open new sale
        if (e.keyCode === 113) {
            e.preventDefault();
            clearCart();
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
}

/**
 * Initialize Toastr notification library
 */
function initializeToastr() {
    toastr.options = {
        closeButton: true,
        progressBar: true,
        positionClass: "toast-bottom-right",
        timeOut: 3000
    };
}

/**
 * Format currency display
 * @param {number} amount - Amount to format
 * @return {string} Formatted currency string
 */
function formatCurrency(amount) {
    return new Intl.NumberFormat('vi-VN', { 
        style: 'currency', 
        currency: 'VND',
        maximumFractionDigits: 0 
    }).format(amount);
}

/**
 * Load products from server
 */
function loadProducts() {
    $('#productGrid').html('<div class="col-12 text-center py-5"><i class="fas fa-spinner fa-spin fa-2x"></i><p class="mt-2">Đang tải sản phẩm...</p></div>');
    
    $.ajax({
        url: POS.urls.getProducts,
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
            
            // Show error and retry button
            $('#productGrid').html(`
                <div class="col-12 text-center py-4">
                    <i class="fas fa-exclamation-triangle fa-2x text-warning"></i>
                    <p class="mt-2">Không thể tải sản phẩm.</p>
                    <button class="btn btn-primary mt-2" onclick="loadProducts()">
                        <i class="fas fa-sync"></i> Thử lại
                    </button>
                </div>
            `);
            
            // Check if we have cached products
            const cachedProducts = sessionStorage.getItem('cached_products_' + currentCategoryId);
            if (cachedProducts) {
                try {
                    const data = JSON.parse(cachedProducts);
                    renderProducts(data.products, data.totalCount, data.currentPage, data.pages);
                    toastr.info('Đang hiển thị dữ liệu đã lưu trong bộ nhớ tạm.');
                } catch (e) {
                    console.error('Error loading cached products', e);
                }
            }
        }
    });
}

/**
 * Render products to grid
 */
function renderProducts(products, totalCount, currentPage, totalPages) {
    // Cache products for offline use
    sessionStorage.setItem('cached_products_' + currentCategoryId, JSON.stringify({
        products: products,
        totalCount: totalCount,
        currentPage: currentPage,
        pages: totalPages
    }));
    
    $('#productsCount').text(totalCount);
    
    if (products.length === 0) {
        $('#productGrid').html('<div class="col-12 text-center py-4"><i class="fas fa-search fa-2x text-muted"></i><p class="mt-2">Không tìm thấy sản phẩm nào</p></div>');
        renderPagination(currentPage, totalPages);
        return;
    }
    
    let html = '';
    
    if (viewMode === 'grid') {
        products.forEach(function(product) {
            let priceHtml = '';
            if (product.discount_price > 0) {
                priceHtml = `
                    <span class="product-price">${formatCurrency(product.discount_price)}</span>
                    <span class="product-original-price">${formatCurrency(product.price)}</span>
                `;
            } else {
                priceHtml = `<span class="product-price">${formatCurrency(product.price)}</span>`;
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
                        <img src="${product.image_url}" class="card-img-top" alt="${product.name}">
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
                    <span class="product-price">${formatCurrency(product.discount_price)}</span>
                    <span class="product-original-price">${formatCurrency(product.price)}</span>
                `;
            } else {
                priceHtml = `<span class="product-price">${formatCurrency(product.price)}</span>`;
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
    renderPagination(currentPage, totalPages);
}

/**
 * Render pagination controls
 */
function renderPagination(currentPage, totalPages) {
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
        
        currentPage = page;
        loadProducts();
    });
}

/**
 * Add product to cart
 */
function addToCart(productId, quantity) {
    $.ajax({
        url: POS.urls.addToCart,
        type: 'POST',
        data: {
            productId: productId,
            quantity: quantity,
            _csrf: POS.csrfToken
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

/**
 * Update cart item quantity
 */
function updateCartItemQuantity(itemKey, quantity, isAbsolute = false) {
    $.ajax({
        url: POS.urls.updateCart,
        type: 'POST',
        data: {
            itemKey: itemKey,
            quantity: isAbsolute ? quantity : null,
            _csrf: POS.csrfToken
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

/**
 * Remove item from cart
 */
function removeFromCart(itemKey) {
    $.ajax({
        url: POS.urls.removeFromCart,
        type: 'POST',
        data: {
            itemKey: itemKey,
            _csrf: POS.csrfToken
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

/**
 * Clear all items from cart
 */
function clearCart() {
    if (Object.keys(cart).length === 0) return;
    
    $.ajax({
        url: POS.urls.clearCart,
        type: 'POST',
        data: {
            _csrf: POS.csrfToken
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

/**
 * Load and update cart data
 */
function loadCart() {
    $.ajax({
        url: POS.urls.getCart,
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

/**
 * Update cart from server response
 */
function updateCart(response) {
    cart = response.cart;
    subtotal = response.subtotal;
    discount = response.discount;
    tax = response.tax;
    grandTotal = response.grandTotal;
    
    updateCartDisplay();
    
    // Enable/disable payment button
    $('#btnPayment').prop('disabled', Object.keys(cart).length === 0);
}

/**
 * Update cart display in UI
 */
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
    
    for (const [itemKey, item] of Object.entries(cart)) {
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
                <td class="text-right">${formatCurrency(item.price)}</td>
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
    
    $('#cartItemsList').html(html);
    $('#cartTotalItems').text(totalItems);
    $('#cartSubtotal').text(formatCurrency(subtotal));
    $('#cartDiscount').text(formatCurrency(discount));
    $('#cartTax').text(formatCurrency(tax));
    $('#cartTotal').text(formatCurrency(grandTotal));
}

/**
 * Show product details in modal
 */
function showProductDetails(productId) {
    selectedProductId = productId;
    
    $('#modalProductDetails').modal('show');
    $('#productDetailsContent').html('<div class="text-center py-4"><i class="fas fa-spinner fa-spin fa-2x"></i><p class="mt-2">Đang tải thông tin sản phẩm...</p></div>');
    
    $.ajax({
        url: POS.urls.getProductDetails,
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

/**
 * Render product details in modal
 */
function renderProductDetails(product) {
    let priceHtml = '';
    if (product.discount_price > 0) {
        priceHtml = `
            <span class="text-success font-weight-bold">${formatCurrency(product.discount_price)}</span>
            <span class="text-danger text-strikethrough ml-2">${formatCurrency(product.price)}</span>
        `;
    } else {
        priceHtml = `<span class="text-success font-weight-bold">${formatCurrency(product.price)}</span>`;
    }
    
    // Check if product is out of stock
    const isOutOfStock = product.in_stock <= 0;
    const stockStatus = isOutOfStock 
        ? '<span class="badge badge-danger">Hết hàng</span>' 
        : `<span class="badge badge-success">Còn hàng</span>`;
    
    let html = `
        <div class="row">
            <div class="col-md-5">
                <img src="${product.image_url}" class="img-fluid rounded" alt="${product.name}">
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
}

/**
 * Hold current order for later
 */
function holdOrder(note) {
    $.ajax({
        url: POS.urls.holdOrder,
        type: 'POST',
        data: {
            note: note,
            _csrf: POS.csrfToken
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
            
            // Store in local storage for offline mode
            if (!navigator.onLine) {
                const heldOrder = {
                    cart: cart,
                    customer: {
                        id: selectedCustomerId,
                        name: selectedCustomerName
                    },
                    note: note,
                    timestamp: new Date().getTime()
                };
                
                let heldOrders = JSON.parse(localStorage.getItem('pos_held_orders') || '[]');
                heldOrders.push(heldOrder);
                localStorage.setItem('pos_held_orders', JSON.stringify(heldOrders));
                
                toastr.info('Đơn hàng đã được lưu tạm trong chế độ ngoại tuyến.');
                
                // Clear cart
                cart = [];
                updateCartDisplay();
                $('#btnPayment').prop('disabled', true);
                
                // Reset customer and note
                selectedCustomerId = null;
                selectedCustomerName = 'Khách lẻ';
                $('#selectedCustomerName').text(selectedCustomerName);
                orderNote = '';
                
                $('#modalHoldOrder').modal('hide');
            }
        }
    });
}

/**
 * Apply discount to current order
 */
function applyDiscount(type, value) {
    $.ajax({
        url: POS.urls.applyDiscount,
        type: 'POST',
        data: {
            type: type,
            value: value,
            _csrf: POS.csrfToken
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

// Export functions for use in other scripts
window.POS = {
    loadProducts: loadProducts,
    addToCart: addToCart,
    updateCartItemQuantity: updateCartItemQuantity,
    removeFromCart: removeFromCart,
    clearCart: clearCart,
    loadCart: loadCart,
    showProductDetails: showProductDetails,
    holdOrder: holdOrder,
    applyDiscount: applyDiscount,
    formatCurrency: formatCurrency
};