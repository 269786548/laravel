@extends('admin.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <h2>编辑任务</h2>
        </div>
        <div class="layui-card-body">
            <form class="layui-form" action="{{route('admin.task.update',['id'=>$task])}}" method="post">
                <input type="hidden" name="id" value="{{$task->id}}">
                {{method_field('put')}}
                @include('admin.task._form')
            </form>
        </div>
    </div>
@endsection


