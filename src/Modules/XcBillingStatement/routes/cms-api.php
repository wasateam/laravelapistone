<?php

use \Wasateam\Laravelapistone\Modules\XcBillingStatement\App\Controllers\XcBillingStatementController;

\Route::group([
  "middleware" => [
    "isadmin",
    "wasascopes:xc_billing_statement-my",
  ],
], function () {
  \Route::get("xc_billing_statement/my", [XcBillingStatementController::class, 'index']);
  \Route::get("xc_billing_statement/my/{id}", [XcBillingStatementController::class, 'show']);
  Route::post("xc_billing_statement/my1", [XcBillingStatementController::class, 'store']);
});

\Route::group([
  "middleware" => [
    "wasascopes:xc_billing_statement-review",
  ],
], function () {
  \Route::get("xc_billing_statement", [XcBillingStatementController::class, 'index']);
  \Route::get("xc_billing_statement/{id}", [XcBillingStatementController::class, 'show']);
  Route::patch("xc_billing_statement/{id}", [XcBillingStatementController::class, 'update']);
  Route::delete("xc_billing_statement/{id}", [XcBillingStatementController::class, 'destroy']);
});

