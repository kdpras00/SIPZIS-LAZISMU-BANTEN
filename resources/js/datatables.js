/**
 * SIPZIS DataTables — shared initialiser
 *
 * Uses datatables.net-dt + datatables.net-responsive-dt.
 * The Responsive extension adds the +/- child-row toggle when columns
 * overflow the viewport on mobile.
 *
 * Usage
 * ──────
 * Static tables  → initTable('#my-table') once on DOMContentLoaded.
 * AJAX tables    → call initTable('#my-table') again after every DOM rebuild.
 *
 * window.SipzisTable.initTable(selector, opts?) is exposed globally so
 * @push('scripts') blocks in Blade can call it without an import.
 */

import DataTable from 'datatables.net-dt';
import Responsive from 'datatables.net-responsive-dt';

/**
 * Initialise (or re-initialise) a DataTable.
 *
 * @param {string} selector  CSS selector, e.g. '#table-muzakki'
 * @param {object} opts      Optional DataTables settings overrides
 * @returns {DataTable|null}
 */
export function initTable(selector, opts = {}) {
    const el = document.querySelector(selector);
    if (!el) return null;

    // Skip initialization if the table is in empty state (only contains a placeholder row with colspan)
    const firstRowColspan = el.querySelector('tbody tr:first-child td[colspan]');
    if (firstRowColspan) {
        return null;
    }

    // Destroy any existing instance before re-init (needed after AJAX rebuild)
    if (DataTable.isDataTable(selector)) {
        new DataTable(selector).destroy();
    }

    return new DataTable(selector, {
        // We manage search and pagination via our own AJAX UI
        searching:  false,
        paging:     false,
        info:       false,
        ordering:   false,
        autoWidth:  false,

        // Responsive: inline child-row expansion with +/- toggle
        responsive: {
            details: {
                type:   'inline',
                target: 'td.dtr-control, th.dtr-control',
            },
        },

        language: {
            emptyTable:   'Tidak ada data tersedia',
            zeroRecords:  'Tidak ada data yang sesuai',
            processing:   'Memproses...',
        },

        // Caller overrides last so they can change anything above
        ...opts,
    });
}

/**
 * Initialise all static admin tables (those never rebuilt by AJAX).
 * Called automatically on DOMContentLoaded.
 */
function initStaticTables() {
    [
        '#table-campaigns',
        '#table-programs-zakat',
        '#table-programs-infaq',
        '#table-programs-shadaqah',
        '#table-programs-pilar',
        '#table-reports-incoming',
        '#table-reports-outgoing',
        '#table-muzakki',
        '#table-mustahik',
        '#table-distributions',
        '#table-payments',
    ].forEach(sel => initTable(sel));
}

document.addEventListener('DOMContentLoaded', initStaticTables);

// Expose globally so Blade @push('scripts') blocks can call initTable
// without needing an ES-module import.
window.SipzisTable = { initTable };
