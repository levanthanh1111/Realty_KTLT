<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Land;
use App\Services\LandService;
use phpDocumentor\Reflection\DocBlock\Tags\Return_;
use phpDocumentor\Reflection\Location;


class ApiController extends Controller
{
    private $landService;
    public function __construct(LandService $landService)
    {
        $this->landService = $landService;
    }
    public function getBy(Request $request)
    {
        try {
            $limit = $request->get('limit') ?? $limit=2;
            $orderBys =[];
            if ($request->get('column') && $request->get('sort')) {
                $orderBys['column'] = $request->get('column');
                $orderBys['sort'] = $request->get('sort');
            }
            $landPaginate = $this->landService->getAll($orderBys, $limit);
            return [
                'land'   => $landPaginate->items(),
                'meta'   => [
                    'total'  => $landPaginate->total(),
                    'perPage' => $landPaginate->perPage(),
                    'currentPage' => $landPaginate->currentPage()
                ]
            ];
        } catch (\Exception $e) {
            return [
               'message'=> $e->getMessage()
            ];
        }

    }
    public function search(Request $request){
        try {
            if ($data = $request->get('location')) {
                $locationSearch = $this->landService->searchLocation($data);
                return $locationSearch;
            }
            if ($data = $request->get('land_disc')){
                $landDiscSearch = $this->landService->searchLandDisc($data);
                return $landDiscSearch;
            }
            if ($data = $request->get('area')){
                $landArea = $this->landService->searchArea($data);
                return $landArea;
            }
            if ($data = $request->get('price')){
                $landPrice = $this->landService->searchPrice($data);
                return $landPrice;
            }
        } catch (\Exception $e){
            return [
                'message' => $e->getMessage()
            ];
        }
    }
}
