<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}" />

  <title>WIN: User Registration</title>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
  
  <link href="https://unpkg.com/cropperjs/dist/cropper.css" rel="stylesheet"/>
  <script src="https://unpkg.com/cropperjs"></script>
  <!-- Styles -->
  <style>
    /* ! tailwindcss v3.2.4 | MIT License | https://tailwindcss.com */
    *,
    ::after,
    ::before {
      box-sizing: border-box;
      border-width: 0;
      border-style: solid;
      border-color: #e5e7eb
    }

    ::after,
    ::before {
      --tw-content: ''
    }

    html {
      line-height: 1.5;
      -webkit-text-size-adjust: 100%;
      -moz-tab-size: 4;
      tab-size: 4;
      font-family: Figtree, sans-serif;
      font-feature-settings: normal
    }

    body {
      margin: 0;
      line-height: inherit
    }

    hr {
      height: 0;
      color: inherit;
      border-top-width: 1px
    }

    abbr:where([title]) {
      -webkit-text-decoration: underline dotted;
      text-decoration: underline dotted
    }

    h1,
    h2,
    h3,
    h4,
    h5,
    h6 {
      font-size: inherit;
      font-weight: inherit
    }

    a {
      color: inherit;
      text-decoration: inherit
    }

    b,
    strong {
      font-weight: bolder
    }

    code,
    kbd,
    pre,
    samp {
      font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
      font-size: 1em
    }

    small {
      font-size: 80%
    }

    sub,
    sup {
      font-size: 75%;
      line-height: 0;
      position: relative;
      vertical-align: baseline
    }

    sub {
      bottom: -.25em
    }

    sup {
      top: -.5em
    }

    table {
      text-indent: 0;
      border-color: inherit;
      border-collapse: collapse
    }

    button,
    input,
    optgroup,
    select,
    textarea {
      font-family: inherit;
      font-size: 100%;
      font-weight: inherit;
      line-height: inherit;
      color: inherit;
      margin: 0;
      padding: 0
    }

    button,
    select {
      text-transform: none
    }

    [type=button],
    [type=reset],
    [type=submit],
    button {
      -webkit-appearance: button;
      background-color: transparent;
      background-image: none
    }

    :-moz-focusring {
      outline: auto
    }

    :-moz-ui-invalid {
      box-shadow: none
    }

    progress {
      vertical-align: baseline
    }

    ::-webkit-inner-spin-button,
    ::-webkit-outer-spin-button {
      height: auto
    }

    [type=search] {
      -webkit-appearance: textfield;
      outline-offset: -2px
    }

    ::-webkit-search-decoration {
      -webkit-appearance: none
    }

    ::-webkit-file-upload-button {
      -webkit-appearance: button;
      font: inherit
    }

    summary {
      display: list-item
    }

    blockquote,
    dd,
    dl,
    figure,
    h1,
    h2,
    h3,
    h4,
    h5,
    h6,
    hr,
    p,
    pre {
      margin: 0
    }

    fieldset {
      margin: 0;
      padding: 0
    }

    legend {
      padding: 0
    }

    menu,
    ol,
    ul {
      list-style: none;
      margin: 0;
      padding: 0
    }

    textarea {
      resize: vertical
    }

    input::placeholder,
    textarea::placeholder {
      opacity: 1;
      color: #9ca3af
    }

    [role=button],
    button {
      cursor: pointer
    }

    :disabled {
      cursor: default
    }

    audio,
    canvas,
    embed,
    iframe,
    img,
    object,
    svg,
    video {
      display: block;
      vertical-align: middle
    }

    img,
    video {
      max-width: 100%;
      height: auto
    }

    [hidden] {
      display: none
    }

    *,
    ::before,
    ::after {
      --tw-border-spacing-x: 0;
      --tw-border-spacing-y: 0;
      --tw-translate-x: 0;
      --tw-translate-y: 0;
      --tw-rotate: 0;
      --tw-skew-x: 0;
      --tw-skew-y: 0;
      --tw-scale-x: 1;
      --tw-scale-y: 1;
      --tw-pan-x: ;
      --tw-pan-y: ;
      --tw-pinch-zoom: ;
      --tw-scroll-snap-strictness: proximity;
      --tw-ordinal: ;
      --tw-slashed-zero: ;
      --tw-numeric-figure: ;
      --tw-numeric-spacing: ;
      --tw-numeric-fraction: ;
      --tw-ring-inset: ;
      --tw-ring-offset-width: 0px;
      --tw-ring-offset-color: #fff;
      --tw-ring-color: rgb(59 130 246 / 0.5);
      --tw-ring-offset-shadow: 0 0 #0000;
      --tw-ring-shadow: 0 0 #0000;
      --tw-shadow: 0 0 #0000;
      --tw-shadow-colored: 0 0 #0000;
      --tw-blur: ;
      --tw-brightness: ;
      --tw-contrast: ;
      --tw-grayscale: ;
      --tw-hue-rotate: ;
      --tw-invert: ;
      --tw-saturate: ;
      --tw-sepia: ;
      --tw-drop-shadow: ;
      --tw-backdrop-blur: ;
      --tw-backdrop-brightness: ;
      --tw-backdrop-contrast: ;
      --tw-backdrop-grayscale: ;
      --tw-backdrop-hue-rotate: ;
      --tw-backdrop-invert: ;
      --tw-backdrop-opacity: ;
      --tw-backdrop-saturate: ;
      --tw-backdrop-sepia:
    }

    ::-webkit-backdrop {
      --tw-border-spacing-x: 0;
      --tw-border-spacing-y: 0;
      --tw-translate-x: 0;
      --tw-translate-y: 0;
      --tw-rotate: 0;
      --tw-skew-x: 0;
      --tw-skew-y: 0;
      --tw-scale-x: 1;
      --tw-scale-y: 1;
      --tw-pan-x: ;
      --tw-pan-y: ;
      --tw-pinch-zoom: ;
      --tw-scroll-snap-strictness: proximity;
      --tw-ordinal: ;
      --tw-slashed-zero: ;
      --tw-numeric-figure: ;
      --tw-numeric-spacing: ;
      --tw-numeric-fraction: ;
      --tw-ring-inset: ;
      --tw-ring-offset-width: 0px;
      --tw-ring-offset-color: #fff;
      --tw-ring-color: rgb(59 130 246 / 0.5);
      --tw-ring-offset-shadow: 0 0 #0000;
      --tw-ring-shadow: 0 0 #0000;
      --tw-shadow: 0 0 #0000;
      --tw-shadow-colored: 0 0 #0000;
      --tw-blur: ;
      --tw-brightness: ;
      --tw-contrast: ;
      --tw-grayscale: ;
      --tw-hue-rotate: ;
      --tw-invert: ;
      --tw-saturate: ;
      --tw-sepia: ;
      --tw-drop-shadow: ;
      --tw-backdrop-blur: ;
      --tw-backdrop-brightness: ;
      --tw-backdrop-contrast: ;
      --tw-backdrop-grayscale: ;
      --tw-backdrop-hue-rotate: ;
      --tw-backdrop-invert: ;
      --tw-backdrop-opacity: ;
      --tw-backdrop-saturate: ;
      --tw-backdrop-sepia:
    }

    ::backdrop {
      --tw-border-spacing-x: 0;
      --tw-border-spacing-y: 0;
      --tw-translate-x: 0;
      --tw-translate-y: 0;
      --tw-rotate: 0;
      --tw-skew-x: 0;
      --tw-skew-y: 0;
      --tw-scale-x: 1;
      --tw-scale-y: 1;
      --tw-pan-x: ;
      --tw-pan-y: ;
      --tw-pinch-zoom: ;
      --tw-scroll-snap-strictness: proximity;
      --tw-ordinal: ;
      --tw-slashed-zero: ;
      --tw-numeric-figure: ;
      --tw-numeric-spacing: ;
      --tw-numeric-fraction: ;
      --tw-ring-inset: ;
      --tw-ring-offset-width: 0px;
      --tw-ring-offset-color: #fff;
      --tw-ring-color: rgb(59 130 246 / 0.5);
      --tw-ring-offset-shadow: 0 0 #0000;
      --tw-ring-shadow: 0 0 #0000;
      --tw-shadow: 0 0 #0000;
      --tw-shadow-colored: 0 0 #0000;
      --tw-blur: ;
      --tw-brightness: ;
      --tw-contrast: ;
      --tw-grayscale: ;
      --tw-hue-rotate: ;
      --tw-invert: ;
      --tw-saturate: ;
      --tw-sepia: ;
      --tw-drop-shadow: ;
      --tw-backdrop-blur: ;
      --tw-backdrop-brightness: ;
      --tw-backdrop-contrast: ;
      --tw-backdrop-grayscale: ;
      --tw-backdrop-hue-rotate: ;
      --tw-backdrop-invert: ;
      --tw-backdrop-opacity: ;
      --tw-backdrop-saturate: ;
      --tw-backdrop-sepia:
    }

    .relative {
      position: relative
    }

    .mx-auto {
      margin-left: auto;
      margin-right: auto
    }

    .mx-6 {
      margin-left: 1.5rem;
      margin-right: 1.5rem
    }

    .ml-4 {
      margin-left: 1rem
    }

    .mt-16 {
      margin-top: 4rem
    }

    .mt-6 {
      margin-top: 1.5rem
    }

    .mt-4 {
      margin-top: 1rem
    }

    .-mt-px {
      margin-top: -1px
    }

    .mr-1 {
      margin-right: 0.25rem
    }

    .flex {
      display: flex
    }

    .inline-flex {
      display: inline-flex
    }

    .grid {
      display: grid
    }

    .h-16 {
      height: 4rem
    }

    .h-7 {
      height: 1.75rem
    }

    .h-6 {
      height: 1.5rem
    }

    .h-5 {
      height: 1.25rem
    }

    .min-h-screen {
      min-height: 100vh
    }

    .w-auto {
      width: auto
    }

    .w-16 {
      width: 4rem
    }

    .w-7 {
      width: 1.75rem
    }

    .w-6 {
      width: 1.5rem
    }

    .w-5 {
      width: 1.25rem
    }

    .max-w-7xl {
      max-width: 80rem
    }

    .shrink-0 {
      flex-shrink: 0
    }

    .scale-100 {
      --tw-scale-x: 1;
      --tw-scale-y: 1;
      transform: translate(var(--tw-translate-x), var(--tw-translate-y)) rotate(var(--tw-rotate)) skewX(var(--tw-skew-x)) skewY(var(--tw-skew-y)) scaleX(var(--tw-scale-x)) scaleY(var(--tw-scale-y))
    }

    .grid-cols-1 {
      grid-template-columns: repeat(1, minmax(0, 1fr))
    }

    .items-center {
      align-items: center
    }

    .justify-center {
      justify-content: center
    }

    .gap-6 {
      gap: 1.5rem
    }

    .gap-4 {
      gap: 1rem
    }

    .self-center {
      align-self: center
    }

    .rounded-lg {
      border-radius: 0.5rem
    }

    .rounded-full {
      border-radius: 9999px
    }

    .bg-gray-100 {
      --tw-bg-opacity: 1;
      background-color: rgb(243 244 246 / var(--tw-bg-opacity))
    }

    .bg-white {
      --tw-bg-opacity: 1;
      background-color: rgb(255 255 255 / var(--tw-bg-opacity))
    }

    .bg-red-50 {
      --tw-bg-opacity: 1;
      background-color: rgb(254 242 242 / var(--tw-bg-opacity))
    }

    .bg-dots-darker {
      background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(0,0,0,0.07)'/%3E%3C/svg%3E")
    }

    .from-gray-700\/50 {
      --tw-gradient-from: rgb(55 65 81 / 0.5);
      --tw-gradient-to: rgb(55 65 81 / 0);
      --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to)
    }

    .via-transparent {
      --tw-gradient-to: rgb(0 0 0 / 0);
      --tw-gradient-stops: var(--tw-gradient-from), transparent, var(--tw-gradient-to)
    }

    .bg-center {
      background-position: center
    }

    .stroke-red-500 {
      stroke: #ef4444
    }

    .stroke-gray-400 {
      stroke: #9ca3af
    }

    .p-6 {
      padding: 1.5rem
    }

    .px-6 {
      padding-left: 1.5rem;
      padding-right: 1.5rem
    }

    .text-center {
      text-align: center
    }

    .text-right {
      text-align: right
    }

    .text-xl {
      font-size: 1.25rem;
      line-height: 1.75rem
    }

    .text-sm {
      font-size: 0.875rem;
      line-height: 1.25rem
    }

    .font-semibold {
      font-weight: 600
    }

    .leading-relaxed {
      line-height: 1.625
    }

    .text-gray-600 {
      --tw-text-opacity: 1;
      color: rgb(75 85 99 / var(--tw-text-opacity))
    }

    .text-gray-900 {
      --tw-text-opacity: 1;
      color: rgb(17 24 39 / var(--tw-text-opacity))
    }

    .text-gray-500 {
      --tw-text-opacity: 1;
      color: rgb(107 114 128 / var(--tw-text-opacity))
    }

    .underline {
      -webkit-text-decoration-line: underline;
      text-decoration-line: underline
    }

    .antialiased {
      -webkit-font-smoothing: antialiased;
      -moz-osx-font-smoothing: grayscale
    }

    .shadow-2xl {
      --tw-shadow: 0 25px 50px -12px rgb(0 0 0 / 0.25);
      --tw-shadow-colored: 0 25px 50px -12px var(--tw-shadow-color);
      box-shadow: var(--tw-ring-offset-shadow, 0 0 #0000), var(--tw-ring-shadow, 0 0 #0000), var(--tw-shadow)
    }

    .shadow-gray-500\/20 {
      --tw-shadow-color: rgb(107 114 128 / 0.2);
      --tw-shadow: var(--tw-shadow-colored)
    }

    .transition-all {
      transition-property: all;
      transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
      transition-duration: 150ms
    }

    .selection\:bg-red-500 *::selection {
      --tw-bg-opacity: 1;
      background-color: rgb(239 68 68 / var(--tw-bg-opacity))
    }

    .selection\:text-white *::selection {
      --tw-text-opacity: 1;
      color: rgb(255 255 255 / var(--tw-text-opacity))
    }

    .selection\:bg-red-500::selection {
      --tw-bg-opacity: 1;
      background-color: rgb(239 68 68 / var(--tw-bg-opacity))
    }

    .selection\:text-white::selection {
      --tw-text-opacity: 1;
      color: rgb(255 255 255 / var(--tw-text-opacity))
    }

    .hover\:text-gray-900:hover {
      --tw-text-opacity: 1;
      color: rgb(17 24 39 / var(--tw-text-opacity))
    }

    .hover\:text-gray-700:hover {
      --tw-text-opacity: 1;
      color: rgb(55 65 81 / var(--tw-text-opacity))
    }

    .focus\:rounded-sm:focus {
      border-radius: 0.125rem
    }

    .focus\:outline:focus {
      outline-style: solid
    }

    .focus\:outline-2:focus {
      outline-width: 2px
    }

    .focus\:outline-red-500:focus {
      outline-color: #ef4444
    }

    .group:hover .group-hover\:stroke-gray-600 {
      stroke: #4b5563
    }

    .z-10 {
      z-index: 10
    }

    @media (prefers-reduced-motion: no-preference) {
      .motion-safe\:hover\:scale-\[1\.01\]:hover {
        --tw-scale-x: 1.01;
        --tw-scale-y: 1.01;
        transform: translate(var(--tw-translate-x), var(--tw-translate-y)) rotate(var(--tw-rotate)) skewX(var(--tw-skew-x)) skewY(var(--tw-skew-y)) scaleX(var(--tw-scale-x)) scaleY(var(--tw-scale-y))
      }
    }

    @media (prefers-color-scheme: dark) {
      .dark\:bg-gray-900 {
        --tw-bg-opacity: 1;
        background-color: rgb(17 24 39 / var(--tw-bg-opacity))
      }

      .dark\:bg-gray-800\/50 {
        background-color: rgb(31 41 55 / 0.5)
      }

      .dark\:bg-red-800\/20 {
        background-color: rgb(153 27 27 / 0.2)
      }

      .dark\:bg-dots-lighter {
        background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(255,255,255,0.07)'/%3E%3C/svg%3E")
      }

      .dark\:bg-gradient-to-bl {
        background-image: linear-gradient(to bottom left, var(--tw-gradient-stops))
      }

      .dark\:stroke-gray-600 {
        stroke: #4b5563
      }

      .dark\:text-gray-400 {
        --tw-text-opacity: 1;
        color: rgb(156 163 175 / var(--tw-text-opacity))
      }

      .dark\:text-white {
        --tw-text-opacity: 1;
        color: rgb(255 255 255 / var(--tw-text-opacity))
      }

      .dark\:shadow-none {
        --tw-shadow: 0 0 #0000;
        --tw-shadow-colored: 0 0 #0000;
        box-shadow: var(--tw-ring-offset-shadow, 0 0 #0000), var(--tw-ring-shadow, 0 0 #0000), var(--tw-shadow)
      }

      .dark\:ring-1 {
        --tw-ring-offset-shadow: var(--tw-ring-inset) 0 0 0 var(--tw-ring-offset-width) var(--tw-ring-offset-color);
        --tw-ring-shadow: var(--tw-ring-inset) 0 0 0 calc(1px + var(--tw-ring-offset-width)) var(--tw-ring-color);
        box-shadow: var(--tw-ring-offset-shadow), var(--tw-ring-shadow), var(--tw-shadow, 0 0 #0000)
      }

      .dark\:ring-inset {
        --tw-ring-inset: inset
      }

      .dark\:ring-white\/5 {
        --tw-ring-color: rgb(255 255 255 / 0.05)
      }

      .dark\:hover\:text-white:hover {
        --tw-text-opacity: 1;
        color: rgb(255 255 255 / var(--tw-text-opacity))
      }

      .group:hover .dark\:group-hover\:stroke-gray-400 {
        stroke: #9ca3af
      }
    }

    @media (min-width: 640px) {
      .sm\:fixed {
        position: fixed
      }

      .sm\:top-0 {
        top: 0px
      }

      .sm\:right-0 {
        right: 0px
      }

      .sm\:ml-0 {
        margin-left: 0px
      }

      .sm\:flex {
        display: flex
      }

      .sm\:items-center {
        align-items: center
      }

      .sm\:justify-center {
        justify-content: center
      }

      .sm\:justify-between {
        justify-content: space-between
      }

      .sm\:text-left {
        text-align: left
      }

      .sm\:text-right {
        text-align: right
      }
    }

    @media (min-width: 768px) {
      .md\:grid-cols-2 {
        grid-template-columns: repeat(2, minmax(0, 1fr))
      }
    }

    @media (min-width: 1024px) {
      .lg\:gap-8 {
        gap: 2rem
      }

      .lg\:p-8 {
        padding: 2rem
      }
    }
  </style>
  @vite('resources/css/app.css')
  @vite('resources/js/app.js')
</head>
@include('layouts.guest_navigation')

<body class="bg-dark-blue-win overflow-x-hidden">
  <style>
    #cropperModal {
        display: none;
        position: fixed;
        z-index: 999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.5);
    }
    #cropperModal .modal-content {
        margin: 15% auto;
        padding: 20px;
        background: white;
        width: 80%;
        max-width: 600px;
    }
    .cropper-container {
        max-height: 400px;
        width: 100%;
    }
