<?php echo e(csrf_field()); ?>

<div class="layui-form-item">
    <label for="" class="layui-form-label">名称</label>
    <div class="layui-input-inline">
        <input type="text" name="name" value="<?php echo e($position->name ?? old('name')); ?>" lay-verify="required" placeholder="请输入名称" class="layui-input" >
    </div>
</div>

<div class="layui-form-item">
    <label for="" class="layui-form-label">排序</label>
    <div class="layui-input-inline">
        <input type="number" name="sort" value="<?php echo e($position->sort ?? 0); ?>" lay-verify="required|number" placeholder="请输入数字" class="layui-input" >
    </div>
</div>


<div class="layui-form-item">
    <div class="layui-input-block">
        <button type="submit" class="layui-btn" lay-submit="" lay-filter="formDemo">确 认</button>
        <a  class="layui-btn" href="<?php echo e(route('admin.position')); ?>" >返 回</a>
    </div>
</div>