<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>B2B Database Schema — Interactive Explorer</title>
<script src="https://cdn.jsdelivr.net/npm/vis-network@9.1.9/standalone/umd/vis-network.min.js"></script>
<script>
// Fallback to unpkg if jsdelivr fails
if (typeof vis === 'undefined') {
  document.write('<script src="https://unpkg.com/vis-network@9.1.9/standalone/umd/vis-network.min.js"><\/script>');
}
</script>
<style>
  * { box-sizing: border-box; }
  html, body { margin: 0; padding: 0; height: 100%; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background: #0f172a; color: #e2e8f0; }
  #app { display: grid; grid-template-columns: 280px 1fr 360px; grid-template-rows: 56px 1fr; height: 100vh; }
  header { grid-column: 1 / -1; display: flex; align-items: center; padding: 0 20px; background: linear-gradient(90deg, #1e293b, #0f172a); border-bottom: 1px solid #334155; }
  header h1 { font-size: 16px; margin: 0; font-weight: 600; }
  header .meta { margin-left: 16px; color: #94a3b8; font-size: 12px; }
  header .legend { margin-left: auto; display: flex; gap: 14px; align-items: center; font-size: 12px; color: #94a3b8; }
  header .legend .dot { display: inline-block; width: 10px; height: 10px; border-radius: 50%; margin-right: 6px; vertical-align: middle; }

  #sidebar { background: #1e293b; border-right: 1px solid #334155; overflow-y: auto; padding: 12px; }
  #sidebar input { width: 100%; padding: 8px 10px; border-radius: 6px; border: 1px solid #334155; background: #0f172a; color: #e2e8f0; font-size: 13px; outline: none; margin-bottom: 10px; }
  #sidebar input:focus { border-color: #6366f1; }
  #sidebar .group { margin-bottom: 14px; }
  #sidebar .group-title { font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em; color: #64748b; margin-bottom: 6px; padding: 4px 8px; cursor: pointer; border-radius: 4px; display: flex; align-items: center; gap: 6px; user-select: none; }
  #sidebar .group-title:hover { background: #334155; color: #cbd5e1; }
  #sidebar .group-title.active { background: rgba(99,102,241,0.2); color: #a5b4fc; }
  #sidebar .group-title .swatch { width: 10px; height: 10px; border-radius: 2px; }
  #sidebar .group-title .hint { margin-left: auto; font-size: 9px; color: #475569; text-transform: none; letter-spacing: 0; }
  #sidebar .table-item { padding: 6px 10px; border-radius: 4px; cursor: pointer; font-size: 13px; color: #cbd5e1; display: flex; justify-content: space-between; align-items: center; transition: background 0.1s; }
  #sidebar .table-item:hover { background: #334155; color: #fff; }
  #sidebar .table-item.active { background: #6366f1; color: #fff; }
  #sidebar .table-item .badge { font-size: 10px; background: #475569; color: #cbd5e1; padding: 1px 6px; border-radius: 10px; }
  #sidebar .table-item.active .badge { background: rgba(255,255,255,0.25); color: #fff; }

  #graph { background: #0b1220; position: relative; min-height: 0; min-width: 0; overflow: hidden; }
  #graph .controls { position: absolute; top: 12px; right: 12px; display: flex; gap: 6px; z-index: 10; }
  #graph button { background: #1e293b; color: #e2e8f0; border: 1px solid #334155; border-radius: 6px; padding: 6px 12px; font-size: 12px; cursor: pointer; }
  #graph button:hover { background: #334155; }
  #network { width: 100%; height: 100%; position: absolute; top: 0; left: 0; }
  #loading { position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; color: #64748b; font-size: 14px; pointer-events: none; z-index: 5; }

  #details { background: #1e293b; border-left: 1px solid #334155; overflow-y: auto; padding: 16px; }
  #details .empty { color: #64748b; font-size: 13px; text-align: center; padding: 40px 16px; }
  #details h2 { margin: 0 0 4px 0; font-size: 18px; color: #fff; word-break: break-all; }
  #details .table-meta { color: #94a3b8; font-size: 12px; margin-bottom: 16px; }
  #details h3 { font-size: 12px; text-transform: uppercase; letter-spacing: 0.05em; color: #64748b; margin: 16px 0 8px 0; }
  #details table { width: 100%; border-collapse: collapse; font-size: 12px; }
  #details table th { text-align: left; padding: 6px 8px; color: #94a3b8; font-weight: 500; border-bottom: 1px solid #334155; }
  #details table td { padding: 6px 8px; border-bottom: 1px solid #1e293b; vertical-align: top; }
  #details .col-name { color: #e2e8f0; font-family: ui-monospace, monospace; font-size: 12px; }
  #details .col-name.pk { color: #fbbf24; font-weight: 600; }
  #details .col-name.fk { color: #60a5fa; }
  #details .col-type { color: #94a3b8; font-family: ui-monospace, monospace; font-size: 11px; }
  #details .pill { display: inline-block; font-size: 9px; padding: 1px 5px; border-radius: 3px; margin-left: 4px; vertical-align: middle; }
  #details .pill.pk { background: #fbbf24; color: #1e293b; }
  #details .pill.fk { background: #60a5fa; color: #1e293b; }
  #details .rel-link { color: #60a5fa; cursor: pointer; padding: 4px 8px; background: #0f172a; border-radius: 4px; display: inline-block; margin: 2px; font-size: 12px; font-family: ui-monospace, monospace; }
  #details .rel-link:hover { background: #334155; }
  #details .desc { color: #94a3b8; font-size: 11px; line-height: 1.4; }
  .scroll-hidden::-webkit-scrollbar { width: 8px; }
  .scroll-hidden::-webkit-scrollbar-track { background: transparent; }
  .scroll-hidden::-webkit-scrollbar-thumb { background: #334155; border-radius: 4px; }
</style>
</head>
<body>
<div id="app">
  <header>
    <h1>🗄️ B2B Database Schema</h1>
    <span class="meta">{{ $databaseName }} ({{ $connectionName }}) · {{ count($schema) }} tables</span>
    <div class="legend" id="legend"></div>
  </header>

  <aside id="sidebar" class="scroll-hidden">
    <input type="text" id="search" placeholder="Search tables…" autocomplete="off" />
    <div id="table-list"></div>
  </aside>

  <main id="graph">
    <div class="controls">
      <span id="group-status" style="display:none; align-items:center; gap:6px; padding:6px 10px; background:#1e293b; border:1px solid #6366f1; border-radius:6px; font-size:12px; color:#a5b4fc;"></span>
      <button id="fit">Fit</button>
      <button id="reset">Reset</button>
      <button id="toggle-outlines">Hide Outlines</button>
      <button id="toggle-physics">Toggle Physics</button>
    </div>
    <div id="loading">Loading graph…</div>
    <div id="network"></div>
  </main>

  <aside id="details" class="scroll-hidden">
    <div class="empty" id="empty-state">
      Click a table in the graph or list to see its columns and relationships.
    </div>
    <div id="details-content" style="display:none"></div>
  </aside>
</div>

<script>
// ============== SCHEMA DATA ==============
// Tables with columns and category for color grouping
const SCHEMA = @json($schema);

const CATEGORY_COLORS = {};
function colorForCategory(cat) {
  if (CATEGORY_COLORS[cat]) return CATEGORY_COLORS[cat];
  let h = 0;
  for (let i = 0; i < cat.length; i++) h = (h * 31 + cat.charCodeAt(i)) % 360;
  const entry = {
    bg:     `hsl(${h}, 65%, 65%)`,
    border: `hsl(${h}, 55%, 40%)`,
    label:  cat.charAt(0).toUpperCase() + cat.slice(1),
  };
  CATEGORY_COLORS[cat] = entry;
  return entry;
}

// Pre-warm so legend rendering is stable
Object.values(SCHEMA).forEach(t => colorForCategory(t.cat || 'other'));

// Build legend from actual categories present
(function buildLegend() {
  const used = new Set(Object.values(SCHEMA).map(t => t.cat || 'other'));
  const el = document.getElementById('legend');
  el.innerHTML = [...used].sort().map(cat => {
    const c = colorForCategory(cat);
    return `<span><span class="dot" style="background:${c.bg}"></span>${c.label}</span>`;
  }).join('');
})();

// ============== BUILD GRAPH ==============
const tableNames = Object.keys(SCHEMA);
const nodes = tableNames.map(name => {
  const cat = SCHEMA[name].cat || 'other';
  const c = colorForCategory(cat);
  return {
    id: name,
    label: name,
    color: { background: c.bg, border: c.border, highlight: { background: '#fff', border: c.border } },
    font: { color: '#0f172a', size: 14, face: 'ui-monospace, monospace', strokeWidth: 0 },
    shape: 'box',
    margin: 8,
    borderWidth: 2,
    shadow: { enabled: true, color: 'rgba(0,0,0,0.4)', size: 6 },
  };
});

const edges = [];
const edgeSet = new Set();
let edgeCounter = 0;
tableNames.forEach(name => {
  SCHEMA[name].cols.forEach(col => {
    const flag = col[2] || '';
    if (flag.startsWith('FK:')) {
      const target = flag.slice(3);
      if (SCHEMA[target]) {
        const key = `${name}->${target}:${col[0]}`;
        if (!edgeSet.has(key)) {
          edgeSet.add(key);
          edges.push({
            id: 'e' + (edgeCounter++),
            from: name, to: target,
            label: col[0],
            arrows: 'to',
            color: { color: '#475569', highlight: '#6366f1', hover: '#94a3b8' },
            font: { color: '#94a3b8', size: 9, strokeWidth: 0, align: 'middle' },
            smooth: { type: 'continuous' },
            width: 1,
          });
        }
      }
    }
  });
});

// ============== INIT NETWORK ==============
if (typeof vis === 'undefined') {
  document.getElementById('loading').textContent = '⚠ Could not load vis-network library. Check your internet connection.';
  throw new Error('vis-network failed to load');
}
const container = document.getElementById('network');
const data = { nodes: new vis.DataSet(nodes), edges: new vis.DataSet(edges) };
const options = {
  layout: { improvedLayout: false },
  physics: {
    enabled: true,
    solver: 'forceAtlas2Based',
    forceAtlas2Based: { gravitationalConstant: -80, centralGravity: 0.01, springLength: 150, springConstant: 0.08, damping: 0.6, avoidOverlap: 0.5 },
    stabilization: { iterations: 300, updateInterval: 25 },
    minVelocity: 0.5,
  },
  interaction: { hover: true, tooltipDelay: 100, navigationButtons: false, keyboard: true },
  edges: { selectionWidth: 2, hoverWidth: 1.5 },
  nodes: { chosen: { node: (values) => { values.borderWidth = 4; } } },
};
const network = new vis.Network(container, data, options);
network.on('stabilizationProgress', (params) => {
  const pct = Math.round(params.iterations / params.total * 100);
  document.getElementById('loading').textContent = `Laying out tables… ${pct}%`;
});
network.on('beforeDrawing', (ctx) => {
  drawGroupOutlines(ctx);
});

// ============== SIDEBAR ==============
function buildSidebar(filter = '') {
  const list = document.getElementById('table-list');
  list.innerHTML = '';
  const groups = {};
  tableNames.forEach(name => {
    if (filter && !name.toLowerCase().includes(filter.toLowerCase())) return;
    const cat = SCHEMA[name].cat || 'other';
    if (!groups[cat]) groups[cat] = [];
    groups[cat].push(name);
  });
  Object.keys(groups).sort().forEach(cat => {
    const c = colorForCategory(cat);
    const div = document.createElement('div');
    div.className = 'group';
    const title = document.createElement('div');
    title.className = 'group-title' + (selectedGroup === cat ? ' active' : '');
    title.dataset.cat = cat;
    title.innerHTML = `<span class="swatch" style="background:${c.bg}"></span>${c.label} (${groups[cat].length})<span class="hint">click to select</span>`;
    title.onclick = () => toggleGroupSelection(cat);
    div.appendChild(title);
    groups[cat].sort().forEach(name => {
      const item = document.createElement('div');
      item.className = 'table-item';
      item.dataset.name = name;
      const colCount = SCHEMA[name].cols.length;
      item.innerHTML = `<span>${name}</span><span class="badge">${colCount}</span>`;
      item.onclick = () => selectTable(name);
      div.appendChild(item);
    });
    list.appendChild(div);
  });
}

document.getElementById('search').addEventListener('input', e => buildSidebar(e.target.value));

// ============== DETAILS PANEL ==============
function buildDetails(name) {
  const t = SCHEMA[name];
  if (!t) return;
  const cat = t.cat || 'other';
  const catLabel = colorForCategory(cat).label;

  const incoming = [];
  const outgoing = [];
  tableNames.forEach(n => {
    SCHEMA[n].cols.forEach(c => {
      const flag = c[2] || '';
      if (flag.startsWith('FK:')) {
        const target = flag.slice(3);
        if (n === name) outgoing.push({ col: c[0], to: target });
        if (target === name) incoming.push({ from: n, col: c[0] });
      }
    });
  });

  let html = `<h2>${name}</h2>`;
  html += `<div class="table-meta">${catLabel} · ${t.cols.length} columns</div>`;

  html += `<h3>Columns</h3>`;
  html += `<table><thead><tr><th>Name</th><th>Type</th><th>Description</th></tr></thead><tbody>`;
  t.cols.forEach(c => {
    const flag = c[2] || '';
    let cls = 'col-name', pill = '';
    if (flag === 'PK') { cls += ' pk'; pill = ' <span class="pill pk">PK</span>'; }
    else if (flag.startsWith('FK:')) { cls += ' fk'; pill = ` <span class="pill fk">FK→${flag.slice(3)}</span>`; }
    html += `<tr><td><span class="${cls}">${c[0]}</span>${pill}</td><td class="col-type">${c[1]}</td><td class="desc">${c[3] || ''}</td></tr>`;
  });
  html += `</tbody></table>`;

  if (outgoing.length) {
    html += `<h3>References (outgoing)</h3>`;
    html += outgoing.map(r => `<span class="rel-link" data-name="${r.to}">${r.col} → ${r.to}</span>`).join('');
  }
  if (incoming.length) {
    html += `<h3>Referenced by (incoming)</h3>`;
    html += incoming.map(r => `<span class="rel-link" data-name="${r.from}">${r.from}.${r.col}</span>`).join('');
  }

  document.getElementById('empty-state').style.display = 'none';
  const dc = document.getElementById('details-content');
  dc.style.display = 'block';
  dc.innerHTML = html;
  dc.querySelectorAll('.rel-link').forEach(el => {
    el.onclick = () => selectTable(el.dataset.name);
  });
}

// ============== GROUP OUTLINES ==============
let selectedGroup = null;
let outlinesVisible = true;
const tablesByCat = {};
tableNames.forEach(n => {
  const cat = SCHEMA[n].cat || 'other';
  (tablesByCat[cat] = tablesByCat[cat] || []).push(n);
});

function convexHull(points) {
  if (points.length < 3) return points.slice();
  const pts = points.slice().sort((a,b) => a.x - b.x || a.y - b.y);
  const cross = (O,A,B) => (A.x - O.x) * (B.y - O.y) - (A.y - O.y) * (B.x - O.x);
  const lower = [];
  for (const p of pts) {
    while (lower.length >= 2 && cross(lower[lower.length-2], lower[lower.length-1], p) <= 0) lower.pop();
    lower.push(p);
  }
  const upper = [];
  for (let i = pts.length - 1; i >= 0; i--) {
    const p = pts[i];
    while (upper.length >= 2 && cross(upper[upper.length-2], upper[upper.length-1], p) <= 0) upper.pop();
    upper.push(p);
  }
  upper.pop(); lower.pop();
  return lower.concat(upper);
}

function expandHull(hull, padding) {
  const cx = hull.reduce((a,p) => a + p.x, 0) / hull.length;
  const cy = hull.reduce((a,p) => a + p.y, 0) / hull.length;
  return hull.map(p => {
    const dx = p.x - cx, dy = p.y - cy;
    const d = Math.hypot(dx, dy) || 1;
    return { x: p.x + (dx/d) * padding, y: p.y + (dy/d) * padding };
  });
}

function drawSmoothPolygon(ctx, points) {
  if (points.length < 2) return;
  ctx.beginPath();
  const n = points.length;
  for (let i = 0; i < n; i++) {
    const curr = points[i];
    const next = points[(i + 1) % n];
    const mx = (curr.x + next.x) / 2;
    const my = (curr.y + next.y) / 2;
    if (i === 0) ctx.moveTo(mx, my);
    else ctx.quadraticCurveTo(curr.x, curr.y, mx, my);
  }
  ctx.closePath();
}

function drawGroupOutlines(ctx) {
  if (!outlinesVisible) return;
  const positions = network.getPositions();
  Object.entries(tablesByCat).forEach(([cat, names]) => {
    if (selectedGroup && selectedGroup !== cat) return;
    const pts = names.map(n => positions[n]).filter(Boolean);
    if (pts.length < 2) return;
    const c = colorForCategory(cat);
    const hull = convexHull(pts);
    const expanded = expandHull(hull, 70);
    const isSelected = selectedGroup === cat;

    ctx.save();
    ctx.lineJoin = 'round';
    ctx.lineCap = 'round';
    drawSmoothPolygon(ctx, expanded);
    ctx.fillStyle = c.bg + (isSelected ? '33' : '1A');
    ctx.strokeStyle = c.border;
    ctx.lineWidth = isSelected ? 4 : 2;
    if (!isSelected) ctx.setLineDash([10, 6]);
    ctx.fill();
    ctx.stroke();
    ctx.restore();

    // Group label near top of bounding box
    const minY = Math.min(...expanded.map(p => p.y));
    const cx = expanded.reduce((a,p) => a + p.x, 0) / expanded.length;
    ctx.save();
    ctx.font = `bold ${isSelected ? 18 : 14}px sans-serif`;
    const text = c.label.toUpperCase();
    const tw = ctx.measureText(text).width;
    ctx.fillStyle = c.border;
    ctx.fillRect(cx - tw/2 - 10, minY - 26, tw + 20, 22);
    ctx.fillStyle = '#0f172a';
    ctx.textAlign = 'center';
    ctx.textBaseline = 'middle';
    ctx.fillText(text, cx, minY - 15);
    ctx.restore();
  });
}

function applyGroupVisibility() {
  if (selectedGroup) {
    const memberSet = new Set(tablesByCat[selectedGroup]);
    data.nodes.update(nodes.map(n => ({ id: n.id, hidden: !memberSet.has(n.id), opacity: 1.0 })));
    data.edges.update(edges.map(e => ({
      id: e.id,
      hidden: !(memberSet.has(e.from) && memberSet.has(e.to)),
      color: { color: '#475569' },
      width: 1,
    })));
  } else {
    data.nodes.update(nodes.map(n => ({ id: n.id, hidden: false, opacity: 1.0 })));
    data.edges.update(edges.map(e => ({ id: e.id, hidden: false, color: { color: '#475569' }, width: 1 })));
  }
}

function toggleGroupSelection(cat) {
  if (selectedGroup === cat) {
    selectedGroup = null;
    network.unselectAll();
    document.getElementById('group-status').style.display = 'none';
    applyGroupVisibility();
  } else {
    selectedGroup = cat;
    applyGroupVisibility();
    network.selectNodes(tablesByCat[cat], false);
    const status = document.getElementById('group-status');
    status.style.display = 'inline-flex';
    const sc = colorForCategory(cat);
    status.innerHTML = `<span style="width:10px;height:10px;border-radius:2px;background:${sc.bg}"></span> ${sc.label} group selected · drag any table to move all <span style="cursor:pointer;margin-left:6px;color:#94a3b8;font-weight:bold" id="group-clear">✕</span>`;
    document.getElementById('group-clear').onclick = (e) => {
      e.stopPropagation();
      toggleGroupSelection(cat);
    };
    // Frame the selected group
    setTimeout(() => network.fit({ nodes: tablesByCat[cat], animation: { duration: 500, easingFunction: 'easeInOutQuad' } }), 50);
  }
  buildSidebar(document.getElementById('search').value);
  network.redraw();
}

// ============== SELECTION ==============
function selectTable(name) {
  const wasGroupSelected = selectedGroup !== null;
  selectedGroup = null;
  document.getElementById('group-status').style.display = 'none';
  if (wasGroupSelected) applyGroupVisibility();
  network.selectNodes([name]);
  network.focus(name, { scale: 1.0, animation: { duration: 500, easingFunction: 'easeInOutQuad' } });
  highlightSidebar(name);
  buildDetails(name);
  highlightConnections(name);
  network.redraw();
}

function highlightSidebar(name) {
  document.querySelectorAll('.table-item').forEach(el => {
    el.classList.toggle('active', el.dataset.name === name);
  });
}

function highlightConnections(name) {
  const connectedNodes = new Set([name]);
  const connectedEdgeIds = new Set();
  edges.forEach(e => {
    if (e.from === name || e.to === name) {
      connectedNodes.add(e.from);
      connectedNodes.add(e.to);
      connectedEdgeIds.add(e.id);
    }
  });
  data.nodes.update(nodes.map(n => ({
    id: n.id,
    opacity: connectedNodes.has(n.id) ? 1.0 : 0.25,
  })));
  data.edges.update(edges.map(e => ({
    id: e.id,
    color: connectedEdgeIds.has(e.id) ? { color: '#6366f1' } : { color: '#1e293b' },
    width: connectedEdgeIds.has(e.id) ? 2 : 0.5,
  })));
}

function clearHighlight() {
  data.nodes.update(nodes.map(n => ({ id: n.id, opacity: 1.0 })));
  data.edges.update(edges.map(e => ({
    id: e.id,
    color: { color: '#475569' },
    width: 1,
  })));
}

network.on('click', (params) => {
  if (params.nodes.length) {
    selectTable(params.nodes[0]);
  } else {
    const wasGroupSelected = selectedGroup !== null;
    selectedGroup = null;
    document.getElementById('group-status').style.display = 'none';
    if (wasGroupSelected) applyGroupVisibility();
    clearHighlight();
    highlightSidebar('');
    buildSidebar(document.getElementById('search').value);
    network.unselectAll();
    document.getElementById('details-content').style.display = 'none';
    document.getElementById('empty-state').style.display = 'block';
    network.redraw();
  }
});

// Keep group multi-selection while dragging: re-select the group on dragStart
// so vis-network drags all member nodes together
network.on('dragStart', (params) => {
  if (selectedGroup && params.nodes.length === 1) {
    const draggedId = params.nodes[0];
    if (tablesByCat[selectedGroup].includes(draggedId)) {
      // Ensure all group nodes are selected so vis-network drags them together
      network.selectNodes(tablesByCat[selectedGroup], false);
    }
  }
});

// ============== CONTROLS ==============
let physicsOn = true;
document.getElementById('fit').onclick = () => network.fit({ animation: true });
document.getElementById('reset').onclick = () => {
  selectedGroup = null;
  document.getElementById('group-status').style.display = 'none';
  applyGroupVisibility();
  clearHighlight();
  highlightSidebar('');
  buildSidebar(document.getElementById('search').value);
  document.getElementById('details-content').style.display = 'none';
  document.getElementById('empty-state').style.display = 'block';
  network.unselectAll();
  network.fit({ animation: true });
};
document.getElementById('toggle-physics').onclick = () => {
  physicsOn = !physicsOn;
  network.setOptions({ physics: { enabled: physicsOn } });
};
document.getElementById('toggle-outlines').onclick = () => {
  outlinesVisible = !outlinesVisible;
  document.getElementById('toggle-outlines').textContent = outlinesVisible ? 'Hide Outlines' : 'Show Outlines';
  network.redraw();
};

network.once('stabilizationIterationsDone', () => {
  network.setOptions({ physics: { enabled: false } });
  physicsOn = false;
  document.getElementById('loading').style.display = 'none';
  network.fit({ animation: { duration: 600, easingFunction: 'easeInOutQuad' } });
});

// Safety net: hide loading after 10s no matter what
setTimeout(() => {
  const l = document.getElementById('loading');
  if (l) l.style.display = 'none';
  network.fit();
}, 10000);

buildSidebar();
</script>
</body>
</html>
