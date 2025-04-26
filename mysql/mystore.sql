-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 24, 2025 at 10:14 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mystore`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_logs`
--

CREATE TABLE `admin_logs` (
  `log_id` int(11) NOT NULL,
  `adm_user` varchar(100) NOT NULL,
  `action` varchar(255) NOT NULL,
  `action_effect` varchar(10) NOT NULL,
  `details` varchar(255) NOT NULL,
  `timestamp` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admin_table`
--

CREATE TABLE `admin_table` (
  `admin_id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `gender` varchar(100) NOT NULL,
  `d_o_b` date NOT NULL,
  `email` varchar(255) NOT NULL,
  `contact_number` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_login` datetime NOT NULL,
  `profile_image` varchar(255) NOT NULL,
  `status` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_table`
--

INSERT INTO `admin_table` (`admin_id`, `full_name`, `user_name`, `gender`, `d_o_b`, `email`, `contact_number`, `password`, `role`, `created_at`, `updated_at`, `last_login`, `profile_image`, `status`) VALUES
(1, 'Joe Swanson', 'joe', 'male', '1983-08-28', 'joeswanson@admin.com', '0712345678', '$2y$10$G77mp5OfZsHFM/uB9LFHd.CMky9wilM6uqFU98Mz0c0eNmB8sBysS', 'admin', '2025-04-16 14:49:56', '2025-01-16 17:20:03', '2025-04-16 17:49:56', 'admin.avif', 'active'),
(2, 'Anna Monroe', 'anna', 'female', '1997-02-03', 'monroe@marketer.com', '0112345677', '$2y$10$n0FNgCPi8ZpWMnoyDTgh9.BwfCgM9OLwxgLtych/SzLO.NlL4Vju.', 'marketer', '2025-04-16 14:51:28', '0000-00-00 00:00:00', '2025-04-16 17:51:28', 'admin2.webp', 'active'),
(3, 'James Bond', 'james', 'male', '2003-05-19', 'jamesbond@storemanager.com', '0712345677', '$2y$10$QAidUTkn6UNQjUpmcOB/ieN6y6q/Gv17teU7KusIvBqQ3o9GKljhe', 'store_manager', '2025-04-20 07:43:28', '0000-00-00 00:00:00', '2025-04-20 10:43:28', 'admin3.jpg', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `brand_id` int(11) NOT NULL,
  `brand_title` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`brand_id`, `brand_title`) VALUES
(1, 'Gucci'),
(2, 'Addidas'),
(3, 'Nike'),
(4, 'Birkenstock'),
(5, 'Yankees'),
(6, 'Zara'),
(9, 'Polo'),
(10, 'Generic'),
(11, 'Louis Vuitton'),
(12, 'Givenchy'),
(13, 'Pandora');

-- --------------------------------------------------------

--
-- Table structure for table `canceled_orders`
--

CREATE TABLE `canceled_orders` (
  `invoice_number` int(255) NOT NULL,
  `order_status` varchar(255) NOT NULL,
  `cancel_reason` varchar(255) NOT NULL,
  `canceled_by` varchar(10) NOT NULL,
  `refund` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cart_details`
--

CREATE TABLE `cart_details` (
  `product_id` int(11) NOT NULL,
  `ip_address` varchar(255) NOT NULL,
  `quantity` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `category_title` varchar(100) NOT NULL,
  `department_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `category_title`, `department_id`) VALUES
(1, 'T-Shirts', 1),
(2, 'Blouses', 1),
(3, 'Hoodies', 1),
(4, 'Jeans', 1),
(5, 'Trousers', 1),
(6, 'Coats', 1),
(7, 'Evening Gowns', 1),
(8, 'Leggings', 1),
(9, 'Sweatpants', 1),
(10, 'Sneakers', 2),
(11, 'Running Shoes', 2),
(12, 'High-Tops', 2),
(13, 'Knee-High Boots', 2),
(14, 'Flip-Flops', 2),
(15, 'Slides', 2),
(16, 'Handbags', 3),
(17, 'Backpacks', 3),
(18, 'Hearings', 3),
(19, 'Bracelets', 3),
(20, 'Necklaces', 3),
(21, 'Chain Belts', 3),
(23, 'Baseball Caps', 3),
(24, 'Sun Hats', 3),
(25, 'Sport Socks', 4),
(26, 'Gym Bags', 4),
(27, 'Boxers', 4),
(28, 'Sports Bra', 4),
(29, 'Sunscreen', 9),
(30, 'Mascara', 4),
(31, 'Cologne', 9),
(32, 'Cushions', 8),
(33, 'Pillow Cases', 8),
(34, 'Duvet Covers', 8),
(35, 'Formal Shoes', 2);

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `department_id` int(11) NOT NULL,
  `department_title` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`department_id`, `department_title`) VALUES
