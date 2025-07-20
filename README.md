# A Monaco Editor form field for Filamentphp.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/abdelhamiderrahmouni/filament-monaco-editor.svg?style=flat-square)](https://packagist.org/packages/abdelhamiderrahmouni/filament-monaco-editor)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/abdelhamiderrahmouni/filament-monaco-editor/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/abdelhamiderrahmouni/filament-monaco-editor/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/abdelhamiderrahmouni/filament-monaco-editor/fix-php-code-styling.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/abdelhamiderrahmouni/filament-monaco-editor/actions?query=workflow%3A"Fix+PHP+code+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/abdelhamiderrahmouni/filament-monaco-editor.svg?style=flat-square)](https://packagist.org/packages/abdelhamiderrahmouni/filament-monaco-editor)



This package provides a Monaco editor field for [FilamentPHP](https://filamentphp.com/).

## Demo

<video width="320" height="240" controls>
  <source src="https://github.com/abdelhamiderrahmouni/filament-monaco-editor/assets/26693672/10d48ffb-278a-42a0-8d0b-a10b04e463c4" type="video/mp4">
</video>


## Installation

You can install the package via composer:

```bash
composer require abdelhamiderrahmouni/filament-monaco-editor
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="filament-monaco-editor-config"
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="filament-monaco-editor-views"
```

This is the contents of the published config file:

```php
return [
"general" => [
        "enable-preview" => true,
        "show-full-screen-toggle" => true,
        "show-placeholder" => true,
        "placeholder-text" => "Your code here...",
        "show-loader" => true,
        "font-size" => "15px",
        "line-numbers-min-chars" => true,
        "automatic-layout" => true,
        "default-theme" => "blackboard"
    ],
    "themes" => [
        "blackboard" => [
            "base" => "vs-dark",
            "inherit" => true,
            "rules" => [
                # long list of rules ... 
            ],
            "colors" => [
                "editor.foreground" => "#F8F8F8",
                "editor.background" => "#0C1021",
                "editor.selectionBackground" => "#253B76",
                "editor.lineHighlightBackground" => "#FFFFFF0F",
                "editorCursor.foreground" => "#FFFFFFA6",
                "editorWhitespace.foreground" => "#FFFFFF40"
            ]
        ]
    ],
];
```

## Usage

You can use this field with minimal configuration like this:
```php
use AbdelhamidErrahmouni\FilamentMonacoEditor\MonacoEditor;

MonacoEditor::make('content')
            ->language('php'),
```

You can change the theme of the editor by using the `theme` method:
```php
->theme('blackboard') # themes should be defined in the config file in the themes array 
```

Add Scripts and Styles to preview's head element
```php
->previewHeadEndContent("<script src='https://cdn.tailwindcss.com'></script><script defer src='https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js'></script>"),
```

Add attributes to the preview's body element
```php
->previewBodyAttributes([
    'class' => 'bg-red-500',
    'id' => 'preview-body'
]),
```

Add content to the start of the preview's body element
```php
->previewBodyStartContent("<div class='bg-red-500'>"),
```

Add content to the end of the preview's body element
```php
->previewBodyEndContent("</div>"),
```

You can Disable preview code functionality by method or in the config
```php 
->enablePreview(false)
# or
->disablePreview()
```

You can show/hide the full screen button by method or in the config
```php
->showFullScreenButton(false)
# or
->hideFullScreenButton()
```

Show/Hide The loader
```php
->showLoader(false)
# or
->hideLoader()
```

Show/Hide the placeholder
```php
->showPlaceholder(false)
# or
->hidePlaceholder()
```

Customize placeholder's text
```php
->placeholderText('Your placeholder text')
```
change the font-size of the editor
```php
->fontSize('14px')
```

## Customization
You can use your own theme by customizing the themes array in `filament-monaco-editor.php` config file:
```php
"themes" => [
    "themeName" => [
        "base" => "vs-dark|vs-light",
        "inherit" => true|false,
        "rules" => [
           //... your rules
        ],
        "colors" => [
            // your colors, the following are an example...
            "editor.foreground" => "#F8F8F8",
            "editor.background" => "#0C1021",
            "editor.selectionBackground" => "#253B76",
            "editor.lineHighlightBackground" => "#FFFFFF0F",
            "editorCursor.foreground" => "#FFFFFFA6",
            "editorWhitespace.foreground" => "#FFFFFF40"
        ]
    ]
],
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits
Thanks to [PinesUI](https://devdojo.com/pines/docs/monaco-editor) for the Monaco editor component 
and the [DevDojo](https://devdojo.com/) team for their dedication and contribution to open source projects.

- [Abdelhamid Errahmouni](https://github.com/abdelhamiderrahmouni)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
