<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */

$this->title = 'Báo Cáo Giao Dịch';
$this->params['breadcrumbs'][] = ['label' => 'Lịch Sử Giao Dịch', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

// Đăng ký JavaScript cho biểu đồ
$this->registerJsFile('https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js', [
    'depends' => [\yii\web\JqueryAsset::class],
]);

// Đăng ký Datepicker JS và CSS
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js', ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css');

// Script để tạo biểu đồ
$script = <<<JS
$(document).ready(function() {
    // Khởi tạo datepicker
    $('.datepicker').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        todayHighlight: true
    });
    
    // Biểu đồ mặc định
    loadChartData('daily');
    
    // Xử lý sự kiện thay đổi loại báo cáo
    $('#report-type').change(function() {
        loadChartData($(this).val());
    });
    
    // Xử lý sự kiện tìm kiếm
    $('#search-report').click(function() {
        loadChartData($('#report-type').val());
    });
    
    // Hàm tải dữ liệu biểu đồ
    function loadChartData(type) {
        var dateFrom = $('#date-from').val();
        var dateTo = $('#date-to').val();
        
        // Hiển thị loading
        $('#chart-container').html('<div class="text-center p-5"><i class="fas fa-spinner fa-spin fa-3x"></i><p class="mt-2">Đang tải dữ liệu...</p></div>');
        
        // Gửi AJAX request
        $.ajax({
            url: 'get-report-data', // Đường dẫn đến action xử lý dữ liệu
            data: {
                type: type,
                date_from: dateFrom,
                date_to: dateTo
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    createCharts(response.data, type);
                    updateSummary(response.data);
                } else {
                    $('#chart-container').html('<div class="alert alert-danger">Không thể tải dữ liệu: ' + (response.message || 'Lỗi không xác định') + '</div>');
                }
            },
            error: function() {
                $('#chart-container').html('<div class="alert alert-danger">Đã xảy ra lỗi khi tải dữ liệu</div>');
            }
        });
    }
    
    // Tạo biểu đồ
    function createCharts(data, type) {
        $('#chart-container').html('<div class="row"><div class="col-md-12"><canvas id="sales-chart" style="height: 300px;"></canvas></div></div><div class="row mt-4"><div class="col-md-6"><canvas id="payment-methods-chart" style="height: 300px;"></canvas></div><div class="col-md-6"><canvas id="transaction-count-chart" style="height: 300px;"></canvas></div></div>');
        
        var labels = [];
        var salesData = [];
        var cashData = [];
        var cardData = [];
        var ewalletData = [];
        var bankData = [];
        var transactionCounts = [];
        
        // Xử lý dữ liệu
        $.each(data, function(index, item) {
            labels.push(item.date);
            salesData.push(parseFloat(item.total_sales || 0));
            cashData.push(parseFloat(item.cash_sales || 0));
            cardData.push(parseFloat(item.card_sales || 0));
            ewalletData.push(parseFloat(item.ewallet_sales || 0));
            bankData.push(parseFloat(item.bank_sales || 0));
            transactionCounts.push(parseInt(item.transaction_count || 0));
        });
        
        // Biểu đồ doanh số
        var salesChart = new Chart(document.getElementById('sales-chart').getContext('2d'), {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Tổng doanh số',
                    data: salesData,
                    borderColor: 'rgb(54, 162, 235)',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    fill: true,
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Biểu đồ doanh số theo ' + (type === 'daily' ? 'ngày' : (type === 'weekly' ? 'tuần' : 'tháng'))
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Doanh số: ' + new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(context.parsed.y);
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND', notation: 'compact' }).format(value);
                            }
                        }
                    }
                }
            }
        });
        
        // Biểu đồ phương thức thanh toán
        var paymentMethodsChart = new Chart(document.getElementById('payment-methods-chart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Tiền mặt',
                        data: cashData,
                        backgroundColor: 'rgba(75, 192, 192, 0.6)',
                        borderColor: 'rgb(75, 192, 192)',
                        borderWidth: 1
                    },
                    {
                        label: 'Thẻ',
                        data: cardData,
                        backgroundColor: 'rgba(153, 102, 255, 0.6)',
                        borderColor: 'rgb(153, 102, 255)',
                        borderWidth: 1
                    },
                    {
                        label: 'Ví điện tử',
                        data: ewalletData,
                        backgroundColor: 'rgba(255, 159, 64, 0.6)',
                        borderColor: 'rgb(255, 159, 64)',
                        borderWidth: 1
                    },
                    {
                        label: 'Chuyển khoản',
                        data: bankData,
                        backgroundColor: 'rgba(255, 99, 132, 0.6)',
                        borderColor: 'rgb(255, 99, 132)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Phương thức thanh toán theo ' + (type === 'daily' ? 'ngày' : (type === 'weekly' ? 'tuần' : 'tháng'))
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(context.parsed.y);
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        stacked: true,
                        ticks: {
                            callback: function(value) {
                                return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND', notation: 'compact' }).format(value);
                            }
                        }
                    },
                    x: {
                        stacked: true
                    }
                }
            }
        });
        
        // Biểu đồ số lượng giao dịch
        var transactionCountChart = new Chart(document.getElementById('transaction-count-chart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Số lượng giao dịch',
                    data: transactionCounts,
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgb(54, 162, 235)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Số lượng giao dịch theo ' + (type === 'daily' ? 'ngày' : (type === 'weekly' ? 'tuần' : 'tháng'))
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    }
    
    // Cập nhật tóm tắt báo cáo
    function updateSummary(data) {
        var totalSales = 0;
        var totalCash = 0;
        var totalCard = 0;
        var totalEwallet = 0;
        var totalBank = 0;
        var totalTransactions = 0;
        
        $.each(data, function(index, item) {
            totalSales += parseFloat(item.total_sales || 0);
            totalCash += parseFloat(item.cash_sales || 0);
            totalCard += parseFloat(item.card_sales || 0);
            totalEwallet += parseFloat(item.ewallet_sales || 0);
            totalBank += parseFloat(item.bank_sales || 0);
            totalTransactions += parseInt(item.transaction_count || 0);
        });
        
        $('#total-sales').text(new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(totalSales));
        $('#total-cash').text(new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(totalCash));
        $('#total-card').text(new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(totalCard));
        $('#total-ewallet').text(new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(totalEwallet));
        $('#total-bank').text(new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(totalBank));
        $('#total-transactions').text(totalTransactions);
    }
});
JS;

