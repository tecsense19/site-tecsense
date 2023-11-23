<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QuickLookDetail;
use App\Models\ServiceImage;
use Illuminate\Support\Facades\Validator;

class QuickLookDetailController extends Controller
{
    function quick_look_detail_store(Request $request){
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
                $imageIds = isset($input['remove_quick_look_detail_pic']) ? explode(',', $input['remove_quick_look_detail_pic']) : [];
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
                $serviceIds = isset($input['remove_quick_look_detail']) ? explode(',', $input['remove_quick_look_detail']) : [];
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

                $quickLookId = '';
                if(isset($input['quick_look_detail_id']) && $input['quick_look_detail_id'] != ''){
                    $checkTitle = QuickLookDetail::where('text', $input['text'])->where('id', '!=', $input['quick_look_detail_id'])->first();
                    if($checkTitle)
                    {
                        return response()->json(['error' => false, 'message' => 'Best Result title already exist.', 'data' => array()], 200);
                    }
                    QuickLookDetail::where('id', $input['quick_look_detail_id'])->update($dataArr);
                    $quickLookId = $input['quick_look_detail_id'];
                    $message = 'QuickLook details update successfully.';
                }else{
                    $checkTitle = QuickLookDetail::where('text', $input['text'])->first();
                    if($checkTitle)
                    {
                        return response()->json(['error' => false, 'message' => 'Best Result title already exist.', 'data' => array()], 200);
                    }
                    $lastquicklook = QuickLookDetail::create($dataArr);
                    $quickLookId = $lastquicklook->id;
                    $message = 'QuickLook details added successfully.';
                }
                
                $checkImageCount = isset($input['image_title']) ? count($input['image_title']) : 0;
                $uploadedFiles = $request->file('quick_look_image');
                $file = null;
                for ($i=0; $i < $checkImageCount; $i++){
                    $imageArr = array(
                        'quick_look_detail_id' => $quickLookId,
                        'title' => $input['image_title'][$i] ? $input['image_title'][$i] : '',
                        'description' => $input['quick_look_description'][$i] ? $input['quick_look_description'][$i] : ''
                    );

                    if (isset($uploadedFiles[$i]) && $uploadedFiles[$i]->isValid()) {
                        $file = $uploadedFiles[$i];
                    } else {
                        $file = null; // Or any default value you prefer
                    }
                    $path = 'public/uploads/quicklook';
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

    function show(Request $request) {
        try {
            $input = $request->all();
            $serviceDataArr = [];
            if(isset($input['search_quick_look_detail']) && $input['search_quick_look_detail'] != '')
            {
                $serviceDataArr = QuickLookDetail::with('quickLookDetailImages')
                                    ->where('text', 'like', '%' . $input['search_quick_look_detail'] . '%')
                                    ->orWhereHas('quickLookDetailImages', function ($subquery) use ($input) {
                                        $subquery->where('title', 'like', '%' . $input['search_quick_look_detail'] . '%');
                                    })
                                    ->orWhereHas('serviceTitle', function ($subquery) use ($input) {
                                        $subquery->where('service_title', 'like', '%' . $input['search_quick_look_detail'] . '%');
                                    })
                                    ->orderBy('id', 'desc')->get();
            }else{
                $serviceDataArr = QuickLookDetail::with('quickLookDetailImages','serviceTitle')->orderBy('quick_look_details.id', 'desc')->get();
            }
            return view('admin/list/quick-look-detail', compact('serviceDataArr'));
        } catch (\Throwable $th) {
            return response()->json(['error' => false, 'message' => $th, 'data' => array()], 200);
        }
    }


    public function delete(Request $request) 
    {
        try {
            $input = $request->all();
            
            $serviceIds = isset($input['quick_look_id']) ? $input['quick_look_id'] : '';
            if($serviceIds)
            {
                $getService = QuickLookDetail::with('quickLookDetailImages')->where('id', $input['quick_look_id'])->first();
                
                foreach ($getService->quickLookDetailImages as $values) 
                {
                    if ($values) {
                        $proFilePath = $values->image_path;
                        $proPath = substr(strstr($proFilePath, 'public/'), strlen('public/'));

                        if (file_exists(public_path($proPath))) {
                            \File::delete(public_path($proPath));
                        }
                    }
                }

                QuickLookDetail::where('id', $input['quick_look_id'])->delete();

                return response()->json(['success' => true, 'message' => 'Quick Look Details deleted successfully.', 'data' => array()], 200);
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
