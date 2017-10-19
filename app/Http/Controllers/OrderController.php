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

    // Delete an order
    public function delete($id)
    {
      // Search database for id
      $order = Order::findOrFail($id);

      // Delete database entry
      $order->delete();

      // Delete pivot table entries
      $order->wines()->detach();

      // Display a message
      Session::flash('success', 'You have deleted the order!');

      // redirect to all countries page
      return redirect()->route('order.show');
    }

    // Process an Order
    // public function purchase()
    // {
    //   // Validate & Get all stuff ready for sending to database
    //   // $this->validate(request(), [
    //   //   'customer_id' => 'required',
    //   //   'order_id' => 'required',
    //   // ]);
    //   //
    //   // // Send data to database table
    //   // $order = Order::create([
    //   //   'customer_id' => request()->customer_id,
    //   //   'order_id' => request()->order_id,
    //   // ]);
    //
    //   // Payment type
    //   $payment = request()->payment;
    //
    //   // Get total cost
    //   $total = request()->total;
    //
    //   // Onscreen message
    //   Session::flash('success', 'Order has been processed');
    //   // Show purchase complete page
    //   return view('store.checkout.complete')->with('order', $order)->with('payment', $payment)->with('total', $total);
    // }
}
