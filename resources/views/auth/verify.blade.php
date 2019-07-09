@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('趕快給我去收信喔') }}</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('新的驗證連結已經發送到您的信箱') }}
                        </div>
                    @endif

                    {{ __('在開始之前，請檢查您的電子郵件以獲取驗證連結，') }}
                    {{ __('如果您沒有收到電子郵件，') }}<a href="{{ route('verification.resend') }}">{{ __('點擊這裡重新發送。') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
