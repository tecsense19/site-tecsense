<?php

namespace App\Http\Controllers\API\V1;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Models\AboutUs;
use App\Models\WorkingProcess;
use App\Models\OurVision;
use App\Models\OurTeam;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Http\JsonResponse;

class AboutController extends BaseController
{
    public function aboutUs(Request $request)
    {
        try {
            $input = $request->all();

            $aboutDataArr = AboutUs::with('aboutImages')->orderBy('id', 'desc')->get()->toArray();

            $responseData = array_map(function ($item) {
                $item['section_image'] = $item['section_image'] ? config('app.url') . '/' . $item['section_image'] : '';
                $item['about_images'] = array_map(function ($aboutImage) {
                    $aboutImage['image_path'] = $aboutImage['image_path'] ? config('app.url') . '/' . $aboutImage['image_path'] : ''; // Replace with the desired URL or path
                    return $aboutImage;
                }, $item['about_images']);
                return $item;
            }, $aboutDataArr);

            return $this->sendResponse($responseData, 'About list get successfully.');

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }    

    /**
     * Technology list api
     *
     * @return \Illuminate\Http\Response
     */
    public function ourWorkingProcess(Request $request)
    {
        try {
            $input = $request->all();

            $processDataArr = WorkingProcess::with('processImages')->orderBy('id', 'desc')->get()->toArray();
            
            $responseData = array_map(function ($item) {
                $item['process_images'] = array_map(function ($processImage) {
                    $processImage['image_path'] = $processImage['image_path'] ? config('app.url') . '/' . $processImage['image_path'] : ''; // Replace with the desired URL or path
                    return $processImage;
                }, $item['process_images']);            

                return $item;
            }, $processDataArr);

            return $this->sendResponse($responseData, 'Our working process list get successfully.');

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * Testimonial list api
     *
     * @return \Illuminate\Http\Response
     */
    public function ourVisionMission(Request $request)
    {
        try {
            $input = $request->all();

            $visionDataArr = OurVision::with('visionImages')->orderBy('id', 'desc')->get()->toArray();
            
            $responseData = array_map(function ($item) {
                $item['section_image'] = $item['section_image'] ? config('app.url') . '/' . $item['section_image'] : '';
                $item['vision_images'] = array_map(function ($visionImage) {
                    $visionImage['image_path'] = $visionImage['image_path'] ? config('app.url') . '/' . $visionImage['image_path'] : ''; // Replace with the desired URL or path
                    return $visionImage;
                }, $item['vision_images']);           

                return $item;

            }, $visionDataArr);

            return $this->sendResponse($responseData, 'Our vision list get successfully.');

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * Portfolio list api
     *
     * @return \Illuminate\Http\Response
     */
    public function ourTeam(Request $request)
    {
        try {
            $input = $request->all();
                
            $teamDataArr = OurTeam::with('teamImages')->orderBy('id', 'desc')->get()->toArray();
            
            $responseData = array_map(function ($item) {
                $item['team_images'] = array_map(function ($teamImage) {
                    $teamImage['image_path'] = $teamImage['image_path'] ? config('app.url') . '/' . $teamImage['image_path'] : ''; // Replace with the desired URL or path
                    return $teamImage;
                }, $item['team_images']);           

                return $item;

            }, $teamDataArr);

            return $this->sendResponse($responseData, 'Portfolio list get successfully.');

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
}