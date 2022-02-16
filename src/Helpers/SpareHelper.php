<?php

namespace Wasateam\Laravelapistone\Helpers;

class SpareHelper
{
  public static function scopes()
  {
    return [

      'xc_work_type-read',
      'xc_work_type-edit',

      'xc_task_template-read',
      'xc_task_template-edit',

      'xc_task-read',
      'xc_task-edit',
      'xc_task-read-my',
      'xc_task-edit-my',
      'xc_task-read-my-xc_project',
      'xc_task-edit-my-xc_project',

    ];
  }
}