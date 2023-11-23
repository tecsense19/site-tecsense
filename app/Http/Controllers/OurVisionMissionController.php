<?php

namespace App\Http\Controllers;

use App\Models\OurVision;
use App\Models\ServiceImage;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class OurVisionMissionController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $input = $request->all();

            $rules = array(
                'vision_text' => 'required|string',
                'vision_title' => 'required|string'
            );

            $validator = Validator::make($input, $rules);

            if ($validator->fails()) {

                return redirect()->route('about')->withErrors($validator)->withInput();

            } else {

                // Remove image with title
                $visionIds = isset($input['remove_vision']) ? explode(',', $input['remove_vision']) : [];

                if(count($visionIds) > 0)
                {
                    foreach ($visionIds as $value) 
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

                // Remove only image
                $imageIds = isset($input['remove_vision_image']) ? explode(',', $input['remove_vision_image']) : [];

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

                $dataArr = array(
                    'text' => $input['vision_text'] ? $input['vision_text'] : '',
                    'title' => $input['vision_title'] ? $input['vision_title'] : ''
                );

                if($aboutImg = $request->file('vision_section_image'))
                {
                    $checkImg = OurVision::where('id', $input['vision_id'])->first();
                    if ($checkImg) {
                        $proFilePath = $checkImg->section_image;
                        $proPath = substr(strstr($proFilePath, 'public/'), strlen('public/'));

                        if (file_exists(public_path($proPath))) {
                            \File::delete(public_path($proPath));
                        }
                    }

                    $path1 = 'public/uploads/vision';
                    $filename1 = Str::random(9) .'_'. time() . '_img_' . $aboutImg->getClientOriginalName();
                    $aboutImg->move($path1, $filename1);
                    $dataArr['section_image'] = $path1 . '/' . $filename1;
                }

                $visionId = '';
                if($input['vision_id'])
                {
                    $checkTitle = OurVision::where('title', $input['vision_title'])->where('id', '!=', $input['vision_id'])->first();
                    if($checkTitle)
                    {
                        return response()->json(['error' => false, 'message' => 'Vision title already exist.', 'data' => array()], 200);
                    }

                    OurVision::where('id', $input['vision_id'])->update($dataArr);
                    $visionId = $input['vision_id'];

                    $message = 'Details update successfully.';
                }
                else
                {
                    $checkTitle = OurVision::where('title', $input['vision_title'])->first();
                    if($checkTitle)
                    {
                        return response()->json(['error' => false, 'message' => 'Vision title already exist.', 'data' => array()], 200);
                    }

                    $lastAbout = OurVision::create($dataArr);
                    $visionId = $lastAbout->id;

                    $message = 'Details added successfully.';
                }

                $checkImageCount = count($input['vision_image_title']);

                $uploadedFiles = $request->file('vision_image');
                $file = null;

                for ($i=0; $i < $checkImageCount; $i++) 
                {
                    $imageArr = array(
                        'vision_id' => $visionId,
                        'title' => $input['vision_image_title'][$i] ? $input['vision_image_title'][$i] : '',
                        'description' => $input['vision_description'][$i] ? $input['vision_description'][$i] : ''
                    );
                    
                    if (isset($uploadedFiles[$i]) && $uploadedFiles[$i]->isValid()) {
                        $file = $uploadedFiles[$i];
                    } else {
                        $file = null; // Or any default value you prefer
                    }

                    $path = 'public/uploads/vision';
                    if(isset($input['vision_image_id'][$i]) && $input['vision_image_id'][$i] != '')
                    {                         
                        if($file != null)
                        {
                            $getImage = ServiceImage::where('id', $input['vision_image_id'][$i])->first();
                            if ($getImage) {
                                $proFilePath = $getImage->image_path;
                                $proPath = substr(strstr($proFilePath, 'public/'), strlen('public/'));

                                if (file_exists(public_path($proPath))) {
                                    \File::delete(public_path($proPath));
                                }
                            }
                            
                            $filename = Str::random(9) .'_'. time() . '_' . $file->getClientOriginalName();
                            $file->move($path, $filename);
                            $imageArr['image_path'] = $path . '/' . $filename;
                        }

                        ServiceImage::where('id', $input['vision_image_id'][$i])->update($imageArr);
                    }
                    else
                    {
                        if($file)
                        {
                            $filename = Str::random(9) .'_'. time() . '_' . $file->getClientOriginalName();
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

    public function details(Request $request)
    {
        try {
            $input = $request->all();

            $visionDataArr = OurVision::with('visionImages')->orderBy('id', 'desc')->first();
            
            return response()->json(['success' => true, 'message' => 'Vision details get successfully.', 'data' => $visionDataArr], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => false, 'message' => $th, 'data' => array()], 200);
        }
    }
}