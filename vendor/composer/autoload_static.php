<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit42eeaa151b3f030d8dbdd33bbf84a2c3
{
    public static $prefixLengthsPsr4 = array (
        'A' => 
        array (
            'App\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'App\\' => 
        array (
            0 => __DIR__ . '/../..' . '/app',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit42eeaa151b3f030d8dbdd33bbf84a2c3::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit42eeaa151b3f030d8dbdd33bbf84a2c3::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit42eeaa151b3f030d8dbdd33bbf84a2c3::$classMap;

        }, null, ClassLoader::class);
    }
}
