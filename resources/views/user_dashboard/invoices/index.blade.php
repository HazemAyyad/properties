@extends('user_dashboard.layouts.app')
@section('style')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <style>
        .invoices-table { width: 100%; border-collapse: collapse; }
        .invoices-table th, .invoices-table td { padding: 12px 16px; text-align: left; border-bottom: 1px solid #eee; }
        .invoices-table th { font-weight: 600; color: #333; background: #f8f9fa; }
        .badge-pending { background: #ffc107; color: #000; }
        .badge-approved { background: #198754; color: #fff; }
        .badge-rejected { background: #dc3545; color: #fff; }
        .receipt-link { color: #1779A7; text-decoration: underline; }
        .receipt-link:hover { color: #0d5a7a; }
    </style>
@endsection
@section('content')
    <div class="widget-box-2 wd-listing">
        <h6 class="title">{{ __('My Invoices') }} / {{ __('Payment History') }}</h6>
        <p class="text-variant-1 mb-4">{{ __('All payments you have made for subscriptions, featured listings, and 3D tours.') }}</p>

        @if($items->isEmpty())
            <div class="box-tes-item p-4">
                <span class="text-variant-1">{{ __('No payment records yet.') }}</span>
                <p class="mt-2 mb-0">{{ __('When you upgrade your plan, pay for featured listings, or 3D tours, they will appear here.') }}</p>
            </div>
        @else
            <div class="wrap-table">
                <div class="table-responsive">
                <table class="invoices-table">
                    <thead>
                        <tr>
                            <th>{{ __('Type') }}</th>
                            <th>{{ __('Related Item') }}</th>
                            <th>{{ __('Amount') }}</th>
                            <th>{{ __('Currency') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Date') }}</th>
                            <th>{{ __('Receipt') }}</th>
                            <th>{{ __('Notes') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                            <tr>
                                <td>{{ $item->type_label }}</td>
                                <td>{{ $item->related_item }}</td>
                                <td>{{ number_format($item->amount, 2) }}</td>
                                <td>{{ $item->currency }}</td>
                                <td>
                                    @php
                                        $badgeClass = match($item->status) {
                                            'approved' => 'badge-approved',
                                            'rejected' => 'badge-rejected',
                                            default => 'badge-pending'
                                        };
                                        $statusLabel = match($item->status) {
                                            'approved' => __('Approved'),
                                            'rejected' => __('Rejected'),
                                            default => __('Pending')
                                        };
                                    @endphp
                                    <span class="badge rounded-pill {{ $badgeClass }}">{{ $statusLabel }}</span>
                                </td>
                                <td>{{ $item->date ? $item->date->format('Y-m-d H:i') : '-' }}</td>
                                <td>
                                    @if(!empty($item->receipt_path))
                                        @php
                                            $receiptUrl = ltrim(str_replace('/public', '', $item->receipt_path), '/');
                                            $receiptUrl = str_replace('/public/public/', '/public/', asset($receiptUrl));
                                        @endphp
                                        <a href="{{ $receiptUrl }}" target="_blank" rel="noopener" class="receipt-link">{{ __('View') }}</a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ $item->notes ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
            </div>
        @endif
    </div>
@endsection
