@if ($errors->any())
  <div class="cc-refer-page__alert cc-refer-page__alert--error" role="alert">
    <ul class="cc-refer-page__alert-list">
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif

@isset($linkID)
  <div class="cc-refer-page__alert cc-refer-page__alert--success" role="status">
    <strong>Success!</strong> You have invited a new vendor.
  </div>
@endisset

<div class="cc-refer-page__card">
  <form
    id="cc-invite-vendor-form"
    method="POST"
    action="{{ route('create.vendor.invite') }}"
    class="cc-refer-form"
    novalidate
  >
    @csrf

    <div class="cc-refer-form__group cc-refer-form__row--vendor-names">
      <div class="cc-refer-form__field">
        <label class="cc-refer-form__label" for="cc-vendor-first-name">
          First Name<span class="cc-refer-form__req" aria-hidden="true">*</span>
        </label>
        <input
          type="text"
          id="cc-vendor-first-name"
          name="first_name"
          class="cc-refer-form__input"
          placeholder="First Name"
          value="{{ old('first_name') }}"
          required
          autocomplete="given-name"
        />
      </div>
      <div class="cc-refer-form__field">
        <label class="cc-refer-form__label" for="cc-vendor-last-name">Last Name</label>
        <input
          type="text"
          id="cc-vendor-last-name"
          name="last_name"
          class="cc-refer-form__input"
          placeholder="Last Name"
          value="{{ old('last_name') }}"
          autocomplete="family-name"
        />
      </div>
    </div>

    <div class="cc-refer-form__group">
      <label class="cc-refer-form__label" for="cc-vendor-email">
        Email<span class="cc-refer-form__req" aria-hidden="true">*</span>
      </label>
      <input
        type="email"
        id="cc-vendor-email"
        name="email"
        class="cc-refer-form__input"
        placeholder="email@example.com"
        value="{{ old('email') }}"
        required
        autocomplete="email"
      />
    </div>

    <div class="cc-refer-form__group">
      <div class="cc-refer-form__label-row">
        <label class="cc-refer-form__label" for="cc-vendor-note">Personal Note (Optional)</label>
        <span class="cc-refer-form__hint" id="cc-vendor-note-count">MAX 250 CHARACTERS</span>
      </div>
      <textarea
        id="cc-vendor-note"
        name="personal_note"
        class="cc-refer-form__textarea"
        rows="4"
        maxlength="250"
        placeholder="Write A Custom Message Here..."
      >{{ old('personal_note') }}</textarea>
    </div>

    <div class="cc-refer-form__submit-wrap">
      <button type="submit" class="cc-refer-form__submit cc-refer-form__submit--vendor">Add Vendor</button>
    </div>
  </form>
</div>
