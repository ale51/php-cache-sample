<?php

require_once('./cache.php');

$time_start = microtime(true);

## 使い方
// CacheHandlerインターフェースを実装したものならなんでも渡せる。余力があれば、SQLiteCacheHandlerやRedisCacheHandlerなども試してみる。
// 下記の処理はindex.phpの上の方で呼び出しておく。
Cache::setInstance(new FileCacheHandler());

// キャッシュしたい場所でキャッシュする。この例では、heavy_processing_functionメソッドをキャッシュする
/** @var FileCacheHandler $cache_handler */
$cache_handler = Cache::getInstance();
$hoge_id = 1;
$var_id = 2;
//$result = heavy_processing_function($hoge_id, $var_id);
$result = $cache_handler->cache(["heavy_processing_function", $hoge_id, $var_id], 5, function () use ($hoge_id, $var_id){
    return heavy_processing_function($hoge_id, $var_id);
});
var_dump($result);

$time = microtime(true) - $time_start;
echo "{$time} 秒";

/**
 * 重い処理
 *
 * @param $hoge_id
 * @param $var_id
 * @return array
 */
function heavy_processing_function($hoge_id, $var_id){
    sleep(5);
    return [
        "ad_id" => 1,
        "name" => "U-NEXT",
        "price" => 1000,
    ];
}

// キャッシュ削除する場合
//$cache_handler->deleteAll();
