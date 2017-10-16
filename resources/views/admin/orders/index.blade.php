@extends('admin/main')

@section('admin-content')
  <h3>All Orders</h3>
  <table class="table">
    <thead>
      <tr>
        <th>Customer Name</th>
        <th>Email</th>
        <th>Order Number</th>
        <th>Wine</th>
        <th>Delete</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($orders as $order)
      <tr>
        <td>{{$order->customer->name}}</td>
        <td>{{$order->customer->email}}</td>
        <td>{{$order->id}}</td>
        <td>
          <a href="{{route('wine.showOne', ['id' => $order->wine_id])}}">
            {{$order->wine_id}}  <!--Wine name here-->
          </a>
        </td>
        <td>
          <a class="btn-sm btn-danger" href="{{route('order.delete', ['id' => $order->id])}}">
            Delete
          </a>
        </td>
      </tr>
    @endforeach
    </tbody>
  </table>
@endsection
