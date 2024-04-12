# A monaco editor form field.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/abdelhamiderrahmouni/filament-monaco-editor.svg?style=flat-square)](https://packagist.org/packages/abdelhamiderrahmouni/filament-monaco-editor)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/abdelhamiderrahmouni/filament-monaco-editor/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/abdelhamiderrahmouni/filament-monaco-editor/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/abdelhamiderrahmouni/filament-monaco-editor/fix-php-code-styling.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/abdelhamiderrahmouni/filament-monaco-editor/actions?query=workflow%3A"Fix+PHP+code+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/abdelhamiderrahmouni/filament-monaco-editor.svg?style=flat-square)](https://packagist.org/packages/abdelhamiderrahmouni/filament-monaco-editor)



This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require abdelhamiderrahmouni/filament-monaco-editor
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="filament-monaco-editor-migrations"
php artisan migrate
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
];
```

## Usage

```php
$monacoEditor = new AbdelhamidErrahmouni\FilamentMonacoEditor();
echo $monacoEditor->echoPhrase('Hello, FilamentMonacoEditor!');
```

## Customization
You can use your own theme by customizing the themes key in `filament-monaco-editor.php` config file:
```php
    return [
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
];
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

- [Abdelhamid Errahmouni](https://github.com/abdelhamiderrahmouni)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
