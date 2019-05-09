@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <h2>Subscriptions</h2>
    </div>
    <div class="row">
        @if (!$user->subscribed('dog_treats'))
            <form action="{{ action('HomeController@subscribe', ['dog_treats', 'dog_treats_monthly']) }}" method="POST">
                {{ csrf_field() }}
                <script
                    src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                    data-key="{{ env('STRIPE_KEY') }}"
                    data-amount="99"
                    data-name="Monthly Dog Treats"
                    data-label="Monthly Dog Treats $0.99"
                    data-description="Monthly subscription for dog treats..."
                    data-image="https://images.all-free-download.com/images/graphiclarge/dog_vector_276101.jpg"
                    data-locale="auto"
                    data-currency="usd">
                </script>
            </form>
            <form action="{{ action('HomeController@subscribe', ['dog_treats', 'dog_treats_yearly']) }}" method="POST">
                {{ csrf_field() }}
                <script
                    src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                    data-key="{{ env('STRIPE_KEY') }}"
                    data-amount="999"
                    data-name="Yearly Dog Treats"
                    data-label="Yearly Dog Treats $9.99"
                    data-description="Yearly subscription for dog treats..."
                    data-image="https://images.all-free-download.com/images/graphiclarge/dog_vector_276101.jpg"
                    data-locale="auto"
                    data-currency="usd">
                </script>
            </form>
        @else
            <div class="clear-both">You are subscribed to a plan...</div>
            @if ($user->subscribedToPlan('dog_treats_monthly', 'dog_treats'))
                <div class="clear-both"><a class="btn btn-success" href="{{ action('HomeController@changeSubscription', ['dog_treats', 'dog_treats_yearly']) }}">Upgrade to Yearly $9.99</a></div>
            @elseif ($user->subscribedToPlan('dog_treats_yearly', 'dog_treats'))
                <div class="clear-both"><a class="btn btn-warning" href="{{ action('HomeController@changeSubscription', ['dog_treats', 'dog_treats_monthly']) }}">Downgrade to Monthly $0.99</a></div>
            @endif
            <div><a class="btn btn-danger" href="{{ action('HomeController@changeSubscription', ['dog_treats', 'CANCEL']) }}">Cancel Subscription</a></div>
        @endif
    </div>
    <div class="row justify-content-center">
        <h2>Invoices</h2>
    </div>
    <div class="row">
        <table class="table">
            <thead>
                <tr>
                    <th>Invoice Date</th>
                    <th>Total</th>
                    <th>Download</th>
                </tr>
            </thead>
            <tbody>
                    @foreach ($user->invoices() as $invoice)
                        <tr>
                            <td>{{ gmdate("Y-m-d H:i:s", $invoice->created) }}</td>
                            <td>{{ $invoice->total() }}</td>
                            <td>
                                <a href="{{ action('HomeController@invoice', ['invoice_id'=>$invoice->id]) }}">Download</a>
                            </td>
                        </tr>
                    @endforeach
            </tbody>
        </table>
    </div>
</div>
<style>
    .clear-both {
        clear: both;
        width: 100%;
    }
</style>
@endsection