<?php
class LxCacheMemcachedDriver implements LxCacheDriver {
    private $memcachedObject = null;
    public function get($key, $distributionKey = null, &$casKey = null) {
        $val = apc_fetch($key, $success);
        if (func_num_args() == 3) {
            $val = &$casKey;
        }
        $this->lastOperation = $success?LxCacheDriver::RES_SUCCESS:LxCacheDriver::RES_FAILURE;
        return $val;
    }
    public function getMulti($keys, $distributionKey = null){
        $success = false;
        $vals = apc_fetch($keys, $success);
        $this->lastOperation = $success?LxCacheDriver::RES_SUCCESS:LxCacheDriver::RES_FAILURE;
        return $vals;
    }
    public function set($key, $value, $ttl = null, $distributionKey = null) {
        if (apc_store($key, $value, $ttl)) {
            $this->lastOperation = LxCacheDriver::RES_SUCCESS;
            return true;
        }
        $this->lastOperation = LxCacheDriver::RES_FAILURE;
        return false;
    }
    public function setMulti($items, $ttl = null, $distributionKey = null ) {
        $errKeys = apc_store($items, null, $ttl);
        $this->lastOperation = empty($errKeys);
        return $errKeys;
    }
    public function cas($key, $value, $cas, $ttl = null, $distributionKey = null){
        if (apc_cas($key, $cas, $value)) {
            $this->lastOperation = LxCacheDriver::RES_SUCCESS;
            return true;
        }
        $this->lastOperation = LxCacheDriver::RES_FAILURE;
        return false;
    }
    public function exists($key, $distributionKey = null) {
        $this->lastOperation = LxCacheDriver::RES_SUCCESS;
        return apc_exists($key);
    }
    public function add($key, $value, $ttl = null, $distributionKey = null) {
        if (apc_add($key, $value, $ttl)) {
            $this->lastOperation = LxCacheDriver::RES_SUCCESS;
            return true;
        }
        $this->lastOperation = LxCacheDriver::RES_FAILURE;
        return false;
    }
    public function update($key, $value, $ttl = null, $distributionKey = null) {
        $success = apc_exists($key)?apc_store($key, $value, $ttl):false;
        if ($success) {
            $this->lastOperation = LxCacheDriver::RES_SUCCESS;
            return true;
        }
        $this->lastOperation = LxCacheDriver::RES_FAILURE;
        return false;
    }
    public function append($key, $value, $ttl = null, $distributionKey = null) {
        // TODO Implement as a while
        $val = apc_fetch ($key, $success);
        if ($success) {
            if (apc_cas($key, $val, $val.$value)) {
                $this->lastOperation = LxCacheDriver::RES_SUCCESS;
                return true;
            }
        }
        $this->lastOperation = LxCacheDriver::RES_FAILURE;
        return false;
    }
    public function prepend($key, $value, $ttl = null, $distributionKey = null) {
        // TODO Implement as a while
        $val = apc_fetch ($key, $success);
        if ($success) {
            if (apc_cas($key, $val, $value.$val)) {
                $this->lastOperation = LxCacheDriver::RES_SUCCESS;
                return true;
            }
        }
        $this->lastOperation = LxCacheDriver::RES_FAILURE;
        return false;
    }
    public function increment($key, $value = 1) {
        $newVal = apc_inc($key, $value, $success);
        if ($success) {
            $this->lastOperation = LxCacheDriver::RES_SUCCESS;
        } else {
            $this->lastOperation = LxCacheDriver::RES_FAILURE;
        }
        return $newVal;
    }
    public function decrement($key, $value = 1) {
        $newVal = apc_dec($key, $value, $success);
        if ($success) {
            $this->lastOperation = LxCacheDriver::RES_SUCCESS;
        } else {
            $this->lastOperation = LxCacheDriver::RES_FAILURE;
        }
        return $newVal;
    }
    public function delete($key, $delay = null, $distributionKey = null) {
        if (apc_delete($key)) {
            $this->lastOperation = LxCacheDriver::RES_SUCCESS;
            return true;
        }
        $this->lastOperation = LxCacheDriver::RES_FAILURE;
        return false;
    }
    public function deleteMulti($keys, $delay = null, $distributionKey = null) {
        if (apc_delete($keys)) {
            $this->lastOperation = LxCacheDriver::RES_SUCCESS;
            return true;
        }
        $this->lastOperation = LxCacheDriver::RES_FAILURE;
        return false;
    }
    public function getResult() {
        return $this->lastOperation;
    }
    public function flush($delay = 0) {
        return apc_clear_cache('user');
    }
    public function stats();
}
