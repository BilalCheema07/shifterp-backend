<?php
namespace App\Console\Commands\Edi;

use App\Models\Legacy\Item;
use App\Services\Edi\EdiDocument;
use App\Services\Edi\FritoLayEdiDocument;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class EdiTest extends EdiBaseCommand
{
    public $signature = 'edi:test';
    public $description = "For Testing Functions";

    public function handle()
    {

        $ediContent = file_get_contents(base_path('tests/edi-test-files/segment-count/852_weeklyinventory_569542996_0303.edi'));

        $edi = new FritoLayEdiDocument($ediContent);

        dd($edi->getFooterSegments());

    }

}
