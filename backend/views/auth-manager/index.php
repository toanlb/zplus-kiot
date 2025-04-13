<?php
use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Quản lý phân quyền';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rbac-index">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><?= Html::encode($this->title) ?></h3>
            <div class="card-tools">
                <?= Html::a('<i class="fas fa-user-plus"></i> Gán vai trò', ['assign-role'], ['class' => 'btn btn-success btn-sm']) ?>
            </div>
        </div>
        <div class="card-body">
            <h4>Vai trò (Roles)</h4>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Tên</th>
                        <th>Mô tả</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($roles as $role): ?>
                    <tr>
                        <td><?= $role->name ?></td>
                        <td><?= $role->description ?></td>
                        <td>
                            <?= Html::a('<i class="fas fa-eye"></i> Xem', ['view-role', 'name' => $role->name], ['class' => 'btn btn-primary btn-sm']) ?>
                            <?= Html::a('<i class="fas fa-edit"></i> Cập nhật quyền', ['update-role-permissions', 'name' => $role->name], ['class' => 'btn btn-info btn-sm']) ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <h4 class="mt-4">Quyền (Permissions)</h4>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Tên</th>
                        <th>Mô tả</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($permissions as $permission): ?>
                    <tr>
                        <td><?= $permission->name ?></td>
                        <td><?= $permission->description ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>