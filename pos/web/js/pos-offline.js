/**
 * POS offline support script
 * Xử lý chế độ offline cho POS
 */

(function() {
    // Check if browser supports Service Workers
    if ('serviceWorker' in navigator) {
        // Register service worker for offline support
        registerServiceWorker();
        
        // Setup offline event handlers
        setupOfflineEvents();
        
        console.log('POS offline support initialized');
    } else {
        console.warn('Service Workers not supported. Offline functionality disabled.');
    }
    
    /**
     * Register service worker
     */
    function registerServiceWorker() {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register('/service-worker.js')
                .then(registration => {
                    console.log('ServiceWorker registration successful with scope: ', registration.scope);
                })
                .catch(err => {
                    console.log('ServiceWorker registration failed: ', err);
                });
        });
    }
    
    /**
     * Setup offline event handlers
     */
    function setupOfflineEvents() {
        // Setup online/offline event listeners
        window.addEventListener('online', handleOnlineStatus);
        window.addEventListener('offline', handleOfflineStatus);
        
        // Check initial status
        if (!navigator.onLine) {
            handleOfflineStatus();
        }
    }
    
    /**
     * Handle when browser goes online
     */
    function handleOnlineStatus() {
        console.log('Application is online. Syncing pending data...');
        
        // Update UI
        $('.network-status').removeClass('text-danger').addClass('text-success')
            .html('<i class="fas fa-wifi"></i> Trực tuyến');
        
        // Show notification
        toastr.success('Kết nối internet đã được khôi phục.');
        
        // Sync pending data
        syncPendingData();
    }
    
    /**
     * Handle when browser goes offline
     */
    function handleOfflineStatus() {
        console.log('Application is offline. Switching to offline mode...');
        
        // Update UI
        $('.network-status').removeClass('text-success').addClass('text-danger')
            .html('<i class="fas fa-wifi-slash"></i> Ngoại tuyến');
        
        // Show notification
        toastr.warning('Mất kết nối internet. Đang chuyển sang chế độ ngoại tuyến.', null, {
            timeOut: 0,
            extendedTimeOut: 0,
            closeButton: true
        });
        
        // Load cached data
        loadCachedData();
    }
    
    /**
     * Sync pending data when coming back online
     */
    function syncPendingData() {
        // Synchronize data in this order:
        // 1. Pending cart operations
        // 2. Held orders
        // 3. Completed transactions
        
        syncPendingCartOperations()
            .then(() => syncHeldOrders())
            .then(() => syncCompletedTransactions())
            .then(() => {
                console.log('All pending data synchronized successfully.');
                toastr.success('Đã đồng bộ dữ liệu thành công.');
            })
            .catch(error => {
                console.error('Error synchronizing data:', error);
                toastr.error('Có lỗi xảy ra khi đồng bộ dữ liệu. Một số dữ liệu có thể chưa được đồng bộ.');
            });
    }
    
    /**
     * Sync pending cart operations
     */
    function syncPendingCartOperations() {
        return new Promise((resolve, reject) => {
            const pendingOperations = JSON.parse(localStorage.getItem('offline_cart_operations') || '[]');
            
            if (pendingOperations.length === 0) {
                resolve();
                return;
            }
            
            console.log(`Syncing ${pendingOperations.length} pending cart operations...`);
            
            // Process operations in sequence
            processOperationsSequentially(pendingOperations)
                .then(() => {
                    localStorage.removeItem('offline_cart_operations');
                    resolve();
                })
                .catch(error => {
                    console.error('Error syncing cart operations:', error);
                    reject(error);
                });
        });
    }
    
    /**
     * Sync held orders from offline mode
     */
    function syncHeldOrders() {
        return new Promise((resolve, reject) => {
            const offlineHeldOrders = JSON.parse(localStorage.getItem('offline_held_orders') || '[]');
            
            if (offlineHeldOrders.length === 0) {
                resolve();
                return;
            }
            
            console.log(`Syncing ${offlineHeldOrders.length} offline held orders...`);
            
            // Process each held order
            const promises = offlineHeldOrders.map(order => {
                return new Promise((resolveOrder, rejectOrder) => {
                    $.ajax({
                        url: POS.urls.holdOrder,
                        type: 'POST',
                        data: {
                            cart: JSON.stringify(order.cart),
                            customer_id: order.customer.id,
                            note: order.note,
                            _csrf: POS.csrfToken
                        },
                        success: function() {
                            resolveOrder();
                        },
                        error: function(xhr) {
                            // If server returned 409 Conflict, it means the order already exists
                            if (xhr.status === 409) {
                                resolveOrder();
                            } else {
                                rejectOrder(new Error('Failed to sync held order'));
                            }
                        }
                    });
                });
            });
            
            Promise.all(promises)
                .then(() => {
                    localStorage.removeItem('offline_held_orders');
                    resolve();
                })
                .catch(error => {
                    console.error('Error syncing held orders:', error);
                    reject(error);
                });
        });
    }
    
    /**
     * Sync completed transactions from offline mode
     */
    function syncCompletedTransactions() {
        return new Promise((resolve, reject) => {
            const offlineTransactions = JSON.parse(localStorage.getItem('offline_completed_transactions') || '[]');
            
            if (offlineTransactions.length === 0) {
                resolve();
                return;
            }
            
            console.log(`Syncing ${offlineTransactions.length} offline transactions...`);
            
            // Process each transaction
            const promises = offlineTransactions.map(transaction => {
                return new Promise((resolveTransaction, rejectTransaction) => {
                    $.ajax({
                        url: POS.urls.syncOfflineTransaction,
                        type: 'POST',
                        data: {
                            transaction: JSON.stringify(transaction),
                            _csrf: POS.csrfToken
                        },
                        success: function() {
                            resolveTransaction();
                        },
                        error: function() {
                            rejectTransaction(new Error('Failed to sync transaction'));
                        }
                    });
                });
            });
            
            Promise.all(promises)
                .then(() => {
                    localStorage.removeItem('offline_completed_transactions');
                    resolve();
                })
                .catch(error => {
                    console.error('Error syncing transactions:', error);
                    reject(error);
                });
        });
    }
    
    /**
     * Process operations sequentially to avoid race conditions
     */
    function processOperationsSequentially(operations) {
        return operations.reduce((promise, operation) => {
            return promise.then(() => {
                return processOperation(operation);
            });
        }, Promise.resolve());
    }
    
    /**
     * Process a single cart operation
     */
    function processOperation(operation) {
        return new Promise((resolve, reject) => {
            let url, data;
            
            switch (operation.type) {
                case 'add':
                    url = POS.urls.addToCart;
                    data = {
                        productId: operation.productId,
                        quantity: operation.quantity,
                        _csrf: POS.csrfToken
                    };
                    break;
                
                case 'update':
                    url = POS.urls.updateCart;
                    data = {
                        itemKey: operation.itemKey,
                        quantity: operation.quantity,
                        _csrf: POS.csrfToken
                    };
                    break;
                
                case 'remove':
                    url = POS.urls.removeFromCart;
                    data = {
                        itemKey: operation.itemKey,
                        _csrf: POS.csrfToken
                    };
                    break;
                
                case 'clear':
                    url = POS.urls.clearCart;
                    data = {
                        _csrf: POS.csrfToken
                    };
                    break;
                
                default:
                    reject(new Error(`Unknown operation type: ${operation.type}`));
                    return;
            }
            
            $.ajax({
                url: url,
                type: 'POST',
                data: data,
                success: resolve,
                error: reject
            });
        });
    }
    
    /**
     * Load cached data when offline
     */
    function loadCachedData() {
        // Load cached products
        const cachedProducts = sessionStorage.getItem('cached_products_0');
        if (cachedProducts) {
            try {
                const data = JSON.parse(cachedProducts);
                if (typeof POS !== 'undefined' && POS.renderProducts) {
                    POS.renderProducts(data.products, data.totalCount, data.currentPage, data.pages);
                    console.log('Loaded cached products.');
                }
            } catch (e) {
                console.error('Error loading cached products:', e);
            }
        }
        
        // Load cached cart
        const cachedCart = sessionStorage.getItem('cached_cart');
        if (cachedCart) {
            try {
                const data = JSON.parse(cachedCart);
                if (typeof POS !== 'undefined' && POS.updateCart) {
                    POS.updateCart(data);
                    console.log('Loaded cached cart.');
                }
            } catch (e) {
                console.error('Error loading cached cart:', e);
            }
        }
    }
    
    /**
     * Store cart operation for offline sync
     */
    window.storeCartOperation = function(operation) {
        if (!navigator.onLine) {
            let operations = JSON.parse(localStorage.getItem('offline_cart_operations') || '[]');
            operations.push({
                ...operation,
                timestamp: new Date().getTime()
            });
            localStorage.setItem('offline_cart_operations', JSON.stringify(operations));
            console.log('Stored offline cart operation:', operation);
        }
    };
    
    /**
     * Store held order for offline sync
     */
    window.storeHeldOrder = function(order) {
        if (!navigator.onLine) {
            let heldOrders = JSON.parse(localStorage.getItem('offline_held_orders') || '[]');
            heldOrders.push({
                ...order,
                timestamp: new Date().getTime()
            });
            localStorage.setItem('offline_held_orders', JSON.stringify(heldOrders));
            console.log('Stored offline held order.');
        }
    };
    
    /**
     * Store completed transaction for offline sync
     */
    window.storeCompletedTransaction = function(transaction) {
        if (!navigator.onLine) {
            let transactions = JSON.parse(localStorage.getItem('offline_completed_transactions') || '[]');
            transactions.push({
                ...transaction,
                timestamp: new Date().getTime(),
                offline_id: 'OFFLINE-' + new Date().getTime()
            });
            localStorage.setItem('offline_completed_transactions', JSON.stringify(transactions));
            console.log('Stored offline completed transaction.');
        }
    };
    
    // Expose offline utilities to global scope
    window.PosOffline = {
        syncPendingData: syncPendingData,
        storeCartOperation: window.storeCartOperation,
        storeHeldOrder: window.storeHeldOrder,
        storeCompletedTransaction: window.storeCompletedTransaction
    };
})();