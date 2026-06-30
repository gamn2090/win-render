@props([
  'rows' => [],
  'emptyMessage' => 'No connected vendors yet. Use Add Vendor to invite your first peer.',
])

<div class="cc-table-wrap">
  <table class="cc-table vn-table">
    <thead>
      <tr>
        <th scope="col" class="cc-table__th vn-table__th--type">Type</th>
        <th scope="col" class="cc-table__th vn-table__th--name">Name</th>
        <th scope="col" class="cc-table__th vn-table__th--location">Location</th>
        <th scope="col" class="cc-table__th vn-table__th--action">Action</th>
      </tr>
    </thead>
    <tbody>
      @forelse($rows as $row)
        <tr class="cc-table__row">
          <td class="cc-table__cell vn-table__cell--type">
            <span class="vn-type">
              @if(!empty($row['type_icon']))
                <img
                  class="vn-type__icon"
                  src="{{ $row['type_icon'] }}"
                  alt=""
                  width="18"
                  height="18"
                />
              @endif
              <span class="vn-type__label">{{ $row['type_label'] }}</span>
            </span>
          </td>
          <td class="cc-table__cell">
            <div class="cc-person">
              <img
                class="cc-person__avatar"
                src="{{ $row['avatar'] }}"
                alt=""
                width="48"
                height="48"
              />
              <span class="cc-person__name">{{ $row['business_name'] }}</span>
            </div>
          </td>
          <td class="cc-table__cell">
            <span class="cc-table__text">{{ $row['location'] }}</span>
          </td>
          <td class="cc-table__cell cc-table__cell--action">
            <div class="cc-actions vn-actions">
              <form
                method="POST"
                action="{{ $row['remove_url'] }}"
                class="vn-actions__form"
                onsubmit="return confirm('Remove this vendor connection?');"
              >
                @csrf
                <input type="hidden" name="aff_vendor" value="{{ $row['aff_vendor_id'] }}" />
                <button type="submit" class="cc-btn cc-btn--remove">Remove Connection</button>
              </form>
              <a href="{{ $row['view_url'] }}" class="cc-btn cc-btn--view">View</a>
            </div>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="4" class="cc-table__empty">{{ $emptyMessage }}</td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>
