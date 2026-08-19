<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>{{ $coupleName }} Wedding Budget</title>
<style>
  * { box-sizing: border-box; }
  body {
    margin: 0;
    padding: 0.5in;
    font-family: "DejaVu Sans", sans-serif;
    color: #151515;
  }
  .header {
    width: 100%;
    border: 1px solid rgba(21,21,21,.08);
    border-radius: 10px;
    padding: 12px 16px;
    margin-bottom: 16px;
  }
  .header td { vertical-align: middle; }
  .logo { height: 46px; width: auto; }
  .title { margin: 0; font-size: 22px; font-weight: 700; color: #6432C8; }
  .sub { margin: 2px 0 0; font-size: 11px; color: rgba(21,21,21,.62); }
  .meta { text-align: right; font-size: 10px; color: rgba(21,21,21,.62); }

  .summary { width: 100%; margin-bottom: 14px; }
  .summary td { padding: 6px 0; }
  .summary .label { font-size: 10px; text-transform: uppercase; letter-spacing: .06em; color: rgba(21,21,21,.55); }
  .summary .value { font-size: 20px; font-weight: 700; color: #151515; }

  .chartBar { width: 100%; border-collapse: collapse; margin-bottom: 4px; }
  .chartBar td { height: 26px; padding: 0; border: 0; }
  .chartBar .empty { background: rgba(21,21,21,.06); }
  .chartHint { margin: 0 0 18px; font-size: 9px; color: rgba(21,21,21,.5); }

  table.breakdown { width: 100%; border-collapse: collapse; border: 1px solid rgba(21,21,21,.08); border-radius: 10px; overflow: hidden; }
  table.breakdown th {
    text-align: left; padding: 7px 10px; background: #f4f0fb; border-bottom: 1px solid rgba(21,21,21,.08);
    font-size: 9.5px; letter-spacing: .08em; text-transform: uppercase; color: rgba(21,21,21,.62);
  }
  table.breakdown td { padding: 6px 10px; border-bottom: 1px solid rgba(21,21,21,.06); font-size: 11px; }
  table.breakdown tr:nth-child(even) td { background: rgba(21,21,21,.02); }
  .swatch { display: inline-block; width: 10px; height: 10px; border-radius: 2px; margin-right: 6px; }
  .num { text-align: right; white-space: nowrap; }
  .hint { margin-top: 10px; font-size: 9px; color: rgba(21,21,21,.55); text-align: right; }
</style>
</head>
<body>
  <table class="header">
    <tr>
      @if($logoSrc)
        <td style="width:60px;"><img class="logo" src="{{ $logoSrc }}" alt="WIN" /></td>
      @endif
      <td>
        <p class="title">{{ $coupleName }} Wedding Budget</p>
        <p class="sub">Planned allocation by category</p>
      </td>
      <td class="meta">
        <div>{{ $stamp }}</div>
        <div>Wedding Insiders Network</div>
      </td>
    </tr>
  </table>

  <table class="summary">
    <tr>
      <td style="width:33%;">
        <p class="label">Total Budget</p>
        <p class="value">${{ number_format($total) }}</p>
      </td>
      <td style="width:33%;">
        <p class="label">Booked So Far</p>
        <p class="value">${{ number_format($spentTotal) }}</p>
      </td>
      <td>
        <p class="label">Remaining</p>
        <p class="value">${{ number_format(max(0, $total - $spentTotal)) }}</p>
      </td>
    </tr>
  </table>

  @if(count($rows))
    <table class="chartBar">
      <tr>
        @foreach($rows as $row)
          <td style="width:{{ number_format($row['pct'], 2, '.', '') }}%; background: {{ $row['color'] }};"></td>
        @endforeach
        @php $usedPct = array_sum(array_column($rows, 'pct')); @endphp
        @if($usedPct < 100)
          <td class="empty" style="width:{{ number_format(100 - $usedPct, 2, '.', '') }}%;"></td>
        @endif
      </tr>
    </table>
    <p class="chartHint">Each segment is proportional to that category's share of the total budget.</p>

    <table class="breakdown">
      <thead>
        <tr>
          <th>Category</th>
          <th style="width:12%;">Share</th>
          <th style="width:18%;" class="num">Planned</th>
          <th style="width:18%;" class="num">Spent</th>
          <th style="width:18%;" class="num">Remaining</th>
        </tr>
      </thead>
      <tbody>
        @foreach($rows as $row)
          <tr>
            <td><span class="swatch" style="background: {{ $row['color'] }};"></span>{{ $row['label'] }}</td>
            <td>{{ number_format($row['pct'], 0) }}%</td>
            <td class="num">${{ number_format($row['planned']) }}</td>
            <td class="num">${{ number_format($row['spent']) }}</td>
            <td class="num">${{ number_format(max(0, $row['planned'] - $row['spent'])) }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @else
    <p>No budget categories selected yet — add planned or booked vendors in the Budget Planner to see your breakdown here.</p>
  @endif

  <p class="hint">Estimates based on regional averages — actual vendor pricing may vary.</p>
</body>
</html>
