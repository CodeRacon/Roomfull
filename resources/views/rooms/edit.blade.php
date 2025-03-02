@extends('layouts.app')

@section('title', 'Edit ' . $room->name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
  <h1>Edit Room: {{ $room->name }}</h1>
  <a href="{{ route('rooms.index') }}" class="btn btn-secondary">Back to List</a>
</div>

@if($errors->any())
<div class="alert alert-danger">
  <ul class="mb-0">
    @foreach($errors->all() as $error)
    <li>{{ $error }}</li>
    @endforeach
  </ul>
</div>
@endif

<div class="card">
  <div class="card-body">
    <form action="{{ route('rooms.update', $room) }}" method="POST">
      @csrf
      @method('PUT')

      <div class="mb-3">
        <label for="name" class="form-label">Name</label>
        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $room->name) }}" required>
      </div>

      <div class="mb-3">
        <label for="type" class="form-label">Type</label>
        <select class="form-select" id="type" name="type" required>
          <option value="">Select type...</option>
          <option value="meeting" {{ old('type', $room->type) == 'meeting' ? 'selected' : '' }}>Meeting Room</option>
          <option value="office" {{ old('type', $room->type) == 'office' ? 'selected' : '' }}>Office</option>
          <option value="booth" {{ old('type', $room->type) == 'booth' ? 'selected' : '' }}>Booth</option>
          <option value="open_world" {{ old('type', $room->type) == 'open_world' ? 'selected' : '' }}>Open World</option>
        </select>
      </div>

      <div class="mb-3">
        <label for="capacity" class="form-label">Capacity (people)</label>
        <input type="number" class="form-control" id="capacity" name="capacity" value="{{ old('capacity', $room->capacity) }}" min="1" required>
      </div>

      <div class="mb-3">
        <label for="min_duration" class="form-label">Minimum Duration (minutes)</label>
        <input type="number" class="form-control" id="min_duration" name="min_duration" value="{{ old('min_duration', $room->min_duration) }}" min="15" step="15" required>
      </div>

      <div class="mb-3">
        <label for="price_per_hour" class="form-label">Price per Hour ($)</label>
        <input type="number" class="form-control" id="price_per_hour" name="price_per_hour" value="{{ old('price_per_hour', $room->price_per_hour) }}" min="0" step="0.01" required>
      </div>

      <div class="mb-3">
        <label for="price_per_day" class="form-label">Price per Day ($)</label>
        <input type="number" class="form-control" id="price_per_day" name="price_per_day" value="{{ old('price_per_day', $room->price_per_day) }}" min="0" step="0.01">
        <small class="text-muted">Optional: Spezialpreis für Ganztagsbuchungen</small>
      </div>

      <div class="mb-3">
        <label for="price_per_week" class="form-label">Price per Week ($)</label>
        <input type="number" class="form-control" id="price_per_week" name="price_per_week" value="{{ old('price_per_week', $room->price_per_week) }}" min="0" step="0.01">
        <small class="text-muted">Optional: Spezialpreis für Wochenbuchungen</small>
      </div>

      <div class="mb-3">
        <label for="discount_percentage" class="form-label">Discount Percentage (%)</label>
        <input type="number" class="form-control" id="discount_percentage" name="discount_percentage" value="{{ old('discount_percentage', $room->discount_percentage) }}" min="0" max="100" step="0.01">
        <small class="text-muted">Optional: Rabatt in Prozent (0-100%)</small>
      </div>


      <div class="mb-3">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $room->is_active) ? 'checked' : '' }}>
          <label class="form-check-label" for="is_active">
            Active
          </label>
        </div>
      </div>

      <button type="submit" class="btn btn-primary">Update Room</button>
    </form>
  </div>
</div>
@endsection