<?php

namespace Dennenboom\VerdantUI;

class VerdantUI
{
    public static function assets()
    {
        $verdantCssPath = self::assetPath('css/verdant-ui.css');
        $verdantJsPath = self::assetPath('js/verdant-ui.js');
        $fontAwesomePath = self::assetPath('vendor/fontawesome/css/all.min.css');

        return <<<HTML
        <link rel="stylesheet" href="{$fontAwesomePath}">
        <link rel="stylesheet" href="{$verdantCssPath}">
        <script src="{$verdantJsPath}" defer></script>
        <script>
            window.verdantPrefix = "v-"
        </script>
        HTML;
    }

    private static function assetPath($path)
    {
        $possiblePaths = [
            "vendor/verdant/{$path}",
            "vendor/dennenboom/verdant-ui/public/build/{$path}"
        ];

        foreach ($possiblePaths as $possiblePath) {
            if (file_exists(public_path($possiblePath))) {
                return asset($possiblePath);
            }
        }

        return asset("vendor/verdant/{$path}");
    }

    public static function prefix()
    {
        return config('verdant.prefix.css', 'v-');
    }

    public static function class($classes)
    {
        $prefix = self::prefix();

        if (is_array($classes)) {
            $classes = implode(' ', $classes);
        }

        return collect(explode(' ', $classes))
            ->map(function($class) use ($prefix) {
                if (empty($class)) {
                    return '';
                }

                return str_starts_with($class, $prefix) ? $class : $prefix . $class;
            })
            ->filter()
            ->implode(' ');
    }
}
