<?php
namespace App\Console\Commands;

use App\Constellation;
use App\Managers\CrestApi;
use App\Region;
use AllianceTools\Constellation\ConstellationInterface;
use AllianceTools\Region\RegionInterface;
use Illuminate\Console\Command;

class RegionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crest:region';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retrieves the regions from CREST';

    /**
     * Execute the console command.
     *
     * @param RegionInterface $region
     * @param ConstellationInterface $constellation
     * @return mixed
     */
    public function handle(RegionInterface $region, ConstellationInterface $constellation)
    {
        $crestApi = new CrestApi('https://crest-tq.eveonline.com/');

        $items = $crestApi->getItems('/regions/');
        foreach ($items as $key => $item) {
            $region = $region->updateOrCreate(
                ['id' => $item->id],
                ['name' => $item->name, 'id' => $item->id]
            );
            $items[$key] = $region;
        }

        $subItems = $crestApi->getSubItems('constellations');
        foreach ($subItems as $key => $subItem) {
            $constellation = $constellation->updateOrCreate(
                ['id' => $subItem->id],
                ['id' => $subItem->id]
            );
            $constellation->region()->associate($items[$subItem->parent])->save();
        }
    }
}