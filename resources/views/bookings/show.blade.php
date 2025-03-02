@extends('layouts.app')

@section('content')
<div class="container">
  <div class="mb-4">
    <a href="{{ route('bookings.index') }}" class="btn btn-outline-secondary">← Zurück zu meinen Buchungen</a>
  </div>

  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h2>Buchungsdetails</h2>
      <div>
        @if($booking->canBeCancelled())
        <form action="{{ route('bookings.cancel', $booking) }}" method="POST" class="d-inline">
          @csrf
          @method('PATCH')
          <button type="submit" class="btn btn-danger" onclick="return confirm('Möchtest du diese Buchung wirklich stornieren?')">Buchung stornieren</button>
        </form>
        @endif
      </div>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-6">
          <h4>Rauminformationen</h4>
          <table class="table">
            <tr>
              <th>Name:</th>
              <td>{{ $booking->room->name }}</td>
            </tr>
            <tr>
              <th>Typ:</th>
              <td>{{ $booking->room->type }}</td>
            </tr>
            <tr>
              <th>Kapazität:</th>
              <td>{{ $booking->room->capacity }} Personen</td>
            </tr>
          </table>
        </div>
        <div class="col-md-6">
          <h4>Buchungsdetails</h4>
          <table class="table">
            <tr>
              <th>Status:</th>
              <td>
                @if($booking->status == 'pending')
                <span class="badge bg-warning">Ausstehend</span>
                @elseif($booking->status == 'confirmed')
                <span class="badge bg-success">Bestätigt</span>
                @else
                <span class="badge bg-danger">Storniert</span>
                @endif
              </td>
            </tr>
            <tr>
              <th>Startzeit:</th>
              <td>{{ \Carbon\Carbon::parse($booking->start_time)->format('d.m.Y H:i') }}</td>
            </tr>
            <tr>
              <th>Endzeit:</th>
              <td>{{ \Carbon\Carbon::parse($booking->end_time)->format('d.m.Y H:i') }}</td>
            </tr>
            <tr>
              <th>Dauer:</th>
              <td>{{ $booking->getDurationInHours() }} Stunden</td>
            </tr>
            <tr>
              <th>Preis:</th>
              <td>{{ number_format($booking->price, 2) }} €</td>
            </tr>
          </table>
        </div>
      </div>

      @if($booking->notes)
      <div class="mt-4">
        <h4>Notizen</h4>
        <div class="card">
          <div class="card-body">
            {{ $booking->notes }}
          </div>
        </div>
      </div>
      @endif

      <div class="mt-4">
        <h4>Zeitplan</h4>
        <div class="card">
          <div class="card-body">
            <p>Gebucht am: {{ \Carbon\Carbon::parse($booking->created_at)->format('d.m.Y H:i') }}</p>
            <p>Letzte Änderung: {{ \Carbon\Carbon::parse($booking->updated_at)->format('d.m.Y H:i') }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection