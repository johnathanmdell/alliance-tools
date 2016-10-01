<?php
namespace App\Console\Commands;

use App\Managers\CrestApi;
use AllianceTools\Constellation\ConstellationInterface;
use AllianceTools\SolarSystem\SolarSystemInterface;
use Illuminate\Console\Command;

class ConstellationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crest:constellation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retrieves the constellations from CREST';

    /**
     * Execute the console command.
     *
     * @param ConstellationInterface $constellation
     * @param SolarSystemInterface $solarSystem
     * @return mixed
     */
    public function handle(ConstellationInterface $constellation, SolarSystemInterface $solarSystem)
    {
        $crestApi = new CrestApi('https://crest-tq.eveonline.com/');

        $items = $crestApi->getItems('/constellations/');
        foreach ($items as $key => $item) {
            $constellation = $constellation->updateOrCreate(
                ['id' => $item->id],
                ['name' => $item->name, 'id' => $item->id]
            );
            $items[$key] = $constellation;
        }

        $subItems = $crestApi->getSubItems('systems');
        foreach ($subItems as $key => $subItem) {
            $system = $solarSystem->updateOrCreate(
                ['id' => $subItem->id],
                ['id' => $subItem->id]
            );
            $system->constellation()->associate($items[$subItem->parent])->save();
        }
    }
}