<?php
/**
 * LxCache APC driver
 * 
 * @author Xavier De Cock <xdecock@gmail.com>
 */
class LxCacheApcDriver implements LxCacheDriver {
	/**
	 * Last Operation result cache
	 * @var int
	 */
    private $lastOperation = -1;
    
    /**
     * Return a Key from APC
     * 
     * @param $keys string key to fetch from APC
     * @param $distributionKey string Hashing Key unused in APC
     * @param $casKey mixed
     * @return mixed
     * @see LxCacheDriver::get()
     */
    public function get($key, $distributionKey = null, &$casKey = null) {
        $val = apc_fetch($key, $success);
        if (func_num_args() == 3) {
            $val = &$casKey;
        }
        $this->lastOperation = $success?LxCacheDriver::RES_SUCCESS:LxCacheDriver::RES_FAILURE;
        return $val;
    }
    
    /**
     * Return multiple keys from APC
     * 
     * @param $keys array list of keys to fetch
     * @param $distributionKey string Hashing Key unused in APC
     * @return array 
     * @see LxCacheDriver::getMulti()
     */
    public function getMulti($keys, $distributionKey = null){
        $success = false;
        $vals = apc_fetch($keys, $success);
        $this->lastOperation = $success?LxCacheDriver::RES_SUCCESS:LxCacheDriver::RES_FAILURE;
        return $vals;
    }
    
    /**
     * Set one key in APC
     * 
     * @param $key string key to set
     * @param $value mixed new value
     * @param $ttl int
     * @param $distributionKey string Hashing Key unused in APC
     * @return bool
     * @see LxCacheDriver::set()
     */
    public function set($key, $value, $ttl, $distributionKey = null) {
        if (apc_store($key, $value, $ttl)) {
            $this->lastOperation = LxCacheDriver::RES_SUCCESS;
            return true;
        }
        $this->lastOperation = LxCacheDriver::RES_FAILURE;
        return false;
    }
    
    /**
     * Sets Multiple Keys in APC
     * 
     * @param $items array key=>value array
     * @param $ttl int
     * @param $distributionKey string Hashing Key unused in APC
     * @see LxCacheDriver::setMulti()
     */
    public function setMulti($items, $ttl, $distributionKey = null ) {
        $errKeys = apc_store($items, null, $ttl);
        $this->lastOperation = empty($errKeys);
        return $errKeys;
    }
    
    /**
     * Compare and swaps value
     * 
     * @param $key string Key to update
     * @param $value mixed New Value
     * @param $cas mixed Cas Value
     * @param $ttl int
     * @param $distributionKey string Hashing Key unused in APC
     * @return bool
     * @see LxCacheDriver::cas()
     */
    public function cas($key, $value, $cas, $ttl, $distributionKey = null){
        if (apc_cas($key, $cas, $value)) {
            $this->lastOperation = LxCacheDriver::RES_SUCCESS;
            return true;
        }
        $this->lastOperation = LxCacheDriver::RES_FAILURE;
        return false;
    }
    
    /**
     * Checks Key Existance
     * 
     * @param $key string Key to check
     * @param $distributionKey string Hashing Key unused in APC
     * @return bool
     * @see LxCacheDriver::exists()
     */
    public function exists($key, $distributionKey = null) {
        $this->lastOperation = LxCacheDriver::RES_SUCCESS;
        return apc_exists($key);
    }
    
    /**
     * Atomicaly adds a key
     * 
     * @param $key string Key to add
     * @param $value mixed Value to store
     * @param $ttl int
     * @param $distributionKey string Hashing Key unused in APC
     * @return bool
     * @see LxCacheDriver::add()
     */
    public function add($key, $value, $ttl, $distributionKey = null) {
        if (apc_add($key, $value, $ttl)) {
            $this->lastOperation = LxCacheDriver::RES_SUCCESS;
            return true;
        }
        $this->lastOperation = LxCacheDriver::RES_FAILURE;
        return false;
    }
    
