<?php
namespace App\Services\Admin;
use Illuminate\Support\Facades\DB;
use App\Traits\DbAffair;

use Facades\ {
    App\Repositories\Eloquent\TaskRepository,
    App\Repositories\Eloquent\TemplateRepository
};

use Exception;

class TaskService {
	use DbAffair;
	protected $module = 'task';
	
	/**
	 * 获取菜单数据
	 * @author 晚黎
	 * @date   2017-11-06
	 * @return [type]     [description]
	 */
	
	// public function beginTransaction()
	// {
	// 	DB::beginTransaction();

	// }
	// public function dbCommit()
	// {
	// 	DB::commit();

	// }
	// public function rollBack()
	// {
	// 	DB::rollBack();
	// }
	public function get_sms_config()
	{
    	$cellData2 = DB::table('sms_call_config_info')->get();
    	$cellData  =  json_decode(json_encode($cellData2), true);
		return $cellData;
	}
	public function insert($data)
	{

		// $data = $request->only(['name','template_id','date_time','period_range','city','file_name']);
		if (is_array($data) && !empty($data)) {
			$ins_data['name'] = $data['name'];
			if ($data['template_id'] == '') {
				return false;
			}else{
				$ins_data['verbal_trick_template_id'] = $data['template_id'];
			}		
			if ($data['date_time'] == '') {
				$ins_data['start_date_str'] = date('Y-m-d');
				$ins_data['end_date_str'] = date('Y-m-d');
			}else{
				$t = explode(' - ',$data['date_time']);
				$ins_data['start_date_str'] = $t[0];
				$ins_data['end_date_str'] = $t[1];
			}
			$data['period_range'] ? $ins_data['call_task_period_range'] = $data['period_range'] : '09:00:00,20:00:00';
			if ($data['file_name'] == '') {
				return false;
			}
			$ins_data['file_name'] = $data['file_name'];
			$ins_data['city'] = $data['city'];
		}
		$para_id = 10000;
		$this->startAffair();

		$tasks = $this->ins_tasks($ins_data);//新增任务
		$balance = $this->para_balance($para_id);//扣除任务费用
		$nums = $this->tasks_nums($ins_data);//统计数量

		if ($tasks && $balance && $nums) {
			$this->commitAffair();
			return true;
		}else{
			$this->rollBackAffair();
			return false;
		}
	}




	public function tasks_nums()
	{
		$nums_data['task_id'] = rand();
		$nums_data['para_id'] = 1000;
    	if (DB::table('sms_tasks_for_num')->insert($nums_data)) {
    		return true;
    	}
    	return false;
	}
	public function ins_tasks($ins_data)
	{
    	if (DB::table('tasks')->insert($ins_data)) {
    		return true;
    	}
    	return false;
	}
	public function para_balance($para_id)
	{
     	$affected = DB::update("update sms_para set balance = balance-100 where para_id = ?", [$para_id]);
		if ($affected) {
			return true;
		}
    	return false;
	}
	public function getMenuList()
	{
		// 判断数据是否缓存
		if (cache()->has(config('admin.global.cache.menuList'))) {
			return cache()->get(config('admin.global.cache.menuList'));
		}
		return $this->sortMenuSetCache();
	}
	
	/**
	 * 递归菜单数据
	 * @author 晚黎
	 * @date   2017-11-06
	 * @param  [type]     $menus [description]
	 * @param  integer    $pid   [description]
	 * @return [type]            [description]
	 */
	private function sortMenu($menus,$pid=0)
	{
		$arr = [];
		if (empty($menus)) {
			return '';
		}
		foreach ($menus as $key => $v) {
			if ($v['pid'] == $pid) {
				$arr[$key] = $v;
				$arr[$key]['child'] = self::sortMenu($menus,$v['id']);
			}
		}
		return $arr;
	}
	
	/**
	 * 排序子菜单并缓存
	 * @author 晚黎
	 * @date   2017-11-06
	 * @return [type]     [description]
	 */
	private function sortMenuSetCache()
	{
		$menus = MenuRepository::all()->toArray();
		if ($menus) {
			$menuList = $this->sortMenu($menus);
			foreach ($menuList as $key => &$v) {
				if ($v['child']) {
					$sort = array_column($v['child'], 'sort');
					array_multisort($sort,SORT_DESC,$v['child']);
				}
			}
			// 缓存菜单数据
			cache()->forever(config('admin.global.cache.menuList'),$menuList);
			return $menuList;
			
		}
		return '';
	}
	/**
	 * 添加菜单视图
	 * @author 晚黎
	 * @date   2017-11-06
	 * @return [type]     [description]
	 */
	public function create()
	{
		$menus = $this->getMenuList();
		$permissions = PermissionRepository::all(['name', 'slug']);
		return compact('menus', 'permissions');
	}
	/**
	 * 添加数据
	 * @author 晚黎
	 * @date   2017-11-06
	 * @param  [type]     $attributes [description]
	 * @return [type]                 [description]
	 */
	public function store($attributes)
	{
		try {
			$result = MenuRepository::create($attributes);
			if ($result) {
				// 更新缓存
				$this->sortMenuSetCache();
			}
			return [
				'status' => $result,
				'message' => $result ? trans('common.create_success'):trans('common.create_error'),
			];
		} catch (Exception $e) {
			return [
				'status' => false,
				'message' => trans('common.create_error'),
			];
		}
	}
	/**
	 * 查看数据
	 * @author 晚黎
	 * @date   2017-11-06
	 * @param  [type]     $id [description]
	 * @return [type]         [description]
	 */
	public function show($id)
	{
		try {
			$menus = $this->getMenuList();
			$menu = MenuRepository::find(decodeId($id, $this->module));
			return compact('menus', 'menu');
		} catch (Exception $e) {
			abort(500);
		}
	}


	public function edit($id)
	{
		try {
			$attr = $this->show($id);
			$permissions = PermissionRepository::all(['name', 'slug']);
			return array_merge($attr, compact('permissions'));
		} catch (Exception $e) {
			abort(500);
		}
	}
	/**
	 * 修改数据
	 * @author 晚黎
	 * @date   2017-11-06
	 * @param  [type]     $attributes [description]
	 * @param  [type]     $id         [description]
	 * @return [type]                 [description]
	 */
	public function update($attributes, $id)
	{
		try {
			$isUpdate = MenuRepository::update($attributes, decodeId($id, $this->module));
			if ($isUpdate) {
				// 更新缓存
				$this->sortMenuSetCache();
			}
			return [
				'status' => $isUpdate,
				'message' => $isUpdate ? trans('common.edit_success'):trans('.common.edit_error'),
			];
		} catch (Exception $e) {
			return [
				'status' => false,
				'message' => trans('common.edit_error'),
			];
		}
	}
	/**
	 * 删除数据
	 * @author 晚黎
	 * @date   2017-11-06
	 * @param  [type]     $id [description]
	 * @return [type]         [description]
	 */
	public function destroy($id)
	{
		try {
			$result = MenuRepository::delete(decodeId($id, $this->module));
			if ($result) {
				$this->sortMenuSetCache();
			}
			flash_info($result,trans('common.destroy_success'),trans('common.destroy_error'));
		} catch (Exception $e) {
			flash(trans('common.destroy_error'), 'danger');
		}
	}
	/**
	 * 清除缓存
	 * @author 晚黎
	 * @date   2017-11-06
	 * @return [type]     [description]
	 */
	public function cacheClear()
	{
		cache()->forget(config('admin.global.cache.menuList'));
		flash(trans('common.cache_clear'), 'success')->important();
	}
	
}