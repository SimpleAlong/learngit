<?php
 
 
class index_EweiShopV2Page extends mobilepage 
{
	public function main() 
	{
	 
		
	}

  //商品列表页
	public function goods_list(){
	 	global $_GPC;
	 	$showrow =8;//一页显示的行数
		$curpage = empty($_GET['page'])?1:$_GET['page'];//当前的页,还应该处理非数字的情况
    $category=$this->Getcategory();
		 $id=$_GPC['id'];
     $marketprice=$_GPC['marketprice'];
     $sales=$_GPC['sales'];
     if($marketprice!=''){
      $desc=$marketprice;
     }elseif ($sales!='') {
        $desc=$sales;
     }else{
      $desc='createtime';
     }
      $categorynew=$this->Get_pan($id);  
		 if($categorynew['parentid'] >0){
		 $cw=$this->Get_pan($categorynew['parentid']);
  
		 }
		 if($categorynew['parentid']==0){
      //查询列表
      $select='id,pcate,title,ccate,thumb,productprice,marketprice';
      $table_name='ewei_shop_goods';
      $where='pcate =:pcate && status=1';
      $array=array(':pcate'=>$id);
      $key=$id;
        
      $rw=$this->Get_table($table_name,$select,$where,$array);
		 	
		 	$total= count($rw);
      $row =$this->Get_table('ewei_shop_goods','id,title,thumb,productprice,marketprice','pcate='.$id.' && status=1 ORDER BY '.$desc.' DESC limit '.($curpage-1)*$showrow.','.$showrow.''); 	
		 }else{
      $rw=$this->Get_table('ewei_shop_goods','id,pcate,title,ccate,thumb,productprice,marketprice','ccate = :ccate  && status=1',array(':ccate' =>$id),$id);
		 	$total= count($rw);
       $row =$this->Get_table('ewei_shop_goods','id,title,thumb,productprice,marketprice','ccate='.$id.' && status=1 ORDER BY '.$desc.' DESC limit '.($curpage-1)*$showrow.','.$showrow.'');
		 }
		    if($total>$showrow){ 
           $page = new feiyelei_EweiShopV2Page($total,$showrow,$curpage,2);
            $fenye=$page->myde_write();
            }
		include $this->template(); 
	}

  
}


 class feiyelei_EweiShopV2Page extends mobilepage{
    private $myde_total;          //总记录数
    private $myde_size;           //一页显示的记录数
    private $myde_page;           //当前页
    private $myde_page_count;     //总页数
    private $myde_i;              //起头页数
    private $myde_en;             //结尾页数
    private $myde_url;            //获取当前的url
    /*
     * $show_pages
     * 页面显示的格式，显示链接的页数为2*$show_pages+1。
     * 如$show_pages=2那么页面上显示就是[首页] [上页] 1 2 3 4 5 [下页] [尾页] 
     */
    private $show_pages;
  
    public function __construct($myde_total=1,$myde_size=1,$myde_page=1,$myde_url,$show_pages=2){
        $this->myde_total = $this->numeric($myde_total);
        $this->myde_size = $this->numeric($myde_size);
        $this->myde_page = $this->numeric($myde_page);
        $this->myde_page_count = ceil($this->myde_total/$this->myde_size);
        $this->myde_url = $myde_url;
        if($this->myde_total<0) $this->myde_total=0;
        if($this->myde_page<1)  $this->myde_page=1;
        if($this->myde_page_count<1) $this->myde_page_count=1;
        if($this->myde_page>$this->myde_page_count) $this->myde_page=$this->myde_page_count;
        $this->limit = ($this->myde_page-1)*$this->myde_size;
        $this->myde_i=$this->myde_page-$show_pages;
        $this->myde_en=$this->myde_page+$show_pages;
        if($this->myde_i<1){
          $this->myde_en=$this->myde_en+(1-$this->myde_i);
          $this->myde_i=1;
        }
        if($this->myde_en>$this->myde_page_count){
          $this->myde_i = $this->myde_i-($this->myde_en-$this->myde_page_count);
          $this->myde_en=$this->myde_page_count;
        }
        if($this->myde_i<1)$this->myde_i=1;

    }
    //检测是否为数字
    private function numeric($num){
      if(strlen($num)){
         if(!preg_match("/^[0-9]+$/",$num)){
             $num=1;
           }else{
             $num = substr($num,0,11);
         }
      }else{
               $num=1;
      }
      return $num;
    }
    //地址替换
    private function page_replace($page){
    	 $url =parse_url($_SERVER['REQUEST_URI']);
                parse_str($url['query'],$queryArr);
                unset($queryArr['page']);
                $queryStr = http_build_query($queryArr);
                return  $url['path'].'?'.$queryStr.'&page='.$page;  
       // return str_replace("{page}",$page,$this->myde_url);
    }
    //首页
    private function myde_home(){
        if($this->myde_page!=1){
            return "<a href=".$this->page_replace(1)." title='首页'>首页</a>";
        }else{
            return ;
        }
    }
    //上一页
    private function myde_prev(){
       if($this->myde_page!=1){
           return "<a href=".$this->page_replace($this->myde_page-1)." title='上一页'>上一页</a>";
       }else{
              return ;
       }
    }
    //下一页
    private function myde_next(){
        if($this->myde_page!=$this->myde_page_count){
            return "<a href=".$this->page_replace($this->myde_page+1)." title='下一页'>下一页</a>";
        }else{
            return;
        }
    }
    //尾页
    private function myde_last(){
        if($this->myde_page!=$this->myde_page_count){
            return "<a href=".$this->page_replace($this->myde_page_count)." title='尾页'>尾页</a>";
        }else{
            return;
        }
    }
    //输出
    public function myde_write($id='page'){
       $str ="<div id=" .$id. ">";
       $str.=$this->myde_home();
       $str.=$this->myde_prev();
       if($this->myde_i>1){
            $str.="<p class='pageEllipsis'>...</p>";
       }
       for($i=$this->myde_i;$i<=$this->myde_en;$i++){
            if($i==$this->myde_page){
                $str.="<a href=".$this->page_replace($i)." title='第'.$i.'页' class='cur'>$i</a>";
            }else{
          $str.="<a href=".$this->page_replace($i)." title='第'.$i.'页'>$i</a>";
            }
       }
       if( $this->myde_en<$this->myde_page_count ){
            $str.="<p class='pageEllipsis'>...</p>";
       }
       $str.=$this->myde_next();
       $str.=$this->myde_last();
       $str.="<p class='pageRemark'>共<b>".$this->myde_page_count.
             "</b>页<b>".$this->myde_total."</b>条数据</p>";
       $str.="</div>";
       return $str;
    }
}
?>
<html>
<header>
<style type="text/css">

#page{
    height:40px;
    padding:20px 0px;
    float: right;
    position: relative;
}
#page a{
    display:block;
    float:left;
    margin-right:10px;
    padding:2px 12px;
    height:24px;
    border:1px #cccccc solid;
    background:#fff;
    text-decoration:none;
    color:#808080;
    font-size:12px;
    line-height:24px;
}
#page a:hover{
    color:red;
    border:1px red solid;
}
#page a.cur{
    border:none;
    background:red;
    color:#fff;
}
#page p{
    float:left;
    padding:2px 12px;
    font-size:12px;
    height:24px;
    line-height:24px;
    color:#bbb;
    border:1px #ccc solid;
    background:#fcfcfc;
    margin-right:8px;
  
}
#page p.pageRemark{
    border-style:none;
    background:none;
    margin-right:0px;
    padding:4px 0px;
    color:#666;
}
#page p.pageRemark b{
    color:red;
}
#page p.pageEllipsis{
    border-style:none;
    background:none;
    padding:4px 0px;
    color:#808080;
}
 
</style>
</header>
<body>
   <div class="pages" style="width: 100%">
                {$fenye}
          </div>
</body>

</html>