<?php

namespace Dennenboom\VerdantUI;

class VerdantUI
{
    public static function assets()
    {
        $verdantCssPath = self::assetPath('css/verdant-ui.css');
        $verdantJsPath = self::assetPath('js/verdant-ui.js');
        $fontAwesomePath = self::assetPath('vendor/fontawesome/css/all.min.css');
        $alpineJsPath = self::assetPath('vendor/alpine/alpine.min.js');
        $cropperJsPath = self::assetPath('js/cropper.min.js');
        $cropperCssPath = self::assetPath('css/cropper.min.css');

        $includeAlpine = config('verdant.assets.include_alpine', true);
        $includeFontawesome = config('verdant.assets.include_fontawesome', true);

        $alpine = $includeAlpine ? "<script src=\"{$alpineJsPath}\" defer></script>" : "";
        $fontawesome = $includeFontawesome ? "<link rel=\"stylesheet\" href=\"{$fontAwesomePath}\">" : "";

        return <<<HTML
        {$fontawesome}
        <link rel="stylesheet" href="{$verdantCssPath}">
        <link rel="stylesheet" href="{$cropperCssPath}">
        {$alpine}
        <script src="{$cropperJsPath}" defer></script>
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