$this->registerJs($script);
?>

<div class="transaction-history-report">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-chart-line"></i> <?= Html::encode($this->title) ?>
            </h3>
            <div class="card-tools">
                <?= Html::a('<i class="fas fa-arrow-left"></i> Quay lại', ['index'], ['class' => 'btn btn-default btn-sm']) ?>
            </div>
        </div>
        
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Bộ lọc báo cáo</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="report-type">Loại báo cáo</label>
                                        <select id="report-type" class="form-control">
                                            <option value="daily">Theo ngày</option>
                                            <option value="weekly">Theo tuần</option>
                                            <option value="monthly">Theo tháng</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="date-from">Từ ngày</label>
                                        <input type="text" id="date-from" class="form-control datepicker" value="<?= date('Y-m-d', strtotime('-30 days')) ?>" placeholder="Chọn ngày bắt đầu...">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="date-to">Đến ngày</label>
                                        <input type="text" id="date-to" class="form-control datepicker" value="<?= date('Y-m-d') ?>" placeholder="Chọn ngày kết thúc...">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                        <button id="search-report" class="btn btn-primary btn-block">
                                            <i class="fas fa-search"></i> Xem báo cáo
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card card-info card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Tổng quan</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="info-box bg-primary">
                                        <span class="info-box-icon"><i class="fas fa-shopping-cart"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Tổng doanh số</span>
                                            <span class="info-box-number" id="total-sales">0</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="info-box bg-success">
                                        <span class="info-box-icon"><i class="fas fa-money-bill-wave"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Tiền mặt</span>
                                            <span class="info-box-number" id="total-cash">0</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="info-box bg-warning">
                                        <span class="info-box-icon"><i class="fas fa-credit-card"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Thẻ</span>
                                            <span class="info-box-number" id="total-card">0</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-4">
                                    <div class="info-box bg-info">
                                        <span class="info-box-icon"><i class="fas fa-wallet"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Ví điện tử</span>
                                            <span class="info-box-number" id="total-ewallet">0</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="info-box bg-secondary">
                                        <span class="info-box-icon"><i class="fas fa-university"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Chuyển khoản</span>
                                            <span class="info-box-number" id="total-bank">0</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="info-box bg-danger">
                                        <span class="info-box-icon"><i class="fas fa-receipt"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Số giao dịch</span>
                                            <span class="info-box-number" id="total-transactions">0</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-success card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Biểu đồ báo cáo</h3>
                        </div>
                        <div class="card-body">
                            <div id="chart-container">
                                <div class="text-center p-5">
                                    <i class="fas fa-spinner fa-spin fa-3x"></i>
                                    <p class="mt-2">Đang tải dữ liệu...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>