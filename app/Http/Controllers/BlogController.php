<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\ServiceImage;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BlogController extends Controller
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
                'blog_title' => 'required|string',
                'slug' => 'required|string'
            );

            $validator = Validator::make($input, $rules);

            if ($validator->fails()) {

                return redirect()->route('blog')->withErrors($validator)->withInput();

            } else {

                $dataArr = array(
                    'blog_title' => $input['blog_title'] ? $input['blog_title'] : '',
                    'blog_slug' => $input['slug'] ? $input['slug'] : '',
                    'blog_content' => $input['blog_content'] ? $input['blog_content'] : '',
                    'blog_category' => $input['blog_category'] ? $input['blog_category'] : '',
                    'blog_tag' => $input['blog_tag'] ? $input['blog_tag'] : '',
                    'seo_meta_title' => $input['seo_meta_title'] ? $input['seo_meta_title'] : '',
                    'seo_meta_description' => $input['seo_meta_description'] ? $input['seo_meta_description'] : '',
                    'seo_meta_keyword' => $input['seo_meta_keyword'] ? $input['seo_meta_keyword'] : ''
                );

                if($blogImg = $request->file('blog_image'))
                {
                    $checkImg = Blog::where('id', $input['blog_id'])->first();
                    if ($checkImg) {
                        $proFilePath = $checkImg->blog_image;
                        $proPath = substr(strstr($proFilePath, 'public/'), strlen('public/'));

                        if (file_exists(public_path($proPath))) {
                            \File::delete(public_path($proPath));
                        }
                    }

                    $path1 = 'public/uploads/blog';
                    $filename1 = Str::random(9) .'_'. time() . '_img_' . $blogImg->getClientOriginalName();
                    $blogImg->move($path1, $filename1);
                    $dataArr['blog_image'] = $path1 . '/' . $filename1;
                }

                $blogId = '';
                if($input['blog_id'])
                {
                    $checkTitle = Blog::where('blog_title', $input['blog_title'])->where('id', '!=', $input['blog_id'])->first();
                    if($checkTitle)
                    {
                        return response()->json(['error' => false, 'message' => 'Blog title already exist.', 'data' => array()], 200);
                    }

                    Blog::where('id', $input['blog_id'])->update($dataArr);
                    $blogId = $input['blog_id'];

                    $message = 'Blog details update successfully.';
                }
                else
                {
                    $checkTitle = Blog::where('blog_title', $input['blog_title'])->first();
                    if($checkTitle)
                    {
                        return response()->json(['error' => false, 'message' => 'Blog title already exist.', 'data' => array()], 200);
                    }

                    $lastAbout = Blog::create($dataArr);
                    $blogId = $lastAbout->id;

                    $message = 'Blog details added successfully.';
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
            
            $blogDataArr = [];
            if(isset($input['search_blog']) && $input['search_blog'] != '')
            {
                $blogDataArr = Blog::where('blog_title', 'like', '%' . $input['search_blog'] . '%')
                                    ->orderBy('id', 'desc')
                                    ->paginate(env('LIST_PER_PAGE'));
            }
            else
            {
                $blogDataArr = Blog::orderBy('id', 'desc')->paginate(env('LIST_PER_PAGE'));
            }

            // $postData = array( 'search_blog' => '' );
            // $result = $this->sharedService->postOrGetApiData(config('constants.SERVICE_LIST'), $postData);
            
            return view('admin/list/blog', compact('blogDataArr'))->with('i', ($request->input('page', 1) - 1) * env('LIST_PER_PAGE'));
        } catch (\Throwable $th) {
            return response()->json(['error' => false, 'message' => $th, 'data' => array()], 200);
        }
    }

    public function delete(Request $request) 
    {
        try {
            $input = $request->all();
            
            $blogIds = isset($input['blog_id']) ? $input['blog_id'] : '';

            if($blogIds)
            {
                $getBlog = Blog::where('id', $input['blog_id'])->first();
                
                if ($getBlog) {
                    $proFilePath = $getBlog->blog_image;
                    $proPath = substr(strstr($proFilePath, 'public/'), strlen('public/'));

                    if (file_exists(public_path($proPath))) {
                        \File::delete(public_path($proPath));
                    }
                }

                Blog::where('id', $input['blog_id'])->delete();

                return response()->json(['success' => true, 'message' => 'Blog deleted successfully.', 'data' => array()], 200);
            }
            else
            {
                return response()->json(['error' => false, 'message' => 'Invalid blog id', 'data' => array()], 200);
            }
        } catch (\Throwable $th) {
            return response()->json(['error' => false, 'message' => $th, 'data' => array()], 200);
        }
    }
}