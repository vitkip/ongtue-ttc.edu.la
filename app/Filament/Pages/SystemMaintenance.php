<?php

namespace App\Filament\Pages;

use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Artisan;

class SystemMaintenance extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

    protected static ?string $navigationGroup = 'ລະບົບ';

    protected static ?string $navigationLabel = 'ບຳລຸງຮັກສາລະບົບ';

    protected static ?string $title = 'ບຳລຸງຮັກສາລະບົບ';

    protected static ?int $navigationSort = 99;

    protected static string $view = 'filament.pages.system-maintenance';

    /** Combined output of the last command batch that was run. */
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
        ];
    }

    public function clearAllCaches(): void
    {
        // Package commands (blade-icons) register only when running in the
        // console, so calling them from this web request throws
        // "command does not exist". Run only what is actually available here
        // and clear the rest by hand.
        $commands = [
            'config:clear',
            'route:clear',
            'view:clear',
            'cache:clear',
            'filament:clear-cached-components',
        ];

        $available = array_keys(Artisan::all());
        $lines = [];
        $failed = false;

        foreach ($commands as $command) {
            if (! in_array($command, $available, true)) {
                $lines[] = "\$ php artisan {$command}\nຂ້າມ (ບໍ່ມີໃນ context ນີ້)";

                continue;
            }

            try {
                Artisan::call($command);
                $out = trim(Artisan::output());
                $lines[] = "\$ php artisan {$command}\n" . ($out !== '' ? $out : 'OK');
            } catch (\Throwable $e) {
                $failed = true;
                $lines[] = "\$ php artisan {$command}\nERROR: " . $e->getMessage();
            }
        }

        // blade-icons cache file (normally cleared by `icons:clear`).
        try {
            $iconCache = app()->bootstrapPath('cache/blade-icons.php');
            if (is_file($iconCache)) {
                @unlink($iconCache);
                $lines[] = "rm bootstrap/cache/blade-icons.php\nOK";
            }
        } catch (\Throwable $e) {
            $lines[] = "rm bootstrap/cache/blade-icons.php\nERROR: " . $e->getMessage();
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
