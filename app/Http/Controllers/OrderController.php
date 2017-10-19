<?php

namespace App\Http\Controllers;
use Gloudemans\Shoppingcart\Cart;
use App\Order;
use App\Customer;
use App\Wine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;

class OrderController extends Controller
{
    // Show all orders (admin)
    public function index()
    {
      $orders = Order::paginate(5);

      return view('admin.orders.index')->with('orders', $orders);
    }

    // Show all orders (customer)
    public function showMyOrders()
    {
      // Collect customer from tables
      $id = Auth::guard('customer')->user()->id;
      $customer = Customer::findOrFail($id);

      // Find orders made by that customer
      $orders = $customer->orders()->with('customer')->latest()->paginate(5);

      // Show results
      return view('customers.orders.index')->with('orders', $orders);
    }

    // Show all orders
    public function checkoutIndex()
    {
      $id = 2;
      // Find wines based on order id
      $order = Order::findOrFail($id);

      // Get total cost
      $total = $order->wines()->sum('price');

      // Show page
      return view('store.checkout.index')->with('order', $order)->with('total', $total);
    }

    // Create an order
    public function create()
    {

    }

    // Save an order
    public function store(Request $request, $id)
    {
      $product = Wine::find($id);

      // dd($product->id);
      // $items = Cart::add($product->id);

      dd($items);
      $user_id = Auth::guard('customer')->user()->id;
      // $items = (new Cart)->add($id);
    }

    // Delete an order
    public function delete($id)
    {
      // Search database for id
      $order = Order::findOrFail($id);

      // Delete database entry
      $order->delete();

      // Display a message
      Session::flash('success', 'You have deleted the order!');

      // redirect to all countries page
      return redirect()->route('order.show');
    }

    // Process an Order
    public function purchase()
    {

      // Validate & Get all stuff ready for sending to database
      $this->validate(request(), [
        'customer_id' => 'required',
        'order_id' => 'required',
      ]);

      // Send data to database table
      $order = Order::create([
        'customer_id' => request()->customer_id,
        'order_id' => request()->order_id,
      ]);

      // Payment type
      $payment = request()->payment;

      // Get total cost
      $total = request()->total;

      // Onscreen message
      Session::flash('success', 'Order has been processed');
      // Show purchase complete page
      return view('store.checkout.complete')->with('order', $order)->with('payment', $payment)->with('total', $total);
    }
}
