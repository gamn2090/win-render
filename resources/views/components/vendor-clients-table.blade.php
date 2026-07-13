@props([
  'rows' => [],
  'emptyMessage' => 'No clients yet.',
])

<div class="cc-table-wrap">
  <table class="cc-table">
    <thead>
      <tr>
        <th scope="col" class="cc-table__th cc-table__th--status">Status</th>
        <th scope="col" class="cc-table__th cc-table__th--name">Name</th>
        <th scope="col" class="cc-table__th cc-table__th--location">Wedding Location</th>
        <th scope="col" class="cc-table__th cc-table__th--date">Wedding Date</th>
        <th scope="col" class="cc-table__th cc-table__th--action">Action</th>
      </tr>
    </thead>
    <tbody>
      @forelse($rows as $row)
        <tr class="cc-table__row">
          <td class="cc-table__cell cc-table__cell--status">
            <span class="cc-status {{ ($row['is_active'] ?? true) ? '' : 'cc-status--archived' }}" role="status">
              <span class="cc-status__dot" aria-hidden="true"></span>
              {{ $row['status_label'] ?? 'Active' }}
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
              <span class="cc-person__name">
                {{ $row['first_name'] }}
                @if(!empty($row['fiance_name']))
                  <span class="cc-person__name-heart" aria-hidden="true">♥</span>
                  {{ $row['fiance_name'] }}
                @endif
              </span>
            </div>
          </td>
          <td class="cc-table__cell">
            <span class="cc-table__text">{{ $row['wedding_location'] }}</span>
          </td>
          <td class="cc-table__cell">
            <span class="cc-table__text">{{ $row['wedding_date'] }}</span>
          </td>
          <td class="cc-table__cell cc-table__cell--action">
            <div class="cc-actions">
              <a href="{{ $row['action_url'] }}" class="cc-btn">{{ $row['action_label'] ?? 'Archive' }}</a>
              <a href="{{ $row['view_url'] }}" class="cc-btn">View</a>
            </div>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="5" class="cc-table__empty">{{ $emptyMessage }}</td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>
