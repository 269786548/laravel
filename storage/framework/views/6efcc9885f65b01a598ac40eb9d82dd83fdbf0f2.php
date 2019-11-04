<?php $__env->startSection('content'); ?>
    <div class="layui-card">
        <div class="layui-card-header  layuiadmin-card-header-auto">
            <h2>添加任务</h2>
        </div>
        <div class="layui-card-body">
            <form class="layui-form" action="<?php echo e(route('admin.task.store')); ?>" method="post">
            <?php echo $__env->make('admin.task._form', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('task.task')): ?>
        <script>

            layui.use('laydate', function(){
              var laydate = layui.laydate;
              //执行一个laydate实例
              laydate.render({
                elem: '#test6' //指定元素
                ,range: true //或 range: '~' 来自定义分割字符
              });

                //时间范围
                laydate.render({
                  elem: '#time1'
                  ,type: 'time'
                  ,range: true
                });

            });
            layui.use('upload', function(){
              var upload = layui.upload;

                 //普通图片上传
                var uploadInst = upload.render({
                    elem: '#test2'
                    ,url: '<?php echo e(route("uploadImg")); ?>'
                    ,multiple: false
                    ,exts: 'xls|csv|xlsx'
                    ,method: 'post'  //可选项。HTTP类型，默认post
                    ,data:{"_token":"<?php echo e(csrf_token()); ?>"}
                    ,before: function(obj){
                        //预读本地文件示例，不支持ie8
                        /*obj.preview(function(index, file, result){
                         $('#layui-upload-box').append('<li><img src="'+result+'" /><p>待上传</p></li>')
                         });*/
                        obj.preview(function(index, file, result){
                            $('#layui-upload-box').html('<li><img src="'+result+'" /><p>上传中</p></li>')
                        });

                    }
                    ,done: function(res){
                        //如果上传失败
                        if(res.code == 0){
                            // $("#thumb").val(res.url);
                            $("#thumb").val(res.clientName);
                            $("#file_up").text(res.clientName);
                            $('#layui-upload-box li p').text('上传成功');
                            return layer.msg(res.msg);
                        }
                        return layer.msg(res.msg);
                    }
                });
            });


        </script>
    <?php endif; ?>
<?php $__env->stopSection(); ?>



<?php echo $__env->make('admin.base', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>