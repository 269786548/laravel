<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\TaskCreateRequest;
use App\Http\Requests\TaskRequest;
use App\Http\Requests\TaskUpdateRequest;
use App\Services\Admin\TaskService;

use App\Models\Task;
use App\Models\User;
use Excel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
class TaskController extends Controller
{

    protected $service;

    public function __construct(TaskService $service)
    {
        $this->service = $service;
    }
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index(Request $request)
    {

        // 获取当前通过认证的用户...
        // $user = Auth::user();

        // 获取当前通过认证的用户 ID...
        // $id = Auth::id();
        // dump($request->session());

          // $log = new Logger('name');
        // $log->pushHandler(new StreamHandler(storagepath('logs/error.log'), Logger::WARNING));

        // add records to the log
        // $log->warning('Foo');
        // $log->error('Bar');
    	// $res = DB::table('sms_called_nums')->select('task_id','customer_phone')->where('task_id','=','1446');
    	// $res = DB::table('sms_called_nums')->select('task_id','customer_phone')->where('task_id','=','1446')->get()->toArray();
    	// dump($res);exit;


//     	DB::connection()->enableQueryLog();  // 开启QueryLog

//     	$stat = DB::table('tasks');
//             $stat = $stat->where('task_id','=','1451');
//             $stat = $stat->where('name','like','%3107%');
//         $res = $stat->paginate($request->get('limit',10))->toArray();
//         dump(DB::getQueryLog());   //获取查询语句、参数和执行时间
// dump($res);


		// $model = Task::query();
  //       $res = $model->orderBy('id','desc')->paginate($request->get('limit',10))->toArray();
  //       dump($res);

        return view('admin.task.index');
    }
    public function data(Request $request)
    {
        // $model = Task::query();
        // if ($request->get('task_id')){
        //     $model = $model->where('task_id','like','%'.$request->get('task_id').'%');
        // }
        // if ($request->get('name')){
        //     $model = $model->where('name','like','%'.$request->get('name').'%');
        // }
        // $res = $model->orderBy('id','desc')->paginate($request->get('limit',10))->toArray();
    	// DB::connection()->enableQueryLog();  // 开启QueryLog

    	$stat = DB::table('tasks');
        if ($request->get('task_id')){
            $stat = $stat->where('task_id','like','%'.$request->get('task_id').'%');
        }
        if ($request->get('time_str')){
        	$str_data = explode(' - ', $request->get('time_str'));
            $stat = $stat->where('create_time','>=',$str_data[0].' 00:00:00');
            $stat = $stat->where('create_time','<=',$str_data[1].' 23:59:59');
        }
        if ($request->get('name')){
            $stat = $stat->where('name','like','%'.$request->get('name').'%');
        }

        $res = $stat->orderBy('id','desc')->paginate($request->get('limit',10))->toArray();
        // dump(DB::getQueryLog());   //获取查询语句、参数和执行时间
        // exit;
        $data = [
            'code' => 0,
            'msg'   => '正在请求中...',
            'count' => $res['total'],
            'data'  => $res['data']
        ];
        return response()->json($data);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function download()
    {
        return view('admin.task.download');
    }
    public function taskSt(Request $request)
    {

        if($request->ajax()){
            $id = $request->input('id');  
            $status = $request->input('status');  
            if ($status == 1) {
                $status_lg = 'start';
                $call_status = 1;
            }elseif($status == 2){
                $status_lg = 'pause';
                $call_status = 2;
            }else{
                return response()->json(['code'=>1,'msg'=>'请求失败!']);
            }
            $url = "https://www.ezipcc.com/openapi/campaigns/".$id."/".$status_lg;
            $headers = array(
                'tenantId:10031',
                'userId:249'
            );

            $commands = http_curl($url,'GET','',$headers);
            file_put_contents('/data/wwwroot/laravel/storage/logs/laravel2.log',json_encode($commands,JSON_UNESCAPED_UNICODE).PHP_EOL, FILE_APPEND);

            if (empty($commands)) {
                DB::table('tasks')
                  ->where('task_id', $id)
                  ->update(['call_status' => $call_status]);
                return response()->json(['code'=>0,'msg'=>'请求成功']);
            }else{
                return response()->json(['code'=>1,'msg'=>'请求失败!!']);
            }
        }
        return response()->json(['code'=>1,'msg'=>'请求失败!!!']);
    }
    
    public function create(Request $request)
    {
        // $this->service->beginTransaction();
        $menus = $this->service->get_sms_config();
        return view('admin.task.create',['menus'=>$menus]);
        // return view('admin.task.create',compact('menus'));
        // return view('admin.task.upload');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TaskRequest $request)
    {
        $data = $request->only(['name','template_id','date_time','period_range','city','file_name','para_id']);
        $res = $this->service->insert($data);
        // if (DB::table('tasks')->insert($data)){
        if ($res){
            return redirect()->to(route('admin.task'))->with(['status'=>'添加任务成功']);
        }
        return redirect()->to(route('admin.task'))->withErrors('系统错误');
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $task = task::findOrFail($id);
        return view('admin.task.edit',compact('task'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $Task = Task::findOrFail($id);
        $data = $request->except('password');
        if ($request->get('password')){
            $data['password'] = bcrypt($request->get('password'));
        }
        $Task->timestamps = false;
        if ($Task->update($data)){
            return redirect()->to(route('admin.task'))->with(['status'=>'更新用户成功']);
        }
        return redirect()->to(route('admin.task'))->withErrors('系统错误');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $ids = $request->get('ids');
        if (empty($ids)){
            return response()->json(['code'=>1,'msg'=>'请选择删除项']);
        }
        if (Task::destroy($ids)){
            return response()->json(['code'=>0,'msg'=>'删除成功']);
        }
        return response()->json(['code'=>1,'msg'=>'删除失败']);
    }

    public function excelExports($id)
    {
    	// $cellData = $users->get()->toArray();
    	// dump($cellData);

    	$cellData2 = DB::table('sms_called_nums')->select('task_id','customer_phone','call_duration',DB::raw("IF(call_duration>0,'成功','失败') as status"))->where('task_id','=',$id)->get();
    	$cellData  =  json_decode(json_encode($cellData2), true);
    	array_unshift($cellData,array('任务id','被叫号码','时长','状态'));//添加元素

        // $cellData = [
        //     ['学号','姓名','成绩'],
        //     ['10001','AAAAA','99'],
        //     ['10002','BBBBB','92'],
        //     ['10003','CCCCC','95'],
        //     ['10004','DDDDD','89'],
        //     ['10005','EEEEE','96'],
        // ];
        Excel::create('学生成绩',function($excel) use ($cellData){
            $excel->sheet('score', function($sheet) use ($cellData){
                $sheet->rows($cellData);
            });
        })->download('csv');
    }

}
