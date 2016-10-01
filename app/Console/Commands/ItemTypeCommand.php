<?php
namespace App\Console\Commands;

use App\ItemType;
use App\Managers\CrestApi;
use Illuminate\Console\Command;

class ItemTypeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crest:item-type';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retrieves the item types from CREST';

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

        $items = $crestApi->getItems('/types/');
        foreach ($items as $key => $item) {
            ItemType::updateOrCreate(
                ['id' => $item->id],
                ['name' => $item->name, 'id' => $item->id]
            );
        }
    }
}