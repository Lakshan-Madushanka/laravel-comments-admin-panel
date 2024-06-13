<?php

namespace LakM\Comments\AdminPanel\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Helper\SymfonyQuestionHelper;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class InstallCommand extends Command
{
    protected $signature = 'commenter-admin-panel:install';

    protected $description = 'This will install the package';

    public function handle(): void
    {
        $this->info("❤️ Commenter Admin Panel installer");

        $this->publishConfigs();
        $this->publishAssets();

        $this->showStatus();
        $this->askSupport();
    }

    private function publishConfigs(): void
    {
        $this->callSilent('vendor:publish', ['--tag' => 'comments-admin-panel-config']);
    }

    private function publishAssets(): void
    {
        $this->callSilent('filament:install', ['--tables', '--no-interaction' => true]);
        $this->callSilent('filament:install', ['--notifications', '--no-interaction' => true]);
        $this->callSilent('vendor:publish', ['--tag' => 'comments-admin-panel-assets']);
    }

    private function showStatus(): void
    {
        $this->newLine();

        $this->info("✅  Config published");
        $this->info("✅  Assets published");

        $this->newLine();

        $this->info("Installation completed successfully");
    }

    private function askSupport(): void
    {
        $this->newLine();

        $wantsToSupport = (new SymfonyQuestionHelper())->ask(
            new ArrayInput([]),
            $this->output,
            new ConfirmationQuestion(
                '<options=bold>❤️ Wanna encourage us by starring it on GitHub?</>',
                true,
            )
        );

        $link = "https://github.com/Lakshan-Madushanka/laravel-comments-admin-panel";

        if ($wantsToSupport === true) {
            if (PHP_OS_FAMILY == 'Darwin') {
                exec('open ' . $link);
            }
            if (PHP_OS_FAMILY == 'Windows') {
                exec('start ' . $link);
            }
            if (PHP_OS_FAMILY == 'Linux') {
                exec('xdg-open ' . $link);
            }
        }
    }
}
