# JamboShop E-commerce Website

![Image](https://github.com/user-attachments/assets/a2360078-4faa-4bd5-97e0-be132563c78c)

## Project Overview

**JamboShop** is a demo e-commerce website focused on eco-friendly fashion and lifestyle products.  
Built as part of a school project, it aims to demonstrate online shopping functionalities such as product browsing, cart management, checkout, and admin panel management.

---

## Features

- User registration and login system
- Product catalog with categories and filters
- Add to cart and manage cart
- Checkout process with payment simulation (e.g., Pay On Delivery, M-Pesa)
- Admin panel for managing products, orders, and users
- Basic order tracking for customers
- Real-time mini-cart updates using AJAX
- EmailJs notification integration (for order status updates)
- Analytics dashboard for admins

---

## Technologies Used

- **Frontend:** HTML5, CSS3, JavaScript, Bootstrap
- **Backend:** PHP (Procedural PHP)
- **Database:** MySQL (via XAMPP)
- **Payment Integration:** M-Pesa Daraja API (sandbox)
- **Other Tools:** AJAX, jQuery, SweetAlert2 for alerts

---

## Screenshots

### Home Page
![Image](https://github.com/user-attachments/assets/ff35d5a1-16af-4a8d-a60a-e7d741581444)

### Product Listing
![Image](https://github.com/user-attachments/assets/58c6e88d-118d-468f-b871-f9c0220fe554)

### Shopping Cart & Promotions/Offers
![Image](https://github.com/user-attachments/assets/012ddfc4-e93e-490e-bccf-9b66da966067)
![Image](https://github.com/user-attachments/assets/9f5bfb83-d108-4c37-84dd-2d61cf65d682)

### Checkout & Payment Simulation (M-Pesa)
![Image](https://github.com/user-attachments/assets/9e8df3cd-22b9-40aa-8606-7e67d3030374)
![Image](https://github.com/user-attachments/assets/cb01184e-ce1f-4ea9-bc7f-93b3de35f562)
![Image](https://github.com/user-attachments/assets/4c9adb19-c171-4954-93d9-050d4958d77e)

### Admin Dashboard
![Image](https://github.com/user-attachments/assets/d6e7c007-bc04-4d62-aaf8-e5cddaff9165)

---

## Setup Instructions

1. **Clone the repository:**
   ```bash
   git clone https://github.com/Evans5403/jamboshop-ecommerce.git
2. Open PhpMyAdmin from your local Application Server.
3. Create new database and name it as "mystore"
4. Import mystore.sql located at mysql folder to your mystore database.
5. Turn On Apache and MySQL on your Application Server Control Panel.
6. Your system should be found on http://localhost/InventorySystem/

## Setting Up M-Pesa Sandbox Credentials

To successfully integrate M-Pesa Daraja API, follow these steps to create an app and obtain the required credentials:

### Steps:

1. **Register an account** on the [Safaricom Developer Portal](https://developer.safaricom.co.ke/).

2. **Login** to the portal.

3. **Create a new app**:
   - Navigate to the **"My Apps"** section.
   - Click **"Add a New App"**.
   - Name your app (e.g., "JamboShop E-commerce Integration").
   - Under "Product Selection", **enable**:
     - _Lipa na M-Pesa Sandbox_
     - _M-Pesa Express Sandbox_

4. **Save your app**.

5. After saving, you will be given:
   - **Consumer Key** (used in `access_token.php`)
   - **Consumer Secret** (used in `access_token.php`)

6. **Get the Shortcode and Passkey**:
   - Go to the **"Test Credentials"** section under your app.
   - Note your **Shortcode** (typically `174379` for PayBill in sandbox).
   - Copy the **Lipa na M-Pesa Passkey** (used in `initiate_payment.php`).

7. **Test Phone Numbers**:
   - Use the provided **sandbox phone numbers** (e.g., `254708374149`) to simulate payments.
   - No actual M-Pesa account is required.

8. **Update the Project Files**:
   - Replace the dummy `Consumer Key`, `Consumer Secret`, and `Passkey` in your project (`access_token.php`, `initiate_payment.php`) with the real sandbox credentials you obtained.

---

### Summary of Needed Credentials:

| Item | Where to Use | 
|:-----|:-------------|
| Consumer Key | `access_token.php` |
| Consumer Secret | `access_token.php` |
| Shortcode (PayBill) | `initiate_payment.php` |
| Lipa na M-Pesa Passkey | `initiate_payment.php` |
| Test Phone Numbers | Payment Simulation |

---

