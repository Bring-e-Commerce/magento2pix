<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitbe00677eb46e5e31c8380547135d6c3f
{
    public static $files = array (
        '6a33331093ac43b1f4b7dac65aed334e' => __DIR__ . '/../..' . '/registration.php',
    );

    public static $prefixLengthsPsr4 = array (
        'B' => 
        array (
            'Bring\\Pix\\' => 10,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Bring\\Pix\\' => 
        array (
            0 => __DIR__ . '/../..' . '/',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitbe00677eb46e5e31c8380547135d6c3f::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitbe00677eb46e5e31c8380547135d6c3f::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
