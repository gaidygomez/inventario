let productos = []

$('#stock_product').change(function(event) {
	if ($('#stock_sucursal').val() !== '' && $(this).val() !== '') {

		productos.push({
			idsucursal: $('#stock_sucursal').val(),
			sucursal: $('#stock_sucursal option:selected').text().trim(), 
			idproducto: $(this).val(),
			producto: $('#stock_product option:selected').text().trim()
		})

		tableStock();
	}
});

$('#stock_sucursal').change(function(event) {
	if ($('#stock_product').val() !== '' && $(this).val() !== '') {

		productos.push({
			idsucursal: $(this).val(),
			sucursal: $('#stock_sucursal option:selected').text().trim(), 
			idproducto: $('#stock_product').val(),
			producto: $('#stock_product option:selected').text().trim()
		})

		tableStock();
	}
});

$('#generar_stock').click(function(event) {
	event.preventDefault()
	let qty = document.querySelectorAll('.stock-qty');

	if (productos.length === 0) {
		Swal.fire({
		    position: 'center',
		    icon: 'error',
		    title: 'Debe agregar productos.',
		    showConfirmButton: false,
		    timer: 2000
		})
	} else {
		productos.forEach((elem, index) => elem.qty = qty[index].value)


		$.ajax({
		  url: 'procesarStock.php',
		  type: 'POST',
		  dataType: 'json',
		  data: {data: JSON.stringify(productos)},
		  complete: function(xhr, textStatus) {
		    //called when complete
		  },
		  success: function(data, textStatus, xhr) {

			productos = [];

			Swal.fire({
				position: 'center',
				icon: 'success',
				title: data.success,
				showConfirmButton: false,
				timer: 2000
			})

			tableStock();
		  },
		  error: function(xhr, textStatus, errorThrown) {
		    Swal.fire({
				position: 'center',
				icon: 'error',
				title: xhr.responseJSON.error,
				showConfirmButton: false,
				timer: 2000
			})
		  }
		});
		
	}
});

function tableStock() {
	let html = ''

	Swal.fire({
	    position: 'center',
	    icon: 'success',
	    title: 'Producto Añadido',
	    showConfirmButton: false,
	    timer: 2000
	})

	productos.map((elem, i) => {
		html += `
		<tr>
		  <td>${i+1}</td>
		  <td>${elem.producto}</td>
		  <td>
		    <input class="form-control stock-qty" type="text" placeholder="Cantidad" value="1">
		  </td>
		  <td>${elem.sucursal}</td>
		</tr>
		`
	})

	$('#table-stock').html(html);

	$('#stock_product').val('') 

	$('#stock_sucursal').val('')
}