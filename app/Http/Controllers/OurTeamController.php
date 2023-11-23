<?php

namespace App\Http\Controllers;

use App\Models\OurTeam;
use App\Models\ServiceImage;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class OurTeamController extends Controller
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
                'team_text' => 'required|string',
                'team_title' => 'required|string'
            );

            $validator = Validator::make($input, $rules);

            if ($validator->fails()) {

                return redirect()->route('about')->withErrors($validator)->withInput();

            } else {

                // Remove image with title
                $teamIds = isset($input['remove_team']) ? explode(',', $input['remove_team']) : [];

                if(count($teamIds) > 0)
                {
                    foreach ($teamIds as $value) 
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
                $imageIds = isset($input['remove_team_image']) ? explode(',', $input['remove_team_image']) : [];

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
                    'text' => $input['team_text'] ? $input['team_text'] : '',
                    'title' => $input['team_title'] ? $input['team_title'] : ''
                );

                if($aboutImg = $request->file('team_section_image'))
                {
                    $checkImg = OurTeam::where('id', $input['team_id'])->first();
                    if ($checkImg) {
                        $proFilePath = $checkImg->section_image;
                        $proPath = substr(strstr($proFilePath, 'public/'), strlen('public/'));

                        if (file_exists(public_path($proPath))) {
                            \File::delete(public_path($proPath));
                        }
                    }

                    $path1 = 'public/uploads/team';
                    $filename1 = Str::random(9) .'_'. time() . '_img_' . $aboutImg->getClientOriginalName();
                    $aboutImg->move($path1, $filename1);
                    $dataArr['section_image'] = $path1 . '/' . $filename1;
                }

                $teamId = '';
                if($input['team_id'])
                {
                    $checkTitle = OurTeam::where('title', $input['team_title'])->where('id', '!=', $input['team_id'])->first();
                    if($checkTitle)
                    {
                        return response()->json(['error' => false, 'message' => 'Team title already exist.', 'data' => array()], 200);
                    }

                    OurTeam::where('id', $input['team_id'])->update($dataArr);
                    $teamId = $input['team_id'];

                    $message = 'Details update successfully.';
                }
                else
                {
                    $checkTitle = OurTeam::where('title', $input['team_title'])->first();
                    if($checkTitle)
                    {
                        return response()->json(['error' => false, 'message' => 'Team title already exist.', 'data' => array()], 200);
                    }

                    $lastAbout = OurTeam::create($dataArr);
                    $teamId = $lastAbout->id;

                    $message = 'Details added successfully.';
                }

                $checkImageCount = count($input['team_image_title']);

                $uploadedFiles = $request->file('team_image');
                $file = null;

                for ($i=0; $i < $checkImageCount; $i++) 
                {
                    $imageArr = array(
                        'team_id' => $teamId,
                        'title' => $input['team_image_title'][$i] ? $input['team_image_title'][$i] : '',
                        'description' => $input['team_description'][$i] ? $input['team_description'][$i] : ''
                    );
                    
                    if (isset($uploadedFiles[$i]) && $uploadedFiles[$i]->isValid()) {
                        $file = $uploadedFiles[$i];
                    } else {
                        $file = null; // Or any default value you prefer
                    }

                    $path = 'public/uploads/team';
                    if(isset($input['team_image_id'][$i]) && $input['team_image_id'][$i] != '')
                    {                         
                        if($file != null)
                        {
                            $getImage = ServiceImage::where('id', $input['team_image_id'][$i])->first();
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

                        ServiceImage::where('id', $input['team_image_id'][$i])->update($imageArr);
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

            $teamDataArr = OurTeam::with('teamImages')->orderBy('id', 'desc')->first();
            
            return response()->json(['success' => true, 'message' => 'Team details get successfully.', 'data' => $teamDataArr], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => false, 'message' => $th, 'data' => array()], 200);
        }
    }
}