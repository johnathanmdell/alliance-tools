<?php
namespace App\Console\Commands;

use App\Managers\CrestApi;
use AllianceTools\SolarSystem\SolarSystemInterface;
use Illuminate\Console\Command;

class SolarSystemCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crest:solar-system';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retrieves the solar systems from CREST';

    /**
     * Execute the console command.
     *
     * @param SolarSystemInterface $solarSystem
     * @return mixed
     */
    public function handle(SolarSystemInterface $solarSystem)
    {
        $crestApi = new CrestApi('https://crest-tq.eveonline.com/');

        $items = $crestApi->getItems('/solarsystems/');
        foreach ($items as $key => $item) {
            $solarSystem->updateOrCreate(
                ['id' => $item->id],
                ['name' => $item->name, 'id' => $item->id]
            );
        }
    }
}