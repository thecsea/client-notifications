
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


--
-- Database: `client_notifications`
--

-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int(12) NOT NULL,
  `user_id` int (11) NOT NULL,
  `message` text NOT NULL,
  `type` varchar(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `notifications`
ADD PRIMARY KEY (`id`);

ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

