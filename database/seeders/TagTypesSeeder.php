<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class TagTypesSeeder extends Seeder
{
    /**
     * Idempotent seeder for the search-filter tag types shown on the
     * "Find Vendors" search page (Venue Type, Max Guest Capacity, Location, Budget, Style, Services, ...).
     *
     * - Resolves vendor_type_id by looking up the `type` name from vendor_types
     *   (seeded by VendorTypesSeeder), so it doesn't depend on hardcoded ids.
     * - Matches existing rows by (vendor_type_id, name); safe to re-run.
     * - Every vendor type gets a "Location" and "Budget" filter; a few types
     *   also get their own extra filters, mirroring the production data shape.
     */
    public function run(): void
    {
        $now = Carbon::now();
        $typeIds = DB::table('vendor_types')->pluck('id', 'type');

        $locationValues = '["Connecticut","Massachusetts","Rhode Island","New Hampshire","Vermont","Maine","New York","New Jersey"]';
        $budgetValues = '[0,1,2,3,4,5,6,7]';

        $rows = [
            ['type' => 'Venue', 'name' => 'Venue Type', 'allowed_values' => '["Ballroom","Barn","Beach","Brewery & Distillery","Castle","Country Club","Ocean","Estate","Garden","Historic","Hotel","Industrial & Warehouse","Library","Loft","Mountain","Museum","Park"]', 'input_type' => 'select', 'search_type' => 'checkbox'],
            ['type' => 'Venue', 'name' => 'Max Guest Capacity', 'allowed_values' => '["0-50","51-100","101-150","151-200","201-250","251-300","300+"]', 'input_type' => 'select', 'search_type' => 'select'],
            ['type' => 'Photographer', 'name' => 'Style', 'allowed_values' => '["Light & Airy","Muted Tones","Dark & Dramatic","Photojournalistic","Film","Fine Art","Editorial","Vibrant","True to Life","Playful & Quirky"]', 'input_type' => 'checkbox', 'search_type' => 'checkbox'],
            ['type' => 'Photographer', 'name' => 'Services', 'allowed_values' => '["Full Day Coverage","Multi Day Coverage","Ethinic Weddings","LGBTQ Friendly","Albums","Video","Engagement Session","Rehearsal Dinner","Destination"]', 'input_type' => 'checkbox', 'search_type' => 'checkbox'],
            ['type' => 'Hair & Makeup', 'name' => 'Services', 'allowed_values' => '["Beauty Group Bookings","Hair Stylists","Hair Trials Available","Makeup Artists","Makeup Trials Available","Nails","On-Site Hair & Makeup","Spas","Tanning","Teeth Whitening","Studio Available","Destination","Hair Only","Makeup Only","Henna"]', 'input_type' => 'checkbox', 'search_type' => 'checkbox'],
            ['type' => 'Hair & Makeup', 'name' => 'Style', 'allowed_values' => '["Glam","Natural","Air Brush"]', 'input_type' => 'checkbox', 'search_type' => 'checkbox'],
            ['type' => 'Videographer', 'name' => 'Style', 'allowed_values' => '["Light & Airy","Muted Tones","Dark & Dramatic","Photojournalistic","Film","Fine Art","Editorial","Vibrant","True to Life","Playful & Quirky"]', 'input_type' => 'checkbox', 'search_type' => 'checkbox'],
            ['type' => 'Videographer', 'name' => 'Services', 'allowed_values' => '["Highlight Film","Teaser Film","Documentary Film","Drone","Full Day Coverage","Multi Day Coverage","Ethinic Weddings","LGBTQ Friendly","Destination"]', 'input_type' => 'checkbox', 'search_type' => 'checkbox'],
        ];

        foreach ($typeIds as $type => $id) {
            $rows[] = ['type' => $type, 'name' => 'Location', 'allowed_values' => $locationValues, 'input_type' => 'checkbox', 'search_type' => 'checkbox'];
            $rows[] = ['type' => $type, 'name' => 'Budget', 'allowed_values' => $budgetValues, 'input_type' => 'account', 'search_type' => 'checkbox'];
        }

        foreach ($rows as $row) {
            $vendorTypeId = $typeIds[$row['type']] ?? null;
            if (!$vendorTypeId) {
                continue;
            }

            $existing = DB::table('tag_types')
                ->where('vendor_type_id', $vendorTypeId)
                ->where('name', $row['name'])
                ->first();

            $data = [
                'vendor_type_id' => $vendorTypeId,
                'name' => $row['name'],
                'allowed_values' => $row['allowed_values'],
                'input_type' => $row['input_type'],
                'search_type' => $row['search_type'],
                'is_required' => 0,
                'hidden' => 0,
                'updated_at' => $now,
            ];

            if ($existing) {
                DB::table('tag_types')->where('id', $existing->id)->update($data);
            } else {
                $data['created_at'] = $now;
                DB::table('tag_types')->insert($data);
            }
        }
    }
}
