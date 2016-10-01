<?php
namespace App\Console\Commands;

use App\ItemCategory;
use App\ItemGroup;
use App\Managers\CrestApi;
use Illuminate\Console\Command;

class ItemCategoryCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crest:item-category';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retrieves the item categories from CREST';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $crestApi = new CrestApi('https://crest-tq.eveonline.com/');

        $items = $crestApi->getItems('/inventory/categories/');
        foreach ($items as $key => $item) {
            // TODO Find out why ids are not available for Item Categories
            $explodedUrl = explode('/', $item->href);
            $itemCategory = ItemCategory::updateOrCreate(
                ['id' => $explodedUrl[5]],
                ['name' => $item->name, 'id' => $explodedUrl[5]]
            );
            $items[$key] = $itemCategory;
        }

        $subItems = $crestApi->getSubItems('groups');
        foreach ($subItems as $key => $subItem) {
            // TODO Find out why ids are not available for Item Groups
            $explodedUrl = explode('/', $subItem->href);
            $itemGroup = ItemGroup::updateOrCreate(
                ['id' => $explodedUrl[5]],
                ['id' => $explodedUrl[5]]
            );
            $itemGroup->itemCategory()->associate($items[$subItem->parent])->save();
        }
    }
}