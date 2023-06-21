<?php
namespace Com\Pdd\Pop\Sdk\Api\Request;

use Com\Pdd\Pop\Sdk\PopBaseHttpRequest;
use Com\Pdd\Pop\Sdk\PopBaseJsonEntity;

class PddGoodsGetRelationRequest extends PopBaseHttpRequest
{
    public function __construct()
	{

	}
	/**
	* @JsonProperty(List<Long>, "pdd_goods_id")
	*/
	private $pddGoodsId;

	protected function setUserParams(&$params)
	{
		$this->setUserParam($params, "pdd_goods_id", $this->pddGoodsId);

	}

	public function getVersion()
	{
		return "V1";
	}

	public function getDataType()
	{
		return "JSON";
	}

	public function getType()
	{
		return "pdd.goods.get.relation";
	}

	public function setPddGoodsId($pddGoodsId)
	{
		$this->pddGoodsId = $pddGoodsId;
	}

}
