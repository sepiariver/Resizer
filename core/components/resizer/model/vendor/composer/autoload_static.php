<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit59234362500a0da6131a6333fd4810eb
{
    public static $prefixLengthsPsr4 = array (
        'I' => 
        array (
            'Imagine\\' => 8,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Imagine\\' => 
        array (
            0 => __DIR__ . '/..' . '/imagine/imagine/src',
        ),
    );

    public static $prefixesPsr0 = array (
        'R' => 
        array (
            'Reductionist' => 
            array (
                0 => __DIR__ . '/..' . '/sepiariver/reductionist/src',
            ),
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit59234362500a0da6131a6333fd4810eb::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit59234362500a0da6131a6333fd4810eb::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInit59234362500a0da6131a6333fd4810eb::$prefixesPsr0;

        }, null, ClassLoader::class);
    }
}
