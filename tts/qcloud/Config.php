 <?php
 /******************************************************
 * author : ruskinli
 * date : 2019/05/05
 * desc : php sdk of tts
 ******************************************************/
/******************************************************
 * 通常情况下，本类中的所有参数 只需配置一次即可。
 * TEXT参数为想要合成的文本
 * SESSION_ID为本次会话的id，建议每次修改为不重复的，不修改也可以。
 * 关于云API账号中的APPID，SecretId 与 SecretKey查询方法，可参考：
 * https://cloud.tencent.com/document/product/441/6203
 * 具体路径为：点控制台右上角您的账号-->选：访问管理-->点左边菜单的：访问秘钥-->API秘钥管理
******************************************************/

class Config {
	// -------------- Required. 请登录腾讯云官网控制台获取 ---------------------
	static $SECRET_ID = "";
	static $SECRET_KEY = "";
	static $APPID = 12510;

	// --------------- 必需，不可更改 ---------------------
	/* 请求参数设置,无需改变 */
	static $ACTION = 'TextToStreamAudio';

	// 默认编码, php只支持pcm格式
	static $CODEC = 'mp3';

	//模型类型，1：默认模型
	static $MODEL_TYPE = 1;
	// --------------- Optional, 请按需修改 ----------------

	// 请求话术，不超过500字符
	static $TEXT = '你好';

	/* 语速，范围：[-2，2]，分别对应不同语速：
	-2代表0.6倍
	-1代表0.8倍
	0代表1.0倍（默认）
	1代表1.2倍
	2代表1.5倍
	输入除以上整数之外的其他参数不生效，按默认值处理。*/
	static $SPEED = 0;

	// 音量大小，范围：[0，10]，分别对应11个等级的音量，默认值为0，代表正常音量。没有静音选项
	static $VOLUME = 5;
	
	/* 音频采样率：
	16000：16k（默认）
	8000：8k
	*/
	static $SAMPLE_RATE = 16000;
	
	# 1 or 2. 语音声道数。在电话 8k通用模型下支持 1和 2，其他模型仅支持 1声道
	static $PROJECT_ID = 0;
	
	/*音色：
	0：亲和女声（默认）
	1：亲和男声
	2：成熟男声
	3：活力男声
	4：温暖女声
	5：情感女声
	6：情感男声
	*/
	static $VOICET_YPE = 0;

	// 语言 1 ： CN ， 2 ： EN
	static $PRIMARY_LANGUAGE = 1;

	//请求鉴权的有效时间，单位 s，默认1h
	static $EXPIRED = 3600;
	
	//会话id
	static $SESSION_ID = '1234';


	/**
	 * 校验必要参数
	 */
	public static function verify() {
		if (empty (self :: $SECRET_KEY)) {
			echo "secret_key can not be empty";
			return -1;
		}
		if (empty (self :: $SECRET_ID)) {
			echo "secretid can not be empty";
			return -1;
		}
		if (empty (self :: $APPID)) {
			echo "appid can not be empty";
			return -1;
		}
		if (empty (self :: $ACTION)) {
			self :: $ACTION = "TextToStreamAudio";
			return 0;
		}
		if (empty (self :: $CODEC)) {
			self :: $CODEC = "pcm";
			return 0;
		}
		/*echo "Verify finished.";*/
	}
}
Config :: verify();
?>
