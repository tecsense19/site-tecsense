<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use App\Models\ServiceImage;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Validator;

class TestimonialController extends Controller
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
                'full_name' => 'required|string',
                'country' => 'required|string',
                'client_description' => 'required|string'
            );

            $validator = Validator::make($input, $rules);

            if ($validator->fails()) {
                return redirect()->route('home')->withErrors($validator)->withInput();
            } else {

                $removeProfilePic = isset($input['remove_profile_pic']) ? $input['remove_profile_pic'] : '';
                if($removeProfilePic)
                {
                    $getProfile = Testimonial::where('id', $input['remove_profile_pic'])->first();

                    if ($getProfile) {
                        $proFilePath = $getProfile->profile_pic;
                        $proPath = substr(strstr($proFilePath, 'public/'), strlen('public/'));

                        if (file_exists(public_path($proPath))) {
                            \File::delete(public_path($proPath));
                        }
                    }

                    Testimonial::where('id', $input['remove_profile_pic'])->update(['profile_pic' => '']);
                }

                // Remove only image
                $imageIds = isset($input['remove_testimonial_image']) ? explode(',', $input['remove_testimonial_image']) : [];

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

                        ServiceImage::where('id', $value)->delete();
                    }
                }

                $dataArr = array(
                    'full_name' => $input['full_name'] ? $input['full_name'] : '',
                    'country' => $input['country'] ? $input['country'] : '',
                    'client_description' => $input['client_description'] ? $input['client_description'] : ''
                );

                if($profile = $request->file('profile_pic'))
                {
                    $path = 'public/uploads/testimonial';
                    $filename = 'profile_' . time() . '_' . $profile->getClientOriginalName();
                    $profile->move($path, $filename);
                    $dataArr['profile_pic'] = $path . '/' . $filename;
                }

                $testimonialId = '';
                if($input['testimonial_id'])
                {
                    Testimonial::where('id', $input['testimonial_id'])->update($dataArr);
                    $testimonialId = $input['testimonial_id'];

                    $message = 'Testimonial update successfully.';
                }
                else
                {
                    $lastTestimonial = Testimonial::create($dataArr);
                    $testimonialId = $lastTestimonial->id;

                    $message = 'Testimonial added successfully.';
                }

                $checkImageCount = 0;
                $tecImageFiles = $request->file('logos');

                if ($tecImageFiles && is_array($tecImageFiles)) {
                    $checkImageCount = count($tecImageFiles);
                } else {
                    $checkImageCount = 0;
                }

                $uploadedFiles = $request->file('logos');
                $file = null;

                for ($i=0; $i < $checkImageCount; $i++) 
                {
                    $imageArr = array(
                        'testimonial_id' => $testimonialId,
                        'title' => ''
                    );
                    
                    if (isset($uploadedFiles[$i]) && $uploadedFiles[$i]->isValid()) {
                        $file = $uploadedFiles[$i];
                    } else {
                        $file = null; // Or any default value you prefer
                    }

                    if($file)
                    {
                        $path = 'public/uploads/testimonial';
                        $filename = time() . '_' . $file->getClientOriginalName();
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

    public function show(Request $request)
    {
        try {
            $input = $request->all();
            
            $testimonialDataArr = [];
            if(isset($input['search_testimonial']) && $input['search_testimonial'] != '')
            {
                $testimonialDataArr = Testimonial::with('testimonialImages')
                                    ->where('full_name', 'like', '%' . $input['search_testimonial'] . '%')
                                    ->orderBy('id', 'desc')
                                    ->paginate(env('LIST_PER_PAGE'));
            }
            else
            {
                $testimonialDataArr = Testimonial::with('testimonialImages')->orderBy('id', 'desc')->paginate(env('LIST_PER_PAGE'));
            }
            
            return view('admin/list/testimonial', compact('testimonialDataArr'))->with('i', ($request->input('page', 1) - 1) * env('LIST_PER_PAGE'));
        } catch (\Throwable $th) {
            return response()->json(['error' => false, 'message' => $th, 'data' => array()], 200);
        }
    }

    public function delete(Request $request) 
    {
        // try {
            $input = $request->all();
            
            $testimonialIds = isset($input['testimonial_id']) ? $input['testimonial_id'] : '';

            if($testimonialIds)
            {
                $getTestimonial = Testimonial::with('testimonialImages')->where('id', $input['testimonial_id'])->first();

                if ($getTestimonial) {
                    $proFilePath = $getTestimonial->profile_pic;
                    $proPath = substr(strstr($proFilePath, 'public/'), strlen('public/'));

                    if (file_exists(public_path($proPath))) {
                        \File::delete(public_path($proPath));
                    }
                }

                foreach ($getTestimonial->testimonialImages as $values) 
                {
                    if ($values) {
                        $proFilePath = $values->image_path;
                        $proPath = substr(strstr($proFilePath, 'public/'), strlen('public/'));

                        if (file_exists(public_path($proPath))) {
                            \File::delete(public_path($proPath));
                        }
                    }
                }

                Testimonial::where('id', $input['testimonial_id'])->delete();

                return response()->json(['success' => true, 'message' => 'Testimonial deleted successfully.', 'data' => array()], 200);
            }
            else
            {
                return response()->json(['error' => false, 'message' => 'Invalid testimonial id', 'data' => array()], 200);
            }
        // } catch (\Throwable $th) {
        //     return response()->json(['error' => false, 'message' => $th, 'data' => array()], 200);
        // }
    }
}
