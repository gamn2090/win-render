<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TagType;
use Illuminate\Support\Facades\Hash;

class create_tags extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:tags';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        for($i = 0; $i < 22; $i++) {
            $tagType = new TagType();
            $tagType->name = "Location";
            $tagType->vendor_type_id = $i + 1;
            $tagType->allowed_values = '["Connecticut","Massachusetts","Rhode Island","New Hampshire","Vermont","Maine","New York","New Jersey"]';
            $tagType->input_type = "checkbox";
            $tagType->search_type = "checkbox";
            $tagType->save();
        }
        for($i = 0; $i < 22; $i++) {
            $tagType = new TagType();
            $tagType->name = "Budget";
            $tagType->vendor_type_id = $i + 1;
            $tagType->allowed_values = '[0,1,2,3,4,5,6,7]';
            $tagType->input_type = "account";
            $tagType->search_type = "checkbox";
            $tagType->save();
        }

    }
}
