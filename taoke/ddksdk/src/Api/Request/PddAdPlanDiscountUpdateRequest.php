<?php
namespace Com\Pdd\Pop\Sdk\Api\Request;

use Com\Pdd\Pop\Sdk\PopBaseHttpRequest;
use Com\Pdd\Pop\Sdk\PopBaseJsonEntity;

class PddAdPlanDiscountUpdateRequest extends PopBaseHttpRequest
{
    public function __construct()
	{

	}
	/**
	* @JsonProperty(Long, "plan_id")
	*/
	private $planId;

	/**
	* @JsonProperty(List<\Com\Pdd\Pop\Sdk\Api\Request\PddAdPlanDiscountUpdateRequest_DiscountsItem>, "discounts")
	*/
	private $discounts;

	/**
	* @JsonProperty(Integer, "scene_type")
	*/
	private $sceneType;

	protected function setUserParams(&$params)
	{
		$this->setUserParam($params, "plan_id", $this->planId);
		$this->setUserParam($params, "discounts", $this->discounts);
		$this->setUserParam($params, "scene_type", $this->sceneType);

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
		return "pdd.ad.plan.discount.update";
	}

	public function setPlanId($planId)
	{
		$this->planId = $planId;
	}

	public function setDiscounts($discounts)
	{
		$this->discounts = $discounts;
	}

	public function setSceneType($sceneType)
	{
		$this->sceneType = $sceneType;
	}

}

class PddAdPlanDiscountUpdateRequest_DiscountsItem extends PopBaseJsonEntity
{

	public function __construct()
	{

	}

	/**
	* @JsonProperty(Integer, "rate")
	*/
	private $rate;

	/**
	* @JsonProperty(Integer, "index")
	*/
	private $index;

	public function setRate($rate)
	{
		$this->rate = $rate;
	}

	public function setIndex($index)
	{
		$this->index = $index;
	}

}