(1, 'Clothings'),
(2, 'Shoes'),
(3, 'Accessories'),
(4, 'Activewear & Sportswear'),
(5, 'Intimates & Sleepwear'),
(6, 'Seasonal & Specialty Clothing'),
(7, 'Bags & Wallets'),
(8, 'Home & Lifestyle'),
(9, 'Beauty & Personal Care');

-- --------------------------------------------------------

--
-- Table structure for table `flash_sales`
--

CREATE TABLE `flash_sales` (
  `flash_sale_id` int(11) NOT NULL,
  `flash_sale_name` varchar(255) NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `discount_value` int(100) NOT NULL,
  `applicable_to` varchar(255) NOT NULL,
  `applicable_id` int(11) NOT NULL,
  `stock_limit` int(100) NOT NULL,
  `qty_sold` int(100) NOT NULL,
  `qty_remaining` int(11) GENERATED ALWAYS AS (`stock_limit` - `qty_sold`) STORED,
  `status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `product_title` varchar(100) NOT NULL,
  `demographic` varchar(255) NOT NULL,
  `product_description` varchar(255) NOT NULL,
  `category_title` int(11) NOT NULL,
  `brand_title` int(11) NOT NULL,
  `product_image1` varchar(255) NOT NULL,
  `product_size` varchar(255) NOT NULL,
  `instock` int(11) NOT NULL,
  `instock_sold` int(11) NOT NULL,
  `price` float NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `average_rating` float NOT NULL,
  `status` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_title`, `demographic`, `product_description`, `category_title`, `brand_title`, `product_image1`, `product_size`, `instock`, `instock_sold`, `price`, `date`, `average_rating`, `status`) VALUES
