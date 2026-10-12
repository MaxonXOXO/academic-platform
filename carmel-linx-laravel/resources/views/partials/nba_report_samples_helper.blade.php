{{-- 
    NBA Accreditation Audit - Assessment Benchmark Cards (Revision 2021)
    Displays clean summary cards on top of reports with Best, Average, and Low marks
    for tests and assignments, with print support and table cell highlights.
--}}
<style>
    /* NBA Cell Badges */
    .nba-badge {
        display: inline-block;
        font-size: 7.5px;
        font-weight: 800;
        letter-spacing: 0.3px;
        padding: 1px 3px;
        border-radius: 3px;
        margin-left: 3px;
        vertical-align: middle;
        line-height: 1.1;
        text-transform: uppercase;
    }
    .nba-badge-best {
        background-color: #dcfce7 !important;
        color: #166534 !important;
        border: 1px solid #16a34a !important;
    }
    .nba-badge-avg {
        background-color: #fef3c7 !important;
        color: #92400e !important;
        border: 1px solid #d97706 !important;
    }
    .nba-badge-low {
        background-color: #fee2e2 !important;
        color: #991b1b !important;
        border: 1px solid #dc2626 !important;
    }

    /* Subtle cell background tints */
    .nba-cell-best {
        background-color: #f0fdf4 !important;
    }
    .nba-cell-avg {
        background-color: #fffbeb !important;
    }
    .nba-cell-low {
        background-color: #fef2f2 !important;
    }

    /* Highlight Off state */
    .nba-highlights-off .nba-badge {
        display: none !important;
    }
    .nba-highlights-off .nba-cell-best,
    .nba-highlights-off .nba-cell-avg,
    .nba-highlights-off .nba-cell-low {
        background-color: inherit !important;
    }

    /* Clean Compact Toolbar (Screen Only) */
    .nba-simple-toolbar {
        background: #f8fafc;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        padding: 6px 12px;
        margin-bottom: 12px;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        font-family: inherit;
    }
    .nba-toolbar-btn {
        background: #ffffff;
        color: #334155;
        border: 1px solid #cbd5e1;
        border-radius: 4px;
        padding: 4px 8px;
        font-size: 10.5px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .nba-toolbar-btn:hover {
        background: #f1f5f9;
        border-color: #94a3b8;
    }
    .nba-toolbar-btn.active {
        background: #0284c7;
        color: #ffffff;
        border-color: #0369a1;
    }

    /* Benchmark Cards Section (Prints on top of reports) */
    .nba-benchmark-section {
        background: #ffffff;
        border: 1.5px solid #0284c7;
        border-radius: 6px;
        padding: 8px 10px;
        margin-top: 6px;
        margin-bottom: 16px;
        font-family: inherit;
        box-shadow: 0 1px 3px rgba(2, 132, 199, 0.06);
    }
    .nba-benchmark-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
        padding-bottom: 4px;
        border-bottom: 1px solid #e0f2fe;
    }
    .nba-benchmark-title {
        font-size: 10.5px;
        font-weight: 800;
        color: #0369a1;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    .nba-benchmark-subtitle {
        font-size: 9px;
        color: #64748b;
        font-weight: 500;
    }
    .nba-cards-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(115px, 1fr));
        gap: 8px;
    }
    .nba-stat-card {
        background: #f8fafc;
        border: 1px solid #cbd5e1;
        border-radius: 5px;
        padding: 5px 6px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .nba-card-title {
        font-size: 9.5px;
        font-weight: 800;
        color: #0f172a;
        text-transform: uppercase;
        margin-bottom: 5px;
        padding-bottom: 3px;
        border-bottom: 1px solid #e2e8f0;
        text-align: center;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .nba-card-metrics {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 3px;
        text-align: center;
    }
    .nba-metric-pill {
        padding: 3px 2px;
        border-radius: 3px;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .nba-metric-pill.best {
        background: #f0fdf4;
        border: 1px solid #86efac;
    }
    .nba-metric-pill.avg {
        background: #fffbeb;
        border: 1px solid #fde047;
    }
    .nba-metric-pill.low {
        background: #fef2f2;
        border: 1px solid #fca5a5;
    }
    .nba-metric-lbl {
        font-size: 7px;
        font-weight: 800;
        text-transform: uppercase;
        line-height: 1;
    }
    .nba-metric-pill.best .nba-metric-lbl { color: #166534; }
    .nba-metric-pill.avg  .nba-metric-lbl { color: #854d0e; }
    .nba-metric-pill.low  .nba-metric-lbl { color: #991b1b; }

    .nba-metric-val {
        font-size: 11px;
        font-weight: 800;
        margin-top: 2px;
        line-height: 1.1;
    }
    .nba-metric-pill.best .nba-metric-val { color: #15803d; }
    .nba-metric-pill.avg  .nba-metric-val { color: #a16207; }
    .nba-metric-pill.low  .nba-metric-val { color: #b91c1c; }

    /* Print Formatting */
    @media print {
        .nba-simple-toolbar, .no-print {
            display: none !important;
        }
        .nba-benchmark-section {
            display: block !important;
            border: 1.5px solid #000 !important;
            box-shadow: none !important;
            margin-top: 6px !important;
            margin-bottom: 12px !important;
            padding: 5px 8px !important;
            page-break-inside: avoid !important;
        }
        .nba-benchmark-header {
            border-bottom: 1px solid #000 !important;
            margin-bottom: 5px !important;
            padding-bottom: 3px !important;
        }
        .nba-benchmark-title {
            color: #000 !important;
            font-size: 9px !important;
        }
        .nba-benchmark-subtitle {
            color: #333 !important;
            font-size: 8px !important;
        }
        .nba-cards-grid {
            display: flex !important;
            flex-wrap: wrap !important;
            gap: 5px !important;
        }
        .nba-stat-card {
            border: 1px solid #000 !important;
            background: #fff !important;
            flex: 1 1 auto !important;
            padding: 3px 5px !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        .nba-card-title {
            color: #000 !important;
            font-size: 8px !important;
            border-bottom: 1px solid #000 !important;
            margin-bottom: 3px !important;
            padding-bottom: 2px !important;
        }
        .nba-metric-pill {
            border: 1px solid #444 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        .nba-metric-pill.best { background: #f0fdf4 !important; }
        .nba-metric-pill.avg  { background: #fffbeb !important; }
        .nba-metric-pill.low  { background: #fef2f2 !important; }
        .nba-metric-lbl { font-size: 6.5px !important; }
        .nba-metric-val { font-size: 9.5px !important; }
        .nba-badge {
            display: inline-block !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            border-width: 1px !important;
        }
        .nba-cell-best, .nba-cell-avg, .nba-cell-low {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
    }
</style>

{{-- Compact Screen Toolbar --}}
<div class="nba-simple-toolbar no-print">
    <div style="display: flex; align-items: center; gap: 8px;">
        <span style="font-weight: 800; font-size: 11px; color: #0369a1; text-transform: uppercase;">
            🎯 NBA Audit Samples:
        </span>
        <button type="button" class="nba-toolbar-btn active" id="btnNbaToggleHighlights" onclick="toggleNbaHighlights()">
            Table Highlights: <span id="nbaHighlightStateText">ON</span>
        </button>
        <button type="button" class="nba-toolbar-btn active" id="btnNbaToggleCards" onclick="toggleNbaBenchmarkCards()">
            Benchmark Cards: <span id="nbaCardsStateText">Visible</span>
        </button>
    </div>
    <div style="display: flex; align-items: center; gap: 8px; font-size: 10px; color: #475569;">
        <span><strong style="color: #15803d;">BEST</strong> = Highest Mark</span> &bull;
        <span><strong style="color: #a16207;">AVG</strong> = Mean Average</span> &bull;
        <span><strong style="color: #b91c1c;">LOW</strong> = Lowest Valid</span>
    </div>
</div>

{{-- Benchmark Cards Section (Prints on Top of Reports) --}}
<div class="nba-benchmark-section" id="nbaBenchmarkSection">
    <div class="nba-benchmark-header">
        <div class="nba-benchmark-title">
            <span>📊</span> Assessment Benchmark Samples (NBA Course File Audit)
        </div>
        <div class="nba-benchmark-subtitle">
            Best, Class Average, and Low benchmark scores required for NBA Course Outcome physical filing &amp; attainment
        </div>
    </div>
    <div class="nba-cards-grid" id="nbaBenchmarkCards">
        {{-- Populated dynamically via JS --}}
    </div>
</div>

<script>
(function() {
    window.nbaState = {
        highlightsOn: true,
        cardsVisible: true,
        assessments: {},
        tableElem: null
    };

    window.initNbaReportSamples = function(config) {
        config = config || {};
        const tableSelector = config.tableSelector || 'table';
        const tables = document.querySelectorAll(tableSelector);
        if (!tables.length) return;

        let mainTable = null;
        for (let t of tables) {
            if (t.classList.contains('meta-info') || t.classList.contains('meta-table')) continue;
            if (t.querySelector('tbody tr')) {
                mainTable = t;
                break;
            }
        }
        if (!mainTable) mainTable = tables[0];
        window.nbaState.tableElem = mainTable;

        const tbody = mainTable.querySelector('tbody');
        if (!tbody) return;

        const rows = Array.from(tbody.querySelectorAll('tr'));
        if (!rows.length) return;

        // Group cells by assessment
        const assessmentsMap = {};

        rows.forEach((row, rIdx) => {
            const cells = row.querySelectorAll('[data-nba-assessment]');
            cells.forEach(cell => {
                const aName = cell.getAttribute('data-nba-assessment');
                if (!aName) return;

                let rawVal = cell.getAttribute('data-nba-val');
                let numVal = null;

                if (rawVal !== null && rawVal !== '' && rawVal !== '-') {
                    numVal = parseFloat(rawVal);
                } else {
                    const txt = cell.innerText.trim();
                    if (txt !== '-' && txt !== 'ABS' && txt !== '' && !isNaN(parseFloat(txt))) {
                        numVal = parseFloat(txt);
                    }
                }

                if (!assessmentsMap[aName]) {
                    assessmentsMap[aName] = [];
                }

                assessmentsMap[aName].push({
                    row: row,
                    cell: cell,
                    val: numVal
                });
            });
        });

        window.nbaState.assessments = assessmentsMap;

        // Compute metrics and generate cards
        const cardsHtml = [];

        Object.keys(assessmentsMap).forEach(aName => {
            const items = assessmentsMap[aName];
            const validItems = items.filter(it => it.val !== null && !isNaN(it.val));

            if (validItems.length === 0) {
                cardsHtml.push(`
                    <div class="nba-stat-card">
                        <div class="nba-card-title">${aName}</div>
                        <div style="font-size: 9px; color: #94a3b8; text-align: center; padding: 6px 0;">No marks entered</div>
                    </div>
                `);
                return;
            }

            const vals = validItems.map(it => it.val);
            const maxVal = Math.max(...vals);
            const minVal = Math.min(...vals);
            const sumVal = vals.reduce((acc, v) => acc + v, 0);
            const meanVal = (sumVal / validItems.length);
            const formattedMean = (meanVal % 1 === 0) ? meanVal.toString() : meanVal.toFixed(1);

            // Find closest to mean for average badge
            let closestItem = validItems[0];
            let minDiff = Math.abs(validItems[0].val - meanVal);
            validItems.forEach(it => {
                const diff = Math.abs(it.val - meanVal);
                if (diff < minDiff) {
                    minDiff = diff;
                    closestItem = it;
                }
            });

            // Mark table cells
            validItems.forEach(it => {
                if (it.val === maxVal) {
                    it.cell.classList.add('nba-cell-best');
                    if (!it.cell.querySelector('.nba-badge-best')) {
                        it.cell.insertAdjacentHTML('beforeend', ` <span class="nba-badge nba-badge-best" title="Best Mark: ${it.val}">BEST</span>`);
                    }
                } else if (it.val === minVal && minVal < maxVal) {
                    it.cell.classList.add('nba-cell-low');
                    if (!it.cell.querySelector('.nba-badge-low')) {
                        it.cell.insertAdjacentHTML('beforeend', ` <span class="nba-badge nba-badge-low" title="Low Mark: ${it.val}">LOW</span>`);
                    }
                } else if (it === closestItem) {
                    it.cell.classList.add('nba-cell-avg');
                    if (!it.cell.querySelector('.nba-badge-avg')) {
                        it.cell.insertAdjacentHTML('beforeend', ` <span class="nba-badge nba-badge-avg" title="Closest to Class Mean: ${it.val}">AVG</span>`);
                    }
                }
            });

            // Clean, compact benchmark card
            cardsHtml.push(`
                <div class="nba-stat-card">
                    <div class="nba-card-title" title="${aName}">${aName}</div>
                    <div class="nba-card-metrics">
                        <div class="nba-metric-pill best" title="Highest Mark in class: ${maxVal}">
                            <span class="nba-metric-lbl">BEST</span>
                            <span class="nba-metric-val">${maxVal}</span>
                        </div>
                        <div class="nba-metric-pill avg" title="Class Mean Average: ${formattedMean}">
                            <span class="nba-metric-lbl">AVG</span>
                            <span class="nba-metric-val">${formattedMean}</span>
                        </div>
                        <div class="nba-metric-pill low" title="Lowest Valid Mark: ${minVal}">
                            <span class="nba-metric-lbl">LOW</span>
                            <span class="nba-metric-val">${minVal}</span>
                        </div>
                    </div>
                </div>
            `);
        });

        // Insert cards
        const container = document.getElementById('nbaBenchmarkCards');
        if (container) {
            container.innerHTML = cardsHtml.join('');
        }
    };

    // Toggle highlights on/off
    window.toggleNbaHighlights = function() {
        const state = !window.nbaState.highlightsOn;
        window.nbaState.highlightsOn = state;

        const table = window.nbaState.tableElem || document.querySelector('table');
        if (table) {
            if (state) {
                table.classList.remove('nba-highlights-off');
            } else {
                table.classList.add('nba-highlights-off');
            }
        }

        const btn = document.getElementById('btnNbaToggleHighlights');
        const txt = document.getElementById('nbaHighlightStateText');
        if (btn && txt) {
            if (state) {
                btn.classList.add('active');
                txt.innerText = 'ON';
            } else {
                btn.classList.remove('active');
                txt.innerText = 'OFF';
            }
        }
    };

    // Toggle benchmark cards visibility
    window.toggleNbaBenchmarkCards = function() {
        const section = document.getElementById('nbaBenchmarkSection');
        if (!section) return;
        const state = (section.style.display === 'none');
        section.style.display = state ? 'block' : 'none';
        window.nbaState.cardsVisible = state;

        const btn = document.getElementById('btnNbaToggleCards');
        const txt = document.getElementById('nbaCardsStateText');
        if (btn && txt) {
            txt.innerText = state ? 'Visible' : 'Hidden';
            if (state) btn.classList.add('active');
            else btn.classList.remove('active');
        }
    };

    // Auto-run when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            window.initNbaReportSamples();
        });
    } else {
        setTimeout(function() {
            window.initNbaReportSamples();
        }, 50);
    }

})();
</script>
