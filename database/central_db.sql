-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 13, 2024 at 09:11 AM
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
-- Database: `central_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `category_list`
--

CREATE TABLE `category_list` (
  `id` int(30) NOT NULL,
  `name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category_list`
--

INSERT INTO `category_list` (`id`, `name`) VALUES
(1, 'Bakery'),
(4, 'Snacks'),
(5, 'Drinks'),
(6, 'Dry Fruits'),
(7, 'Edible Oils & Ghee'),
(8, 'Masalas & Spices'),
(9, 'Breads & Buns'),
(10, 'Dairy'),
(11, 'Energy & Soft Drinks'),
(12, 'Coffee and Tea'),
(13, 'Biscuits & Cookies'),
(14, 'Chocolates & Candies'),
(15, 'Noodles , Pasta & Vermicelli'),
(16, 'Pickles & Chutney'),
(17, 'Spreads, Sauces & Ketchup'),
(18, 'Bath & HandWash'),
(19, 'Fragrances & Deos'),
(20, 'Hair Care'),
(21, 'Oral Care'),
(22, 'Detergents & Dishwash'),
(23, 'All Purpose Cleaner'),
(24, 'Fresheners & Repellents'),
(25, 'Party & Festive Needs '),
(26, 'Stationery'),
(27, 'Kitchen Accessories'),
(28, 'Appliances'),
(29, 'Eggs');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `image` text NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `image`, `category_id`) VALUES
(1, 'Maaza', 'maaza.jpg', 5),
(2, 'Thumps Up', 'thumps_up.jpg', 5),
(3, 'Parle-G', 'parle_g.png', 13),
(4, 'Parle-G Gold', 'parle_g_gold.png', 13),
(5, 'Parle-G Chhota Bheem', 'parle_g_chhota_bheem.png', 13),
(6, '20-20 Cookies Classic BUTTER', '20_20_cookies_classic_butter.png', 13),
(7, '20-20 Cookies Classic Cashew', '20_20_cookies_classic_cashew.png', 13),
(8, '20-20 Gold Cookies', '20_20_gold_cookies.png', 13),
(9, '20-20 Nice', '20_20_nice.png', 13),
(10, 'Happy Happy Cookies', 'happy_happy_cookies.png', 13),
(11, 'Magix Orange', 'magix_orange.png', 13),
(12, 'Magix Elaichi', 'magix_elaichi.png', 13),
(13, 'Magix Chocolate', 'magix_chocolate.png', 13),
(14, 'Monaco', 'monaco.png', 13),
(15, 'Monaco Zeera ', 'monaco_zeera.png', 13),
(16, 'Monaco Pizza', 'monaco_pizza.png', 13),
(17, 'Monaco Piri Piri', 'monaco_piri_piri.png', 13),
(18, 'Krackjack', 'krackjack.png', 13),
(19, 'Krackjack ButTER Masala ', 'krackjack_butter_masala.png', 13),
(20, 'Krackjack Jeera', 'krackjack_jeera.png', 13),
(21, 'Top Crackers ', 'top_crackers.png', 13),
(22, 'Top Spin Crackers ', 'top_spin_crackers.png', 13),
(23, 'Parle Marie ', 'parle_marie.png', 13),
(24, 'COCONUT COOKIES ', 'coconut_cookies.png', 13),
(25, 'Fab Bourbon', 'fab_bourbon.png', 13),
(26, 'Fabio Chocolate ', 'fabio_chocolate.png', 13),
(27, 'Fab Jam-In', 'fab_jam_in.png', 13),
(28, 'Fabio Vanilla', 'fabio_vanilla.png', 13),
(29, 'Hide & Seek Chocolate Chip Cookies', 'hide_&_seek_chocolate_chip_cookies.png', 13),
(30, 'Hide & Seek Chocolate & Almonds', 'hide_&_seek_chocolate_&_almonds.png', 13),
(31, 'Hide & Seek Café Mocha ', 'hide_&_seek_café_mocha.png', 13),
(32, 'Hide & Seek Choco Rolls', 'hide_&_seek_choco_rolls.png', 13),
(33, 'Hide & Seek black bourbon', 'hide_&_seek_black_bourbon.png', 13),
(34, 'Hide & Seek Crème SANDWICHES', 'hide_&_seek_crème_sandwiches.png', 13),
(35, 'MILANO Chocolate Chip Cookies', 'milano_chocolate_chip_cookies.png', 13),
(36, 'MILANO Centre Filled Cookies - Dark Choco', 'milano_centre_filled_cookies_dark_choco.png', 13),
(37, 'MILANO CENTRE FILLED Cookies - Mixed BerrieS', 'milano_centre_filled_cookies_mixed_berries.png', 13),
(38, 'MILANO CENTRE FILLED Cookies -Choco & Hazelnut', 'milano_centre_filled_cookies_choco_&_hazelnut.png', 13),
(39, 'Kaccha Mango Bite', 'kaccha_mango_bite.png', 14),
(40, 'Bigger Kaccha Mango Bite', 'bigger_kaccha_mango_bite.png', 14),
(41, 'Mango Bite', 'mango_bite.png', 14),
(42, 'Mazelo Fruit Gang', 'mazelo_fruit_gang.png', 14),
(43, 'Mazelo ', 'mazelo.png', 14),
(44, 'Londonderry', 'londonderry.png', 14),
(45, 'Poppins ', 'poppins.png', 14),
(46, 'Melody', 'melody.png', 14),
(47, 'Kismi Single Twist', 'kismi_single_twist.png', 14),
(48, 'Kismi Elaichi Bar', 'kismi_elaichi_bar.png', 14),
(49, 'Kismi Classic Toffee', 'kismi_classic_toffee.png', 14),
(50, '2 in 1 ÉclairS', '2_in_1_éclairs.png', 14),
(51, 'Parle’s Wafers Tangy Tomato', 'parle’s_wafers_tangy_tomato.png', 4),
(52, 'Parle’s Wafers Cream n Onion', 'parle’s_wafers_cream_n_onion.png', 4),
(53, 'Parle’s Wafers Classic Salted', 'parle’s_wafers_classic_salted.png', 4),
(54, 'Parle’s Wafers Piri Piri', 'parle’s_wafers_piri_piri.png', 4),
(55, 'Parle Fulltoss Masala Masti', 'parle_fulltoss_masala_masti.png', 4),
(56, 'Parle Fulltoss Noodle Masala', 'parle_fulltoss_noodle_masala.png', 4),
(57, 'Parle Fulltoss Tangy Tomato', 'parle_fulltoss_tangy_tomato.png', 4),
(58, 'Parle Fulltoss Thai Sriracha', 'parle_fulltoss_thai_sriracha.png', 4),
(59, 'Parle Chatkeens Aloo Bhujia', 'parle_chatkeens_aloo_bhujia.png', 4),
(60, 'Parle Chatkeens Bhujia', 'parle_chatkeens_bhujia.png', 4),
(61, 'Parle Chatkeens Chatpata Matar', 'parle_chatkeens_chatpata_matar.png', 4),
(62, 'Parle Chatkeens Corn Flake', 'parle_chatkeens_corn_flake.png', 4),
(63, 'Parle Chatkeens Dal Biji', 'parle_chatkeens_dal_biji.png', 4),
(64, 'Parle Chatkeens Farali Chivda', 'parle_chatkeens_farali_chivda.png', 4),
(65, 'Parle Chatkeens Hotnspicy', 'parle_chatkeens_hotnspicy.png', 4),
(66, 'Parle Chatkeens Kt Meetha', 'parle_chatkeens_kt_meetha.png', 4),
(67, 'Parle Chatkeens Moong Dal', 'parle_chatkeens_moong_dal.png', 4),
(68, 'Parle Chatkeens Chana Dal', 'parle_chatkeens_chana_dal.png', 4),
(69, 'Parle Chatkeens Punjabi Tadka', 'parle_chatkeens_punjabi_tadka.png', 4),
(70, 'Parle Chatkeens Ratlami Sev', 'parle_chatkeens_ratlami_sev.png', 4),
(71, 'Parle Chatkeens Salted Peanuts', 'parle_chatkeens_salted_peanuts.png', 4),
(72, 'Parle Chatkeens Sev Murmura', 'parle_chatkeens_sev_murmura.png', 4),
(73, 'Parle Chatkeens South Mixture', 'parle_chatkeens_south_mixture.png', 4),
(74, 'Parle Chatkeens Tasty Peanuts', 'parle_chatkeens_tasty_peanuts.png', 4),
(75, 'Monaco Cheeslings', 'monaco_cheeslings.png', 4),
(76, 'Monaco Jeffs ', 'monaco_jeffs.png', 4),
(77, 'Monaco Sixer', 'monaco_sixer.png', 4),
(78, 'Parle MILK Rusk', 'parle_milk_rusk.png', 1),
(79, 'Parle ELAICHI Rusk', 'parle_elaichi_rusk.png', 1),
(80, 'Happy Happy Egg Cake Vanilla', 'happy_happy_egg_cake_vanilla.png', 1),
(81, 'Happy Happy Egg Cake Tutti Fruitty', 'happy_happy_egg_cake_tutti_fruitty.png', 1),
(82, 'Happy Happy Egg Cake Chocolate', 'happy_happy_egg_cake_chocolate.png', 1),
(83, 'Happy Happy Veg Cake Pineapple', 'happy_happy_veg_cake_pineapple.png', 1),
(84, 'Happy Happy Veg Cake Tutti Fruitty', 'happy_happy_veg_cake_tutti_fruitty.png', 1),
(85, 'Sunfeast Bounce Chocolate', 'sunfeast_bounce_chocolate.png', 13),
(86, 'Sunfeast Bounce Orange', 'sunfeast_bounce_orange.png', 13),
(87, 'Anmol Lemon Maaza', 'anmol_lemon_maaza.png', 13),
(88, 'Butter Twinz', 'butter_twinz.png', 13),
(89, 'Jeera Sugar-Free Cream Cracker', 'jeera_sugar-free_cream_cracker.png', 13),
(90, 'Choco Mazaa', 'choco_mazaa.png', 13),
(91, 'Yummy Chocolate', 'yummy_chocolate.png', 13),
(92, 'Yummy Elaichi', 'yummy_elaichi.png', 13),
(93, 'Yummy Orange', 'yummy_orange.png', 13),
(94, 'Jadoo', 'jadoo.png', 13),
(95, 'FruitBix', 'fruitbix.png', 13),
(96, 'Milk Made', 'milk_made.png', 13),
(97, 'Jeera Dhamaal', 'jeera_dhamaal.png', 13),
(98, 'Sugar-Free Cream Cracker', 'sugar-free_cream_cracker.png', 13),
(99, 'Hit & Run', 'hit_&_run.png', 13),
(100, 'Coconutty', 'coconutty.png', 13),
(101, 'E-Time', 'e-time.png', 13),
(102, 'Bakers Bix', 'bakers_bix.png', 13),
(103, 'Butter Bake', 'butter_bake.png', 13),
(104, 'Smileys Butter', 'smileys_butter.png', 13),
(105, 'Coconut Premium', 'coconut_premium.png', 13),
(106, 'Vita Marie Plus', 'vita_marie_plus.png', 13),
(107, '2 in 1', '2_in_1.png', 13),
(108, 'Veg Munch', 'veg_munch.png', 13),
(109, 'Thin Arrowroot', 'thin_arrowroot.png', 13),
(110, 'Fruit Maaza', 'fruit_maaza.png', 13),
(111, 'Vanilla Mazaa', 'vanilla_mazaa.png', 13),
(112, 'Yummy Milk', 'yummy_milk.png', 13),
(113, 'Snack It', 'snack_it.png', 13),
(114, 'Cheese Maaza', 'cheese_maaza.png', 13),
(115, 'Dream Lite', 'dream_lite.png', 13),
(116, 'Kaju Bake', 'kaju_bake.png', 13),
(117, 'Top Magic', 'top_magic.png', 13),
(118, 'Marie Plus', 'marie_plus.png', 13),
(119, 'Tip Top', 'tip_top.png', 13),
(120, 'Digestive', 'digestive.png', 13),
(121, 'Anmol Rusk Jeera', 'anmol_rusk_jeera.png', 1),
(122, 'Anmol Rusk Butter', 'anmol_rusk_butter.png', 1),
(123, 'Anmol Rusk Elaichi', 'anmol_rusk_elaichi.png', 1),
(124, 'Fruit and Nut Cookies', 'fruit_and_nut_cookies.png', 13),
(125, 'ChocoChip Cookies', 'chocochip_cookies.png', 13),
(126, 'Shortbread', 'shortbread.png', 13),
(127, 'Romanzo', 'romanzo.png', 13),
(128, 'Pineapple Cake', 'pineapple_cake.png', 1),
(129, 'Fruit Cake', 'fruit_cake.png', 1),
(130, 'Orange Cake', 'orange_cake.png', 1),
(131, 'Milk and Vanilla Cake', 'milk_and_vanilla_cake.png', 1),
(132, 'Chocolate Cake', 'chocolate_cake.png', 1),
(133, 'Yummy Orange Cake', 'yummy_orange_cake.png', 1),
(134, 'Kream Cake Choco Double', 'kream_cake_choco_double.png', 1),
(135, 'Kream Cake Mixed Fruit', 'kream_cake_mixed_fruit.png', 1),
(136, 'Kream Cake Choco Vanilla', 'kream_cake_choco_vanilla.png', 1),
(137, 'Kream Cake Vanilla', 'kream_cake_vanilla.png', 1),
(138, 'Kream Cake Orange', 'kream_cake_orange.png', 1),
(139, 'Kream Cake Lemon', 'kream_cake_lemon.png', 1),
(140, 'Yummy Chocolate Cake', 'yummy_chocolate_cake.png', 1),
(141, 'Veg Cake – Chocolate', 'veg_cake_–_chocolate.png', 1),
(142, 'Veg Cake – Mixed Fruits', 'veg_cake_–_mixed_fruits.png', 1),
(143, 'GOOGLY', 'googly.png', 13),
(144, 'GOOGLY TWIST', 'googly_twist.png', 13),
(145, 'GOOGLY SESAME', 'googly_sesame.png', 13),
(146, 'RICH MARIE', 'rich_marie.png', 13),
(147, 'SUGAR FREE MARIE', 'sugar_free_marie.png', 13),
(148, 'PETIT BEURRE', 'petit_beurre.png', 13),
(149, 'THE TOP', 'the_top.png', 13),
(150, 'TOP GOLD', 'top_gold.png', 13),
(151, 'TOP HERBS', 'top_herbs.png', 13),
(152, 'TOP TADKA', 'top_tadka.png', 13),
(153, 'ALMOND THINZ', 'almond_thinz.png', 13),
(154, 'MILK THINZ', 'milk_thinz.png', 13),
(155, 'COFFEE THINZ', 'coffee_thinz.png', 13),
(156, 'POTATO THINZ', 'potato_thinz.png', 13),
(157, 'CHAMP MILKUIT', 'champ_milkuit.png', 13),
(158, 'CHAMP MALT', 'champ_malt.png', 13),
(159, 'BUTTER BISCOTTI', 'butter_biscotti.png', 13),
(160, 'COCO MALAI', 'coco_malai.png', 13),
(161, 'JEERA WONDER', 'jeera_wonder.png', 13),
(162, 'CHOCOLATE WONDER', 'chocolate_wonder.png', 13),
(163, 'ELAICHI WONDER', 'elaichi_wonder.png', 13),
(164, 'NICE', 'nice.png', 13),
(165, 'HEYLO BUTTER COOKIES', 'heylo_butter_cookies.png', 13),
(166, 'HEYLO CASHEW COOKIES', 'heylo_cashew_cookies.png', 13),
(167, 'HEYLO T-TIME COOKIES', 'heylo_t-time_cookies.png', 13);

-- --------------------------------------------------------

--
-- Table structure for table `shops`
--

CREATE TABLE `shops` (
  `id` int(11) NOT NULL,
  `shop_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `contact` varchar(255) DEFAULT NULL,
  `cover_img` text NOT NULL,
  `shop_tagline` varchar(255) DEFAULT NULL,
  `shop_url` varchar(255) NOT NULL,
  `db_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



--
-- Indexes for table `category_list`
--
ALTER TABLE `category_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shops`
--
ALTER TABLE `shops`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `shop_url` (`shop_url`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category_list`
--
ALTER TABLE `category_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=168;

--
-- AUTO_INCREMENT for table `shops`
--
ALTER TABLE `shops`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
