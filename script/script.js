// copy menu for mobile
function copyMenu(){
  // copy inside .dpt-cat to .dp-departments
  var dptCartegory = document.querySelector('.dpt-cat');
  var dptPlace = document.querySelector('.departments')
  if (dptPlace) {
    dptPlace.innerHTML = dptCartegory.innerHTML;
  }

  // copy inside nav to nav
  var mainNav = document.querySelector('.header-nav nav');
  var navPlace = document.querySelector('.off-canvas nav');
  if (navPlace) {
    navPlace.innerHTML = mainNav.innerHTML;
  }

  // copy .header-top .wrapper to .thetop-nav
  var topNav = document.querySelector('.header-top .wrapper');
  var topPlace = document.querySelector('.off-canvas .thetop-nav');
  if (topPlace) {
    topPlace.innerHTML = topNav.innerHTML;
  }
}
copyMenu();

// show mobile menu
const menuButton = document.querySelector('.trigger'),
      closeButton = document.querySelector('.t-close'),
      addclass = document.querySelector('.site');
if (menuButton) {
  menuButton.addEventListener('click', function(){
    addclass.classList.toggle('showmenu')
  })
}
if (closeButton) {
  closeButton.addEventListener('click',function(){
    addclass.classList.remove('showmenu')
  })
}
// show sub Menu on mobile
const submenu = document.querySelectorAll('.has-child .icon-small')
submenu.forEach((menu) => menu.addEventListener('click', toggle))

function toggle(e){
  e.preventDefault();
  submenu.forEach((item) => item != this ? item.closest('.has-child').classList.remove('expand') : null)
  if(this.closest('.has-child').classList != 'expand');
  this.closest('.has-child').classList.toggle('expand')
}


// SLIDER
if (document.querySelector('.swiper')) {
  const swiper = new Swiper('.swiper', {
    loop: true,

    pagination: {
      el: '.swiper-pagination',
    },

  });
}

// display header tag when scrolling up
const header = document.getElementById('stickyHeader');
let lastScrollY = window.scrollY;
let ticking = false;

window.addEventListener('scroll', () => {
    if (!ticking) {
      window.requestAnimationFrame(() => {
        const currentScrollY = window.scrollY;

        if (currentScrollY > lastScrollY + 10) {
          header.classList.add('hidden'); // Hide on significant scroll down
        } else if (currentScrollY < lastScrollY - 10) {
          header.classList.remove('hidden'); // Show on significant scroll up
        }

        lastScrollY = currentScrollY;
        ticking = false;
      });

      ticking = true;
  }
});



// show/hide search-bottom
const searchButton = document.querySelector('.t-search'),
      tClose = document.querySelector('.search-close'),
      showClass = document.querySelector('.site');
if (searchButton) {
  searchButton.addEventListener('click', function(){
    showClass.classList.toggle('showsearch')
  });
}
if (tClose) {
  tClose.addEventListener('click', function(){
    showClass.classList.remove('showsearch')
  })
}


// PRODUCT-PAGE
// show dpt menu - product page
const dptButton = document.querySelector('.dpt-cat .dpt-trigger'),
      dptClass = document.querySelector('.site');
if (dptButton) {
  dptButton.addEventListener('click',function() {
    dptClass.classList.toggle('showdpt')
  })
}

const signup = document.querySelector('.t-signup');
const login = document.querySelector('.t-login');
const add_class = document.querySelector('.sitetwo');
if (signup || login) {
  signup.addEventListener('click', function(){
    add_class.className = 'sitetwo signup-show';
  })
  login.addEventListener('click', function(){
    add_class.className = 'sitetwo login-show';
  })
}


// product image slider
var productThumb = new Swiper('.small-image', {
  loop: true,
  spaceBetween: 10,
  slidesPerView: 2,
  freeMode: true,
  watchSlidesProgress: true,
  breakpoints: {
    481: {
      spaceBetween: 32,
    }
  }
});

// var productBig = new Swiper('.big-image', {
//   loop: true,
//   autoHeight: true,
//   navigation: {
//     nextEl: '.swiper-button-next',
//     prevEl: '.swiper-button-prev',
//   },
//   thumbs: {
//     swiper: productThumb,
//   }
// });




// show/hide cart on click

const divtoShow = '.mini-cart';
const divPopup = document.querySelector(divtoShow);
const divTrigger = document.querySelector('.cart-trigger');

divTrigger.addEventListener('click', () => {
  setTimeout(() => {
    if(!divPopup.classList.contains('show')){
      divPopup.classList.add('show')
    }
  }, 250)
})

