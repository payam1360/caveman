<?php

header('Content-type: text/event-stream');
header('Cache-Control: no-cache');
require '../../vendor/autoload.php';
use Orhanerday\OpenAi\OpenAi;
    
$open_ai_key = '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';
$open_ai = new OpenAi($open_ai_key);
$opts = [
    'model' => 'text-davinci-003',
    'prompt' => 'provide one meal plans for 2500 calories per day for losing weight',
    'temperature' => 0.9,
    'max_tokens' => 250,
    'frequency_penalty' => 0,
    'presence_penalty' => 0.6,
    'stream' => true,
];
$open_ai->completion($opts, function ($curl_info, $data) {
    echo $data . "<br><br>";
    echo PHP_EOL;
    ob_flush();
    flush();
    return strlen($data);
});
?>



