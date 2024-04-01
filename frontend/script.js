
var gateway =' http://127.0.0.1:91/api/';

async function post(url, data = {}){
    return response = await fetch(gateway+url, {
        method: "POST", // *GET, POST, PUT, DELETE, etc.
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(data),
        }).then(data => {
            return data.json();
        });
}

async function get(url, data = {}){
    return response = await fetch(gateway+url, {
        method: "GET", // *GET, POST, PUT, DELETE, etc.
        headers: {
          "Content-Type": "application/json",
        },
        }).then(data => {
            return data.json();
        });
}

// Función asincrónica para realizar un pedido al servidor
async function placeOrder() {
    console.log('Lanzamos pedido');

    // Llama a la función get para obtener datos del servidor
    const response = await post('management/generar-pedido');

    // Imprime la respuesta del servidor en la consola
    console.log(response);
    loadOrders();
    loadIngredients();
    loadPurchaseHistory();
    loadOrderHistory();
    loadRecipes();

    // Aquí puedes agregar más lógica para manejar la respuesta del servidor
}
//placeOrder();
// Función para cargar órdenes en preparación desde el servidor
async function loadOrders() {
    // Lógica para cargar órdenes desde el servidor y mostrarlas en la interfaz
    try {
        const response = await get('management/orders');
    
        if (response && response.length > 0) {
          const ordersContainer = document.getElementById('orders-list');
          ordersContainer.innerHTML = '';
    
          response.forEach(order => {
            if (order.status !== 'en preparación' && order.status !== 'pendiente') {
              return;
            }
    
            const orderElement = document.createElement('div');
            orderElement.textContent = `ID del Pedido: ${order.id}, Receta: ${order.recipe_name}, Estado: ${order.status}`;
    
            // Aplicar estilos según el estado de la orden
            if (order.status === 'pendiente') {
              orderElement.style.color = 'red';
            } else {
              orderElement.style.color = 'yellow';
            }
    
            ordersContainer.appendChild(orderElement);
          });
        } else {
          const ordersContainer = document.getElementById('orders-list');
          ordersContainer.innerHTML = '<p>No se encontraron órdenes en preparación o pendientes.</p>';
        }
      } catch (error) {
        console.error('Error al cargar los pedidos:', error);
      }    
    
}

// Función para cargar ingredientes desde el servidor
async function loadIngredients() {
  try {
    // Obtiene los datos de la API
    const data = await get('kitchen/inventory');

    // Limpia la lista antes de agregar nuevos elementos
    const ingredientsList = document.getElementById('ingredients-list');
    ingredientsList.innerHTML = '';

    // Itera sobre los datos de ingredientes y agrega cada uno a la lista
    data.ingredients.forEach(ingredient => {
        // Crea un elemento <li> para el ingrediente
        const ingredientItem = document.createElement('div');

        // Crea un elemento <span> para el nombre del ingrediente
        const nameSpan = document.createElement('span');
        nameSpan.textContent = ingredient.name;
        nameSpan.style.color = 'blue'; // Aplica estilo amarillo al nombre
        nameSpan.style.fontWeight = 'bold'; 

        // Crea un elemento <span> para la cantidad del ingrediente
        const quantitySpan = document.createElement('span');
        quantitySpan.textContent = ingredient.quantity;
        quantitySpan.style.color = 'yellow'; // Aplica estilo verde a la cantidad

        // Agrega los elementos de nombre y cantidad al elemento <li>
        ingredientItem.appendChild(nameSpan);
        ingredientItem.appendChild(document.createTextNode(': ')); // Agrega separador
        ingredientItem.appendChild(quantitySpan);

        // Agrega el elemento <li> a la lista
        ingredientsList.appendChild(ingredientItem);
    });

} catch (error) {
    // Manejo de errores
    console.error('Error al obtener los ingredientes:', error);
    alert('Error al obtener los ingredientes. Por favor, inténtelo más tarde.');
}
}

