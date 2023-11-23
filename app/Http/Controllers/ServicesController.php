<?php

namespace App\Http\Controllers;

use App\Models\OurService;
use App\Models\ServiceImage;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Validator;
use App\Services\SharedService;

class ServicesController extends Controller
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
                'service_title' => 'required|string',
                'service_description' => 'required|string'
            );

            $validator = Validator::make($input, $rules);

            if ($validator->fails()) {
                return redirect()->route('home')->withErrors($validator)->withInput();
            } else {

                // Remove image with title
                $serviceIds = isset($input['remove_service']) ? explode(',', $input['remove_service']) : [];

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

                // Remove only image
                $imageIds = isset($input['remove_image']) ? explode(',', $input['remove_image']) : [];

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
                    'service_title' => $input['service_title'] ? $input['service_title'] : '',
                    'service_description' => $input['service_description'] ? $input['service_description'] : ''
                );

                if($bLogo = $request->file('service_black_logo'))
                {
                    $getLogo = OurService::where('id', $input['service_id'])->first();
                    if ($getLogo) {
                        $proFilePath = $getLogo->service_black_logo;
                        $proPath = substr(strstr($proFilePath, 'public/'), strlen('public/'));

                        if (file_exists(public_path($proPath))) {
                            \File::delete(public_path($proPath));
                        }
                    }

                    $path1 = 'public/uploads/services/logo';
                    $filename1 = time() . '_' . $bLogo->getClientOriginalName();
                    $bLogo->move($path1, $filename1);
                    $dataArr['service_black_logo'] = $path1 . '/' . $filename1;
                }

                if($wLogo = $request->file('service_white_logo'))
                {
                    $getLogo = OurService::where('id', $input['service_id'])->first();
                    if ($getLogo) {
                        $proFilePath = $getLogo->service_white_logo;
                        $proPath = substr(strstr($proFilePath, 'public/'), strlen('public/'));

                        if (file_exists(public_path($proPath))) {
                            \File::delete(public_path($proPath));
                        }
                    }

                    $path1 = 'public/uploads/services/logo';
                    $filename1 = time() . '_' . $wLogo->getClientOriginalName();
                    $wLogo->move($path1, $filename1);
                    $dataArr['service_white_logo'] = $path1 . '/' . $filename1;
                }

                $serviceId = '';
                if(isset($input['service_id']) && $input['service_id'] != '')
                {
                    $checkTitle = OurService::where('service_title', $input['service_title'])->where('id', '!=', $input['service_id'])->first();
                    if($checkTitle)
                    {
                        return response()->json(['error' => false, 'message' => 'Service title already exist.', 'data' => array()], 200);
                    }

                    OurService::where('id', $input['service_id'])->update($dataArr);
                    $serviceId = $input['service_id'];

                    $message = 'Service update successfully.';
                }
                else
                {
                    $checkTitle = OurService::where('service_title', $input['service_title'])->first();
                    if($checkTitle)
                    {
                        return response()->json(['error' => false, 'message' => 'Service title already exist.', 'data' => array()], 200);
                    }

                    $lastService = OurService::create($dataArr);
                    $serviceId = $lastService->id;

                    $message = 'Service added successfully.';
                }

                $checkImageCount = isset($input['image_title']) ? count($input['image_title']) : 0;

                $uploadedFiles = $request->file('service_image');
                $file = null;

                for ($i=0; $i < $checkImageCount; $i++) 
                {
                    $imageArr = array(
                        'service_id' => $serviceId,
                        'title' => $input['image_title'][$i] ? $input['image_title'][$i] : ''
                    );
                    
                    if (isset($uploadedFiles[$i]) && $uploadedFiles[$i]->isValid()) {
                        $file = $uploadedFiles[$i];
                    } else {
                        $file = null; // Or any default value you prefer
                    }
                    
                    $path = 'public/uploads/services';

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
            
            $serviceDataArr = [];
            if(isset($input['search_service']) && $input['search_service'] != '')
            {
                $serviceDataArr = OurService::with('serviceImages')
                                    ->where('service_title', 'like', '%' . $input['search_service'] . '%')
                                    ->orWhereHas('serviceImages', function ($subquery) use ($input) {
                                        $subquery->where('title', 'like', '%' . $input['search_service'] . '%');
                                    })
                                    ->orderBy('id', 'desc')
                                    ->paginate(env('LIST_PER_PAGE'));
            }
            else
            {
                $serviceDataArr = OurService::with('serviceImages')->orderBy('id', 'desc')->paginate(env('LIST_PER_PAGE'));
            }

            // $postData = array( 'search_service' => '' );
            // $result = $this->sharedService->postOrGetApiData(config('constants.SERVICE_LIST'), $postData);
            
            return view('admin/list/service', compact('serviceDataArr'))->with('i', ($request->input('page', 1) - 1) * env('LIST_PER_PAGE'));
        } catch (\Throwable $th) {
            return response()->json(['error' => false, 'message' => $th, 'data' => array()], 200);
        }
    }

    public function delete(Request $request) 
    {
        try {
            $input = $request->all();
            
            $serviceIds = isset($input['service_id']) ? $input['service_id'] : '';

            if($serviceIds)
            {
                $getService = OurService::with('serviceImages')->where('id', $input['service_id'])->first();
                
                foreach ($getService->serviceImages as $values) 
                {
                    if ($values) {
                        $proFilePath = $values->image_path;
                        $proPath = substr(strstr($proFilePath, 'public/'), strlen('public/'));

                        if (file_exists(public_path($proPath))) {
                            \File::delete(public_path($proPath));
                        }
                    }
                }

                OurService::where('id', $input['service_id'])->delete();

                return response()->json(['success' => true, 'message' => 'Service deleted successfully.', 'data' => array()], 200);
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
