<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>WIN: Planning Tools</title>
    @vite('resources/css/app.css')
    @vite('resources/css/vendor-dashboard.css')
    @vite('resources/js/app.js')
    @include('components.fonts')
</head>

<body class="m-0 overflow-x-hidden bg-white">
@if($role === 'vendor')
    @include('layouts.vendor_navigation')
@else
    @include('layouts.navigation')
@endif

<main class="relative h-full transition-all duration-200 ease-in-out rounded-xl">
    @if($role === 'vendor')
        @include('layouts.dashboard_topbar', ['role' => 'vendor'])
    @endif
    <div class="bg-[#EDE9F5] lg:mx-8 rounded-3xl pb-8">
        <div class="w-full px-6 py-6 container mx-auto">
            <div class="bg-white rounded-3xl p-6 md:p-8">
                <h1 class="headline-small mb-2">Planning Tools</h1>
                <p class="text-win-charcoal mb-6">
                    Access WIN planning experiences in dedicated pages. These tools are kept separate from the current dashboards to avoid affecting existing production flows.
                </p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    @if($role === 'couple')
                        <section class="rounded-2xl border border-[#E6DFFF] bg-white p-5 shadow-sm">
                            <p class="subheading mb-2">Wedding Investment Planner</p>
                            <p class="body-copy text-win-charcoal mb-4">Build and save your wedding budget with the full planner interface.</p>
                            <a href="{{ route('couple.investment_planner') }}" class="inline-flex items-center rounded-lg bg-win-blue text-white px-4 py-2 font-semibold">
                                Open Budget Tool
                            </a>
                        </section>
                    @endif

                    <section class="rounded-2xl border border-[#E6DFFF] bg-white p-5 shadow-sm">
                        <p class="subheading mb-2">Wedding Timeline Planner</p>
                        <p class="body-copy text-win-charcoal mb-4">
                            Build your day-of timeline with vendors, tasks, and export options.
                        </p>
                        @if($role === 'vendor')
                            <a href="{{ route('vendor.timeline') }}" class="inline-flex items-center rounded-lg bg-win-blue text-white px-4 py-2 font-semibold">
                                Open Timeline Tool
                            </a>
                        @else
                            <a href="{{ route('couple.timeline') }}" class="inline-flex items-center rounded-lg bg-win-blue text-white px-4 py-2 font-semibold">
                                Open Timeline Tool
                            </a>
                        @endif
                    </section>
                </div>
            </div>
        </div>
    </div>
    @if($role === 'vendor')
        <p class="vd-copyright">&copy; {{ date('Y') }} Wedding Insiders Network.</p>
    @endif
</main>
</body>
</html>

