<?php

namespace App\Http\Controllers\API\V1;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Models\Blog;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Http\JsonResponse;

class BlogController extends BaseController
{
    public function blogList(Request $request)
    {
        try {
            $input = $request->all();

            $blogDataArr = [];
            if(isset($input['search_blog']) && $input['search_blog'] != '')
            {
                $blogDataArr = Blog::where('blog_title', 'like', '%' . $input['search_blog'] . '%')
                                    ->orderBy('id', 'desc')
                                    ->get()->toArray();
            }
            else
            {
                $blogDataArr = Blog::orderBy('id', 'desc')->get()->toArray();
            }

            $responseData = array_map(function ($item) {
                $item['blog_image'] = $item['blog_image'] ? config('app.url') . '/' . $item['blog_image'] : '';
                return $item;
            }, $blogDataArr);

            return $this->sendResponse($responseData, 'About list get successfully.');

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
}