  /*Datetimepicker*/
  $(function () {
    $('').datetimepicker({
        format: 'DD-MM-YYYY HH:mm A',
        icons: {
            time: "fa fa-clock-o",
            date: "fa fa-calendar",
            up: "fa fa-arrow-up",
            down: "fa fa-arrow-down",
            previous: "fa fa-arrow-left",
            next: "fa fa-arrow-right",
            clear: "fa fa-trash",
            close: "fa fa-remove"
        }/*,
        showClear: true,
        showClose: true*/
    });
  });
  /*Datepicker*/
  $(function () {
          $('#start_date, #end_date, #road_day, #invoice_date, #pay_date, #citation_date, #paid_date, #expiration_date').datepicker({
              format: 'dd-mm-yyyy',
              icons: {
                  previous: "fa fa-arrow-left",
                  next: "fa fa-arrow-right",
                  clear: "fa fa-trash",
                  close: "fa fa-remove"
              },
              showClear: true,
              showClose: true,
              autoClose: true
          });
      });
  $(function () {
    $('#transaction_start, #transaction_end, #date_for').datepicker({
      format: 'mm/dd/yyyy',
      icons: {
        previous: "fa fa-arrow-left",
        next: "fa fa-arrow-right",
        clear: "fa fa-trash",
        close: "fa fa-remove"
      },
      showclear: true,
      showclose: true,
      autoclose: true
    });
  });
  /*Datepicker*/
  $(function () {
          $('#filter_month, #post_month').datepicker({
              format: 'yyyy-mm',
              startView: 'months', 
              minViewMode: 'months',
              icons: {
                  previous: "fa fa-arrow-left",
                  next: "fa fa-arrow-right",
                  clear: "fa fa-trash",
                  close: "fa fa-remove"
              },
              showClear: true,
              showClose: true
          });
      });
  
   /*DataTables*/
  $(function () {
    $('#basic-datatables').DataTable({
      'destroy': true,
      "responsive": true,
      columnDefs: [
            { responsivePriority: 1, targets: 0 },
            { responsivePriority: 10001, targets: -2 },
            { responsivePriority: 2, targets: -1 }
        ],
      dom: 'Blfrtip',
      buttons: [
          {
              extend:    'excelHtml5',
              text:      '<i class="fa fa-file-excel"></i>',
              titleAttr: 'Export to Excel',
              exportOptions: {
                            columns: "thead th:not(.noExport)"
                        }
          },
          {
              extend:    'pdfHtml5',
              text:      '<i class="fa fa-file-pdf"></i>',
              titleAttr: 'Export to PDF',
              orientation: 'portrait', 
              pageSize: 'A4',
                  /*customize: function() {
                       $('#table_id')
                           .addClass('compact');
                   },*/
                  exportOptions: {
                                columns: "thead th:not(.noExport)"
                            }
          },
          {
            extend:    'print', 
            text:      '<i class="fa fa-print"></i>',
            titleAttr: 'Print',
            orientation: 'portrait', 
            pageSize: 'A4',
                /*customize: function(win) {
                     $(win.document.body).find('table')
                         .addClass('compact')
                         .css('font-size', '10pt');
                 },*/
                exportOptions: {
                              columns: "thead th:not(.noExport)"
                          }
          },
          {
            extend:    'colvis', 
            text:      '<i class="fa fa-eye"></i>',
            titleAttr: 'Visible columns'
          }
      ]
    });
  });

$(function() {
  var currentPath = window.location.pathname;
  $("nav a").each(function() {
     var src = $(this).attr("href");
     if (src.indexOf(currentPath) != -1) {
       $(this).css({"background-color" : "#59b2f6", "color" : "#ffffff"});
     }
  });
});
