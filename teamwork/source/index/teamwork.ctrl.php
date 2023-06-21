<?php
class teamworkControl extends skymvc
{
    public function onDefault()
    {
		M("login")->checkLogin();
        $userid=M("login")->userid;
        $prolist=MM("teamwork","teamwork_shop_product")->Dselect(array(
                "where"=>" status in(0,1,2) AND  userid=".$userid,
                "fields"=>"id,title,createtime"
            ));
        //我参与的项目
        $sql="select p.title,p.id,p.createtime from ".table('mod_teamwork_shop_product_user')." as u 
				left join ".table('mod_teamwork_shop_product')." as p
				on u.productid=p.id
				where p.status in(0,1,2) AND u.userid={$userid}
			";
        $userProlist=MM("teamwork","teamwork_shop_product")->getAll($sql);
        if ($userProlist) {
            foreach ($userProlist as $k=>$v) {
                $v['timeago']=timeago(strtotime($v['createtime']));
                $userProlist[$k]=$v;
            }
        }
        $sql="select p.title,p.id,p.createtime,p.status,p.orderindex,p.endtime from ".table('mod_teamwork_shop_product_item_user')." as u 
				left join ".table('mod_teamwork_shop_product_item')." as p
				on u.itemid=p.id
				where u.userid={$userid} AND  p.status in(0,1,2,3,4)
				order by p.id DESC 
				limit 0,12
			";
        $itemlist=M("mod_teamwork_shop_product")->getAll($sql);
        if ($itemlist) {
            $statusList=M("mod_teamwork_shop_product_item")->statusList();
            foreach ($itemlist as $k=>$v) {
                $v['timeago']=timeago(strtotime($v['createtime']));
                $v['status_name']=$statusList[$v['status']];
                $itemlist[$k]=$v;
            }
        }
        $loglist=MM("teamwork","teamwork_shop_product_item_log")->Dselect(array(
                "where"=>" userid=".$userid,
                "order"=>"id DESC",
                "limit"=>12
            ));
             
        $this->smarty->goAssign(array(
                "prolist"=>$prolist,
                "userProlist"=>$userProlist,
                "itemlist"=>$itemlist,
                "loglist"=>$loglist,
            ));
        $this->smarty->display("index.html");
    }
}
