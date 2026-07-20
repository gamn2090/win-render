@props([
  'rows' => [],
  'emptyMessage' => 'No timelines yet.',
])

<div class="cc-table-wrap">
  <table class="cc-table">
    <thead>
      <tr>
        <th scope="col" class="cc-table__th cc-table__th--name">Name</th>
        <th scope="col" class="cc-table__th cc-table__th--date">Last Updated</th>
        <th scope="col" class="cc-table__th cc-table__th--action">Action</th>
      </tr>
    </thead>
    <tbody>
      @forelse($rows as $row)
        <tr class="cc-table__row">
          <td class="cc-table__cell">
            <span class="cc-table__text">{{ $row->name }}</span>
          </td>
          <td class="cc-table__cell">
            <span class="cc-table__text">{{ $row->updated_at->format('M j, Y') }}</span>
          </td>
          <td class="cc-table__cell cc-table__cell--action">
            <div class="cc-actions">
              <a href="{{ route('vendor.timelines.show', $row->id) }}" class="cc-btn">Open</a>

              <form action="{{ route('vendor.timelines.rename', $row->id) }}" method="POST" class="cc-actions__inline-form">
                @csrf
                @method('PATCH')
                <input type="text" name="name" value="{{ $row->name }}" class="cc-actions__inline-input" required maxlength="190" />
                <button type="submit" class="cc-btn">Rename</button>
              </form>

              <form action="{{ route('vendor.timelines.destroy', $row->id) }}" method="POST" onsubmit="return confirm('Delete this timeline? This cannot be undone.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="cc-btn">Delete</button>
              </form>
            </div>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="3" class="cc-table__empty">{{ $emptyMessage }}</td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>
