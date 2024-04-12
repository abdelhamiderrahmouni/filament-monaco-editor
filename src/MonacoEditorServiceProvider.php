<?php

namespace AbdelhamidErrahmouni\FilamentMonacoEditor;

use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Assets\Asset;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Filesystem\Filesystem;
use Livewire\Features\SupportTesting\Testable;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use AbdelhamidErrahmouni\FilamentMonacoEditor\Commands\MonacoEditorCommand;
use AbdelhamidErrahmouni\FilamentMonacoEditor\Testing\TestsMonacoEditor;

class MonacoEditorServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-monaco-editor';

    public static string $viewNamespace = 'filament-monaco-editor';

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package->name(static::$name)
            ->hasConfigFile('filament-monaco-editor')
            ->hasTranslations()
            ->hasViews(static::$viewNamespace);
    }

    public function packageRegistered(): void
    {
    }

    public function packageBooted(): void
    {
        // Asset Registration
        FilamentAsset::register(
            $this->getAssets(),
            static::$name
        );

//        FilamentAsset::registerScriptData(
//            $this->getScriptData(),
//            static::$name
//        );

        // Testing
        Testable::mixin(new TestsMonacoEditor());
    }

    /**
     * @return array<Asset>
     */
    protected function getAssets(): array
    {
        return [
            // AlpineComponent::make('filament-monaco-editor', __DIR__ . '/../resources/dist/components/filament-monaco-editor.js'),
            Css::make('filament-monaco-editor-styles', __DIR__ . '/../resources/dist/filament-monaco-editor.css'),
            Js::make('filament-monaco-editor-scripts', __DIR__ . '/../resources/dist/filament-monaco-editor.js'),
        ];
    }

    /**
     * @return array<string>
     */
    protected function getIcons(): array
    {
        return [];
    }

    /**
     * @return array<string, mixed>
     */
    protected function getScriptData(): array
    {
        return [];
    }
}
