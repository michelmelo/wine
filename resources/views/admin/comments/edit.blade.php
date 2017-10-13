@extends('admin/main')

@section('admin-content')
  <div class="panel panel-default">
    <div class="panel-heading">
      Edit Comment
    </div>
    <div class="panel-body">
      <form action="{{route('comment.update', ['comment' => $comment->id])}}" method="post">
        {{ csrf_field() }}
        <input type="hidden" name="wine_id" value="{{$comment->wine_id}}">
        <div class="form-group">
          <label for="customer_id">Name</label>
          <input type="text" class="form-control" name="customer_id" value="{{$comment->customer->name}}"> <!-- This is still needed -->
        </div>

        <div class="form-group">
          <label for="title">Title</label>
          <input type="text" class="form-control" name="title" value="{{$comment->title}}">
        </div>

        <div class="form-group">
          <label for="body">Your words?</label>
          <textarea class="form-control" name="body" rows="2" cols="50">{{$comment->body}}</textarea>
        </div>

        <div class="form-group">
          <label for="grade">Grade</label>
          @php
            $prevGrade = $comment->grade;
            for ($i=1; $i < 6; $i++) {
              if ($i == $prevGrade) {
                echo '<label class="checkbox-inline">';
                echo '<input name="grade" type="radio" checked="checked" value="';
                echo $i;
                echo '">' . ' ' . $i . ' ';
                echo '</label>';
              } else {
                echo '<label class="checkbox-inline">';
                echo '<input name="grade" type="radio" value="';
                echo $i;
                echo '">' . ' ' . $i . ' ';
                echo '</label>';
              }
            }
          @endphp

        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
      </form>
    </div>
  </div>
@endsection