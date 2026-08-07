<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>{{ $coupleName }} Timeline</title>
<style>
  /* dompdf only supports a subset of CSS (no grid, limited flexbox) —
     this mirrors the browser print view's look using plain tables instead. */
  * { box-sizing: border-box; }
  body {
    margin: 0;
    padding: 0.35in;
    font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
    color: #151515;
  }
  .header {
    width: 100%;
    border: 1px solid rgba(21,21,21,.08);
    border-radius: 10px;
    padding: 10px 12px;
    margin-bottom: 12px;
  }
  .header td { vertical-align: middle; }
  .title {
    margin: 0;
    font-size: 20px;
    font-weight: 700;
    color: #6432C8;
  }
  .sub {
    margin: 2px 0 0;
    font-size: 11px;
    color: rgba(21,21,21,.62);
  }
  .meta {
    text-align: right;
    font-size: 10px;
    color: rgba(21,21,21,.62);
  }
  table.schedule {
    width: 100%;
    border-collapse: collapse;
    border: 1px solid rgba(21,21,21,.08);
    border-radius: 10px;
    overflow: hidden;
  }
  table.schedule th {
    text-align: left;
    padding: 7px 10px;
    background: #f4f0fb;
    border-bottom: 1px solid rgba(21,21,21,.08);
    font-size: 9.5px;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: rgba(21,21,21,.62);
  }
  table.schedule td {
    padding: 6px 10px;
    border-bottom: 1px solid rgba(21,21,21,.06);
    font-size: 11px;
  }
  table.schedule tr:nth-child(even) td { background: rgba(21,21,21,.02); }
  .time { font-weight: 700; color: rgba(21,21,21,.86); white-space: nowrap; }
  .end { color: rgba(21,21,21,.70); white-space: nowrap; }
  .badge {
    display: inline-block;
    width: 18px;
    text-align: center;
    color: #6432C8;
  }
  .dur { text-align: right; color: rgba(21,21,21,.62); font-size: 10px; white-space: nowrap; }
  .hint { margin-top: 8px; font-size: 9px; color: rgba(21,21,21,.55); text-align: right; }
</style>
</head>
<body>
  <table class="header">
    <tr>
      <td>
        <p class="title">{{ $coupleName }} Timeline</p>
        <p class="sub">Window {{ $windowLabel }}</p>
      </td>
      <td class="meta">
        <div>{{ $stamp }}</div>
        <div>Wedding Insiders Network</div>
      </td>
    </tr>
  </table>

  <table class="schedule">
    <thead>
      <tr>
        <th style="width:12%;">Start</th>
        <th style="width:12%;">End</th>
        <th>Event</th>
        <th style="width:14%;">Duration</th>
      </tr>
    </thead>
    <tbody>
      @forelse($lines as $line)
        <tr>
          <td class="time">{{ $line['start'] }}</td>
          <td class="end">{{ $line['end'] }}</td>
          <td><span class="badge">{{ $line['icon'] }}</span> {{ $line['label'] }}</td>
          <td class="dur">{{ $line['durationMin'] ? $line['durationMin'] . ' min' : '' }}</td>
        </tr>
      @empty
        <tr>
          <td colspan="4" style="text-align:center;color:rgba(21,21,21,.55);">No events added yet.</td>
        </tr>
      @endforelse
    </tbody>
  </table>
  <p class="hint">Includes key events plus vendor task milestones.</p>
</body>
</html>
