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
    <strong>Success!</strong> You have invited a new client.
  </div>
@endisset

<div class="cc-refer-page__card">
  <form
    id="cc-refer-form"
    method="POST"
    action="{{ route('create.client.invite') }}"
    class="cc-refer-form"
    novalidate
  >
    @csrf

    <div class="cc-refer-form__group cc-refer-form__row--names">
      <div class="cc-refer-form__name-block">
        <label class="cc-refer-form__label">
          Full Name<span class="cc-refer-form__req" aria-hidden="true">*</span>
        </label>
        <div class="cc-refer-form__pair">
          <input
            type="text"
            name="first_name"
            class="cc-refer-form__input"
            placeholder="First Name"
            value="{{ old('first_name') }}"
            required
            autocomplete="given-name"
          />
          <input
            type="text"
            name="last_name"
            class="cc-refer-form__input"
            placeholder="Last Name"
            value="{{ old('last_name') }}"
            required
            autocomplete="family-name"
          />
        </div>
      </div>

      <div class="cc-refer-form__name-block">
        <label class="cc-refer-form__label">
          Fiance Name<span class="cc-refer-form__req" aria-hidden="true">*</span>
        </label>
        <div class="cc-refer-form__pair">
          <input
            type="text"
            name="fiance_first_name"
            class="cc-refer-form__input"
            placeholder="First Name"
            value="{{ old('fiance_first_name') }}"
            required
            autocomplete="off"
          />
          <input
            type="text"
            name="fiance_last_name"
            class="cc-refer-form__input"
            placeholder="Last Name"
            value="{{ old('fiance_last_name') }}"
            autocomplete="off"
          />
        </div>
      </div>
    </div>

    <div class="cc-refer-form__group">
      <label class="cc-refer-form__label" for="cc-refer-email">
        Email<span class="cc-refer-form__req" aria-hidden="true">*</span>
      </label>
      <input
        type="email"
        id="cc-refer-email"
        name="email"
        class="cc-refer-form__input"
        placeholder="email@example.com"
        value="{{ old('email') }}"
        required
        autocomplete="email"
      />
    </div>

    <div class="cc-refer-form__group cc-refer-form__group--split">
      <div class="cc-refer-form__field">
        <label class="cc-refer-form__label" for="cc-refer-wedding-date">Wedding Date</label>
        <input
          type="text"
          id="cc-refer-wedding-date"
          name="wedding_date"
          class="cc-refer-form__input"
          placeholder="MM/DD/YYYY"
          value="{{ old('wedding_date') }}"
          autocomplete="off"
        />
      </div>
      <div class="cc-refer-form__field">
        <label class="cc-refer-form__label" for="cc-refer-venue">Wedding Venue</label>
        <div class="cc-refer-form__select-wrap">
          <input
            type="text"
            id="cc-refer-venue"
            name="venue"
            class="cc-refer-form__input cc-refer-form__input--select"
            placeholder="City"
            value="{{ old('venue') }}"
            autocomplete="off"
          />
          <span class="cc-refer-form__select-chevron" aria-hidden="true">▾</span>
        </div>
      </div>
    </div>

    <div class="cc-refer-form__group">
      <div class="cc-refer-form__label-row">
        <label class="cc-refer-form__label" for="cc-refer-note">Personal Note (Optional)</label>
        <span class="cc-refer-form__hint" id="cc-refer-note-count">MAX 250 CHARACTERS</span>
      </div>
      <textarea
        id="cc-refer-note"
        name="personal_note"
        class="cc-refer-form__textarea"
        rows="4"
        maxlength="250"
        placeholder="Write A Custom Message Here..."
      >{{ old('personal_note') }}</textarea>
    </div>

    <div class="cc-refer-form__submit-wrap">
      <button type="submit" class="cc-refer-form__submit">Add Client</button>
    </div>
  </form>
</div>
