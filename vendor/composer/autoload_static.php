<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit0074034495626c8cdbdb5ef9212bda94
{
    public static $prefixLengthsPsr4 = array (
        'W' => 
        array (
            'WPOrgSubmissionRules\\' => 21,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'WPOrgSubmissionRules\\' => 
        array (
            0 => __DIR__ . '/../..' . '/WPOrgSubmissionRules',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit0074034495626c8cdbdb5ef9212bda94::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit0074034495626c8cdbdb5ef9212bda94::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit0074034495626c8cdbdb5ef9212bda94::$classMap;

        }, null, ClassLoader::class);
    }
}
