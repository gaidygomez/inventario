document.addEventListener("DOMContentLoaded", function () {
    $('#tbl').DataTable();
    $(".confirmar").submit(function (e) {
        e.preventDefault();
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
            }
        })
    })
    $("#nom_cliente").autocomplete({
        minLength: 3,
        source: function (request, response) {
            $.ajax({
                url: "ajax.php",
                dataType: "json",
                data: {
                    q: request.term
                },
                success: function (data) {
                    response(data);
                }
            });
        },
        select: function (event, ui) {
            $("#idcliente").val(ui.item.id);
            $("#nom_cliente").val(ui.item.label);
            $("#tel_cliente").val(ui.item.telefono);
            $("#dir_cliente").val(ui.item.direccion);
        }
    })

    $("#producto").change(function() {
        if ($(this).val() != '' && $('#sucursal_venta').val() != '') {
            $.ajax({
              url: 'evaluarStock.php',
              type: 'GET',
              dataType: 'json',
              data: {
                producto: $(this).val(),
                sucursal: $('#sucursal_venta').val()
              },
              success: function(data, textStatus, xhr) {
                $.ajax({
                    url: 'ajax.php',
                    async: true,
                    dataType: 'json',
                    data: {
                        pro: $('#producto').val()
                    },
                    success: function(res) {
                        registrarDetalle(res.codproducto, res.precio)
                    }
                });
              },
              error: function(xhr, textStatus, errorThrown) {
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: xhr.responseJSON.error,
                    showConfirmButton: false,
                    timer: 2000
                })

                $('#producto').val('')

                $('#sucursal_venta').val('')
              }
            });
            
        } else {
            Swal.fire({
                position: 'center',
                icon: 'error',
                title: 'Escoja una Sucursal',
                showConfirmButton: false,
                timer: 2000
            })

            $(this).val('')
        }
    });

    $('#btn_generar').click(function (e) {
        e.preventDefault();
        
        let codigos = document.querySelectorAll('.codproducto');
        let quantities = document.querySelectorAll('.cantidad');
        let prices = document.querySelectorAll('.precio');
        let total = document.getElementById('total');
        let productos = []
        let precios = []
        let cantidades = []

        codigos.forEach(code => productos.push(code.value))

        quantities.forEach( q => cantidades.push(q.value))

        prices.forEach( p => precios.push(p.value))        

        $.ajax({
          url: 'procesarVenta.php',
          type: 'POST',
          dataType: 'json',
          data: {
            user: $('#idcliente').val(),
            sucursal: $('#sucursal_venta').val(),
            productos: productos,
            cantidades: cantidades,
            precios: precios,
            total: parseFloat(total.textContent).toFixed(2)            
          },
          complete: function(xhr, textStatus) {
            //called when complete
          },
          success: function(data, textStatus, xhr) {
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: data.success,
                showConfirmButton: false,
                timer: 2000
            })
            setTimeout(() => {
                generarPDF(data.cliente, data.venta);
                location.reload();
            }, 300);
          },
          error: function(xhr, textStatus, errorThrown) {
            Swal.fire({
                position: 'top-end',
                icon: 'error',
                title: xhr.responseJSON.error,
                showConfirmButton: false,
                timer: 2000
            })
          }
        });
    });
    if (document.getElementById("detalle_venta")) {
        listar();
    }
})

function listar() {
    let html = '';
    let detalle = 'detalle';
    $.ajax({
        url: "ajax.php",
        dataType: "json",
        data: {
            detalle: detalle
        },
        success: function (response){
            response.forEach((row, index) => {
                html += `
                <tr>
                <td>
                    <span class="producto-venta">${row['id']}</span>
                    <input type="hidden" class="codproducto" value="${row['codigo']}">
                </td>
                <td>${row['descripcion']}</td>
                <td>
                    <input class="form-control cantidad" type="text" value="${row['cantidad']}">
                </td>
                <td>
                    <input class="form-control precio" type="text" value="${row['precio_venta']}">
                </td>
                <td class="subtotal">${row['sub_total']}</td>
                <td><button class="btn btn-danger" type="button" onclick="deleteDetalle(${row['id']})">
                <i class="fas fa-trash-alt"></i></button></td>
                </tr>`;
            });
            document.querySelector("#detalle_venta").innerHTML = html;
            calcular();
            recalcularValores();
        }
    });
}

