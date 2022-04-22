<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Transaction;
use App\Models\Land;
use App\Models\User;

class TransactionController extends Controller
{
    //lấy hợp đồng
    public function index()
    {
        $data= DB::table('transaction')
            ->select('owner_id','buyer_id','land_id','commision_money')
            ->where('status',1)->get();
        return response()->json(["transactions"=>$data],200);
    }
    //thêm hợp đồng, khi thêm hợp đồng thì mảnh đất sẽ chuyển từ trang thái đang bán sang không bán
    public function store(Request $request){
        $trData = $request->input();

        $tr = new Transaction;
        $tr->owner_id = $trData['owner_id'];
        $tr->buyer_id = $trData['buyer_id'];
        $tr->land_id = $trData['land_id'];
        $tr->commision_money = $trData['commision_money'];
        $temp = $tr->buyer_id;
        $tr->save();
        Land::where('land_id', $tr->land_id)->update(['for_sale' => 0]);
        Land::where('land_id', $tr->land_id)->update(['owner_id'=>$temp]);
        return response()->json(['message' => 'add Transaction success'], 201);

    }
    // xóa hợp đồng
    public function destroy($id1,$id2,$id3)
    {
        Transaction::where('owner_id',$id1)->where('buyer_id',$id2)->where('land_id',$id3)->update(['status'=>0]);
        return response()->json(['message'=> 'delete transaction success'],202);
    }
    // khôi phục hợp đồng
    public function restoreTransaction($id1,$id2,$id3)
    {
        Transaction::where('owner_id',$id1)->where('buyer_id',$id2)->where('land_id',$id3)->update(['status'=>1]);
        return response()->json(['message'=> 'restore transaction success'],202);
    }
}
