<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Featuredetail;
use App\Models\ServiceImage;

class FeaturesController extends Controller
{

    function features_detail_store(Request $request){
        try {
            $input = $request->all();
            $rules = array(
                'service_id' => 'required',
                'text' => 'required',
            );
            $validator = Validator::make($input, $rules);
            if ($validator->fails()) {
                return redirect()->route('home')->withErrors($validator)->withInput();
            }else{
                // Remove only image
                $imageIds = isset($input['remove_feature_detail_pic']) ? explode(',', $input['remove_feature_detail_pic']) : [];
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
                $serviceIds = isset($input['remove_features_detail']) ? explode(',', $input['remove_features_detail']) : [];
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
                );
                $featuresLookId = '';
                    if(isset($input['features_detail_id']) && $input['features_detail_id'] != ''){
                        $checkTitle = Featuredetail::where('text', $input['text'])->where('id', '!=', $input['features_detail_id'])->first();
                        if($checkTitle)
                        {
                            return response()->json(['error' => false, 'message' => 'Best Result title already exist.', 'data' => array()], 200);
                        }
                        Featuredetail::where('id', $input['features_detail_id'])->update($dataArr);
                        $featuresLookId = $input['features_detail_id'];
                        $message = 'Features details update successfully.';
                    }else{
                        $checkTitle = Featuredetail::where('text', $input['text'])->first();
                        if($checkTitle)
                        {
                            return response()->json(['error' => false, 'message' => 'Best Result title already exist.', 'data' => array()], 200);
                        }
                        $lastquicklook = Featuredetail::create($dataArr);
                        $featuresLookId = $lastquicklook->id;
                        $message = 'Features details added successfully.';
                    }

                    $checkImageCount = isset($input['image_title']) ? count($input['image_title']) : 0;
                    $uploadedFiles = $request->file('image');
                    $file = null;
                    for ($i=0; $i < $checkImageCount; $i++){

                        $imageArr = array(
                            'features_detail_id' => $featuresLookId,
                            'title' => $input['image_title'][$i] ? $input['image_title'][$i] : '',
                            'description' => $input['description'][$i] ? $input['description'][$i] : ''
                        );

                        if (isset($uploadedFiles[$i]) && $uploadedFiles[$i]->isValid()) {
                            $file = $uploadedFiles[$i];
                        } else {
                            $file = null; // Or any default value you prefer
                        }
                        
                        $path = 'public/uploads/features';
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
        } catch (\Throwable $th) {
            return response()->json(['error' => false, 'message' => $th, 'data' => array()], 200);
        }
    }

    function show(Request $request){
        try {
            $input = $request->all();
            $serviceDataArr = [];
            if(isset($input['search_features_detail']) && $input['search_features_detail'] != '')
            {
                // dd($input);
                $serviceDataArr = QuickLookDetail::with('featuresDetailImages')
                                ->where('text', 'like', '%' . $input['search_quick_look_detail'] . '%')
                                ->orWhereHas('featuresDetailImages', function ($subquery) use ($input) {
                                    $subquery->where('title', 'like', '%' . $input['search_quick_look_detail'] . '%');
                                })
                                ->orWhereHas('serviceTitle', function ($subquery) use ($input) {
                                    $subquery->where('service_title', 'like', '%' . $input['search_quick_look_detail'] . '%');
                                })
                                ->orderBy('id', 'desc')->get();
            }else{
                $featuresDataArr = Featuredetail::with('featuresDetailImages','serviceTitle')->orderBy('features_details.id', 'desc')->get();
            }
            return view('admin/list/features-details', compact('featuresDataArr'));
        }catch (\Throwable $th) {
            return response()->json(['error' => false, 'message' => $th, 'data' => array()], 200);
        }
    }

    public function delete(Request $request) 
    {
        try {
            $input = $request->all();
            $serviceIds = isset($input['feature_id']) ? $input['feature_id'] : '';
            if($serviceIds)
            {
                Featuredetail::where('id', $input['feature_id'])->delete();

                return response()->json(['success' => true, 'message' => 'Feature deleted successfully.', 'data' => array()], 200);
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
