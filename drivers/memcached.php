<?php
/**
 * LxCache Memcached driver
 * 
 * @author Xavier De Cock <xdecock@gmail.com>
 */
class LxCacheMemcachedDriver implements LxCacheDriver {
	/**
	 * Last Operation result cache
	 * @var Memcached
	 */
    private $memcachedObject = -1;
    
    public function __construct(Memcached $memcached) {
    	$this->memcachedObject = $memcached;
    }
    
    /**
     * Return a Key from Memcached pool
     * 
     * @param $keys string key to fetch
     * @param $distributionKey string Hashing Key
     * @param $casKey mixed
     * @return mixed
     * @see LxCacheDriver::get()
     */
    public function get($key, $distributionKey = null, &$casKey = null) {
    	if ($distributionKey === null) {
    		return $this->memcachedObject->get($key, null, $casKey);
    	} else {
    		return $this->memcachedObject->getByKey($distributionKey, $key, null, $casKey);
    	}
    }
    
    /**
     * Return multiple keys from Memcached pool
     * 
     * @param $keys array list of keys to fetch
     * @param $distributionKey string Hashing Key
     * @return array 
     * @see LxCacheDriver::getMulti()
     */
    public function getMulti($keys, $distributionKey = null){
        if ($distributionKey === null) {
    		return $this->memcachedObject->getMulti($keys);
    	} else {
    		return $this->memcachedObject->getMultiByKey($distributionKey, $keys);
    	}
    }
    
    /**
     * Set one key in a memcached pool
     * 
     * @param $key string key to set
     * @param $value mixed new value
     * @param $ttl int
     * @param $distributionKey string Hashing Key
     * @return bool
     * @see LxCacheDriver::set()
     */
    public function set($key, $value, $ttl, $distributionKey = null) {
        if ($distributionKey === null) {
    		return $this->memcachedObject->set($key, $value, $ttl);
    	} else {
    		return $this->memcachedObject->setByKey($distributionKey, $key, $value, $ttl);
    	}
    }
    
    /**
     * Sets Multiple Keys in a memcached pool
     * 
     * @param $items array key=>value array
     * @param $ttl int
     * @param $distributionKey string Hashing Keys
     * @see LxCacheDriver::setMulti()
     */
    public function setMulti($items, $ttl, $distributionKey = null ) {
        if ($distributionKey === null) {
        	return $this->memcachedObject->setMulti($items, $ttl);
        } else {
        	return $this->memcachedObject->setMultiByKey($distributionKey, $items, $ttl);
        }
    }
    
    /**
     * Compare and swaps value
     * 
     * @param $key string Key to update
     * @param $value mixed New Value
     * @param $cas mixed Cas Value
     * @param $ttl int
     * @param $distributionKey string Hashing Key
     * @return bool
     * @see LxCacheDriver::cas()
     */
    public function cas($key, $value, $cas, $ttl, $distributionKey = null){
        if ($distributionKey === null) {
        	return $this->memcachedObject->cas($cas, $key, $value, $ttl);
        } else {
        	return $this->memcachedObject->casByKey($cas, $distributionKey, $key, $value, $ttl);
        }
    }
    
    /**
     * Checks Key Existance
     * 
     * @param $key string Key to check
     * @param $distributionKey string Hashing Key
     * @return bool
     * @see LxCacheDriver::exists()
     */
    public function exists($key, $distributionKey = null) {
    	if ($distributionKey === null) {
    		$this->memcachedObject->get($key);
    	} else {
    		$this->memcachedObject->getByKey($distributionKey, $key);
    	}
    	if ($this->memcachedObject->getResultCode()=== Memcached::RES_SUCCESS) {
    		return true;
    	}
    	return false;
    }
    
