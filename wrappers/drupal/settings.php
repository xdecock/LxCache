/* Number of levels in the cache */
$settings['lxcache']['levels']                      = '2';
/* Per level settings [L1] */
$settings['lxcache']['L1']['driver']                = 'apc';
$settings['lxcache']['L1']['mode']                  = 'coherent_passthru';
$settings['lxcache']['L1']['includeMode']           = 'whitelist';
$settings['lxcache']['L1']['defaultTtl']            = 1200;
$settings['lxcache']['L1']['coherentPointerSuffix'] = ':pointer';
$settings['lxcache']['L1']['keyPrefix']             = '';
/* Per level settings [L2] */
$settings['lxcache']['L2']['driver']                = 'memcached';
$settings['lxcache']['L2']['mode']					= 'default';
$settings['lxcache']['L2']['includeMode']           = 'all';
$settings['lxcache']['L2']['servers']               = array ();
$settings['lxcache']['L2']['keyPrefix']             = '';
$settings['lxcache']['L1']['defaultTtl']            = 86400;

// Whitelist
$settings['lxcache']['L1']['whiteList']['system.themeRegistry'] = true;
$settings['lxcache']['L1']['whiteList']['L1Cache.*'] = true;