    /**
     * Updates a key
     * 
     * @param $key string Key to update
     * @param $value mixed Value to store
     * @param $ttl int
     * @param $distributionKey string Hashing Key unused in APC
     * @return bool
     * @see LxCacheDriver::update()
     */
    public function update($key, $value, $ttl, $distributionKey = null) {
        $success = apc_exists($key)?apc_store($key, $value, $ttl):false;
        if ($success) {
            $this->lastOperation = LxCacheDriver::RES_SUCCESS;
            return true;
        }
        $this->lastOperation = LxCacheDriver::RES_FAILURE;
        return false;
    }
    
    /**
     * Appends a value to a given key
     * 
     * @param $key string Key to append to
     * @param $value string to append to the key value
     * @param $ttl int new TTL
     * @param $distributionKey string Hashing Key unused in APC
     * @return bool
     * @see LxCacheDriver::append()
     */
    public function append($key, $value, $ttl, $distributionKey = null) {
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
    
    /**
     * Prepends a value to a given key
     * 
     * @param $key string Key to prepend to
     * @param $value string to prepend to the key value
     * @param $ttl int new TTL
     * @param $distributionKey string Hashing Key unused in APC
     * @return bool
     * @see LxCacheDriver::prepend()
     */
    public function prepend($key, $value, $ttl, $distributionKey = null) {
        // TODO Implement as a while (retry)
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
    
    /**
     * Increment the stored value for a given key
     * 
     * @param $key string key to increment
     * @param $value int increment by $value steps
     * @return int new value
     * @see LxCacheDriver::increment()
     */
    public function increment($key, $value = 1) {
        $newVal = apc_inc($key, $value, $success);
        if ($success) {
            $this->lastOperation = LxCacheDriver::RES_SUCCESS;
        } else {
            $this->lastOperation = LxCacheDriver::RES_FAILURE;
        }
        return $newVal;
    }
    
    /**
     * Decrement the stored value for a given key
     * 
     * @param $key string key to decrement
     * @param $value int decrement by $value steps
     * @return int new value
     * @see LxCacheDriver::decrement()
     */
    public function decrement($key, $value = 1) {
        $newVal = apc_dec($key, $value, $success);
        if ($success) {
            $this->lastOperation = LxCacheDriver::RES_SUCCESS;
        } else {
            $this->lastOperation = LxCacheDriver::RES_FAILURE;
        }
        return $newVal;
    }
    
    /**
     * Deletes a key from the cache
     * 
     * @param $key string
     * @param $delay int delete delay unused in apc
     * @param $distributionKey string Hashing Key unused in APC
     * @see LxCacheDriver::delete()
     */
    public function delete($key, $delay = null, $distributionKey = null) {
        if (apc_delete($key)) {
            $this->lastOperation = LxCacheDriver::RES_SUCCESS;
            return true;
        }
        $this->lastOperation = LxCacheDriver::RES_FAILURE;
        return false;
    }
    
    /**
     * Deletes a set of keys from the cache
     * 
     * @param $keys array list of keys to delete
     * @param $delay int delete delay unused in apc
     * @param $distributionKey string Hashing Key unused in APC
     * @return bool
     * @see LxCacheDriver::deleteMulti()
     */
    public function deleteMulti($keys, $delay = null, $distributionKey = null) {
        if (apc_delete($keys)) {
            $this->lastOperation = LxCacheDriver::RES_SUCCESS;
            return true;
        }
        $this->lastOperation = LxCacheDriver::RES_FAILURE;
        return false;
    }
    
    /**
     * Return last operation result Code
     * 
     * @see LxCacheDriver::getResult()
     * @return int
     */
    public function getResult() {
        return $this->lastOperation;
    }
    
    /**
     * Flush APC user Cache
     * 
     * @param $delay Delay before flushing unused in apc
     * @return bool
     * @see LxCacheDriver::flush()
     */
    public function flush($delay = 0) {
        return apc_clear_cache('user');
    }
    
    /**
     * Returns APC Stats
     * 
     * @return array
     * @see LxCacheDriver::stats()
     */
    public function stats(){
    	return array();
    }
}
