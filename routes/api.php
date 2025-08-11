    <?php

    use App\Http\Controllers\Api\AttendanceController;
    use App\Http\Controllers\Api\BookingController;
    use App\Http\Controllers\Api\CategoriesController;
    use App\Http\Controllers\Api\DataPenjualanController;
    use App\Http\Controllers\Api\DetailsController;
    use App\Http\Controllers\Api\GoogleAuthController;
    use App\Http\Controllers\Api\ItemsController;
    use App\Http\Controllers\Api\LeaderboardController;
    use App\Http\Controllers\Api\ListTrainerController;
    use App\Http\Controllers\Api\LoginController;
    use App\Http\Controllers\Api\MembershipController;
    use App\Http\Controllers\Api\MembershipHistoryUserController;
    use App\Http\Controllers\Api\MembershipUserController;
use App\Http\Controllers\Api\PasswordResetController;
use App\Http\Controllers\Api\PaymentAdminController;
    use App\Http\Controllers\Api\PaymentController;
    use App\Http\Controllers\Api\PaymentHistoryUserController;
    use App\Http\Controllers\Api\PersonalTrainerController;
    use App\Http\Controllers\Api\RegisterController;
    use App\Http\Controllers\Api\ScanBarcodeController;
    use App\Http\Controllers\Api\UserController;
    use App\Http\Controllers\Api\UserProfile;
    use App\Http\Controllers\Api\WorkoutCategoryController;
    use App\Http\Controllers\Api\WorkoutExerciseController;
    use App\Http\Controllers\Api\WorkoutLevelController;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Route;

    Route::get('/user', function (Request $request) {
        return $request->user();
    })->middleware('auth:sanctum');

    Route::post('/password/forgot-password', [PasswordResetController::class, 'forgot']);
    Route::post('/password/reset-password', [PasswordResetController::class, 'reset']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::resource('/user', UserController::class);
    });
    Route::middleware('auth:sanctum')->group(function () {
        Route::resource('/user-profile', UserProfile::class);
        Route::put('/user-profile', [UserProfile::class, 'update']);
    });

    Route::resource('/register',RegisterController::class);
    Route::resource('/login', LoginController::class);
    Route::post('/google-login', [GoogleAuthController::class, 'handleGoogleFromFlutter']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::resource('/membership',MembershipController::class);
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/payment', [PaymentController::class, 'store']);
        Route::get('/payment', [PaymentController::class, 'index']);
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/booking', [BookingController::class, 'store']);
        Route::get('/booking', [BookingController::class, 'index']);
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/payment-admin', [PaymentAdminController::class, 'store']);
        Route::delete('/payment-admin/{id}', [PaymentAdminController::class, 'destroy']);
    });
    Route::middleware('auth:sanctum')->get('/payment-history-user', [PaymentHistoryUserController::class, 'store']);

    Route::get('/data-penjualan', [DataPenjualanController::class, 'index']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::resource('/membership-user', MembershipUserController::class);
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::resource('/membership-user-history', MembershipHistoryUserController::class);
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::resource('/scan-barcode', ScanBarcodeController::class)->only(['store']);
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/attendance', [AttendanceController::class, 'index']);
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::resource('/personal-trainers', PersonalTrainerController::class);
        Route::resource('/trainers', ListTrainerController::class);
    });

    Route::get('/leaderboard', [LeaderboardController::class, 'index']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::resource('items', ItemsController::class);
        Route::resource('categories', CategoriesController::class);
        Route::resource('details', DetailsController::class);
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::apiResource('workout-categories', WorkoutCategoryController::class);
        Route::apiResource('workout-levels', WorkoutLevelController::class);
        Route::apiResource('workout-exercises', WorkoutExerciseController::class);
    });

