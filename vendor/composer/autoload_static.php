<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit9575811885d065e9793b7a08607da08c
{
    public static $files = array (
        '5f2aad0f1beee097fba38a252c1ebd00' => __DIR__ . '/..' . '/a7/autoload/package.php',
    );

    public static $prefixLengthsPsr4 = array (
        'O' => 
        array (
            'ODS\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'ODS\\' => 
        array (
            0 => __DIR__ . '/..' . '/oberonlai/wp-asset/src',
            1 => __DIR__ . '/..' . '/oberonlai/wp-option/src',
            2 => __DIR__ . '/..' . '/oberonlai/wp-ajax/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit9575811885d065e9793b7a08607da08c::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit9575811885d065e9793b7a08607da08c::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit9575811885d065e9793b7a08607da08c::$classMap;

        }, null, ClassLoader::class);
    }
}
