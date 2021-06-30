<?php

namespace Wasateam\Laravelapistone\Helpers;

class TestHelper
{
  public static function crud_test($props)
  {
    $test        = array_key_exists('test', $props) ? $props['test'] : null;
    $create_data = array_key_exists('create_data', $props) ? $props['create_data'] : null;
    $update_data = array_key_exists('update_data', $props) ? $props['update_data'] : null;
    $routes      = array_key_exists('routes', $props) ? $props['routes'] : [
      'index',
      'show',
      'create',
      'update',
      'delete',
    ];
    $token = array_key_exists('token', $props) ? $props['token'] : env('TEST_TOKEN');

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
}
