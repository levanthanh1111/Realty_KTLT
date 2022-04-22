<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Land;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Services\LandService;

class LandController extends Controller
{
    // thêm mảnh đất
    public function store(Request $request)
    {
            $landData = $request->input();

            $land = new Land;
            $land->location = $landData['location'];
            $land->area = $landData['area'];
            $land->price = $landData['price'];
            $land->land_disc = $landData['land_disc'];
            $land->document_id = $landData['document_id'];
            $land->owner_id = $landData['owner_id'];
            $land->save();
            return response()->json(['message' => 'add land success'], 201);
        }


    // sửa mảnh đất
    public function update(Request $request, $id)
    {
        $landData = $request->input();
        Land::where('land_id', $id)->update(['location'=>$landData['location'],'area'=>$landData['area'],
            'price'=>$landData['price'], 'land_disc'=>$landData['land_disc'],
            'owner_id'=>$landData['owner_id'],
            'status'=>$landData['status'], 'updated_at'=>$landData['updated_at']]);
        return response()->json(['message'=>'update land success'],202);
    }

    // xóa mảnh đất theo id
    public function destroy($id)
    {
        Land::where('land_id',$id)->update(['status'=>0]);
        return response()->json(['message'=> 'delete land success'],202);
    }
    // khôi phục mảnh đất theo id
    public function restoreLand($id)
    {
        Land::where('land_id',$id)->update(['status'=>1]);
        return response()->json(['message'=> 'restore land success'],202);
    }
    // lấy tất cả mảnh đất trên hệ thống
    Public function getAll() {
        $land = DB::table('land')->select('land_id','location','area','price','land_disc','owner_id')
            ->where('status',1)->get();
        return response()->json(["users"=>$land],200);
    }
    // lấy tất cả những mảnh đất đang được bán
    Public function getBy() {
        $land = DB::table('land')->select('land_id','location','area','price','land_disc','owner_id')
            ->where('status',1)->where('for_sale',1)->get();
        return response()->json(["users"=>$land],200);
    }
    // chuyển mảnh đất từ trạng thái không bán sang đang bán
    public function saleLand($id){
        Land::where('land_id',$id)->update(['for_sale'=>1]);
        return response()->json(['message'=> 'Land is selling '],202);
    }

    private $landService;
    public function __construct(LandService $landService)
    {
        $this->landService = $landService;
    }
    // tìm kiếm mảnh đất theo 4 thông tin trong hàm
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