(1, 'Nike Dri-Fit Victory T-Shirt', 'men', 'Standard fit for a relaxed, no-fuss feel\r\n100% polyester\r\nMachine wash\r\nImported', 1, 3, 'dri-fit-victory-t-shirt.jpg', 'l', 10, 0, 1200, '2025-04-20 07:21:04', 0, 'true'),
(2, 'Nature Kenya Unisex Round Neck T-Shirt', 'unisex', '100% polyester The knit fabric has a soft feel and plenty of stretch so you can move freely.\r\nUpdated shoulder seams provide a smooth, natural feel as you swing.', 1, 10, 'men-round-neck-tshirt.jpg', 'm', 10, 0, 850, '2025-04-20 07:21:09', 0, 'true'),
(3, 'Kids Black Cotton Logo T-shirt', 'kids', 'Black Mini-me T-shirt for boys by luxury French brand Givenchy, made in soft and easy wearing cotton jersey. It has multi-colour gradient logo embroidery on the front and the designer&#039;s 4G logo on the back hem.', 1, 3, 'kids-black-cotton-logo-t-shirt.jpg', 's', 10, 0, 650, '2025-04-20 07:21:18', 0, 'true'),
(4, 'Gildan Men Dryblend Short Sleeve Polo 8800 (G880)', 'men', 'Polo Neck Short Sleeve Cotton Blend\r\nStyle Code : CHP-04 Neck Type : Polo Neck \r\nIdeal For Men', 1, 9, 'Gildan Men Dryblend Short Sleeve Polo 8800 (G880).webp', 'l', 10, 0, 900, '2025-04-20 07:21:21', 0, 'true'),
(5, 'Princess Cute Cherry Long Sleeve', 'kids', 'Indoor&amp;Outdoor,Fashion,Leisure girls 3t clothes 5t girls shirts shirts for girls toddler', 2, 6, 'cute cherry long sleeve.jpg', 'xs', 10, 0, 500, '2025-04-20 07:21:26', 0, 'true'),
(6, 'Neck Sash Blouse', 'women', 'Solid Color Wool blend classic collar front button fastening long sleeves panelled design straight hem Size: 2. Gender: Female. Material:Virgin Wool/Elastane/Silk. Colour: natural', 2, 10, 'Solid-color blouse with neck sash.webp', 's', 10, 0, 860, '2025-04-20 07:21:31', 0, 'true'),
(7, 'ANRABESS Women Oversized Hoodies', 'women', 'Women&#039;s oversized hoodies made with soft fleece material, it features a comfortable crew neck, long sleeves with drop shoulders, and a convenient pocket.', 3, 10, 'ANRABESS Women Hoodies Oversized Fleece Long Sleeve.jpg', 'xl', 10, 0, 950, '2025-04-20 07:21:36', 0, 'true'),
(8, 'Armor Hoodie Black', 'men', '100% cotton\r\n400 GSM\r\nKangaroo pocket at waist\r\nRib-knit cuffs and hem\r\nRelaxed fit\r\nMachine wash cold / hang to dry (recommended)', 3, 10, 'Armor Hoodie Black.webp', 'l', 10, 0, 1550, '2025-04-20 07:21:41', 0, 'true'),
(9, 'Nike Dunk Low Retro', 'men', 'White/White/Black\r\nStyle: DD1391-100', 10, 3, 'nike dunk low retro.webp', '42EUR/8UK', 10, 0, 3700, '2025-04-20 07:22:54', 0, 'true'),
(10, 'adidas Drop Step Low', 'men', 'Low-cut casual shoes inspired by vintage basketball attire. Designs from the adidas basketball archives join forces in these low-profile shoes.', 10, 2, 'adidas-drop-step-casual-sport-sh.jpg', '41EUR/7UK', 10, 0, 4700, '2025-04-20 07:21:52', 0, 'true'),
(11, 'Bad Bunny Trainers', 'unisex', 'These exclusively Adidas Bad Bunny Sneakers are made with a suede upper, buckle-fastening ankle strap, perforated detailing and a rubber sole.', 10, 2, 'addidas-bad-bunny-trainers-unisex-black.webp', '40EUR/6.5UK', 10, 0, 4500, '2025-04-20 07:21:57', 0, 'true'),
(12, 'addidas Tensaur School', 'kids', 'The breathable mesh upper feels light and airy while a classic lace-up front with a hook-and-loop top strap secures the fit.', 10, 2, 'addidas-kids-boys-tensaur-school-shoes.jpg', '25EUR/7UK', 10, 0, 4000, '2025-04-20 07:22:02', 0, 'true'),
(13, 'Alabama A&amp;M Pegasus 41', 'men', 'Experience lighter-weight energy return with dual Air Zoom units and a ReactX foam midsole.', 11, 3, 'NIKE+ZOOM+PEGASUS+41+AAMU.png', '42EUR/8UK', 10, 0, 3800, '2025-04-20 07:22:06', 0, 'true'),
(14, 'Nike Ultrafly', 'women', 'Bright Crimson/White/Black\r\nStyle: DZ0489-600\r\nOffers peak performance, sleek speed and endurance for those who want to summit nature’s playground.', 11, 3, 'WMNS+NIKE+ZOOMX+ULTRAFLY+TRAIL.png', '38EUR/5UK', 10, 0, 4000, '2025-04-20 07:22:11', 0, 'true'),
(15, 'Runfalcon 3 Running Shoes', 'women', 'The textile upper feels comfy and breathable, and the rubber outsole gives you plenty of grip for a confident stride.\r\nMade with a series of recycled materials, this upper features at least 50% recycled content. ', 11, 2, 'Runfalcon_3_Running_Shoes_Pink_HP7563_01_00_standard.avif', '40EUR/6.5UK', 10, 0, 4100, '2025-04-20 07:22:15', 0, 'true'),
(16, 'Air Jordan 1 MID', 'men', 'Inspired by the original AJ1, this mid-top edition maintains the iconic look you love while choice colours and crisp leather give it a distinct identity.', 12, 3, 'AIR+JORDAN+1+MID men.png', '43EUR/9UK', 10, 0, 4700, '2025-04-20 07:20:55', 0, 'true'),
(17, 'dark brown maxi GG canvas', 'women', 'Women&#039;s\r\nLeather lining\r\nPlatform sole with rubber bottom\r\nMid-heel\r\n55mm height\r\nMade in Italy', 15, 1, '623212_UKO00_2580_001_100_0000_Light-Womens-platform-slide-sandal.avif', '38EUR/5UK', 10, 0, 3800, '2025-04-20 07:20:48', 0, 'true'),
(18, 'Interlocking G', 'women', 'Black leather\r\nWomen&#039;s\r\nInterlocking G cut-out\r\nLeather sole\r\nFlat\r\n10mm heel height\r\nMade in Italy', 15, 1, 'Interlocking G.avif', '39EUR/6UK', 10, 0, 3700, '2025-04-20 07:20:43', 0, 'true'),
(19, 'Harvey Nichols', 'kids', 'Gucci Kids rubber sandals\r\nEmbossed GG plaque at strap, moulded footbed, debossed logo at footbed, gripped rubber sole, open toe', 15, 1, 'harvay-nichols.webp', '25EUR/7UK', 10, 0, 2200, '2025-04-20 07:20:36', 0, 'true'),
(20, 'Birkenstock Kyoto Mink Suede Slides', 'unisex', 'The athletic design and suede upper create a seamless silhouette that elevates everyday wear, without sacrificing comfort. The upper is made from especially soft suede and high-quality nubuck leather.', 15, 4, 'kyotominksuede.jpg', '39EUR/6UK', 10, 0, 2800, '2025-04-20 07:20:30', 0, 'true'),
(21, 'Oxford Laced Official Leather', 'men', 'A timeless addition to your collection that never goes out of style.\r\nCrafted from a high grade leather with robust stitching for long-lasting wear.\r\nPerfect for formal occasions and office wear.', 35, 10, 'trendy-men-official-leather-shoes.jpg', '43EUR/9UK', 10, 0, 4800, '2025-04-20 07:22:46', 0, 'true'),
(22, 'Mustard Women Official Brogue Lace up Ladies Shoes', 'women', 'Official Leather:\r\n Mustard Color:\r\nClosure-&gt; Lace up\r\nOutsole-&gt; Rubber', 35, 10, 'mustard-women-official-lace-up.webp', '40EUR/6.5UK', 10, 0, 2500, '2025-02-16 11:29:19', 0, 'true'),
(23, 'Louis Vuitton Speedy Bag', 'women', 'Dated back in 1965, this Speedy Bag was requested by Audrey Hepburn. She wanted a regular carry bag for travel and everyday in a Keepall bag form. Louis obliged and designed a miniature day bag that would be perfect for travelling for all elite classes.', 16, 11, 'Louis-Vuitton-Speedy-30-Damier-Ebene-Canvas-Bag.webp', 'm', 10, 0, 1500, '2025-04-20 07:22:20', 0, 'true'),
(24, 'Givenchy Pandora Box Chain Shoulder Bag', 'women', 'It has evolved into a number of transformations – from being a slouchy calfskin that takes on a square-ish appearance, to being a fine, structured evening bag that it is now. ', 16, 12, 'Givenchy-Pandora-Box-Chain-Shoulder-Bag.jpg', 'l', 10, 0, 1350, '2025-04-20 07:22:24', 0, 'true'),
(25, 'Nike Hayward Backpack', 'kids', '100% polyester construction\r\nAdjustable padded shoulder straps and top grab handle\r\nLarge main compartment with internal laptop slip pocket and dual zipper closure\r\nLarge front pocket with internal zippered mesh pouch and zipper closure\r\n1 mesh side pocke', 17, 3, 'Nike Hayward Backpack.avif', 's', 10, 0, 899, '2025-04-20 07:22:29', 0, 'true'),
(26, 'Power Backpack', 'unisex', 'Hitting the books or the gym, this versatile bag has you covered. Multiple pockets keep your must-haves organised and the padded sleeve protects your laptop. The durable coated base is built to withstand everything from bumps and scrapes to damp floors. C', 17, 2, 'Power_Backpack_Blue_IT5360_01_standard.avif', 'l', 10, 0, 1200, '2025-04-20 07:25:11', 0, 'true'),
(27, '´47MLB New York Yankees &#039;47 CLEAN UP w/No Loop Label', 'men', 'The &#039;47 Clean Up Cap with curved visor is an absolute fan favorite. Made of cotton twill material, the headwear has a relaxed fit and can be adjusted with the closure at the back. The design is rounded out by embroidery on the front.', 23, 5, '47MLB New York Yankees 47 CLEAN UP wNo Loop Label.webp', 'm', 10, 0, 559, '2025-04-20 07:25:15', 0, 'true'),
(28, ' New Era MLB New York Yankees Pin Stripe Size Cap, Chrome White / Walnut', 'men', 'New Era 7 5/8 Fitted Hat New York Yankees Black &amp;Gold Cap\r\nPre-owned\r\nNew York Yankees hat cap strap back blue white pinstripe &#039;47 mvp baseball mens\r\n\r\nAmerican Needle MLB Wool New York Yankees Pin Stripe Fitted Hat 7 1/2 ~ Vintage\r\n', 23, 5, 'yk.webp', 'l', 10, 0, 630, '2025-04-20 07:26:06', 0, 'true'),
(29, 'TREFOIL BASEBALL CA', 'unisex', 'Made of 100% Cotton\r\nPre-curved brim\r\nAdjustable back strap with D-ring closure\r\nTrefoil graphic\r\nTwill', 23, 2, 'TREFOIL BASEBALL CA.avif', 'l', 10, 0, 1020, '2025-04-20 07:25:27', 0, 'true'),
(30, 'ZARA Mens Medium Underwear Boxer ', 'men', 'Blue White Dots Elastic Waist Front Pouch NWOT', 27, 6, 'zara-men-boxer.webp', 'm', 10, 0, 330, '2025-04-20 07:25:34', 0, 'true'),
(31, '6-14 YEARS/ BOXERS X RICARDO CAVOLO', 'kids', 'Boxers with elastic waistband.', 27, 6, 'zara-kids-boxers.jpg', 's', 10, 0, 280, '2025-04-20 07:25:38', 0, 'true'),
(32, 'Gold-Tone Pavé Logo Link Flex Bracelet', 'women', 'Approx. length: 7-1/4&quot;\r\n\r\nSet in gold-tone mixed metal\r\n\r\nGlass crystal\r\n\r\nFoldover clasp closure', 19, 12, 'givenchy-flex-bracelet.webp', 'm', 10, 0, 2300, '2025-04-20 07:29:48', 0, 'true'),
(33, 'PANDORA Sterling Silver Heart Necklace with Adjustable Clasp', 'women', 'Let love guide your look with this sterling silver heart necklace, which can be worn in two different lengths thanks to its adjustable clasp.', 20, 13, 'PANDORA Sterling Silver Heart Necklace with Adjustable Clasp.webp', 'l', 10, 0, 12880, '2025-04-20 07:25:46', 0, 'true'),
(34, 'Pandora Icons Heart Clasp Bracelet', 'women', 'Item display length 17 centimetres Item width 4.5 centimetres Material Silber Metal type Sterling Silver Clasp type Locking Clip Clasp Metal weight 0.5 g Country of origin Germany\r\nLet your heart beat faster with this romantic version of Pandora&#039;s Ch', 19, 13, 'pandora-moments-heart-clasp-snake-chain.jpg', 'm', 10, 0, 10080, '2025-04-20 07:25:50', 0, 'true');

