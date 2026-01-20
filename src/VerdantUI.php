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

    private static function assetPath($path)
    {
        $possiblePaths = [
            "vendor/verdant/{$path}",
            "vendor/dennenboom/verdant-ui/public/build/{$path}",
        ];

        foreach ($possiblePaths as $possiblePath) {
            if (file_exists(public_path($possiblePath))) {
                return asset($possiblePath);
            }
        }

        return asset("vendor/verdant/{$path}");
    }

    private static function getCustomColorsCSS(): string
    {
        $colors = config('verdant.theme.colors', []);
        $darkColors = config('verdant.theme.dark_colors', []);

        if (empty($colors) && empty($darkColors)) {
            return '';
        }

        $css = '';

        if (!empty($colors)) {
            $css .= ':root {' . self::buildColorVariables($colors) . '}';
        }

        if (!empty($darkColors)) {
            $css .= '[data-theme="dark"] {' . self::buildColorVariables($darkColors) . '}';
        }

        return $css;
    }

    private static function buildColorVariables(array $colors): string
    {
        $css = '';

        foreach ($colors as $colorName => $shades) {
            $varPrefix = str_starts_with($colorName, 'v-')
                ? "--{$colorName}"
                : "--color-{$colorName}";

            if (is_string($shades)) {
                $rgb = self::hexToRgb($shades);
                $css .= "{$varPrefix}: {$rgb};";

                if (!str_starts_with($colorName, 'v-')) {
                    $generatedShades = self::generateColorShades($shades);
                    foreach ($generatedShades as $shade => $rgb) {
                        $css .= "{$varPrefix}-{$shade}: {$rgb};";
                    }
                }

                continue;
            }

            if (!is_array($shades)) {
                continue;
            }

            $hasAllShades = count(
                    array_intersect(
                        array_keys($shades),
                        ['50', '100', '200', '300', '400', '500', '600', '700', '800', '900']
                    )
                ) >= 5;

            foreach ($shades as $shade => $color) {
                $rgb = self::hexToRgb($color);

                if ($shade === 'default') {
                    $css .= "{$varPrefix}: {$rgb};";
                } else {
                    $css .= "{$varPrefix}-{$shade}: {$rgb};";
                }
            }

            if (!$hasAllShades && !str_starts_with($colorName, 'v-')) {
                $baseColor = $shades['default'] ?? $shades['500'] ?? null;

                if ($baseColor) {
                    $generatedShades = self::generateColorShades($baseColor);

                    foreach ($generatedShades as $shade => $rgb) {
                        if (!isset($shades[$shade]) && $shade !== 'default') {
                            $css .= "{$varPrefix}-{$shade}: {$rgb};";
                        }
                    }
                }
            }
        }

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

    private static function generateColorShades(string $hex): array
    {
        $hex = ltrim($hex, '#');

        if (strlen($hex) === 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }

        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        return [
            '50' => self::lighten($r, $g, $b, 0.95),
            '100' => self::lighten($r, $g, $b, 0.9),
            '200' => self::lighten($r, $g, $b, 0.75),
            '300' => self::lighten($r, $g, $b, 0.6),
            '400' => self::lighten($r, $g, $b, 0.3),
            '500' => "{$r} {$g} {$b}",
            '600' => self::darken($r, $g, $b, 0.1),
            '700' => self::darken($r, $g, $b, 0.2),
            '800' => self::darken($r, $g, $b, 0.4),
            '900' => self::darken($r, $g, $b, 0.6),
        ];
    }

    private static function lighten(int $r, int $g, int $b, float $amount): string
    {
        $newR = (int)round($r + (255 - $r) * $amount);
        $newG = (int)round($g + (255 - $g) * $amount);
        $newB = (int)round($b + (255 - $b) * $amount);

        return "{$newR} {$newG} {$newB}";
    }

    private static function darken(int $r, int $g, int $b, float $amount): string
    {
        $newR = (int)round($r * (1 - $amount));
        $newG = (int)round($g * (1 - $amount));
        $newB = (int)round($b * (1 - $amount));

        return "{$newR} {$newG} {$newB}";
    }

    public static function class($classes)
    {
        $prefix = self::prefix();

        if (is_array($classes)) {
            $classes = implode(' ', $classes);
        }

        return collect(explode(' ', $classes))
            ->map(function ($class) use ($prefix) {
                if (empty($class)) {
                    return '';
                }

                return str_starts_with($class, $prefix) ? $class : $prefix . $class;
            })
            ->filter()
            ->implode(' ');
    }

    public static function prefix()
    {
        return config('verdant.prefix.css', 'v-');
    }
}
