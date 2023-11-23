<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Models\Ourservicedetail;
use App\Models\WhyChooseDetail;
use App\Models\Featuredetail;
use App\Models\BestResult;
use App\Models\QuickLookDetail;
use App\Models\BusinessDetail;
// use Illuminate\Http\JsonResponse;

class DetailsController extends BaseController
{
    function deatilsList(Request $request){
        try {
            $input = $request->all();
            $serviceDataArr = [];

            if(isset($input['select_service']) && $input['select_service'] != ''){
                
                $serviceData = Ourservicedetail::with('serviceDetailImages','serviceTitle')->where('service_id', $input['select_service'])->orderBy('ourservicedetails.id', 'desc')->get()->toArray();
                $responseData = array_map(function ($item) {
                    $item['servicedetail_pic'] = $item['servicedetail_pic'] ? config('app.url') . '/' . $item['servicedetail_pic'] : '';
                    $item['service_detail_images'] = array_map(function ($serviceImage) {
                        $serviceImage['image_path'] = $serviceImage['image_path'] ? config('app.url') . '/' . $serviceImage['image_path'] : ''; // Replace with the desired URL or path
                        return $serviceImage;
                    }, $item['service_detail_images']);
                    return $item;
                }, $serviceData);
                $serviceDataArr['ourservice_detail'] = $responseData;
                

                $serviceData = WhyChooseDetail::with('whyChooseDetailImages', 'serviceTitle')->where('service_id', $input['select_service'])->orderBy('why_choose_details.id', 'desc')->get()->toArray();
                $responseData = array_map(function ($item) {
                    $item['why_choose_detail_pic'] = $item['why_choose_detail_pic'] ? config('app.url') . '/' . $item['why_choose_detail_pic'] : '';
                    $item['why_choose_detail_images'] = array_map(function ($serviceImage) {
                        $serviceImage['image_path'] = $serviceImage['image_path'] ? config('app.url') . '/' . $serviceImage['image_path'] : ''; // Replace with the desired URL or path
                        return $serviceImage;
                    }, $item['why_choose_detail_images']);
                    return $item;
                }, $serviceData);
                $serviceDataArr['whychoose_detail'] = $responseData;


                $serviceData = Featuredetail::with('featuresDetailImages','serviceTitle')->where('service_id', $input['select_service'])->orderBy('features_details.id', 'desc')->get()->toArray();
                $responseData = array_map(function ($item) {
                    $item['features_detail_images'] = array_map(function ($serviceImage) {
                        $serviceImage['image_path'] = $serviceImage['image_path'] ? config('app.url') . '/' . $serviceImage['image_path'] : ''; // Replace with the desired URL or path
                        return $serviceImage;
                    }, $item['features_detail_images']);
                    return $item;
                }, $serviceData);
                $serviceDataArr['features_detail'] = $responseData;

                $serviceData = BestResult::with('bestResultDetailImages','serviceTitle')->where('service_id', $input['select_service'])->orderBy('best_results_details.id', 'desc')->get()->toArray();
                $responseData = array_map(function ($item) {
                    $item['best_result_detail_images'] = array_map(function ($serviceImage) {
                        $serviceImage['image_path'] = $serviceImage['image_path'] ? config('app.url') . '/' . $serviceImage['image_path'] : ''; // Replace with the desired URL or path
                        return $serviceImage;
                    }, $item['best_result_detail_images']);
                    return $item;
                }, $serviceData);
                $serviceDataArr['best_detail_detail'] = $responseData;

                $serviceData = QuickLookDetail::with('quickLookDetailImages','serviceTitle')->where('service_id', $input['select_service'])->orderBy('quick_look_details.id', 'desc')->get()->toArray();
                $responseData = array_map(function ($item) {
                    $item['quick_look_detail_images'] = array_map(function ($serviceImage) {
                        $serviceImage['image_path'] = $serviceImage['image_path'] ? config('app.url') . '/' . $serviceImage['image_path'] : ''; // Replace with the desired URL or path
                        return $serviceImage;
                    }, $item['quick_look_detail_images']);
                    return $item;
                }, $serviceData);
                $serviceDataArr['quick_look_detail'] = $responseData;

                $serviceData = BusinessDetail::with('businessDetailImages','serviceTitle')->where('service_id', $input['select_service'])->orderBy('business_details.id', 'desc')->get()->toArray();
                $responseData = array_map(function ($item) {
                    $item['business_detail_images'] = array_map(function ($serviceImage) {
                        $serviceImage['image_path'] = $serviceImage['image_path'] ? config('app.url') . '/' . $serviceImage['image_path'] : ''; // Replace with the desired URL or path
                        return $serviceImage;
                    }, $item['business_detail_images']);
                    return $item;
                }, $serviceData);
                $serviceDataArr['business_detail'] = $responseData;

                return $this->sendResponse($serviceDataArr, 'Deatils list get successfully.');
            }else{
                return $this->sendError('Please select a service');
            }
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
}