    /**
     * Atomicaly adds a key
     * 
     * @param $key string Key to add
     * @param $value mixed Value to store
     * @param $ttl int
     * @param $distributionKey string Hashing Key
     * @return bool
     * @see LxCacheDriver::add()
     */
    public function add($key, $value, $ttl, $distributionKey = null) {
        if ($distributionKey === null) {
    		return $this->memcachedObject->add($key, $value, $ttl);
    	} else {
    		return $this->memcachedObject->addByKey($distributionKey, $key, $value, $ttl);
    	}
    }
    
    /**
     * Updates a key
     * 
     * @param $key string Key to update
     * @param $value mixed Value to store
     * @param $ttl int
     * @param $distributionKey string Hashing Key
     * @return bool
     * @see LxCacheDriver::update()
     */
    public function update($key, $value, $ttl, $distributionKey = null) {
        if ($distributionKey === null) {
    		return $this->memcachedObject->replace($key, $value, $ttl);
    	} else {
    		return $this->memcachedObject->replaceByKey($distributionKey, $key, $value, $ttl);
    	}
    }
    
    /**
     * Appends a value to a given key
     * 
     * @param $key string Key to append to
     * @param $value string to append to the key value
     * @param $ttl int new TTL unused in memcached
     * @param $distributionKey string Hashing Key
     * @return bool
     * @see LxCacheDriver::append()
     */
    public function append($key, $value, $ttl, $distributionKey = null) {
        if ($distributionKey === null) {
    		return $this->memcachedObject->append($key, $value);
    	} else {
    		return $this->memcachedObject->appendByKey($distributionKey, $key, $value);
    	}
    }
    
    /**
     * Prepends a value to a given key
     * 
     * @param $key string Key to prepend to
     * @param $value string to prepend to the key value
     * @param $ttl int new TTL unused in memcached
     * @param $distributionKey string Hashing Key
     * @return bool
     * @see LxCacheDriver::prepend()
     */
    public function prepend($key, $value, $ttl, $distributionKey = null) {
        if ($distributionKey === null) {
    		return $this->memcachedObject->prepend($key, $value);
    	} else {
    		return $this->memcachedObject->prependByKey($distributionKey, $key, $value);
    	}
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
		return $this->memcachedObject->increment($key, $value);
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
		return $this->memcachedObject->decrement($key, $value);
    }
    
    /**
     * Deletes a key from the cache
     * 
     * @param $key string
     * @param $delay int delete delay
     * @param $distributionKey string Hashing Key
     * @see LxCacheDriver::delete()
     */
    public function delete($key, $delay = null, $distributionKey = null) {
    	if ($distributionKey) {
    		return $this->memcachedObject->delete($key, $delay);
    	} else {
    		return $this->memcachedObject->deleteByKey($distributionKey, $key, $delay);
    	}
    }
    
    /**
     * Deletes a set of keys from the cache
     * 
     * @param $keys array list of keys to delete
     * @param $delay int delete delay
     * @param $distributionKey string Hashing Key
     * @return bool
     * @see LxCacheDriver::deleteMulti()
     */
    public function deleteMulti($keys, $delay = null, $distributionKey = null) {
      	if ($distributionKey) {
    		return $this->memcachedObject->deleteMulti($keys, $delay);
    	} else {
    		return $this->memcachedObject->deleteMultiByKey($distributionKey, $keys, $delay);
    	}
    }
    
    /**
     * Return last operation result Code
     * 
     * @see LxCacheDriver::getResult()
     * @return int
     */
    public function getResult() {
        return $this->memcachedObject->getResultCode()===Memcached::RES_SUCCESS;
    }
    
    /**
     * Flush Memcached pool
     * 
     * @param $delay Delay before flushing
     * @return bool
     * @see LxCacheDriver::flush()
     */
    public function flush($delay = 0) {
        return $this->memcachedObject->flush($delay);
    }
    
    /**
     * Returns Memcached Stats
     * 
     * @return array
     * @see LxCacheDriver::stats()
     */
    public function stats(){
    	return $this->memcachedObject->getStats();
    }
}
