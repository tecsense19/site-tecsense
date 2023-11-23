<?php

namespace App\Http\Controllers;

use App\Models\Technology;
use App\Models\ServiceImage;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Validator;

class TechnologyController extends Controller
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
                'technology_name' => 'required|string',
                'technology_description' => 'required|string'
            );

            $validator = Validator::make($input, $rules);

            if ($validator->fails()) {
                return redirect()->route('home')->withErrors($validator)->withInput();
            } else {

                // Remove image with title
                $tecImageIds = isset($input['remove_technology']) ? explode(',', $input['remove_technology']) : [];

                if(count($tecImageIds) > 0)
                {
                    foreach ($tecImageIds as $value) 
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
                $imageIds = isset($input['remove_tec_image']) ? explode(',', $input['remove_tec_image']) : [];

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
                    'technology_name' => $input['technology_name'] ? $input['technology_name'] : '',
                    'technology_description' => $input['technology_description'] ? $input['technology_description'] : ''
                );

                $technologyId = '';
                if($input['technology_id'])
                {
                    $checkName = Technology::where('technology_name', $input['technology_name'])->where('id', '!=', $input['technology_id'])->first();
                    if($checkName)
                    {
                        return response()->json(['error' => false, 'message' => 'Technology name already exist.', 'data' => array()], 200);
                    }

                    Technology::where('id', $input['technology_id'])->update($dataArr);
                    $technologyId = $input['technology_id'];

                    $message = 'Technology update successfully.';
                }
                else
                {
                    $checkName = Technology::where('technology_name', $input['technology_name'])->first();
                    if($checkName)
                    {
                        return response()->json(['error' => false, 'message' => 'Technology name already exist.', 'data' => array()], 200);
                    }

                    $lastTechnology = Technology::create($dataArr);
                    $technologyId = $lastTechnology->id;

                    $message = 'Technology added successfully.';
                }

                $checkImageCount = count($input['tec_image_title']);

                $uploadedFiles = $request->file('tec_image');
                $file = null;

                for ($i=0; $i < $checkImageCount; $i++) 
                {
                    $imageArr = array(
                        'technology_id' => $technologyId,
                        'title' => $input['tec_image_title'][$i] ? $input['tec_image_title'][$i] : ''
                    );
                    
                    if (isset($uploadedFiles[$i]) && $uploadedFiles[$i]->isValid()) {
                        $file = $uploadedFiles[$i];
                    } else {
                        $file = null; // Or any default value you prefer
                    }
                    
                    $path = 'public/uploads/technology';

                    if(isset($input['tec_image_id'][$i]) && $input['tec_image_id'][$i] != '')
                    {
                        if($file)
                        {
                            $getImage = ServiceImage::where('id', $input['tec_image_id'][$i])->first();
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

                        ServiceImage::where('id', $input['tec_image_id'][$i])->update($imageArr);
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

    public function show(Request $request)
    {
        try {
            $input = $request->all();
            
            $technologyDataArr = [];
            if(isset($input['search_technology']) && $input['search_technology'] != '')
            {
                $technologyDataArr = Technology::with('technologyImages')
                                    ->where('technology_name', 'like', '%' . $input['search_technology'] . '%')
                                    ->orWhereHas('technologyImages', function ($subquery) use ($input) {
                                        $subquery->where('title', 'like', '%' . $input['search_technology'] . '%');
                                    })
                                    ->orderBy('id', 'desc')
                                    ->paginate(env('LIST_PER_PAGE'));
            }
            else
            {
                $technologyDataArr = Technology::with('technologyImages')->orderBy('id', 'desc')->paginate(env('LIST_PER_PAGE'));
            }
            
            return view('admin/list/technology', compact('technologyDataArr'))->with('i', ($request->input('page', 1) - 1) * env('LIST_PER_PAGE'));
        } catch (\Throwable $th) {
            return response()->json(['error' => false, 'message' => $th, 'data' => array()], 200);
        }
    }

    public function delete(Request $request) 
    {
        try {
            $input = $request->all();
            
            $technologyIds = isset($input['technology_id']) ? $input['technology_id'] : '';

            if($technologyIds)
            {
                $getTechnology = Technology::with('technologyImages')->where('id', $input['technology_id'])->first();
                
                foreach ($getTechnology->technologyImages as $values) 
                {
                    if ($values) {
                        $proFilePath = $values->image_path;
                        $proPath = substr(strstr($proFilePath, 'public/'), strlen('public/'));

                        if (file_exists(public_path($proPath))) {
                            \File::delete(public_path($proPath));
                        }
                    }
                }

                Technology::where('id', $input['technology_id'])->delete();

                return response()->json(['success' => true, 'message' => 'Technology deleted successfully.', 'data' => array()], 200);
            }
            else
            {
                return response()->json(['error' => false, 'message' => 'Invalid technology id', 'data' => array()], 200);
            }
        } catch (\Throwable $th) {
            return response()->json(['error' => false, 'message' => $th, 'data' => array()], 200);
        }
    }
}
