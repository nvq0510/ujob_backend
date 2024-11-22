<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller; 
use App\Models\Address; 
use Illuminate\Http\Request; 

class AddressController extends Controller
{
    public function showByZipCode(Request $request)
    {
        $zipcode = (string) $request->route('zipcode'); 

        $address = Address::where('zipcode', $zipcode)
                          ->select('region_kanji', 'city_kanji', 'area_kanji')
                          ->first();

        if ($address) {
            return response()->json($address, 200);
        } else {
            return response()->json(['message' => 'Không tìm thấy địa chỉ với mã bưu điện này'], 404);
        }
    }
}