-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 10, 2023 at 10:19 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.1.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `smartolt`
--

-- --------------------------------------------------------

--
-- Table structure for table `capabilitys`
--

CREATE TABLE `capabilitys` (
  `idCapability` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `capabilitys`
--

INSERT INTO `capabilitys` (`idCapability`, `name`) VALUES
(2, 'Bridging/Routing'),
(3, 'Bridging');

-- --------------------------------------------------------

--
-- Table structure for table `odbs`
--

CREATE TABLE `odbs` (
  `idOdb` int(10) UNSIGNED NOT NULL,
  `zone_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `numPorts` int(11) DEFAULT NULL,
  `lat` varchar(255) DEFAULT NULL,
  `lng` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `odbs`
--

INSERT INTO `odbs` (`idOdb`, `zone_id`, `name`, `numPorts`, `lat`, `lng`, `created_at`) VALUES
(8, 1, 'Nuevo ODBS', 12, NULL, NULL, '2023-07-10 14:15:39'),
(9, 3, 'Nuevo ODBS 2', 2, NULL, NULL, '2023-07-10 14:18:46'),
(10, 8, 'ODBS 1', 21, NULL, NULL, '2023-07-10 15:11:18'),
(12, 12, 'Nuevo OBDS', 12, NULL, NULL, '2023-07-10 17:57:05');

-- --------------------------------------------------------

--
-- Table structure for table `olts`
--

CREATE TABLE `olts` (
  `idOlt` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `oltIp` varchar(255) DEFAULT NULL,
  `telnet_username` varchar(255) DEFAULT NULL,
  `telnet_password` varchar(255) DEFAULT NULL,
  `snmp_read_only` varchar(255) DEFAULT NULL,
  `snmp_read_write` varchar(255) DEFAULT NULL,
  `snmp_udp_port` int(11) DEFAULT NULL,
  `telnet_ssh_tcp_port` int(11) DEFAULT NULL,
  `ipvt` varchar(11) DEFAULT NULL,
  `oltHardwareVersion` varchar(255) DEFAULT NULL,
  `oltSwVersion` varchar(255) DEFAULT NULL,
  `support_pon_type` enum('gpon','epon','gpon_epon') DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `olts`
--

INSERT INTO `olts` (`idOlt`, `name`, `oltIp`, `telnet_username`, `telnet_password`, `snmp_read_only`, `snmp_read_write`, `snmp_udp_port`, `telnet_ssh_tcp_port`, `ipvt`, `oltHardwareVersion`, `oltSwVersion`, `support_pon_type`, `created_at`, `updated_at`) VALUES
(3, 'Nuevo OLT', '192.168.1.2', NULL, NULL, NULL, NULL, NULL, 12, '12', 'Harware Versions', 'Harware Versions', NULL, '2023-07-10 17:57:51', '2023-07-10 17:57:51');

-- --------------------------------------------------------

--
-- Table structure for table `onus`
--

CREATE TABLE `onus` (
  `idOnu` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `onus`
--

INSERT INTO `onus` (`idOnu`, `name`) VALUES
(3, 'ONU 1');

-- --------------------------------------------------------

--
-- Table structure for table `onu_types`
--

CREATE TABLE `onu_types` (
  `idOnuType` int(10) UNSIGNED NOT NULL,
  `capability_id` int(11) DEFAULT NULL,
  `ponType` enum('gpon','epon') DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `ethernetPorts` int(11) DEFAULT NULL,
  `wifi` int(11) DEFAULT NULL,
  `voipPorts` int(11) DEFAULT NULL,
  `catv` int(11) DEFAULT NULL,
  `customProfile` tinyint(1) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `ethernetPortsPrefix` varchar(255) DEFAULT NULL,
  `wifiPrefix` varchar(255) DEFAULT NULL,
  `voipPortsPrefix` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `onu_types`
--

INSERT INTO `onu_types` (`idOnuType`, `capability_id`, `ponType`, `name`, `ethernetPorts`, `wifi`, `voipPorts`, `catv`, `customProfile`, `image`, `ethernetPortsPrefix`, `wifiPrefix`, `voipPortsPrefix`, `created_at`, `updated_at`) VALUES
(5, 2, 'epon', 'Nuevo ONUTYPE editado', 12, 12, 12, 12, 1, NULL, NULL, NULL, NULL, '2023-07-10 17:40:52', '2023-07-10 17:40:52'),
(6, 3, 'gpon', 'Segundo ONUTYpe', 12, 12, 12, 12, 1, NULL, NULL, NULL, NULL, '2023-07-10 18:07:25', '2023-07-10 18:07:25');

-- --------------------------------------------------------

--
-- Table structure for table `profiles`
--

CREATE TABLE `profiles` (
  `profileId` int(10) UNSIGNED NOT NULL,
  `profileName` varchar(255) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `speed_profiles`
--

CREATE TABLE `speed_profiles` (
  `idSpeedProfile` int(10) UNSIGNED NOT NULL,
  `onu_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `type_conexion` varchar(255) DEFAULT NULL,
  `type_speed` enum('upload','download') DEFAULT NULL,
  `prefix` varchar(11) DEFAULT NULL,
  `speed` bigint(20) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `is_default` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `speed_profiles`
--

INSERT INTO `speed_profiles` (`idSpeedProfile`, `onu_id`, `name`, `type_conexion`, `type_speed`, `prefix`, `speed`, `type`, `is_default`) VALUES
(5, 1, 'Perfil de descarga', 'Internet', 'download', 'No', 2000, NULL, 0),
(8, 1, 'Perfil de Subida', 'Internet', 'upload', 'Si', 1500, NULL, 0),
(9, 1, '300 MB descarga', 'internet', 'download', 'Descarga', 300, NULL, 0),
(10, 3, '300 MB descarga', 'internet', 'download', NULL, 300, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `vpntunnels`
--

CREATE TABLE `vpntunnels` (
  `idVpnTunnel` int(10) UNSIGNED NOT NULL,
  `tunnelSubnet` varchar(255) DEFAULT NULL,
  `tunnelName` varchar(255) DEFAULT NULL,
  `tunnelPassword` varchar(255) DEFAULT NULL,
  `tunnelRoutes` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vpntunnels`
--

INSERT INTO `vpntunnels` (`idVpnTunnel`, `tunnelSubnet`, `tunnelName`, `tunnelPassword`, `tunnelRoutes`, `created_at`, `updated_at`) VALUES
(3, 'Ejemplo Sub red', 'Nuevo Tunnel', 'tunnerl password', 'tunnel routes', '2023-07-10 19:01:13', '2023-07-10 19:01:13');

-- --------------------------------------------------------

--
-- Table structure for table `zones`
--

CREATE TABLE `zones` (
  `idZone` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `zones`
--

INSERT INTO `zones` (`idZone`, `name`, `created_at`, `updated_at`) VALUES
(12, 'Zona 5', '2023-07-10 16:10:42', '2023-07-10 16:10:42'),
(15, 'Zona 1', '2023-07-10 20:17:23', '2023-07-10 20:17:23');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `capabilitys`
--
ALTER TABLE `capabilitys`
  ADD PRIMARY KEY (`idCapability`);

--
-- Indexes for table `odbs`
--
ALTER TABLE `odbs`
  ADD PRIMARY KEY (`idOdb`);

--
-- Indexes for table `olts`
--
ALTER TABLE `olts`
  ADD PRIMARY KEY (`idOlt`);

--
-- Indexes for table `onus`
--
ALTER TABLE `onus`
  ADD PRIMARY KEY (`idOnu`);

--
-- Indexes for table `onu_types`
--
ALTER TABLE `onu_types`
  ADD PRIMARY KEY (`idOnuType`);

--
-- Indexes for table `profiles`
--
ALTER TABLE `profiles`
  ADD PRIMARY KEY (`profileId`);

--
-- Indexes for table `speed_profiles`
--
ALTER TABLE `speed_profiles`
  ADD PRIMARY KEY (`idSpeedProfile`);

--
-- Indexes for table `vpntunnels`
--
ALTER TABLE `vpntunnels`
  ADD PRIMARY KEY (`idVpnTunnel`);

--
-- Indexes for table `zones`
--
ALTER TABLE `zones`
  ADD PRIMARY KEY (`idZone`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `capabilitys`
--
ALTER TABLE `capabilitys`
  MODIFY `idCapability` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `odbs`
--
ALTER TABLE `odbs`
  MODIFY `idOdb` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `olts`
--
ALTER TABLE `olts`
  MODIFY `idOlt` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `onus`
--
ALTER TABLE `onus`
  MODIFY `idOnu` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `onu_types`
--
ALTER TABLE `onu_types`
  MODIFY `idOnuType` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `profiles`
--
ALTER TABLE `profiles`
  MODIFY `profileId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `speed_profiles`
--
ALTER TABLE `speed_profiles`
  MODIFY `idSpeedProfile` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `vpntunnels`
--
ALTER TABLE `vpntunnels`
  MODIFY `idVpnTunnel` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `zones`
--
ALTER TABLE `zones`
  MODIFY `idZone` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