function registrarDetalle(id, precio) {
    if (document.getElementById('producto').value != '') {
        if (id != null) {
            let action = 'regDetalle';
            $.ajax({
                url: "ajax.php",
                type: 'POST',
                dataType: "json",
                data: {
                    id: id,
                    cant: 1,
                    action: action,
                    precio: precio
                },
                success: function (response) {
                    if (response == 'registrado') {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: '',
                            showConfirmButton: false,
                            timer: 2000
                        })
                        document.querySelector("#producto").value = '';
                        document.querySelector("#producto").focus();
                        listar();
                    } else if (response == 'actualizado') {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: 'Producto Actualizado',
                            showConfirmButton: false,
                            timer: 2000
                        })
                        document.querySelector("#producto").value = '';
                        document.querySelector("#producto").focus();
                        listar();
                    } else {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            title: 'Error al ingresar el producto',
                            showConfirmButton: false,
                            timer: 2000
                        })
                    }
                }
            });
        }
    }
}
function deleteDetalle(id) {
    let detalle = 'Eliminar'
    $.ajax({
        url: "ajax.php",
        data: {
            id: id,
            delete_detalle: detalle
        },
        success: function (response) {
            console.log(response);
            if (response == 'restado') {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Producto Descontado',
                    showConfirmButton: false,
                    timer: 2000
                })
                document.querySelector("#producto").value = '';
                document.querySelector("#producto").focus();
                listar();
            } else if (response == 'ok') {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Producto Eliminado',
                    showConfirmButton: false,
                    timer: 2000
                })
                document.querySelector("#producto").value = '';
                document.querySelector("#producto").focus();
                listar();
            } else {
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: 'Error al eliminar el producto',
                    showConfirmButton: false,
                    timer: 2000
                })
            }
        }
    });
}
function calcular() {
    // obtenemos todas las filas del tbody
    var filas = document.querySelectorAll("#tblDetalle tbody tr");

    var total = 0;

    // recorremos cada una de las filas
    filas.forEach(function (e) {

        // obtenemos las columnas de cada fila
        var columnas = e.querySelectorAll("td");

        // obtenemos los valores de la cantidad y importe
        var importe = parseFloat(columnas[4].textContent);

        total += importe;
    });

    // mostramos la suma total
    var filas = document.querySelectorAll("#tblDetalle tfoot tr td");
    filas[1].textContent = total.toFixed(2);
}

function recalcularValores() {
    let cantidades = document.querySelectorAll('.cantidad');
    let precios = document.querySelectorAll('.precio');
    let subtotal = document.querySelectorAll('.subtotal');
    let codigos = document.querySelectorAll('.producto-venta');

    cantidades.forEach((cantidad, key) => {
        cantidad.addEventListener('change', (e) => {
            let subt = parseFloat(e.target.value) * parseFloat(precios[key].value)

            subtotal[key].textContent = subt.toFixed(2);

            $.ajax({
                url: 'updateTemp.php',
                type: 'POST',
                data: {
                    id: codigos[key].innerText,
                    qty: e.target.value
                },
                success: function(data, textStatus, xhr) {
                    console.log(data)
                },
                error: function(xhr, textStatus, errorThrown) {
                    console.error(xhr)
                }
            })

            calcular();
        });
    });

    precios.forEach((precio, key) => {
        precio.addEventListener('change', (e) => {
            let subt = parseFloat(e.target.value) * parseFloat(cantidades[key].value);

            subtotal[key].textContent = subt.toFixed(2)

            $.ajax({
                url: 'updateTemp.php',
                type: 'POST',
                data: {
                    id: codigos[key].innerText,
                    price: e.target.value
                },
                success: function(data, textStatus, xhr) {
                    console.log(data)
                },
                error: function(xhr, textStatus, errorThrown) {
                    console.error(xhr)
                }
            })

            calcular();
        })
    })
}

