<?php echo e(csrf_field()); ?>

<div class="layui-form-item">
    <label for="" class="layui-form-label">昵称</label>
    <div class="layui-input-inline">
        <input type="text" name="name" value="<?php echo e($member->name??old('name')); ?>"  lay-verify="required" class="layui-input" >
    </div>
</div>

<div class="layui-form-item">
    <label for="" class="layui-form-label">花费</label>
    <div class="layui-input-inline">
        <input type="text" name="avatar" value="<?php echo e($member->avatar??old('avatar')); ?>"  lay-verify="required" placeholder="请输入金额" class="layui-input">
    </div>
</div>
<div class="layui-form-item">
    <label for="" class="layui-form-label">备注</label>
    <div class="layui-input-inline">
        <input type="text" name="remark" value="<?php echo e($member->remark??old('remark')); ?>"  lay-verify="required"  class="layui-input">
    </div>
</div>
<div class="layui-form-item">
    <label for="" class="layui-form-label">消费日期</label>
    <div class="layui-input-inline">
        <input type="text" name = 'pay_time' value="<?php echo date('Y-m-d');?>"class="layui-input" id="test6"  lay-key="1" lay-verify="required">
    </div>
</div>

<div class="layui-form-item">
    <div class="layui-input-block">
        <button type="submit" class="layui-btn" lay-submit="" lay-filter="formDemo">确 认</button>
        <a  class="layui-btn" href="<?php echo e(route('admin.member')); ?>" >返 回</a>
    </div>
</div>