document.addEventListener('click', (e) => {
  const isClosest = e.target.closest(divtoShow);
  if(!isClosest && divPopup.classList.contains('show')){
    divPopup.classList.remove('show')
  }

  // ---- 1. ADD TO CART ------
  // Check if the clicked element is an Add to Cart button
  if (e.target.classList.contains('add-to-cart-btn')){
    const productId = e.target.getAttribute('data-product-id'); // Get product ID from data attribute

    if (!productId) {
        console.error('Product ID not found.');
        return;
    }

    // Debug to ensure the productId is correctly fetched
    console.log('Product ID:', productId);

    fetch('./functions/add_to_cart.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ product_id: productId }) // Send product ID in the request
    })
    .then(response => response.json())
    .then(data => {
      console.log('Response:', data);
      if (data.success) {
        Swal.fire({
          position: 'top',
          icon: 'success',
          title: 'Added to Cart',
          text: data.message,
          showConfirmButton: false,
          timer: 1000
        });
        fetchMiniCart();
      }else {
        Swal.fire({
          position: 'top',
          icon: 'error',
          title: 'Error',
          text: data.message,
          showConfirmButton: false,
          timer: 1000
        });
      }
    })
    .catch(error => {
      Swal.fire({
        position: 'top',
        icon: 'error',
        title: 'Oops...',
        text: 'Something went wrong.',
        showConfirmButton: false,
        timer: 1000
      });
      console.error('Error:', error);
    });
  }

  // ---- 2. REMOVE ITEMS FROM CART ----
  const removeButton = e.target.closest('.item-remove'); // Check for remove button
  if (removeButton) {
    e.stopPropagation(); // Prevent event bubbling
    const productId = removeButton.getAttribute('data-product-id'); // Fetch product ID

    if (!productId) {
      console.error('Product ID not found.');
      return;
    }

    // AJAX Request to remove the item
    fetch('./functions/remove_from_cart.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ product_id: productId }),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          console.log('Item successfully removed:', productId); // Debugging
          // Remove the item from the DOM
          const cartItem = removeButton.closest('.item-to-be-removed'); // Locate the parent <li>
          if (cartItem) {
            cartItem.remove();
          }
          // Refresh both mini-cart and cart page
          fetchMiniCart();
          fetchCartPage();
          updateCartSubTotal();
          updateCartTotal();
        }
      })
      .catch((error) => {
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: 'Something went wrong.',
          confirmButtonText: 'OK',
        });
        console.error('Error:', error);
      });
  }

  // ---- 3. UPDATE CART QUANTITY ----
  if (e.target.classList.contains('plus') || e.target.classList.contains('minus')) {
    e.preventDefault(); // Prevent default button behavior
    const qtyControl = e.target.closest('.qty-control');
    const inputField = qtyControl.querySelector('input');
    const row = e.target.closest('tr');
    const productId = row.dataset.productId;
    const priceCell = row.querySelector('td:nth-child(2)'); // Price column
    const subtotalCell = row.querySelector('td:nth-child(4)'); // Subtotal column
    const stockCell = row.querySelector('.stock-info');

    if (!inputField || !productId || !priceCell || !subtotalCell) {
      console.error('Required elements missing for quantity update.');
      return;
    }

    let currentQty = parseInt(inputField.value);
    const pricePerItem = parseFloat(priceCell.textContent.replace('Kshs.', '').trim());
    const maxStock = parseInt(stockCell.textContent);

    // Increment or decrement quantity
    if (e.target.classList.contains('plus')) {
      if (currentQty < maxStock) {
        currentQty++;
      } else {
        Swal.fire({
          icon: 'warning',
          title: 'Stock Limit Reached',
          text: `You cannot add more than ${maxStock} items for this product.`,
          confirmButtonText: 'OK',
        });
        return;
      }
    } else if (e.target.classList.contains('minus') && currentQty > 1) {
      currentQty--;
    }

    // Update the input value immediately
    inputField.value = currentQty;

    // Update subtotal dynamically
    const newSubtotal = currentQty * pricePerItem;
    subtotalCell.textContent = `Kshs.${newSubtotal.toLocaleString('en-US')}`;  // Update the subtotal cell

    // Send updated quantity to the server
    fetch('./functions/update_cart_quantity.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ product_id: productId, quantity: currentQty }),
    })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        console.log('Quantity updated successfully:', productId);
        // Optionally update the total dynamically
        updateCartSubTotal();
        updateCartTotal();
        fetchMiniCart(); // Refresh mini-cart if applicable
      } else {
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: data.message,
          confirmButtonText: 'OK',
        });
      }
    })
    .catch((error) => {
      Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: 'Something went wrong.',
        confirmButtonText: 'OK',
      });
      console.error('Error updating quantity:', error);
    });
  }

  // -------- 4. ADD/REMOVE PRODUCT TO/FROM WISHLIST -------
  if (e.target.classList.contains('ri-heart-fill')) {
    const icon = e.target;
    const parentLi = icon.closest('.wishlist-icon'); // Get the parent <li>
    const productId = parentLi.getAttribute('data-product-id'); // Fetch product_id from data attribute
    const isActive = parentLi.classList.contains('clicked'); // Check if the wishlist item is active
    const action = isActive ? 'remove' : 'add'; // Determine action based on active state

    // Perform AJAX request to add/remove the product from the wishlist
    fetch('./functions/wishlist_action.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ product_id: productId, action: action }),
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
    console.log('success');
        // Update all elements with the same data-product-id
        document
          .querySelectorAll(`.wishlist-icon[data-product-id="${productId}"]`)
          .forEach(el => el.classList.toggle('clicked', !isActive));
        fetchMiniWishlist();
      } else if (data.message) {
        Swal.fire({
          icon: 'warning',
          title: 'Login Required',
          text: data.message,
        });
      } else {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'An error occurred. Please try again.',
        });
      }
    })
    .catch(error => console.error('Error:', error));
  }

  // ---- 5. REMOVE ITEMS FROM WISHLIST ----
  const removeWishlistButton = e.target.closest('.remove-wishlist');
  if (removeWishlistButton) {

    const productId = removeWishlistButton.getAttribute('data-product-id');
    const action = 'remove';

    if (!productId) {
      console.error('Product ID not found.');
      return;
    }

    // AJAX Request to remove the item
    fetch('./functions/wishlist_action.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ product_id: productId, action: action }),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          // Remove the wishlist item from the DOM
          const wishlistItem = removeWishlistButton.closest('.wishlist-item-removed');
          if (wishlistItem) {
            wishlistItem.remove();
          }

          // Refresh mini-wishlist
          fetchMiniWishlist();
          checkWishlistIcons();
        }
      })
      .catch((error) => {
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: 'Something went wrong.',
          confirmButtonText: 'OK',
        });
        console.error('Error:', error);
      });
  }
})
window.addEventListener('DOMContentLoaded', () => {
  // Check if there are wishlist elements on the page
  if (document.querySelector('.wishlist-icon')) {
    fetch('./functions/get_wishlist.php', { method: 'GET' })
    .then(response => {
      if (!response.ok) {
        throw new Error(`HTTP error! Status: ${response.status}`);
      }
      return response.json();
    })
    .then(data => {
      if (data.success && data.wishlist) {
        data.wishlist.forEach(product => {
          // Select all matching wishlist icons with the same data-product-id
          document
            .querySelectorAll(`.wishlist-icon[data-product-id="${product.product_id}"]`)
            .forEach(icon => icon.classList.add('clicked'));
        });
      } else if (!data.success && data.message) {
        console.warn(data.message);
      }
    })
    .catch(error => console.error('Error fetching wishlist:', error));
  }
});




