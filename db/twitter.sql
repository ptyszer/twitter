-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 08 Sty 2018, 19:45
-- Wersja serwera: 10.1.26-MariaDB
-- Wersja PHP: 7.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `twitter`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `post_id` int(11) DEFAULT NULL,
  `text` varchar(60) COLLATE utf8_polish_ci DEFAULT NULL,
  `creation_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `comments`
--

INSERT INTO `comments` (`id`, `user_id`, `post_id`, `text`, `creation_date`) VALUES
(2, 9, 7, 'nice to see you', '2018-01-02 23:02:42'),
(3, 9, 7, 'afdss', '2018-01-02 23:03:06'),
(7, 7, 7, 'hello!', '2018-01-02 23:17:00'),
(9, 9, 7, 'adsdad', '2018-01-02 23:22:06'),
(10, 9, 10, 'm,m,m,', '2018-01-02 23:33:43'),
(11, 9, 1, 'hello', '2018-01-03 09:43:22'),
(12, 7, 4, 'fhfhthf', '2018-01-07 21:26:34'),
(13, 7, 11, 'gjgyjj', '2018-01-07 21:26:47'),
(15, 10, 10, 'kfjgkjfhgjdfhgkjdhgjkdhfjghdjkfghjdkfhgjkdhfjkhkjhgsjkhslhrk', '2018-01-08 17:10:13');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `tweets`
--

CREATE TABLE `tweets` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `text` varchar(140) COLLATE utf8_polish_ci DEFAULT NULL,
  `creation_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `tweets`
--

INSERT INTO `tweets` (`id`, `user_id`, `text`, `creation_date`) VALUES
(1, 7, 'hello world!\r\n', '2017-12-31 14:04:18'),
(4, 1, 'my first tweet\r\n', '2017-12-31 14:05:47'),
(5, 7, 'hey everyone', '2017-12-31 14:07:21'),
(6, 7, 'dhdfdh', '2018-01-02 13:14:59'),
(7, 8, 'first tweet', '2018-01-02 19:38:53'),
(8, 8, 'hello!', '2018-01-02 19:42:00'),
(10, 9, 'jkjkj', '2018-01-02 23:33:34'),
(11, 7, 'hfthfhf', '2018-01-07 21:26:42');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) COLLATE utf8_polish_ci NOT NULL,
  `username` varchar(255) COLLATE utf8_polish_ci NOT NULL,
  `hash_pass` varchar(255) COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `users`
--

INSERT INTO `users` (`id`, `email`, `username`, `hash_pass`) VALUES
(1, 'user1@gmail.com', 'user1', '$2y$10$LLRc8YPxyIZOTguDusMsEuF6JhcssAu4b0jSvPp/o07WG59w2zl.u'),
(3, 'ziomek@o2.pl', 'ziomek', '$2y$10$nB6Ov.5qiQwJCRi0pl/OguNVd7hD3lubS1T9meA8ZPiI5iPnxW6JO'),
(5, 'user2@o2.pl', 'user2', '$2y$10$V49cJ1YQ4xHN4wUI1rCCq.xB9QLMUDoEGb5frgSaqaYphdvkr5TE6'),
(6, 'user3@wp.pl', 'user3', '$2y$10$d.OQoDsWznMke4dbNByElOukJ1010aq6aGUQjoGuyhKC20vzXz.lW'),
(7, 'user@wp.pl', 'user', '$2y$10$4XUwIHS8tdjVqJltBEYnteb0ZAxPy9xXbI7NWAlG2Z4SHNahhrF2e'),
(8, 'jan@wp.pl', 'jan', '$2y$10$kU5LcBl6.HpwJuzxoHFGg.DkA.Hf9cUWilUKL61EFvm.hveeeKUSi'),
(9, 'john@wp.pl', 'john', '$2y$10$ZqIy72UFDSz2ShIxjTvtUeEad9jQ7RGwyv7mh7qmJEs1FTMlHOUCK'),
(11, 'some@one', 'someone', '$2y$10$ShplAofzYzdMOc9Hj4.f7efkW2AKFHGDbSzC9nbMKNRqrfV4fT52m');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`);

--
-- Indexes for table `tweets`
--
ALTER TABLE `tweets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT dla tabeli `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT dla tabeli `tweets`
--
ALTER TABLE `tweets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT dla tabeli `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Ograniczenia dla zrzutów tabel
--

--
-- Ograniczenia dla tabeli `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `tweets` (`id`) ON DELETE CASCADE;

--
-- Ograniczenia dla tabeli `tweets`
--
ALTER TABLE `tweets`
  ADD CONSTRAINT `tweets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
