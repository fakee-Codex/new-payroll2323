// Call the dataTables jQuery plugin
$(document).ready(function() {
  $('#dataTable').DataTable({
    "lengthMenu": [ [100, 10, 25, 50], [100, 10, 25, 50] ], // Define the options for "Show entries"
    "iDisplayLength": 100 // Set the default value to 100
  });
});
