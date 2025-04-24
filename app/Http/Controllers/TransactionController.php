<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\orderDetails;
use App\Models\products;
use App\Models\Orders;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use RealRashid\SweetAlert\Facades\Alert;
use RealRashid\SweetAlert\Toaster;


class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = products::where('is_active', 1)->get()->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->product_name,
                'price' => $product->product_price,
                'image' => '',
                'option' => '',
            ];
        });

        return view('pos.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Categories::orderBy('id', 'asc')->get();
        return view('pos.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $qOrderCode = Orders::max('id');
        $qOrderCode++;
        $orderCode = "ORD" . date("YMD") . sprintf("%03d", $qOrderCode);
        $data = [
            'order_code' => $orderCode,
            'order_date' => date('Y-m-d'),
            'order_amount' => $request->amounttotal,
            'order_change' => 1,
            'order_status' => 1,
        ];

        $order = Orders::create($data);

        $qty = $request->qty;
        foreach ($qty as $key => $data) {
            orderDetails::create([
                'order_id' => $order->id,
                'product_id' => $request->product_id[$key],
                'qty' => $request->qty[$key],
                'order_price' => $request->order_price[$key],
                'order_subtotal' => $request->subtotal_input[$key],
            ]);
        }
        return redirect()->to('pos');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $order = Orders::findOrFail($id);
        $orderDetails = orderDetails::with('product')->where('order_id', $id)->get();
        $title = "Order Details of " . $order->order_code;
        return view('pos.show', compact('order', 'orderDetails', 'title'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view('#');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function getProduct($category_id)
    {
        $products = products::where('category_id', $category_id)->get();
        $response = [
            'status' => 'success',
            'message' => 'Fetch Product Success',
            'data' => $products
        ];
        return response()->json($response, 200);
        //alternative
        // return response()->json($products);
    }

    public function print($id)
    {
        $title = "Print Transaction";
        $order = Orders::findOrFail($id);
        $orderDetails = orderDetails::with('product')->where('order_id', $id)->get();
        return view('pos.print-receipt', compact('title', 'order', 'orderDetails'));
    }
}
