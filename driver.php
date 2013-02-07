<?php
interface LxCacheDriver {
    const RES_SUCCESS = 0;
    const RES_FAILURE = 1;
    public function get($key, $distributionKey = null, &$casKey = null);
    public function getMulti($keys, $distributionKey = null);
    public function set($key, $value, $ttl = null, $distributionKey = null);
    public function setMulti($items, $ttl = null, $distributionKey = null );
    public function cas($key, $value, $cas, $ttl = null, $distributionKey = null);
    public function exists($key, $distributionKey = null);
    public function add($key, $value, $ttl = null, $distributionKey = null);
    public function update($key, $value, $ttl = null, $distributionKey = null);
    public function append($key, $value, $ttl = null, $distributionKey = null);
    public function prepend($key, $value, $ttl = null, $distributionKey = null);
    public function increment($key, $value = 1);
    public function decrement($key, $value = 1);
    public function delete($key, $delay = null, $distributionKey = null);
    public function deleteMulti($keys, $delay = null, $distributionKey = null);
    public function getResult();
    public function flush($delay);
    public function stats();
}
