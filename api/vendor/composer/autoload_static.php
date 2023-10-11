<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit9d44a6c39d7e16e2b929d01722d625ce
{
    public static $prefixLengthsPsr4 = array (
        'F' => 
        array (
            'Firebase\\JWT\\' => 13,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Firebase\\JWT\\' => 
        array (
            0 => __DIR__ . '/..' . '/firebase/php-jwt/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit9d44a6c39d7e16e2b929d01722d625ce::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit9d44a6c39d7e16e2b929d01722d625ce::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit9d44a6c39d7e16e2b929d01722d625ce::$classMap;

        }, null, ClassLoader::class);
    }
}