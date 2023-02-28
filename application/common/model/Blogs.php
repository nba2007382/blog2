<?php

namespace app\common\model;
use think\Db;use think\model\concern\SoftDelete;
use think\Model;

class Blogs extends Model
{
    protected $pk = 'bid';
    protected $table = 'blogs';
    use SoftDelete;
    protected $deleteTime = 'delete_time';

    //通过搜索关键字的数据
    public function getSearchData($key,$page,$pageSize){
      $data=Db::name('blogs')
      ->where('delete_time',null)
      ->where('title','like',"%".$key."%")
      ->order('uploaddate','desc')
      ->page($page,$pageSize)
      ->select();
      return $data;
    }
     //通过搜索关键字的数据记录数
      public function getSearchCount($key){
      $count=Db::name('blogs')
      ->where('delete_time',null)
      ->where('title','like',"%".$key."%")
      ->count();
      return $count;
    }

    //按年月归档
    public function getYearMonth(){
        $sql="SELECT DATE_FORMAT(uploaddate,'%Y年%m月') as yearmonth , ";
        $sql.="DATE_FORMAT(uploaddate,'%Y') as byear,DATE_FORMAT(uploaddate,'%m') as bmonth, ";
        $sql.="count(bid) as bcount FROM `blogs` ";
        $sql.="where delete_time is null ";
        $sql.="GROUP BY yearmonth ";
        $sql.="ORDER BY yearmonth desc ";
        $data=\db('blog')->query($sql);
        if ($data){
            return $data;
        }
        else{
            return ['msg'=>'获取数据失败!','sql'=>$sql];
        }
    }

    //按年月查询数据
    public function queryYearMonth($year,$month,$page,$pageSize){
        $sql="SELECT * from blogs  WHERE YEAR(uploaddate)=$year and  MONTH(uploaddate)=$month  and delete_time is null ORDER BY uploaddate DESC ";
        $sql.="LIMIT ". ($page - 1) * $pageSize . ',' . $pageSize;
        $data=db('blog')->query($sql);
        if ($data){
            return $data;
        }else{
            return ['msg'=>'查询失败!','sql'=>$sql];
        }
    }
    //获取年月博文总数
    public function getCount($year,$month){
        $sql="SELECT count(bid) from blogs  WHERE YEAR(uploaddate)=$year and  MONTH(uploaddate)=$month and delete_time is null ";
        $count=db('blog')->query($sql);
        if ($count){
            return $count;
        }else{
            return ['msg'=>'获取总数失败!','sql'=>$sql];
        }
    }
  //获取上一篇、下一篇博文
//  获取一行数据
   public function getNextBlog($where="2>1",$orderby="bid ASC"){
    $sql="SELECT * FROM blogs where $where and delete_time is NULL ORDER BY $orderby limit 1";
    $data=db('blog')->query($sql);
    if ($data){
        return $data;
    }else{
        return ['msg'=>'获取数据失败!','sql'=>$sql];
    }
   }
}
