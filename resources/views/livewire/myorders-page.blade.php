<div>
    <div class="w-full max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 mx-auto">
        <h1 class="text-4xl font-bold text-slate-500">My Orders</h1>
        <div class="flex flex-col bg-white p-5 rounded mt-4 shadow-lg">
            <div class="-m-1.5 overflow-x-auto">
                <div class="p-1.5 min-w-full inline-block align-middle">
                    <div class="overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead>
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Order</th>
                                    <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Date</th>
                                    <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Order Status</th>
                                    <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Payment Status</th>
                                    <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Order Amount</th>
                                    <th scope="col" class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order as $item)

                                @php
                                $status ='';
                                $payment_status = '';

                                switch ($item->status) {
                                    case 'new':
                                    $status = '<span class="bg-blue-500 py-1 px-3 rounded text-white shadow"> New</span>';
                                        break;
                                        case 'processing':
                                    $status = '<span class="bg-yellow-500 py-1 px-3 rounded text-white shadow">Processing</span>';
                                        break;
                                        case 'shipped':
                                    $status = '<span class="bg-green-500 py-1 px-3 rounded text-white shadow">Shipped</span>';
                                        break;
                                        case 'delivered':
                                    $status = '<span class="bg-green-700 py-1 px-3 rounded text-white shadow">Delivered</span>';
                                        break;
                                        case 'canceled':
                                    $status = '<span class="bg-red-500 py-1 px-3 rounded text-white shadow"> Cancelled</span>';
                                        break;
                                    default:
                                        'not found';
                                        break;
                                }

                                switch ($item->payment_status) {
                                    case 'paid':
                                    $payment_status = '<span class="bg-green-500 py-1 px-3 rounded text-white shadow">Paid</span>';
                                        break;
                                        case 'pending':
                                    $payment_status = '<span class="bg-yellow-500 py-1 px-3 rounded text-white shadow">Pending</span>';
                                        break;
                                        case 'failed':
                                    $payment_status = '<span class="bg-red-500 py-1 px-3 rounded text-white shadow">Failed</span>';
                                        break;
                                    default:
                                        'not found';
                                        break;
                                }
                                @endphp
                                <tr wire:key="{{ $item->id }}" class="odd:bg-white even:bg-gray-100 dark:odd:bg-slate-900 dark:even:bg-slate-800">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">{{ $item->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">{{ $item->created_at->format('d-M-Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">{!! $status !!} </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">{!! $payment_status  !!}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                        @php
                                        $price = "₦" . Number_format($item->grand_total, 2)
                                        @endphp
                                        {{ $price}}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                                        <a href="/myorders/{{ $item->id }}" class="bg-slate-600 text-white py-2 px-4 rounded-md hover:bg-slate-500">View Details</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-4">
                            {{ $order->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