// Function to calculate the cart discount dynamically
function calculateCartDiscount(subtotal) {
  // Fetch active cart promotions from the server
  return fetch('./functions/get_active_cart_promotions.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' }
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      const promotions = data.promotions; // Array of promotions from the server
      let discount = 0;

      // Calculate the discount based on the highest matching promotion
      promotions.forEach(promo => {
        if (subtotal >= promo.min_cart_value) {
          const currentDiscount = (subtotal * promo.discount_value) / 100;
          if (currentDiscount > discount) {
            discount = currentDiscount;
          }
        }
      });

      return discount;
    } else {
      console.error('Error fetching promotions:', data.message);
      return 0;
    }
  })
  .catch(error => {
    console.error('Error:', error);
    return 0;
  });
}


// document.addEventListener('DOMContentLoaded', () => {
//   fetchCartPage();
// });

// Function to fetch and refresh mini-wishlist
function fetchMiniWishlist() {
  fetch('./functions/get_wishlist.php?mode=mini-wishlist')
  .then((response) => response.text())
  .then((html) => {
    const miniWishlist = document.querySelector('.wishlist');
    if (miniWishlist) {
      miniWishlist.innerHTML = html; // Update the mini-cart content
    }
  })
  .catch((error) => console.error('Error fetching mini-cart:', error));
}
// visually mark wishlisted products after filtering function
function checkWishlistIcons() {
  if (document.querySelector('.wishlist-icon')) {
    fetch('./functions/get_wishlist.php', { method: 'GET' })
      .then(response => {
        if (!response.ok) {
          throw new Error(`HTTP error! Status: ${response.status}`);
        }
        return response.json();
      })
      .then(data => {
        if (data.success && data.wishlist) {
          // Reset all wishlist icons first
          document.querySelectorAll('.wishlist-icon').forEach(icon => icon.classList.remove('clicked'));

          // Apply 'clicked' class to wishlisted products
          data.wishlist.forEach(product => {
            document
              .querySelectorAll(`.wishlist-icon[data-product-id="${product.product_id}"]`)
              .forEach(icon => icon.classList.add('clicked'));
          });
        } else if (!data.success && data.message) {
          console.warn(data.message);
        }
      })
      .catch(error => console.error('Error fetching wishlist:', error));
  }
}
// Function to fetch and refresh mini-cart content
function fetchMiniCart() {
  fetch('./functions/fetch_cart_details.php?mode=mini-cart')
  .then((response) => response.text())
  .then((html) => {
    const miniCart = document.querySelector('.iscart');
    if (miniCart) {
      miniCart.innerHTML = html; // Update the mini-cart content
    }
  })
  .catch((error) => console.error('Error fetching mini-cart:', error));
}

