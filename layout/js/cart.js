document.addEventListener('DOMContentLoaded', () => {
    console.log('DOM fully loaded');
    loadCartCountFromServer();
    if (document.getElementById('cart-items')) {
        loadCart(); // Load the cart items only if the cart table exists
    }
   // loadCart(); // Load the cart items on page load
      
});

function loadCart() { // load the cart items and update the cart count.
    fetch('/E-Commerce/includes/cart/get_cart.php')
        .then(response => response.json())
        .then(cartItems => {
            const cartTable = document.getElementById('cart-items');
            const cartTotal = document.getElementById('cart-total'); // Ensure this ID matches your HTML
            if (!cartTable || !cartTotal) return; // Ensure these elements exist before proceeding

            cartTable.innerHTML = ''; // Clear existing rows

            let total = 0; // Initialize total amount
            cartItems.forEach(item => {
                const itemTotal = parseFloat(item.Total);
                console.log('item Total:', itemTotal);
                total += itemTotal; // Add item total to the total amount
                cartTable.innerHTML += `
                    <tr data-item-id="${item.item_id}">
                        <td>${item.Name}</td>
                        <td>$${item.Price}</td>
                        <td>
                            <button class="btn btn-sm btn-secondary btn-decrease">-</button>
                            ${item.quantity}
                            <button class="btn btn-sm btn-secondary btn-increase">+</button>
                        </td>
                        <td>$${item.Total}</td>
                        <td>
                            <button class="btn btn-sm btn-danger btn-remove">Supprimer</button>
                        </td>
                    </tr>
                `;
              
            });
         
            console.log('Cart Total:', total); // Debug total
            cartTotal.textContent = `$${total.toFixed(2)}`; // Update total in the DO
             
        })
        .catch(error => console.error('Error fetching cart data:', error));      
}


function updateCartBadge(count) {
    const cartCount = document.getElementById('cart-count');
    if (cartCount) {
        cartCount.textContent = count;
        console.log('Cart count updated:', count);
    }
}



// add event lisenter to handle the "add to cart" button click event
document.addEventListener('DOMContentLoaded', () => {
    // Select all buttons with the "add-to-cart" class
    const buttons = document.querySelectorAll('.add-to-cart');

    buttons.forEach(button => {
        button.addEventListener('click', () => {
            const itemId = button.getAttribute('data-item-id'); // Get the item ID
            console.log(`Adding item ID: ${itemId} to cart`);

            // Send the item ID to the backend
            fetch('/E-Commerce/includes/cart/add_to_cart.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ item_id: itemId, quantity: 1 }) // You can set quantity dynamically
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message); // Show confirmation
                        updateCartBadge(data.cartCount);
                } else {
                    alert(`Error: ${data.message}`);
                }
            })
            .catch(error => console.error('Error adding to cart:', error));
        });
    }); 
   loadCart(); // Load the cart items on page load
});


// Event delegation for control buttons(increase-decrease-remove)item from the cart table
document.addEventListener('DOMContentLoaded', () => {
    
    const cartTable = document.getElementById('cart-items');

    if (cartTable) {
        
    // Event delegation for control buttons
      cartTable.addEventListener('click', (event) => {
        const target = event.target;
        const itemId = target.closest('tr').getAttribute('data-item-id'); // Get item ID

        if (target.classList.contains('btn-increase')) {
            updateCartItem(itemId, 1); // Increase quantity  
        } else if (target.classList.contains('btn-decrease')) {
            updateCartItem(itemId, -1); // Decrease quantity
        } else if (target.classList.contains('btn-remove')) {
            removeCartItem(itemId); // Remove item
        } 
   
        loadCart(); // Reload cart to reflect changes 
        loadCartCountFromServer(); // Update the badge count from the server
              
    });
  }
});

// Function to update the quantity of an item in the cart
function updateCartItem(itemId, change) {
    fetch('/E-Commerce/includes/cart/update_cart.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ item_id: itemId, change: change }) // Pass item ID and change amount
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadCart(); // Reload cart to reflect changes
                
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error('Error updating cart item:', error));      
}

function removeCartItem(itemId) {
    fetch('/E-Commerce/includes/cart/remove_cart_item.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ item_id: itemId }) // Pass item ID
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadCart(); // Reload the cart table
                updateCartBadge(data.cartCount); // Update the badge immediately
                 // Check if the cart is empty and clear the design if so
            if (data.cartCount === 0) {
                clearCartPage(); // Call function to clear the page
            }
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error('Error removing cart item:', error));

}

function loadCartCountFromServer() {
     
    fetch('/E-Commerce/includes/cart/get_cart_count.php')
         
        .then(response => response.json())
        .then(data => {
             updateCartBadge(data.cartCount || 0);    
        })
        .catch(error => console.error('Error fetching cart count:', error));
}

// Function to clear the cart page design
function clearCartPage() {
    const cartSection = document.getElementById('tableContainer'); // Assuming cart section is wrapped in an element with this ID
    if (cartSection) {
        cartSection.innerHTML = `
            <div style="text-align: center; margin-top: 50px;">
               <div class='alert alert-info  comment_msg'> Votre panier est vide.</div>
               <a href="/E-Commerce/index.php" class="btn btn-primary" style="margin-top: 20px;">Continuer vos achats</a>
            </div>`;
    }
}










