@forelse($vendors as $vendor)
  <x-couple-vendor-card :vendor="$vendor" :show-badges="true" />
@empty
  <p style="grid-column:1/-1;text-align:center;color:#7a7a7a;">No vendors match this filter.</p>
@endforelse
