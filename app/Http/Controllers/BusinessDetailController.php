<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\BusinessDetail;
use App\Models\ServiceImage;

class BusinessDetailController extends Controller
{
    function business_detail_store(Request $request){
        // try {
            $input = $request->all();
            $rules = array(
                'service_id' => 'required',
                'text' => 'required',
                'business_description' => 'required',
            );
            $validator = Validator::make($input, $rules);

            if ($validator->fails()) {
                return redirect()->route('home')->withErrors($validator)->withInput();
            }else{

                // Remove only image
                $imageIds = isset($input['remove_business_pic']) ? explode(',', $input['remove_business_pic']) : [];
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
                $serviceIds = isset($input['remove_business_detail']) ? explode(',', $input['remove_business_detail']) : [];
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
                    'text' => $input['text'] ? $input['text'] : '',
                    'business_description' => $input['business_description'] ? $input['business_description'] : '',
                );

                $businessId = '';
                if(isset($input['business_detail_id']) && $input['business_detail_id'] != ''){
                    $checkTitle = BusinessDetail::where('text', $input['text'])->where('id', '!=', $input['business_detail_id'])->first();
                    if($checkTitle)
                    {
                        return response()->json(['error' => false, 'message' => 'Best Result title already exist.', 'data' => array()], 200);
                    }
                    BusinessDetail::where('id', $input['business_detail_id'])->update($dataArr);
                    $businessId = $input['business_detail_id'];
                    $message = 'Business details update successfully.';
                }else{
                    $lastquicklook = BusinessDetail::create($dataArr);
                    $businessId = $lastquicklook->id;
                    $message = 'Business details added successfully.';
                }

                $checkImageCount = isset($input['image_title']) ? count($input['image_title']) : 0;
                $uploadedFiles = $request->file('image');
                $file = null;
                for ($i=0; $i < $checkImageCount; $i++){
                    $imageArr = array(
                        'business_detail_id' => $businessId,
                        'title' => $input['image_title'][$i] ? $input['image_title'][$i] : '',
                    );

                    if (isset($uploadedFiles[$i]) && $uploadedFiles[$i]->isValid()) {
                        $file = $uploadedFiles[$i];
                    } else {
                        $file = null; // Or any default value you prefer
                    }
                    $path = 'public/uploads/business';
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
                    }else{
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
        // } catch (\Throwable $th) {
        //     return response()->json(['error' => false, 'message' => $th, 'data' => array()], 200);
        // }
    }

    function show(Request $request) {
        $input = $request->all();
        $serviceDataArr = [];
        if(isset($input['search_business_detail']) && $input['search_business_detail'] != '')
        {
            $serviceDataArr = BusinessDetail::with('businessDetailImages')
                                ->where('text', 'like', '%' . $input['search_business_detail'] . '%')
                                ->orWhereHas('businessDetailImages', function ($subquery) use ($input) {
                                    $subquery->where('title', 'like', '%' . $input['search_business_detail'] . '%');
                                })
                                ->orWhereHas('serviceTitle', function ($subquery) use ($input) {
                                    $subquery->where('service_title', 'like', '%' . $input['search_business_detail'] . '%');
                                })
                                ->orderBy('id', 'desc')->get();
        }else{
            $serviceDataArr = BusinessDetail::with('businessDetailImages','serviceTitle')->orderBy('business_details.id', 'desc')->get();
        }
        return view('admin/list/business-detail', compact('serviceDataArr'));
    }

    public function delete(Request $request) 
    {
        try {
            $input = $request->all();
            
            $serviceIds = isset($input['business_id']) ? $input['business_id'] : '';
            if($serviceIds)
            {
                $getService = BusinessDetail::with('businessDetailImages')->where('id', $input['business_id'])->first();
                
                foreach ($getService->businessDetailImages as $values) 
                {
                    if ($values) {
                        $proFilePath = $values->image_path;
                        $proPath = substr(strstr($proFilePath, 'public/'), strlen('public/'));

                        if (file_exists(public_path($proPath))) {
                            \File::delete(public_path($proPath));
                        }
                    }
                }

                BusinessDetail::where('id', $input['business_id'])->delete();

                return response()->json(['success' => true, 'message' => 'Business Details deleted successfully.', 'data' => array()], 200);
            }
            else
            {
                return response()->json(['error' => false, 'message' => 'Invalid Business id', 'data' => array()], 200);
            }
        } catch (\Throwable $th) {
            return response()->json(['error' => false, 'message' => $th, 'data' => array()], 200);
        }
    }
}
