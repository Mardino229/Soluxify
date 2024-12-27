
@extends('vendors.layouts.app')
@section('main')
    <div class="py-12">
        <section class="bg-white py-8 antialiased dark:bg-gray-900 md:py-16">
            <div class="mx-auto max-w-screen-xl px-4 2xl:px-0">
                <div class="mt-6 sm:mt-8 lg:flex lg:gap-8">
                    <div class="w-full divide-y divide-gray-200 overflow-hidden rounded-lg border border-gray-200 dark:divide-gray-700 dark:border-gray-700 lg:max-w-xl xl:max-w-2xl">
                        @foreach($order->products as $product)
                            <div class="space-y-4 p-6">
                                <div class="flex items-center gap-6">
                                    <a href="{{route('view', $product)}}" class="h-14 w-14 shrink-0">
                                        <img class="h-full w-full dark:hidden" src="{{$product->image()}}" alt="imac image" />
                                    </a>

                                    <a href="{{route('view', $product)}}" class="min-w-0 flex-1 font-medium text-gray-900 hover:underline dark:text-white">
                                        {{$product->name}}
                                    </a>
                                </div>

                                <div class="flex items-center justify-between gap-4">
                                    <p class="text-sm font-normal text-gray-500 dark:text-gray-400"><span class="font-medium text-gray-900 dark:text-white">Product ID:</span> {{$product->reference}}</p>

                                    <div class="flex items-center justify-end gap-4">
                                        <p class="text-base font-normal text-gray-900 dark:text-white">x{{$product->pivot->quantity}}</p>

                                        <p class="text-xl font-bold leading-tight text-gray-900 dark:text-white">${{$product->pivot->quantity*$product->price}}</p>
                                    </div>
                                </div>
                            </div>

                        @endforeach

                        <div class="space-y-4 bg-gray-50 p-6 dark:bg-gray-800">
                            <div class="space-y-2">
                                <dl class="flex items-center justify-between gap-4">
                                    <dt class="font-normal text-gray-500 dark:text-gray-400">Original price</dt>
                                    <dd class="font-medium text-gray-900 dark:text-white">${{$order->total}}</dd>
                                </dl>

                                <dl class="flex items-center justify-between gap-4">
                                    <dt class="font-normal text-gray-500 dark:text-gray-400">Savings</dt>
                                    <dd class="text-base font-medium text-green-500">-$0.00</dd>
                                </dl>

                                <dl class="flex items-center justify-between gap-4">
                                    <dt class="font-normal text-gray-500 dark:text-gray-400">Store Pickup and Delivery</dt>
                                    <dd class="font-medium text-gray-900 dark:text-white">$5</dd>
                                </dl>
                            </div>

                            <dl class="flex items-center justify-between gap-4 border-t border-gray-200 pt-2 dark:border-gray-700">
                                <dt class="text-lg font-bold text-gray-900 dark:text-white">Total</dt>
                                <dd class="text-lg font-bold text-gray-900 dark:text-white">${{$order->total + 5 }}</dd>
                            </dl>
                        </div>
                    </div>

                    <div class="mt-6 grow sm:mt-8 lg:mt-0">
                        <div class="space-y-6 rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Order details</h3>

                            <dl class="max-w-md text-gray-900 divide-y divide-gray-200 dark:text-white dark:divide-gray-700">
                                <div class="flex flex-col pb-3">
                                    <dt class="mb-1 text-gray-500 md:text-lg dark:text-gray-400">Client name</dt>
                                    <dd class="text-lg font-semibold">{{$order->client->name}}</dd>
                                </div>
                                <div class="flex flex-col pb-3">
                                    <dt class="mb-1 text-gray-500 md:text-lg dark:text-gray-400">Email address</dt>
                                    <dd class="text-lg font-semibold">{{$order->client->email}}</dd>
                                </div>
                                <div class="flex flex-col py-3">
                                    <dt class="mb-1 text-gray-500 md:text-lg dark:text-gray-400">Delivery address</dt>
                                    <dd class="text-lg font-semibold">{{$order->delivery_address}}</dd>
                                </div>
                                <div class="flex flex-col py-3">
                                    <dt class="mb-1 text-gray-500 md:text-lg dark:text-gray-400">Status</dt>
                                    @if($order->status == "Cancelled" | $order->status == "Refused" )
                                        <dd class="me-2 mt-1.5 inline-flex items-center rounded bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800 dark:bg-red-900 dark:text-red-300">
                                            <svg class="me-1 h-3 w-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6" />
                                            </svg>
                                            {{$order->status}}
                                        </dd>
                                    @elseif($order->status == "Validated")
                                        <dd class="me-2 mt-1.5 inline-flex items-center rounded bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:bg-green-900 dark:text-green-300">
                                            <svg class="me-1 h-3 w-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 11.917 9.724 16.5 19 7.5" />
                                            </svg>
                                            {{$order->status}}
                                        </dd>
                                    @else
                                        <dd class="me-2 mt-1.5 inline-flex items-center rounded bg-primary-100 px-2.5 py-0.5 text-xs font-medium text-primary-800 dark:bg-primary-900 dark:text-primary-300">
                                            <svg class="me-1 h-3 w-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.5 4h-13m13 16h-13M8 20v-3.333a2 2 0 0 1 .4-1.2L10 12.6a1 1 0 0 0 0-1.2L8.4 8.533a2 2 0 0 1-.4-1.2V4h8v3.333a2 2 0 0 1-.4 1.2L13.957 11.4a1 1 0 0 0 0 1.2l1.643 2.867a2 2 0 0 1 .4 1.2V20H8Z" />
                                            </svg>
                                            {{$order->status}}
                                        </dd>
                                    @endif
                                </div>
                            </dl>

                            <div class="gap-4 sm:flex sm:items-center">
                                @if($order->status == "Pending")
                                    <form method="POST" action={{route("validated_order", $order->reference)}}>
                                        @method("PATCH")
                                        @csrf
                                        <button type="submit" class="mt-4 flex w-full items-center justify-center rounded-lg bg-primary-700  px-5 py-2.5 text-sm font-medium text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300  dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800 sm:mt-0">Validate the order</button>
                                    </form>
                                    <form method="POST" action={{route("refused_order", $order->reference)}}>
                                        @method("PATCH")
                                        @csrf
                                        <button type="submit" class="w-full rounded-lg  border border-gray-200 bg-white px-5  py-2.5 text-sm font-medium text-gray-900 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:outline-none focus:ring-4 focus:ring-gray-100 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white dark:focus:ring-gray-700">
                                            Refuse the order
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
