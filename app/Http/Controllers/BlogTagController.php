<?php

namespace App\Http\Controllers;

use App\Models\BlogTag;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BlogTagController extends Controller
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
                'title' => 'required|string'
            );

            $validator = Validator::make($input, $rules);

            if ($validator->fails()) {

                return redirect()->route('blog.tag')->withErrors($validator)->withInput();

            } else {

                $dataArr = array(
                    'title' => $input['title'] ? $input['title'] : ''
                );

                $categoryId = '';
                if($input['tag_id'])
                {
                    $checkTitle = BlogTag::where('title', $input['title'])->where('id', '!=', $input['tag_id'])->first();
                    if($checkTitle)
                    {
                        return response()->json(['error' => false, 'message' => 'Tag name already exist.', 'data' => array()], 200);
                    }

                    BlogTag::where('id', $input['tag_id'])->update($dataArr);
                    $categoryId = $input['tag_id'];

                    $message = 'Tag update successfully.';
                }
                else
                {
                    $checkTitle = BlogTag::where('title', $input['title'])->first();
                    if($checkTitle)
                    {
                        return response()->json(['error' => false, 'message' => 'Tag name already exist.', 'data' => array()], 200);
                    }

                    $lastAbout = BlogTag::create($dataArr);
                    $categoryId = $lastAbout->id;

                    $message = 'Tag added successfully.';
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

            $tagDataArr = [];
            if(isset($input['search_tag']) && $input['search_tag'] != '')
            {
                $tagDataArr = BlogTag::where('title', 'like', '%' . $input['search_tag'] . '%')
                                    ->orderBy('id', 'desc')
                                    ->paginate(env('LIST_PER_PAGE'));
            }
            else
            {
                $tagDataArr = BlogTag::orderBy('id', 'desc')->paginate(env('LIST_PER_PAGE'));
            }
            
            return view('admin/list/blog_tag', compact('tagDataArr'))->with('i', ($request->input('page', 1) - 1) * env('LIST_PER_PAGE'));
        } catch (\Throwable $th) {
            return response()->json(['error' => false, 'message' => $th, 'data' => array()], 200);
        }
    }

    public function delete(Request $request) 
    {
        try {
            $input = $request->all();
            
            $tagIds = isset($input['tag_id']) ? $input['tag_id'] : '';

            if($tagIds)
            {
                BlogTag::where('id', $input['tag_id'])->delete();

                return response()->json(['success' => true, 'message' => 'Tag deleted successfully.', 'data' => array()], 200);
            }
            else
            {
                return response()->json(['error' => false, 'message' => 'Invalid tag id', 'data' => array()], 200);
            }
        } catch (\Throwable $th) {
            return response()->json(['error' => false, 'message' => $th, 'data' => array()], 200);
        }
    }
}