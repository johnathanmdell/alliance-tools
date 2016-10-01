<?php
namespace App\Console\Commands;

use App\ItemGroup;
use App\ItemType;
use App\Managers\CrestApi;
use Illuminate\Console\Command;

class ItemGroupCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crest:item-group';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retrieves the item groups from CREST';

    /**
     * @var array
     */
    protected $items = [];

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $crestApi = new CrestApi('https://crest-tq.eveonline.com/');

        $items = $crestApi->getItems('/inventory/groups/');
        foreach ($items as $key => $item) {
            // TODO Find out why ids are not available for Item Groups
            $explodedUrl = explode('/', $item->href);
            $itemGroup = ItemGroup::updateOrCreate(
                ['id' => $explodedUrl[5]],
                ['name' => $item->name, 'id' => $explodedUrl[5]]
            );
            $items[$key] = $itemGroup;
        }

        $subItems = $crestApi->getSubItems('types');
        foreach ($subItems as $key => $subItem) {
            // TODO Find out why ids are not available for Item Types
            $explodedUrl = explode('/', $subItem->href);
            $itemType = ItemType::updateOrCreate(
                ['id' => $explodedUrl[4]],
                ['id' => $explodedUrl[4]]
            );
            $itemType->itemGroup()->associate($items[$subItem->parent])->save();
        }
    }
}