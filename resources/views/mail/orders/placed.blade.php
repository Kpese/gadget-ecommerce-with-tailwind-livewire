<x-mail::message>
# Order Placed Successfuly!

Thank you for your order. Your order number is: {{ $order->id }}.

<x-mail::button :url="$url">
View Order Details
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
