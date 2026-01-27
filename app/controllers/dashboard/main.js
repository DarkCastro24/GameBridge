// Constante para establecer la ruta y parámetros de comunicación con la API.
const API_PRODUCTOS = '../../app/api/dashboard/productos.php?action=';

// Método manejador de eventos que se ejecuta cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', function () {
    // Se declara e inicializa un objeto con la fecha y hora actual.
    let today = new Date();
    // Se declara e inicializa una variable con el número de horas transcurridas en el día.
    let hour = today.getHours();
    // Se declara e inicializa una variable para guardar un saludo.
    let greeting = '';
    // Dependiendo del número de horas transcurridas en el día, se asigna un saludo para el usuario.
    if (hour < 12) {
        greeting = 'Buenos días';
    } else if (hour < 19) {
        greeting = 'Buenas tardes';
    } else if (hour <= 23) {
        greeting = 'Buenas noches';
    }
    // Colocamos el nombre del usuario al saludo
    let usuario = document.getElementById('user').value;
    document.getElementById('greeting').textContent = greeting;
    let mensaje = `${greeting}  usuario ${usuario} `;
    // Se muestra el saludo en la página web.
    document.getElementById('greeting').textContent = mensaje;
    // Se llaman a la funciones que muestran las 5 gráficas en la página web.
    graficaCategorias();
    graficaProductos();
    graficaMarcas();
    graficaFechas();
    graficaClientes();
    //sweetAlert(4, 'Sesión cerrada por inactividad de 5 minutos', null);
});


