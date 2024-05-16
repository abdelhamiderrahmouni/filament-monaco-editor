<?php

namespace AbdelhamidErrahmouni\FilamentMonacoEditor;

use Closure;
use Filament\Forms\Components\Field;

class MonacoEditor extends Field
{
    public bool | Closure $showPlaceholder = true;

    public bool | Closure $showLoader = true;

    public bool | Closure $automaticLayout = true;

    public int | Closure $lineNumbersMinChars = 3;

    public string | Closure $fontSize = '15px';

    public string | Closure $language = 'html';

    public string | Closure $placeholderText = 'Your code here...';

    public string | Closure $previewHeadEndContent = '';

    public array | Closure $previewBodyAttributes = ['class' => ''];

    public string | Closure $previewBodyStartContent = '';

    public string | Closure $previewBodyEndContent = '';

    public bool | Closure $enablePreview = true;

    public bool | Closure $showFullScreenToggle = true;

    public string | Closure $theme = 'blackboard';

    protected string $view = 'filament-monaco-editor::filament-monaco-editor';

    protected function setUp(): void
    {
        $this->showPlaceholder = config('filament-monaco-editor.general.show-placeholder');
        $this->placeholderText = config('filament-monaco-editor.general.placeholder-text');
        $this->showLoader = config('filament-monaco-editor.general.show-loader');
        $this->fontSize = config('filament-monaco-editor.general.font-size');
        $this->lineNumbersMinChars = config('filament-monaco-editor.general.line-numbers-min-chars');
        $this->automaticLayout = config('filament-monaco-editor.general.automatic-layout');
        $this->theme = config('filament-monaco-editor.general.default-theme');
        $this->enablePreview = config('filament-monaco-editor.general.enable-preview');
        $this->showFullScreenToggle = config('filament-monaco-editor.general.show-full-screen-toggle');
    }

    /*
     *  Default theme for the editor, change theme from config.
     */
    public function editorTheme()
    {
        if (! isset(config('filament-monaco-editor.themes')[$this->theme])) {
            throw new \Exception("Theme {$this->theme} not found in config file.");
        }

        return json_encode([
            'base' => config("filament-monaco-editor.themes.{$this->theme}.base"),
            'inherit' => config("filament-monaco-editor.themes.{$this->theme}.inherit"),
            'rules' => config("filament-monaco-editor.themes.{$this->theme}.rules"),
            'colors' => config("filament-monaco-editor.themes.{$this->theme}.colors"),
        ], JSON_THROW_ON_ERROR);
    }

    /**
     * @return $this
     *
     * Set the language for the editor: html|javascript|css|php|vue|...
     */
    public function language(string | Closure $lang = 'html'): static
    {
        $this->language = $lang;

        return $this;
    }

    /**
     * @param  bool|Closure  $show
     * @return $this
     *
     * Show/Hide placeholder text when editor is empty.
     */
    public function showPlaceholder(bool | Closure $condition = true): static
    {
        $this->showPlaceholder = $condition;

        return $this;
    }

    /**
     * @return $this
     *
     * Hide the placeholder text when editor is empty.
     */
    public function hidePlaceholder(): static
    {
        $this->showPlaceholder = false;

        return $this;
    }

    /**
     * @return $this
     *
     * Set the placeholder text for the editor.
     */
    public function placeholderText(string | Closure $palceholder = ''): static
    {
        $this->placeholderText = $palceholder;

        return $this;
    }

    /**
     * @param  bool|Closure  $show
     * @return $this
     *
     * Show/Hide loader when editor is loading.
     */
    public function showLoader(bool | Closure $condition = true): static
    {
        $this->showLoader = $condition;

        return $this;
    }

    /**
     * @return $this
     *
     * Hide the loader when editor is loading.
     */
    public function hideLoader(): static
    {
        $this->showLoader = false;

        return $this;
    }

    /**
     * @return $this
     *
     * Change the font size of the editor's content.
     */
    public function fontSize(string | Closure $size = '15px'): static
    {
        $this->fontSize = $size;

        return $this;
    }

    /**
     * @return $this
     *
     * Change the line numbers min characters
     */
    public function lineNumbersMinChars(int | Closure $value = 3): static
    {
        $this->lineNumbersMinChars = $value;

        return $this;
    }

    /**
     * @param  bool|Closure  $value
     * @return $this
     *
     * Enable/Disable automatic layout.
     */
    public function automaticLayout(bool | Closure $condition = true): static
    {
        $this->automaticLayout = $condition;

        return $this;
    }

    public function previewHeadEndContent(string | Closure $content = ''): static
    {
        $this->previewHeadEndContent = $content;

        return $this;
    }

    public function previewBodyAttributes(array | Closure $attributes = ['class' => '']): static
    {
        $this->previewBodyAttributes = $attributes;

        return $this;
    }

    public function previewBodyStartContent(string | Closure $content = ''): static
    {
        $this->previewBodyStartContent = $content;

        return $this;
    }

    public function previewBodyEndContent(string | Closure $content = ''): static
    {
        $this->previewBodyEndContent = $content;

        return $this;
    }

    public function enablePreview(bool | Closure $condition = true): static
    {
        $this->enablePreview = $condition;

        return $this;
    }

    public function disablePreview(): static
    {
        $this->enablePreview = false;

        return $this;
    }

    public function showFullScreenToggle(bool | Closure $condition = true): static
    {
        $this->showFullScreenToggle = $condition;

        return $this;
    }

    public function hideFullScreenButton()
    {
        $this->showFullScreenToggle = false;

        return $this;
    }

    public function theme(string | Closure $name = 'blackboard'): static
    {
        $this->theme = $name;

        return $this;
    }

    // -----------------------

    public function getLanguage()
    {
        return $this->evaluate($this->language);
    }

    public function getShowPlaceholder()
    {
        return (bool) $this->evaluate($this->showPlaceholder);
    }

    public function getPlaceholderText()
    {
        return $this->evaluate($this->placeholderText);
    }

    public function getShowLoader()
    {
        return (bool) $this->evaluate($this->showLoader);
    }

    public function getFontSize()
    {
        return $this->evaluate($this->fontSize);
    }

    public function getLineNumbersMinChars()
    {
        return (int) $this->evaluate($this->lineNumbersMinChars);
    }

    public function getAutomaticLayout()
    {
        return (bool) $this->evaluate($this->automaticLayout);
    }

    public function getPreviewHeadEndContent()
    {
        return $this->evaluate($this->previewHeadEndContent);
    }

    public function getPreviewBodyAttributes()
    {
        $attributes = $this->evaluate($this->previewBodyAttributes);

        return implode(' ', array_map(fn ($key, $value) => "$key=&quot;$value&quot;", array_keys($attributes), $attributes));
    }

    public function getPreviewBodyStartContent()
    {
        return $this->evaluate($this->previewBodyStartContent);
    }

    public function getPreviewBodyEndContent()
    {
        return $this->evaluate($this->previewBodyEndContent);
    }

    public function getEnablePreview()
    {
        return (bool) $this->evaluate($this->enablePreview);
    }

    public function getShowFullScreenToggle()
    {
        return (bool) $this->evaluate($this->showFullScreenToggle);
    }
}
