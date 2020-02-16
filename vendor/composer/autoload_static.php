<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit6695c7f93e6b7b674fbfb6442b91d6e8
{
    public static $prefixLengthsPsr4 = array (
        'G' => 
        array (
            'GApp\\' => 5,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'GApp\\' => 
        array (
            0 => __DIR__ . '/../..' . '/app',
        ),
    );

    public static $classMap = array (
        'GApp\\Controllers\\DocController' => __DIR__ . '/../..' . '/app/Controllers/DocController.php',
        'GApp\\Controllers\\HomeController' => __DIR__ . '/../..' . '/app/Controllers/HomeController.php',
        'GApp\\Controllers\\IndexController' => __DIR__ . '/../..' . '/app/Controllers/IndexController.php',
        'GApp\\Controllers\\InstallController' => __DIR__ . '/../..' . '/app/Controllers/InstallController.php',
        'GApp\\Controllers\\LangController' => __DIR__ . '/../..' . '/app/Controllers/LangController.php',
        'GApp\\Controllers\\LoadLangController' => __DIR__ . '/../..' . '/app/Controllers/LoadlangController.php',
        'GApp\\Controllers\\LoginController' => __DIR__ . '/../..' . '/app/Controllers/LoginController.php',
        'GApp\\Controllers\\PdfController' => __DIR__ . '/../..' . '/app/Controllers/PdfController.php',
        'GApp\\Controllers\\TaskrunnerController' => __DIR__ . '/../..' . '/app/Controllers/TaskrunnerController.php',
        'GApp\\Controllers\\TemplateController' => __DIR__ . '/../..' . '/app/Controllers/TemplateController.php',
        'GApp\\Controllers\\UprvController' => __DIR__ . '/../..' . '/app/Controllers/UprvController.php',
        'GApp\\Controllers\\UserProfileController' => __DIR__ . '/../..' . '/app/Controllers/UserprofileController.php',
        'GApp\\Controllers\\UsersController' => __DIR__ . '/../..' . '/app/Controllers/UsersController.php',
        'GApp\\Lib\\App' => __DIR__ . '/../..' . '/app/Lib/App.php',
        'GApp\\Lib\\Config' => __DIR__ . '/../..' . '/app/Lib/Config.php',
        'GApp\\Lib\\Helper\\FnTrait' => __DIR__ . '/../..' . '/app/Lib/Helper/FnTrait.php',
        'GApp\\Lib\\Helper\\IndexTrait' => __DIR__ . '/../..' . '/app/Lib/Helper/IndexTrait.php',
        'GApp\\Lib\\Helper\\ResponseTrait' => __DIR__ . '/../..' . '/app/Lib/Helper/ResponseTrait.php',
        'GApp\\Lib\\MyPDO' => __DIR__ . '/../..' . '/app/Lib/MyPDO.php',
        'GApp\\Lib\\Start' => __DIR__ . '/../..' . '/app/Lib/Start.php',
        'GApp\\Models\\DocModel' => __DIR__ . '/../..' . '/app/Models/DocModel.php',
        'GApp\\Models\\HomeModel' => __DIR__ . '/../..' . '/app/Models/HomeModel.php',
        'GApp\\Models\\InstallModel' => __DIR__ . '/../..' . '/app/Models/InstallModel.php',
        'GApp\\Models\\LangModel' => __DIR__ . '/../..' . '/app/Models/LangModel.php',
        'GApp\\Models\\LoadLangModel' => __DIR__ . '/../..' . '/app/Models/LoadlangModel.php',
        'GApp\\Models\\LoginModel' => __DIR__ . '/../..' . '/app/Models/LoginModel.php',
        'GApp\\Models\\PdfModel' => __DIR__ . '/../..' . '/app/Models/PdfModel.php',
        'GApp\\Models\\TaskrunnerModel' => __DIR__ . '/../..' . '/app/Models/TaskrunnerModel.php',
        'GApp\\Models\\UprvModel' => __DIR__ . '/../..' . '/app/Models/UprvModel.php',
        'GApp\\Models\\UserProfileModel' => __DIR__ . '/../..' . '/app/Models/UserprofileModel.php',
        'GApp\\Models\\UsersModel' => __DIR__ . '/../..' . '/app/Models/UsersModel.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit6695c7f93e6b7b674fbfb6442b91d6e8::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit6695c7f93e6b7b674fbfb6442b91d6e8::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit6695c7f93e6b7b674fbfb6442b91d6e8::$classMap;

        }, null, ClassLoader::class);
    }
}
