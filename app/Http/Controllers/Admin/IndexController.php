<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

use View;
use DB;
use Session;
use Redirect;
use App\Model\AdminMenu;
use App\Model\AdminUser;

class IndexController extends Controller
{
	 
	/**
	 * 后台控制主架构
	 */
	public function index()
	{
		$menu_list = AdminMenu::where(['menu_parent_id' => 0, 'menu_status' => 1])->orderBy('menu_sort','asc')->get()->toArray();
		if ($menu_list) {
			foreach ($menu_list as $key => $value) {
				$menu_list[$key]['soninfo'] = AdminMenu::where(['menu_parent_id' => $value['menu_id'], 'menu_status' => 1])->orderBy('menu_sort','asc')->get()->toArray();
			}
		}
		$admin_user = Session::get('adminuser')->toArray();
		return View::make('admin.index.index', ['menulist' => $menu_list, 'adminuser' => $admin_user]);
	}

	/**
	 * 后台控制主页面
	 */
	public function welcome()
	{
		$siteInfo = DB::table('site_info')->first();
		return View::make('admin.index.welcome', ['siteinfo' => $siteInfo]);
	}

	/**
	 * 后台登陆页面
	 */
	public function login()
	{
		return View::make('admin.index.login');
	}

	/**
	 * 后台登陆方法
	 */
	public function toLogin(Requests\AdminUserLoginRequest $request)
	{
		$data = $request->all();
		$userInfo = AdminUser::where('user_name', $data['user_name'])->first();
		if (count($userInfo) == 0) {
            $jsonData = [
	            'status'  => '0',
	            'message' => '用户名不存在',
	        ];
        } else {
        	$check_pwd = Hash::check($data['user_password'] , $userInfo->user_password);
        	if (!$check_pwd) {
        		$jsonData = [
		            'status'  => '0',
		            'message' => '密码错误',
		        ];
        	}
        	if (!$userInfo->user_status) {
        		$jsonData = [
		            'status'  => '0',
		            'message' => '账号已被禁用，请联系总管理员',
		        ];
        	}
        }

        if(!isset($jsonData)) {
        	$update_data = [
        		'user_last_login_time' => time(),
        		'user_last_login_ip'   => $request->getClientIp(),
        	];
        	AdminUser::where('user_id',$userInfo->user_id)->update($update_data);
        	session(['adminuser' => $userInfo]);
        	$jsonData = [
        		'status'  => '2',
		        'message' => '登陆成功',
		        'jumpurl' => '/admin',
        	];
        }
        return response()->json($jsonData);
	}
	
	/**
     *  后台退出操作
     */
    public function logout()
    {
        $res = Session::forget('adminuser');
        return Redirect::to('/admin/login');
    }

    /**
     * 网站信息编辑页面
     */
    public function siteInfo()
    {
        $siteInfo = DB::table('site_info')->first();
        return View::make('admin.index.siteinfo', ['siteinfo' => $siteInfo]);
    }

    /**
     * 网站信息编辑操作
     */
    public function toSiteInfo(Request $request)
    {
        $data = $request->except(['_token','s']);
		
		//Base64 保存图片
		$fileroot = 'uploads/images/'.date("Ymd").'/';
		$data['site_logo'] = isset($data['site_logo']) ? $this->basePic($data['site_logo'],$fileroot):'';
		$data['wechat_logo'] = isset($data['wechat_logo']) ? $this->basePic($data['wechat_logo'],$fileroot):'';
		
        $siteInfo = DB::table('site_info')->first();
		if($siteInfo){
			$re = DB::table('site_info')->where('id', $siteInfo->id)->update($data);
		}else{
			$re = DB::table('site_info')->insert($data);
		}
        
        if($re) {
            $jsonData = [ 'status'  => '2', 'message' => '修改成功', 'jumpurl' => '/admin/index/siteinfo' ];
        } else {
            $jsonData = [ 'status'  => '0', 'message' => '修改失败' ];
        }
        return response()->json($jsonData);
    }
}