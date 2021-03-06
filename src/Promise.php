<?php
namespace mole\yii;

/**
 * Register promise for asynchronous processing.
 *
 * @author Jin Chen <jmole.chen@gmail.com>
 * @since 1.0
 */
class Promise
{
    /**
     * @var boolean
     */
    private static $_isRegistered = false;
    /**
     * @var \GuzzleHttp\Promise\Promise[]
     */
    private static $_promises = [];

    /**
     * Register function for exit.
     *
     * @return void
     */
    public static function register()
    {
        static::$_isRegistered = true;
        register_shutdown_function(function () {
            if (static::$_promises) {
                if (function_exists('\fastcgi_finish_request')) {
                    \fastcgi_finish_request();
                }
                \GuzzleHttp\Promise\inspect_all(static::$_promises);
            }
        });
    }

    /**
     * Add promise to stacks.
     *
     * @param \GuzzleHttp\Promise\Promise $promise
     * @return void
     */
    public static function add(\GuzzleHttp\Promise\Promise $promise)
    {
        if (!static::$_isRegistered) {
            static::register();
        }

        static::$_promises[] = $promise;
    }
}
