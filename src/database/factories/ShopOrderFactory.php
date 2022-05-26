<?php

namespace Wasateam\Laravelapistone\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Wasateam\Laravelapistone\Models\ShopOrder;

class ShopOrderFactory extends Factory
{
  /**
   * The name of the factory's corresponding model.
   *
   * @var string
   */
  protected $model = ShopOrder::class;

  /**
   * Define the model's default state.
   *
   * @return array
   */
  public function definition()
  {
    return [
      'no'                => 'test-' . $this->faker->numberBetween(1000, 9999),
      'orderer'           => $this->faker->name(),
      'orderer_tel'       => $this->faker->phoneNumber(),
      'orderer_birthday'  => $this->faker->date(),
      'orderer_email'     => $this->faker->unique()->safeEmail(),
      'orderer_gender'    => $this->faker->randomElement(['male', 'femail']),
      'receiver'          => $this->faker->name(),
      'receiver_tel'      => $this->faker->phoneNumber(),
      'receiver_email'    => $this->faker->unique()->safeEmail(),
      'receiver_gender'   => $this->faker->randomElement(['male', 'femail']),
      'receiver_birthday' => $this->faker->date(),
      'receive_address'   => $this->faker->address(),
      'receive_remark'    => $this->faker->text(5),
      'status'            => 'not-established',
      'receive_way'       => $this->faker->randomElement([
        'phone-contact',
        'phone-contact-building-manager',
        'building-manager',
      ]),
      'ship_start_time'   => $this->faker->time(),
      'ship_end_time'     => $this->faker->time(),
      'ship_date'         => \Carbon\Carbon::parse($this->faker->dateTimeBetween('now', '+5 days'))->format('Y-m-d'),
      'delivery_date'     => \Carbon\Carbon::parse($this->faker->dateTimeBetween('now', '+5 days'))->format('Y-m-d'),
      'ship_status'       => 'unfulfilled',
      'pay_type'          => $this->faker->randomElement([
        'Credit',
        'CVS',
        'ATM',
        'BARCODE',
        'line_pay',
      ]),
      'pay_status'        => 'waiting',
      'user_id'           => 52258,
    ];
  }

  public function paid()
  {
    return $this->state(function (array $attributes) {
      return [
        'pay_status' => 'paid',
        'status'     => 'established',
      ];
    });
  }
}
