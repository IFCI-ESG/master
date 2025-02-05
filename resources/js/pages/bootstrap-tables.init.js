/*
Template Name: Ubold - Responsive Bootstrap 5 Admin Dashboard
Author: CoderThemes
Website: https://coderthemes.com/
Contact: support@coderthemes.com
File: Bootstrap tables
*/


import 'bootstrap-table/dist/bootstrap-table';
$(document).ready(function () {


    // BOOTSTRAP TABLE - CUSTOM TOOLBAR
    // =================================================================
    // Require Bootstrap Table
    // http://bootstrap-table.wenzhixin.net.cn/
    // =================================================================
    var $table = $('#demo-custom-toolbar'), $remove = $('#demo-delete-row');


    $table.on('check.bs.table uncheck.bs.table check-all.bs.table uncheck-all.bs.table', function () {
        $remove.prop('disabled', !$table.bootstrapTable('getSelections').length);
    });

    $remove.click(function () {
        var ids = $.map($table.bootstrapTable('getSelections'), function (row) {
            return row.id
        });
        $table.bootstrapTable('remove', {
            field: 'id',
            values: ids
        });
        $remove.prop('disabled', true);
    });


});

/**
 * Hack for table loading issue - ideally this should be fixed in plugin code itself.
 */
$(window).on('load', function () {
    $('[data-toggle="table"]').show();
});

// Sample format for Invoice Column.
// =================================================================
function invoiceFormatter(value, row) {
    return '<a href="#" class="btn-link" > Order #' + value + '</a>';
}

// Sample Format for User Name Column.
// =================================================================
function nameFormatter(value, row) {
    return '<a href="#" class="btn-link" > ' + value + '</a>';
}

// Sample Format for Order Date Column.
// =================================================================
function dateFormatter(value, row) {
    var icon = row.id % 2 === 0 ? 'fa-star' : 'fa-user';
    return '<span class="text-muted"> ' + value + '</span>';
}


// Sample Format for Order Status Column.
// =================================================================
function statusFormatter(value, row) {
    var labelColor;
    if (value == "Paid") {
        labelColor = "success";
    } else if (value == "Unpaid") {
        labelColor = "warning";
    } else if (value == "Shipped") {
        labelColor = "info";
    } else if (value == "Refunded") {
        labelColor = "danger";
    }
    var icon = row.id % 2 === 0 ? 'fa-star' : 'fa-user';
    return '<div class="badge label-table bg-' + labelColor + '"> ' + value + '</div>';
}


// Sort Price Column
// =================================================================
function priceSorter(a, b) {
    a = +a.substring(1); // remove $
    b = +b.substring(1);
    if (a > b) return 1;
    if (a < b) return -1;
    return 0;
}

window.icons = {
    refresh: 'mdi mdi-refresh',
    toggle: 'fa-refresh',
    toggleOn: 'fa-toggle-on',
    toggleOff: 'fa-toggle-on',
    columns: 'fas fa-th-list',
    paginationSwitchDown: 'glyphicon-collapse-down icon-chevron-down'
};
