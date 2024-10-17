<?php

namespace App\Api\Cache;

class Cache {
    private $cache_key_prefix;

    public function __construct($prefix = 'cache_') {
        $this->cache_key_prefix = $prefix;
    }

    // Cache'e veri ekler
    public function set($key, $value, $ttl = 3600) {
        $cache_key = $this->getCacheKey($key);
        return apcu_store($cache_key, $value, $ttl);
    }

    // Cache'den veri çeker
    public function get($key) {
        $cache_key = $this->getCacheKey($key);
        return apcu_fetch($cache_key);
    }

    // Cache'de verinin var olup olmadığını kontrol eder
    public function has($key) {
        $cache_key = $this->getCacheKey($key);
        return apcu_exists($cache_key);
    }

    // Cache'deki veriyi temizler
    public function delete($key) {
        $cache_key = $this->getCacheKey($key);
        return apcu_delete($cache_key);
    }

    // Tüm cache'i temizler
    public function clear() {
        return apcu_clear_cache();
    }

    // Cache anahtarını ön ek ile oluşturur
    private function getCacheKey($key) {
        return $this->cache_key_prefix . md5($key);
    }
}
