<?php

namespace App\Http\Controllers\API\V1;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Models\FooterCategory;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Http\JsonResponse;

class FooterController extends BaseController
{
    public function menuList(Request $request)
    {
        try {
            $input = $request->all();

            $footerDataArr = FooterCategory::with('footerMenus')->orderBy('id', 'asc')->get()->toArray();

            $responseData = array_map(function ($item) {
                $item['footer_menus'] = array_map(function ($teamImage) {
                    $teamImage['image_path'] = $teamImage['image_path'] ? config('app.url') . '/' . $teamImage['image_path'] : ''; // Replace with the desired URL or path
                    return $teamImage;
                }, $item['footer_menus']);           

                return $item;

            }, $footerDataArr);

            return $this->sendResponse($responseData, 'Footer menu list get successfully.');

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
}