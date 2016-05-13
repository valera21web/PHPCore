<?php

namespace lib;

class CacheManager
{
    private static function add($name, $value, $ttl = 3600)
    {
        \apc_add(md5($name), $value, $ttl);
    }
    private static function edit($name, $value, $ttl = 3600)
    {
        \apc_store(md5($name), $value, $ttl);
    }

    public static function get($name)
    {
        return \apc_fetch(md5($name));
    }

    public static function set($name, $value, $ttl = 3600)
    {
        if(self::exist($name))
            self::edit($name, $value, $ttl);
        else
            self::add($name, $value, $ttl);
    }

    public static function exist($name)
    {
        return \apc_exists(md5($name));
    }

    public static function delete($name)
    {
        \apc_delete(md5($name));
    }
}