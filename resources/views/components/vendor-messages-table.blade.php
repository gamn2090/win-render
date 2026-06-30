@props([
  'rows' => [],
  'emptyMessage' => 'No messages yet.',
  'showRoleColumn' => true,
])

<div class="vm-table-wrap">
  <table class="vm-table">
    <thead>
      <tr>
        @if($showRoleColumn)
          <th scope="col" class="vm-table__th vm-table__th--role">ROLE</th>
        @endif
        <th scope="col" class="vm-table__th vm-table__th--name">Name</th>
        <th scope="col" class="vm-table__th vm-table__th--message">Latest Message</th>
        <th scope="col" class="vm-table__th vm-table__th--action">Action</th>
      </tr>
    </thead>
    <tbody>
      @forelse($rows as $row)
        <tr
          class="vm-table__row"
          data-vm-convo="{{ $row['conversation_id'] }}"
          data-vm-meta='@json($row['view_meta'])'
          tabindex="0"
        >
          @if($showRoleColumn)
            <td class="vm-table__cell vm-table__cell--role">
              @if($row['show_role'])
                <span class="vm-role vm-role--{{ $row['role'] }}" role="status">{{ $row['role_label'] }}</span>
              @endif
            </td>
          @endif
          <td class="vm-table__cell vm-table__cell--name">
            <div class="vm-person">
              <img class="vm-person__avatar" src="{{ $row['avatar'] }}" alt="" width="48" height="48" />
              <div class="vm-person__text">
                <span class="vm-person__name">{{ $row['name'] }}</span>
                @if(!empty($row['subtitle']))
                  <span class="vm-person__subtitle">{{ $row['subtitle'] }}</span>
                @endif
              </div>
            </div>
          </td>
          <td class="vm-table__cell vm-table__cell--message">
            @if($row['sent_at'] !== '')
              <p class="vm-message__date">{{ $row['sent_at'] }}</p>
            @endif
            <p class="vm-message__preview">
              @if($row['has_unread'] || ($row['has_new_request'] ?? false))
                <span class="vm-message__dot" aria-hidden="true"></span>
              @endif
              <span class="vm-message__text">{{ $row['preview'] }}</span>
            </p>
          </td>
          <td class="vm-table__cell vm-table__cell--action">
            <button
              type="button"
              class="vm-view-btn"
              data-vm-view-btn
              data-vm-convo="{{ $row['conversation_id'] }}"
              data-vm-meta='@json($row['view_meta'])'
            >View</button>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="{{ $showRoleColumn ? 4 : 3 }}" class="vm-table__empty">{{ $emptyMessage }}</td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>
