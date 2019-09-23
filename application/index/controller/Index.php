<?php
namespace app\index\controller;

use app\common\controller\Base;
use app\common\model\ArtCate;
use app\common\model\Article;
//use think\Request;
use think\facade\Request;

class Index extends Base
{
    public function index()
    {
        //获取栏目名称
        $id=Request::param('cate_id');
       // dump(if($id));
        if(isset($id)) {
            $resname = ArtCate::get($id);
            $this->assign('catname', $resname->name);

        $reslistt=Article::all(function ($query) use($id){
            $query ->where('cate_id',$id)->with('hasoneuser');

        });
        $reslist =Article::haswhere('hasoneuser')
                -> where('cate_id',$id)
                ->paginate(3, false, ['query'=>request()->param()]);



     //   $reslistt =Article::paginate(3);

      // dump($reslistt);
           // ->with('hasoneuser');
            $reslistt=Article::with('hasoneuser')
                         ->where('cate_id',$id)
                         ->paginate(3, false, ['query'=>request()->param()]);

         //   $reslist=Article::all(['user_id'=>12,'cate_id'=>$id],['hasoneuser']); //用模型关联查询文章对应的用户名

         // dump($reslistt);

             }else{
           // $reslist=Article::all()->paginate(3, false, ['query'=>request()->param()]);//用模型关联查询文章对应的用户名
            $reslist =Article::with('hasoneuser')->paginate(3, false, ['query'=>request()->param()]); // 分页 //模板中 加 |raw 不然输出代码
           // $reslist = Article::paginate(2,14);
            //$pages = $reslist->render();
           // $res =$this->logicindex();

          // $reslist= $reslist->toArray();
            // dump($res);
        }



       // $this->view->assign("list",$res['list']);
       // $this->assign("pages",$res['pages']);
      //  $this->view->assign('pages')
        $this->assign('list',$reslist);
       return $this->fetch();
      //  return view('index', ['list' => $reslist]);
        //return view();
    }


    public function logicindex(){
        $reslist =Article::with('hasoneuser')->paginate(3, '', ['query'=>request()->param()]);;
        return ["list" => $reslist, "pages" => $reslist->render()];
    }

    public function insert(){
        //登陆才能发布
        $this->islogin();
        //设置页面标题
        $this->view->assign('title','发布文章');
        //获取栏目信息
        $data=Artcate::all();
        //dump($data);
        if (count($data) >0) {
            $this->view->assign('catelist', $data);
            return $this->fetch();
        }{
            $this->error('请添加分类',url('/index/index'));
        }
    }

    public function save(){
        //判断提交类型
        if (Request::isPost()){
            //获取用户发不的信息
            $data =Request::post();
           // halt($data); //调试方法测试是否获取数据
            $res=$this->validate($data,'app\common\validate\Article');
            if(true!==$res){
                //验证失败
                echo '<script>alert("'.$res.'");history.go(-1);</script>';

            }else{
                //验证成功写入数据库
                //获取图片信息
                $file=Request::file('title_img');
                //validate 方法检查文件是否合格，move方法上传到uploads 目录$info保存上传的目录信息
                $info=$file->validate(['ext'=>'jpg,jpeg,png,gif','size'=>150000])->move('uploads');
                //判断是否上传成功
                if($info){
                    $data['title_img'] = $info->getSaveName(); //写人文件保存信息
                }else{
                    $this->error($file->getError());
                }
                //数据写入数据库
                //$Article = new Article();
                $res=Article::create($data);
                if($res){
                    $this->success('文章发布成功');
                }else{
                    $this->error('发布失败',url('/index/index'));
                }
            }

        }else{

            $this->error('请求类型错误');
        };
    }
}


