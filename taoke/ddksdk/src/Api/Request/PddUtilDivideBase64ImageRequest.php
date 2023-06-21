<?php
namespace Com\Pdd\Pop\Sdk\Api\Request;

use Com\Pdd\Pop\Sdk\PopBaseHttpRequest;
use Com\Pdd\Pop\Sdk\PopBaseJsonEntity;

class PddUtilDivideBase64ImageRequest extends PopBaseHttpRequest
{
    public function __construct()
	{

	}
	/**
	* @JsonProperty(String, "img_data")
	*/
	private $imgData;

	protected function setUserParams(&$params)
	{
		$this->setUserParam($params, "img_data", $this->imgData);

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
		return "pdd.util.divide.base64.image";
	}

	public function setImgData($imgData)
	{
		$this->imgData = $imgData;
	}

}
