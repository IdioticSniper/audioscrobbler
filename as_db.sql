SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


CREATE TABLE `as_artists` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `added_on` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `as_scrobbles` (
  `id` int(11) NOT NULL,
  `song_artist` varchar(500) DEFAULT NULL,
  `song_title` text NOT NULL,
  `song_length` int(11) NOT NULL,
  `played_by` text NOT NULL,
  `played_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `as_songs` (
  `id` int(11) NOT NULL,
  `song_artist` text NOT NULL,
  `song_title` text NOT NULL,
  `song_length` int(11) NOT NULL,
  `added_on` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `as_users` (
  `id` int(11) NOT NULL,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `password_md5` text NOT NULL,
  `registered_on` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


ALTER TABLE `as_artists`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `as_scrobbles`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `as_songs`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `as_users`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `as_artists`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `as_scrobbles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `as_songs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `as_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
