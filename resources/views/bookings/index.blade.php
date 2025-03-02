@extends('layouts.app')

@section('content')
<div class="container">
  <h1>Meine Buchungen</h1>

  <div class="mb-4">
    <a href="{{ route('bookings.create') }}" class="btn btn-primary">Neue Buchung</a>
  </div>

  @if(session('success'))
  <div class="alert alert-success">
    {{ session('success') }}
  </div>
  @endif

  @if(count($bookings) > 0)
  <div class="table-responsive">
    <table class="table table-striped">
      <thead>
        <tr>
          <th>Raum</th>
          <th>Startzeit</th>
          <th>Endzeit</th>
          <th>Status</th>
          <th>Preis</th>
          <th>Aktionen</th>
        </tr>
      </thead>
      <tbody>
        @foreach($bookings as $booking)
        <tr>
          <td>{{ $booking->room->name }}</td>
          <td>{{ $booking->start_time }}</td>
          <td>{{ $booking->end_time }}</td>
          <td>
            @if($booking->status == 'pending')
            <span class="badge bg-warning">Ausstehend</span>
            @elseif($booking->status == 'confirmed')
            <span class="badge bg-success">Bestätigt</span>
            @else
            <span class="badge bg-danger">Storniert</span>
            @endif
          </td>
          <td>{{ number_format($booking->price, 2) }} €</td>
          <td>
            <a href="{{ route('bookings.show', $booking) }}" class="btn btn-sm btn-info">Details</a>

            @if($booking->canBeCancelled())
            <form action="{{ route('bookings.cancel', $booking) }}" method="POST" class="d-inline">
              @csrf
              @method('PATCH')
              <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Möchtest du diese Buchung wirklich stornieren?')">Stornieren</button>
            </form>
            @endif
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  @else
  <div class="alert alert-info">
    Du hast noch keine Buchungen. <a href="{{ route('bookings.create') }}">Erstelle jetzt deine erste Buchung</a>.
  </div>
  @endif
</div>
@endsection