<?php

namespace App\Http\Controllers;

use App\Models\DedicatedExperts;
use App\Models\ServiceImage;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Validator;
use App\Services\SharedService;

class DedicatedExpertController extends Controller
{
    protected $sharedService;

    public function __construct(SharedService $sharedService)
    {
        $this->sharedService = $sharedService;
    }
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
                'expert_title' => 'required|string',
                'expert_description' => 'required|string'
            );

            $validator = Validator::make($input, $rules);

            if ($validator->fails()) {
                return redirect()->route('home')->withErrors($validator)->withInput();
            } else {

                // Remove image with title
                $expertIds = isset($input['remove_expert_service']) ? explode(',', $input['remove_expert_service']) : [];

                if(count($expertIds) > 0)
                {
                    foreach ($expertIds as $value) 
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
                $imageIds = isset($input['remove_expert_image']) ? explode(',', $input['remove_expert_image']) : [];

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
                    'title' => $input['expert_title'] ? $input['expert_title'] : '',
                    'description' => $input['expert_description'] ? $input['expert_description'] : ''
                );

                $expertId = '';
                if(isset($input['expert_id']) && $input['expert_id'] != '')
                {
                    $checkTitle = DedicatedExperts::where('title', $input['expert_title'])->where('id', '!=', $input['expert_id'])->first();
                    if($checkTitle)
                    {
                        return response()->json(['error' => false, 'message' => 'Title already exist.', 'data' => array()], 200);
                    }

                    DedicatedExperts::where('id', $input['expert_id'])->update($dataArr);
                    $expertId = $input['expert_id'];

                    $message = 'Expert update successfully.';
                }
                else
                {
                    $checkTitle = DedicatedExperts::where('title', $input['expert_title'])->first();
                    if($checkTitle)
                    {
                        return response()->json(['error' => false, 'message' => 'Title already exist.', 'data' => array()], 200);
                    }

                    $lastDedicatedExperts = DedicatedExperts::create($dataArr);
                    $expertId = $lastDedicatedExperts->id;

                    $message = 'Expert added successfully.';
                }

                $checkImageCount = isset($input['expert_image_title']) ? count($input['expert_image_title']) : 0;

                $uploadedFiles = $request->file('expert_image');
                $file = null;

                for ($i=0; $i < $checkImageCount; $i++) 
                {
                    $imageArr = array(
                        'expert_id' => $expertId,
                        'title' => $input['expert_image_title'][$i] ? $input['expert_image_title'][$i] : ''
                    );
                    
                    if (isset($uploadedFiles[$i]) && $uploadedFiles[$i]->isValid()) {
                        $file = $uploadedFiles[$i];
                    } else {
                        $file = null; // Or any default value you prefer
                    }
                    
                    $path = 'public/uploads/expert';

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

    public function show(Request $request)
    {
        try {
            $input = $request->all();
            
            $expertDataArr = [];
            if(isset($input['search_expert']) && $input['search_expert'] != '')
            {
                $expertDataArr = DedicatedExperts::with('expertImages')
                                    ->where('title', 'like', '%' . $input['search_expert'] . '%')
                                    ->orWhereHas('expertImages', function ($subquery) use ($input) {
                                        $subquery->where('title', 'like', '%' . $input['search_expert'] . '%');
                                    })
                                    ->orderBy('id', 'desc')
                                    ->paginate(env('LIST_PER_PAGE'));
            }
            else
            {
                $expertDataArr = DedicatedExperts::with('expertImages')->orderBy('id', 'desc')->paginate(env('LIST_PER_PAGE'));
            }

            // $postData = array( 'search_expert' => '' );
            // $result = $this->sharedService->postOrGetApiData(config('constants.SERVICE_LIST'), $postData);
            
            return view('admin/list/dedicated_expert', compact('expertDataArr'))->with('i', ($request->input('page', 1) - 1) * env('LIST_PER_PAGE'));
        } catch (\Throwable $th) {
            return response()->json(['error' => false, 'message' => $th, 'data' => array()], 200);
        }
    }

    public function delete(Request $request) 
    {
        try {
            $input = $request->all();
            
            $expertIds = isset($input['expert_id']) ? $input['expert_id'] : '';

            if($expertIds)
            {
                $getExpert = DedicatedExperts::with('expertImages')->where('id', $input['expert_id'])->first();
                
                foreach ($getExpert->expertImages as $values) 
                {
                    if ($values) {
                        $proFilePath = $values->image_path;
                        $proPath = substr(strstr($proFilePath, 'public/'), strlen('public/'));

                        if (file_exists(public_path($proPath))) {
                            \File::delete(public_path($proPath));
                        }
                    }
                }

                DedicatedExperts::where('id', $input['expert_id'])->delete();

                return response()->json(['success' => true, 'message' => 'Expert deleted successfully.', 'data' => array()], 200);
            }
            else
            {
                return response()->json(['error' => false, 'message' => 'Invalid expert id', 'data' => array()], 200);
            }
        } catch (\Throwable $th) {
            return response()->json(['error' => false, 'message' => $th, 'data' => array()], 200);
        }
    }
}
