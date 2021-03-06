<?php
namespace Admin\Controller;

use Think\Controller;
use \Think\Model;


class IndexController extends BaseController {
	public function index() {
		$news = M("News");
		$res = $news->order("time DESC")->limit("0,10")->where(array("type"=>0,"type_id"=>$_SESSION['sys_id']))->field("id,title,time,content")->select();
		$this->assign("news",$res);
		$this->display ();
	}
	public function changeSecret() {
		if(empty($_POST['cha_username']) || empty($_POST['cha_password_old']) || empty($_POST['cha_password_new']) || empty($_POST['cha_code']) ){
			$this->returnAjax(false,"输入非法！");
		}
		//检测验证码
		$c = new \Admin\Controller\CodeController();
		if(!$c->check_verify($_POST['cha_code'])){
			$this->returnAjax(false,"验证码错误！");
		}
		if(htmlspecialchars(str_replace(" ", "", $_POST['cha_password_new'])) != $_POST['cha_password_new']){
			$this->returnAjax(false,"新密码不能含有非法字符!");
		}
		$m = M("Systemmanager");
		$where = array("sys_username"=>$_POST['cha_username']);
		$res = $m->field("sys_password")->where($where)->select();
		if($res[0]['sys_password'] == md5($_POST['cha_password_old'])){
			if($m->where($where)->setField("sys_password",md5($_POST['cha_password_new']))){
				$this->returnAjax(true,"修改密码成功！");
			
			}else{
				$this->returnAjax(false,$m->getError());
			}
		}
		$this->returnAjax(false,"原始密码错误！");
		
	}
	public function getSysInfo() {
		$m = M ( "Tenant" );
		$d = new \Org\Util\Date ();
		$res ['sys_time'] = $d->format ( "%Y-%m-%d %H:%M" );
		$res ['sys_tenant_total'] = $m->count ();
		$res ['sys_tenant_isrun'] = $m->where ( "tnt_isrun=1" )->count ();
		
		$res ['sys_time_bj'] = gmdate ( "Y-n-j H:i", time () + 8 * 3600 );
		$res ['sys_env'] = substr ( $_SERVER ["SERVER_SOFTWARE"], 0, 12 );
		$res ['sys_os'] = PHP_OS;
		$res ['sys_server_name'] = $_SERVER ['SERVER_NAME'];
		$res ['sys_server_ip'] = gethostbyname ( $_SERVER ['SERVER_NAME'] );
		$res ['sys_space'] = round ( (@disk_free_space ( "." ) / (1024 * 1024)), 2 ) . 'M';
		$res ['sys_max_upload'] = ini_get ( 'upload_max_filesize' );
		$this->returnAjax ( true, "获取系统信息成功！", $res );
	}
	public function doRepair(){
		$m = M("Tenant");
		$where['tnt_isrun']="1";
		if($m->where($where)->setField("tnt_isrun",2)){
			$this->returnAjax(true,"成功开启维护模式！");
		}else{
			$this->returnAjax(false,"开启维护模式失败！");
		}
	}
	public function dounRepair(){
		$m = M("Tenant");
		$where['tnt_isrun']="2";
		if($m->where($where)->setField("tnt_isrun",1)){
			$this->returnAjax(true,"成功关闭维护模式！");
		}else{
			$this->returnAjax(false,"关闭维护模式失败！");
		}
	}
	public function isRepair(){
		$m = M("Tenant");
		$where['tnt_isrun']="2";
		if(count($m->where($where)->limit(0,2)->select()) > 0){
			$this->returnAjax(true);
		}else {
			$this->returnAjax(false);
		}
	}
	public function releaseNews(){
		if(empty($_POST['title']) || empty($_POST["content"])){
			$this->returnAjax(false,"标题或者内容不能为空！");
		}
		$news = M("News");
		 
		$data['type']=0;
		$data['title']=$_POST['title'];
		$data['content']=$_POST['content'];
		$data['type_id']=$_SESSION['sys_id'];
		$data['time']=time();
		$r = $news->add($data);
		if($r){
			$data['time']=date("Y-m-d",time());
			$data['id']=$r;
			$this->returnAjax(true,"添加公告成功！",$data);
		}else{
			$this->returnAjax(false,"发布公告失败！请重试！");
		}
	}
	/**
	 * ajaxReturn的简化版本
	 * 
	 * @param string $status        	
	 * @param string $info        	
	 * @param string $content        	
	 */
	private function returnAjax($status = true, $info = "", $content = null) {
		$info = $status ? (empty ( $info ) ? "操作成功" : $info) : (empty ( $info ) ? "操作失败" : $info);
		$this->ajaxReturn ( array (
				"content" => $content,
				"info" => $info,
				"status" => $status 
		), "json" );
	}
}
?>