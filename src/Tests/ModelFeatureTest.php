<?php
namespace Wasateam\Laravelapistone\Tests;

use Tests\TestCase;

class ModelFeatureTest extends TestCase
{

  /**
   * A basic feature test example.
   *
   * @return void
   */
  public static function test_index($tester, $test)
  {
    $url      = "/api/{$test->name}";
    $response = $tester->json('GET', $url);
    return $response->assertStatus(200);
  }

  public static function test_show($tester, $test)
  {
    $url      = "/api/{$test->name}/1";
    $response = $tester->json('GET', $url);
    return $response->assertStatus(200);
  }

  public static function test_create($tester, $test, $create_data)
  {
    $url      = "/api/{$test->name}";
    $response = $tester->json('POST', $url, $create_data);
    $response->assertStatus(201);
  }

  public static function test_update($tester, $test, $update_data)
  {
    $url      = "/api/{$test->name}/1";
    $response = $tester->json('PATCH', $url, $update_data);
    $response->assertStatus(200);
  }

  public static function test_delete($tester, $test)
  {
    $url      = "/api/{$test->name}/1";
    $response = $tester->json('DELETE', $url);
    $response->assertStatus(200);
  }

  public static function crud_test($test, $create_data, $update_data, $routes = [
    'index',
    'show',
    'create',
    'update',
    'delete',
  ]) {

    $token  = env('TEST_TOKEN');
    $tester = $test->withHeaders([
      'Accept'        => 'application/json',
      'Authorization' => $token,
    ]);

    if (in_array('index', $routes)) {
      self::test_index($tester, $test);
    }

    if (in_array('show', $routes)) {
      self::test_show($tester, $test);
    }

    if (in_array('create', $routes)) {
      self::test_create($tester, $test, $create_data);
    }

    if (in_array('update', $routes)) {
      self::test_update($tester, $test, $update_data);
    }

    if (in_array('delete', $routes)) {
      self::test_delete($tester, $test);
    }
  }
}
