<?php

namespace App\Http\Controllers;

use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BlogCategoryController extends Controller
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

                return redirect()->route('blog.category')->withErrors($validator)->withInput();

            } else {

                $dataArr = array(
                    'title' => $input['title'] ? $input['title'] : ''
                );

                $categoryId = '';
                if($input['category_id'])
                {
                    $checkTitle = BlogCategory::where('title', $input['title'])->where('id', '!=', $input['category_id'])->first();
                    if($checkTitle)
                    {
                        return response()->json(['error' => false, 'message' => 'Category title already exist.', 'data' => array()], 200);
                    }

                    BlogCategory::where('id', $input['category_id'])->update($dataArr);
                    $categoryId = $input['category_id'];

                    $message = 'Blog category update successfully.';
                }
                else
                {
                    $checkTitle = BlogCategory::where('title', $input['title'])->first();
                    if($checkTitle)
                    {
                        return response()->json(['error' => false, 'message' => 'Category title already exist.', 'data' => array()], 200);
                    }

                    $lastAbout = BlogCategory::create($dataArr);
                    $categoryId = $lastAbout->id;

                    $message = 'Blog category added successfully.';
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

            $categoryDataArr = [];
            if(isset($input['search_blog_category']) && $input['search_blog_category'] != '')
            {
                $categoryDataArr = BlogCategory::where('title', 'like', '%' . $input['search_blog_category'] . '%')
                                    ->orderBy('id', 'desc')
                                    ->paginate(env('LIST_PER_PAGE'));
            }
            else
            {
                $categoryDataArr = BlogCategory::orderBy('id', 'desc')->paginate(env('LIST_PER_PAGE'));
            }
            
            return view('admin/list/blog_category', compact('categoryDataArr'))->with('i', ($request->input('page', 1) - 1) * env('LIST_PER_PAGE'));
        } catch (\Throwable $th) {
            return response()->json(['error' => false, 'message' => $th, 'data' => array()], 200);
        }
    }

    public function delete(Request $request) 
    {
        try {
            $input = $request->all();
            
            $categoryIds = isset($input['category_id']) ? $input['category_id'] : '';

            if($categoryIds)
            {
                BlogCategory::where('id', $input['category_id'])->delete();

                return response()->json(['success' => true, 'message' => 'Blog category deleted successfully.', 'data' => array()], 200);
            }
            else
            {
                return response()->json(['error' => false, 'message' => 'Invalid category id', 'data' => array()], 200);
            }
        } catch (\Throwable $th) {
            return response()->json(['error' => false, 'message' => $th, 'data' => array()], 200);
        }
    }
}