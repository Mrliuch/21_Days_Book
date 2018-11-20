<?php

namespace app\index\controller;

use app\index\common\Baseindex;
use think\Request;
use app\index\model\Photodate;
use app\index\model\Admin;
use think\Session;
use think\Image;


class Photo extends Baseindex
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $this -> isLogin();
        $nam =Session::get('user_id');
         $user = new Admin;
        $aa = $user->where('xh', $nam)->select();
        
        $xm = $aa[0]['username'];
        //return dump($xm);
        $this ->view ->assign('nam',$xm);
        return $this ->view ->fetch('photo');
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function upload()
    {
        $this -> isLogin();
        return $this ->view ->fetch('index');
    }
    public function loada()
    {
        $this -> isLogin();

        $data = input('post.');
        // $data = $request ->param();
        $lb = $data['lb'];
        $rq = $data['rq'];
        $js = $data['js'];
 
            $nam =Session::get('user_id');
            $usera = new Admin;
            $aa = $usera->where('xh', $nam)->select();
        
            $xm = $aa[0]['username'];
            



        $user = new Photodate;
            $a = md5(time());
            $user -> lb = $lb;
            $user -> rq = $rq;
            $user -> js = $js;
            $user -> name = $xm;
            $user -> aaa= $a;
            $user -> data = time();
            Session::set('aaa',$a);

         $user ->save();

        return $this ->view ->fetch('upload');
    }
    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function up(Request $request)
    {
        $user = new Photodate;
        $file = $request ->file('file');
        if(empty($file)){
            $this ->error('请选择上传文件');
        }
            $image = Image::open($file);
            $image ->thumb(450,500);
            $savename = $request -> time() . '.png';
            $image ->save(ROOT_PATH . 'public/static/up/' . $savename);
        $info = $file ->validate(['ext'=>'jpg,png','size'=>'204800000000'])->move(ROOT_PATH . 'public/static/');
        if($info){
            

            // $nam =Session::get('user_id');
            // $usera = new Admin;
            // $aa = $usera->where('xh', $nam)->select();
            // //return dump($aa);
            // $xm = $aa[0]['username'];
            // $aa = $user->where('xh', $nam)->select();
            Photodate::where('aaa', Session::pull('aaa')) ->update(['path' =>  '/up/' . $savename]);

            // $user -> path = '/up/' . $savename;
            // $user -> data = time();
            // $user ->save();

            $this ->success('照片上传成功','photo/index');

        }else{
            $this ->error($file -> getError());
        }
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */

    public function guanyu()
    {

        return $this ->view ->fetch('index/jilu');
    }
    public function read($id)
    {
        //
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
       $user = Photodate::destroy($id);
       $this ->success('照片删除成功');
    }
}
