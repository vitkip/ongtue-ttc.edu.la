<?php

namespace App\Filament\Pages;

use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Process\Process;

class SystemMaintenance extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

    protected static ?string $navigationGroup = 'ລະບົບ';

    protected static ?string $navigationLabel = 'ບຳລຸງຮັກສາລະບົບ';

    protected static ?string $title = 'ບຳລຸງຮັກສາລະບົບ';

    protected static ?int $navigationSort = 99;

    protected static string $view = 'filament.pages.system-maintenance';

    /** Combined stdout/stderr of the last command that was run. */
    public ?string $lastOutput = null;

    /** 'success' | 'failed' | null */
    public ?string $lastStatus = null;

    public ?string $lastTitle = null;

    public ?string $lastFinishedAt = null;

    public static function canAccess(): bool
    {
        return (bool) (auth()->user()?->is_admin);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('clearCache')
                ->label('ລ້າງ Cache ທັງໝົດ')
                ->icon('heroicon-o-trash')
                ->color('warning')
                ->requiresConfirmation()
                ->modalHeading('ລ້າງ Cache ທັງໝົດ')
                ->modalDescription('ຈະລ້າງ config, route, view, application cache ແລະ cache ຂອງ Filament. ຄຳຮ້ອງທຳອິດຫຼັງຈາກນີ້ອາດຊ້າເລັກນ້ອຍ.')
                ->modalSubmitActionLabel('ລ້າງ Cache')
                ->action(fn () => $this->clearAllCaches()),

            Action::make('clearViewCache')
                ->label('ລ້າງ View Cache')
                ->icon('heroicon-o-document-text')
                ->color('gray')
                ->requiresConfirmation()
                ->modalHeading('ລ້າງ View Cache')
                ->modalDescription('ລ້າງແຕ່ compiled Blade views (storage/framework/views). ໄວ ແລະ ປອດໄພ.')
                ->modalSubmitActionLabel('ລ້າງ View Cache')
                ->action(fn () => $this->clearViewCache()),

            Action::make('runBuild')
                ->label('Run Build (npm run build)')
                ->icon('heroicon-o-cube')
                ->color('primary')
                ->requiresConfirmation()
                ->modalHeading('ແລ່ນ Build assets')
                ->modalDescription('ຈະແລ່ນ "npm run build" ເພື່ອ compile CSS/JS ໃໝ່. ອາດໃຊ້ເວລາ 10–90 ວິນາທີ, ຢ່າປິດໜ້ານີ້ໃນລະຫວ່າງນັ້ນ.')
                ->modalSubmitActionLabel('ແລ່ນ Build')
                ->action(fn () => $this->runBuild()),
        ];
    }

    public function clearAllCaches(): void
    {
        $commands = [
            'config:clear',
            'route:clear',
            'view:clear',
            'cache:clear',
            'filament:clear-cached-components',
            'icons:clear',
        ];

        $lines = [];
        $failed = false;

        foreach ($commands as $command) {
            try {
                Artisan::call($command);
                $out = trim(Artisan::output());
                $lines[] = "\$ php artisan {$command}\n" . ($out !== '' ? $out : 'OK');
            } catch (\Throwable $e) {
                $failed = true;
                $lines[] = "\$ php artisan {$command}\nERROR: " . $e->getMessage();
            }
        }

        $this->recordResult(
            title: 'ລ້າງ Cache ທັງໝົດ',
            output: implode("\n\n", $lines),
            success: ! $failed,
        );

        $this->notify(
            success: ! $failed,
            title: $failed ? 'ລ້າງ Cache ບໍ່ຄົບ (ມີ error)' : 'ລ້າງ Cache ສຳເລັດແລ້ວ',
        );
    }

    public function clearViewCache(): void
    {
        try {
            Artisan::call('view:clear');
            $this->recordResult(
                title: 'ລ້າງ View Cache',
                output: trim(Artisan::output()) ?: 'OK',
                success: true,
            );
            $this->notify(success: true, title: 'ລ້າງ View Cache ສຳເລັດແລ້ວ');
        } catch (\Throwable $e) {
            $this->recordResult(title: 'ລ້າງ View Cache', output: 'ERROR: ' . $e->getMessage(), success: false);
            $this->notify(success: false, title: 'ລ້າງ View Cache ລົ້ມເຫລວ');
        }
    }

    public function runBuild(): void
    {
        $npm = (string) config('admin_tools.npm_path', 'npm');
        $timeout = (int) config('admin_tools.build_timeout', 600);
        $home = (string) config('admin_tools.build_home');

        if ($home !== '' && ! is_dir($home)) {
            @mkdir($home, 0777, true);
        }

        $pathDirs = (array) config('admin_tools.build_path_dirs', []);
        if (str_contains($npm, '/')) {
            array_unshift($pathDirs, dirname($npm));
        }
        $path = implode(PATH_SEPARATOR, array_values(array_unique(array_filter($pathDirs))));

        $env = array_filter([
            'PATH' => $path !== '' ? $path : null,
            'HOME' => $home !== '' ? $home : null,
            'npm_config_cache' => $home !== '' ? $home . '/.npm' : null,
            'CI' => '1',
        ]);

        $process = new Process([$npm, 'run', 'build'], base_path(), $env, null, $timeout);

        try {
            $process->run();
        } catch (\Throwable $e) {
            $this->recordResult(
                title: 'Run Build',
                output: 'ບໍ່ສາມາດເລີ່ມ process ໄດ້: ' . $e->getMessage()
                    . "\n\nກວດ config/admin_tools.php (ADMIN_NPM_PATH) ໃຫ້ຊີ້ໄປຫາ npm ແບບ absolute path.",
                success: false,
            );
            $this->notify(success: false, title: 'Build ລົ້ມເຫລວ (ເລີ່ມ process ບໍ່ໄດ້)');

            return;
        }

        $output = trim($process->getOutput() . "\n" . $process->getErrorOutput());
        $success = $process->isSuccessful();

        if ($success) {
            // The freshly built assets change the Vite manifest; drop compiled
            // views so Apache re-renders against the new hashes.
            try {
                Artisan::call('view:clear');
            } catch (\Throwable) {
                // best effort
            }
        }

        $this->recordResult(
            title: 'Run Build',
            output: ($output !== '' ? $output : '(ບໍ່ມີ output)')
                . "\n\nexit code: " . $process->getExitCode(),
            success: $success,
        );

        $this->notify(
            success: $success,
            title: $success
                ? 'Build ສຳເລັດແລ້ວ'
                : 'Build ລົ້ມເຫລວ (exit code ' . $process->getExitCode() . ')',
        );
    }

    protected function recordResult(string $title, string $output, bool $success): void
    {
        $this->lastTitle = $title;
        $this->lastOutput = $output;
        $this->lastStatus = $success ? 'success' : 'failed';
        $this->lastFinishedAt = now()->format('Y-m-d H:i:s');
    }

    protected function notify(bool $success, string $title): void
    {
        $notification = Notification::make()->title($title);

        $success ? $notification->success() : $notification->danger();

        if (! $success) {
            $notification->body('ເບິ່ງ log ຂ້າງລຸ່ມສຳລັບລາຍລະອຽດ.');
        }

        $notification->send();
    }
}
