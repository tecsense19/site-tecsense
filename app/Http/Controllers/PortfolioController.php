<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use App\Models\ServiceImage;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Validator;
use App\Services\SharedService;

class PortfolioController extends Controller
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

            $checkImageCount = count($request->file('portfolio_images'));

            $uploadedFiles = $request->file('portfolio_images');
            $file = null;

            for ($i=0; $i < $checkImageCount; $i++) 
            {
                $imageArr = array();
                
                if (isset($uploadedFiles[$i]) && $uploadedFiles[$i]->isValid()) {
                    $file = $uploadedFiles[$i];
                } else {
                    $file = null; // Or any default value you prefer
                }
                
                $path = 'public/uploads/portfolio';

                if($file)
                {
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $file->move($path, $filename);
                    $imageArr['image_path'] = $path . '/' . $filename;

                    Portfolio::create($imageArr);
                }
            }

            return response()->json(['success' => true, 'message' => 'Protfolio added successfully.', 'data' => array()], 200);

        } catch (\Throwable $th) {
            return response()->json(['error' => false, 'message' => $th, 'data' => array()], 200);
        }
    }

    public function show(Request $request)
    {
        try {
            $input = $request->all();

            $portfolioDataArr = Portfolio::orderBy('id', 'desc')->paginate(env('LIST_PER_PAGE'));
            
            return view('admin/list/portfolio', compact('portfolioDataArr'))->with('i', ($request->input('page', 1) - 1) * env('LIST_PER_PAGE'));
        } catch (\Throwable $th) {
            return response()->json(['error' => false, 'message' => $th, 'data' => array()], 200);
        }
    }

    public function delete(Request $request) 
    {
        try {
            $input = $request->all();
            
            $portfolioIds = isset($input['portfolio_id']) ? $input['portfolio_id'] : '';

            if($portfolioIds)
            {
                $getService = Portfolio::where('id', $input['portfolio_id'])->first();

                if ($getService->image_path) {
                    $proFilePath = $getService->image_path;
                    $proPath = substr(strstr($proFilePath, 'public/'), strlen('public/'));

                    if (file_exists(public_path($proPath))) {
                        \File::delete(public_path($proPath));
                    }
                }

                Portfolio::where('id', $input['portfolio_id'])->delete();

                return response()->json(['success' => true, 'message' => 'Portfolio deleted successfully.', 'data' => array()], 200);
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