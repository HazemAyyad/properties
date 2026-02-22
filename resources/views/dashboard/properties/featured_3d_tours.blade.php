@extends('dashboard.layouts.app')
@section('style')
<style>.btn-moderation-status { cursor: pointer; } .btn-icon { padding: 0.35rem 0.5rem; }</style>
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Home') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.properties.index', 1) }}">{{ __('Properties') }}</a></li>
                <li class="breadcrumb-item active">{{ __('Featured 3D Tour') }}</li>
            </ol>
        </nav>
        @if(session('success'))
            <div class="alert alert-success alert-dismissible" role="alert">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        @endif
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">{{ __('Featured 3D Tour') }}</h5>
                <small class="text-muted">{{ __('Properties with 3D tour request. Approve to activate.') }}</small>
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
                            <th>{{ __('Action') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($properties as $index => $p)
                            @php
                                $until = $p->featured_3d_tour_until ? \Carbon\Carbon::parse($p->featured_3d_tour_until) : null;
                                $pending = !$until;
                                $active = $until && $until->isFuture();
                                $expired = $until && $until->isPast();
                            @endphp
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <a href="{{ route('admin.properties.edit', $p->id) }}">{{ $p->title ?: __('Property') . ' #' . $p->id }}</a>
                                </td>
                                <td>{{ $p->user->name ?? '-' }}</td>
                                <td>
                                    @if($p->featured_3d_tour_receipt)
                                        <a href="{{ asset(ltrim(str_replace('/public', '', $p->featured_3d_tour_receipt), '/')) }}" target="_blank" class="btn btn-sm btn-icon btn-outline-primary" title="{{ __('View') }}">
                                            <i class="ti ti-eye ti-sm"></i>
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ $p->featured_3d_tour_until ?? __('Pending') }}</td>
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
                                    <a href="{{ route('admin.properties.edit', $p->id) }}" class="btn btn-sm btn-icon btn-outline-secondary" title="{{ __('Edit') }}">
                                        <i class="ti ti-edit ti-sm"></i>
                                    </a>
                                    @if($pending)
                                        <button type="button" class="btn btn-sm btn-icon btn-success btn-approve-3d" data-url="{{ route('admin.properties.approve-featured-3d', $p->id) }}" title="{{ __('Approve (1 month)') }}">
                                            <i class="ti ti-check ti-sm"></i>
                                        </button>
                                        <form method="post" action="{{ route('admin.properties.reject-featured-3d', $p->id) }}" class="d-inline" onsubmit="return confirm('{{ __('Are you sure you want to reject?') }}');">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-icon btn-danger" title="{{ __('Reject') }}">
                                                <i class="ti ti-x ti-sm"></i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center text-muted py-4">{{ __('No 3D tour requests.') }}</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Approve with iframe URL -->
    <div class="modal fade" id="approve3dModal" tabindex="-1" aria-labelledby="approve3dModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="approve3dModalLabel">{{ __('Approve 3D Tour') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="approve3dForm" method="post" action="">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="iframe_url" class="form-label">{{ __('3D Tour iframe URL / Embed Code') }} <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="iframe_url" name="iframe_url" rows="4" placeholder="{{ __('Paste the iframe embed code or URL here...') }}" required></textarea>
                            <small class="text-muted">{{ __('Paste the full iframe HTML or the src URL of the 3D tour embed.') }}</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" class="btn btn-success">
                            <i class="ti ti-check me-1"></i>{{ __('Approve (1 month)') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
<script>
    $(function() {
        $('.btn-approve-3d').on('click', function() {
            var url = $(this).data('url');
            $('#approve3dForm').attr('action', url);
            $('#iframe_url').val('');
            var modal = new bootstrap.Modal(document.getElementById('approve3dModal'));
            modal.show();
        });
    });
</script>
@endsection
