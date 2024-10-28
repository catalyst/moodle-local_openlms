<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInitdbec4d4bc8617e7a2e6437a23894a057
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        require __DIR__ . '/platform_check.php';

        spl_autoload_register(array('ComposerAutoloaderInitdbec4d4bc8617e7a2e6437a23894a057', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInitdbec4d4bc8617e7a2e6437a23894a057', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInitdbec4d4bc8617e7a2e6437a23894a057::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}
