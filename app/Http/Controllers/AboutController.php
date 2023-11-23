<?php

namespace App\Http\Controllers;

use App\Models\AboutUs;
use App\Models\ServiceImage;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AboutController extends Controller
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
                'about_text' => 'required|string',
                'about_title' => 'required|string',
                'about_description' => 'required|string'
            );

            $validator = Validator::make($input, $rules);

            if ($validator->fails()) {

                return redirect()->route('about')->withErrors($validator)->withInput();

            } else {

                // Remove image with title
                $aboutIds = isset($input['remove_about']) ? explode(',', $input['remove_about']) : [];

                if(count($aboutIds) > 0)
                {
                    foreach ($aboutIds as $value) 
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
                $imageIds = isset($input['remove_about_image']) ? explode(',', $input['remove_about_image']) : [];

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
                    'text' => $input['about_text'] ? $input['about_text'] : '',
                    'title' => $input['about_title'] ? $input['about_title'] : '',
                    'description' => $input['about_description'] ? $input['about_description'] : ''
                );

                if($aboutImg = $request->file('about_section_image'))
                {
                    $checkImg = AboutUs::where('id', $input['about_id'])->first();
                    if ($checkImg) {
                        $proFilePath = $checkImg->section_image;
                        $proPath = substr(strstr($proFilePath, 'public/'), strlen('public/'));

                        if (file_exists(public_path($proPath))) {
                            \File::delete(public_path($proPath));
                        }
                    }

                    $path1 = 'public/uploads/about';
                    $filename1 = Str::random(9) .'_'. time() . '_img_' . $aboutImg->getClientOriginalName();
                    $aboutImg->move($path1, $filename1);
                    $dataArr['section_image'] = $path1 . '/' . $filename1;
                }

                $aboutId = '';
                if($input['about_id'])
                {
                    $checkTitle = AboutUs::where('title', $input['about_title'])->where('id', '!=', $input['about_id'])->first();
                    if($checkTitle)
                    {
                        return response()->json(['error' => false, 'message' => 'About title already exist.', 'data' => array()], 200);
                    }

                    AboutUs::where('id', $input['about_id'])->update($dataArr);
                    $aboutId = $input['about_id'];

                    $message = 'Details update successfully.';
                }
                else
                {
                    $checkTitle = AboutUs::where('title', $input['about_title'])->first();
                    if($checkTitle)
                    {
                        return response()->json(['error' => false, 'message' => 'About title already exist.', 'data' => array()], 200);
                    }

                    $lastAbout = AboutUs::create($dataArr);
                    $aboutId = $lastAbout->id;

                    $message = 'Details added successfully.';
                }

                $checkImageCount = count($input['about_image_title']);

                $uploadedFiles = $request->file('about_image');
                $file = null;

                for ($i=0; $i < $checkImageCount; $i++) 
                {
                    $imageArr = array(
                        'about_id' => $aboutId,
                        'title' => $input['about_image_title'][$i] ? $input['about_image_title'][$i] : ''
                    );
                    
                    if (isset($uploadedFiles[$i]) && $uploadedFiles[$i]->isValid()) {
                        $file = $uploadedFiles[$i];
                    } else {
                        $file = null; // Or any default value you prefer
                    }

                    $path = 'public/uploads/about';
                    if(isset($input['about_image_id'][$i]) && $input['about_image_id'][$i] != '')
                    {                         
                        if($file != null)
                        {
                            $getImage = ServiceImage::where('id', $input['about_image_id'][$i])->first();
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

                        ServiceImage::where('id', $input['about_image_id'][$i])->update($imageArr);
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

            $aboutDataArr = AboutUs::with('aboutImages')->orderBy('id', 'desc')->first();
            
            return response()->json(['success' => true, 'message' => 'About details get successfully.', 'data' => $aboutDataArr], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => false, 'message' => $th, 'data' => array()], 200);
        }
    }
}