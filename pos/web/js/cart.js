/**
 * POS cart script
 * Xử lý giỏ hàng trong POS
 */

$(document).ready(function() {
    console.log('Cart module initialized');
    
    // Cart quantity change
    $('#cartItemsList').on('click', '.btn-quantity-minus', function() {
        const itemKey = $(this).closest('tr').data('key');
        const currentQuantity = parseInt($(this).closest('tr').find('.cart-quantity').val());
        
        if (currentQuantity <= 1) {
            // If quantity is 1, ask for confirmation before removing
            if (confirm('Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng?')) {
                POS.removeFromCart(itemKey);
            }
        } else {
            // Just decrease the quantity
            POS.updateCartItemQuantity(itemKey, currentQuantity - 1, true);
        }
    });
    
    $('#cartItemsList').on('click', '.btn-quantity-plus', function() {
        const itemKey = $(this).closest('tr').data('key');
        const currentQuantity = parseInt($(this).closest('tr').find('.cart-quantity').val());
        POS.updateCartItemQuantity(itemKey, currentQuantity + 1, true);
    });
    
    $('#cartItemsList').on('change', '.cart-quantity', function() {
        const itemKey = $(this).closest('tr').data('key');
        let quantity = parseInt($(this).val()) || 1;
        
        // Ensure quantity is at least 1
        if (quantity < 1) {
            quantity = 1;
            $(this).val(1);
        }
        
        POS.updateCartItemQuantity(itemKey, quantity, true);
    });
    
    // Remove from cart
    $('#cartItemsList').on('click', '.btn-remove-item', function() {
        const itemKey = $(this).closest('tr').data('key');
        
        if (confirm('Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng?')) {
            POS.removeFromCart(itemKey);
        }
    });
    
    // Clear cart
    $('#btnClearCart').on('click', function() {
        if (Object.keys(cart).length === 0) return;
        
        if (confirm('Bạn có chắc chắn muốn xóa tất cả sản phẩm trong giỏ hàng?')) {
            POS.clearCart();
        }
    });
    
    // Hold order
    $('#btnHoldOrder').on('click', function() {
        if (Object.keys(cart).length === 0) {
            toastr.warning('Giỏ hàng trống, không thể lưu tạm');
            return;
        }
        
        $('#holdOrderNote').val(orderNote);
        $('#modalHoldOrder').modal('show');
    });
    
    $('#btnSaveHoldOrder').on('click', function() {
        const note = $('#holdOrderNote').val();
        POS.holdOrder(note);
    });
    
    // Discount
    $('#btnDiscount').on('click', function() {
        if (Object.keys(cart).length === 0) {
            toastr.warning('Giỏ hàng trống, không thể áp dụng giảm giá');
            return;
        }
        
        // Reset discount form
        $('#discountValue').val('');
        $('input[name="discountType"][value="percent"]').prop('checked', true);
        $('#discountValueUnit').text('%');
        
        // Show current values in preview
        $('#discountSubtotal').text(POS.formatCurrency(subtotal));
        $('#discountAmount').text(POS.formatCurrency(0));
        $('#discountTotal').text(POS.formatCurrency(subtotal));
        
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
        const discountType = $('input[name="discountType"]:checked').val();
        const discountValue = parseFloat($('#discountValue').val()) || 0;
        
        if (discountValue <= 0) {
            toastr.warning('Vui lòng nhập giá trị giảm giá hợp lệ.');
            return;
        }
        
        // Additional validation for percent discount
        if (discountType === 'percent' && discountValue > 100) {
            toastr.warning('Giảm giá theo phần trăm không thể vượt quá 100%.');
            return;
        }
        
        POS.applyDiscount(discountType, discountValue);
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
        if (Object.keys(cart).length === 0) {
            toastr.warning('Giỏ hàng trống, không thể thanh toán');
            return;
        }
        
        // Redirect to payment page
        window.location.href = POS.urls.payment;
    });
    
    /**
     * Update discount preview in the discount modal
     */
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
        
        $('#discountAmount').text(POS.formatCurrency(discountAmount));
        $('#discountTotal').text(POS.formatCurrency(total));
    }
    
    /**
     * Handle keyboard events for cart
     */
    function setupCartKeyboardEvents() {
        $(document).on('keydown', function(e) {
            // Alt + C - Clear cart
            if (e.altKey && e.keyCode === 67) {
                e.preventDefault();
                $('#btnClearCart').click();
            }
            
            // Alt + H - Hold order
            if (e.altKey && e.keyCode === 72) {
                e.preventDefault();
                $('#btnHoldOrder').click();
            }
            
            // Alt + D - Discount
            if (e.altKey && e.keyCode === 68) {
                e.preventDefault();
                $('#btnDiscount').click();
            }
            
            // Alt + N - Note
            if (e.altKey && e.keyCode === 78) {
                e.preventDefault();
                $('#btnNote').click();
            }
            
            // Alt + P - Payment
            if (e.altKey && e.keyCode === 80) {
                e.preventDefault();
                $('#btnPayment').click();
            }
        });
    }
    
    // Initialize keyboard events
    setupCartKeyboardEvents();
    
    // Offline support for cart operations
    if ('serviceWorker' in navigator) {
        // Check for offline support
        window.addEventListener('online', syncOfflineCart);
        window.addEventListener('offline', function() {
            console.log('Offline mode activated for cart.');
        });
    }
    
    /**
     * Sync offline cart operations when coming back online
     */
    function syncOfflineCart() {
        if (localStorage.getItem('offline_cart_operations')) {
            console.log('Syncing offline cart operations...');
            
            try {
                const operations = JSON.parse(localStorage.getItem('offline_cart_operations'));
                
                if (operations && operations.length > 0) {
                    // Process each operation in sequence
                    processOfflineOperations(operations).then(() => {
                        localStorage.removeItem('offline_cart_operations');
                        toastr.success('Đã đồng bộ thay đổi giỏ hàng khi ngoại tuyến.');
                    }).catch(error => {
                        console.error('Error syncing offline cart operations:', error);
                        toastr.error('Có lỗi xảy ra khi đồng bộ giỏ hàng.');
                    });
                }
            } catch (e) {
                console.error('Error parsing offline cart operations:', e);
            }
        }
    }
    
    /**
     * Process offline operations in sequence
     */
    async function processOfflineOperations(operations) {
        for (const op of operations) {
            try {
                await processOperation(op);
            } catch (error) {
                console.error('Error processing operation:', op, error);
            }
        }
    }
    
    /**
     * Process a single offline operation
     */
    function processOperation(operation) {
        return new Promise((resolve, reject) => {
            switch (operation.type) {
                case 'add':
                    $.ajax({
                        url: POS.urls.addToCart,
                        type: 'POST',
                        data: {
                            productId: operation.productId,
                            quantity: operation.quantity,
                            _csrf: POS.csrfToken
                        },
                        success: resolve,
                        error: reject
                    });
                    break;
                    
                case 'update':
                    $.ajax({
                        url: POS.urls.updateCart,
                        type: 'POST',
                        data: {
                            itemKey: operation.itemKey,
                            quantity: operation.quantity,
                            _csrf: POS.csrfToken
                        },
                        success: resolve,
                        error: reject
                    });
                    break;
                    
                case 'remove':
                    $.ajax({
                        url: POS.urls.removeFromCart,
                        type: 'POST',
                        data: {
                            itemKey: operation.itemKey,
                            _csrf: POS.csrfToken
                        },
                        success: resolve,
                        error: reject
                    });
                    break;
                    
                case 'clear':
                    $.ajax({
                        url: POS.urls.clearCart,
                        type: 'POST',
                        data: {
                            _csrf: POS.csrfToken
                        },
                        success: resolve,
                        error: reject
                    });
                    break;
                    
                default:
                    reject(new Error('Unknown operation type: ' + operation.type));
            }
        });
    }
});