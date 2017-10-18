@extends('customers/main')

@section('content')
  <div class="row">
    <div class="col-sm-6 text-center">
      <img style="width: 100%; height: auto; margin-top: 10px;" src="{{URL($wine->image_url)}}" alt="">
      <div class="panel panel-default">
        <div class="panel-body">
          <ul style="list-style: none; margin-top: 20px;">
            <li>
              Average Rating:
              @php
              echo round($rating);
              @endphp
            </li>
            <li>&nbsp<!--Empty Row--></li>
            <li>
              <a href="/" class="btn btn-outline-info">
                Back to Store
              </a>
              <a href="{{route('order.store', ['id' => $wine->id])}}" class="btn btn-outline-success">
                Add to Cart
              </a>
            </li>
          </ul>
        </div>
      </div>
    </div>
    <div class="col-sm-6">
      <ul class="list-group">
        <li class="list-group-item">
          Name:
          <h3>{{ $wine->name }}</h3>
        </li>
        <li class="list-group-item">
          <p>Description:<br />
            {{ $wine->description }}
          </p>
        </li>
        <li class="list-group-item">Price: {{ $wine->price }}</li>
        <li class="list-group-item">Country: {{ $wine->country->name }}</li>
        <li class="list-group-item">Status:
          @php
            $stock = $wine->stock;
            if ($stock > 0) {
              echo 'In Stock';
            } else {
              echo 'Unavailable at this time';
            }
          @endphp
        </li>
        <li class="list-group-item">
          Contributed by: {{$wine->customer->name}}
        </li>
      </ul>
    </div>
  </div>
  <hr />

  <div class="row">
    <div class="col-sm-6">
      <h3>Comments</h3>
      @foreach ($comments as $comment)
          <ul style="list-style: none;padding:0;">
            <li><strong>{{$comment->title}}</strong></li>
            <li>{{$comment->body}}</li>
            <li>Written by {{$comment->customer->name}}</li>
            <li>Rating: {{$comment->rating}}</li>
              @php
                $time = strtotime($comment->created_at);
                $newTime = date("m/d/y, G:i", $time);
                echo '<p>' . $newTime . '</p>';
              @endphp
            </li>
          </ul>
          <hr />
      @endforeach
      {{$comments->links()}}
    </div>
    <div class="col-sm-6">
      @auth ('customer')
        <h3>Leave a comment…</h3>
        <form action="{{route('comment.store')}}" method="post">
          {{ csrf_field() }}
          <input type="hidden" name="wine_id" value="{{$wine->id}}">
          <input type="hidden" name="customer_id" value="{{Auth::guard('customer')->user()->id}}">
          <div class="form-group">
            <label for="customer_id">Name</label>
            <h5>{{Auth::guard('customer')->user()->name}}</h5>  <!--Need logged in user id to fill in name-->
          </div>

          <div class="form-group">
            <label for="title">Title</label>
            <input type="text" class="form-control" name="title">
          </div>

          <div class="form-group">
            <label for="body">Your words?</label>
            <textarea class="form-control" name="body" rows="2" cols="50"></textarea>
          </div>

          <div class="form-group">
            <label for="rating">Rating</label>

            @php
            for ($i=1; $i < 6; $i++) {
              echo '<label class="checkbox-inline">';
              echo '<input name="rating" type="radio" value="';
              echo $i;
              echo '">' . ' ' . $i . ' ';
              echo '</label>';
            }
            @endphp

          </div>
          <button type="submit" class="btn btn-primary">Submit</button>
        </form>

      @endauth
    </div>
  </div>
@endsection
