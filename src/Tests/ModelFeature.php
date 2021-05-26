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

  public static function create($tester, $url, $data)
  {
    $response = $tester->json('POST', $url, $data);
    $response->assertStatus(201);
  }

  public static function update($tester, $url, $data)
  {
    $response = $tester->json('PATCH', $url, $data);
    $response->assertStatus(200);
  }

  public static function delete_test($tester, $url)
  {
    $response = $tester->json('DELETE', $url);
    $response->assertStatus(200);
  }

  public static function general_test($tester, $data)
  {
    $type    = $data['type'];
    $methods = $data['methods'];
    $url     = $data['url'];

    if ($type === 'create' || $type === 'update') {
      $response = $tester->json($methods, $url, $data['post_data']);
    } else {
      $response = $tester->json($methods, $url);
    }

    return $response->assertStatus($data["status"]);
  }
}
