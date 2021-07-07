const excel = document.getElementById('excel');
const pdf = document.getElementById('pdf');
let startDate = document.getElementById('start_date');
let endDate = document.getElementById('end_date');
let user = document.getElementById('user');

$('#start_date').datepicker({
    format: "dd/mm/yyyy",
    language: "es",
    autoclose: true,
    startDate: new Date()
});

$('#end_date').datepicker({
    format: "dd/mm/yyyy",
    language: "es",
    autoclose: true,
    startDate: new Date()
});

excel.addEventListener('click', () => {
	$.ajax({
	  url: '/path/to/file',
	  type: 'GET',
	  dataType: 'json',
	  data: {param1: 'value1'},
	  complete: function(xhr, textStatus) {
	    //called when complete
	  },
	  success: function(data, textStatus, xhr) {
	    //called when successful
	  },
	  error: function(xhr, textStatus, errorThrown) {
	    //called when there is an error
	  }
	});
	
});

pdf.addEventListener('click', () => {
	$.ajax({
	  url: 'pdf/reporteVentas.php',
	  type: 'GET',
	  xhrFields: {responseType: "blob"},
	  data: {
	  	user: user.value,
	  	startDate: startDate.value,
	  	endDate: endDate.value
	  },
	  complete: function(xhr, textStatus) {
	    //called when complete
	  },
	  success: function(data, textStatus, xhr) {
	    // don't set the MIME type to pdf or it will display
        var blob = new Blob([data], {type: "application/pdf"});
        // build a blob URL
        var bloburl = window.URL.createObjectURL(blob);
        // trigger download for edge
        var link = $("<a>").attr({href: bloburl, download: "test.pdf"}).click();
        // trigger download for other browsers
        window.open(bloburl, '_blank');

	  },
	  error: function(xhr, textStatus, errorThrown) {
	    console.error(xhr.responseJSON.error);
	  }
	});
})