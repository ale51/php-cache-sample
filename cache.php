<?php

interface CacheHandler {
    public function cache(array $key, $ttl_seconds, callable $func);
    public function delete(array $key);
    public function deleteAll();
}

class Cache {

    public static $instance;

    public static function setInstance(CacheHandler $cacheHandler) {
        self::$instance = $cacheHandler;
    }

    public static function getInstance()
    {
        return self::$instance;
    }
}

class FileCacheHandler implements CacheHandler {

    public $data;

    function __construct()
    {
        $this->data = json_decode(file_get_contents("./cache.json"), true);
    }

    function __destruct() {
        file_put_contents("./cache.json", json_encode($this->data));
    }

    public function cache(array $key, $ttl_seconds, callable $func) {
        $key_string = join("-",$key);

        $value = $this->data[$key_string]['value'];
        $cached_at = $this->data[$key_string]['cached_at'];

        if($value && time() - $cached_at < $ttl_seconds) {
            echo "cache hit\n";
            return $value;
        }

        echo "cache no hit\n";
        $return = $func();

        $this->data[$key_string]['cached_at'] = time();
        $this->data[$key_string]['value'] = $return;

        return $return;
    }

    public function delete(array $key) {
        // 不要かなそもそも。
    }

    public function deleteAll() {
        $this->data = [];
    }
}
