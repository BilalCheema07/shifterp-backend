<?php

namespace App\Console;

use Carbon\Carbon;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    public function __construct(Application $app, Dispatcher $events)
    {
        $this->commands = $this->getCommandClasses();

        parent::__construct($app, $events);
    }

    protected function schedule(Schedule $schedule)
    {
        $schedule->command("edi:poll afi BARESN")
            ->everyMinute()->runInBackground();

        $schedule->command("edi:sendorder afi BARESN")
            ->everyMinute()->runInBackground();

        $fivePM = '17:00';
        $fourPM = '16:00';

        $schedule->command("edi:852daily afi BARESN")
            ->timezone('America/Denver')
            ->dailyAt($fourPM)
            ->runInBackground();

        $schedule->command("edi:852weekly afi BARESN")
            ->timezone('America/Denver')
            ->weeklyOn(Carbon::SATURDAY, $fivePM)
            ->runInBackground();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }

    /**
     * Gets list of class names within the commands directory.
     * @return array
     */
    private function getCommandClasses()
    {
        $path  = __DIR__.'/Commands/';
        $fqcns = [];

        $allFiles = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path));
        $phpFiles = new \RegexIterator($allFiles, '/\.php$/');

        foreach ($phpFiles as $phpFile) {

            // Don't load base commands.
            if (false !== stripos($phpFile, 'BaseCommand')) {
                continue;
            }

            $content = file_get_contents($phpFile->getRealPath());
            $tokens  = token_get_all($content);

            $namespace = '';

            for ($index = 0; isset($tokens[$index]); $index++) {
                if ( ! isset($tokens[$index][0])) {
                    continue;
                }

                if (T_NAMESPACE === $tokens[$index][0]) {
                    $index += 2;

                    while (isset($tokens[$index]) && is_array($tokens[$index])) {
                        $namespace .= $tokens[$index++][1];
                    }
                }

                if (T_CLASS === $tokens[$index][0] && T_WHITESPACE === $tokens[$index + 1][0] && T_STRING === $tokens[$index + 2][0]) {

                    $index   += 2;
                    $fqcns[] = $namespace.'\\'.$tokens[$index][1];

                    break;
                }
            }
        }

        return $fqcns;
    }
}
