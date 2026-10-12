{{-- 
    NBA Accreditation Audit - Physical Filing Samples Helper (Revision 2021)
    Provides automatic identification, highlighting, sorting, and summary generation
    for Best, Average, and Low student samples across Formative and Summative assessments.
--}}
<style>
    /* NBA Sample Badges */
    .nba-badge {
        display: inline-block;
        font-size: 8px;
        font-weight: 800;
        letter-spacing: 0.3px;
        padding: 1px 4px;
        border-radius: 3px;
        margin-left: 4px;
        vertical-align: middle;
        line-height: 1.2;
        text-transform: uppercase;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
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

    /* Interactive Toolbar */
    .nba-toolbar-container {
        background: #0f172a;
        color: #f8fafc;
        border-radius: 8px;
        padding: 10px 14px;
        margin-bottom: 16px;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -2px rgba(0,0,0,0.1);
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    }
    .nba-toolbar-left {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }
    .nba-toolbar-title {
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        color: #38bdf8;
        display: flex;
        align-items: center;
        gap: 6px;
        margin-right: 6px;
    }
    .nba-btn {
        background: #1e293b;
        color: #e2e8f0;
        border: 1px solid #334155;
        border-radius: 6px;
        padding: 5px 10px;
        font-size: 11px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: all 0.15s ease;
        line-height: 1.3;
    }
    .nba-btn:hover {
        background: #334155;
        color: #ffffff;
        border-color: #475569;
    }
    .nba-btn.active {
        background: #0284c7;
        color: #ffffff;
        border-color: #38bdf8;
    }
    .nba-btn-filter.active {
        background: #16a34a;
        color: #ffffff;
        border-color: #4ade80;
    }
    .nba-select {
        background: #1e293b;
        color: #e2e8f0;
        border: 1px solid #334155;
        border-radius: 6px;
        padding: 5px 8px;
        font-size: 11px;
        font-weight: 500;
        cursor: pointer;
        outline: none;
    }
    .nba-select:focus {
        border-color: #38bdf8;
    }
    .nba-toolbar-right {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 10px;
        color: #94a3b8;
    }
    .nba-legend-item {
        display: inline-flex;
        align-items: center;
        gap: 3px;
        font-size: 9.5px;
        font-weight: 600;
    }

    /* NBA Physical Filing Samples Summary Card */
    .nba-filing-card {
        background: #ffffff;
        border: 1.5px solid #0284c7;
        border-radius: 8px;
        margin-bottom: 20px;
        margin-top: 10px;
        box-shadow: 0 2px 8px rgba(2, 132, 199, 0.08);
        overflow: hidden;
        font-family: inherit;
    }
    .nba-filing-card-header {
        background: #f0f9ff;
        border-bottom: 1px solid #bae6fd;
        padding: 8px 12px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 8px;
    }
    .nba-filing-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 10px;
        table-layout: auto;
    }
    .nba-filing-table th {
        background: #f8fafc;
        color: #0f172a;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 9px;
        padding: 6px 8px;
        border: 1px solid #e2e8f0;
        text-align: left;
    }
    .nba-filing-table td {
        padding: 6px 8px;
        border: 1px solid #e2e8f0;
        vertical-align: middle;
        font-size: 10px;
    }
    .nba-filing-table tr:nth-child(even) {
        background-color: #fafbfc;
    }
    .nba-sample-box {
        display: flex;
        flex-direction: column;
        gap: 1px;
    }
    .nba-sample-name {
        font-weight: 700;
        font-size: 10.5px;
        color: #0f172a;
    }
    .nba-sample-meta {
        font-size: 9px;
        color: #64748b;
        font-family: monospace;
    }

    /* Print styles */
    @media print {
        .nba-toolbar-container, .no-print {
            display: none !important;
        }
        .nba-badge {
            display: inline-block !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        .nba-cell-best, .nba-cell-avg, .nba-cell-low {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        .nba-filing-card {
            border: 1.5px solid #000 !important;
            box-shadow: none !important;
            page-break-inside: avoid;
            margin-top: 15px !important;
            margin-bottom: 20px !important;
        }
        .nba-filing-card-header {
            background: #f1f5f9 !important;
            border-bottom: 1px solid #000 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        .nba-filing-table th, .nba-filing-table td {
            border: 1px solid #000 !important;
            color: #000 !important;
        }
    }
</style>

{{-- Reusable Toolbar HTML Template --}}
<div class="nba-toolbar-container no-print" id="nbaToolbar">
    <div class="nba-toolbar-left">
        <div class="nba-toolbar-title">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
            NBA Filing Audit (R21)
        </div>

        {{-- Toggle Highlights --}}
        <button type="button" class="nba-btn active" id="btnNbaToggleHighlights" onclick="toggleNbaHighlights()">
            🎯 Highlights: <span id="nbaHighlightStateText">ON</span>
        </button>

        {{-- Filter to Samples Only --}}
        <button type="button" class="nba-btn nba-btn-filter" id="btnNbaToggleFilter" onclick="toggleNbaSamplesFilter()">
            🔍 View: <span id="nbaFilterStateText">All Students</span>
        </button>

        {{-- Sort Dropdown --}}
        <div style="display: inline-flex; align-items: center; gap: 4px;">
            <label for="nbaSortSelect" style="font-size: 10px; color: #94a3b8; font-weight: 600;">Sort By:</label>
            <select class="nba-select" id="nbaSortSelect" onchange="handleNbaSortChange(this.value)">
                <option value="default">Default Order</option>
            </select>
        </div>

        {{-- Toggle Filing Index Card --}}
        <button type="button" class="nba-btn" id="btnNbaToggleCard" onclick="toggleNbaSummaryCard()">
            📋 Filing Index Card: <span id="nbaCardStateText">Visible</span>
        </button>
    </div>

    <div class="nba-toolbar-right">
        <span class="nba-legend-item"><span class="nba-badge nba-badge-best">BEST</span> Highest Mark</span>
        <span class="nba-legend-item"><span class="nba-badge nba-badge-avg">AVG</span> Mean Average</span>
        <span class="nba-legend-item"><span class="nba-badge nba-badge-low">LOW</span> Lowest Valid</span>
    </div>
</div>

{{-- Reusable Summary Card HTML Template --}}
<div class="nba-filing-card" id="nbaFilingSummaryCard">
    <div class="nba-filing-card-header">
        <div style="display: flex; align-items: center; gap: 8px;">
            <span style="font-size: 14px;">📁</span>
            <div>
                <strong style="font-size: 11px; text-transform: uppercase; color: #0369a1; letter-spacing: 0.5px;">
                    NBA Accreditation Physical Filing Samples Register
                </strong>
                <div style="font-size: 9.5px; color: #64748b;">
                    Three student samples per assessment (Best, Average, Low) required for physical Course File verification &amp; attainment analysis
                </div>
            </div>
        </div>
        <div id="nbaStatsBadge" style="font-size: 9.5px; font-weight: 700; color: #0284c7; background: #e0f2fe; padding: 2px 8px; border-radius: 4px;">
            Analyzing...
        </div>
    </div>
    <div style="overflow-x: auto;">
        <table class="nba-filing-table">
            <thead>
                <tr>
                    <th style="width: 22%;">Assessment / Test</th>
                    <th style="width: 13%; text-align: center;">Class Stats</th>
                    <th style="width: 21%; background-color: #f0fdf4; color: #166534;">🟢 Best Sample (Max)</th>
                    <th style="width: 22%; background-color: #fffbeb; color: #92400e;">🟡 Average Sample (~Mean)</th>
                    <th style="width: 22%; background-color: #fef2f2; color: #991b1b;">🔴 Low Sample (Min)</th>
                </tr>
            </thead>
            <tbody id="nbaFilingTableBody">
                <tr>
                    <td colspan="5" style="text-align: center; color: #64748b; padding: 10px;">Computing samples...</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
(function() {
    window.nbaState = {
        highlightsOn: true,
        filterOnlySamples: false,
        summaryCardVisible: true,
        sampleRows: new Set(),
        assessments: {},
        originalRowsOrder: [],
        tableElem: null
    };

    window.initNbaReportSamples = function(config) {
        config = config || {};
        const tableSelector = config.tableSelector || 'table';
        const tables = document.querySelectorAll(tableSelector);
        if (!tables.length) return;

        // Pick the marks data table (usually table with tbody)
        let mainTable = null;
        for (let t of tables) {
            if (t.id === 'nbaFilingSummaryCard' || t.classList.contains('nba-filing-table') || t.classList.contains('meta-info') || t.classList.contains('meta-table')) continue;
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

        // Save original rows order
        window.nbaState.originalRowsOrder = rows.slice();

        // 1. Scan each row and extract marked cells
        const assessmentsMap = {};
        const sampleRowsSet = new Set();

        rows.forEach((row, rIdx) => {
            row.setAttribute('data-nba-row-idx', rIdx);

            // Student identification
            let name = row.getAttribute('data-student-name') || '';
            let roll = row.getAttribute('data-student-roll') || '';
            let reg = row.getAttribute('data-student-reg') || '';

            // Fallback parsing if attributes missing
            if (!name) {
                const cells = row.querySelectorAll('td');
                if (cells.length >= 3) {
                    roll = cells[0].innerText.trim();
                    name = (cells[1].innerText.trim().length > 3) ? cells[1].innerText.trim() : (cells[2] ? cells[2].innerText.trim() : '');
                    reg = (cells[2] && cells[2].innerText.trim().length > 4) ? cells[2].innerText.trim() : (cells[3] ? cells[3].innerText.trim() : '');
                }
            }

            // Find all cells configured with data-nba-assessment
            const cells = row.querySelectorAll('[data-nba-assessment]');
            cells.forEach(cell => {
                const aName = cell.getAttribute('data-nba-assessment');
                if (!aName) return;

                let rawVal = cell.getAttribute('data-nba-val');
                let numVal = null;

                if (rawVal !== null && rawVal !== '' && rawVal !== '-') {
                    numVal = parseFloat(rawVal);
                } else {
                    // Try parsing from cell innerText
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
                    val: numVal,
                    student: { name: name || 'Student #' + (rIdx + 1), roll: roll || '-', reg: reg || '-' }
                });
            });
        });

        window.nbaState.assessments = assessmentsMap;

        // 2. Compute Best, Avg, Low for each assessment
        const summaryRowsHtml = [];
        const sortOptions = [{ value: 'default', label: 'Default Roll/Serial Order' }];

        Object.keys(assessmentsMap).forEach(aName => {
            const items = assessmentsMap[aName];
            const validItems = items.filter(it => it.val !== null && !isNaN(it.val));

            // Populate sort select
            sortOptions.push({ value: aName + '__desc', label: `${aName}: High to Low` });
            sortOptions.push({ value: aName + '__asc', label: `${aName}: Low to High` });
            sortOptions.push({ value: aName + '__samples', label: `${aName}: Samples First (Best/Avg/Low)` });

            if (validItems.length === 0) {
                summaryRowsHtml.push(`
                    <tr>
                        <td style="font-weight: 700; color: #0f172a;">${aName}</td>
                        <td style="text-align: center; color: #64748b;">No data</td>
                        <td colspan="3" style="text-align: center; color: #94a3b8; font-style: italic;">No numeric marks submitted yet</td>
                    </tr>
                `);
                return;
            }

            // Calculations
            const vals = validItems.map(it => it.val);
            const maxVal = Math.max(...vals);
            const minVal = Math.min(...vals);
            const sumVal = vals.reduce((acc, v) => acc + v, 0);
            const meanVal = sumVal / validItems.length;

            // Find Best
            const bestItems = validItems.filter(it => it.val === maxVal);
            const primaryBest = bestItems[0];

            // Find Low
            const lowItems = validItems.filter(it => it.val === minVal);
            const primaryLow = (minVal < maxVal) ? lowItems[0] : primaryBest;

            // Find Average (closest to mean, preferring one not chosen as best/low if available)
            let remainingItems = validItems.filter(it => it !== primaryBest && it !== primaryLow);
            if (remainingItems.length === 0) {
                remainingItems = validItems;
            }
            remainingItems.sort((a, b) => Math.abs(a.val - meanVal) - Math.abs(b.val - meanVal));
            const primaryAvg = remainingItems[0] || primaryBest;

            // Mark cells with badges & classes
            // Best
            bestItems.forEach(it => {
                it.cell.classList.add('nba-cell-best');
                if (!it.cell.querySelector('.nba-badge-best')) {
                    it.cell.insertAdjacentHTML('beforeend', ` <span class="nba-badge nba-badge-best" title="NBA Best Sample (Score: ${it.val})">BEST</span>`);
                }
                it.row.setAttribute('data-has-nba-sample', 'true');
                sampleRowsSet.add(it.row);
            });

            // Low
            if (minVal < maxVal) {
                lowItems.forEach(it => {
                    it.cell.classList.add('nba-cell-low');
                    if (!it.cell.querySelector('.nba-badge-low')) {
                        it.cell.insertAdjacentHTML('beforeend', ` <span class="nba-badge nba-badge-low" title="NBA Low Sample (Score: ${it.val})">LOW</span>`);
                    }
                    it.row.setAttribute('data-has-nba-sample', 'true');
                    sampleRowsSet.add(it.row);
                });
            }

            // Avg
            if (primaryAvg && !primaryAvg.cell.classList.contains('nba-cell-best') && !primaryAvg.cell.classList.contains('nba-cell-low')) {
                primaryAvg.cell.classList.add('nba-cell-avg');
                if (!primaryAvg.cell.querySelector('.nba-badge-avg')) {
                    primaryAvg.cell.insertAdjacentHTML('beforeend', ` <span class="nba-badge nba-badge-avg" title="NBA Average Sample (Score: ${primaryAvg.val}, Class Avg: ${meanVal.toFixed(1)})">AVG</span>`);
                }
                primaryAvg.row.setAttribute('data-has-nba-sample', 'true');
                sampleRowsSet.add(primaryAvg.row);
            }

            // Summary row format
            summaryRowsHtml.push(`
                <tr>
                    <td style="font-weight: 700; color: #0f172a;">
                        <span style="display: block; font-size: 11px;">${aName}</span>
                    </td>
                    <td style="text-align: center; font-size: 9px; line-height: 1.4;">
                        <div><strong>Avg:</strong> ${meanVal.toFixed(1)}</div>
                        <div style="color: #64748b;">Max: ${maxVal} | Min: ${minVal}</div>
                        <div style="color: #0284c7; font-weight: 600;">(${validItems.length} Submissions)</div>
                    </td>
                    <td style="background-color: #f0fdf4;">
                        <div class="nba-sample-box">
                            <span class="nba-sample-name" style="color: #166534;">
                                ${primaryBest.student.name} <span class="nba-badge nba-badge-best">${primaryBest.val}</span>
                            </span>
                            <span class="nba-sample-meta">Roll: ${primaryBest.student.roll} &bull; Reg: ${primaryBest.student.reg}</span>
                        </div>
                    </td>
                    <td style="background-color: #fffbeb;">
                        <div class="nba-sample-box">
                            <span class="nba-sample-name" style="color: #92400e;">
                                ${primaryAvg.student.name} <span class="nba-badge nba-badge-avg">${primaryAvg.val}</span>
                            </span>
                            <span class="nba-sample-meta">Roll: ${primaryAvg.student.roll} &bull; Reg: ${primaryAvg.student.reg} (&Delta; ${Math.abs(primaryAvg.val - meanVal).toFixed(1)} to avg)</span>
                        </div>
                    </td>
                    <td style="background-color: #fef2f2;">
                        <div class="nba-sample-box">
                            <span class="nba-sample-name" style="color: #991b1b;">
                                ${primaryLow.student.name} <span class="nba-badge nba-badge-low">${primaryLow.val}</span>
                            </span>
                            <span class="nba-sample-meta">Roll: ${primaryLow.student.roll} &bull; Reg: ${primaryLow.student.reg}</span>
                        </div>
                    </td>
                </tr>
            `);
        });

        window.nbaState.sampleRows = sampleRowsSet;

        // Render summary rows
        const summaryTbody = document.getElementById('nbaFilingTableBody');
        if (summaryTbody) {
            summaryTbody.innerHTML = summaryRowsHtml.join('');
        }

        // Update stats badge
        const statsBadge = document.getElementById('nbaStatsBadge');
        if (statsBadge) {
            statsBadge.innerHTML = `Identified <strong>${sampleRowsSet.size}</strong> distinct sample students across ${Object.keys(assessmentsMap).length} assessments`;
        }

        // Populate sort select
        const sortSelect = document.getElementById('nbaSortSelect');
        if (sortSelect) {
            sortSelect.innerHTML = sortOptions.map(opt => `<option value="${opt.value}">${opt.label}</option>`).join('');
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

    // Toggle filter: all students vs sample students only
    window.toggleNbaSamplesFilter = function() {
        const state = !window.nbaState.filterOnlySamples;
        window.nbaState.filterOnlySamples = state;

        const table = window.nbaState.tableElem || document.querySelector('table');
        if (!table) return;

        const tbody = table.querySelector('tbody');
        if (!tbody) return;

        const rows = Array.from(tbody.querySelectorAll('tr'));
        const sampleSet = window.nbaState.sampleRows;

        rows.forEach(row => {
            if (state) {
                // Show only sample rows
                if (sampleSet.has(row) || row.getAttribute('data-has-nba-sample') === 'true') {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            } else {
                row.style.display = '';
            }
        });

        const btn = document.getElementById('btnNbaToggleFilter');
        const txt = document.getElementById('nbaFilterStateText');
        if (btn && txt) {
            if (state) {
                btn.classList.add('active');
                txt.innerText = `NBA Samples Only (${sampleSet.size})`;
            } else {
                btn.classList.remove('active');
                txt.innerText = 'All Students';
            }
        }
    };

    // Toggle summary card visibility
    window.toggleNbaSummaryCard = function() {
        const card = document.getElementById('nbaFilingSummaryCard');
        if (!card) return;
        const state = (card.style.display === 'none');
        card.style.display = state ? 'block' : 'none';

        const btn = document.getElementById('btnNbaToggleCard');
        const txt = document.getElementById('nbaCardStateText');
        if (btn && txt) {
            txt.innerText = state ? 'Visible' : 'Hidden';
            if (state) btn.classList.add('active');
            else btn.classList.remove('active');
        }
    };

    // Handle sort dropdown change
    window.handleNbaSortChange = function(sortValue) {
        const table = window.nbaState.tableElem;
        if (!table) return;
        const tbody = table.querySelector('tbody');
        if (!tbody) return;

        if (sortValue === 'default') {
            window.nbaState.originalRowsOrder.forEach(r => tbody.appendChild(r));
            return;
        }

        const [aName, direction] = sortValue.split('__');
        const items = window.nbaState.assessments[aName];
        if (!items) return;

        // Map row to item
        const rowMap = new Map();
        items.forEach(it => rowMap.set(it.row, it));

        const rows = Array.from(tbody.querySelectorAll('tr'));

        rows.sort((rowA, rowB) => {
            const itA = rowMap.get(rowA);
            const itB = rowMap.get(rowB);
            const valA = (itA && itA.val !== null && !isNaN(itA.val)) ? itA.val : -999999;
            const valB = (itB && itB.val !== null && !isNaN(itB.val)) ? itB.val : -999999;

            if (direction === 'desc') {
                return valB - valA;
            } else if (direction === 'asc') {
                return valA - valB;
            } else if (direction === 'samples') {
                const isSampleA = (itA && (itA.cell.classList.contains('nba-cell-best') || itA.cell.classList.contains('nba-cell-avg') || itA.cell.classList.contains('nba-cell-low'))) ? 1 : 0;
                const isSampleB = (itB && (itB.cell.classList.contains('nba-cell-best') || itB.cell.classList.contains('nba-cell-avg') || itB.cell.classList.contains('nba-cell-low'))) ? 1 : 0;
                if (isSampleA !== isSampleB) return isSampleB - isSampleA;
                return valB - valA;
            }
            return 0;
        });

        rows.forEach(r => tbody.appendChild(r));
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
