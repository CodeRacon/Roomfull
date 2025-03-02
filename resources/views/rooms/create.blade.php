@extends('layouts.app')

@section('title', 'Add New Room')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
  <h1>Add New Room</h1>
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
    <form action="{{ route('rooms.store') }}" method="POST">
      @csrf
      <div class="mb-3">
        <label for="name" class="form-label">Name</label>
        <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
      </div>

      <div class="mb-3">
        <label for="type" class="form-label">Type</label>
        <select class="form-select" id="type" name="type" required>
          <option value="">Select type...</option>
          <option value="meeting" {{ old('type') == 'meeting' ? 'selected' : '' }}>Meeting Room</option>
          <option value="office" {{ old('type') == 'office' ? 'selected' : '' }}>Office</option>
          <option value="booth" {{ old('type') == 'booth' ? 'selected' : '' }}>Booth</option>
          <option value="open_world" {{ old('type') == 'open_world' ? 'selected' : '' }}>Open World</option>
        </select>
      </div>

      <div class="mb-3">
        <label for="capacity" class="form-label">Capacity (people)</label>
        <input type="number" class="form-control" id="capacity" name="capacity" value="{{ old('capacity') }}" min="1" required>
      </div>

      <div class="mb-3">
        <label for="min_duration" class="form-label">Minimum Duration (minutes)</label>
        <input type="number" class="form-control" id="min_duration" name="min_duration" value="{{ old('min_duration', 60) }}" min="15" step="15" required>
      </div>

      <div class="mb-3">
        <label for="price_per_hour" class="form-label">Price per Hour ($)</label>
        <input type="number" class="form-control" id="price_per_hour" name="price_per_hour" value="{{ old('price_per_hour') }}" min="0" step="0.01" required>
      </div>

      <div class="mb-3">
        <label for="price_per_day" class="form-label">Price per Day ($)</label>
        <input type="number" class="form-control" id="price_per_day" name="price_per_day" value="{{ old('price_per_day') }}" min="0" step="0.01">
        <small class="text-muted">Optional: Spezialpreis für Ganztagsbuchungen</small>
      </div>

      <div class="mb-3">
        <label for="price_per_week" class="form-label">Price per Week ($)</label>
        <input type="number" class="form-control" id="price_per_week" name="price_per_week" value="{{ old('price_per_week') }}" min="0" step="0.01">
        <small class="text-muted">Optional: Spezialpreis für Wochenbuchungen</small>
      </div>

      <div class="mb-3">
        <label for="discount_percentage" class="form-label">Discount Percentage (%)</label>
        <input type="number" class="form-control" id="discount_percentage" name="discount_percentage" value="{{ old('discount_percentage') }}" min="0" max="100" step="0.01">
        <small class="text-muted">Optional: Rabatt in Prozent</small>
      </div>


      <div class="mb-3">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }}>
          <label class="form-check-label" for="is_active">
            Active
          </label>
        </div>
      </div>

      <button type="submit" class="btn btn-primary">Create Room</button>
    </form>
  </div>
</div>
@endsection