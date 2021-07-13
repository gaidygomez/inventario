const excel = document.getElementById('excel');
const pdf = document.getElementById('pdf');
let startDate = document.getElementById('start_date');
let endDate = document.getElementById('end_date');
let user = document.getElementById('user');

$('#start_date').flatpickr({
	locale: 'es',
	dateFormat: 'd-m-Y',
	allowInput: false
});

$('#end_date').flatpickr({
	locale: 'es',
	dateFormat: 'd-m-Y',
	allowInput: false
});

excel.addEventListener('click', () => {
	$.ajax({
	  url: 'pdf/reporteXLSX.php',
	  type: 'GET',
	  dataType: 'json',
	  data: {
		user: user.value,
		startDate: startDate.value,
		endDate: endDate.value
	  },
	  complete: function(xhr, textStatus) {
	    //called when complete
	  },
	  success: function(data, textStatus, xhr) {
	    console.log(data)
	  },
	  error: function(xhr, textStatus, errorThrown) {
	    console.error(xhr)
	  }
	});
	
});

pdf.addEventListener('click', () => {
	$.ajax({
	  url: 'pdf/reportePDF.php',
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
	    Swal.fire({
			position: 'top-end',
			icon: 'error',
			title: 'Un error ha ocurrido',
			showConfirmButton: false,
			timer: 2000
		})
	  }
	});
})