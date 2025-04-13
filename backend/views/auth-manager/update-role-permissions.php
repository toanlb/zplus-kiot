<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Cập nhật quyền cho vai trò: ' . $role->name;
$this->params['breadcrumbs'][] = ['label' => 'Quản lý phân quyền', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $role->name, 'url' => ['view-role', 'name' => $role->name]];
$this->params['breadcrumbs'][] = 'Cập nhật quyền';
?>

<div class="rbac-update-role-permissions">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="card-body">
            <?php $form = ActiveForm::begin(); ?>
            
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title">Quản lý sản phẩm</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php
                        $productPermissions = ['viewProduct', 'createProduct', 'updateProduct', 'deleteProduct'];
                        foreach ($allPermissions as $permission) {
                            if (in_array($permission->name, $productPermissions)) {
                                $checked = isset($permissions[$permission->name]) ? 'checked' : '';
                                echo '<div class="col-md-3">';
                                echo '<div class="form-check">';
                                echo '<input class="form-check-input" type="checkbox" name="permissions[]" value="' . $permission->name . '" id="' . $permission->name . '" ' . $checked . '>';
                                echo '<label class="form-check-label" for="' . $permission->name . '">' . $permission->description . '</label>';
                                echo '</div>';
                                echo '</div>';
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
            
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title">Quản lý danh mục</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php
                        $categoryPermissions = ['viewCategory', 'createCategory', 'updateCategory', 'deleteCategory'];
                        foreach ($allPermissions as $permission) {
                            if (in_array($permission->name, $categoryPermissions)) {
                                $checked = isset($permissions[$permission->name]) ? 'checked' : '';
                                echo '<div class="col-md-3">';
                                echo '<div class="form-check">';
                                echo '<input class="form-check-input" type="checkbox" name="permissions[]" value="' . $permission->name . '" id="' . $permission->name . '" ' . $checked . '>';
                                echo '<label class="form-check-label" for="' . $permission->name . '">' . $permission->description . '</label>';
                                echo '</div>';
                                echo '</div>';
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
            
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title">Quản lý đơn vị tính</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php
                        $unitPermissions = ['viewUnit', 'createUnit', 'updateUnit', 'deleteUnit'];
                        foreach ($allPermissions as $permission) {
                            if (in_array($permission->name, $unitPermissions)) {
                                $checked = isset($permissions[$permission->name]) ? 'checked' : '';
                                echo '<div class="col-md-3">';
                                echo '<div class="form-check">';
                                echo '<input class="form-check-input" type="checkbox" name="permissions[]" value="' . $permission->name . '" id="' . $permission->name . '" ' . $checked . '>';
                                echo '<label class="form-check-label" for="' . $permission->name . '">' . $permission->description . '</label>';
                                echo '</div>';
                                echo '</div>';
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
            
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title">Quản lý khách hàng</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php
                        $customerPermissions = ['viewCustomer', 'createCustomer', 'updateCustomer', 'deleteCustomer'];
                        foreach ($allPermissions as $permission) {
                            if (in_array($permission->name, $customerPermissions)) {
                                $checked = isset($permissions[$permission->name]) ? 'checked' : '';
                                echo '<div class="col-md-3">';
                                echo '<div class="form-check">';
                                echo '<input class="form-check-input" type="checkbox" name="permissions[]" value="' . $permission->name . '" id="' . $permission->name . '" ' . $checked . '>';
                                echo '<label class="form-check-label" for="' . $permission->name . '">' . $permission->description . '</label>';
                                echo '</div>';
                                echo '</div>';
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
            
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title">Quản lý đơn hàng</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php
                        $orderPermissions = ['viewOrder', 'createOrder', 'updateOrder', 'deleteOrder', 'updateOwnOrder', 'accessPos'];
                        foreach ($allPermissions as $permission) {
                            if (in_array($permission->name, $orderPermissions)) {
                                $checked = isset($permissions[$permission->name]) ? 'checked' : '';
                                echo '<div class="col-md-3">';
                                echo '<div class="form-check">';
                                echo '<input class="form-check-input" type="checkbox" name="permissions[]" value="' . $permission->name . '" id="' . $permission->name . '" ' . $checked . '>';
                                echo '<label class="form-check-label" for="' . $permission->name . '">' . $permission->description . '</label>';
                                echo '</div>';
                                echo '</div>';
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
            
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title">Quản lý bảo hành</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php
                        $warrantyPermissions = ['viewWarranty', 'createWarranty', 'updateWarranty', 'deleteWarranty'];
                        foreach ($allPermissions as $permission) {
                            if (in_array($permission->name, $warrantyPermissions)) {
                                $checked = isset($permissions[$permission->name]) ? 'checked' : '';
                                echo '<div class="col-md-3">';
                                echo '<div class="form-check">';
                                echo '<input class="form-check-input" type="checkbox" name="permissions[]" value="' . $permission->name . '" id="' . $permission->name . '" ' . $checked . '>';
                                echo '<label class="form-check-label" for="' . $permission->name . '">' . $permission->description . '</label>';
                                echo '</div>';
                                echo '</div>';
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
            
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title">Quản lý nhà cung cấp</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php
                        $supplierPermissions = ['viewSupplier', 'createSupplier', 'updateSupplier', 'deleteSupplier'];
                        foreach ($allPermissions as $permission) {
                            if (in_array($permission->name, $supplierPermissions)) {
                                $checked = isset($permissions[$permission->name]) ? 'checked' : '';
                                echo '<div class="col-md-3">';
                                echo '<div class="form-check">';
                                echo '<input class="form-check-input" type="checkbox" name="permissions[]" value="' . $permission->name . '" id="' . $permission->name . '" ' . $checked . '>';
                                echo '<label class="form-check-label" for="' . $permission->name . '">' . $permission->description . '</label>';
                                echo '</div>';
                                echo '</div>';
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
            
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title">Quản lý báo cáo và người dùng</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php
                        $otherPermissions = ['viewReport', 'viewUser', 'createUser', 'updateUser', 'deleteUser', 'manageRbac'];
                        foreach ($allPermissions as $permission) {
                            if (in_array($permission->name, $otherPermissions)) {
                                $checked = isset($permissions[$permission->name]) ? 'checked' : '';
                                echo '<div class="col-md-3">';
                                echo '<div class="form-check">';
                                echo '<input class="form-check-input" type="checkbox" name="permissions[]" value="' . $permission->name . '" id="' . $permission->name . '" ' . $checked . '>';
                                echo '<label class="form-check-label" for="' . $permission->name . '">' . $permission->description . '</label>';
                                echo '</div>';
                                echo '</div>';
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <?= Html::submitButton('<i class="fas fa-save"></i> Lưu thay đổi', ['class' => 'btn btn-success']) ?>
            </div>
            
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>