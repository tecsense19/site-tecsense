<?php 

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\FooterCategory;
use App\Models\BlogTag;
use App\Models\BlogCategory;
use Illuminate\View\View; 
USE App\Models\OurService;
USE App\Models\WhyChoose;

class HomeController extends Controller
{
    /**
     * Instantiate a new LoginRegisterController instance.
     */
    public function __construct()
    {
        // $this->middleware('guest')->except([ 'logout', 'home' ]);
    }

    /**
     * Display a home page settings.
     *
     * @return \Illuminate\Http\Response
     */
    public function home()
    {
        return view('admin.home');
    }

    /**
     * Display a about page settings.
     *
     * @return \Illuminate\Http\Response
     */
    public function about()
    {
        return view('admin.about');
    }

    public function servicedetail()
    {
        $ourservice = OurService::get();
        return view('admin.servicedetail',compact('ourservice'));
    }


    public function whyChoosedetail()
    {
        $ourservice = OurService::get();
        return view('admin.whychoosedetail',compact('ourservice'));
    }

    function featuresdetail() {
        $ourservice = OurService::get();
        return view('admin.featuresdetail',compact('ourservice'));
    }

    function bestresultdetail() {
        $ourservice = OurService::get();
        return view('admin.bestresultdetail',compact('ourservice'));
    }

    function quicklookdetail(){
        $ourservice = OurService::get();
        return view('admin.quick-look-detail',compact('ourservice'));
    }

    function businessdetail(){
        $ourservice = OurService::get();
        return view('admin.business-detail',compact('ourservice'));
    }
    
    /**
     * Display a blog page settings.
     *
     * @return \Illuminate\Http\Response
     */
    public function blog()
    {
        $blogTagList = BlogTag::get();
        $blogCategoryList = BlogCategory::get();
        return view('admin.blog', compact('blogTagList', 'blogCategoryList'));
    }

    /**
     * Display a blog category page settings.
     *
     * @return \Illuminate\Http\Response
     */
    public function blogCategory()
    {
        return view('admin.blog_category');
    }

    /**
     * Display a blog tag page settings.
     *
     * @return \Illuminate\Http\Response
     */
    public function blogTag()
    {
        return view('admin.blog_tag');
    }
    
    /**
     * Display a footer category page settings.
     *
     * @return \Illuminate\Http\Response
     */
    public function footer()
    {
        return view('admin.footer_category');
    }
    
    /**
     * Display a footer menu page settings.
     *
     * @return \Illuminate\Http\Response
     */
    public function footerMenu()
    {
        $getCategoryList = FooterCategory::orderBy('id', 'asc')->get();
        return view('admin.footer_menu', compact('getCategoryList'));
    }
}