<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetItemTest extends TestCase
{
   
    /** @test */
    public function add_item_to_the_database()
    {
        $this->withoutExceptionHandling();

        $response = $this->post('getItems', [
            'type' => 'story',
            'by' => 'Manley',
            'time' => time(),
            'url' =>'https://www.google.com',
            'title' =>'Testing purposes',
        ]);

        $response->assertOk();
        $this->assertCount(1, Item::all());
    }
}
