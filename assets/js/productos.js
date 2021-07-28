$(function() {
	let table;

	$('body').on('submit', '.confirmar', function(event) {
		event.preventDefault();
	    
	    Swal.fire({
	        title: 'Esta seguro de eliminar?',
	        icon: 'warning',
	        showCancelButton: true,
	        confirmButtonColor: '#3085d6',
	        cancelButtonColor: '#d33',
	        confirmButtonText: 'SI, Eliminar!'
	    }).then((result) => {
	        if (result.isConfirmed) {
	            this.submit();

	            table.ajax.reload();
	        }
	    })
	});

	$('#select-products').change(function(event) {
		if ($(this).val() !== '') {
			$('#info-table').remove()

			$('#reportSucursal').css({display: 'flex'})
			
			if ($.fn.DataTable.isDataTable("#table-productos")) {
			  $('#table-productos').DataTable().clear().destroy();
			}

			table = $('#table-productos').DataTable({
				ajax: {
					url: 'tableProductos.php',
					data: { sucursal: $('#select-products').val() },
				},
				columns: [
					{data: 'id', name: 'id'},
					{data: 'codigo', name: 'codigo'},
					{data: 'descripcion', name: 'producto'},
					{data: 'compra', name: 'compra'},
					{data: 'precio', name: 'precio'},
					//{data: 'sabor', name: 'sabor'},
					{
						data: 'estado', 
						name: 'estado',
						render: function (data, type, row, meta) {
							if (data) {
								return '<span class="badge badge-pill badge-success">Activo</span>';
							} else {
								return '<span class="badge badge-pill badge-danger">Inactivo</span>';
							}
						}
					},
					{
						render: function (data, type, row) {
							if (row.estado) {
								let sucursal = $('#select-products').val();

								return `
								<a href="agregar_producto.php?id=${row.id}&sucursal=${sucursal}" class="btn btn-primary">
									<i class='fas fa-audio-description'></i>
								</a>
	                        	<a href="editar_producto.php?id=${row.id}" class="btn btn-success">
	                             	<i class='fas fa-edit'></i>
	                     		</a>
	                            <a href="consulta_stock.php?id=${row.id}" class="btn btn-info">
	                            	<i class="fas fa-cubes"></i>
	                        	</a>
		                        <form action="eliminar_producto.php?id=${row.id}" method="post" class="confirmar d-inline">
		                        	<button class="btn btn-danger" type="submit"><i class='fas fa-trash-alt'></i> </button>
		                        </form>
		                        <a href="pdf/reporteProductos.php?producto=${row.id}" target="_blank" class="btn btn-danger">
		                        	<i class="far fa-file-pdf"></i>
		                        </a>
								`
							}
						}
					}
				]
			})
		} else {
			$('#reportSucursal').css({display: 'none'})

			table.clear().destroy()

			$('tbody').html(`
			<td colspan="8" class="text-center" id="info-table">
				Escoja una sucursal
			</td>
			`)
		}
	});

	$('#reporteSucursal').click(function(event) {
		$.ajax({
		  url: 'pdf/reporteSucursal.php',
		  type: 'GET',
		  //dataType: 'json',
		  xhrFields: {responseType: "blob"},
		  data: { sucursal: $('#select-products').val() },
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
		    console.error(xhr)
		  }
		});
		
	});
});
