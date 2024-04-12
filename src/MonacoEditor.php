<?php

namespace AbdelhamidErrahmouni\FilamentMonacoEditor;

use Filament\Forms\Components\Field;
use Closure;

class MonacoEditor extends Field
{
    public bool     | Closure     $showPlaceholder          = true;
    public bool     | Closure     $showLoader               = true;
    public bool     | Closure     $automaticLayout          = true;
    public int      | Closure     $lineNumbersMinChars      = 3;
    public string   | Closure     $fontSize                 = '15px';
    public string   | Closure     $language                 = 'html';
    public string   | Closure     $placeholderText          = 'Start typing here';
    public string   | Closure     $previewHeadEndContent    = "";
    public string   | Closure     $previewBodyStartContent  = "";
    public string   | Closure     $previewBodyEndContent    = "";

    protected string $view = 'filament-monaco-editor::+___monaco-editor';

    /*
     *  Default theme for the editor, change theme from config.
     */
    public function editorTheme()
    {
        return json_encode([
            "base" => config('filament-monaco-editor.themes.blackboard.base'),
            "inherit" => config('filament-monaco-editor.themes.blackboard.inherit'),
            "rules" => config('filament-monaco-editor.themes.blackboard.rules'),
            "colors" => config('filament-monaco-editor.themes.blackboard.colors'),
        ], JSON_THROW_ON_ERROR);
    }

    /**
     * @param string|Closure $lang
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
     * @param bool|Closure $show
     * @return $this
     *
     * Show/Hide placeholder text when editor is empty.
     */
    public function showPlaceholder(bool | Closure $show = true): static
    {
        $this->showPlaceholder = $show;

        return $this;
    }

    /**
     * @param string|Closure $palceholder
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
     * @param bool|Closure $show
     * @return $this
     *
     * Show/Hide loader when editor is loading.
     */
    public function showLoader(bool | Closure $show = true): static
    {
        $this->showLoader = $show;

        return $this;
    }

    /**
     * @param string|Closure $size
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
     * @param int|Closure $value
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
     * @param bool|Closure $value
     * @return $this
     *
     * Enable/Disable automatic layout.
     */
    public function automaticLayout(bool | Closure $value = true): static
    {
        $this->automaticLayout = $value;

        return $this;
    }

    public function previewHeadEndContent(string | Closure $content = ''): static
    {
        $this->previewHeadEndContent = $content;

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

    public function getPreviewBodyStartContent()
    {
        return $this->evaluate($this->previewBodyStartContent);
    }

    public function getPreviewBodyEndContent()
    {
        return $this->evaluate($this->previewBodyEndContent);
    }
}
