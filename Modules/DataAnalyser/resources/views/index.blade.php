<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Data Analyser — Bookmark Classifier</title>
<style>
  * { box-sizing: border-box; }
  html, body { margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background: #0f172a; color: #e2e8f0; }
  header { display: flex; align-items: center; padding: 14px 24px; background: linear-gradient(90deg, #1e293b, #0f172a); border-bottom: 1px solid #334155; }
  header h1 { font-size: 16px; margin: 0; font-weight: 600; }
  header .meta { margin-left: 16px; color: #94a3b8; font-size: 12px; }
  main { max-width: 1100px; margin: 0 auto; padding: 24px; }
  .card { background: #1e293b; border: 1px solid #334155; border-radius: 10px; padding: 20px; margin-bottom: 24px; }
  .card h2 { margin: 0 0 12px; font-size: 14px; text-transform: uppercase; letter-spacing: 0.05em; color: #94a3b8; }
  textarea { width: 100%; min-height: 160px; padding: 10px 12px; border-radius: 8px; border: 1px solid #334155; background: #0f172a; color: #e2e8f0; font-size: 13px; font-family: ui-monospace, SFMono-Regular, Menlo, monospace; outline: none; resize: vertical; }
  textarea:focus { border-color: #6366f1; }
  .form-row { display: flex; align-items: center; gap: 16px; margin-top: 14px; flex-wrap: wrap; }
  input[type="file"] { color: #94a3b8; font-size: 13px; }
  button[type="submit"] { background: #6366f1; color: #fff; border: 0; border-radius: 8px; padding: 9px 22px; font-size: 13px; font-weight: 600; cursor: pointer; }
  button[type="submit"]:hover { background: #818cf8; }
  .hint { color: #64748b; font-size: 12px; margin-top: 8px; }
  .errors { background: rgba(244,63,94,0.12); border: 1px solid #f43f5e; color: #fda4af; border-radius: 8px; padding: 10px 14px; margin-bottom: 16px; font-size: 13px; }
  .counts { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 10px; }
  .count-item { background: #0f172a; border: 1px solid #334155; border-radius: 8px; padding: 10px 12px; }
  .count-item .label { display: flex; justify-content: space-between; font-size: 13px; margin-bottom: 6px; }
  .count-item .label .n { color: #a5b4fc; font-weight: 600; }
  .bar { height: 5px; border-radius: 3px; background: #334155; overflow: hidden; }
  .bar span { display: block; height: 100%; background: #6366f1; }
  .table-wrap { overflow-x: auto; max-height: 560px; overflow-y: auto; border: 1px solid #334155; border-radius: 8px; }
  table { width: 100%; border-collapse: collapse; font-size: 13px; }
  th, td { text-align: left; padding: 8px 12px; border-bottom: 1px solid #1e293b; vertical-align: top; }
  th { position: sticky; top: 0; background: #0f172a; color: #94a3b8; font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em; }
  td.url { max-width: 420px; word-break: break-all; }
  td.url a { color: #93c5fd; text-decoration: none; }
  td.url a:hover { text-decoration: underline; }
  .badge { display: inline-block; background: rgba(99,102,241,0.2); color: #a5b4fc; border-radius: 10px; padding: 1px 9px; font-size: 11px; white-space: nowrap; }
  select { background: #0f172a; color: #e2e8f0; border: 1px solid #334155; border-radius: 6px; padding: 6px 10px; font-size: 13px; }
</style>
</head>
<body>
<header>
  <h1>Data Analyser</h1>
  <span class="meta">Classify bookmark URLs into categories</span>
</header>
<main>
  <div class="card">
    <h2>Input</h2>
    @if ($errors->any())
      <div class="errors">
        @foreach ($errors->all() as $error)
          <div>{{ $error }}</div>
        @endforeach
      </div>
    @endif
    <form method="POST" action="{{ route('dataanalyser.classify') }}" enctype="multipart/form-data">
      @csrf
      <textarea name="content" placeholder="https://example.com/page&#9;Page title&#10;https://laravel.com/docs&#9;Laravel Documentation">{{ old('content') }}</textarea>
      <div class="form-row">
        <input type="file" name="file" accept=".tsv,.txt,.csv" />
        <button type="submit">Classify</button>
      </div>
      <p class="hint">One bookmark per line: URL, then a tab, then the title (title optional). Or upload a .tsv file instead.</p>
    </form>
  </div>

  @if ($result !== null)
    <div class="card">
      <h2>Category counts — {{ count($result['rows']) }} bookmarks</h2>
      @php $max = max(1, max($result['counts'])); @endphp
      <div class="counts">
        @foreach ($categories as $category)
          <div class="count-item">
            <div class="label">
              <span>{{ $category }}</span>
              <span class="n">{{ $result['counts'][$category] }}</span>
            </div>
            <div class="bar"><span style="width: {{ round($result['counts'][$category] / $max * 100) }}%"></span></div>
          </div>
        @endforeach
      </div>
    </div>

    <div class="card">
      <h2>Rows</h2>
      <div class="form-row" style="margin: 0 0 12px;">
        <select id="category-filter">
          <option value="">All categories</option>
          @foreach ($categories as $category)
            <option value="{{ $category }}">{{ $category }} ({{ $result['counts'][$category] }})</option>
          @endforeach
        </select>
      </div>
      <div class="table-wrap">
        <table>
          <thead>
            <tr><th>Category</th><th>URL</th><th>Title</th></tr>
          </thead>
          <tbody>
            @foreach ($result['rows'] as $row)
              <tr data-category="{{ $row['category'] }}">
                <td><span class="badge">{{ $row['category'] }}</span></td>
                <td class="url"><a href="{{ $row['url'] }}" target="_blank" rel="noopener noreferrer">{{ $row['url'] }}</a></td>
                <td>{{ $row['title'] }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

    <script>
      document.getElementById('category-filter').addEventListener('change', function () {
        const selected = this.value;
        document.querySelectorAll('tbody tr').forEach(function (tr) {
          tr.style.display = (!selected || tr.dataset.category === selected) ? '' : 'none';
        });
      });
    </script>
  @endif
</main>
</body>
</html>
