<?php

namespace AbdelhamidErrahmouni\FilamentMonacoEditor\Commands;

use Illuminate\Console\Command;

class MonacoEditorCommand extends Command
{
    public $signature = 'filament-monaco-editor';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
