@extends('layouts.app')

@section('title', $room->name)

@section('content')
<div class="mb-4">
  <a href="{{ route('rooms.index') }}" class="btn btn-secondary">&laquo; Back to All Rooms</a>
</div>

<div class="card mb-4">
  <div class="card-body">
    <h1 class="card-title">{{ $room->name }}</h1>
    <h6 class="card-subtitle mb-3 text-muted">Type: {{ ucfirst($room->type) }}</h6>

    <div class="row mb-3">
      <div class="col-md-4">
        <strong>Capacity:</strong> {{ $room->capacity }} people
      </div>
      <div class="col-md-4">
        <strong>Price:</strong> ${{ $room->price_per_hour }}/hour
      </div>
      <div class="col-md-4">
        <strong>Minimum Duration:</strong> {{ $room->min_duration }} minutes
      </div>
    </div>

    <div class="mb-4">
      <a href="{{ route('rooms.edit', $room) }}" class="btn btn-warning">Edit Room</a>
    </div>
  </div>
</div>

<!-- Danger Zone (Collapsible) -->
<div class="accordion mt-4" id="dangerZoneAccordion">
  <div class="accordion-item border-danger">
    <h2 class="accordion-header" id="dangerZoneHeading">
      <button class="accordion-button collapsed text-danger fs-6 custom-danger-button" type="button" data-bs-toggle="collapse" data-bs-target="#dangerZoneCollapse" aria-expanded="false" aria-controls="dangerZoneCollapse">
        Danger Zone
      </button>
    </h2>
    <div id="dangerZoneCollapse" class="accordion-collapse collapse" aria-labelledby="dangerZoneHeading" data-bs-parent="#dangerZoneAccordion">
      <div class="accordion-body">
        <p class="text-danger small">Actions in this area can't be undone. Be careful.</p>

        <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteRoomModal">
          Delete Room
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteRoomModal" tabindex="-1" aria-labelledby="deleteRoomModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="deleteRoomModalLabel">Confirm Room Deletion</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="mb-3">This action <strong>cannot</strong> be undone. This will permanently delete the room <strong>{{ $room->name }}</strong> and all associated data.</p>

        <form id="deleteRoomForm" action="{{ route('rooms.destroy', $room) }}" method="POST">
          @csrf
          @method('DELETE')

          <div class="mb-3">
            <label for="confirmRoomName" class="form-label">Please type <strong>{{ $room->name }}</strong> to confirm:</label>
            <input type="text" class="form-control" id="confirmRoomName" required>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="confirmDeleteBtn" disabled>Delete this room</button>
      </div>
    </div>
  </div>
</div>

<style>
  /* Viel spezifischerer Selektor mit !important für höhere Priorität */
  .custom-danger-button.accordion-button::after {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23dc3545'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e") !important;
  }

  .custom-danger-button.accordion-button:not(.collapsed)::after {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23dc3545'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e") !important;
    transform: rotate(-180deg) !important;
  }

  /* Entfernt den blauem Focus-Ring */
  .custom-danger-button:focus {
    box-shadow: none !important;
    border-color: transparent !important;
  }

  /* Verhindert die blaue Hintergrundfarbe im geöffneten Zustand */
  .custom-danger-button:not(.collapsed) {
    background-color: transparent !important;
    color: #dc3545 !important;
  }
</style>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const roomName = "{{ $room->name }}";
    const confirmInput = document.getElementById('confirmRoomName');
    const confirmBtn = document.getElementById('confirmDeleteBtn');
    const deleteForm = document.getElementById('deleteRoomForm');

    confirmInput.addEventListener('input', function() {
      // Enable button only if input matches room name exactly
      confirmBtn.disabled = this.value !== roomName;
    });

    confirmBtn.addEventListener('click', function() {
      if (confirmInput.value === roomName) {
        deleteForm.submit();
      }
    });
  });
</script>
@endsection