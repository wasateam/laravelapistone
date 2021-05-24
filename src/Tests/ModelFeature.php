<?php
namespace Wasateam\Laravelapistone\Tests;

use Tests\TestCase;

class ModelFeature extends TestCase
{
  /**
   * A basic feature test example.
   *
   * @return void
   */
  public static function index($tester, $url)
  {
    $response = $tester->json('GET', $url);
    return $response->assertStatus(200);
  }

  public static function show($tester, $url)
  {
    $response = $tester->json('GET', $url);
    return $response->assertStatus(200);
  }
}
