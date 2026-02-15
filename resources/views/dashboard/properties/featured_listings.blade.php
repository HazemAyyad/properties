@extends('dashboard.layouts.app')
@section('style')
<style>.btn-moderation-status { cursor: pointer; }</style>
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Home') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.properties.index', 1) }}">{{ __('Properties') }}</a></li>
                <li class="breadcrumb-item active">{{ __('Featured Listings') }}</li>
            </ol>
        </nav>
        @if(session('success'))
            <div class="alert alert-success alert-dismissible" role="alert">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        @endif
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">{{ __('Featured Listings') }}</h5>
                <small class="text-muted">{{ __('Properties with featured listing request. Approve to show first in search.') }}</small>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('Property') }}</th>
                            <th>{{ __('User') }}</th>
                            <th>{{ __('Receipt') }}</th>
                            <th>{{ __('Valid until') }}</th>
                            <th>{{ __('Remaining') }}</th>
                            <th>{{ __('Moderation Status') }}</th>
                            <th>{{ __('Action') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($properties as $index => $p)
                            @php
                                $until = $p->featured_listing_until ? \Carbon\Carbon::parse($p->featured_listing_until) : null;
                                $pending = !$until;
                                $active = $until && $until->isFuture();
                                $expired = $until && $until->isPast();
                                $daysLeft = $until ? now()->diffInDays($until, false) : null;
                                $moderationStatuses = [0 => ['class' => 'text-warning', 'label' => __('Pending')], 1 => ['class' => 'text-success', 'label' => __('Approved')], 2 => ['class' => 'text-danger', 'label' => __('Rejected')]];
                                $ms = $moderationStatuses[$p->moderation_status ?? 0] ?? $moderationStatuses[0];
                            @endphp
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <a href="{{ route('admin.properties.edit', $p->id) }}">{{ $p->title ?: __('Property') . ' #' . $p->id }}</a>
                                </td>
                                <td>{{ $p->user->name ?? '-' }}</td>
                                <td>
                                    @if($p->featured_listing_receipt)
                                        <a href="{{ asset(ltrim(str_replace('/public', '', $p->featured_listing_receipt), '/')) }}" target="_blank" class="btn btn-sm btn-outline-primary">{{ __('View') }}</a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ $p->featured_listing_until ?? __('Pending') }}</td>
                                <td>
                                    @if($pending)
                                        <span class="badge bg-warning">{{ __('Pending approval') }}</span>
                                    @elseif($active)
                                        <span class="text-success">{{ $until->diffForHumans(now(), true) }}</span>
                                    @else
                                        <span class="text-muted">{{ __('Expired') }}</span>
                                    @endif
                                </td>
                                <td>
                                    <strong class="{{ $ms['class'] }} cursor-pointer btn-moderation-status" data-id="{{ $p->id }}" data-status="{{ $p->moderation_status ?? 0 }}" title="{{ __('Click to change') }}">{{ $ms['label'] }}</strong>
                                </td>
                                <td>
                                    <a href="{{ route('admin.properties.edit', $p->id) }}" class="btn btn-sm btn-outline-secondary">{{ __('Edit') }}</a>
                                    @if($pending)
                                        <form method="post" action="{{ route('admin.properties.approve-featured', $p->id) }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success">{{ __('Approve (1 month)') }}</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center text-muted py-4">{{ __('No featured listings.') }}</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Change Moderation Status -->
    <div class="modal fade" id="moderationStatusModal" tabindex="-1" aria-labelledby="moderationStatusModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="moderationStatusModalLabel">{{ __('Change Moderation Status') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="moderationStatusForm">
                        <div class="mb-3">
                            <label for="moderation_status_select" class="form-label">{{ __('Select Status') }}</label>
                            <select class="form-select" id="moderation_status_select" name="moderation_status">
                                <option value="0">{{ __('Pending') }}</option>
                                <option value="1">{{ __('Approved') }}</option>
                                <option value="2">{{ __('Rejected') }}</option>
                            </select>
                        </div>
                        <input type="hidden" id="property_id_moderation">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="button" class="btn btn-primary" id="saveModerationStatus">{{ __('Save changes') }}</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
<script>
    $(function () {
        $('.btn-moderation-status').on('click', function () {
            var propertyId = $(this).data('id');
            var currentStatus = $(this).data('status');
            $('#property_id_moderation').val(propertyId);
            $('#moderation_status_select').val(currentStatus);
            var myModal = new bootstrap.Modal(document.getElementById('moderationStatusModal'));
            myModal.show();
        });

        $('#saveModerationStatus').on('click', function () {
            var propertyId = $('#property_id_moderation').val();
            var newStatus = $('#moderation_status_select').val();
            var $btn = $(this);
            $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> {{ __("Saving") }}...');
            $.ajax({
                url: '{{ route("admin.properties.updateModerationStatus") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: propertyId,
                    moderation_status: newStatus
                },
                success: function (response) {
                    $btn.prop('disabled', false).html('{{ __("Save changes") }}');
                    if (response.success) {
                        var myModalEl = document.getElementById('moderationStatusModal');
                        var modal = bootstrap.Modal.getInstance(myModalEl);
                        if (modal) modal.hide();
                        window.location.reload();
                    } else {
                        alert('{{ __("Failed to update moderation status.") }}');
                    }
                },
                error: function () {
                    $btn.prop('disabled', false).html('{{ __("Save changes") }}');
                    alert('{{ __("An error occurred while updating the moderation status.") }}');
                }
            });
        });
    });
</script>
@endsection