// Función para cargar historial de compras desde el servidor
async function loadPurchaseHistory() {
  try {
    // Obtiene los datos de la API
    const data = await get('warehouse/compras');
    
    // Limpia la lista antes de agregar nuevos elementos
    const purchaseHistoryList = document.getElementById('purchase-history');
    purchaseHistoryList.innerHTML = '';

    // Verifica si hay datos de compras disponibles en la respuesta
    if (data && data.compras && data.compras.length > 0) {
        // Itera sobre los datos de compras y agrega cada uno a la lista
        data.compras.forEach(purchase => {
            // Crea un elemento <li> para la compra
            const purchaseItem = document.createElement('div');
            purchaseItem.textContent = `ID de la compra: ${purchase.id}, Fecha: ${purchase.purchase_date}, Cantidad: ${purchase.quantity}`;
            
            // Agrega el elemento <li> a la lista
            purchaseHistoryList.appendChild(purchaseItem);
        });
    } else {
        // Si no hay datos de compras disponibles, muestra un mensaje
        purchaseHistoryList.innerHTML = '<div>No hay historial de compras disponible.</div>';
    }
} catch (error) {
    // Manejo de errores
    console.error('Error al obtener el historial de compras:', error);
    alert('Error al obtener el historial de compras. Por favor, inténtelo más tarde.');
}
  }


// Función para cargar historial de pedidos desde el servidor
async function loadOrderHistory() {
    // Lógica para cargar historial de pedidos desde el servidor y mostrarlo en la interfaz
// Lógica para cargar historial de pedidos desde el servidor y mostrarlo en la interfaz
try {
    const response = await get('management/orders');

    if (response && response.length > 0) {
      const ordersContainer = document.getElementById('order-history-list');
      ordersContainer.innerHTML = '';

      response.forEach(order => {
        if (order.status !== 'listo') {
          return;
        }

        const orderElement = document.createElement('div');
        orderElement.textContent = `ID del Pedido: ${order.id}, Receta: ${order.recipe_name}, Estado: ${order.status}`;

        // Aplicar estilos según el estado de la orden
        if (order.status === 'pendiente') {
          orderElement.style.color = 'red';
        } else if (order.status === 'en preparación') {
          orderElement.style.color = 'yellow';
        } else {
          orderElement.style.color = 'green';
        }

        ordersContainer.appendChild(orderElement);
      });
    } else {
      const ordersContainer = document.getElementById('orders-list');
      ordersContainer.innerHTML = '<p>No se encontraron órdenes con estado "listo".</p>';
    }
  } catch (error) {
    console.error('Error al cargar los pedidos:', error);
  }
}

// Función para cargar recetas desde el servidor
async function loadRecipes() {
  try {
    // Realizar una solicitud GET al servidor para obtener las recetas
    const data = await get('kitchen/recipes'); // Ajusta la URL según tu configuración
    
    // Verificar si se recibieron datos de recetas correctamente
    if (data && data.recipes && data.recipes.length > 0) {
        // Obtener el contenedor donde se mostrarán las recetas
        const recipesContainer = document.getElementById('recipes-list');
        
        // Limpiar el contenedor antes de agregar nuevas recetas
        recipesContainer.innerHTML = '';

        // Iterar sobre las recetas recibidas y mostrarlas en la interfaz
        data.recipes.forEach(recipe => {
            const recipeElement = document.createElement('div');
            recipeElement.classList.add('recipe');

            const recipeTitle = document.createElement('h2');
            recipeTitle.textContent = recipe.name;
            recipeElement.appendChild(recipeTitle);

            const ingredientsList = document.createElement('ul');
            recipe.ingredients.forEach(ingredient => {
                const ingredientItem = document.createElement('li');
                ingredientItem.textContent = `${ingredient.name}: 1`;
                ingredientItem.style.color = 'blue';
                ingredientsList.appendChild(ingredientItem);
            });
            recipeElement.appendChild(ingredientsList);

            recipesContainer.appendChild(recipeElement);
        });
    } else {
        // Mostrar un mensaje si no se encontraron recetas
        const recipesContainer = document.getElementById('recipes-container');
        recipesContainer.innerHTML = '<p>No se encontraron recetas.</p>';
    }
} catch (error) {
    // Manejar errores en caso de que la solicitud falle
    console.error('Error al cargar las recetas:', error);
    alert('Error al cargar las recetas. Por favor, inténtalo de nuevo más tarde.');
}
}

// Asignar eventos a elementos de la interfaz
document.getElementById('order-button').addEventListener('click', placeOrder);

// Cargar datos al cargar la página
window.onload = function() {
    loadOrders();
    loadIngredients();
    loadPurchaseHistory();
    loadOrderHistory();
    loadRecipes();
};
