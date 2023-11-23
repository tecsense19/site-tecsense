<?php

namespace App\Http\Controllers;

use App\Models\FooterMenu;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class FooterMenuController extends Controller
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
                'category_id' => 'required',
                'menu_title' => 'required|string'
            );

            $validator = Validator::make($input, $rules);

            if ($validator->fails()) {

                return redirect()->route('footer.menu')->withErrors($validator)->withInput();

            } else {

                $dataArr = array(
                    'category_id' => $input['category_id'] ? $input['category_id'] : '',
                    'menu_title' => $input['menu_title'] ? $input['menu_title'] : '',
                    'menu_link' => $input['menu_link'] ? $input['menu_link'] : ''
                );

                if($menuLogo = $request->file('menu_image'))
                {
                    $getLogo = FooterMenu::where('id', $input['menu_id'])->first();
                    if ($getLogo) {
                        $proFilePath = $getLogo->image_path;
                        $proPath = substr(strstr($proFilePath, 'public/'), strlen('public/'));

                        if (file_exists(public_path($proPath))) {
                            \File::delete(public_path($proPath));
                        }
                    }

                    $path1 = 'public/uploads/footer';
                    $filename1 = Str::random(10) .'_'. time() . '_' . $menuLogo->getClientOriginalName();
                    $menuLogo->move($path1, $filename1);
                    $dataArr['image_path'] = $path1 . '/' . $filename1;
                }

                $menuId = '';
                if($input['menu_id'])
                {
                    $checkTitle = FooterMenu::where('menu_title', $input['menu_title'])->where('id', '!=', $input['menu_id'])->first();
                    if($checkTitle)
                    {
                        return response()->json(['error' => false, 'message' => 'Menu title already exist.', 'data' => array()], 200);
                    }

                    FooterMenu::where('id', $input['menu_id'])->update($dataArr);
                    $menuId = $input['menu_id'];

                    $message = 'Menu update successfully.';
                }
                else
                {
                    $checkTitle = FooterMenu::where('menu_title', $input['menu_title'])->first();
                    if($checkTitle)
                    {
                        return response()->json(['error' => false, 'message' => 'Menu title already exist.', 'data' => array()], 200);
                    }

                    $lastAbout = FooterMenu::create($dataArr);
                    $menuId = $lastAbout->id;

                    $message = 'Menu added successfully.';
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

            $menuDataArr = [];
            if(isset($input['search_menu']) && $input['search_menu'] != '')
            {
                $menuDataArr = FooterMenu::with('menuCategory')
                                    ->where('menu_title', 'like', '%' . $input['search_menu'] . '%')
                                    ->orderBy('id', 'desc')
                                    ->paginate(env('LIST_PER_PAGE'));
            }
            else
            {
                $menuDataArr = FooterMenu::with('menuCategory')->orderBy('id', 'desc')->paginate(env('LIST_PER_PAGE'));
            }
            
            return view('admin/list/footer_menu', compact('menuDataArr'))->with('i', ($request->input('page', 1) - 1) * env('LIST_PER_PAGE'));
        } catch (\Throwable $th) {
            return response()->json(['error' => false, 'message' => $th, 'data' => array()], 200);
        }
    }

    public function delete(Request $request) 
    {
        try {
            $input = $request->all();
            
            $menuIds = isset($input['menu_id']) ? $input['menu_id'] : '';

            if($menuIds)
            {
                FooterMenu::where('id', $input['menu_id'])->delete();

                return response()->json(['success' => true, 'message' => 'Footer menu deleted successfully.', 'data' => array()], 200);
            }
            else
            {
                return response()->json(['error' => false, 'message' => 'Invalid menu id', 'data' => array()], 200);
            }
        } catch (\Throwable $th) {
            return response()->json(['error' => false, 'message' => $th, 'data' => array()], 200);
        }
    }
}