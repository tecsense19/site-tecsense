<?php

namespace App\Http\Controllers;

use App\Models\WhyChoose;
use App\Models\ServiceImage;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Validator;
use App\Services\SharedService;

class WhyChooseController extends Controller
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
                'why_choose_title' => 'required|string',
                'why_choose_description' => 'required|string'
            );

            $validator = Validator::make($input, $rules);

            if ($validator->fails()) {
                return redirect()->route('home')->withErrors($validator)->withInput();
            } else {

                // Remove image with title
                $whyChooseIds = isset($input['remove_why_choose_service']) ? explode(',', $input['remove_why_choose_service']) : [];

                if(count($whyChooseIds) > 0)
                {
                    foreach ($whyChooseIds as $value) 
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
                $imageIds = isset($input['remove_why_choose_image']) ? explode(',', $input['remove_why_choose_image']) : [];

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
                    'title' => $input['why_choose_title'] ? $input['why_choose_title'] : '',
                    'description' => $input['why_choose_description'] ? $input['why_choose_description'] : ''
                );

                $whyChooseId = '';
                if(isset($input['why_choose_id']) && $input['why_choose_id'] != '')
                {
                    $checkTitle = WhyChoose::where('title', $input['why_choose_title'])->where('id', '!=', $input['why_choose_id'])->first();
                    if($checkTitle)
                    {
                        return response()->json(['error' => false, 'message' => 'Title already exist.', 'data' => array()], 200);
                    }

                    WhyChoose::where('id', $input['why_choose_id'])->update($dataArr);
                    $whyChooseId = $input['why_choose_id'];

                    $message = 'Why choose update successfully.';
                }
                else
                {
                    $checkTitle = WhyChoose::where('title', $input['why_choose_title'])->first();
                    if($checkTitle)
                    {
                        return response()->json(['error' => false, 'message' => 'Title already exist.', 'data' => array()], 200);
                    }

                    $lastWhyChoose = WhyChoose::create($dataArr);
                    $whyChooseId = $lastWhyChoose->id;

                    $message = 'Why choose added successfully.';
                }

                $checkImageCount = isset($input['why_choose_image_title']) ? count($input['why_choose_image_title']) : 0;

                $uploadedFiles = $request->file('why_choose_image');
                $file = null;

                for ($i=0; $i < $checkImageCount; $i++) 
                {
                    $imageArr = array(
                        'why_choose_id' => $whyChooseId,
                        'title' => $input['why_choose_image_title'][$i] ? $input['why_choose_image_title'][$i] : '',
                        'description' => $input['why_choose_image_description'][$i] ? $input['why_choose_image_description'][$i] : ''
                    );
                    
                    if (isset($uploadedFiles[$i]) && $uploadedFiles[$i]->isValid()) {
                        $file = $uploadedFiles[$i];
                    } else {
                        $file = null; // Or any default value you prefer
                    }
                    
                    $path = 'public/uploads/whychoose';

                    if(isset($input['why_choose_image_id'][$i]) && $input['why_choose_image_id'][$i] != '')
                    {
                        if($file)
                        {
                            $getImage = ServiceImage::where('id', $input['why_choose_image_id'][$i])->first();
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

                        ServiceImage::where('id', $input['why_choose_image_id'][$i])->update($imageArr);
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
            
            $whyChooseDataArr = [];
            if(isset($input['search_why_choose']) && $input['search_why_choose'] != '')
            {
                $whyChooseDataArr = WhyChoose::with('whyChooseImages')
                                    ->where('title', 'like', '%' . $input['search_why_choose'] . '%')
                                    ->orWhereHas('whyChooseImages', function ($subquery) use ($input) {
                                        $subquery->where('title', 'like', '%' . $input['search_why_choose'] . '%');
                                    })
                                    ->orderBy('id', 'desc')
                                    ->paginate(env('LIST_PER_PAGE'));
            }
            else
            {
                $whyChooseDataArr = WhyChoose::with('whyChooseImages')->orderBy('id', 'desc')->paginate(env('LIST_PER_PAGE'));
            }

            // $postData = array( 'search_why_choose' => '' );
            // $result = $this->sharedService->postOrGetApiData(config('constants.SERVICE_LIST'), $postData);
            
            return view('admin/list/why_choose', compact('whyChooseDataArr'))->with('i', ($request->input('page', 1) - 1) * env('LIST_PER_PAGE'));
        } catch (\Throwable $th) {
            return response()->json(['error' => false, 'message' => $th, 'data' => array()], 200);
        }
    }

    public function delete(Request $request) 
    {
        try {
            $input = $request->all();
            
            $whyChooseIds = isset($input['why_choose_id']) ? $input['why_choose_id'] : '';

            if($whyChooseIds)
            {
                $getWhyChoose = WhyChoose::with('whyChooseImages')->where('id', $input['why_choose_id'])->first();
                
                foreach ($getWhyChoose->whyChooseImages as $values) 
                {
                    if ($values) {
                        $proFilePath = $values->image_path;
                        $proPath = substr(strstr($proFilePath, 'public/'), strlen('public/'));

                        if (file_exists(public_path($proPath))) {
                            \File::delete(public_path($proPath));
                        }
                    }
                }

                WhyChoose::where('id', $input['why_choose_id'])->delete();

                return response()->json(['success' => true, 'message' => 'Why choose deleted successfully.', 'data' => array()], 200);
            }
            else
            {
                return response()->json(['error' => false, 'message' => 'Invalid why choose id', 'data' => array()], 200);
            }
        } catch (\Throwable $th) {
            return response()->json(['error' => false, 'message' => $th, 'data' => array()], 200);
        }
    }
}
