<?php
namespace App\Support;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

class Tenant implements \App\Contracts\Tenant {

    protected string $tenantDbConfig = 'facility';
    protected string $tenant = '';

    public function use($tenant, $callback = null)
    {
        if ($this->tenant == $tenant) {
            return;
        }

        $this->tenant = $tenant;

        $configName = 'database.connections.' . $this->tenantDbConfig;

        $config = app('config')->get($configName);

        $config['database'] = $tenant;

        app('config')->set($configName, $config);

        app('db')->reconnect($this->tenantDbConfig);

        $this->resetCachePrefix();

        if ($callback instanceof \Closure) {
            $callback($this);
        }
    }

    protected function resetCachePrefix()
    {
        $cachePrefix = env('CACHE_PREFIX', 'app');

        $prefix = "{$cachePrefix}:{$this->tenant}";

        Config::set('cache.prefix', $prefix);

        $store = Cache::getStore();

        if (method_exists($store, 'setPrefix')) {

            $store->setPrefix($prefix);
        }
    }

}
