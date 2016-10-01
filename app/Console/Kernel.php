<?php
namespace App\Console;

use App\Console\Commands\AllianceCommand;
use App\Console\Commands\BotCommand;
use App\Console\Commands\ConstellationCommand;
use App\Console\Commands\ItemCategoryCommand;
use App\Console\Commands\ItemGroupCommand;
use App\Console\Commands\ItemTypeCommand;
use App\Console\Commands\RegionCommand;
use App\Console\Commands\SolarSystemCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = array(
        RegionCommand::class,
        ConstellationCommand::class,
        SolarSystemCommand::class,
        ItemCategoryCommand::class,
        ItemGroupCommand::class,
        ItemTypeCommand::class,
        AllianceCommand::class,
        BotCommand::class,
    );

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        //
    }
}
