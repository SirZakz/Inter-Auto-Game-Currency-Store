const stripe = Stripe('THIS API KEY IS CHANGED TO PROTECTED THE STRIPE. ONLY LIVE VERSION HAD THE ORIGINAL API KEY');
let elements;

document
  .querySelector("#payment-form")
  .addEventListener("submit", handleSubmit);

function renderCart() {
  const cart = JSON.parse(localStorage.getItem('cart')) || [];
  const cartItemsContainer = document.getElementById('cart-items');
  cartItemsContainer.innerHTML = '';
  let totalPrice = 0;

  if (cart.length === 0) {
    cartItemsContainer.innerHTML = `
                    <div class="flex flex-col items-center justify-center shadow border p-6 bg-[#2b303c] dark:border-gray-900 rounded-lg text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 3h18l-1.6 10.8A2 2 0 0117.4 16H6.6a2 2 0 01-1.98-2.2L3 3z"></path>
                            <circle cx="9" cy="21" r="1"></circle>
                            <circle cx="20" cy="21" r="1"></circle>
                        </svg>
                        <p class="mt-4 text-lg font-semibold">Your cart is currently empty</p>
                    </div>`;
    return;
    return;
  }

  cart.forEach((item, index) => {
    totalPrice += item.cost * item.quantity;
    const itemElement = document.createElement('div');
    itemElement.classList.add('flex', 'justify-between', 'items-center', 'border', 'p-4', 'rounded-lg', 'shadow-md', 'bg-[#2b303c]', 'dark:border-gray-900');
    itemElement.innerHTML = `
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-[#21242e] text-white flex items-center justify-center rounded-full">
                                <span class="text-lg font-bold"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shopping-bag"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg></span>
                            </div>
                        </div>
                        <div class="text-white">
                            <p class="text-lg font-semibold">${item.name ? item.name : 'Item Name'}</p>
                            <p class="text-sm">Cost: RM${item.cost}</p>
                            <p class="text-sm">Quantity: ${item.quantity}</p>
                        </div>
                    </div>
                    <button class="remove-cart-item-button bg-red-500 text-white p-2 rounded-full hover:bg-red-600 transition duration-200 ease-in-out" data-index="${index}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.707a1 1 0 00-1.414-1.414L10 8.586 7.707 6.293a1 1 0 00-1.414 1.414L8.586 10l-2.293 2.293a1 1 0 001.414 1.414L10 11.414l2.293 2.293a1 1 0 001.414-1.414L11.414 10l2.293-2.293z" clip-rule="evenodd" />
                        </svg>
                    </button>
                `;
    cartItemsContainer.appendChild(itemElement);
  });

  // Add total price to the cart container
  const totalPriceElement = document.createElement('div');
  totalPriceElement.classList.add('text-white', 'text-lg', 'font-semibold', 'mt-4');
  totalPriceElement.textContent = `Total Price: RM${totalPrice.toFixed(2)}`;
  cartItemsContainer.appendChild(totalPriceElement);

  const removeButtons = document.querySelectorAll('.remove-cart-item-button');
  removeButtons.forEach(button => {
    button.addEventListener('click', function () {
      const itemIndex = this.getAttribute('data-index');
      removeCartItem(itemIndex);
    });
  });
}


function removeCartItem(index) {
  let cart = JSON.parse(localStorage.getItem('cart')) || [];
  cart.splice(index, 1);
  localStorage.setItem('cart', JSON.stringify(cart));
  renderCart();
}


// Add event listeners to all "Add to Cart" buttons
const addToCartButtons = document.querySelectorAll('.add-to-cart-button');
addToCartButtons.forEach(button => {
  button.addEventListener('click', function () {
    const articleId = this.getAttribute('data-id');
    const articleName = this.getAttribute('data-name');
    const cost = this.getAttribute('data-cost');

    let cart = JSON.parse(localStorage.getItem('cart')) || [];

    // Check if item already in the cart
    const itemIndex = cart.findIndex(item => item.id === articleId);

    if (itemIndex > -1) {
      cart[itemIndex].quantity += 1;
    } else {
      cart.push({ id: articleId, name: articleName, cost: cost, quantity: 1 });
    }

    localStorage.setItem('cart', JSON.stringify(cart));
    renderCart();

    //alert('Item added to cart');
  });
});

// Add event listener to checkout button
const proceedCheckoutButton = document.getElementById('proceed-to-checkout-button');
if (proceedCheckoutButton) {
  proceedCheckoutButton.addEventListener('click', processCheckout);
}

// Add event listener to checkout button
const checkoutButton = document.getElementById('checkout-button');

// Add event listener to cancel button
async function processCheckout() {
  // Get cart data from localStorage
  const cart = JSON.parse(localStorage.getItem('cart')) || [];
  if (cart.length === 0) {
    Toast.fire({
      icon: 'error',
      title: error.message
    })
  }

  // Make an API request to create a Stripe Checkout Session
  try {
    const { clientSecret } = await fetch('/payment/proceed', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
      },
      body: JSON.stringify({ items: cart }),
    }).then(response => response.json());

    const appearance = {
      theme: 'night',
      variables: {
        colorBackground: '#2b303c'
      }
    };

    addToCartButtons.forEach(button => {
      button.disabled = true;
      button.classList.add('opacity-50', 'cursor-not-allowed');
    });

    elements = stripe.elements({ clientSecret, appearance });

    const paymentElementOptions = {
      layout: "tabs",
    };

    const paymentElement = elements.create("payment", paymentElementOptions);
    paymentElement.mount("#payment-element");

    proceedCheckoutButton.classList.add('hidden');
    const checkoutContainer = document.getElementById('checkout');
    checkoutContainer.innerHTML = `
                <button type="submit" id="checkout-button" class="w-full py-2 px-4 bg-green-600 hover:bg-green-700 text-white p-2 border-2 border-green-500 transition ease-in-out duration-150 font-semibold rounded">Pay Now</button>
                <button type="button" id="cancel-button" class="w-full py-2 px-4 bg-red-600 text-white rounded hover:bg-red-700 transition ease-in-out duration-150 font-semibold rounded">Cancel</button>
                `;

    const cancelButton = document.getElementById('cancel-button');
    if (cancelButton) {
      cancelButton.addEventListener('click', function () {
        window.location.reload();
      });
    }

    const removeButtons = document.querySelectorAll('.remove-cart-item-button');
    removeButtons.forEach(button => {
      button.disabled = true;
      button.classList.add('opacity-50', 'cursor-not-allowed');
    });

  } catch (error) {
    console.error('Error:', error);
    // alert('Something went wrong while redirecting to payment.');
  }
}

async function handleSubmit(e) {
  e.preventDefault();

  const { error } = await stripe.confirmPayment({
    elements,
    confirmParams: {
      // Make sure to change this to your payment completion page
      return_url: "https://interplus.my/payment/success",
    },
  });

  // This point will only be reached if there is an immediate error when
  // confirming the payment. Otherwise, your customer will be redirected to
  // your `return_url`. For some payment methods like iDEAL, your customer will
  // be redirected to an intermediate site first to authorize the payment, then
  // redirected to the `return_url`.
  if (error.type === "card_error" || error.type === "validation_error") {
    Toast.fire({
      icon: 'error',
      title: error.message
    })
  } else {
    Toast.fire({
      icon: 'error',
      title: "An unexpected error occurred."
    })
  }

}

renderCart(); 