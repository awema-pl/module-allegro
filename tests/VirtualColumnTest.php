<?php

namespace AwemaPL\Allegro\Tests;

use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class VirtualColumnTest extends TestCase
{
    public function test()
    {
        $c = DB::table('allegro_competitive_prices')->where('seller_id', 2333)->get()->toArray();

        DB::table('allegro_competitive_prices')->insert([
            'user_id' =>5,
            'account_id' => 4,
            'offer_id' =>3,
            'prices' =>json_encode([
                [
                    'seller_id' =>23332,
                    'price' =>23.22
                ],
                [
                    'seller_id' =>23331,
                    'price' =>23.22
                ]
            ])
        ]);
    }
}
