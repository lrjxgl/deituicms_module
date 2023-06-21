<?php
require ('TTSUtil.php');

# 1. 先修改好Config.php文件中的配置值。
# 2. TEXT为每次请求的文本，SESSION_ID建议每次请求修改成唯一id，例如uuid。
Config :: $TEXT = "你好，五一节准备去哪里玩啊";
Config :: $SESSION_ID = guid();
//echo "Session id : " . Config :: $SESSION_ID . "\n";

# 2. 调用获取pcm格式音频
$result = getVoice();
$pcm_file = fopen('./test.pcm', "w");
fwrite($pcm_file, $result);
?>
 