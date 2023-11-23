<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WhyChooseDetail;
use Illuminate\Support\Facades\Validator;
use App\Models\ServiceImage;

class WhychoosedetailsController extends Controller
{
    function why_choose_detail_store(Request $request) {
        try {
            $input = $request->all();
            $rules = array(
                'service_id' => 'required',
                'why_choose_detail_title' => 'required',
                'why_choose_detail_description' => 'required'
            );
            
            $validator = Validator::make($input, $rules);
            
            if ($validator->fails()) {
                return redirect()->route('home')->withErrors($validator)->withInput();
            }else{
                
                // Remove only image
                $imageIds = isset($input['remove_why_choose_detail_pic']) ? explode(',', $input['remove_why_choose_detail_pic']) : [];
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
                $serviceIds = isset($input['remove_why_choose_detail']) ? explode(',', $input['remove_why_choose_detail']) : [];
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
                    'why_choose_detail_title' => $input['why_choose_detail_title'] ? $input['why_choose_detail_title'] : '',
                    'why_choose_detail_description' => $input['why_choose_detail_description'] ? $input['why_choose_detail_description'] : ''
                );

                if($profile = $request->file('why_choose_detail_pic')){
                    $path = 'public/uploads/whychoosedetails';
                    $filename = 'profile_' . time() . '_' . $profile->getClientOriginalName();
                    $profile->move($path, $filename);
                    $dataArr['why_choose_detail_pic'] = $path . '/' . $filename;
                }

                $serviceId = '';
                if(isset($input['why_choose_detail_id']) && $input['why_choose_detail_id'] != '')
                {
                    $checkTitle = WhyChooseDetail::where('why_choose_detail_title', $input['why_choose_detail_title'])->where('id', '!=', $input['why_choose_detail_id'])->first();
                    if($checkTitle)
                    {
                        return response()->json(['error' => false, 'message' => 'Service title already exist.', 'data' => array()], 200);
                    }

                    WhyChooseDetail::where('id', $input['why_choose_detail_id'])->update($dataArr);
                    $serviceId = $input['why_choose_detail_id'];
                    $message = 'Why Choose Deatil update successfully.';
                }else{
                    
                    $checkTitle = WhyChooseDetail::where('why_choose_detail_title', $input['why_choose_detail_title'])->first();
                    if($checkTitle)
                    {
                        return response()->json(['error' => false, 'message' => 'Service title already exist.', 'data' => array()], 200);
                    }

                    $lastWhychoose = WhyChooseDetail::create($dataArr);
                    $serviceId = $lastWhychoose->id;
                    $message = 'Why Choose details added successfully.';
                }

                // service_image tABLE in store a subimage and subtitle 
                $checkImageCount = isset($input['image_title']) ? count($input['image_title']) : 0;
                $uploadedFiles = $request->file('service_image');
                $file = null;
                for ($i=0; $i < $checkImageCount; $i++) 
                {
                    $imageArr = array(
                        'why_choose_detail_id' => $serviceId,
                        'title' => $input['image_title'][$i] ? $input['image_title'][$i] : ''
                    );
                    
                    if (isset($uploadedFiles[$i]) && $uploadedFiles[$i]->isValid()) {
                        $file = $uploadedFiles[$i];
                    } else {
                        $file = null; // Or any default value you prefer
                    }
                    
                    $path = 'public/uploads/whychoosedetails';

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

    function show(Request $request){
        try {
            $input = $request->all();
            
            $serviceDataArr = [];
            if(isset($input['search_Why_choose_detail']) && $input['search_Why_choose_detail'] != '')
            {
                // dd($input);
                $whychooseDataArr = WhyChooseDetail::with('whyChooseDetailImages')
                                    ->where('why_choose_detail_title', 'like', '%' . $input['search_Why_choose_detail'] . '%')
                                    ->orWhereHas('whyChooseDetailImages', function ($subquery) use ($input) {
                                        $subquery->where('title', 'like', '%' . $input['search_Why_choose_detail'] . '%');
                                    })
                                    ->orderBy('id', 'desc')->get();
            }else{
                $whychooseDataArr = WhyChooseDetail::with('whyChooseDetailImages', 'serviceTitle')->orderBy('why_choose_details.id', 'desc')->get();
            }
            return view('admin/list/why-choose-details', compact('whychooseDataArr'));
        } catch (\Throwable $th) {
            return response()->json(['error' => false, 'message' => $th, 'data' => array()], 200);
        } 
    }


    function delete(Request $request) {
        // try {
            $input = $request->all();
            $serviceIds = isset($input['why_choose_id']) ? $input['why_choose_id'] : '';
            if($serviceIds)
            {
                $getService = WhyChooseDetail::with('whyChooseDetailImages')->where('id', $input['why_choose_id'])->first();
                
                foreach ($getService->whyChooseDetailImages as $values) 
                {
                    if ($values) {
                        $proFilePath = $values->image_path;
                        $proPath = substr(strstr($proFilePath, 'public/'), strlen('public/'));

                        if (file_exists(public_path($proPath))) {
                            \File::delete(public_path($proPath));
                        }
                    }
                }

                WhyChooseDetail::where('id', $input['why_choose_id'])->delete();

                return response()->json(['success' => true, 'message' => 'Why choose detail deleted successfully.', 'data' => array()], 200);
            }
            else
            {
                return response()->json(['error' => false, 'message' => 'Invalid service id', 'data' => array()], 200);
            }
        // } catch (\Throwable $th) {
        //     return response()->json(['error' => false, 'message' => $th, 'data' => array()], 200);
        // }
    }
}
