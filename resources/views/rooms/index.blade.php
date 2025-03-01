@extends('layouts.app')

@section('title', 'All Rooms')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
  <h1>All Rooms</h1>
  <a href="{{ route('rooms.create') }}" class="btn btn-primary">Add New Room</a>
</div>

<div class="row">
  @forelse($rooms as $room)
  <div class="col-md-4 mb-4">
    <div class="card position-relative">
      <a href="{{ route('rooms.show', $room) }}" class="position-absolute w-100 h-100 top-0 start-0" style="z-index: 1;"></a>

      <div class="card-body">
        <h5 class="card-title">{{ $room->name }}</h5>
        <h6 class="card-subtitle mb-2 text-muted">Type: {{ ucfirst($room->type) }}</h6>
        <p class="card-text">
          Capacity: {{ $room->capacity }} people<br>
          Price: ${{ $room->price_per_hour }}/hour<br>
          Minimum duration: {{ $room->min_duration }} minutes
        </p>

      </div>
    </div>
  </div>
  @empty
  <div class="col-12">
    <div class="alert alert-info">No rooms available yet.</div>
  </div>
  @endforelse
</div>
@endsection