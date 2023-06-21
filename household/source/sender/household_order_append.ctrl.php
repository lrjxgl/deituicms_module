<?php
class household_order_appendControl extends skymvc{

    public function onDefault(){

    }
    public function onSave(){
		$hhconfig=M("mod_household_config")->selectRow("1");
		if($hhconfig["append_money"]!=1){
			$this->goAll("未开启尾款支付",1);
		}
        $orderid=get_post("orderid","i");
        $order=M("mod_household_order")->selectRow("orderid=".$orderid);
        if($order["senderid"]!=SENDERID){
            $this->goAll("暂无权限",1);
        }
        $append=M("mod_household_order_append")->selectRow("orderid=".$orderid);
        if($append){
            $this->goAll("已经添加过了",1);
        }
        $money=post("money","f");
        if($money<=$order["money"]){
            $this->goAll("总费用比订金小无需添加",1);
        }
        $content=post("content","h");
        if(empty($content)){
            $this->goAll("请填写费用内容",1);
        }
        $retype=post("retype","i");
        $ispay=0;
        if($retype==0 ){
            $ispay=1;
        }
        M("mod_household_order_append")->insert(array(
            "orderid"=>$orderid,
            "total_money"=>$money,
            "content"=>$content,
            "pay_money"=>$money-$order["money"],
            "createtime"=>date("Y-m-d H:i:s"),
			"userid"=>$order["userid"],
			"senderid"=>$order["senderid"],
            "retype"=>$retype,
            "ispay"=>$ispay
            
        ));
        $this->goAll("保存成功");

    }

}