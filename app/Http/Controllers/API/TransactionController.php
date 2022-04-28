<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use App\Models\Transaction;

class TransactionController extends Controller
{
    private $transactionService;
    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }
    //lấy hợp đồng
    public function index()
    {
       return $this->transactionService->getTransaction();
    }
    //thêm hợp đồng, khi thêm hợp đồng thì mảnh đất sẽ chuyển từ trang thái đang bán sang không bán
    public function store(Request $request){
        $trData = $request->input();
        return $this->transactionService->addTracsaction($trData);
    }
    // xóa hợp đồng
    public function destroy($id)
    {
        Transaction::where('transaction_id',$id)->update(['status'=>0]);
        return response()->json(['message'=> 'delete transaction success'],202);
    }
    // khôi phục hợp đồng
    public function restoreTransaction($id)
    {
        Transaction::where('transaction_id',$id)->update(['status'=>1]);
        return response()->json(['message'=> 'restore transaction success'],202);
    }

}
