<?php

namespace App\Http\Controllers\API;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Mockery\Exception;

class UserController extends Controller
{
    // lấy thông tin cần thiết của tất cả user mà chưa bị xóa
    public function getList(){

        $users=DB::table('user')->select('user_id','name','address','phone_number','email')
            ->where('status',1)->get();
        return response()->json(["users"=>$users],200);

    }
    // lấy user theo id
    public function getUsers($id=null) {
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
    // thêm user
    public function addUsers(Request $request){
        if($request->isMethod('post')){
            $userData = $request->input();
            $rule =[
                "user_name"=>"required|regex:/^[\pL\s\-]+$/u|string|unique:user",
                "pass_word"=>"required",
                "name"=>"required",
                "address"=>"required",
                "phone_number"=>"required",
                "email"=>"required|email|unique:user",
           //     "status"=>"required",

            ];
            $customMessages =[
                'user_name.required'=>'Username is required',
                'user_name.unique'=>'Username already exists in database',
                'pass_word.required'=>'Password is required',
                'name.required'=>'Name is required',
                'address.required'=>'Address is required',
                'phone_number.required'=>'Phone number is required',
                'email.required'=>'Email is required',
                'email.email'=>'Valid Email is required',
                'email.unique'=>'Email already exists in database',
           //     'status.required'=>'Status is required'

            ];
            $validator = Validator::make ($userData,$rule,$customMessages);
            if($validator->fails()){
                return response()->json($validator->errors(),422);
            }
            $user=new User;
            $user->user_name = $userData['user_name'];
            $user->pass_word = bcrypt($userData['pass_word']);
            $user->name = $userData['name'];
            $user->address = $userData['address'];
            $user->phone_number = $userData['phone_number'];
            $user->email = $userData['email'];
        //    $user->status= $userData['status'];
            $user->save();
            return response()->json(['message'=>'add user success'],201);
        }
    }
    // sửa user
    public function updateUsers(Request $request,$id){
        if($request->isMethod('put')){
            $userData = $request->input();
            $rule =[
                "user_name"=>"required|regex:/^[\pL\s\-]+$/u|string|unique:user",
                "pass_word"=>"required",
                "name"=>"required",
                "address"=>"required",
                "phone_number"=>"required",
                "email"=>"required|email|unique:user",
                "status"=>"required",
                "update_at"=>"required"

            ];
            $customMessages =[
                'user_name.required'=>'Username is required',
                'user_name.unique'=>'Username already exists in database',
                'pass_word.required'=>'Password is required',
                'name.required'=>'Name is required',
                'address.required'=>'Address is required',
                'phone_number.required'=>'Phone number is required',
                'email.required'=>'Email is required',
                'email.email'=>'Valid Email is required',
                'email.unique'=>'Email already exists in database',
                'status.required'=>'Status is required',
                'update_at.required'=>'update is required'

            ];
            $validator = Validator::make ($userData,$rule,$customMessages);
            if($validator->fails()){
                return response()->json($validator->errors(),422);
            }
            User::where('user_id', $id)->update(['user_name'=>$userData['user_name'],
                'pass_word'=>bcrypt($userData['pass_word']),'name'=>$userData['name'],
                'address'=>$userData['address'],'phone_number'=>$userData['phone_number'],
                'email'=>$userData['email'], 'status'=>$userData['status'], 'update_at'=>$userData['update_at']]);
            return response()->json(['message'=>'update user success'],202);
        }
    }

    // xóa user
    public function deleteUsers($id){
        User::where('user_id',$id)->update(['status'=>0]);
        return response()->json(['message'=> 'delete user success'],202);
    }
    // khôi phục user đã xóa
    public function restoreUsers($id){
        User::where('user_id',$id)->update(['status'=>1]);
        return response()->json(['message'=> 'restoreaaa user success'],202);
    }
    // sắp xếp theo user tăng dần theo sở hữu đất của họ
    public function showUserSortedByLand(){
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
