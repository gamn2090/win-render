@props(['model'])

@php
  $isCouple = $model instanceof \App\Models\User;
  $personName = $isCouple
    ? trim((string) ($model->first_name ?? '') . ' ' . (string) ($model->fiance_first_name ?? ''))
    : trim((string) ($model->first_name ?? '') . ' ' . (string) ($model->last_name ?? ''));
  $displayName = trim((string) ($model->business_name ?? '')) ?: $personName;

  // This is a personal profile-picture avatar (headshot), not the vendor's
  // storefront cover photo — those are two different things. Cards that
  // need the storefront cover call $vendor->coverImageUrl() directly.
  $photoUrl = \App\Support\ProfileImageStorage::hasCustomImage($model->image ?? null)
    ? \App\Support\ProfileImageStorage::url($model->image)
    : null;
@endphp

@if($photoUrl)
  <img
    src="{{ $photoUrl }}"
    alt="{{ $displayName }}"
    {{ $attributes->class(['win-avatar-img']) }}
  />
@else
  @php
    [$avatarBg, $avatarFg] = \App\Support\AvatarPalette::colorFor(get_class($model) . ':' . $model->id);
    $initials = $isCouple
      ? \App\Support\AvatarPalette::coupleInitials($model->first_name ?? '', $model->fiance_first_name ?? '')
      : \App\Support\AvatarPalette::initials($personName);
  @endphp
  <span
    {{ $attributes->class(['win-avatar-fallback']) }}
    style="background:{{ $avatarBg }}; color:{{ $avatarFg }};"
    aria-hidden="true"
  >{{ $initials }}</span>
@endif
