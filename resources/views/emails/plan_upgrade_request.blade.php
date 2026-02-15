<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .box { padding: 15px; background: #f5f5f5; border-radius: 8px; margin: 10px 0; }
        .btn { display: inline-block; padding: 10px 20px; background: #7367F0; color: #fff !important; text-decoration: none; border-radius: 6px; margin-top: 10px; }
    </style>
</head>
<body>
    <p>{{ __('New plan upgrade request') }}</p>
    <div class="box">
        <p><strong>{{ __('User') }}:</strong> {{ $upgradeRequest->user ? $upgradeRequest->user->name : '—' }}</p>
        <p><strong>{{ __('Email') }}:</strong> {{ $upgradeRequest->user ? $upgradeRequest->user->email : '—' }}</p>
        <p><strong>{{ __('Requested plan') }}:</strong> {{ $upgradeRequest->plan ? $upgradeRequest->plan->title : '—' }} ({{ $upgradeRequest->plan ? $upgradeRequest->plan->price_monthly : '' }} JOD)</p>
        <p><strong>{{ __('Date') }}:</strong> {{ $upgradeRequest->created_at->format('Y-m-d H:i') }}</p>
    </div>
    <a href="{{ $requestUrl }}" class="btn">{{ __('View request & transfer receipt') }}</a>
</body>
</html>
