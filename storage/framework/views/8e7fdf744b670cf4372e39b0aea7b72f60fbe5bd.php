<?php $__env->startSection('content'); ?>
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <div class="layui-btn-group ">
                <button class="layui-btn layui-btn-sm" id="memberSearch">搜索</button>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('task.task.destroy')): ?>
                    <button class="layui-btn layui-btn-sm layui-btn-danger" id="listDelete">删除</button>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('task.task.create')): ?>
                    <a class="layui-btn layui-btn-sm" href="<?php echo e(route('admin.task.create')); ?>">添加</a>
                <?php endif; ?>
            </div>
            <div class="layui-form">
            <div class="layui-input-inline">
                  <input type="text" class="layui-input" name="test1" id="test1" placeholder="请选择日期"/>
                </div>
                <div class="layui-input-inline">
                    <input type="text" name="name" id="name" placeholder="请输入昵称" class="layui-input">
                </div>
                <div class="layui-input-inline">
                    <input type="text" name="task_id" id="task_id" placeholder="请输入任务ID" class="layui-input">
                </div>
            </div>
        </div>
        <div class="layui-card-body">
            <table id="dataTable" lay-filter="dataTable"></table>
            <script type="text/html" id="options">

                <div class="layui-btn-group layui-table-cell laytable-cell-1-7">
                        <a class="layui-btn layui-btn-sm"  lay-event="edit">编辑</a>
                        <a class="layui-btn layui-btn-sm"  lay-event="del">删除</a>
                        <a class="layui-btn layui-btn-sm"  lay-event="excel">导出</a>
                        <a class="layui-btn layui-btn-sm"  lay-event="taskS">开始</a>
                        <a class="layui-btn layui-btn-sm"  lay-event="taskP">停止</a>
                        
                </div>
            </script>
          
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('task.task')): ?>
        <script>
            layui.use(['layer','table','form'],function () {
                var layer = layui.layer;
                var form = layui.form;
                var table = layui.table;
                //用户表格初始化
                var dataTable = table.render({
                    elem: '#dataTable'
                    ,height: 500
                    ,url: "<?php echo e(route('admin.task.data')); ?>" //数据接口
                    ,where:{model:"task"}
                    ,page: true //开启分页
                    ,cols: [[ //表头
                        {checkbox: true,fixed: true}
                        ,{field: 'create_time', title: '创建时间',width:160}
                        ,{field: 'name', title: '任务名称'}
                        ,{field: 'task_id', title: '任务ID'}
                        ,{field: 'verbal_trick_template_id', title: '模板ID'}
                        ,{field: 'count_num', title: '总量'}
                        ,{field: 'success_num', title: '接通'}
                        ,{field: 'error_num', title: '失败'}
                        ,{field: 'unn', title: '待返回',templet: function(d){
                                return (d.count_num-d.success_num-d.error_num) ;
                              }
                          }
                        ,{field: 'type', title: '接通率',templet: function(d){
                                if (!d.success_num) {return 0};
                                return (d.success_num/(d.success_num+d.error_num)*100).toFixed(2)+'%' ;
                              }
                        }

                        ,{field: 'start_date_str', title: '开始时间',width:120}
                        ,{field: 'end_date_str', title: '结束时间',width:120}
                        ,{field: 'call_task_period_range', title: '发送时间段',width:180}
                        ,{field: 'status', title: '号码状态',templet: function(d){
                                if (d.status ==0) {return '<span class="layui-btn layui-btn-sm" >初始化</a>'}
                                else if(d.status ==1) {return '<span class="layui-btn layui-btn-normal layui-btn-sm" >已上传</a>'}
                                else{return '<span class="layui-btn layui-btn-danger layui-btn-sm" >未知</a>'}
                              }
                        }
                        ,{field: 'call_status', title: '外呼状态',templet: function(d){
                                if (d.call_status ==0) {return '<span class="layui-btn layui-btn-sm" id = "call_status_'+d.task_id+'" >初始化</a>'}
                                else if(d.call_status ==1) {return '<span class="layui-btn layui-btn-normal layui-btn-sm" id = "call_status_'+d.task_id+'">已开始</a>'}
                                else if(d.call_status ==2) {return '<span class="layui-btn layui-btn-danger layui-btn-sm" id = "call_status_'+d.task_id+'">已停止</a>'}
                                else{return '<span class="layui-btn layui-btn-danger layui-btn-sm" >未知</a>'}
                              }
                        }
                        ,{field: 'if_return', title: '返还状态',templet: function(d){
                                if (d.if_return ==0) {return '<span class="layui-btn layui-btn-sm" >未返还</a>'}
                                else if(d.if_return ==1) {return '<span class="layui-btn layui-btn-normal layui-btn-sm" >已返还</a>'}
                                else{return '<span class="layui-btn layui-btn-danger layui-btn-sm" >未知</a>'}
                              }


                        }
                        ,{fixed: 'right', title: '操作', width: 250, align:'left', toolbar: '#options'}
                    ]]
                });
                //监听工具条
                table.on('tool(dataTable)', function(obj){ //注：tool是工具条事件名，dataTable是table原始容器的属性 lay-filter="对应的值"
                    var data = obj.data //获得当前行数据
                        ,layEvent = obj.event; //获得 lay-event 对应的值
                    if(layEvent === 'del'){
                        layer.confirm('确认删除吗？', function(index){
                            $.post("<?php echo e(route('admin.task.destroy')); ?>",{_method:'delete',ids:[data.id]},function (result) {
                                if (result.code==0){
                                    obj.del(); //删除对应行（tr）的DOM结构
                                }
                                layer.close(index);
                                layer.msg(result.msg)
                            });
                        });
                    } else if(layEvent === 'edit'){
                        location.href = '/admin/task/'+data.id+'/edit';
                    }else if(layEvent === 'excel'){
                        location.href = '/admin/task/'+data.task_id+'/excelExports';
                    }else if(layEvent == 'taskS'){
                        if (data.call_status==1) {layer.msg('开启中,勿重复...'); return};
                        layer.confirm('确认开启吗？', function(index){
                           $.ajax({
                                type: 'post',
                                dataType:'json',
                                data: {"id": data.task_id,"status": 1},
                                url:"<?php echo e(route('admin.task.taskSt')); ?>",
                                success:function(result){
                                    layer.close(index);
                                    layer.msg(result.msg);
                                    if (result.code==0){
                                        var cname = "#call_status_"+data.task_id;
                                        $(cname).text("已开始");
                                        $(cname).attr("class","layui-btn layui-btn-normal layui-btn-sm");
                                        // $('div').attr('id','call_status');

                                    }
                                },
                            });


                            // $.post("<?php echo e(route('admin.task.taskSt')); ?>",{id:[data.task_id],status:1},function (result) {
                            //     layer.close(index);
                            //     layer.msg(result.msg);
                            //     if (result.code==0){
                            //         $("#call_status").text("已开始");
                            //         $("#call_status").attr("class","layui-btn layui-btn-normal layui-btn-sm");
                            //         // $(".layui-laypage-btn")[0].click();
                            //     }
                            // });
                        });
                    }else if(layEvent == 'taskP'){
                        if (data.call_status==2) {layer.msg('停止中,勿重复...'); return};
                        layer.confirm('确认停止吗？', function(index){
                           $.ajax({
                                type: 'post',
                                dataType:'json',
                                data: {"id": data.task_id,"status": 2},
                                url:"<?php echo e(route('admin.task.taskSt')); ?>",
                                success:function(result){
                                    layer.close(index);
                                    layer.msg(result.msg);
                                    if (result.code==0){
                                        var cname = "#call_status_"+data.task_id;
                                        $(cname).text("已停止");
                                        $(cname).attr("class","layui-btn layui-btn-danger layui-btn-sm");
                                    }
                                },
                            });

                            // $.post("<?php echo e(route('admin.task.taskSt')); ?>",{id:[data.task_id],status:2},function (result) {
                            //     layer.close(index);
                            //     layer.msg(result.msg);
                            //     if (result.code==0){
                            //         $("#call_status").text("已停止");
                            //         $("#call_status").attr("class","layui-btn layui-btn-danger layui-btn-sm");
                            //     }
                            // });
                        });
                    }else if(layEvent == 'tasksss'){
                       $.ajax({
                            type: 'post',
                            dataType:'json',
                            data: {"id": 1,"status": 2},
                            url:"<?php echo e(route('admin.task.taskSt')); ?>",
                            success:function(result){
                                alert(result);
                            },
                        });
                    }
                });

                //按钮批量删除
                $("#listDelete").click(function () {
                    var ids = []
                    var hasCheck = table.checkStatus('dataTable')
                    var hasCheckData = hasCheck.data
                    if (hasCheckData.length>0){
                        $.each(hasCheckData,function (index,element) {
                            ids.push(element.id)
                        })
                    }
                    if (ids.length>0){
                        layer.confirm('确认删除吗？', function(index){
                            $.post("<?php echo e(route('admin.task.destroy')); ?>",{_method:'delete',ids:ids},function (result) {
                                if (result.code==0){
                                    dataTable.reload()
                                }
                                layer.close(index);
                                layer.msg(result.msg)
                            });
                        })
                    }else {
                        layer.msg('请选择删除项')
                    }
                })
                //搜索
                $("#memberSearch").click(function () {
                    var userSign = $("#user_sign").val()
                    var name = $("#name").val();
                    var task_id = $("#task_id").val();
                    var time_str = $("#test1").val();
                    dataTable.reload({
                        where:{user_sign:userSign,name:name,task_id:task_id,time_str:time_str},
                        page:{curr:1}
                    })
                })
            })
            layui.use('laydate', function(){
              var laydate = layui.laydate;
              //执行一个laydate实例
              laydate.render({
                elem: '#test1' //指定元素
                ,range: true //或 range: '~' 来自定义分割字符

              });
            });

        </script>
    <?php endif; ?>
<?php $__env->stopSection(); ?>




<?php echo $__env->make('admin.base', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>