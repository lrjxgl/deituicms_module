<?php
$client_id="5299841766704a069947c6f869a63aca";
$url=urlencode("https://www.deitui.com/module/taoken/ddksdk/setToken.php");
$url="https://jinbao.pinduoduo.com/open.html?client_id=$client_id&response_type=code&redirect_uri=$url";
header("Location: $url");