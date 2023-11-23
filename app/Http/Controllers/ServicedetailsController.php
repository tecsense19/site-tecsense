<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ourservicedetail;
use App\Models\ServiceImage;
use Illuminate\Support\Facades\Validator;
use DB;

class ServicedetailsController extends Controller
{
    function servicedetail_store(Request $request){
        try {
            $input = $request->all();
            $rules = array(
                'service_id' => 'required',
                'service_detail_title' => 'required'
            );
            $validator = Validator::make($input, $rules);

            
            if ($validator->fails()) {
                return redirect()->route('home')->withErrors($validator)->withInput();
            }
            else{
                 // Remove only image
                $imageIds = isset($input['remove_servicedetail_pic']) ? explode(',', $input['remove_servicedetail_pic']) : [];
                if(count($imageIds) > 0)
                {
                    foreach ($imageIds as $value) 
                    {
                        $getImg = ServiceImage::where('id', $value)->first();
                        
                        if ($getImg) {
                            $proFilePath = $getImg->image_path;
                            $proPath = substr(strstr($proFilePath, 'public/'), strlen('public/'));

                            if (file_exists(public_path($proPath))) {
                                \File::delete(public_path($proPath));
                            }
                        }

                        ServiceImage::where('id', $value)->update(['image_path' => '']);
                    }
                }

                // Remove image with title
                $serviceIds = isset($input['remove_service_detail']) ? explode(',', $input['remove_service_detail']) : [];
                if(count($serviceIds) > 0)
                {
                    foreach ($serviceIds as $value) 
                    {
                        $getImg = ServiceImage::where('id', $value)->first();
                        
                        if ($getImg) {
                            $proFilePath = $getImg->image_path;
                            $proPath = substr(strstr($proFilePath, 'public/'), strlen('public/'));

                            if (file_exists(public_path($proPath))) {
                                \File::delete(public_path($proPath));
                            }
                        }

                        ServiceImage::where('id', $value)->delete();
                    }
                }

                $dataArr = array(
                'service_id' => $input['service_id'] ? $input['service_id'] : '',
                'service_detail_title' => $input['service_detail_title'] ? $input['service_detail_title'] : ''
                );
                
                if($profile = $request->file('servicedetail_pic')){
                    $path = 'public/uploads/servicesdetails';
                    $filename = 'profile_' . time() . '_' . $profile->getClientOriginalName();
                    $profile->move($path, $filename);
                    $dataArr['servicedetail_pic'] = $path . '/' . $filename;
                }

                $serviceId = '';
                if(isset($input['service_detail_id']) && $input['service_detail_id'] != '')
                {
                    $checkTitle = Ourservicedetail::where('service_detail_title', $input['service_detail_title'])->where('id', '!=', $input['service_detail_id'])->first();
                    if($checkTitle)
                    {
                        return response()->json(['error' => false, 'message' => 'Service title already exist.', 'data' => array()], 200);
                    }

                    Ourservicedetail::where('id', $input['service_detail_id'])->update($dataArr);
                    $serviceId = $input['service_detail_id'];

                    $message = 'Service update successfully.';
                }else{

                    $checkTitle = Ourservicedetail::where('service_detail_title', $input['service_detail_title'])->first();
                    if($checkTitle)
                    {
                        return response()->json(['error' => false, 'message' => 'Service title already exist.', 'data' => array()], 200);
                    }
                    $lastService = Ourservicedetail::create($dataArr);
                    $serviceId = $lastService->id;
                    $message = 'Service details added successfully.';
                }
        

                $checkImageCount = isset($input['image_title']) ? count($input['image_title']) : 0;
                $uploadedFiles = $request->file('service_image');
                $file = null;

                for ($i=0; $i < $checkImageCount; $i++) 
                {
                    $imageArr = array(
                        'servicedetial_id' => $serviceId,
                        'title' => $input['image_title'][$i] ? $input['image_title'][$i] : ''
                    );
                    
                    if (isset($uploadedFiles[$i]) && $uploadedFiles[$i]->isValid()) {
                        $file = $uploadedFiles[$i];
                    } else {
                        $file = null; // Or any default value you prefer
                    }
                    
                    $path = 'public/uploads/servicesdetails';

                    if(isset($input['image_id'][$i]) && $input['image_id'][$i] != '')
                    {
                        if($file)
                        {
                            $getImage = ServiceImage::where('id', $input['image_id'][$i])->first();
                            if ($getImage) {
                                $proFilePath = $getImage->image_path;
                                $proPath = substr(strstr($proFilePath, 'public/'), strlen('public/'));

                                if (file_exists(public_path($proPath))) {
                                    \File::delete(public_path($proPath));
                                }
                            }
                            
                            $filename = time() . '_' . $file->getClientOriginalName();
                            $file->move($path, $filename);
                            $imageArr['image_path'] = $path . '/' . $filename;
                        }
                        ServiceImage::where('id', $input['image_id'][$i])->update($imageArr);
                    }
                    else
                    {
                        if($file)
                        {
                            $filename = time() . '_' . $file->getClientOriginalName();
                            $file->move($path, $filename);
                            $imageArr['image_path'] = $path . '/' . $filename;
                        }

                        ServiceImage::create($imageArr);
                    }
                }

                return response()->json(['success' => true, 'message' => $message, 'data' => array()], 200);
            }
        } catch (\Throwable $th) {
            return response()->json(['error' => false, 'message' => $th, 'data' => array()], 200);
        }
    }

    function show(Request $request) {
        try {
            $input = $request->all();
            $serviceDataArr = [];
            if(isset($input['search_service_detail']) && $input['search_service_detail'] != '')
            {
                $serviceDataArr = Ourservicedetail::with('serviceDetailImages')
                                    ->where('service_detail_title', 'like', '%' . $input['search_service_detail'] . '%')
                                    ->orWhereHas('serviceDetailImages', function ($subquery) use ($input) {
                                        $subquery->where('title', 'like', '%' . $input['search_service_detail'] . '%');
                                    })
                                    ->orderBy('id', 'desc')->get();
            }else{
                $serviceDataArr = Ourservicedetail::with('serviceDetailImages','serviceTitle')->orderBy('ourservicedetails.id', 'desc')->get();
            }
            return view('admin/list/service-details', compact('serviceDataArr'));
        } catch (\Throwable $th) {
            return response()->json(['error' => false, 'message' => $th, 'data' => array()], 200);
        }
    }

    public function delete(Request $request) 
    {
        try {
            $input = $request->all();
            
            $serviceIds = isset($input['service_id']) ? $input['service_id'] : '';
            if($serviceIds)
            {
                $getService = Ourservicedetail::with('serviceDetailImages')->where('id', $input['service_id'])->first();
                
                foreach ($getService->serviceDetailImages as $values) 
                {
                    if ($values) {
                        $proFilePath = $values->image_path;
                        $proPath = substr(strstr($proFilePath, 'public/'), strlen('public/'));

                        if (file_exists(public_path($proPath))) {
                            \File::delete(public_path($proPath));
                        }
                    }
                }

                Ourservicedetail::where('id', $input['service_id'])->delete();

                return response()->json(['success' => true, 'message' => 'Service deleted successfully.', 'data' => array()], 200);
            }
            else
            {
                return response()->json(['error' => false, 'message' => 'Invalid service id', 'data' => array()], 200);
            }
        } catch (\Throwable $th) {
            return response()->json(['error' => false, 'message' => $th, 'data' => array()], 200);
        }
    }
}
