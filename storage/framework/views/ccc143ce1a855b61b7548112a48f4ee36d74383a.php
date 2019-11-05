<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title></title>
    <link rel="stylesheet" href="/static/home/layui/css/layui.css">
    <link rel="stylesheet" href="/static/home/css/main.css">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <script src="/static/home/js/jquery.min.js"  type="text/javascript"></script>
    <script src="/static/home/layui/layui.all.js"  type="text/javascript"></script>
</head>
<body>
<div class="layui-container">
    <?php echo $__env->yieldContent('content'); ?>
</div>
<script type="text/javascript">

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var element = layui.element;
    var layer = layui.layer;
    var form = layui.form;
    var table = layui.table;
    var upload = layui.upload;

    form.render();
    element.render();

    //统一错误提示信息
    <?php if(count($errors)>0): ?>
    var errorStr = '';
    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        errorStr += "<?php echo e($error); ?><br />";
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        layer.msg(errorStr);
    <?php endif; ?>

    <?php if(session('status')): ?>
        layer.msg("<?php echo e(session('status')); ?>");
    <?php endif; ?>

    //删除确认
    function delConfirm(url) {
        layer.confirm('真的删除行么', function(index){
            layer.close(index);
            $.post(url,{_method:"delete"},function (data) {
                layer.msg(data.msg,{time:1000},function () {
                    if (data.code==0){
                        location.reload()
                    }
                });
            })
        });
    }
</script>
<?php echo $__env->yieldContent('script'); ?>
</body>
</html>