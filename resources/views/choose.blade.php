<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="https://img.icons8.com/external-smashingstocks-flat-smashing-stocks/66/external-Shopping-App-smooth-conceptual-smashingstocks-flat-smashing-stocks.png" type="image/x-icon">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-900 antialiased">
<div class=" flex flex-col justify-center items-center sm:pt-0 bg-gray-100">
    <div class="flex flex-col w-full items-center justify-center min-h-screen bg-gradient-to-b from-blue-100 to-white">
        <a href="#" class="flex items-center mb-3 text-4xl font-semibold text-gray-900 dark:text-white">
            <img class="w-32 h-32 mr-2" src="https://img.icons8.com/external-smashingstocks-flat-smashing-stocks/66/external-Shopping-App-smooth-conceptual-smashingstocks-flat-smashing-stocks.png" alt="logo">
            Soluxify
        </a>
        <h1 class="md:text-6xl text-4xl text-primary-600 font-bold mb-8 text-center">Bienvenue sur Soluxify</h1>
        <p class="text-2xl md:mb-12 text-primary-400 text-center">Êtes-vous un client ou un vendeur ?</p>
        <div class="md:flex flex-wrap p-4 gap-6 md:w-auto w-full">
            <a href="{{route("client_login")}}" class="">
                <button type="submit" class="mb-4 text-xl mt-4 flex w-full items-center justify-center rounded-lg bg-primary-700  px-5 py-2.5 font-medium text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300  dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800 sm:mt-0">Je suis un client</button>
            </a>
            <a href="{{route("vendor_login")}}">
                <button type="button" class="text-xl w-full rounded-lg  border border-gray-200 bg-white px-5  py-2.5 font-medium text-gray-900 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:outline-none focus:ring-4 focus:ring-gray-100 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white dark:focus:ring-gray-700">Je suis un vendeur</button>
            </a>
        </div>
    </div>
</div>
</body>
</html>
