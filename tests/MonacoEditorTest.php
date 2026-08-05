<?php

use AbdelhamidErrahmouni\FilamentMonacoEditor\MonacoEditor;

it('uses the configured field defaults', function () {
    config()->set('filament-monaco-editor.general.height', '60vh');
    config()->set('filament-monaco-editor.general.min-height', '400px');
    config()->set('filament-monaco-editor.general.max-height', '80vh');

    $field = MonacoEditor::make('content');

    expect($field->getHeight())->toBe('60vh')
        ->and($field->getMinHeight())->toBe('400px')
        ->and($field->getMaxHeight())->toBe('80vh');
});

it('supports fluent sizing methods', function () {
    $field = MonacoEditor::make('content')
        ->height('70vh')
        ->minHeight('600px')
        ->maxHeight('900px');

    expect($field->getHeight())->toBe('70vh')
        ->and($field->getMinHeight())->toBe('600px')
        ->and($field->getMaxHeight())->toBe('900px');
});

it('uses html highlighting and blade decorations for blade fields', function () {
    $field = MonacoEditor::make('template')->language('blade.php');

    expect($field->getLanguage())->toBe('blade')
        ->and($field->getMonacoLanguage())->toBe('html')
        ->and($field->getIsBladeLanguage())->toBeTrue();
});

it('escapes preview body attributes', function () {
    $field = MonacoEditor::make('content')->previewBodyAttributes([
        'class' => 'preview" onload="alert(1)',
    ]);

    expect($field->getPreviewBodyAttributes())
        ->toBe('class="preview&quot; onload=&quot;alert(1)"');
});
