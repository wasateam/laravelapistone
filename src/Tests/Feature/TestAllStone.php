<?php

namespace Wasateam\Laravelapistone\Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Wasateam\Laravelapistone\Helpers\TestHelper;

class TestAllStone extends TestCase
{
  use DatabaseTransactions;

  public function __construct()
  {
    parent::setUp();
  }

  public function test()
  {
    TestHelper::test_all_stone($this);
    return $response->assertStatus(200);
  }
}
