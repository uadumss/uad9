// Call the dataTables jQuery plugin
$(document).ready(function() {
  $('[id^="dataTable"]').each(function() {
    var $table = $(this);

    if ($.fn.dataTable.isDataTable(this)) {
      return;
    }

    var pageLength = parseInt($table.data('page-length'), 10);
    var searchDelay = parseInt($table.data('search-delay'), 10);
    var deferRender = String($table.data('defer-render')) === '1';

    $table.DataTable({
      pageLength: isNaN(pageLength) ? 500 : pageLength,
      searchDelay: isNaN(searchDelay) ? 0 : searchDelay,
      deferRender: deferRender
    });
  });
});
