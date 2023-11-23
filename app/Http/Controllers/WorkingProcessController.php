<?php

namespace App\Http\Controllers;

use App\Models\WorkingProcess;
use App\Models\ServiceImage;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class WorkingProcessController extends Controller
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
                'working_process_text' => 'required|string',
                'working_process_title' => 'required|string'
            );

            $validator = Validator::make($input, $rules);

            if ($validator->fails()) {

                return redirect()->route('about')->withErrors($validator)->withInput();

            } else {

                $dataArr = array(
                    'text' => $input['working_process_text'] ? $input['working_process_text'] : '',
                    'title' => $input['working_process_title'] ? $input['working_process_title'] : ''
                );

                $processId = '';
                if($input['working_process_id'])
                {
                    $checkTitle = WorkingProcess::where('title', $input['working_process_title'])->where('id', '!=', $input['working_process_id'])->first();
                    if($checkTitle)
                    {
                        return response()->json(['error' => false, 'message' => 'About title already exist.', 'data' => array()], 200);
                    }

                    WorkingProcess::where('id', $input['working_process_id'])->update($dataArr);
                    $processId = $input['working_process_id'];

                    $message = 'Details update successfully.';
                }
                else
                {
                    $checkTitle = WorkingProcess::where('title', $input['working_process_title'])->first();
                    if($checkTitle)
                    {
                        return response()->json(['error' => false, 'message' => 'About title already exist.', 'data' => array()], 200);
                    }

                    $lastAbout = WorkingProcess::create($dataArr);
                    $processId = $lastAbout->id;

                    $message = 'Details added successfully.';
                }

                $checkImageCount = !empty($request->file('working_process_images')) ? count($request->file('working_process_images')) : 0;

                $uploadedFiles = $request->file('working_process_images');
                $file = null;

                for ($i=0; $i < $checkImageCount; $i++) 
                {
                    $imageArr = array(
                        'working_process_id' => $processId,
                    );
                    
                    if (isset($uploadedFiles[$i]) && $uploadedFiles[$i]->isValid()) {
                        $file = $uploadedFiles[$i];
                    } else {
                        $file = null; // Or any default value you prefer
                    }

                    $path = 'public/uploads/working_process';
                    
                    if($file)
                    {
                        $filename = Str::random(9) .'_'. time() . '_' . $file->getClientOriginalName();
                        $file->move($path, $filename);
                        $imageArr['image_path'] = $path . '/' . $filename;
                    }

                    ServiceImage::create($imageArr);
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

            $processDataArr = WorkingProcess::with('processImages')->orderBy('id', 'desc')->first();
            
            return response()->json(['success' => true, 'message' => 'Working process details get successfully.', 'data' => $processDataArr], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => false, 'message' => $th, 'data' => array()], 200);
        }
    }

    public function delete(Request $request) 
    {
        try {
            $input = $request->all();
            
            $processIds = isset($input['working_process_id']) ? $input['working_process_id'] : '';

            if($processIds)
            {
                $getService = ServiceImage::where('id', $input['working_process_id'])->first();

                if ($getService->image_path) {
                    $proFilePath = $getService->image_path;
                    $proPath = substr(strstr($proFilePath, 'public/'), strlen('public/'));

                    if (file_exists(public_path($proPath))) {
                        \File::delete(public_path($proPath));
                    }
                }

                ServiceImage::where('id', $input['working_process_id'])->delete();

                return response()->json(['success' => true, 'message' => 'Image deleted successfully.', 'data' => array()], 200);
            }
            else
            {
                return response()->json(['error' => false, 'message' => 'Invalid portfolio id', 'data' => array()], 200);
            }
        } catch (\Throwable $th) {
            return response()->json(['error' => false, 'message' => $th, 'data' => array()], 200);
        }
    }
}