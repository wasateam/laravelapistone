<?php

use \Wasateam\Laravelapistone\Modules\XcBillingStatement\App\Controllers\XcBillingStatementController;

\Route::group([
  "middleware" => [
    "wasascopes:xc_billing_statement-my",
  ],
], function () {
  \Route::get("/my/xc_billing_statement", [XcBillingStatementController::class, 'index_admin_my']);
  \Route::get("/my/xc_billing_statement/{id}", [XcBillingStatementController::class, 'show_admin_my']);
  \Route::post("/my/xc_billing_statement", [XcBillingStatementController::class, 'store_admin_my']);
});

\Route::group([
  "middleware" => [
    "wasascopes:xc_billing_statement-review",
  ],
], function () {
  \Route::get("xc_billing_statement", [XcBillingStatementController::class, 'index']);
  \Route::get("xc_billing_statement/{id}", [XcBillingStatementController::class, 'show']);
  \Route::patch("xc_billing_statement/{id}", [XcBillingStatementController::class, 'update']);
  \Route::delete("xc_billing_statement/{id}", [XcBillingStatementController::class, 'destroy']);
});
