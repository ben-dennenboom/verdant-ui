<?php

namespace Dennenboom\VerdantUI;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\View\Compilers\BladeCompiler;

class VerdantUIServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/verdant.php',
            'verdant'
        );
    }

    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'verdant');

        $this->registerComponents();
        $this->registerBladeDirectives();

        $this->publishAssets();
    }

    protected function registerComponents()
    {
        $this->callAfterResolving(BladeCompiler::class, function () {
            $componentsPath = __DIR__ . '/../resources/views/components';

            $componentFiles = $this->findComponentFiles($componentsPath);

            foreach ($componentFiles as $componentFile) {
                $relPath = str_replace($componentsPath . '/', '', $componentFile);

                $name = str_replace(['/', '.blade.php'], ['.', ''], $relPath);

                Blade::component("verdant::components.{$name}", "v-{$name}");
            }
        });
    }

    protected function registerBladeDirectives()
    {
        Blade::directive('verdantAssets', function () {
            return "<?php echo \Dennenboom\VerdantUI\VerdantUI::assets(); ?>";
        });

        Blade::directive('vclass', function ($expression) {
            return "<?php echo \Dennenboom\VerdantUI\VerdantUI::class({$expression}); ?>";
        });
    }

    protected function findComponentFiles($directory)
    {
        $files = [];

        foreach (File::allFiles($directory) as $file) {
            if ($file->getExtension() === 'php' && Str::endsWith($file->getFilename(), '.blade.php')) {
                $files[] = $file->getPathname();
            }
        }

        return $files;
    }

    protected function publishAssets()
    {
        $this->publishes(
            [
                __DIR__ . '/../resources/views' => resource_path('views/vendor/verdant'),
            ],
            'verdant-views'
        );

        $this->publishes(
            [
                __DIR__ . '/../public/build/css' => public_path('vendor/verdant/css'),
                __DIR__ . '/../public/build/js' => public_path('vendor/verdant/js'),
                __DIR__ . '/../public/build/vendor' => public_path('vendor/verdant/vendor'),
            ],
            'verdant-assets'
        );

        $this->publishes(
            [
                __DIR__ . '/../config/verdant.php' => config_path('verdant.php'),
            ],
            'verdant-config'
        );

        $this->publishes(
            [
                __DIR__ . '/../package.json'       => base_path('package.verdant.json'),
                __DIR__ . '/../vite.config.js'     => base_path('vite.verdant.config.js'),
                __DIR__ . '/../tailwind.config.js' => base_path('tailwind.verdant.config.js'),
                __DIR__ . '/../postcss.config.js'  => base_path('postcss.verdant.config.js'),
                __DIR__ . '/../resources/js'       => resource_path('js/vendor/verdant'),
                __DIR__ . '/../resources/css'      => resource_path('css/vendor/verdant'),
            ],
            'verdant-source'
        );
    }
}
