<?php

namespace Wasateam\Laravelapistone\Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class TestContactRequest extends TestCase
{
  use DatabaseTransactions;
  public $name = 'contact_request';

  public function __construct()
  {
    parent::setUp();
  }

  public function test()
  {
    $create_data = [
      'name'         => '企鵝',
      'email'        => 'penguin@aa.aa',
      'tel'          => '0987987987',
      'remark'       => '不覺得企鵝很可愛嗎= =?',
      'company_name' => '企鵝極樂世界',
      'budget'       => 'nolimit',
    ];
    \Wasateam\Laravelapistone\Tests\ModelFeatureTest::crud_test($this, $create_data, [], [
      'create',
    ]);
  }
}
