<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BestResult;
use App\Models\ServiceImage;
use Illuminate\Support\Facades\Validator;

class BestResultDetailController extends Controller
{
    function best_result_detail_store(Request $request){
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
                $imageIds = isset($input['remove_result_detail_pic']) ? explode(',', $input['remove_result_detail_pic']) : [];
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
                $serviceIds = isset($input['remove_result_detail']) ? explode(',', $input['remove_result_detail']) : [];
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

                $serviceId = '';
                if(isset($input['best_result_detail_id']) && $input['best_result_detail_id'] != ''){
                    $checkTitle = BestResult::where('text', $input['text'])->where('id', '!=', $input['best_result_detail_id'])->first();
                    if($checkTitle)
                    {
                        return response()->json(['error' => false, 'message' => 'Best Result title already exist.', 'data' => array()], 200);
                    }
                    BestResult::where('id', $input['best_result_detail_id'])->update($dataArr);
                    $serviceId = $input['best_result_detail_id'];
                    $message = 'Best Result details update successfully.';
                }else{
                    $checkTitle = BestResult::where('text', $input['text'])->first();
                    if($checkTitle)
                    {
                        return response()->json(['error' => false, 'message' => 'Best Result title already exist.', 'data' => array()], 200);
                    }
                    $lastService = BestResult::create($dataArr);
                    $serviceId = $lastService->id;
                    $message = 'Best Result details added successfully.';
                }

                $checkImageCount = isset($input['image_title']) ? count($input['image_title']) : 0;
                $uploadedFiles = $request->file('service_image');
                $file = null;
                
                for ($i=0; $i < $checkImageCount; $i++){
                    $imageArr = array(
                        'best_result_detail_id' => $serviceId,
                        'title' => $input['image_title'][$i] ? $input['image_title'][$i] : '',
                        'step_title' => $input['step_title'][$i] ? $input['step_title'][$i] : ''
                    );

                    if (isset($uploadedFiles[$i]) && $uploadedFiles[$i]->isValid()) {
                        $file = $uploadedFiles[$i];
                    } else {
                        $file = null; // Or any default value you prefer
                    }
                    $path = 'public/uploads/bestresultdetail';

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
            if(isset($input['search_best_result_detail']) && $input['search_best_result_detail'] != '')
            {
                $serviceDataArr = BestResult::with('bestResultDetailImages')
                                    ->where('text', 'like', '%' . $input['search_best_result_detail'] . '%')
                                    ->orWhereHas('bestResultDetailImages', function ($subquery) use ($input) {
                                        $subquery->where('title', 'like', '%' . $input['search_best_result_detail'] . '%');
                                    })
                                    ->orWhereHas('serviceTitle', function ($subquery) use ($input) {
                                        $subquery->where('service_title', 'like', '%' . $input['search_best_result_detail'] . '%');
                                    })
                                    ->orderBy('id', 'desc')->get();
            }else{
                $serviceDataArr = BestResult::with('bestResultDetailImages','serviceTitle')->orderBy('best_results_details.id', 'desc')->get();
            }
            return view('admin/list/best-result-details', compact('serviceDataArr'));
        } catch (\Throwable $th) {
            return response()->json(['error' => false, 'message' => $th, 'data' => array()], 200);
        }
    }


    public function delete(Request $request) 
    {
        try {
            $input = $request->all();
            
            $serviceIds = isset($input['Best_result_id']) ? $input['Best_result_id'] : '';
            if($serviceIds)
            {
                $getService = BestResult::with('bestResultDetailImages')->where('id', $input['Best_result_id'])->first();
                
                foreach ($getService->bestResultDetailImages as $values) 
                {
                    if ($values) {
                        $proFilePath = $values->image_path;
                        $proPath = substr(strstr($proFilePath, 'public/'), strlen('public/'));

                        if (file_exists(public_path($proPath))) {
                            \File::delete(public_path($proPath));
                        }
                    }
                }

                BestResult::where('id', $input['Best_result_id'])->delete();

                return response()->json(['success' => true, 'message' => 'Best Result Details deleted successfully.', 'data' => array()], 200);
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