// Function to fetch and refresh cart page
function fetchCartPage() {
  fetch('./functions/fetch_cart_details.php?mode=cart-page')
    .then((response) => response.text())
    .then((html) => {
      // console.log('Cart page response:', html); // Debug
      const cartTable = document.querySelector('#cart-table');
      if (cartTable) {
        cartTable.innerHTML = html;
      }
    })
    .catch((error) => console.error('Error fetching cart page:', error));
}

// function to update cart subtotal price
function updateCartSubTotal() {
  const cartTable = document.querySelector('#cart-table');
  if (!cartTable) return;

  let total = 0;

  // Iterate over each subtotal cell and sum up the values
  const subtotalCells = cartTable.querySelectorAll('td:nth-child(4)'); // Assuming subtotal is in the 4th column
  subtotalCells.forEach((cell) => {
    const subtotal = parseFloat(cell.textContent.replace('Kshs.', '').replace(/,/g, '').trim());
    if (!isNaN(subtotal)) {
      total += subtotal;
    }
  });

  // Update the total price in the DOM
  const totalCell = document.querySelector('#cart-total'); // Assuming there's an element to show the total
  if (totalCell) {
    totalCell.textContent = `Kshs.${total.toLocaleString('en-US')}`;
  }
}

// function to get discount and update cart total dynamically
function updateCartTotal() {
  const subtotalElement = document.getElementById('cart-total');
  const discountElement = document.getElementById('cart-discount');
  const totalElement = document.querySelector('.grand-total td strong');

  if (!subtotalElement || !discountElement || !totalElement) {
    console.error('Required elements for cart total update are missing.');
    return;
  }
  // Extract current subtotal
  const subtotal = parseFloat(
    subtotalElement.textContent.replace('Kshs.', '').replace(/,/g, '').trim()
  );

  // Calculate discount dynamically
  calculateCartDiscount(subtotal).then(discount => {
    // Update discount and total
    discountElement.textContent = `Kshs.${discount.toLocaleString('en-US')}`;
    const total = subtotal - discount;
    totalElement.textContent = `Kshs.${total.toLocaleString('en-US')}`;
  });
}

// show coupon modal
window.onload = function() {
  const cartPage = document.querySelector('.page-cart');
  const modal = document.querySelector('#modal');

  if (cartPage && modal) {
    cartPage.classList.add('showmodal');
  }
};

document.querySelector('.modalclose')?.addEventListener('click', function() {
  const cartPage = document.querySelector('.page-cart');
  if (cartPage) {
    cartPage.classList.remove('showmodal');
  }
});

document.addEventListener('DOMContentLoaded', function() {
  // Check if the search form exists on the page
  const siteSearchForm = document.getElementById('siteSearch');

  if (siteSearchForm) {
    siteSearchForm.addEventListener('submit', function(e) {
      e.preventDefault();

      // Get the search query from the input field named "query"
      const queryInput = siteSearchForm.querySelector('input[name="query"]');
      if (queryInput) {
        const query = queryInput.value.trim();
        // Redirect to products.php with the search query in the URL
        window.location.href = "products.php?query=" + encodeURIComponent(query);
      }
    });
  }
});
