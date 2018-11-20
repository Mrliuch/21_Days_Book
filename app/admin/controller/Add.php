<?php

namespace app\admin\controller;

use think\Controller;
use app\admin\common\Base;
use app\admin\model\User;
       
class Add extends Base
{

    public function view()
    {

        // $this -> isLogin();
        $aa = User::all(); 
        $this ->view ->assign('aa',$aa);
        return $this ->view ->fetch('aaa');
    }

  //上传excal代码
    public function add(){
        if(request() -> isPost())
        {
            vendor("PHPExcel.PHPExcel"); 
            $objPHPExcel =new \PHPExcel();
            //获取表单上传文件
            $file = request()->file('file');
            $info = $file->validate(['ext' => 'xlsx'])->move(ROOT_PATH . 'public');  //上传验证后缀名,以及上传之后移动的地址  E:\wamp\www\bick\public
            if($info)
            {
//              echo $info->getFilename();
                $exclePath = $info->getSaveName();  //获取文件名
                $file_name = ROOT_PATH . 'public' . DS . $exclePath;//上传文件的地址
                $objReader =\PHPExcel_IOFactory::createReader("Excel2007");
                $obj_PHPExcel =$objReader->load($file_name, $encode = 'utf-8');  //加载文件内容,编码utf-8
                $excel_array=$obj_PHPExcel->getSheet(0)->toArray();   //转换为数组格式
                array_shift($excel_array);  //删除第一个数组(标题);
                array_shift($excel_array);
                array_shift($excel_array);
                array_shift($excel_array);
                $city = [];
                $citya = [];
                $i=0;
                foreach($excel_array as $k=>$v) {
                    $city[$k]['number'] = $v[0];
                    $city[$k]['activity'] = $v[1];
                    $city[$k]['grade'] = $v[2];
                    $city[$k]['three_id'] = $v[3]*0.15;
                    // $city[$k]['d'] = $v[4];
                    // $city[$k]['e'] = $v[5];
                    
                }
                User::name("perform")->insertAll($city);
                $this ->success('上传成功，点击查看确认提交！');

                //User::name("admin")->insertAll($citya);
            }else
            {
                echo $file->getError();
            }
        }

    }

public function stat()
    {
         $user = new User;
         $user->where('stat', 0)->update(['stat'=>1]);
         //$aa = User::all(); 
         //$this ->view ->assign('aa',$aa);
         //return $this->fetch("aaaindex");
         return '数据保存成功';
    }


//按学号查询
     public function chaxun()
    {
       $data = input('post.');
       //return $data['name'];
       $user = new User;
       $aa=$user->where('b',$data['xh'])->select();

       //return dump($aa);


       //$aa  = User::get(['b' => $data['name']]);
       //return $aa['a'];
       $this ->view ->assign('aa',$aa);
       return $this->fetch("aaaindex");

    }


//编辑
    public function edit($id)
    {
        $aa = User::get($id);
        $this ->view ->assign('aa',$aa);
        return $this ->view ->fetch('aq');
    }


        public function genggai($id)
    {
        $data = input('post.');
        $admin = User::get($id);

        $admin ->b = $data['name'];
        $admin ->a = $data['class'];
        $admin ->d = $data['shijian'];
        $admin ->c = $data['fen'];
        $admin ->save();
        $this ->success('更改成功');

}


    public function delete($id)
    {
       $user = User::destroy($id);
       $this ->success('照片删除成功');
    }








}
