<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Land;
use Illuminate\Http\Request;
use App\Services\LandService;

class LandController extends Controller
{
    private $landService;
    public function __construct(LandService $landService)
    {
        $this->landService = $landService;
    }
    // thêm mảnh đất
    public function store(Request $request)
    {
        $landData = $request->input();
        return $this->landService->createLand($landData);
    }
    // sửa mảnh đất
    public function update(Request $request,int $id)
    {
        $landData = $request->input();

        Land::where('land_id', $id)->update(['location'=>$landData['location'],'area'=>$landData['area'],
            'price'=>$landData['price'], 'land_disc'=>$landData['land_disc'],
            'owner_id'=>$landData['owner_id'],'for_sale'=>$landData['for_sale'],
            'updated_at'=>$landData['updated_at']]);
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
         return $this->landService->getAll();
    }
    // lấy tất cả những mảnh đất đang được bán
    Public function getBy() {
        return $this->landService->getSale();
    }
    // chuyển mảnh đất từ trạng thái không bán sang đang bán
    public function saleLand($id){
        Land::where('land_id',$id)->update(['for_sale'=>1]);
        return response()->json(['message'=> 'Land is selling '],202);
    }
    // tìm kiếm mảnh đất theo 4 thông tin trong hàm
    public function search(Request $request){
        try {
            if ($data = $request->get('location')) {
                return $this->landService->searchLocation($data);
            }
            if ($data = $request->get('land_disc')){
                return $this->landService->searchLandDisc($data);
            }
            if ($data = $request->get('area')){
                return $this->landService->searchArea($data);
            }
            if ($data = $request->get('price')){
                return $this->landService->searchPrice($data);
            }
        } catch (\Exception $e){
            return [
                'message' => $e->getMessage()
            ];
        }
    }
}
