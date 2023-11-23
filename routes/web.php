<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginRegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ServicesController;
use App\Http\Controllers\TechnologyController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\DedicatedExpertController;
use App\Http\Controllers\WhyChooseController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\WorkingProcessController;
use App\Http\Controllers\OurVisionMissionController;
use App\Http\Controllers\OurTeamController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\BlogCategoryController;
use App\Http\Controllers\FooterController;
use App\Http\Controllers\FooterMenuController;
use App\Http\Controllers\BlogTagController;
use App\Http\Controllers\ServicedetailsController;
use App\Http\Controllers\WhychoosedetailsController;
use App\Http\Controllers\FeaturesController;
use App\Http\Controllers\BestResultDetailController;
use App\Http\Controllers\QuickLookDetailController;
use App\Http\Controllers\BusinessDetailController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::controller(LoginRegisterController::class)->group(function() {
    Route::get('/', 'login')->name('login');
    Route::post('/authenticate', 'authenticate')->name('authenticate');
    Route::post('/store', 'store')->name('store');
    Route::get('/register', 'register')->name('register');
    Route::post('/logout', 'logout')->name('logout');
});


Route::group(['prefix' => '/admin', 'as' => 'admin.',], function () {
    Route::get('/dashboard', [LoginRegisterController::class, 'dashboard'])->name('dashboard');
    
    Route::get('/home', [HomeController::class, 'home'])->name('home');

    // Services
    Route::post('service/store', [ServicesController::class, 'store'])->name('service.store');
    Route::post('service/list', [ServicesController::class, 'show'])->name('service.list');
    Route::post('service/delete', [ServicesController::class, 'delete'])->name('service.delete');


    // services Details
    Route::get('/servicedetail', [HomeController::class,'servicedetail'])->name('servicedetail');
    Route::post('servicedetail/store', [ServicedetailsController::class,'servicedetail_store'])->name('service.servicedetail_store');
    Route::post('servicedetail/list', [ServicedetailsController::class, 'show'])->name('servicedetail.list');
    Route::post('servicedetail/delete', [ServicedetailsController::class, 'delete'])->name('servicedetail.delete');

    // WhyChoose Details
    Route::get('/whychoosedetail', [HomeController::class,'whychoosedetail'])->name('whychoosedetail');
    Route::post('whychoosedetail/store', [WhychoosedetailsController::class,'why_choose_detail_store'])->name('whychoose.why_choose_detail_store');
    Route::post('whychoosedetail/list', [WhychoosedetailsController::class, 'show'])->name('whychoose.list');
    Route::post('whychoosedetail/delete', [WhychoosedetailsController::class, 'delete'])->name('whychoose.delete');

    // features deatils
    Route::get('/featuresdetail',[HomeController::class,'featuresdetail'])->name('featuresdetail');
    Route::post('featuresdetail/store',[FeaturesController::class,'features_detail_store'])->name('feature.features_detail_store');
    Route::post('featuresdetail/list',[FeaturesController::class,'show'])->name('feature.list');
    Route::post('featuresdetail/delete', [FeaturesController::class, 'delete'])->name('feature.delete');

    // Best Result Detail
    Route::get('/bestresultdetail',[HomeController::class,'bestresultdetail'])->name('bestresultdetail');
    Route::post('bestresultdetail/store',[BestResultDetailController::class,'best_result_detail_store'])->name('bestresult.best_result_detail_store');
    Route::post('bestresultdetail/list',[BestResultDetailController::class,'show'])->name('bestresult.list');
    Route::post('bestresultdetail/delete', [BestResultDetailController::class, 'delete'])->name('bestresult.delete');

    // Quick Look Detail
    Route::get('/quicklookdetail',[HomeController::class,'quicklookdetail'])->name('quicklookdetail');
    Route::post('quicklookdetail/store',[QuickLookDetailController::class,'quick_look_detail_store'])->name('quicklook.quick_look_detail_store');
    Route::post('quicklookdetail/list',[QuickLookDetailController::class,'show'])->name('quicklook.list');
    Route::post('quicklookdetail/delete', [QuickLookDetailController::class, 'delete'])->name('quicklook.delete');

    // Business Detail
    Route::get('/businessdetail',[HomeController::class,'businessdetail'])->name('businessdetail');
    Route::post('businessdetail/store',[BusinessDetailController::class,'business_detail_store'])->name('businessdetail.business_detail_store');
    Route::post('businessdetail/list',[BusinessDetailController::class,'show'])->name('businessdetail.list');
    Route::post('businessdetail/delete', [BusinessDetailController::class, 'delete'])->name('businessdetail.delete');

    // Portfolio
    Route::post('portfolio/store', [PortfolioController::class, 'store'])->name('portfolio.store');
    Route::post('portfolio/list', [PortfolioController::class, 'show'])->name('portfolio.list');
    Route::post('portfolio/delete', [PortfolioController::class, 'delete'])->name('portfolio.delete');
    
    // Expert
    Route::post('expert/store', [DedicatedExpertController::class, 'store'])->name('expert.store');
    Route::post('expert/list', [DedicatedExpertController::class, 'show'])->name('expert.list');
    Route::post('expert/delete', [DedicatedExpertController::class, 'delete'])->name('expert.delete');

    // WhyChoose
    Route::post('why/choose/store', [WhyChooseController::class, 'store'])->name('why.choose.store');
    Route::post('why/choose/list', [WhyChooseController::class, 'show'])->name('why.choose.list');
    Route::post('why/choose/delete', [WhyChooseController::class, 'delete'])->name('why.choose.delete');
    
    // Technology
    Route::post('technology/store', [TechnologyController::class, 'store'])->name('technology.store');
    Route::post('technology/list', [TechnologyController::class, 'show'])->name('technology.list');
    Route::post('technology/delete', [TechnologyController::class, 'delete'])->name('technology.delete');
    
    // Testimonial
    Route::post('testimonial/store', [TestimonialController::class, 'store'])->name('testimonial.store');
    Route::post('testimonial/list', [TestimonialController::class, 'show'])->name('testimonial.list');
    Route::post('testimonial/delete', [TestimonialController::class, 'delete'])->name('testimonial.delete');

    // About Us
    Route::get('/about', [HomeController::class, 'about'])->name('about');

    Route::post('about/store', [AboutController::class, 'store'])->name('about.store');
    Route::post('about/details', [AboutController::class, 'details'])->name('about.details');

    // Working Process
    Route::post('working/process/store', [WorkingProcessController::class, 'store'])->name('working.process.store');
    Route::post('working/process/details', [WorkingProcessController::class, 'details'])->name('working.process.details');
    Route::post('working/process/delete', [WorkingProcessController::class, 'delete'])->name('working.process.delete');

    // Our Vision Mission
    Route::post('our/vision/store', [OurVisionMissionController::class, 'store'])->name('our.vision.store');
    Route::post('our/vision/details', [OurVisionMissionController::class, 'details'])->name('our.vision.details');
    Route::post('our/vision/delete', [OurVisionMissionController::class, 'delete'])->name('our.vision.delete');
    
    // Our Team
    Route::post('our/team/store', [OurTeamController::class, 'store'])->name('our.team.store');
    Route::post('our/team/details', [OurTeamController::class, 'details'])->name('our.team.details');
    Route::post('our/team/delete', [OurTeamController::class, 'delete'])->name('our.team.delete');

    // Blog
    Route::get('/blog', [HomeController::class, 'blog'])->name('blog');

    Route::post('blog/store', [BlogController::class, 'store'])->name('blog.store');
    Route::post('blog/list', [BlogController::class, 'show'])->name('blog.list');
    Route::post('blog/delete', [BlogController::class, 'delete'])->name('blog.delete');

    // Blog Category
    Route::get('blog/category', [HomeController::class, 'blogCategory'])->name('blog.category');

    Route::post('blog/category/store', [BlogCategoryController::class, 'store'])->name('category.store');
    Route::post('blog/category/list', [BlogCategoryController::class, 'show'])->name('category.list');
    Route::post('blog/category/delete', [BlogCategoryController::class, 'delete'])->name('category.delete');

    // Blog Tags
    Route::get('blog/tag', [HomeController::class, 'blogTag'])->name('blog.tag');

    Route::post('blog/tag/store', [BlogTagController::class, 'store'])->name('tag.store');
    Route::post('blog/tag/list', [BlogTagController::class, 'show'])->name('tag.list');
    Route::post('blog/tag/delete', [BlogTagController::class, 'delete'])->name('tag.delete');

    // Footer Category
    Route::get('footer', [HomeController::class, 'footer'])->name('footer');

    Route::post('footer/category/store', [FooterController::class, 'store'])->name('footer.category.store');
    Route::post('footer/category/list', [FooterController::class, 'show'])->name('footer.category.list');
    Route::post('footer/category/delete', [FooterController::class, 'delete'])->name('footer.category.delete');

    // Footer Menu
    Route::get('footer/menu', [HomeController::class, 'footerMenu'])->name('footer.menu');

    Route::post('footer/menu/store', [FooterMenuController::class, 'store'])->name('menu.store');
    Route::post('footer/menu/list', [FooterMenuController::class, 'show'])->name('menu.list');
    Route::post('footer/menu/delete', [FooterMenuController::class, 'delete'])->name('menu.delete');
});