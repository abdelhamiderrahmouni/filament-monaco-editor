<?php

namespace AbdelhamidErrahmouni\FilamentMonacoEditor\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \AbdelhamidErrahmouni\FilamentMonacoEditor\MonacoEditor
 */
class MonacoEditor extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \AbdelhamidErrahmouni\FilamentMonacoEditor\MonacoEditor::class;
    }
}