// Función para mostrar la cantidad de productos vendidos por cada categoria en una gráfica de barras.
function graficaCategorias() {
    // Realizamos peticion a la API enviando el nombre del caso y metodo get debido a que la funcion de la API retorna datos
    fetch(API_PRODUCTOS + 'categoriasVentas', {
        method: 'get'
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje indicando el problema.
        if (request.ok) {
            request.json().then(function (response) {
                // Se declara variable para guardar el numero de registros que han sido ingresados en el arreglo
                let contador = 0;
                // Se comprueba si la respuesta es satisfactoria, de lo contrario se remueve la etiqueta canvas de la gráfica.
                if (response.status) {
                    // Se declaran los arreglos para guardar los datos por gráficar.
                    let categorias = [];
                    let cantidad = [];
                    // Se recorre el conjunto de registros devuelto por la API (dataset) fila por fila a través del objeto row.
                    response.dataset.map(function (row) {
                        // Se asignan los datos a los arreglos.
                        if (contador < 5) {
                            categorias.push(row.categoria);
                            cantidad.push(row.cantidad);
                        } 
                        contador = contador + 1;
                    });
                    // Se llama a la función que genera y muestra una gráfica de barras. Se encuentra en el archivo components.js
                    polarGraph('chart1', categorias, cantidad, 'Unidades vendidas');
                } else {
                    document.getElementById('chart1').remove();
                    console.log(response.exception);
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    }).catch(function (error) {
        console.log(error);
    });
}

// Función para mostrar los 5 productos mas vendidos de la tienda en una gráfica de barras.
function graficaProductos() {
    // Realizamos peticion a la API enviando el nombre del caso y metodo get debido a que la funcion de la API retorna datos
    fetch(API_PRODUCTOS + 'ventasProductos', {
        method: 'get'
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje indicando el problema.
        if (request.ok) {
            request.json().then(function (response) {
                // Se declara variable para guardar el numero de registros que han sido ingresados en el arreglo
                let contador = 0;
                // Se comprueba si la respuesta es satisfactoria, de lo contrario se remueve la etiqueta canvas de la gráfica.
                if (response.status) {
                    // Se declaran los arreglos para guardar los datos por gráficar.
                    let categorias = [];
                    let cantidad = [];
                    // Se recorre el conjunto de registros devuelto por la API (dataset) fila por fila a través del objeto row.
                    response.dataset.map(function (row) {
                        // Se asignan los datos a los arreglos.
                        if (contador < 5) {
                            categorias.push(row.producto);
                            cantidad.push(row.cantidad);
                        } 
                        contador = contador + 1;
                    });
                    // Se llama a la función que genera y muestra una gráfica de barras. Se encuentra en el archivo components.js
                    barGraph('chart2', categorias, cantidad, 'Unidades vendidas', '');
                } else {
                    document.getElementById('chart2').remove();
                    console.log(response.exception);
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    }).catch(function (error) {
        console.log(error);
    });
}

// Función para mostrar el porcentaje de productos por categoría en una gráfica de pastel.
function graficaMarcas() {
    // Realizamos peticion a la API enviando el nombre del caso y metodo get debido a que la funcion de la API retorna datos
    fetch(API_PRODUCTOS + 'ventasMarcas', {
        method: 'get'
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje indicando el problema.
        if (request.ok) {
            request.json().then(function (response) {
                // Se declara variable para guardar el numero de registros que han sido ingresados en el arreglo
                let contador = 0;
                // Se comprueba si la respuesta es satisfactoria, de lo contrario se remueve la etiqueta canvas de la gráfica.
                if (response.status) {
                    // Se declaran los arreglos para guardar los datos por gráficar.
                    let categorias = [];
                    let cantidad = [];
                    // Se recorre el conjunto de registros devuelto por la API (dataset) fila por fila a través del objeto row.
                    response.dataset.map(function (row) {
                        // Colocamos el numero de registros que deseamos que se muestren
                        if (contador < 5) {
                            // Se asignan los datos a los arreglos.
                            categorias.push(row.marca);
                            cantidad.push(row.cantidad);
                        } 
                        contador = contador + 1;
                    });
                    // Se llama a la función que genera y muestra una gráfica de pastel en porcentajes. Se encuentra en el archivo components.js
                    pieGraph('chart3', categorias, cantidad, 'Unidades vendidas');
                } else {
                    document.getElementById('chart3').remove();
                    console.log(response.exception);
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    }).catch(function (error) {
        console.log(error);
    });
}

// Función para mostrar el numero de ventas de productos en el ultimo mes en una gráfica de barras.
function graficaFechas() {
    // Realizamos peticion a la API enviando el nombre del caso y metodo get debido a que la funcion de la API retorna datos
    fetch(API_PRODUCTOS + 'ventasFechas', {
        method: 'get'
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje indicando el problema.
        if (request.ok) {
            request.json().then(function (response) {
                // Se declara variable para guardar el numero de registros que han sido ingresados en el arreglo
                let contador = 0;
                // Se comprueba si la respuesta es satisfactoria, de lo contrario se remueve la etiqueta canvas de la gráfica.
                if (response.status) {
                    // Se declaran los arreglos para guardar los datos por gráficar.
                    let categorias = [];
                    let cantidad = [];
                    // Se recorre el conjunto de registros devuelto por la API (dataset) fila por fila a través del objeto row.
                    response.dataset.map(function (row) {
                        // Se asignan los datos a los arreglos.
                        if (contador < 7) {
                            categorias.push(row.fecha);
                            cantidad.push(row.cantidad);
                        } 
                        contador = contador + 1;
                    });
                    // Se llama a la función que genera y muestra una gráfica de barras. Se encuentra en el archivo components.js
                    lineGraph('chart4', categorias, cantidad, 'Cantidad de ventas', '');
                } else {
                    document.getElementById('chart4').remove();
                    console.log(response.exception);
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    }).catch(function (error) {
        console.log(error);
    });
}

// Función para mostrar los clientes que han realizado mas compras en una gráfica de barras.
function graficaClientes() {
    // Realizamos peticion a la API enviando el nombre del caso y metodo get debido a que la funcion de la API retorna datos
    fetch(API_PRODUCTOS + 'ventasClientes', {
        method: 'get'
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje indicando el problema.
        if (request.ok) {
            request.json().then(function (response) {
                // Se declara variable para guardar el numero de registros que han sido ingresados en el arreglo
                let contador = 0;
                // Se comprueba si la respuesta es satisfactoria, de lo contrario se remueve la etiqueta canvas de la gráfica.
                if (response.status) {
                    // Se declaran los arreglos para guardar los datos por gráficar.
                    let categorias = [];
                    let cantidad = [];
                    // Se recorre el conjunto de registros devuelto por la API (dataset) fila por fila a través del objeto row.
                    response.dataset.map(function (row) {
                        // Se asignan los datos a los arreglos.
                        if (contador < 5) {
                            categorias.push(row.correo_electronico);
                            cantidad.push(row.cantidad);
                        } 
                        contador = contador + 1;
                    });
                    // Se llama a la función que genera y muestra una gráfica de barras. Se encuentra en el archivo components.js
                    doughnutGraph('chart5', categorias, cantidad, 'Pedidos realizados', '');
                } else {
                    document.getElementById('chart5').remove();
                    console.log(response.exception);
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    }).catch(function (error) {
        console.log(error);
    });
}


