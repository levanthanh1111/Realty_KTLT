<?php

namespace App\Services;
use App\Models\Land;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;

class TransactionService
{
    public function getTransaction(){
        try{
            $in4Land = "SELECT land.land_id, land.location, land.area, land.price , land.land_disc,
                            user.name as owner_name, user.address as owner_address, user.phone_number as owner_phone,
                            user.email as owner_email
                        FROM land, user
                        WHERE land.owner_id = user.user_id";
            $in4Trans = "SELECT transaction.status ,transaction.transaction_id ,transaction.land_id,
                                transaction.commision_money as money
                        FROM transaction";
            $data=DB::select('SELECT trans.transaction_id, land.location, land.area, land.land_disc,
                                        trans.money as last_peice, land.owner_name as owner_new,
                                        land.owner_address, land.owner_phone, land.owner_email
                            FROM ('.$in4Land.')as land, ('.$in4Trans.') as trans
                            WHERE land.land_id = trans.land_id and trans.status = 1' );
        }catch (MySQLDuplicateKeyException $e) {
            return $e->getMessage();
        }catch (Exception $e) {
            return $e->getMessage();
        }
        return response()->json(["transactions"=>$data],200);
    }
    public function addTracsaction($trData){
        $tr = new Transaction;
        $tr->buyer_id = $trData['buyer_id'];
        $tr->land_id = $trData['land_id'];
        $tr->commision_money = $trData['commision_money'];
        $temp = $tr->buyer_id;
        $tr->save();
        Land::where('land_id', $tr->land_id)->update(['for_sale' => 0]);
        Land::where('land_id', $tr->land_id)->update(['owner_id'=>$temp]);
        return response()->json(['message' => 'add Transaction success'], 201);
    }

}
