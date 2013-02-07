<?php
Class LxCache {
	const INCLUDE_MODE_ALL = 1;
	const INCLUDE_MODE_BLACKLIST = 2;
	const INCLUDE_MODE_WHITELIST = 3;
	const INCLUDE_MODE_PREG_WHITELIST = 4;
	const INCLUDE_MODE_PREG_BLACKLIST = 5;
	
	const DRIVER_MODE_DEFAULT = 0;
	const DRIVER_MODE_PASSTHROUGH = 1;
	const DRIVER_MODE_COHERENT_PASSTHROUGH = 2;
	
	private $level;
	
	public function __construct() {
		$this->level = 0;
	}
	
	public function addLayer(LxCacheDriver $driver, $mode, $includeMode, $defaultTTL, $options) {
		$level = $this->level++;
		$this->drivers[$level] = $driver;
		$this->mode[$level] = $mode;
		$this->incMode[$level] = $includeMode;
		$this->ttl[$level] = $defaultTTL;
		$this->options[$level] = $options;
	}
	
	public function get($key) {
		for ($i=0; $i<$this->level; ++$i) {
			switch ($this->incMode[$i]) {
				
			}
		}
	}
}