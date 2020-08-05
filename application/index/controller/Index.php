<?php
namespace app\index\controller;

use app\common\controller\Base;
use app\common\model\ArtCate;
use app\common\model\Article;
use app\common\model\conmmentsl;
//use think\Request;
use think\facade\Request;
use think\Db;
class Index extends Base
{
    public function index()
    {
        //全局查询条件
        $map =[]; //将所有的查询条件封装到数组中
        //条件1：
        $map[]=['status','=',1];//这里的等号不能省略
        //获取搜索条件
        $keyword =Request::param('keyword');
        //搜索时模板中的action 留空就是提交到当前URL

        if (!empty($keyword)){
            $map[] =['title','like','%'.$keyword.'%'];
        }


        //获取栏目名称
        $id=Request::param('cate_id');
       // dump(if($id));
        if(isset($id)) {
            $map[] =['cate_id','=',$id];

            $resname = ArtCate::get($id);
            $this->assign('catname', $resname->name);

        $reslistt=Article::all(function ($query) use($id){
            $query ->where('cate_id',$id)->with('hasoneuser');

        });

        $reslistt =Article::haswhere('hasoneuser')
                 ->where('zh_article.status',1)  //Integrity constraint violation: 1052 Column 'status' in where clause is ambiguous 使用haswhere不加上表名不认识是那个表的字段
                 -> where('cate_id',$id)
             //   -> where($map)
                ->paginate(3, false, ['query'=>request()->param()]);



     //   $reslistt =Article::paginate(3);

       //dump($reslistt);
           // ->with('hasoneuser');
            $reslist=Article::with('hasoneuser')
                         //->where('cate_id',$id)
                          -> where($map)
                         ->paginate(3, false, ['query'=>request()->param()]);

         //   $reslist=Article::all(['user_id'=>12,'cate_id'=>$id],['hasoneuser']); //用模型关联查询文章对应的用户名

         // dump($reslistt);

             }else{
           // $reslist=Article::all()->paginate(3, false, ['query'=>request()->param()]);//用模型关联查询文章对应的用户名
            $reslist =Article::with('hasoneuser')
                 -> where($map)
                ->paginate(3, false, ['query'=>request()->param()]); // 分页 //模板中 加 |raw 不然输出代码
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
//保存文章
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

    public function detail(){
       // $artId = Request::get('id');

        $artId=Request::param('id');
      //  dump($artId);
        if (!empty($artId)){
            $res=Article::with(['hartcate'=>['user'],'hasoneuser','userfav'])  //方法 Article模型中的hartcate方法通过cate_id关联artCate中的id ，-> Artcate模型中的user 方法查询id 关联的上级user_id；
              //  $res=Article::with(['username','hasoneuser'])
               ->where('id',$artId)
                ->find();
            $res->setInc('pv'); //每打开一次 就给PV阅读量+1


            $this->view->assign('conmmentsl',conmmentsl::select(function($query) use($artId){

                    $query->where('status',1)->where('art_id',$artId)->order('create_time','desc');

            }));



            $this->view->assign('title',$res['title']);
            $this->view->assign('content',$res['content']);
            $this->view->assign('pv',$res['pv']);
            $this->view->assign('create_time',$res['create_time']);
            $this->view->assign('user',$res['hasoneuser']['name']);
            $this->view->assign('catename',$res['hartcate']['name']);
            dump($res);
            $this->view->assign('art',$res);

        }

        return $this->view->fetch('detail');
    }

    public function favorite(){
        if(!Request::isAjax()){
            return ['status'=>-1,'message'=>'请求错误'];
        }

        //获取从前端传递过来的数据
        $data=Request::param();
        //var_dump($data);
        if (empty($data['sessionid'])){
            return  ['status'=>2,'message'=>'登录后在收藏'];
        }
        $map[] =['user_id','=',$data['userId']];
        $map[] =['art_id','=',$data['artId']];
        $fav =Db::table('zh_user_fav')->where($map)->find();
        if (is_null($fav)){
            Db::table('zh_user_fav')->data([
                'user_id'=>$data['userId'],
               'art_id'=>$data['artId']
            ])->insert();
            return  ['status'=>1,'message'=>'收藏成功'];
        }else{
            Db::table('zh_user_fav')->where($map)->delete();
          return  ['status'=>0,'message'=>'未收藏'];
        }
    }

    public function conmmentsl(){
        if(!Request::isAjax()){
            return ['status'=>-1,'message'=>'请求错误'];
        }else{
            $data = Request::param();

            $res=conmmentsl::create($data);
            if($res){
                return ['status'=>1,'message'=>'发布成功'];

            }else{
                return ['status'=>0,'message'=>'发布失败'];
            }


        }
    }
}



