<?php

namespace App\Services;

use App\Models\Land;
use Illuminate\Support\Facades\DB;


class LandService
{
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