function generarPDF(cliente, id_venta) {
    url = 'pdf/generar.php?cl=' + cliente + '&v=' + id_venta;
    window.open(url, '_blank');
}
if (document.getElementById("sales-chart")) {
    const action = "sales";
    $.ajax({
        url: 'chart.php',
        type: 'POST',
        data: {
            action
        },
        async: true,
        success: function (response) {
            if (response != 0) {
                var data = JSON.parse(response);
                var nombre = [];
                var cantidad = [];
                for (var i = 0; i < data.length; i++) {
                    nombre.push(data[i]['descripcion']);
                    cantidad.push(data[i]['existencia']);
                }
                try {
                    //Sales chart
                    var ctx = document.getElementById("sales-chart");
                    if (ctx) {
                        ctx.height = 150;
                        var myChart = new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: nombre,
                                type: 'line',
                                defaultFontFamily: 'Poppins',
                                datasets: [{
                                    label: "Disponible",
                                    data: cantidad,
                                    backgroundColor: 'transparent',
                                    borderColor: 'rgba(220,53,69,0.75)',
                                    borderWidth: 3,
                                    pointStyle: 'circle',
                                    pointRadius: 5,
                                    pointBorderColor: 'transparent',
                                    pointBackgroundColor: 'rgba(220,53,69,0.75)',
                                }, {
                                    label: "Cantidad",
                                    data: [0, 2, 3, 4, 5, 6, 7, 8, 9, 10],
                                    backgroundColor: 'transparent',
                                    borderColor: 'rgba(40,167,69,0.75)',
                                    borderWidth: 3,
                                    pointStyle: 'circle',
                                    pointRadius: 5,
                                    pointBorderColor: 'transparent',
                                    pointBackgroundColor: 'rgba(40,167,69,0.75)',
                                }]
                            },
                            options: {
                                responsive: true,
                                tooltips: {
                                    mode: 'index',
                                    titleFontSize: 12,
                                    titleFontColor: '#000',
                                    bodyFontColor: '#000',
                                    backgroundColor: '#fff',
                                    titleFontFamily: 'Poppins',
                                    bodyFontFamily: 'Poppins',
                                    cornerRadius: 3,
                                    intersect: false,
                                },
                                legend: {
                                    display: false,
                                    labels: {
                                        usePointStyle: true,
                                        fontFamily: 'Poppins',
                                    },
                                },
                                scales: {
                                    xAxes: [{
                                        display: true,
                                        gridLines: {
                                            display: false,
                                            drawBorder: false
                                        },
                                        scaleLabel: {
                                            display: false,
                                            labelString: 'Month'
                                        },
                                        ticks: {
                                            fontFamily: "Poppins"
                                        }
                                    }],
                                    yAxes: [{
                                        display: true,
                                        gridLines: {
                                            display: false,
                                            drawBorder: false
                                        },
                                        scaleLabel: {
                                            display: true,
                                            labelString: 'Cantidad',
                                            fontFamily: "Poppins"

                                        },
                                        ticks: {
                                            fontFamily: "Poppins"
                                        }
                                    }]
                                },
                                title: {
                                    display: false,
                                    text: 'Normal Legend'
                                }
                            }
                        });
                    }
                } catch (error) {
                    console.log(error);
                }
            }
        },
        error: function (error) {
            console.log(error);
        }
    });
}
if (document.getElementById("polarChart")) {
    const action = "polarChart";
    $('.alertAddProduct').html('');
    $.ajax({
        url: 'chart.php',
        type: 'POST',
        async: true,
        data: {
            action
        },
        success: function (response) {
            if (response != 0) {
                var data = JSON.parse(response);
                var nombre = [];
                var cantidad = [];
                for (var i = 0; i < data.length; i++) {
                    nombre.push(data[i]['descripcion']);
                    cantidad.push(data[i]['cantidad']);
                }
            }
            try {

                // polar chart
                var ctx = document.getElementById("polarChart");
                if (ctx) {
                    ctx.height = 200;
                    var myChart = new Chart(ctx, {
                        type: 'polarArea',
                        data: {
                            datasets: [{
                                data: cantidad,
                                backgroundColor: [
                                    "rgb(0, 123, 255)",
                                    "rgb(255, 0, 0)",
                                    "rgb(0, 255, 0)",
                                    "rgb(0,0,0)",
                                    "rgb(0, 0, 255)"
                                ]

                            }],
                            labels: nombre
                        },
                        options: {
                            legend: {
                                position: 'top',
                                labels: {
                                    fontFamily: 'Poppins'
                                }

                            },
                            responsive: true
                        }
                    });
                }

            } catch (error) {
                console.log(error);
            }
        },
        error: function (error) {
            console.log(error);

        }
    });
}
function btnCambiar(e) {
    e.preventDefault();
    const actual = document.getElementById('actual').value;
    const nueva = document.getElementById('nueva').value;
    if (actual == "" || nueva == "") {
        Swal.fire({
            position: 'top-end',
            icon: 'error',
            title: 'Los campos estan vacios',
            showConfirmButton: false,
            timer: 2000
        })
    } else {
        const cambio = 'pass';
         $.ajax({
             url: "ajax.php",
             type: 'POST',
             data: {
                 actual: actual,
                 nueva: nueva,
                 cambio: cambio
             },
             success: function (response) {
                 console.log(response);
                 if (response == 'ok') {
                     Swal.fire({
                         position: 'top-end',
                         icon: 'success',
                         title: 'Contraseña modificado',
                         showConfirmButton: false,
                         timer: 2000
                     })
                     document.querySelector('frmPass').reset();
                     $("#nuevo_pass").modal("hide");
                 } else if (response == 'dif') {
                     Swal.fire({
                         position: 'top-end',
                         icon: 'error',
                         title: 'La contraseña actual incorrecta',
                         showConfirmButton: false,
                         timer: 2000
                     })
                 } else {
                     Swal.fire({
                         position: 'top-end',
                         icon: 'error',
                         title: 'Error al modificar la contraseña',
                         showConfirmButton: false,
                         timer: 2000
                     })
                 }
             }
         });
    }
}