<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\HomeController;
use App\Http\Controllers\Api\V1\AboutController;
use App\Http\Controllers\Api\V1\BlogController;
use App\Http\Controllers\Api\V1\FooterController;
use App\Http\Controllers\Api\V1\DetailsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/v1/services/list', [HomeController::class, 'servicesList']);
Route::post('/v1/technology/list', [HomeController::class, 'technologyList']);
Route::post('/v1/testimonial/list', [HomeController::class, 'testimonialList']);
Route::post('/v1/portfolio/list', [HomeController::class, 'portfolioList']);
Route::post('/v1/expert/list', [HomeController::class, 'expertList']);
Route::post('/v1/why/choose/list', [HomeController::class, 'whyChooseList']);

Route::post('/v1/about/us', [AboutController::class, 'aboutUs']);
Route::post('/v1/our/working/process', [AboutController::class, 'ourWorkingProcess']);
Route::post('/v1/our/vision/mission', [AboutController::class, 'ourVisionMission']);
Route::post('/v1/our/team', [AboutController::class, 'ourTeam']);

Route::post('/v1/blog', [BlogController::class, 'blogList']);

Route::post('/v1/footer/menu', [FooterController::class, 'menuList']);


Route::post('/v1/details/list', [DetailsController::class, 'deatilsList']);