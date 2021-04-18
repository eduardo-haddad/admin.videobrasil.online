<?php
define("WEBSITE_MODE_RUNNING_LIVE",true);

require_once('SendEmail.php');
require_once('AlertSystemConfig.php');

$config = new AlertSystemConfig();
$data = $config->getConfig();
$key = $data['sparkpostApiKey'];

$sender = new SendEmail($key);
$sender->setDebug(true);
$sender->sendTest('Ricardo','ricardo@agenteimovel.com.br');
echo 'ok';
?>