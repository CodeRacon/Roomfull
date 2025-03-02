@extends('layouts.app')

@section('content')
<div class="container">
  <div class="mb-4">
    <a href="{{ route('bookings.index') }}" class="btn btn-outline-secondary">← Zurück zu meinen Buchungen</a>
  </div>

  <div class="card">
    <div class="card-header">
      <h2>Neue Buchung erstellen</h2>
    </div>
    <div class="card-body">
      @if(session('error'))
      <div class="alert alert-danger">
        {{ session('error') }}
      </div>
      @endif

      <form method="POST" action="{{ route('bookings.store') }}" id="booking-form">
        @csrf

        <div class="mb-3">
          <label for="room_id" class="form-label">Raum auswählen</label>
          <select id="room_id" name="room_id" class="form-control @error('room_id') is-invalid @enderror" required>
            <option value="">-- Raum wählen --</option>
            @foreach($rooms as $availableRoom)
            <option value="{{ $availableRoom->id }}"
              {{ (old('room_id') == $availableRoom->id || (isset($room) && $room->id == $availableRoom->id)) ? 'selected' : '' }}
              data-min-duration="{{ $availableRoom->min_duration }}"
              data-price-hour="{{ $availableRoom->price_per_hour }}"
              data-price-day="{{ $availableRoom->price_per_day }}"
              data-price-week="{{ $availableRoom->price_per_week }}"
              data-discount="{{ $availableRoom->discount_percentage }}">
              {{ $availableRoom->name }} ({{ $availableRoom->type }}) - {{ number_format($availableRoom->price_per_hour, 2) }}€/Stunde
            </option>
            @endforeach
          </select>
          @error('room_id')
          <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="mb-3">
              <label for="start_time" class="form-label">Startzeit</label>
              <input type="datetime-local" id="start_time" name="start_time" class="form-control @error('start_time') is-invalid @enderror"
                value="{{ old('start_time') }}" required>
              @error('start_time')
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>
          <div class="col-md-6">
            <div class="mb-3">
              <label for="end_time" class="form-label">Endzeit</label>
              <input type="datetime-local" id="end_time" name="end_time" class="form-control @error('end_time') is-invalid @enderror"
                value="{{ old('end_time') }}" required>
              @error('end_time')
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>
        </div>

        <div class="mb-3">
          <label for="notes" class="form-label">Notizen</label>
          <textarea id="notes" name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3">{{ old('notes') }}</textarea>
          @error('notes')
          <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div id="availability-info" class="alert d-none mb-3"></div>

        <div id="price-preview" class="mb-3 d-none">
          <h4>Preisvorschau</h4>
          <div class="card">
            <div class="card-body">
              <p class="mb-0"><strong>Gesamtpreis:</strong> <span id="total-price">0.00</span> €</p>
            </div>
          </div>
        </div>

        <div class="mb-3">
          <button type="button" id="check-availability" class="btn btn-outline-primary">Verfügbarkeit prüfen</button>
          <button type="submit" id="submit-booking" class="btn btn-primary d-none">Buchung erstellen</button>
        </div>
      </form>
    </div>
  </div>
</div>

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('booking-form');
    const roomSelect = document.getElementById('room_id');
    const startTimeInput = document.getElementById('start_time');
    const endTimeInput = document.getElementById('end_time');
    const checkButton = document.getElementById('check-availability');
    const submitButton = document.getElementById('submit-booking');
    const availabilityInfo = document.getElementById('availability-info');
    const pricePreview = document.getElementById('price-preview');
    const totalPriceDisplay = document.getElementById('total-price');

    // Verfügbarkeit prüfen
    checkButton.addEventListener('click', function() {
      const roomId = roomSelect.value;
      const startTime = startTimeInput.value;
      const endTime = endTimeInput.value;

      if (!roomId || !startTime || !endTime) {
        availabilityInfo.textContent = 'Bitte fülle alle Felder aus.';
        availabilityInfo.classList.remove('d-none', 'alert-success', 'alert-danger');
        availabilityInfo.classList.add('alert-warning');
        return;
      }

      // AJAX-Anfrage für Verfügbarkeitsprüfung
      fetch('{{ route("rooms.check-availability") }}', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          body: JSON.stringify({
            room_id: roomId,
            start_time: startTime,
            end_time: endTime
          })
        })
        .then(response => response.json())
        .then(data => {
          availabilityInfo.classList.remove('d-none');

          if (data.available) {
            availabilityInfo.textContent = 'Der Raum ist zu diesem Zeitraum verfügbar!';
            availabilityInfo.classList.remove('alert-danger', 'alert-warning');
            availabilityInfo.classList.add('alert-success');

            submitButton.classList.remove('d-none');

            // Preisvorschau anzeigen
            pricePreview.classList.remove('d-none');
            totalPriceDisplay.textContent = data.price.toFixed(2);
          } else {
            availabilityInfo.textContent = 'Der Raum ist zu diesem Zeitraum leider nicht verfügbar.';
            availabilityInfo.classList.remove('alert-success', 'alert-warning');
            availabilityInfo.classList.add('alert-danger');

            submitButton.classList.add('d-none');
            pricePreview.classList.add('d-none');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          availabilityInfo.textContent = 'Es ist ein Fehler aufgetreten. Bitte versuche es erneut.';
          availabilityInfo.classList.remove('d-none', 'alert-success');
          availabilityInfo.classList.add('alert-danger');
        });
    });
  });
</script>
@endpush
@endsection