@props(['activeStep' => 1])
@php
    $assetBase = '/images/new_view_Join_Us';
    $activeStep = (int) $activeStep;
@endphp
<ul class="join-us-vendor-stepper-nav">
    <li class="join-us-vendor-stepper-item" data-hs-stepper-nav-item='{"index": 1}'>
        <img
            src="{{ $assetBase }}/{{ $activeStep === 1 ? 'one_color.png' : 'one_opaco.png' }}"
            alt="Step 1"
            class="join-us-vendor-step-icon"
        >
        <span class="join-us-vendor-step-label {{ $activeStep === 1 ? 'is-active' : 'is-inactive' }}">Your Account</span>
    </li>
    <li class="join-us-vendor-stepper-item" data-hs-stepper-nav-item='{"index": 2}'>
        <img
            src="{{ $assetBase }}/{{ $activeStep === 2 ? 'two_color.png' : 'two_opaco.png' }}"
            alt="Step 2"
            class="join-us-vendor-step-icon"
        >
        <span class="join-us-vendor-step-label {{ $activeStep === 2 ? 'is-active' : 'is-inactive' }}">Your Business</span>
    </li>
</ul>
