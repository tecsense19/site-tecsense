<?php

namespace App\Http\Controllers\API\V1;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Models\OurService;
use App\Models\Technology;
use App\Models\Testimonial;
use App\Models\Portfolio;
use App\Models\DedicatedExperts;
use App\Models\WhyChoose;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Http\JsonResponse;

class HomeController extends BaseController
{
    public function servicesList(Request $request)
    {
        try {
            $input = $request->all();
            $serviceDataArr = [];

            if(isset($input['search_service']) && $input['search_service'] != '')
            {
                $serviceDataArr = OurService::with('serviceImages')
                                    ->where('service_title', 'like', '%' . $input['search_service'] . '%')
                                    ->orWhereHas('serviceImages', function ($subquery) use ($input) {
                                        $subquery->where('title', 'like', '%' . $input['search_service'] . '%');
                                    })
                                    ->orderBy('id', 'desc')
                                    ->get()->toArray();
            }
            else
            {
                $serviceDataArr = OurService::with('serviceImages')->orderBy('id', 'desc')->get()->toArray();
            }
            
            $responseData = array_map(function ($item) {
                $item['service_black_logo'] = $item['service_black_logo'] ? config('app.url') . '/' . $item['service_black_logo'] : '';
                $item['service_white_logo'] = $item['service_white_logo'] ? config('app.url') . '/' . $item['service_white_logo'] : '';
                $item['service_images'] = array_map(function ($serviceImage) {
                    $serviceImage['image_path'] = $serviceImage['image_path'] ? config('app.url') . '/' . $serviceImage['image_path'] : ''; // Replace with the desired URL or path
                    return $serviceImage;
                }, $item['service_images']);
                return $item;
            }, $serviceDataArr);

            return $this->sendResponse($responseData, 'Services list get successfully.');

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }    

    /**
     * Technology list api
     *
     * @return \Illuminate\Http\Response
     */
    public function technologyList(Request $request)
    {
        try {
            $input = $request->all();

            $technologyDataArr = [];
            if(isset($input['search_technology']) && $input['search_technology'] != '')
            {
                $technologyDataArr = Technology::with('technologyImages')
                                        ->where('technology_name', 'like', '%' . $input['search_technology'] . '%')
                                        ->orWhereHas('technologyImages', function ($subquery) use ($input) {
                                            $subquery->where('title', 'like', '%' . $input['search_technology'] . '%');
                                        })
                                        ->orderBy('id', 'desc')
                                        ->get()->toArray();
            }
            else
            {
                $technologyDataArr = Technology::with('technologyImages')->orderBy('id', 'desc')->get()->toArray();
            }
            
            $responseData = array_map(function ($item) {
                $item['technology_images'] = array_map(function ($technologyImage) {
                    $technologyImage['image_path'] = $technologyImage['image_path'] ? config('app.url') . '/' . $technologyImage['image_path'] : ''; // Replace with the desired URL or path
                    return $technologyImage;
                }, $item['technology_images']);            

                return $item;
            }, $technologyDataArr);

            return $this->sendResponse($responseData, 'Technology list get successfully.');

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * Testimonial list api
     *
     * @return \Illuminate\Http\Response
     */
    public function testimonialList(Request $request)
    {
        try {
            $input = $request->all();

            $testimonialDataArr = [];
            if(isset($input['search_testimonial']) && $input['search_testimonial'] != '')
            {
                $testimonialDataArr = Testimonial::with('testimonialImages')
                                        ->where('full_name', 'like', '%' . $input['search_testimonial'] . '%')
                                        ->orderBy('id', 'desc')
                                        ->get()->toArray();
            }
            else
            {
                $testimonialDataArr = Testimonial::with('testimonialImages')->orderBy('id', 'desc')->get()->toArray();
            }
            
            $responseData = array_map(function ($item) {
                $item['profile_pic'] = $item['profile_pic'] ? config('app.url') . '/' . $item['profile_pic'] : '';
                $item['testimonial_images'] = array_map(function ($testimonialImage) {
                    $testimonialImage['image_path'] = $testimonialImage['image_path'] ? config('app.url') . '/' . $testimonialImage['image_path'] : ''; // Replace with the desired URL or path
                    return $testimonialImage;
                }, $item['testimonial_images']);           

                return $item;

            }, $testimonialDataArr);

            return $this->sendResponse($responseData, 'Testimonial list get successfully.');

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * Portfolio list api
     *
     * @return \Illuminate\Http\Response
     */
    public function portfolioList(Request $request)
    {
        try {
            $input = $request->all();
                
            $testimonialDataArr = Portfolio::orderBy('id', 'desc')->get()->toArray();
            
            $responseData = array_map(function ($item) {
                $item['image_path'] = $item['image_path'] ? config('app.url') . '/' . $item['image_path'] : '';
                return $item;
            }, $testimonialDataArr);

            return $this->sendResponse($responseData, 'Portfolio list get successfully.');

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
    
    /**
     * Expert list api
     *
     * @return \Illuminate\Http\Response
     */
    public function expertList(Request $request)
    {
        try {
            $input = $request->all();
            $expertDataArr = [];

            if(isset($input['search_expert']) && $input['search_expert'] != '')
            {
                $expertDataArr = DedicatedExperts::with('expertImages')
                                    ->where('title', 'like', '%' . $input['search_expert'] . '%')
                                    ->orWhereHas('expertImages', function ($subquery) use ($input) {
                                        $subquery->where('title', 'like', '%' . $input['search_expert'] . '%');
                                    })
                                    ->orderBy('id', 'desc')
                                    ->get()->toArray();
            }
            else
            {
                $expertDataArr = DedicatedExperts::with('expertImages')->orderBy('id', 'desc')->get()->toArray();
            }
            
            $responseData = array_map(function ($item) {
                $item['expert_images'] = array_map(function ($expertImage) {
                    $expertImage['image_path'] = $expertImage['image_path'] ? config('app.url') . '/' . $expertImage['image_path'] : ''; // Replace with the desired URL or path
                    return $expertImage;
                }, $item['expert_images']);
                return $item;
            }, $expertDataArr);

            return $this->sendResponse($responseData, 'Expert list get successfully.');

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
    
    /**
     * Why Choose list api
     *
     * @return \Illuminate\Http\Response
     */
    public function whyChooseList(Request $request)
    {
        try {
            $input = $request->all();
            $whyChooseDataArr = [];

            if(isset($input['search_why_choose']) && $input['search_why_choose'] != '')
            {
                $whyChooseDataArr = WhyChoose::with('whyChooseImages')
                                    ->where('title', 'like', '%' . $input['search_why_choose'] . '%')
                                    ->orWhereHas('whyChooseImages', function ($subquery) use ($input) {
                                        $subquery->where('title', 'like', '%' . $input['search_why_choose'] . '%');
                                    })
                                    ->orderBy('id', 'desc')
                                    ->get()->toArray();
            }
            else
            {
                $whyChooseDataArr = WhyChoose::with('whyChooseImages')->orderBy('id', 'desc')->get()->toArray();
            }
            
            $responseData = array_map(function ($item) {
                $item['why_choose_images'] = array_map(function ($whyChooseImage) {
                    $whyChooseImage['image_path'] = $whyChooseImage['image_path'] ? config('app.url') . '/' . $whyChooseImage['image_path'] : ''; // Replace with the desired URL or path
                    return $whyChooseImage;
                }, $item['why_choose_images']);
                return $item;
            }, $whyChooseDataArr);

            return $this->sendResponse($responseData, 'Why choose list get successfully.');

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
}