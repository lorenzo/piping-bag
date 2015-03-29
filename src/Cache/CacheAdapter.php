<?php

namespace PipingBag\Cache;

use Cake\Cache\Cache;
use Doctrine\Common\Cache\CacheProvider;

/**
 * Exposes methods from Cake's Cache class as a cache engine for
 * Doctrine.
 */
class CacheAdapter extends CacheProvider
{

    /**
     * The Cache config name to use.
     *
     * @var string
     */
    protected $config;

    /**
     * Constructor.
     *
     * @param string $configName The Cache config name to use.
     */
    public function __construct($configName)
    {
        $this->config = $configName;
    }

    /**
     * {@inheritDoc}
     */
    protected function doFetch($id)
    {
        return Cache::read($id, $this->config);
    }

    /**
     * {@inheritDoc}
     */
    protected function doContains($id)
    {
        return Cache::read($id, $this->config) !== false;
    }

    /**
     * {@inheritDoc}
     */
    protected function doSave($id, $data, $lifeTime = 0)
    {
        return Cache::write($id, $data, $this->config);
    }

    /**
     * {@inheritDoc}
     */
    protected function doDelete($id)
    {
        return Cache::delete($id, $this->config);
    }

    /**
     * {@inheritDoc}
     */
    protected function doFlush()
    {
        return Cache::clear(false, $this->config);
    }

    /**
     * {@inheritDoc}
     */
    protected function doGetStats()
    {
        return null;
    }
}
