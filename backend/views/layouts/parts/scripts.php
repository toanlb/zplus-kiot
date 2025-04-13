<!-- Các script bổ sung cho AdminLTE -->
<script>
$(document).ready(function() {
    // Fix cho sidebar menu khi giao diện bị vỡ
    if (typeof $.fn.Treeview !== 'undefined') {
        $('[data-widget="treeview"]').Treeview('init');
    }
    
    // Khởi tạo các thành phần UI khác
    if (typeof $.fn.Dropdown !== 'undefined') {
        $('[data-toggle="dropdown"]').Dropdown('init');
    }
    
    if (typeof $.fn.PushMenu !== 'undefined') {
        $('[data-widget="pushmenu"]').PushMenu('init');
    }
});
</script>