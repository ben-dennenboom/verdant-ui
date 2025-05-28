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
        $customColors = self::getCustomColorsCSS();

        return <<<HTML
        {$fontawesome}
        <link rel="stylesheet" href="{$verdantCssPath}">
        <link rel="stylesheet" href="{$cropperCssPath}">
        <style>{$customColors}</style>
        {$alpine}
        <script src="{$cropperJsPath}" defer></script>
        <script src="{$verdantJsPath}" defer></script>
        <script>
            window.verdantPrefix = "v-"
        </script>
        HTML;
    }

    private static function getCustomColorsCSS(): string
    {
        $primaryColors = config('verdant.theme.colors.primary', []);
        $secondaryColors = config('verdant.theme.colors.secondary', []);

        if (empty($primaryColors) && empty($secondaryColors)) {
            return '';
        }

        $css = ':root {';

        if (!empty($primaryColors)) {
            foreach ($primaryColors as $shade => $color) {
                $rgb = self::hexToRgb($color);
                $varName = $shade === 'default' ? '--color-primary' : "--color-primary-{$shade}";
                $css .= "{$varName}: {$rgb};";
            }
        }

        if (!empty($secondaryColors)) {
            foreach ($secondaryColors as $shade => $color) {
                $rgb = self::hexToRgb($color);
                $varName = $shade === 'default' ? '--color-secondary' : "--color-secondary-{$shade}";
                $css .= "{$varName}: {$rgb};";
            }
        }

        $css .= '}';

        return $css;
    }

    private static function hexToRgb(string $hex): string
    {
        $hex = ltrim($hex, '#');

        if (strlen($hex) === 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }

        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        return "{$r} {$g} {$b}";
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
