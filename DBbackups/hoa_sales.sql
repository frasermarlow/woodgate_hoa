-- phpMyAdmin SQL Dump
-- version 4.9.11
-- https://www.phpmyadmin.net/
--
-- Host: db792354502.hosting-data.io
-- Generation Time: Nov 20, 2023 at 05:42 AM
-- Server version: 5.7.42-log
-- PHP Version: 7.0.33-0+deb9u12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db792354502`
--

-- --------------------------------------------------------

--
-- Table structure for table `hoa_sales`
--

CREATE TABLE `hoa_sales` (
  `type` text NOT NULL,
  `unit` int(11) NOT NULL,
  `transaction_date` date NOT NULL,
  `sale_price` int(11) NOT NULL,
  `realtor` text NOT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `hoa_sales`
--

INSERT INTO `hoa_sales` (`type`, `unit`, `transaction_date`, `sale_price`, `realtor`, `id`) VALUES
('SALE', 153, '2003-06-26', 211500, '', 69),
('SALE', 153, '2000-12-06', 154900, '', 68),
('SALE', 153, '1997-08-22', 137000, '', 67),
('SALE', 152, '2012-10-03', 289000, 'Coldwell Banker ', 66),
('SALE', 152, '2012-04-11', 160000, '', 65),
('SALE', 151, '1986-09-01', 113500, '', 64),
('SALE', 150, '1997-01-03', 140500, '', 63),
('SALE', 149, '1978-10-01', 54880, '', 62),
('SALE', 148, '1986-12-01', 110000, '', 61),
('SALE', 147, '2003-05-15', 193500, 'Audrey A Cover', 60),
('SALE', 146, '2005-11-15', 268000, '', 58),
('SALE', 147, '1996-03-07', 133500, '', 59),
('SALE', 145, '2000-07-10', 146000, '', 57),
('SALE', 143, '1994-04-25', 139000, '', 56),
('SALE', 142, '1999-09-14', 143000, '', 55),
('SALE', 140, '2018-08-07', 340000, '', 52),
('SALE', 141, '1996-05-02', 125000, '', 53),
('SALE', 142, '2018-06-05', 328427, '', 54),
('SALE', 139, '1999-06-18', 140000, '', 51),
('SALE', 138, '1998-06-08', 140000, '', 50),
('SALE', 137, '2015-12-21', 330000, 'Tony Dahm', 49),
('SALE', 137, '2005-08-18', 320000, '', 48),
('SALE', 137, '2001-09-18', 185000, '', 47),
('SALE', 136, '2004-06-09', 253000, '', 46),
('SALE', 136, '2013-10-15', 290000, 'Chris Talone', 45),
('SALE', 135, '2011-07-19', 247500, '', 44),
('SALE', 134, '2016-11-16', 285000, 'Lily Wu', 43),
('SALE', 133, '2009-06-24', 278000, '', 42),
('SALE', 133, '2006-10-10', 292000, '', 41),
('SALE', 132, '1998-11-12', 135000, '', 40),
('SALE', 132, '2004-10-07', 280000, '', 39),
('SALE', 132, '2015-04-07', 275000, '', 38),
('SALE', 131, '2018-05-02', 300000, '', 36),
('SALE', 132, '2019-04-30', 325000, 'Tom Toole Sales Group', 37),
('SALE', 131, '2013-11-08', 291500, '', 35),
('SALE', 131, '2006-09-11', 313500, '', 34),
('SALE', 131, '2002-05-14', 160000, '', 33),
('SALE', 131, '1995-01-20', 135000, '', 32),
('SALE', 130, '2015-12-15', 285000, 'Anita Lockhart', 31),
('SALE', 130, '2011-04-20', 235000, '', 30),
('SALE', 130, '2000-08-24', 154000, '', 29),
('SALE', 129, '2002-08-22', 258000, '', 28),
('SALE', 128, '2016-12-19', 275000, '', 27),
('SALE', 128, '2009-10-27', 272500, '', 26),
('SALE', 128, '2004-03-19', 232000, '', 25),
('SALE', 127, '1990-05-01', 147750, '', 24),
('SALE', 126, '2014-11-14', 280000, 'Phyllis Zecca', 23),
('SALE', 126, '2000-08-02', 141200, '', 22),
('SALE', 125, '1998-06-04', 145000, '', 21),
('SALE', 124, '2017-07-12', 320000, 'Karen Belber', 20),
('SALE', 123, '2007-07-27', 281000, '', 19),
('SALE', 122, '2008-04-16', 273000, 'John Collins', 18),
('SALE', 121, '1998-01-27', 130000, '', 17),
('SALE', 120, '2008-10-09', 256000, '', 16),
('SALE', 120, '2000-09-21', 146108, '', 15),
('SALE', 119, '2005-05-04', 284900, '', 14),
('SALE', 118, '2004-12-28', 260000, '', 13),
('SALE', 118, '2014-09-04', 245000, 'Frank May', 12),
('SALE', 116, '1998-06-17', 125000, 'Pat Moyer', 11),
('SALE', 115, '2015-06-25', 276500, '', 10),
('SALE', 114, '1996-10-23', 130000, '', 8),
('SALE', 115, '2018-08-03', 312000, '', 9),
('SALE', 113, '2003-06-30', 216000, '', 7),
('SALE', 113, '1999-04-30', 138000, '', 6),
('SALE', 113, '1997-08-20', 135000, '', 5),
('SALE', 112, '1998-07-10', 137000, '', 4),
('SALE', 111, '2015-03-20', 275000, 'JoAnn Piazza', 3),
('SALE', 111, '2004-09-08', 255000, '', 2),
('SALE', 111, '1999-01-22', 142000, '', 1),
('SALE', 153, '2005-12-09', 297000, '', 70),
('SALE', 153, '2007-08-15', 302000, '', 71),
('SALE', 153, '2011-06-07', 265000, 'Debbie McCabe', 72),
('SALE', 154, '2006-03-20', 290000, '', 73),
('SALE', 155, '1994-01-01', 140000, '', 74),
('SALE', 156, '1998-12-21', 132000, '', 75),
('SALE', 156, '2001-01-08', 155250, '', 76),
('SALE', 156, '2008-06-11', 268000, '', 77),
('SALE', 156, '2017-10-05', 335500, 'John Collins', 78),
('SALE', 157, '1997-12-30', 139000, '', 79),
('SALE', 158, '2000-08-14', 165000, '', 80),
('SALE', 159, '1996-10-14', 143000, '', 81),
('SALE', 161, '1978-08-01', 61880, '', 82),
('SALE', 162, '2002-09-06', 180500, '', 83),
('SALE', 163, '1989-05-01', 150000, '', 84),
('SALE', 164, '1997-07-23', 133000, '', 85),
('SALE', 164, '2004-07-30', 250000, '', 86),
('SALE', 165, '2001-05-08', 150000, '', 87),
('SALE', 165, '2002-05-10', 185000, '', 88),
('SALE', 166, '1994-01-25', 130000, '', 89),
('SALE', 166, '1999-09-02', 145000, '', 90),
('SALE', 167, '1995-06-08', 135000, '', 91),
('SALE', 167, '2008-08-22', 286000, '', 92),
('SALE', 168, '2005-06-16', 275900, '', 94),
('SALE', 168, '2012-09-14', 250000, '', 95),
('SALE', 169, '1994-05-17', 134000, '', 96),
('SALE', 169, '1999-07-13', 144000, '', 97),
('SALE', 169, '2019-03-25', 310000, 'BHHS Fox & Roach Devon', 98),
('SALE', 170, '1978-08-01', 59380, '', 99),
('SALE', 144, '1986-12-01', 113250, '', 100),
('SALE', 144, '2007-06-28', 295000, '', 101),
('SALE', 144, '2014-10-10', 278000, '', 102),
('SALE', 143, '2019-06-20', 340000, '', 116),
('OFF RECORD', 111, '2013-02-06', 250000, '', 111),
('FORECLOSURE', 140, '2017-08-11', 215000, '', 115),
('OFF RECORD', 118, '2017-07-01', 289000, '', 113),
('LISTED', 111, '2019-06-01', 339000, '', 114),
('SALE', 167, '2019-06-03', 336000, 'JP Platinum Realty Services', 117),
('SALE', 168, '2019-10-23', 354000, '', 118),
('SALE', 153, '2020-12-18', 364500, '', 119),
('SALE', 166, '2020-11-20', 400000, '', 120),
('SALE', 131, '2021-06-14', 398500, '', 127),
('SALE', 152, '2021-04-16', 415000, '', 128),
('SALE', 154, '2021-06-30', 420000, 'Sale by owner', 126),
('SALE', 147, '2021-10-15', 407000, '', 130),
('SALE', 111, '2021-06-29', 380000, '', 131),
('SALE', 128, '2022-09-12', 451000, '', 132),
('SALE', 136, '2023-07-11', 400000, '', 133),
('SALE', 119, '2023-05-10', 380000, '', 134);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `hoa_sales`
--
ALTER TABLE `hoa_sales`
  ADD KEY `id` (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `hoa_sales`
--
ALTER TABLE `hoa_sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=135;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
