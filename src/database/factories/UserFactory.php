<?php

namespace Wasateam\Laravelapistone\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Wasateam\Laravelapistone\Models\User;

class UserFactory extends Factory
{
  /**
   * The name of the factory's corresponding model.
   *
   * @var string
   */
  protected $model = User::class;

  /**
   * Define the model's default state.
   *
   * @return array
   */
  public function definition()
  {
    return [
      'name'              => $this->faker->name(),
      'email'             => $this->faker->unique()->safeEmail(),
      'email_verified_at' => now(),
      'password'          => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
      'is_active'         => 1,
      'uuid'              => Str::uuid(),
      'scopes'            => config('stone.auth.default_scopes'),
    ];
  }

  /**
   * Indicate that the model's email address should be unverified.
   *
   * @return \Illuminate\Database\Eloquent\Factories\Factory
   */
  public function unverified()
  {
    return $this->state(function (array $attributes) {
      return [
        'email_verified_at' => null,
      ];
    });
  }

  public function cowabunga()
  {
    return $this->state(function (array $attributes) {
      return [
        'name'     => 'cowabunga',
        'email'    => 'cowabunga@haha.com',
        'password' => '123123',
      ];
    });
  }
}
