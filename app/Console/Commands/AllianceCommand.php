<?php
namespace App\Console\Commands;

use App\Constellation;
use App\Managers\CrestApi;
use App\Region;
use Illuminate\Console\Command;
use AllianceTools\Alliance\Alliance;
use AllianceTools\Corporation\Corporation;

class AllianceCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'crest:alliance';

    /**
     * @var string
     */
    protected $description = 'Retrieves the alliances from CREST';

    /**
     * @return mixed
     */
    public function handle()
    {
        $crestApi = new CrestApi('https://crest-tq.eveonline.com/');

        $items = $crestApi->getItems('/alliances/');
        foreach ($items as $key => $item) {
            $alliance = Alliance::updateOrCreate(
                ['id' => $item->id],
                ['name' => $item->name, 'short_name' => $item->shortName, 'id' => $item->id]
            );
            $items[$key] = $alliance;
        }

        $subItems = $crestApi->getSubItems('corporations');
        foreach ($subItems as $key => $subItem) {
            $corporation = Corporation::updateOrCreate(
                ['id' => $subItem->id],
                ['name' => $subItem->name, 'id' => $subItem->id]
            );
            $corporation->alliance()->associate($items[$subItem->parent])->save();
        }
    }
}