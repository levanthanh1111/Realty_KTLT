<?php

namespace App\Services;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;

class UserService
{   public function getUser()
    {
    $users = DB::table('user')->select('user_id', 'name', 'address', 'phone_number', 'email')
        ->where('status', 1)->get();
    return response()->json(["users" => $users], 200);
    }
    public  function getUserId($id){
        if(empty($id)){
            $users=DB::table('user')->select('user_id','name','address','phone_number','email')
                ->where('status',1)->where('user_id',$id)->get();
            return response()->json(["users"=>$users],200);
        }else{
            $users=DB::table('user')->select('user_id','name','address','phone_number','email')
                ->where('status',1)->where('user_id',$id)->get();
            return response()->json(["users"=>$users],200);
        }
    }
    public function SoertUser(){
        try{
            $userSorted = DB::select('SELECT user.user_id, user.name, COUNT(land.owner_id) as Land_ownership FROM user,
                                            (SELECT land.owner_id  FROM land where land.status = 1) as land
                                            WHERE user.user_id = land.owner_id and user.status = 1
                                            GROUP BY user.user_id, user.name
                                            ORDER BY COUNT(land.owner_id)') ;
        }catch (MySQLDuplicateKeyException $e) {
            return $e->getMessage();
        }catch (Exception $e) {
            return $e->getMessage();
        }
        return response()->json([$userSorted]);
    }
}
