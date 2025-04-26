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

### Product Review/Ratings
![Image](https://github.com/user-attachments/assets/0aa42e37-a9ee-43ad-8b1f-7a5a50f50db9)

### Shopping Cart & Promotions/Offers
![Image](https://github.com/user-attachments/assets/012ddfc4-e93e-490e-bccf-9b66da966067)
![Image](https://github.com/user-attachments/assets/9f5bfb83-d108-4c37-84dd-2d61cf65d682)

### Checkout & Payment Simulation (M-Pesa)
![Image](https://github.com/user-attachments/assets/9e8df3cd-22b9-40aa-8606-7e67d3030374)
![Image](https://github.com/user-attachments/assets/cb01184e-ce1f-4ea9-bc7f-93b3de35f562)
![Image](https://github.com/user-attachments/assets/4c9adb19-c171-4954-93d9-050d4958d77e)

### Order Tracking User & Admin
![Image](https://github.com/user-attachments/assets/b1588151-2db8-4510-8dff-d14a83dca9f5)
![Image](https://github.com/user-attachments/assets/f713e9c1-4f98-4a7f-8447-3d4fc1e90a65)
![Image](https://github.com/user-attachments/assets/0c0dac5b-351d-46f9-8291-7e0f0af0519c)

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

### 📁 Files of Interest

- `users_area/mpesa_api/access_token.php` – Handles generation of the OAuth 2.0 access token from Safaricom API.
- `users_area/mpesa_api/initiate_payment.php` – Initiates the STK Push (Lipa na M-Pesa Online) request.

---

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
   - **Consumer Key**
   - **Consumer Secret**
   - which you will replace those in `access_token.php`

6. **Get the Shortcode and Passkey**:
   - Prompt Chatgpt to rewrite `initiate_payment.php` using the passkey description: 'This is the password used for encrypting the request sent: A base64 encoded string. (The base64 string is a combination of Shortcode+Passkey+Timestamp)' - M-Pesa Express Simulate under API in your profile
   - For further assistance (https://www.youtube.com/watch?v=_wZI3uGubzY&list=WL&index=1&t=848s).

---

### Summary of Needed Credentials:

| Item | Where to Use | 
|:-----|:-------------|
| Consumer Key | `access_token.php` |
| Consumer Secret | `access_token.php` |
| Shortcode (PayBill) | `initiate_payment.php` |
| Lipa na M-Pesa Passkey | `initiate_payment.php` |

---

## Setting Up EmailJS for Order Notifications

The project uses **EmailJS** to send email notifications directly from the frontend/backend without needing your own server SMTP.

### Steps:

1. **Register an account** on [EmailJS](https://www.emailjs.com/).

2. **Login** to your EmailJS dashboard.

3. **Create an Email Service**:
   - Go to **Email Services** → **Add New Service**.
   - Connect an email service provider (e.g., Gmail, Outlook, Yahoo).
   - Authorize the connection.

4. **Create an Email Template**:
   - Go to **Email Templates** → **Create New Template**.
   - Design your email:
     - Subject: _"New Order Notification"_
     - Body: Include placeholders like `${order_id}`, `${customer_name}`, `${order_total}`, etc.
   - Save the template.
   - Example of template used
![Image](https://github.com/user-attachments/assets/be12dc48-adf1-493a-95a3-402554cd811c)

5. **Get Your Credentials**:
   - **Service ID** — your connected email service.
   - **Template ID** — the email template you created.
   - **Public Key** — your EmailJS user/public key.

6. **Update the Project File (`list_orders.php`)**:
   - Replace the dummy `YOUR_SERVICE_ID`, `YOUR_TEMPLATE_ID`, and `YOUR_PUBLIC_KEY` in the file with the actual ones you obtained.

---

## User Credentials
1. username: johndoe password: 123
2. username: janedoe password: 123

## Admin Credentials
1. admin: joe password: 123
2. marketer: anna password: 123
3. store_manager: james password: 123

---
## Contact
1. Developer: Evans Abonyo
2. Phone Number: 254743677813
3. Email: evansabonyomutula@gmail.com
4. LinkedIn: www.linkedin.com/in/evansabonyo
5. GitHub: Evan5403