</style>
  <!-- Stepper -->
  <div data-hs-stepper>
    <!-- Stepper Nav -->
    <ul class="relative flex flex-row gap-x-2 items-center mt-4">
      <li class="flex items-center gap-x-2 shrink basis-0 flex-1 group" data-hs-stepper-nav-item='{"index": 1}'>
        <span class="min-w-7 min-h-7 group inline-flex items-center text-xs align-middle mx-auto">
          <span
            class="size-7 flex justify-center items-center flex-shrink-0 bg-gray-100 font-medium text-gray-800 rounded-full group-focus:bg-gray-200 dark:bg-gray-700 dark:text-white dark:group-focus:bg-gray-600 hs-stepper-active:bg-pink-win hs-stepper-active:text-white hs-stepper-success:bg-pink-win hs-stepper-success:text-white hs-stepper-completed:bg-pink-win hs-stepper-completed:group-focus:bg-pink-win">
            <span class="hs-stepper-success:hidden hs-stepper-completed:hidden">1</span>
            <svg class="hidden flex-shrink-0 size-3 hs-stepper-success:block" xmlns="http://www.w3.org/2000/svg"
              width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"
              stroke-linecap="round" stroke-linejoin="round">
              <polyline points="20 6 9 17 4 12" />
            </svg>
          </span>
          <span class="ms-2 text-sm font-medium text-white">
            Your Wedding
          </span>
        </span>
      </li>

      <li class="flex items-center gap-x-2 shrink basis-0 flex-1 group" data-hs-stepper-nav-item='{"index": 2}'>
        <span class="min-w-7 min-h-7 group inline-flex items-center text-xs align-middle mx-auto">
          <span
            class="size-7 flex justify-center items-center flex-shrink-0 bg-gray-100 font-medium text-gray-800 rounded-full group-focus:bg-gray-200 dark:bg-gray-700 dark:text-white dark:group-focus:bg-gray-600 hs-stepper-active:bg-pink-win hs-stepper-active:text-white hs-stepper-success:bg-pink-win hs-stepper-success:text-white hs-stepper-completed:bg-pink-win hs-stepper-completed:group-focus:bg-pink-win">
            <span class="hs-stepper-success:hidden hs-stepper-completed:hidden">2</span>
            <svg class="hidden flex-shrink-0 size-3 hs-stepper-success:block" xmlns="http://www.w3.org/2000/svg"
              width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"
              stroke-linecap="round" stroke-linejoin="round">
              <polyline points="20 6 9 17 4 12" />
            </svg>
          </span>
          <span class="ms-2 text-sm font-medium text-white">
            Your Profile
          </span>
        </span>
      </li>

      <li class="flex items-center gap-x-2 shrink basis-0 flex-1 group" data-hs-stepper-nav-item='{"index": 3}'>
        <span class="min-w-7 min-h-7 group inline-flex items-center text-xs align-middle mx-auto">
          <span
            class="size-7 flex justify-center items-center flex-shrink-0 bg-gray-100 font-medium text-gray-800 rounded-full group-focus:bg-gray-200 dark:bg-gray-700 dark:text-white dark:group-focus:bg-gray-600 hs-stepper-active:bg-pink-win hs-stepper-active:text-white hs-stepper-success:bg-pink-win hs-stepper-success:text-white hs-stepper-completed:bg-pink-win hs-stepper-completed:group-focus:bg-pink-win">
            <span class="hs-stepper-success:hidden hs-stepper-completed:hidden">3</span>
            <svg class="hidden flex-shrink-0 size-3 hs-stepper-success:block" xmlns="http://www.w3.org/2000/svg"
              width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"
              stroke-linecap="round" stroke-linejoin="round">
              <polyline points="20 6 9 17 4 12" />
            </svg>
          </span>
          <span class="ms-2 text-sm font-medium text-white">
            Connect
          </span>
        </span>
      </li>
    </ul>
    <div class="mt-5 sm:mt-8">
      <div data-hs-stepper-content-item='{
              "index": 1
            }'>
        <div
          class="p-4 h-auto bg-gray-50 flex justify-center items-center border border-dashed border-gray-200 rounded-xl">
          <div class="overflow-hidden">
            <div class="mx-auto px-4 sm:px-6 lg:px-8 pb-16 pt-4">
              <div class="relative mx-auto grid space-y-5 sm:space-y-10">
                <div class="text-center">
                  <h1 class="text-3xl text-white font-bold sm:text-5xl lg:text-5xl lg:leading-tight dark:text-gray-200">
                    Welcome to <span class="text-pink-win">Wedding Insiders Network</span>
                  </h1>
                  <p class="text-2xl sm:text-3xl font-bold text-white">Tell us about your wedding:</p>
                </div>
                <div class="md:grid md:grid-cols-9 md:gap-4">
                  <div class="col-span-4">
                    <div class="md:grid md:grid-cols-7 md:gap-4">
                      <div class="text-center my-auto col-span-2">
                        <h2 class="text-white text-center text-3xl font-bold">You</h2>
                      </div>
                      <div
                        class="mx-auto max-w-2xl sm:flex sm:space-x-3 p-3 bg-white border rounded-lg shadow-lg shadow-gray-100 dark:bg-slate-900 dark:border-gray-700 dark:shadow-gray-900/[.2] col-span-5">
                        <div class="pb-2 sm:pb-0 sm:flex-[1_0_0%]">
                          <label for="hs-first-name" class="block text-sm font-medium dark:text-white"><span
                              class="sr-only">First name</span></label>
                          <input type="text" id="hs-first-name"
                            class="py-3 px-4 block w-full border-transparent rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-slate-900 dark:border-transparent dark:text-gray-400 dark:focus:ring-gray-600"
                            placeholder="First name">
                        </div>
                        <div
                          class="pt-2 sm:pt-0 sm:ps-3 border-t border-gray-200 sm:border-t-0 sm:border-s sm:flex-[1_0_0%] dark:border-gray-700">
                          <label for="hs-last-name" class="block text-sm font-medium dark:text-white"><span
                              class="sr-only">Last name</span></label>
                          <input type="text" id="hs-last-name"
                            class="py-3 px-4 block w-full border-transparent rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-slate-900 dark:border-transparent dark:text-gray-400 dark:focus:ring-gray-600"
                            placeholder="Last name">
                        </div>
                      </div>
                    </div>

                    <div
                      class="flex items-center text-xs text-white uppercase before:flex-[1_1_0%] before:border-t before:border-white before:me-6 after:flex-[1_1_0%] after:border-t after:white after:ms-6 dark:text-gray-500 dark:before:border-gray-600 dark:after:border-gray-600 py-4">
                      &</div>
                    <div class="md:grid md:grid-cols-7 md:gap-4">
                      <div class="text-center my-auto col-span-2">
                        <h2 class="text-pink-win text-center text-3xl font-bold">Fiance</h2>
                      </div>
                      <div
                        class="mx-auto max-w-2xl sm:flex sm:space-x-3 p-3 bg-white border rounded-lg shadow-lg shadow-gray-100 dark:bg-slate-900 dark:border-gray-700 dark:shadow-gray-900/[.2] col-span-5">
                        <div class="pb-2 sm:pb-0 sm:flex-[1_0_0%]">
                          <label for="hs-fiance-first-name" class="block text-sm font-medium dark:text-white"><span
                              class="sr-only">First name</span></label>
                          <input type="text" id="hs-fiance-first-name"
                            class="py-3 px-4 block w-full border-transparent rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-slate-900 dark:border-transparent dark:text-gray-400 dark:focus:ring-gray-600"
                            placeholder="First name">
                        </div>
                        <div
                          class="pt-2 sm:pt-0 sm:ps-3 border-t border-gray-200 sm:border-t-0 sm:border-s sm:flex-[1_0_0%] dark:border-gray-700">
                          <label for="hs-fiance-last-name" class="block text-sm font-medium dark:text-white"><span
                              class="sr-only">Last name</span></label>
                          <input type="text" id="hs-fiance-last-name"
                            class="py-3 px-4 block w-full border-transparent rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-slate-900 dark:border-transparent dark:text-gray-400 dark:focus:ring-gray-600"
                            placeholder="Last name">
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-span-1">

                  </div>
                  <div class="col-span-4">
                    <div class="sm:col-span-4">
                      <label for="client-wedding-date"
                        class="inline-block text-3xl font-bold mb-2 text-white dark:text-gray-200">
                        Wedding Date
                      </label>
                    </div>
                    <div class="sm:col-span-8">
                      <input id="client-wedding-date"
                        class="peer h-full w-full rounded-[7px] border border-blue-gray-200 border-t-transparent bg-transparent px-3 py-2.5 font-sans text-sm font-normal text-blue-gray-700 outline outline-0 transition-all placeholder-shown:border placeholder-shown:border-blue-gray-200 placeholder-shown:border-t-blue-gray-200 focus:border-2 focus:border-gray-900 focus:border-t-transparent focus:outline-0 disabled:border-0 disabled:bg-blue-gray-50"
                        placeholder="Select a date" />
                    </div>
                    <div class="sm:col-span-4">
                      <label for="client-venue"
                        class="inline-block text-3xl font-bold mb-2 text-white mt-2.5 dark:text-gray-200">
                        Wedding Venue
                      </label>
                    </div>
                    <div class="sm:col-span-8">
                      <input id="client-venue" type="text"
                        class="py-2 px-3 pe-11 block w-full border-gray-200 shadow-sm text-sm rounded-lg focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600"
                        placeholder="Location">
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- End First Contnet -->

      <!-- First Contnet -->
      <div data-hs-stepper-content-item='{
              "index": 2
            }' style="display: none;">
        <div class="h-auto bg-gray-50 flex justify-center items-center text-white">
          <!-- Card Section -->
          <div class="px-4 w-full sm:px-6 lg:px-8 mx-auto">
            <form>
              <!-- Card -->
              <div class="bg-white rounded-xl shadow dark:bg-slate-900">
                <div class="relative h-24 rounded-t-xl bg-pink-win bg-no-repeat bg-cover bg-center">
                </div>

                <div class="pt-0 p-4 sm:pt-0 sm:p-7">
                  <!-- Grid -->
                  <div class="space-y-4 sm:space-y-6">
                    <div>
                      <label class="sr-only">
                        Product photo
                      </label>

                      <div class="grid sm:flex sm:items-center sm:gap-x-5">
                        <img id="profileImagePreview"
                          class="-mt-8 relative z-10 inline-block size-24 mx-auto sm:mx-0 rounded-full ring-4 ring-white dark:ring-gray-800"
                          src="/images/profile.jpg" alt="Profile Picture">

                        <div class="mt-4 sm:mt-auto sm:mb-1.5 flex justify-center sm:justify-start gap-2">
                          <button id="uploadImageButton" type="button"
                            class="py-2 px-3 inline-flex items-center gap-x-2 text-sm text-dark-grey-win font-medium rounded-lg border border-gray-200 bg-white shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-white dark:hover:bg-gray-800 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                            <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                              viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                              stroke-linecap="round" stroke-linejoin="round">
                              <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                              <polyline points="17 8 12 3 7 8" />
                              <line x1="12" x2="12" y1="3" y2="15" />
                            </svg>
                            Upload Profile Picture
                          </button>
                          <input id="imageUpload" type="file" accept=".png,.jpg,.jpeg" hidden />
    
                          <div id="cropperModal">
                              <div class="modal-content">
                                  <div>
                                      <img id="image" style="max-width: 100%;" />
                                  </div>
                                  <button type="button" id="cropButton">Crop</button>
                                  <button type="button" id="cancelButton">Cancel</button>
                              </div>
                          </div>
                          
                          <canvas id="croppedCanvas" style="display: none;"></canvas>
                          <button type="button"
                            class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-red shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:hover:bg-gray-800 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                            Delete
                          </button>
                        </div>
                      </div>
                      <div class="mt-4 bg-red text-sm text-white rounded-lg p-2 my-2" role="alert" id="badImageAlert"
                        hidden>
                        <ul>
                          <li>Unsupported file type. Please upload .png, .jpg, or .jpeg</li>
                        </ul>
                      </div>
                      <div class="mt-4 bg-green text-sm text-white rounded-lg p-2 my-2" role="alert" id="goodImageAlert"
                        hidden>
                        <ul>
                          <li>Uploaded: <span id="imageUploadName"></span></li>
                        </ul>
                      </div>
                    </div>

                    <div class="space-y-2">
                      <div class="mx-auto">
                        <div class="mt-4 text-dark-grey-win">
                          <label for="password_first" class="text-dark-grey-win">Password <span
                              class="text-red">*</span></label>
                          <input id="password_first" class="block mt-1 w-full rounded-lg" type="password"
                            name="password" required autocomplete="new-password">
                        </div>
                        <div class="mt-4 text-dark-grey-win">
                          <label for="password_confirmation" class="text-dark-grey-win">Confirm Password <span
                              class="text-red">*</span></label>
                          <input id="password_confirmation" class="block mt-1 w-full rounded-lg" type="password"
                            name="password_confirmation" required autocomplete="new-password">
                        </div>
                      </div>
                    </div>

                    <div class="space-y-2">
                      <label for="af-submit-app-description"
                        class="inline-block font-medium text-dark-grey-win dark:text-gray-200">
                        Bio <span class="text-sm text-gray">(Optional)</span>
                      </label>

                      <textarea id="af-submit-app-description"
                        class="py-2 px-3 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600"
                        rows="4"
                        placeholder="Tell us about you and your wedding! This information will appear on your profile page."></textarea>
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
      <div data-hs-stepper-content-item='{
              "index": 3
            }' style="display: none;">
        <div class="p-4 h-auto bg-gray-50 justify-center items-center border border-dashed border-gray-200 rounded-xl">
          <div class="md:grid md:grid-cols-2">
            <div>
              <h1 class="text-3xl text-white mb-2 text-center font-bold">Referred by:</h1>
              <div
                class="flex flex-col rounded-xl p-4 md:p-6 bg-white border border-gray-200 dark:bg-slate-900 dark:border-gray-700">
                <div class="flex items-center gap-x-4">
                  <img class="rounded-full size-20" src="/images/profile.jpg" alt="Profile Picture">
                  <div class="grow">
                    <h3 class="font-medium text-gray-800 dark:text-gray-200">
                      {{ $vendor->first_name }} {{ $vendor->last_name }}
                    </h3>
                    <p class="text-xs uppercase text-gray-500">
                      {{ $vendor->type }}
                    </p>
                  </div>
                </div>

                <p class="mt-3 text-gray-500">
                  (bio)
                </p>
              </div>
            </div>
            <div>
              <h1 class="text-3xl text-white mb-2 text-center font-bold">What vendors are you looking to connect with?
              </h1>
              <br>
              <div class="flex justify-center items-center">
                <ul class="max-w-sm flex flex-col">
                  <li
                    class="inline-flex items-center gap-x-2 py-3 px-4 text-sm font-medium bg-white border text-gray-800 -mt-px first:rounded-t-lg first:mt-0 last:rounded-b-lg dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                    <div class="relative flex items-start w-full">
                      <div class="flex items-center h-5">
                        <input id="hs-bakery" name="hs-bakery" type="checkbox"
                          class="border-gray-200 text-pink-win accent-pink-win rounded disabled:opacity-50 dark:bg-gray-800 dark:border-gray-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800">
                      </div>
                      <label for="hs-bakery" class="ms-3.5 block w-full text-sm text-gray-600 dark:text-gray-500">
                        Bakery/Cake
                      </label>
                    </div>
                  </li>

                  <li
                    class="inline-flex items-center gap-x-2 py-3 px-4 text-sm font-medium bg-white border text-gray-800 -mt-px first:rounded-t-lg first:mt-0 last:rounded-b-lg dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                    <div class="relative flex items-start w-full">
                      <div class="flex items-center h-5">
                        <input id="hs-caterer" name="hs-caterer" type="checkbox"
                          class="border-gray-200 text-pink-win accent-pink-win rounded disabled:opacity-50 dark:bg-gray-800 dark:border-gray-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800">
                      </div>
                      <label for="hs-caterer" class="ms-3.5 block w-full text-sm text-gray-600 dark:text-gray-500">
                        Caterer
                      </label>
                    </div>
                  </li>

                  <li
                    class="inline-flex items-center gap-x-2 py-3 px-4 text-sm font-medium bg-white border text-gray-800 -mt-px first:rounded-t-lg first:mt-0 last:rounded-b-lg dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                    <div class="relative flex items-start w-full">
                      <div class="flex items-center h-5">
                        <input id="hs-dj" name="hs-dj" type="checkbox"
                          class="border-gray-200 text-pink-win accent-pink-win rounded disabled:opacity-50 dark:bg-gray-800 dark:border-gray-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800">
                      </div>
                      <label for="hs-dj" class="ms-3.5 block w-full text-sm text-gray-600 dark:text-gray-500">
                        DJ/Live Band
                      </label>
                    </div>
                  </li>
                  <li
                    class="inline-flex items-center gap-x-2 py-3 px-4 text-sm font-medium bg-white border text-gray-800 -mt-px first:rounded-t-lg first:mt-0 last:rounded-b-lg dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                    <div class="relative flex items-start w-full">
                      <div class="flex items-center h-5">
                        <input id="hs-florist" name="hs-florist" type="checkbox"
                          class="border-gray-200 text-pink-win accent-pink-win rounded disabled:opacity-50 dark:bg-gray-800 dark:border-gray-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800">
                      </div>
                      <label for="hs-florist" class="ms-3.5 block w-full text-sm text-gray-600 dark:text-gray-500">
                        Florist
                      </label>
                    </div>
                  </li>
                  <li
                    class="inline-flex items-center gap-x-2 py-3 px-4 text-sm font-medium bg-white border text-gray-800 -mt-px first:rounded-t-lg first:mt-0 last:rounded-b-lg dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                    <div class="relative flex items-start w-full">
                      <div class="flex items-center h-5">
                        <input id="hs-hair" name="hs-hair" type="checkbox"
                          class="border-gray-200 text-pink-win accent-pink-win rounded disabled:opacity-50 dark:bg-gray-800 dark:border-gray-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800">
                      </div>
                      <label for="hs-hair" class="ms-3.5 block w-full text-sm text-gray-600 dark:text-gray-500">
                        Hair & Makeup
                      </label>
                    </div>
                  </li>
                  <li
                    class="inline-flex items-center gap-x-2 py-3 px-4 text-sm font-medium bg-white border text-gray-800 -mt-px first:rounded-t-lg first:mt-0 last:rounded-b-lg dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                    <div class="relative flex items-start w-full">
                      <div class="flex items-center h-5">
                        <input id="hs-officiant" name="hs-officiant" type="checkbox"
                          class="border-gray-200 text-pink-win accent-pink-win rounded disabled:opacity-50 dark:bg-gray-800 dark:border-gray-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800">
                      </div>
                      <label for="hs-officiant" class="ms-3.5 block w-full text-sm text-gray-600 dark:text-gray-500">
                        Officiant
                      </label>
                    </div>
                  </li>
                </ul>
                <ul class="max-w-sm flex flex-col">
                  <li
                    class="inline-flex items-center gap-x-2 py-3 px-4 text-sm font-medium bg-white border text-gray-800 -mt-px first:rounded-t-lg first:mt-0 last:rounded-b-lg dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                    <div class="relative flex items-start w-full">
                      <div class="flex items-center h-5">
                        <input id="hs-photographer" name="hs-photographer" type="checkbox"
                          class="border-gray-200 text-pink-win accent-pink-win rounded disabled:opacity-50 dark:bg-gray-800 dark:border-gray-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800">
                      </div>
                      <label for="hs-photographer" class="ms-3.5 block w-full text-sm text-gray-600 dark:text-gray-500">
                        Photographer
                      </label>
                    </div>
                  </li>
                  <li
                    class="inline-flex items-center gap-x-2 py-3 px-4 text-sm font-medium bg-white border text-gray-800 -mt-px first:rounded-t-lg first:mt-0 last:rounded-b-lg dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                    <div class="relative flex items-start w-full">
                      <div class="flex items-center h-5">
                        <input id="hs-rentals" name="hs-rentals" type="checkbox"
                          class="border-gray-200 text-pink-win accent-pink-win rounded disabled:opacity-50 dark:bg-gray-800 dark:border-gray-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800">
                      </div>
                      <label for="hs-rentals" class="ms-3.5 block w-full text-sm text-gray-600 dark:text-gray-500">
                        Rentals
                      </label>
                    </div>
                  </li>
                  <li
                    class="inline-flex items-center gap-x-2 py-3 px-4 text-sm font-medium bg-white border text-gray-800 -mt-px first:rounded-t-lg first:mt-0 last:rounded-b-lg dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                    <div class="relative flex items-start w-full">
                      <div class="flex items-center h-5">
                        <input id="hs-transportation" name="hs-transportation" type="checkbox"
                          class="border-gray-200 text-pink-win accent-pink-win rounded disabled:opacity-50 dark:bg-gray-800 dark:border-gray-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800">
                      </div>
                      <label for="hs-transportation"
                        class="ms-3.5 block w-full text-sm text-gray-600 dark:text-gray-500">
                        Transportation
                      </label>
                    </div>
                  </li>
                  <li
                    class="inline-flex items-center gap-x-2 py-3 px-4 text-sm font-medium bg-white border text-gray-800 -mt-px first:rounded-t-lg first:mt-0 last:rounded-b-lg dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                    <div class="relative flex items-start w-full">
                      <div class="flex items-center h-5">
                        <input id="hs-venue" name="hs-venue" type="checkbox"
                          class="border-gray-200 text-pink-win accent-pink-win rounded disabled:opacity-50 dark:bg-gray-800 dark:border-gray-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800">
                      </div>
                      <label for="hs-venue" class="ms-3.5 block w-full text-sm text-gray-600 dark:text-gray-500">
                        Venue
                      </label>
                    </div>
                  </li>
                  <li
                    class="inline-flex items-center gap-x-2 py-3 px-4 text-sm font-medium bg-white border text-gray-800 -mt-px first:rounded-t-lg first:mt-0 last:rounded-b-lg dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                    <div class="relative flex items-start w-full">
                      <div class="flex items-center h-5">
                        <input id="hs-wedding-planner" name="hs-wedding-planner" type="checkbox"
                          class="border-gray-200 text-pink-win accent-pink-win rounded disabled:opacity-50 dark:bg-gray-800 dark:border-gray-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800">
                      </div>
                      <label for="hs-wedding-planner"
                        class="ms-3.5 block w-full text-sm text-gray-600 dark:text-gray-500">
                        Wedding Planner
                      </label>
                    </div>
                  </li>
                  <li
                    class="inline-flex items-center gap-x-2 py-3 px-4 text-sm font-medium bg-white border text-gray-800 -mt-px first:rounded-t-lg first:mt-0 last:rounded-b-lg dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                    <div class="relative flex items-start w-full">
                      <div class="flex items-center h-5">
                        <input id="hs-videographer" name="hs-videographer" type="checkbox"
                          class="border-gray-200 text-pink-win accent-pink-win rounded disabled:opacity-50 dark:bg-gray-800 dark:border-gray-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800">
                      </div>
                      <label for="hs-videographer" class="ms-3.5 block w-full text-sm dark:text-gray-500">
                        Videographer
                      </label>
                    </div>
                  </li>
                </ul>
              </div>
              <div class="max-w-1/2 dark:bg-gray-800 dark:border-gray-700 text-center flex justify-center align-middle">
                <label for="hs-meetups-near-you" class="flex p-4 md:p-5">
                  <span class="flex">
                    <svg class="flex-shrink-0 mt-1 size-5 text-white" xmlns="http://www.w3.org/2000/svg" width="24"
                      height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                      stroke-linecap="round" stroke-linejoin="round">
                      <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                      <circle cx="9" cy="7" r="4" />
                      <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                      <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                    </svg>
                    <span class="ms-5">
                      <span class="block text-white">Would you like our vendors to contact you directly?</span>
                    </span>
                  </span>
                  <input type="checkbox" id="hs-meetups-near-you"
                    class="relative w-[3.25rem] ml-4 h-7 text-pink-win accent-pink-win bg-gray-100 checked:bg-none checked:bg-blue-600 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 ring-1 ring-transparent focus:border-pink-win focus:ring-pink-win ring-offset-white focus:outline-none appearance-none  dark:bg-gray-700 dark:checked:bg-blue-600 dark:focus:ring-offset-gray-800
                      before:inline-block before:size-6 before:bg-white checked:before:bg-blue-200 before:translate-x-0 checked:before:translate-x-full before:shadow before:rounded-full before:transform before:ring-0 before:transition before:ease-in-out before:duration-200 dark:before:bg-gray-400 dark:checked:before:bg-blue-200" checked>
                </label>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="mt-5 flex justify-between items-center gap-x-2">
        <button id="btn-back" type="button"
          class="py-2 px-3 inline-flex items-center gap-x-1 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none"
          data-hs-stepper-back-btn>
          <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
            stroke-linejoin="round">
            <path d="m15 18-6-6 6-6" />
          </svg>
          Back
        </button>
        <button id="btn-next" type="button"
          class="py-2 px-3 inline-flex items-center gap-x-1 text-sm font-semibold rounded-lg border border-transparent bg-pink-win text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none"
          data-hs-stepper-next-btn>
          Next
          <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
            stroke-linejoin="round">
            <path d="m9 18 6-6-6-6" />
          </svg>
        </button>
        <button id="btn-finish-setup" type="button"
          class="py-2 px-3 inline-flex items-center gap-x-1 text-sm font-semibold rounded-lg border border-transparent bg-pink-win text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none"
          style="display: none;">
          Finish
        </button>
        <button type="reset"
          class="py-2 px-3 inline-flex items-center gap-x-1 text-sm font-semibold rounded-lg border border-transparent bg-pink-win text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none"
          data-hs-stepper-reset-btn style="display: none;">
          Reset
        </button>
      </div>
    </div>
  </div>
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <script>
    $("#uploadImageButton").on("click", () => {
      $("#imageUpload").trigger("click");
    });
    let file = document.getElementById('imageUpload');
    file.onchange = function (e) {
      let ext = this.value.match(/\.([^\.]+)$/)[1].toLowerCase();
      switch (ext) {
        case 'jpg':
        case 'jpeg':
        case 'png':
          $("#imageUploadName").html(e.target.files[0].name);
          $("#goodImageAlert").attr("hidden", false);
          let imagePreview = document.getElementById('profileImagePreview');
          imagePreview.src = URL.createObjectURL(event.target.files[0]);
          break;
        default:
          $("#badImageAlert").attr("hidden", false);
          this.value = '';
      }
    };
  </script>
  <script>
    @if($user != null)
      $("#hs-first-name").val("{!! $user->first_name !!}");
      $("#hs-last-name").val("{!! $user->last_name !!}");
      @if ($user -> fiance_first_name != null)
        $("#hs-fiance-first-name").val("{!! $user->fiance_first_name !!}");
      @endif
      @if ($user -> fiance_last_name != null)
        $("#hs-fiance-last-name").val("{!! $user->fiance_last_name !!}");
      @endif
      @if ($user -> wedding_location != null)
        $("#client-venue").val("{!! $user->wedding_location !!}");
      @endif
      @if ($user -> wedding_date != null)
        $("#client-wedding-date").val("{!! $user->wedding_date !!}");
      @endif
    @endif
    let page = 1;
    $("#btn-next").on("click", function () {
      page += 1;
      console.log("next page");
      if (page == 3) {
        if (($("#password_first").val() != $("#password_confirmation").val()) || $("#password_first").val() == "") {
          $("#btn-back").trigger("click");
          Swal.fire({
            title: 'Oops!',
            text: `Your passwords didn't match. Please try again!`,
            icon: 'error',
            confirmButtonText: 'Retry'
          });
        } else {
          $("#btn-finish-setup").css("display", "block");
        }
      }
    });
    $("#btn-back").on("click", function () {
      $("#btn-finish-setup").css("display", "none");
      page -= 1;
    });
    $("#btn-finish-setup").on("click", function () {
      let formData = {
        first_name: $("#hs-first-name").val(),
        last_name: $("#hs-last-name").val(),
        password: $("#password").val(),
        password_confirmation: $("#password_confirmation").val(),
        wedding_date: $("#client-wedding-date").val(),
        wedding_location: $("#client-venue").val(),
        id: {{ $user-> id }}
      };
      let userData = new FormData();
      userData.append('image', $("#imageUpload").prop('files')[0]);
      console.log(userData);
      function uploadImage(){
        $.ajax({
          type: "POST",
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          url: "/user/upload/image",
          enctype: 'multipart/form-data',
          contentType: false,
          data: userData,
          processData: false,
          success: function (data) {
            window.location = "/dashboard";
          }
        });
      }

      $.ajax({
        type: "POST",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: "/ref/user/register",
        data: formData,

        success: function (data) {
          console.log("registered user");
          uploadImage();
        }
      });
    });
  </script>
</body>
<script>
  $("#client-wedding-date").flatpickr({});
</script>

</html>