-- --------------------------------------------------------

--
-- Stand-in structure for view `product_promotions`
-- (See below for the actual view)
--
CREATE TABLE `product_promotions` (
`product_id` int(11)
,`product_name` varchar(100)
,`original_price` float
,`display_price` double
,`discount_value` int(100)
,`promotion_id` int(11)
,`product_promo_start_date` datetime
,`product_promo_end_date` datetime
,`category_promo_start_date` datetime
,`category_promo_end_date` datetime
);

-- --------------------------------------------------------

--
-- Table structure for table `promotions`
--

CREATE TABLE `promotions` (
  `promotion_id` int(11) NOT NULL,
  `promotion_name` varchar(255) NOT NULL,
  `discount_value` int(100) NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `applicable_to` varchar(255) NOT NULL,
  `applicable_id` int(11) NOT NULL,
  `minimum_cart_value` int(11) NOT NULL,
  `status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `review_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `review_summary` varchar(255) NOT NULL,
  `review_text` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `slides`
--

CREATE TABLE `slides` (
  `slide_id` int(11) NOT NULL,
  `image_cover` varchar(255) NOT NULL,
  `header_title` varchar(100) NOT NULL,
  `mini_title` varchar(100) NOT NULL,
  `description` varchar(255) NOT NULL,
  `category_id` int(100) NOT NULL,
  `created_date` datetime NOT NULL,
  `status` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_orders`
--

CREATE TABLE `user_orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount_due` int(255) NOT NULL,
  `invoice_number` int(255) NOT NULL,
  `total_products` int(255) NOT NULL,
  `order_date` datetime NOT NULL,
  `expected_date` datetime NOT NULL,
  `delivered_date` datetime DEFAULT NULL,
  `payment_mode` varchar(255) NOT NULL,
  `contact_number` int(100) NOT NULL,
  `contact_email` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `order_status` varchar(255) NOT NULL,
  `order_feedback` varchar(255) NOT NULL,
  `feedback_type` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_table`
--

CREATE TABLE `user_table` (
  `user_id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `user_email` varchar(100) NOT NULL,
  `gender` varchar(255) NOT NULL,
  `date_of_birth` date NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `user_image` varchar(255) NOT NULL,
  `user_mobile` varchar(20) NOT NULL,
  `created_at` datetime NOT NULL,
  `status` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_table`
--

INSERT INTO `user_table` (`user_id`, `full_name`, `user_name`, `user_email`, `gender`, `date_of_birth`, `user_password`, `user_image`, `user_mobile`, `created_at`, `status`) VALUES
(1, 'John Doe', 'john_doe', 'johndoe@gmail.com', 'male', '2003-02-20', '$2y$10$y8mybpDdDbV7X84VUJtMmeNzHAboOA1r2AnqYCpnuBjCP1eUeX6xy', 'joker.jpg', '0712345678', '2025-04-20 10:17:38', 'active'),
(2, 'Jane Doe', 'janedoe', 'janedoe@gmail.com', 'female', '2007-04-17', '$2y$10$GoQcu9LdRv2S9H5bGKA6lex9v2qGhBmWjEXHY/n1zDfsX5AhSmVSG', 'download.jpg', '0711223344', '2025-04-20 10:28:30', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `view_order_details`
--

CREATE TABLE `view_order_details` (
  `invoice_number` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `subtotal` int(11) NOT NULL,
  `order_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure for view `product_promotions`
--
DROP TABLE IF EXISTS `product_promotions`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `product_promotions`  AS SELECT `p`.`product_id` AS `product_id`, `p`.`product_title` AS `product_name`, `p`.`price` AS `original_price`, CASE WHEN `pr1`.`promotion_id` is not null AND `pr1`.`status` = 'active' THEN `p`.`price`- `p`.`price` * (`pr1`.`discount_value` / 100) WHEN `pr2`.`promotion_id` is not null AND `pr2`.`status` = 'active' THEN `p`.`price`- `p`.`price` * (`pr2`.`discount_value` / 100) ELSE `p`.`price` END AS `display_price`, CASE WHEN `pr1`.`promotion_id` is not null AND `pr1`.`status` = 'active' THEN `pr1`.`discount_value` WHEN `pr2`.`promotion_id` is not null AND `pr2`.`status` = 'active' THEN `pr2`.`discount_value` ELSE NULL END AS `discount_value`, CASE WHEN `pr1`.`promotion_id` is not null THEN `pr1`.`promotion_id` WHEN `pr2`.`promotion_id` is not null THEN `pr2`.`promotion_id` ELSE NULL END AS `promotion_id`, `pr1`.`start_date` AS `product_promo_start_date`, `pr1`.`end_date` AS `product_promo_end_date`, `pr2`.`start_date` AS `category_promo_start_date`, `pr2`.`end_date` AS `category_promo_end_date` FROM ((`products` `p` left join `promotions` `pr1` on(`p`.`product_id` = `pr1`.`applicable_id` and `pr1`.`applicable_to` = 'product')) left join `promotions` `pr2` on(`p`.`category_title` = `pr2`.`applicable_id` and `pr2`.`applicable_to` = 'category')) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_logs`
--
ALTER TABLE `admin_logs`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `admin_table`
--
ALTER TABLE `admin_table`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`brand_id`);
ALTER TABLE `brands` ADD FULLTEXT KEY `brand_title` (`brand_title`);

--
-- Indexes for table `cart_details`
--
ALTER TABLE `cart_details`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);
ALTER TABLE `categories` ADD FULLTEXT KEY `category_title` (`category_title`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`department_id`);
ALTER TABLE `department` ADD FULLTEXT KEY `department_title` (`department_title`);

--
-- Indexes for table `flash_sales`
--
ALTER TABLE `flash_sales`
  ADD PRIMARY KEY (`flash_sale_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);
ALTER TABLE `products` ADD FULLTEXT KEY `product_title` (`product_title`,`product_description`,`demographic`);

--
-- Indexes for table `promotions`
--
ALTER TABLE `promotions`
  ADD PRIMARY KEY (`promotion_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`review_id`);

--
-- Indexes for table `slides`
--
ALTER TABLE `slides`
  ADD PRIMARY KEY (`slide_id`);

--
-- Indexes for table `user_orders`
--
ALTER TABLE `user_orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `user_table`
--
ALTER TABLE `user_table`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_logs`
--
ALTER TABLE `admin_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `admin_table`
--
ALTER TABLE `admin_table`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `brand_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `department_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `flash_sales`
--
ALTER TABLE `flash_sales`
  MODIFY `flash_sale_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `promotions`
--
ALTER TABLE `promotions`
  MODIFY `promotion_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `slides`
--
ALTER TABLE `slides`
  MODIFY `slide_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_orders`
--
ALTER TABLE `user_orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_table`
--
ALTER TABLE `user_table`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

DELIMITER $$
--
-- Events
--
CREATE DEFINER=`root`@`localhost` EVENT `update_promotion_status` ON SCHEDULE EVERY '0:1' MINUTE_SECOND STARTS '2025-01-04 10:30:31' ON COMPLETION NOT PRESERVE ENABLE DO BEGIN
    UPDATE promotions
    SET status = 'inactive'
    WHERE 
        status = 'active' 
        AND NOW() > end_date;
END$$

CREATE DEFINER=`root`@`localhost` EVENT `update_flash_sales_status` ON SCHEDULE EVERY '0:1' MINUTE_SECOND STARTS '2025-01-06 09:26:50' ON COMPLETION NOT PRESERVE ENABLE DO BEGIN
    UPDATE flash_sales
    SET status = 'inactive'
    WHERE 
        status = 'active'
        AND (NOW() > end_date OR qty_remaining = 0);
END$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
