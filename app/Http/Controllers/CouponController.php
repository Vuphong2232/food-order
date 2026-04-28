<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CouponController extends Controller
{
    public function index()
    {
        return view('admin.coupons');
    }

    public function getCoupons()
    {
        $coupons = Coupon::orderBy('created_at', 'desc')->get();
        return response()->json(['data' => $coupons]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|unique:coupons,code',
            'discount_percent' => 'required|numeric|min:1|max:100',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        Coupon::create([
            'code' => strtoupper($request->code),
            'discount_percent' => $request->discount_percent,
            'is_active' => $request->is_active ?? 1,
        ]);

        return response()->json(['message' => 'Thêm mã giảm giá thành công']);
    }

    public function update(Request $request, Coupon $coupon)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|unique:coupons,code,' . $coupon->id,
            'discount_percent' => 'required|numeric|min:1|max:100',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        $coupon->update([
            'code' => strtoupper($request->code),
            'discount_percent' => $request->discount_percent,
            'is_active' => $request->is_active ?? 0,
        ]);

        return response()->json(['message' => 'Cập nhật mã giảm giá thành công']);
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return response()->json(['message' => 'Đã xóa mã giảm giá']);
    }
}