SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";
CREATE DATABASE IF NOT EXISTS `humanity_test` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `humanity_test`;

CREATE TABLE `request` (
    `id` int(11) NOT NULL,
    `user_id` int(11) NOT NULL,
    `start_date` datetime NOT NULL,
    `end_date` datetime NOT NULL,
    `status` enum('NEW','APPROVED','REJECTED') NOT NULL,
    `type` enum('PAID','UNPAID','MEDICAL','') NOT NULL,
    `created_at` datetime DEFAULT NULL,
    `modified_at` datetime DEFAULT NULL,
    `processed_at` datetime DEFAULT NULL,
    `processed_by` int(11) DEFAULT NULL,
    `deleted_at` datetime DEFAULT NULL,
    `deleted_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `request` (`id`, `user_id`, `start_date`, `end_date`, `status`, `type`, `created_at`, `modified_at`, `processed_at`, `processed_by`, `deleted_at`, `deleted_by`) VALUES
    (1, 2, '2017-11-09 00:00:00', '2017-11-26 00:00:00', 'REJECTED', 'PAID', NULL, '2017-11-24 15:32:49', '2017-11-28 03:08:24', 4, NULL, NULL),
    (2, 2, '2017-11-16 00:00:00', '2017-11-18 00:00:00', 'REJECTED', 'MEDICAL', '2017-11-07 00:00:00', '2017-11-24 15:33:06', '2017-11-28 03:08:20', 4, NULL, NULL),
    (3, 2, '2017-11-02 00:00:00', '2017-11-05 00:00:00', 'REJECTED', 'PAID', NULL, NULL, '2017-11-28 02:05:59', 4, NULL, NULL),
    (4, 2, '2017-11-04 00:00:00', '2017-11-05 00:00:00', 'REJECTED', 'PAID', NULL, '2017-11-24 15:33:20', '2017-11-25 13:27:15', 4, '2017-11-25 13:53:10', 4),
    (5, 2, '2017-11-04 00:00:00', '2017-11-19 00:00:00', 'NEW', 'PAID', NULL, NULL, NULL, NULL, '2017-11-24 15:49:52', 2),
    (6, 2, '2017-11-04 00:00:00', '2017-11-19 00:00:00', 'NEW', 'PAID', NULL, NULL, NULL, NULL, '2017-11-24 15:49:56', 2),
    (7, 2, '2017-11-04 00:00:00', '2017-11-19 00:00:00', 'REJECTED', 'PAID', NULL, NULL, '2017-11-24 16:03:32', 4, NULL, NULL),
    (8, 2, '2017-11-03 00:00:00', '2017-11-05 00:00:00', 'NEW', 'MEDICAL', NULL, NULL, NULL, NULL, '2017-11-24 15:49:59', 2),
    (9, 2, '2017-11-03 00:00:00', '2017-11-19 00:00:00', 'NEW', 'MEDICAL', NULL, NULL, NULL, NULL, '2017-11-24 15:51:06', 4),
    (10, 2, '2017-11-28 00:00:00', '2017-11-30 00:00:00', 'NEW', 'PAID', '2017-11-24 00:00:00', '2017-11-24 15:31:33', '2017-11-24 16:36:35', 4, '2017-11-25 13:53:05', 4),
    (20, 3, '2017-11-09 00:00:00', '2017-11-12 00:00:00', 'APPROVED', 'PAID', '2017-11-24 00:00:00', '2017-11-24 18:41:03', '2017-11-24 16:03:08', 4, NULL, NULL),
    (22, 3, '2017-11-06 00:00:00', '2017-11-12 00:00:00', 'APPROVED', 'PAID', '2017-11-24 00:00:00', '2017-11-24 17:17:31', '2017-11-28 03:08:27', 4, NULL, NULL),
    (23, 1, '2017-11-28 00:00:00', '2017-11-30 00:00:00', 'APPROVED', 'MEDICAL', '2017-11-24 00:00:00', NULL, '2017-11-24 16:07:44', 4, NULL, NULL),
    (24, 1, '2017-11-09 00:00:00', '2017-11-15 00:00:00', 'NEW', 'PAID', '2017-11-24 00:00:00', NULL, NULL, NULL, '2017-11-24 15:49:12', 1),
    (26, 3, '2017-11-06 00:00:00', '2017-11-07 00:00:00', 'NEW', 'MEDICAL', '2017-11-24 17:19:32', NULL, '2017-11-24 17:21:39', 4, '2017-11-25 13:46:50', 4),
    (27, 1, '2017-10-01 00:00:00', '2017-11-26 00:00:00', 'NEW', 'PAID', '2017-11-25 13:26:21', NULL, '2017-11-25 13:46:58', 4, '2017-11-28 02:05:48', 1),
    (28, 2, '2017-11-02 00:00:00', '2017-11-24 00:00:00', 'APPROVED', 'MEDICAL', '2017-11-25 13:48:06', NULL, '2017-11-25 13:48:12', 4, NULL, NULL),
    (29, 1, '2017-11-10 00:00:00', '2017-11-11 00:00:00', 'APPROVED', 'MEDICAL', '2017-11-25 13:48:54', '2017-11-25 13:51:05', '2017-11-25 13:51:20', 4, NULL, NULL),
    (30, 1, '2017-11-01 00:00:00', '2017-11-01 00:00:00', 'NEW', 'PAID', '2017-11-28 02:22:14', NULL, NULL, NULL, NULL, NULL),
    (31, 1, '2017-11-02 00:00:00', '2017-11-10 00:00:00', 'NEW', 'PAID', '2017-11-28 02:24:32', NULL, NULL, NULL, NULL, NULL),
    (34, 4, '2017-11-02 00:00:00', '2017-11-04 23:59:59', 'NEW', 'PAID', '2017-11-28 03:10:46', NULL, NULL, NULL, NULL, NULL),
    (35, 3, '2016-12-27 00:00:00', '2017-01-02 23:59:59', 'APPROVED', 'PAID', '2017-11-28 03:12:54', '2017-11-28 03:13:36', '2017-11-28 03:13:44', 4, NULL, NULL);

CREATE TABLE `user` (
    `id` int(11) NOT NULL,
    `name` varchar(255) NOT NULL,
    `joined_date` date NOT NULL,
    `role` enum('USER','MANAGER') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `user` (`id`, `name`, `joined_date`, `role`) VALUES
    (1, 'Andrew Tan', '2016-02-12', 'USER'),
    (2, 'Stanly Walker', '2017-11-18', 'USER'),
    (3, 'Anthony Hopkins', '2005-07-24', 'USER'),
    (4, 'Michael Faraday', '1854-06-04', 'MANAGER');


ALTER TABLE `request`
    ADD PRIMARY KEY (`id`),
    ADD KEY `user_id` (`user_id`),
    ADD KEY `deleted_by` (`deleted_by`),
    ADD KEY `processed_by` (`processed_by`);

ALTER TABLE `user`
    ADD PRIMARY KEY (`id`);


ALTER TABLE `request`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;
ALTER TABLE `user`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

ALTER TABLE `request`
    ADD CONSTRAINT `request_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
    ADD CONSTRAINT `request_ibfk_2` FOREIGN KEY (`deleted_by`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
    ADD CONSTRAINT `request_ibfk_3` FOREIGN KEY (`processed_by`) REFERENCES `user` (`id`) ON UPDATE CASCADE;
COMMIT;
