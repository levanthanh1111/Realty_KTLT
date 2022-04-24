<?php

namespace App\Services;

use App\Models\Land;
use Illuminate\Support\Facades\DB;


class LandService
{
    public function createLand($landData){
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
    public function getAll(){
        $land = DB::select('SELECT land.land_id,land.location,land.area,land.price,land.land_disc,user.name as owner
                                FROM land,user
                                WHERE land.owner_id = user.user_id and land.status = 1'
        );
        return response()->json(["users"=>$land],200);
    }
    public function getSale(){
        $land = DB::select('SELECT land.land_id,land.location,land.area,land.price,land.land_disc,user.name as owner
                                FROM land,user
                                WHERE land.owner_id = user.user_id and land.status = 1 and land.for_sale = 1'
        );
        return response()->json(["users"=>$land],200);
    }
    // tìm kiếm đất theo location
    public function searchLocation($data){
        $result = DB::table('land')->select('land_id','location','area','price','land_disc','owner_id')
            ->where('location','LIKE','%'.$data.'%')->get();
        return $result;
    }
    //tìm kiếm đất theo land_disc
    public function searchLandDisc($data){
        $result = DB::table('land')->select('land_id','location','area','price','land_disc','owner_id')
            ->where('land_disc','LIKE','%'.$data.'%')->get();
        return $result;
    }
    //tìm kiếm đất theo diện tích
    public function searchArea($data){
        $result = DB::table('land')->select('land_id','location','area','price','land_disc','owner_id')
            ->where('area','=',''.$data.'')->get();
        return $result;
    }
    //tìm kiếm đất theo giá
    public function searchPrice($data){
        $result = DB::table('land')->select('land_id','location','area','price','land_disc','owner_id')
            ->where('price','=',''.$data.'')->get();
        return $result;
    }
}
