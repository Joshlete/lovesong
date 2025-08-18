<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Profile</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styles -->
    @livewireStyles
</head>
<body class="font-sans antialiased">
    <x-banner />

    <div class="min-h-screen bg-gradient-to-br from-purple-600 via-pink-500 to-red-500">
        @livewire('dashboard-header')

        <!-- Page Content -->
        <main class="py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Profile Header with Stats -->
                @livewire('profile-stats')
                
                <!-- Profile Forms -->
                <div class="mt-8 space-y-6">
                    @if (Laravel\Fortify\Features::canUpdateProfileInformation())
                        <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-xl p-6 border border-white/20">
                            @livewire('profile.update-profile-information-form')
                        </div>
                    @endif

                    @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
                        <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-xl p-6 border border-white/20">
                            @livewire('profile.update-password-form')
                        </div>
                    @endif

                    @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                        <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-xl p-6 border border-white/20">
                            @livewire('profile.two-factor-authentication-form')
                        </div>
                    @endif

                    <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-xl p-6 border border-white/20">
                        @livewire('profile.logout-other-browser-sessions-form')
                    </div>

                    @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
                        <div class="bg-red-50/95 backdrop-blur-sm rounded-2xl shadow-xl p-6 border border-red-200/20">
                            @livewire('profile.delete-user-form')
                        </div>
                    @endif
                </div>
            </div>
        </main>
    </div>

    @stack('modals')

    @livewireScripts
</body>
</html>
