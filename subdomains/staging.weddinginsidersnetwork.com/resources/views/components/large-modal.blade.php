<div id="{{ $id }}-modal" class="hs-overlay hs-overlay-backdrop-open:bg-black/90 hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none" role="dialog" tabindex="-1" aria-labelledby="{{ $id }}-modal-label">
  <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto min-h-[calc(100%-3.5rem)] flex items-center">
    <div class="w-full flex flex-col bg-white shadow-sm rounded-xl pointer-events-auto">
      <div class="block bg-win-blue py-1 rounded-t-lg text-right"><button id="{{ $id }}-modal-close-btn" class="text-white mx-3" type="button" aria-label="Close" data-hs-overlay="#{{ $id }}-modal"><i class="fa-solid fa-xmark"></i></button></div>
      <div class="p-4 overflow-y-auto">
        {{ $slot }}
      </div>
    </div>
  </div>
</div>