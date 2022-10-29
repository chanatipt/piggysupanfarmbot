<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitea0390d6cea695588c7a75c7618ffacb
{
    public static $prefixLengthsPsr4 = array (
        'L' => 
        array (
            'LINE\\' => 5,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'LINE\\' => 
        array (
            0 => __DIR__ . '/..' . '/linecorp/line-bot-sdk/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitea0390d6cea695588c7a75c7618ffacb::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitea0390d6cea695588c7a75c7618ffacb::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitea0390d6cea695588c7a75c7618ffacb::$classMap;

        }, null, ClassLoader::class);
    }
}
