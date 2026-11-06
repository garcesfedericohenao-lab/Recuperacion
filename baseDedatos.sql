-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 06-11-2026 a las 04:19:15
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `phpmyadmin`
--
CREATE DATABASE IF NOT EXISTS `phpmyadmin` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;
USE `phpmyadmin`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pma__bookmark`
--

CREATE TABLE `pma__bookmark` (
  `id` int(10) UNSIGNED NOT NULL,
  `dbase` varchar(255) NOT NULL DEFAULT '',
  `user` varchar(255) NOT NULL DEFAULT '',
  `label` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `query` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Bookmarks';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pma__central_columns`
--

CREATE TABLE `pma__central_columns` (
  `db_name` varchar(64) NOT NULL,
  `col_name` varchar(64) NOT NULL,
  `col_type` varchar(64) NOT NULL,
  `col_length` text DEFAULT NULL,
  `col_collation` varchar(64) NOT NULL,
  `col_isNull` tinyint(1) NOT NULL,
  `col_extra` varchar(255) DEFAULT '',
  `col_default` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Central list of columns';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pma__column_info`
--

CREATE TABLE `pma__column_info` (
  `id` int(5) UNSIGNED NOT NULL,
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `table_name` varchar(64) NOT NULL DEFAULT '',
  `column_name` varchar(64) NOT NULL DEFAULT '',
  `comment` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `mimetype` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `transformation` varchar(255) NOT NULL DEFAULT '',
  `transformation_options` varchar(255) NOT NULL DEFAULT '',
  `input_transformation` varchar(255) NOT NULL DEFAULT '',
  `input_transformation_options` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Column information for phpMyAdmin';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pma__designer_settings`
--

CREATE TABLE `pma__designer_settings` (
  `username` varchar(64) NOT NULL,
  `settings_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Settings related to Designer';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pma__export_templates`
--

CREATE TABLE `pma__export_templates` (
  `id` int(5) UNSIGNED NOT NULL,
  `username` varchar(64) NOT NULL,
  `export_type` varchar(10) NOT NULL,
  `template_name` varchar(64) NOT NULL,
  `template_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Saved export templates';

--
-- Volcado de datos para la tabla `pma__export_templates`
--

INSERT INTO `pma__export_templates` (`id`, `username`, `export_type`, `template_name`, `template_data`) VALUES
(1, 'root', 'database', 'FedericoBS', '{\"quick_or_custom\":\"quick\",\"what\":\"sql\",\"structure_or_data_forced\":\"0\",\"table_select[]\":[\"materias\",\"recursos\",\"usuarios\"],\"table_structure[]\":[\"materias\",\"recursos\",\"usuarios\"],\"table_data[]\":[\"materias\",\"recursos\",\"usuarios\"],\"aliases_new\":\"\",\"output_format\":\"sendit\",\"filename_template\":\"@DATABASE@\",\"remember_template\":\"on\",\"charset\":\"utf-8\",\"compression\":\"none\",\"maxsize\":\"\",\"codegen_structure_or_data\":\"data\",\"codegen_format\":\"0\",\"csv_separator\":\",\",\"csv_enclosed\":\"\\\"\",\"csv_escaped\":\"\\\"\",\"csv_terminated\":\"AUTO\",\"csv_null\":\"NULL\",\"csv_columns\":\"something\",\"csv_structure_or_data\":\"data\",\"excel_null\":\"NULL\",\"excel_columns\":\"something\",\"excel_edition\":\"win\",\"excel_structure_or_data\":\"data\",\"json_structure_or_data\":\"data\",\"json_unicode\":\"something\",\"latex_caption\":\"something\",\"latex_structure_or_data\":\"structure_and_data\",\"latex_structure_caption\":\"Estructura de la tabla @TABLE@\",\"latex_structure_continued_caption\":\"Estructura de la tabla @TABLE@ (continúa)\",\"latex_structure_label\":\"tab:@TABLE@-structure\",\"latex_relation\":\"something\",\"latex_comments\":\"something\",\"latex_mime\":\"something\",\"latex_columns\":\"something\",\"latex_data_caption\":\"Contenido de la tabla @TABLE@\",\"latex_data_continued_caption\":\"Contenido de la tabla @TABLE@ (continúa)\",\"latex_data_label\":\"tab:@TABLE@-data\",\"latex_null\":\"\\\\textit{NULL}\",\"mediawiki_structure_or_data\":\"structure_and_data\",\"mediawiki_caption\":\"something\",\"mediawiki_headers\":\"something\",\"htmlword_structure_or_data\":\"structure_and_data\",\"htmlword_null\":\"NULL\",\"ods_null\":\"NULL\",\"ods_structure_or_data\":\"data\",\"odt_structure_or_data\":\"structure_and_data\",\"odt_relation\":\"something\",\"odt_comments\":\"something\",\"odt_mime\":\"something\",\"odt_columns\":\"something\",\"odt_null\":\"NULL\",\"pdf_report_title\":\"\",\"pdf_structure_or_data\":\"structure_and_data\",\"phparray_structure_or_data\":\"data\",\"sql_include_comments\":\"something\",\"sql_header_comment\":\"\",\"sql_use_transaction\":\"something\",\"sql_compatibility\":\"NONE\",\"sql_structure_or_data\":\"structure_and_data\",\"sql_create_table\":\"something\",\"sql_auto_increment\":\"something\",\"sql_create_view\":\"something\",\"sql_procedure_function\":\"something\",\"sql_create_trigger\":\"something\",\"sql_backquotes\":\"something\",\"sql_type\":\"INSERT\",\"sql_insert_syntax\":\"both\",\"sql_max_query_size\":\"50000\",\"sql_hex_for_binary\":\"something\",\"sql_utc_time\":\"something\",\"texytext_structure_or_data\":\"structure_and_data\",\"texytext_null\":\"NULL\",\"xml_structure_or_data\":\"data\",\"xml_export_events\":\"something\",\"xml_export_functions\":\"something\",\"xml_export_procedures\":\"something\",\"xml_export_tables\":\"something\",\"xml_export_triggers\":\"something\",\"xml_export_views\":\"something\",\"xml_export_contents\":\"something\",\"yaml_structure_or_data\":\"data\",\"\":null,\"lock_tables\":null,\"as_separate_files\":null,\"csv_removeCRLF\":null,\"excel_removeCRLF\":null,\"json_pretty_print\":null,\"htmlword_columns\":null,\"ods_columns\":null,\"sql_dates\":null,\"sql_relation\":null,\"sql_mime\":null,\"sql_disable_fk\":null,\"sql_views_as_tables\":null,\"sql_metadata\":null,\"sql_create_database\":null,\"sql_drop_table\":null,\"sql_if_not_exists\":null,\"sql_simple_view_export\":null,\"sql_view_current_user\":null,\"sql_or_replace_view\":null,\"sql_truncate\":null,\"sql_delayed\":null,\"sql_ignore\":null,\"texytext_columns\":null}');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pma__favorite`
--

CREATE TABLE `pma__favorite` (
  `username` varchar(64) NOT NULL,
  `tables` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Favorite tables';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pma__history`
--

CREATE TABLE `pma__history` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(64) NOT NULL DEFAULT '',
  `db` varchar(64) NOT NULL DEFAULT '',
  `table` varchar(64) NOT NULL DEFAULT '',
  `timevalue` timestamp NOT NULL DEFAULT current_timestamp(),
  `sqlquery` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='SQL history for phpMyAdmin';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pma__navigationhiding`
--

CREATE TABLE `pma__navigationhiding` (
  `username` varchar(64) NOT NULL,
  `item_name` varchar(64) NOT NULL,
  `item_type` varchar(64) NOT NULL,
  `db_name` varchar(64) NOT NULL,
  `table_name` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Hidden items of navigation tree';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pma__pdf_pages`
--

CREATE TABLE `pma__pdf_pages` (
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `page_nr` int(10) UNSIGNED NOT NULL,
  `page_descr` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='PDF relation pages for phpMyAdmin';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pma__recent`
--

CREATE TABLE `pma__recent` (
  `username` varchar(64) NOT NULL,
  `tables` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Recently accessed tables';

--
-- Volcado de datos para la tabla `pma__recent`
--

INSERT INTO `pma__recent` (`username`, `tables`) VALUES
('root', '[{\"db\":\"repasaya\",\"table\":\"usuarios\"},{\"db\":\"repasaya\",\"table\":\"materias\"}]');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pma__relation`
--

CREATE TABLE `pma__relation` (
  `master_db` varchar(64) NOT NULL DEFAULT '',
  `master_table` varchar(64) NOT NULL DEFAULT '',
  `master_field` varchar(64) NOT NULL DEFAULT '',
  `foreign_db` varchar(64) NOT NULL DEFAULT '',
  `foreign_table` varchar(64) NOT NULL DEFAULT '',
  `foreign_field` varchar(64) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Relation table';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pma__savedsearches`
--

CREATE TABLE `pma__savedsearches` (
  `id` int(5) UNSIGNED NOT NULL,
  `username` varchar(64) NOT NULL DEFAULT '',
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `search_name` varchar(64) NOT NULL DEFAULT '',
  `search_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Saved searches';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pma__table_coords`
--

CREATE TABLE `pma__table_coords` (
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `table_name` varchar(64) NOT NULL DEFAULT '',
  `pdf_page_number` int(11) NOT NULL DEFAULT 0,
  `x` float UNSIGNED NOT NULL DEFAULT 0,
  `y` float UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Table coordinates for phpMyAdmin PDF output';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pma__table_info`
--

CREATE TABLE `pma__table_info` (
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `table_name` varchar(64) NOT NULL DEFAULT '',
  `display_field` varchar(64) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Table information for phpMyAdmin';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pma__table_uiprefs`
--

CREATE TABLE `pma__table_uiprefs` (
  `username` varchar(64) NOT NULL,
  `db_name` varchar(64) NOT NULL,
  `table_name` varchar(64) NOT NULL,
  `prefs` text NOT NULL,
  `last_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Tables'' UI preferences';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pma__tracking`
--

CREATE TABLE `pma__tracking` (
  `db_name` varchar(64) NOT NULL,
  `table_name` varchar(64) NOT NULL,
  `version` int(10) UNSIGNED NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  `schema_snapshot` text NOT NULL,
  `schema_sql` text DEFAULT NULL,
  `data_sql` longtext DEFAULT NULL,
  `tracking` set('UPDATE','REPLACE','INSERT','DELETE','TRUNCATE','CREATE DATABASE','ALTER DATABASE','DROP DATABASE','CREATE TABLE','ALTER TABLE','RENAME TABLE','DROP TABLE','CREATE INDEX','DROP INDEX','CREATE VIEW','ALTER VIEW','DROP VIEW') DEFAULT NULL,
  `tracking_active` int(1) UNSIGNED NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Database changes tracking for phpMyAdmin';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pma__userconfig`
--

CREATE TABLE `pma__userconfig` (
  `username` varchar(64) NOT NULL,
  `timevalue` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `config_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='User preferences storage for phpMyAdmin';

--
-- Volcado de datos para la tabla `pma__userconfig`
--

INSERT INTO `pma__userconfig` (`username`, `timevalue`, `config_data`) VALUES
('root', '2026-09-24 21:42:55', '{\"Console\\/Mode\":\"collapse\",\"lang\":\"es\"}');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pma__usergroups`
--

CREATE TABLE `pma__usergroups` (
  `usergroup` varchar(64) NOT NULL,
  `tab` varchar(64) NOT NULL,
  `allowed` enum('Y','N') NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='User groups with configured menu items';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pma__users`
--

CREATE TABLE `pma__users` (
  `username` varchar(64) NOT NULL,
  `usergroup` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Users and their assignments to user groups';

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `pma__bookmark`
--
ALTER TABLE `pma__bookmark`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pma__central_columns`
--
ALTER TABLE `pma__central_columns`
  ADD PRIMARY KEY (`db_name`,`col_name`);

--
-- Indices de la tabla `pma__column_info`
--
ALTER TABLE `pma__column_info`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `db_name` (`db_name`,`table_name`,`column_name`);

--
-- Indices de la tabla `pma__designer_settings`
--
ALTER TABLE `pma__designer_settings`
  ADD PRIMARY KEY (`username`);

--
-- Indices de la tabla `pma__export_templates`
--
ALTER TABLE `pma__export_templates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `u_user_type_template` (`username`,`export_type`,`template_name`);

--
-- Indices de la tabla `pma__favorite`
--
ALTER TABLE `pma__favorite`
  ADD PRIMARY KEY (`username`);

--
-- Indices de la tabla `pma__history`
--
ALTER TABLE `pma__history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `username` (`username`,`db`,`table`,`timevalue`);

--
-- Indices de la tabla `pma__navigationhiding`
--
ALTER TABLE `pma__navigationhiding`
  ADD PRIMARY KEY (`username`,`item_name`,`item_type`,`db_name`,`table_name`);

--
-- Indices de la tabla `pma__pdf_pages`
--
ALTER TABLE `pma__pdf_pages`
  ADD PRIMARY KEY (`page_nr`),
  ADD KEY `db_name` (`db_name`);

--
-- Indices de la tabla `pma__recent`
--
ALTER TABLE `pma__recent`
  ADD PRIMARY KEY (`username`);

--
-- Indices de la tabla `pma__relation`
--
ALTER TABLE `pma__relation`
  ADD PRIMARY KEY (`master_db`,`master_table`,`master_field`),
  ADD KEY `foreign_field` (`foreign_db`,`foreign_table`);

--
-- Indices de la tabla `pma__savedsearches`
--
ALTER TABLE `pma__savedsearches`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `u_savedsearches_username_dbname` (`username`,`db_name`,`search_name`);

--
-- Indices de la tabla `pma__table_coords`
--
ALTER TABLE `pma__table_coords`
  ADD PRIMARY KEY (`db_name`,`table_name`,`pdf_page_number`);

--
-- Indices de la tabla `pma__table_info`
--
ALTER TABLE `pma__table_info`
  ADD PRIMARY KEY (`db_name`,`table_name`);

--
-- Indices de la tabla `pma__table_uiprefs`
--
ALTER TABLE `pma__table_uiprefs`
  ADD PRIMARY KEY (`username`,`db_name`,`table_name`);

--
-- Indices de la tabla `pma__tracking`
--
ALTER TABLE `pma__tracking`
  ADD PRIMARY KEY (`db_name`,`table_name`,`version`);

--
-- Indices de la tabla `pma__userconfig`
--
ALTER TABLE `pma__userconfig`
  ADD PRIMARY KEY (`username`);

--
-- Indices de la tabla `pma__usergroups`
--
ALTER TABLE `pma__usergroups`
  ADD PRIMARY KEY (`usergroup`,`tab`,`allowed`);

--
-- Indices de la tabla `pma__users`
--
ALTER TABLE `pma__users`
  ADD PRIMARY KEY (`username`,`usergroup`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `pma__bookmark`
--
ALTER TABLE `pma__bookmark`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pma__column_info`
--
ALTER TABLE `pma__column_info`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pma__export_templates`
--
ALTER TABLE `pma__export_templates`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `pma__history`
--
ALTER TABLE `pma__history`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pma__pdf_pages`
--
ALTER TABLE `pma__pdf_pages`
  MODIFY `page_nr` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pma__savedsearches`
--
ALTER TABLE `pma__savedsearches`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- Base de datos: `repasaya`
--
CREATE DATABASE IF NOT EXISTS `repasaya` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `repasaya`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `intentos_taller`
--

CREATE TABLE `intentos_taller` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `taller_id` int(11) NOT NULL,
  `correctas` int(11) NOT NULL,
  `incorrectas` int(11) NOT NULL,
  `total_preguntas` int(11) NOT NULL,
  `puntaje` int(11) NOT NULL,
  `detalles_json` longtext DEFAULT NULL,
  `completado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `intentos_taller`
--

INSERT INTO `intentos_taller` (`id`, `usuario_id`, `taller_id`, `correctas`, `incorrectas`, `total_preguntas`, `puntaje`, `detalles_json`, `completado_en`) VALUES
(1, 1, 1, 5, 0, 5, 100, '{\"tiempo_segundos\":145,\"total_preguntas\":5,\"correctas\":5,\"incorrectas\":0,\"puntaje\":100,\"nivel\":\"Nivel 4 (Avanzado)\"}', '2026-10-22 03:24:06'),
(2, 6, 1, 0, 5, 5, 0, '{\"tiempo_segundos\":9,\"total_preguntas\":5,\"correctas\":0,\"incorrectas\":5,\"puntaje\":0,\"nivel\":\"Nivel 1 (Insuficiente)\"}', '2026-10-23 13:37:09'),
(3, 6, 1, 1, 4, 5, 20, '{\"tiempo_segundos\":42,\"total_preguntas\":5,\"correctas\":1,\"incorrectas\":4,\"puntaje\":20,\"nivel\":\"Nivel 1 (Insuficiente)\"}', '2026-10-23 13:38:09'),
(4, 6, 3, 2, 3, 5, 40, '{\"tiempo_segundos\":9,\"total_preguntas\":5,\"correctas\":2,\"incorrectas\":3,\"puntaje\":40,\"nivel\":\"Nivel 1 (Insuficiente)\"}', '2026-10-23 13:39:15'),
(5, 6, 2, 2, 3, 5, 40, '{\"tiempo_segundos\":15,\"total_preguntas\":5,\"correctas\":2,\"incorrectas\":3,\"puntaje\":40,\"nivel\":\"Nivel 1 (Insuficiente)\"}', '2026-10-23 13:58:39'),
(6, 6, 6, 3, 2, 5, 60, '{\"tiempo_segundos\":172,\"total_preguntas\":5,\"correctas\":3,\"incorrectas\":2,\"puntaje\":60,\"nivel\":\"Nivel 2 (Mínimo)\"}', '2026-10-26 18:41:17'),
(7, 6, 5, 0, 5, 5, 0, '{\"tiempo_segundos\":13,\"total_preguntas\":5,\"correctas\":0,\"incorrectas\":5,\"puntaje\":0,\"nivel\":\"Nivel 1 (Insuficiente)\"}', '2026-10-27 02:40:37');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `materias`
--

CREATE TABLE `materias` (
  `id` int(11) NOT NULL,
  `nombre` varchar(80) NOT NULL,
  `slug` varchar(80) NOT NULL,
  `imagen` varchar(150) DEFAULT NULL,
  `orden` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `materias`
--

INSERT INTO `materias` (`id`, `nombre`, `slug`, `imagen`, `orden`) VALUES
(1, 'Matemáticas', 'matematicas', 'matematicas.jpg', 1),
(2, 'Física', 'fisica', 'fisica.jpg', 2),
(3, 'Química', 'quimica', 'quimica.jpg', 3),
(4, 'Biología', 'biologia', 'biologia.jpg', 4),
(5, 'Español y Literatura', 'espanol', 'espanol.jpg', 5),
(6, 'Ciencias Sociales', 'sociales', 'sociales.jpg', 6),
(7, 'Filosofía', 'filosofia', 'sociales.jpg', 7),
(8, 'Inglés', 'ingles', 'espanol.jpg', 8),
(9, 'Tecnología e Informática', 'tecnologia', 'fisica.jpg', 9),
(10, 'Ética y Valores', 'etica', 'sociales.jpg', 10),
(11, 'Educación Física', 'educacion-fisica', 'biologia.jpg', 11),
(12, 'Educación Religiosa', 'religion', 'sociales.jpg', 12);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `preguntas`
--

CREATE TABLE `preguntas` (
  `id` int(11) NOT NULL,
  `taller_id` int(11) NOT NULL,
  `contexto` text DEFAULT NULL,
  `enunciado` text NOT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `opcion_a` text NOT NULL,
  `opcion_b` text NOT NULL,
  `opcion_c` text NOT NULL,
  `opcion_d` text NOT NULL,
  `respuesta_correcta` enum('A','B','C','D') NOT NULL,
  `explicacion` text DEFAULT NULL,
  `orden` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `preguntas`
--

INSERT INTO `preguntas` (`id`, `taller_id`, `contexto`, `enunciado`, `imagen`, `opcion_a`, `opcion_b`, `opcion_c`, `opcion_d`, `respuesta_correcta`, `explicacion`, `orden`) VALUES
(1, 1, 'En una empresa de envíos, el costo $C(x)$ en pesos para transportar un paquete de $x$ kilogramos está modelado por la función lineal $C(x) = 5.000 + 2.500x$, donde $5.000$ es una tarifa fija base y $2.500$ es el valor por cada kilogramo.', 'Si un cliente pagó un total de $30.000 pesos por el envío de un paquete, ¿cuál fue el peso exacto del paquete en kilogramos?', NULL, '8 kg', '10 kg', '12 kg', '14 kg', 'B', 'Planteamos la ecuación $30.000 = 5.000 + 2.500x$. Restando la tarifa fija: $25.000 = 2.500x$. Dividiendo entre 2.500: $x = 10$. Por tanto, el peso es de 10 kg.', 1),
(2, 1, 'Un agricultor dispone de 120 metros de cerca para encerrar un terreno rectangular. Desea maximizar el área del terreno.', 'Si el perímetro de un rectángulo es $2x + 2y = 120$, ¿cuáles deben ser las dimensiones para que el área sea máxima y cuál es dicha área?', NULL, 'Largo 40 m y ancho 20 m; Área = 800 m²', 'Largo 30 m y ancho 30 m (cuadrado); Área = 900 m²', 'Largo 50 m y ancho 10 m; Área = 500 m²', 'Largo 35 m y ancho 25 m; Área = 875 m²', 'B', 'Para un perímetro fijo, el rectángulo de área máxima es siempre un cuadrado. Con 120 m de perímetro, cada lado mide $120 / 4 = 30$ m. El área máxima es $30 \\times 30 = 900$ m².', 2),
(3, 1, 'En una bolsa hay 5 bolas rojas, 3 bolas azules y 2 bolas verdes, todas de igual tamaño y peso.', 'Al extraer una bola al azar, ¿cuál es la probabilidad de que la bola NO sea de color rojo?', NULL, '1/2 (50%)', '3/10 (30%)', '1/5 (20%)', '7/10 (70%)', 'A', 'El total de bolas es $5 + 3 + 2 = 10$. Las bolas que NO son rojas son azules o verdes: $3 + 2 = 5$. La probabilidad es $5 / 10 = 1/2 = 50\\%$.', 3),
(4, 1, 'Una tienda ofrece un descuento del 20% en todos sus artículos. Además, por pagar con tarjeta débito del banco aliado, se aplica un 10% de descuento adicional sobre el valor ya rebajado.', 'Un par de zapatos tiene un precio original de $100.000 pesos. ¿Cuánto pagará finalmente un comprador que use la tarjeta aliada?', NULL, '$70.000', '$72.000', '$75.000', '$80.000', 'B', 'Con el primer 20% de descuento: $100.000 - 20.000 = $80.000$. El 10% adicional sobre $80.000 es $8.000$. Precio final: $80.000 - 8.000 = $72.000$. Recuerda que los descuentos sucesivos no se suman linealmente.', 4),
(5, 1, 'En un triángulo rectángulo, la hipotenusa mide 15 cm y uno de sus catetos mide 9 cm.', 'Aplicando el Teorema de Pitágoras, ¿cuál es la longitud del otro cateto?', NULL, '10 cm', '11 cm', '12 cm', '13 cm', 'C', '$c^2 = a^2 + b^2 \\Rightarrow 15^2 = 9^2 + b^2 \\Rightarrow 225 = 81 + b^2 \\Rightarrow b^2 = 144 \\Rightarrow b = 12$ cm.', 5),
(6, 2, 'Un automóvil de masa $m = 1.000$ kg se desplaza por una carretera recta horizontal a velocidad constante de 20 m/s.', 'De acuerdo con la Primera Ley de Newton (Ley de la Inercia), ¿cuál es la fuerza neta resultante que actúa sobre el vehículo mientras mantiene dicha velocidad constante?', NULL, '20.000 N hacia adelante', '1.000 N en dirección del movimiento', '0 N', '9.800 N hacia abajo', 'C', 'Si la velocidad es constante, la aceleración es nula ($a = 0$). Por la Segunda Ley de Newton, $F_{neta} = m \\cdot a = 1.000 \\cdot 0 = 0$ N. La fuerza del motor equilibra exactamente la fricción y resistencia del aire.', 1),
(7, 2, 'Se deja caer libremente un objeto desde la azotea de un edificio de 20 metros de altura. Se desprecia la fricción con el aire y se asume la aceleración de la gravedad $g = 10 \\text{ m/s}^2$.', '¿Con qué velocidad impacta el objeto contra el suelo?', NULL, '10 m/s', '20 m/s', '30 m/s', '40 m/s', 'B', 'Por conservación de energía: $E_p = E_k \\Rightarrow m g h = \\frac{1}{2} m v^2 \\Rightarrow v = \\sqrt{2gh} = \\sqrt{2 \\times 10 \\times 20} = \\sqrt{400} = 20$ m/s.', 2),
(8, 2, 'Dos patinadores, uno de 80 kg y otro de 40 kg, se encuentran en reposo sobre una pista de hielo sin fricción. En un instante, el patinador de 80 kg empuja al de 40 kg.', 'Según la Tercera Ley de Newton (Acción y Reacción), ¿cómo son las fuerzas que experimentan ambos patinadores?', NULL, 'El patinador de 80 kg ejerce mayor fuerza porque tiene más masa.', 'El patinador de 40 kg experimenta una fuerza el doble de grande.', 'Ambos experimentan fuerzas de igual magnitud pero en sentidos opuestos.', 'Solo el patinador de 40 kg experimenta una fuerza, el de 80 kg no.', 'C', 'La Tercera Ley de Newton establece que para toda acción existe una reacción de igual magnitud, misma dirección y sentido opuesto, independientemente de las masas de los cuerpos que interactúan.', 3),
(9, 2, 'Una montaña rusa traslada un carro sin fricción desde el punto más alto (punto 1) hasta el punto más bajo (punto 2).', '¿Qué transformación energética fundamental ocurre durante el descenso?', NULL, 'La energía cinética se transforma en energía potencial gravitacional.', 'La energía potencial gravitacional se transforma en energía cinética.', 'La energía térmica se transforma en energía potencial.', 'La energía mecánica total disminuye a la mitad.', 'B', 'Al descender se pierde altura (disminuye la energía potencial $mgh$) y se gana velocidad (aumenta la energía cinética $\\frac{1}{2}mv^2$), manteniéndose constante la energía mecánica total.', 4),
(10, 2, 'Un circuito eléctrico básico está compuesto por una fuente de voltaje de 12 V y una resistencia de 4 ohmios ($\\Omega$).', 'Aplicando la Ley de Ohm ($V = I \\cdot R$), ¿cuál es la corriente eléctrica que circula por el circuito?', NULL, '48 A', '3 A', '8 A', '0.33 A', 'B', 'Despejando la intensidad de corriente: $I = V / R = 12 \\text{ V} / 4\\ \\Omega = 3 \\text{ A}$.', 5),
(11, 3, 'El sodio ($Na$) es un metal alcalino con número atómico $Z=11$ (1 electrón de valencia) y el cloro ($Cl$) es un halógeno no metal con $Z=17$ (7 electrones de valencia).', 'Al reaccionar para formar cloruro de sodio ($NaCl$), ¿qué tipo de enlace químico se produce y cómo se logra la estabilidad del octeto?', NULL, 'Enlace covalente apolar, compartiendo electrones equitativamente.', 'Enlace iónico, donde el sodio cede un electrón al cloro formándose iones $Na^+$ y $Cl^-$.', 'Enlace metálico, compartiendo una nube libre de electrones.', 'Enlace covalente coordinado, donde el cloro aporta ambos electrones.', 'B', 'Debido a la gran diferencia de electronegatividad entre un metal alcalino y un no metal halógeno, el metal cede su electrón de valencia formando un enlace iónico mediado por atracción electrostática.', 1),
(12, 3, 'Considera la reacción de combustión incompleta del propano: $\\text{C}_3\\text{H}_8 + \\text{O}_2 \\rightarrow \\text{CO}_2 + \\text{H}_2\\text{O}$.', 'Al balancear correctamente la ecuación por el método del tanteo, ¿cuál es el coeficiente estequiométrico que acompaña al oxígeno molecular ($\\text{O}_2$)?', NULL, '3', '4', '5', '10', 'C', 'Ecuación balanceada: $\\text{C}_3\\text{H}_8 + 5\\text{O}_2 \\rightarrow 3\\text{CO}_2 + 4\\text{H}_2\\text{O}$. A la izquierda tenemos 3 carbonos, 8 hidrógenos y $5 \\times 2 = 10$ oxígenos. A la derecha tenemos $3 \\times 2 + 4 \\times 1 = 10$ oxígenos.', 2),
(13, 3, 'Una solución acuosa tiene una concentración de iones hidrógeno $[H^+] = 1 \\times 10^{-4}$ M.', 'Recordando que $\\text{pH} = -\\log[H^+]$, ¿cuál es el pH de la solución y cuál es su carácter químico?', NULL, 'pH = 4, solución ácida', 'pH = 10, solución básica', 'pH = 7, solución neutra', 'pH = 14, solución neutra', 'A', '$\\text{pH} = -\\log(10^{-4}) = 4$. Dado que $4 < 7$, la disolución es de carácter ácido.', 3),
(14, 3, 'A temperatura constante, el volumen de una masa gaseosa determinada es inversamente proporcional a la presión que soporta.', '¿A qué ley fundamental de los gases ideales corresponde este principio?', NULL, 'Ley de Charles', 'Ley de Gay-Lussac', 'Ley de Boyle-Mariotte', 'Ley de Dalton', 'C', 'La Ley de Boyle establece que para un gas a temperatura constante ($T = \\text{cte}$), el producto $P \\cdot V$ es constante, es decir, el volumen es inversamente proporcional a la presión.', 4),
(15, 3, 'El agua ($H_2O$) tiene un punto de ebullición anormalmente alto (100 °C) en comparación con moléculas de masa similar como el sulfuro de hidrógeno ($H_2S$, -60 °C).', '¿Cuál fuerza intermolecular explica este fenómeno en el agua?', NULL, 'Fuerzas de dispersión de London débiles', 'Puentes de hidrógeno', 'Atracción ión-dipolo', 'Fuerzas gravitacionales moleculares', 'B', 'El átomo de oxígeno altamente electronegativo unido al hidrógeno genera intensos puentes de hidrógeno entre las moléculas de agua, requiriendo mucha mayor energía térmica para pasar al estado gaseoso.', 5),
(16, 4, 'En las plantas de guisantes, el color amarillo de las semillas ($A$) es dominante sobre el color verde ($a$). Se cruzan dos plantas heterocigotas ($Aa \\times Aa$).', 'Según la Primera y Segunda Ley de Mendel, ¿cuál es la proporción fenotípica esperada en la descendencia?', NULL, '100% semillas amarillas', '50% amarillas y 50% verdes', '75% semillas amarillas (3/4) y 25% verdes (1/4)', '100% semillas verdes', 'C', 'El cuadro de Punnett da genotipos: 1 AA (amarillo), 2 Aa (amarillo) y 1 aa (verde). Fenotipo: 3 semillas amarillas por cada 1 verde (proporción clásica 3:1 o 75% a 25%).', 1),
(17, 4, 'En una cadena trófica de un bosque andino, los productores primarios captan energía solar. Posteriormente, los herbívoros consumen plantas, y los carnívoros consumen herbívoros.', 'De acuerdo con la Ley del Diezmo Ecológico (10%), ¿qué ocurre con la energía utilizable a medida que se asciende de un nivel trófico al siguiente?', NULL, 'Aumenta un 10% en cada eslabón.', 'Se transfiere solo aproximadamente el 10% de la energía, perdiéndose el 90% en forma de calor y procesos metabólicos.', 'Permanece 100% constante en todos los niveles.', 'Se multiplica debido a la fotosíntesis de los carnívoros.', 'B', 'En ecología, la regla del 10% indica que sólo cerca del 10% de la energía de un nivel trófico es transferida e incorporada al siguiente nivel trófico; el resto se disipa como calor.', 2),
(18, 4, 'La mitocondria es un organelo presente en las células eucariotas y es fundamental para la supervivencia del organismo.', '¿Cuál es el proceso metabólico principal que se lleva a cabo en la mitocondria para producir ATP?', NULL, 'Fotosíntesis oxigénica', 'Respiración celular aeróbica', 'Fermentación láctica anaerobia', 'Glucogénesis hepática', 'B', 'La respiración celular aeróbica (ciclo de Krebs y fosforilación oxidativa) tiene lugar en la mitocondria, produciendo la mayor cantidad de ATP que utiliza la célula eucariota.', 3),
(19, 4, 'El proceso de división celular que permite la formación de gametos (óvulos y espermatozoides) con la mitad del número cromosómico ($n$) se denomina meiosis.', '¿Qué mecanismo clave ocurre durante la Profase I de la meiosis que genera variabilidad genética en las especies?', NULL, 'Duplicación del número de centrómeros', 'Entrecruzamiento o crossing-over entre cromosomas homólogos', 'Citocinesis asimétrica celular', 'Clonación del ADN mitocondrial', 'B', 'Durante la profase I, los cromosomas homólogos se aparean e intercambian segmentos de ADN mediante el entrecruzamiento (crossing-over), lo que genera nuevas combinaciones alélicas y garantiza la variabilidad genética.', 4),
(20, 4, 'En una población de polillas que habitan en un bosque, las polillas oscuras eran escasas frente a las polillas claras. Tras la industrialización, los troncos se cubrieron de hollín oscuro y, con los años, las polillas oscuras pasaron a ser la inmensa mayoría.', '¿Qué teoría biológica explica adecuadamente este cambio en la frecuencia fenotípica de la población?', NULL, 'Teoría de la Selección Natural de Charles Darwin', 'Generación espontánea de Francesco Redi', 'Herencia de los caracteres adquiridos de Lamarck', 'Fijismo creacionista', 'A', 'Es el clásico caso de melanismo industrial: las polillas oscuras tenían mayor camuflaje frente a los depredadores en los troncos tiznados, lo que les confirió ventaja reproductiva y adaptativa (Selección Natural).', 5),
(21, 5, '«Nadie es libre si no es dueño de sí mismo. La libertad no consiste simplemente en hacer lo que uno desea en un momento determinado, sino en la capacidad racional de autodeterminarse y gobernar las propias pasiones. Quien cede ante cada impulso ciego no es un hombre libre, sino el siervo de sus propios deseos efímeros.» (Epicteto, fragmento adaptado).', 'De acuerdo con el texto anterior, ¿cuál es la tesis central que defiende el autor?', NULL, 'La libertad plena consiste en satisfacer todos los placeres sin restricción alguna.', 'La verdadera libertad radica en el autocontrol racional y no en el mero seguimiento de los impulsos.', 'El ser humano nunca puede ser libre porque la sociedad se lo impide.', 'Las pasiones y deseos deben reprimirse mediante la violencia.', 'B', 'El autor contrapone hacer lo que uno desea por impulso versus el autogobierno racional, concluyendo que la auténtica libertad es ser dueño de sí mismo mediante la autodeterminación.', 1),
(22, 5, 'En el enunciado: «Aunque el candidato presentó un discurso elocuente y emotivo, sus propuestas carecían de viabilidad financiera y respaldo técnico; por consiguiente, el comité evaluador rechazó el proyecto.»', '¿Cuál es la función textual del conector «por consiguiente» en la oración?', NULL, 'Introducir una contradicción u objeción a lo dicho previamente.', 'Indicar una consecuencia o deducción lógica derivada de las premisas anteriores.', 'Agregar una idea complementaria sin relación causal.', 'Establecer una comparación temporal simultánea.', 'B', '«Por consiguiente» es un conector consecutivo o ilativo; señala que el rechazo del proyecto es la consecuencia directa de la falta de viabilidad financiera y respaldo técnico.', 2),
(23, 5, '«En una famosa fábula, un cuervo que llevaba un pedazo de queso en su pico se posó en un árbol. Una zorra astuta, deseando el queso, comenzó a alabar la belleza de las plumas del cuervo y le pidió que cantara para admirar su voz. El cuervo, vanidoso, abrió el pico para cantar y dejó caer el queso, que fue devorado al instante por la zorra.»', '¿Cuál es la moraleja o sentido alegórico fundamental de la fábula?', NULL, 'Los animales salvajes siempre son enemigos en la naturaleza.', 'Se debe desconfiar de quienes recurren a la adulación interesada.', 'El canto de las aves no debe interrumpirse jamás.', 'Es preferible comer rápido antes de posarse en los árboles.', 'B', 'La fábula enseña que los aduladores suelen fingir admiración con el único objetivo de obtener un provecho egoísta a expensas de la vanidad ajena.', 3),
(24, 5, '«El cambio climático ya no es una amenaza lejana: según el último informe del IPCC, las olas de calor récord, el deshielo acelerado de los glaciares y las sequías extremas son evidencia incontestable de la huella antropogénica en el sistema climático global.»', '¿Qué tipo de argumento utiliza primordialmente el autor para respaldar su afirmación?', NULL, 'Argumento de autoridad y evidencia empírica sustentado en una institución científica reconocida.', 'Argumento por analogía estética.', 'Falacia ad hominem contra los científicos.', 'Generalización apresurada sin datos.', 'A', 'El autor cita al IPCC (Panel Intergubernamental de Expertos sobre el Cambio Climático) y datos empíricos de fenómenos meteorológicos extremos, configurando un argumento de autoridad y evidencia científica.', 4),
(25, 5, 'Una caricatura política muestra a un ciudadano común cargando sobre su espalda una enorme roca etiquetada con «Impuestos», mientras varios funcionarios en trajes elegantes disfrutan de comodidades sobre la misma roca diciendo: «¡Un esfuerzo más por la patria!».', '¿Cuál es el recurso retórico dominante y la intención comunicativa de la caricatura?', NULL, 'Uso de la ironía y la sátira para criticar la inequidad tributaria y el cinismo de la clase política.', 'Celebrar la fortaleza física de los ciudadanos trabajadores.', 'Promocionar una campaña de recaudo fiscal oficial.', 'Describir literalmente un evento deportivo de levantamiento de rocas.', 'A', 'La caricatura emplea la sátira y el contraste irónico para denunciar cómo las cargas fiscales recaen sobre el pueblo mientras las élites políticas se benefician de ellas sin sacrificio.', 5),
(26, 6, 'A un ciudadano en Colombia le niegan un tratamiento médico urgente recetado por su médico tratante para una enfermedad que pone en riesgo inminente su vida, argumentando trámites administrativos de su EPS.', 'Según la Constitución Política de 1991, ¿cuál es el mecanismo judicial preferente, rápido y eficaz para proteger de inmediato el derecho fundamental a la vida y a la salud?', NULL, 'Acción de Cumplimiento', 'Acción de Tutela (Artículo 86)', 'Referendo derogatorio', 'Habeas Corpus', 'B', 'La Acción de Tutela (Art. 86) es el mecanismo constitucional diseñado para la protección inmediata de derechos fundamentales vulnerados o amenazados por la acción u omisión de cualquier autoridad pública o particular.', 1),
(27, 6, 'La Constitución de 1991 consagra que Colombia es un Estado Social de Derecho organizado en forma de República unitaria, descentralizada y democrática.', '¿Cuáles son las tres ramas del poder público en Colombia y qué órgano encabeza la rama legislativa?', NULL, 'Ejecutiva, Legislativa y Judicial; y el Congreso de la República (Senado y Cámara) encabeza la legislativa.', 'Militar, Civil y Eclesiástica; encabezada por el Presidente.', 'Fiscalía, Procuraduría y Contraloría; encabezada por la Corte Suprema.', 'Electoral, Ciudadana y Gremial; encabezada por las Alcaldías.', 'A', 'Las tres ramas del poder son Ejecutiva (Presidente/Alcaldes), Legislativa (Congreso de la República) y Judicial (Cortes y jueces). Los organismos de control no son ramas sino órganos autónomos del Estado.', 2),
(28, 6, 'Durante el siglo XX en Colombia, los dos partidos tradicionales pactaron alternarse la presidencia de la República y repartirse los cargos públicos de manera paritaria durante 16 años (1958-1974) para poner fin a la época de La Violencia.', '¿Cómo se denominó formalmente este pacto político e institucional?', NULL, 'La Regeneración', 'El Frente Nacional', 'La Séptima Papeleta', 'La Patria Boba', 'B', 'El Frente Nacional fue el acuerdo bipartidista entre liberales y conservadores (iniciado tras el plebiscito de 1957) que rigió de 1958 a 1974 para pacificar el país y terminar la dictadura militar de Rojas Pinilla.', 3),
(29, 6, 'En un municipio, la comunidad descubre que una empresa minera iniciará trabajos a cielo abierto cerca a la fuente de agua dulce del acueducto, afectando a miles de familias.', '¿Qué mecanismo constitucional de participación ciudadana permite a los habitantes del municipio pronunciarse formalmente para decidir si aprueban o rechazan el proyecto?', NULL, 'Consulta Popular', 'Moción de censura', 'Plebiscito presidencial', 'Juicio político', 'A', 'La Consulta Popular es la institución mediante la cual una pregunta de carácter general sobre un asunto de trascendencia nacional, departamental o municipal es sometida por el gobernante a consideración del pueblo.', 4),
(30, 6, 'Un grupo de ciudadanos decide recolectar firmas para revocar el mandato del alcalde de su ciudad porque consideran que no ha cumplido su plan de gobierno.', '¿A quiénes puede aplicarse constitucionalmente el mecanismo de revocatoria del mandato en Colombia?', NULL, 'Exclusivamente al Presidente de la República y ministros.', 'A Senadores y Representantes a la Cámara.', 'A Alcaldes y Gobernadores elegidos por voto popular.', 'A jueces y magistrados de las altas cortes.', 'C', 'En Colombia, la revocatoria del mandato solo procede para los cargos de elección popular de orden territorial: Gobernadores y Alcaldes (Art. 103 de la Constitución).', 5),
(31, 7, 'You see a sign at an airport terminal that says: «PASSENGERS MUST HAVE THEIR BOARDING PASS AND PASSPORT READY BEFORE ENTERING THE GATE».', 'Where would you most likely see this notice and what is the rule?', NULL, 'At a cinema ticket counter to watch a movie.', 'At an airport departure gate, requiring travel documents ready.', 'In a hospital waiting room for doctors only.', 'Inside a grocery store to buy food.', 'B', 'The vocabulary words \"boarding pass\", \"passport\", and \"gate\" clearly indicate an airport boarding area. The modal verb \"must\" expresses a mandatory obligation.', 1),
(32, 7, 'Complete the sentence with the correct grammatical option: «If I ______ enough money, I would travel around South America next summer.»', 'Choose the correct verb form for this Second Conditional sentence:', NULL, 'have', 'had', 'will have', 'having', 'B', 'The Second Conditional describes hypothetical or imaginary situations in the present/future: \"If + Simple Past (had), would + base verb (would travel)\".', 2),
(33, 7, 'Read the short excerpt: «Maria has lived in Medellin for five years. She works as a graphic designer and enjoys cycling on weekends.»', 'Based on the text, what can we accurately conclude about Maria?', NULL, 'She moved away from Medellin five years ago.', 'She currently lives in Medellin and rides a bike in her free time.', 'She dislikes sports and outdoor activities.', 'She has never had a job in design.', 'B', 'The Present Perfect \"has lived ... for five years\" indicates she started living there 5 years ago and continues to live there now. Cycling on weekends confirms she enjoys bike rides.', 3),
(34, 7, 'Complete the sentence: «The new library _______ by the mayor yesterday morning in front of hundreds of students.»', 'Which passive voice structure correctly completes the past tense sentence?', NULL, 'is opened', 'was opened', 'were opened', 'will open', 'B', 'The subject \"The new library\" is singular and the action took place in the past (\"yesterday morning\"), so the correct passive voice construction is \"was opened\" (was + past participle).', 4),
(35, 7, 'Choose the connector that best expresses contrast in this statement: «David studied hard every night; _______, he found the physics exam quite challenging.»', 'Select the most appropriate transitional word:', NULL, 'however', 'because', 'so', 'and', 'A', '\"However\" is used to show contrast or unexpected outcomes between two independent clauses (he studied hard, BUT the exam was still difficult).', 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recursos`
--

CREATE TABLE `recursos` (
  `id` int(11) NOT NULL,
  `materia_id` int(11) NOT NULL,
  `tipo` enum('taller','video','juego') NOT NULL,
  `titulo` varchar(150) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `enlace` varchar(500) NOT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `recursos`
--

INSERT INTO `recursos` (`id`, `materia_id`, `tipo`, `titulo`, `descripcion`, `enlace`, `creado_en`) VALUES
(1, 1, 'taller', 'Taller de álgebra', 'Ejercicios de ecuaciones para grado 11', 'https://es.khanacademy.org/math/algebra', '2026-09-20 22:11:06'),
(4, 2, 'taller', 'Taller de cinemática', 'Movimiento rectilíneo y parabólico', 'https://es.khanacademy.org/science/physics', '2026-09-20 22:11:06'),
(6, 3, 'taller', 'Taller de estequiometría', 'Cálculos de moles y reacciones', 'https://es.khanacademy.org/science/chemistry', '2026-09-20 22:11:06'),
(8, 4, 'taller', 'Taller de genética', 'Herencia y ADN', 'https://es.khanacademy.org/science/biology', '2026-09-20 22:11:06'),
(10, 5, 'taller', 'Comprensión lectora', 'Análisis de textos', 'https://www.colombiaaprende.edu.co', '2026-09-20 22:11:06'),
(12, 6, 'taller', 'Historia de Colombia', 'Línea de tiempo siglo XX', 'https://www.colombiaaprende.edu.co', '2026-09-20 22:11:06'),
(17, 9, 'taller', 'Introducción a la programación', 'Lógica de programación', 'https://es.khanacademy.org/computing', '2026-09-20 22:11:06'),
(22, 1, 'video', 'Ecuaciones y Funciones Lineales paso a paso', 'Aprende a despejar incógnitas, graficar rectas y resolver problemas aplicados al examen Saber 11.', 'https://www.youtube-nocookie.com/embed/LLncPYk3990', '2026-10-22 03:05:51'),
(23, 1, 'video', 'Trigonometría Básica y Teorema de Pitágoras', 'Razones trigonométricas (seno, coseno, tangente) y su aplicación en triángulos rectángulos.', 'https://www.youtube-nocookie.com/embed/Dbd5OmbOE9c', '2026-10-22 03:05:51'),
(24, 2, 'video', 'Las Tres Leyes de Newton Explicadas', 'Comprende la inercia, la relación fuerza-masa-aceleración y el principio de acción y reacción con ejemplos.', 'https://www.youtube-nocookie.com/embed/2UHS883_P68', '2026-10-22 03:05:51'),
(25, 2, 'video', 'Conservación de la Energía Mecánica', 'Transformación entre energía cinética y energía potencial gravitatoria en montañas rusas y caídas libres.', 'https://www.youtube-nocookie.com/embed/k9E3xYw7M2Y', '2026-10-22 03:05:51'),
(26, 3, 'video', 'Estructura Atómica y Tabla Periódica', 'Protones, neutrones, electrones de valencia y cómo se organizan los elementos en la tabla periódica.', 'https://www.youtube-nocookie.com/embed/rP3iM7qjZp8', '2026-10-22 03:05:51'),
(27, 3, 'video', 'Balanceo de Ecuaciones Químicas por Tanteo', 'Aprende el método sistemático para igualar reactivos y productos en una reacción química.', 'https://www.youtube-nocookie.com/embed/yQ5uFq6zY7k', '2026-10-22 03:05:51'),
(28, 4, 'video', 'Estructura Celular y sus Organelos', 'Diferencias entre célula animal y vegetal, mitocondrias, núcleo y síntesis celular.', 'https://www.youtube-nocookie.com/embed/el_J3U7J4oM', '2026-10-22 03:05:51'),
(29, 4, 'video', 'Leyes de Mendel y Herencia Genética', 'Cuadros de Punnett, genotipos dominantes y recesivos explicados con ejercicios prácticos.', 'https://www.youtube-nocookie.com/embed/LXXK2l1pdv8', '2026-10-22 03:05:51'),
(30, 5, 'video', 'Técnicas de Lectura Crítica para el ICFES', 'Cómo identificar la tesis del autor, detectar argumentos y evaluar premisas en textos complejos.', 'https://www.youtube-nocookie.com/embed/m7wFms8b-yQ', '2026-10-22 03:05:51'),
(31, 5, 'video', 'Conectores Lógicos y Cohesión Textual', 'Uso correcto de conectores de causa, consecuencia, oposición y adición en textos argumentativos.', 'https://www.youtube-nocookie.com/embed/oK2B0vW4rQw', '2026-10-22 03:05:51'),
(32, 6, 'video', 'Constitución de 1991 y Derechos Fundamentales', 'Historia de la Asamblea Nacional Constituyente y catálogo de derechos en Colombia.', 'https://www.youtube-nocookie.com/embed/zS6b9i4wX_8', '2026-10-22 03:05:51'),
(33, 6, 'video', 'Ramas del Poder Público y Organismos de Control', 'Funciones del Ejecutivo, Legislativo, Judicial, Procuraduría, Contraloría y Defensoría.', 'https://www.youtube-nocookie.com/embed/V7d1h9X5p_A', '2026-10-22 03:05:51'),
(34, 7, 'video', 'El Paso del Mito al Logos en Grecia Antigua', 'Los presocráticos y el nacimiento del pensamiento filosófico y racional en occidente.', 'https://www.youtube-nocookie.com/embed/xHQbUq-J8hE', '2026-10-22 03:05:51'),
(35, 8, 'video', 'Tiempos Verbales en Inglés (Present, Past & Future)', 'Repaso intensivo de gramática en inglés enfocada en las pruebas estandarizadas Saber 11.', 'https://www.youtube-nocookie.com/embed/8v_5b3y5d0U', '2026-10-22 03:05:51'),
(36, 9, 'video', 'Introducción a la Lógica de Programación', 'Qué es un algoritmo, diagramas de flujo y estructuras condicionales para resolver problemas.', 'https://www.youtube-nocookie.com/embed/Kuu_4yH_o_M', '2026-10-22 03:05:51'),
(37, 10, 'video', 'Dilemas Morales y Ética Ciudadana', 'Cómo reflexionar críticamente ante decisiones éticas en la vida cotidiana y social.', 'https://www.youtube-nocookie.com/embed/oB9_3K-o1zI', '2026-10-22 03:05:51'),
(38, 11, 'video', 'Entrenamiento Físico y Salud Cardiovascular', 'Frecuencia cardíaca, resistencia aeróbica y hábitos de vida activa para jóvenes.', 'https://www.youtube-nocookie.com/embed/1kYgVqB1f-c', '2026-10-22 03:05:51'),
(39, 12, 'video', 'Historia y Diversidad de las Grandes Religiones', 'Panorama del cristianismo, islam, judaísmo, budismo e hinduismo en el mundo moderno.', 'https://www.youtube-nocookie.com/embed/v7qQ4u9eMuo', '2026-10-22 03:05:51'),
(40, 1, 'juego', 'Trivia Relámpago: Desafío Numérico', 'Demuestra tu agilidad mental resolviendo retos rápidos de matemáticas en 60 segundos.', 'juego:trivia:1', '2026-10-22 03:05:51'),
(41, 1, 'juego', 'Memoria de Fórmulas Matemáticas', 'Encuentra las parejas entre teoremas, propiedades y sus fórmulas correspondientes.', 'juego:memoria:matematicas', '2026-10-22 03:05:51'),
(42, 2, 'juego', 'Simulador PhET: Fuerzas y Movimiento', 'Experimenta con fricción, masa y aceleración en un laboratorio virtual interactivo.', 'https://phet.colorado.edu/sims/html/forces-and-motion-basics/latest/forces-and-motion-basics_all.html', '2026-10-22 03:05:51'),
(43, 2, 'juego', 'Trivia Relámpago de Física', 'Pon a prueba tus reflejos con preguntas rápidas de cinemática y leyes de Newton.', 'juego:trivia:2', '2026-10-22 03:05:51'),
(44, 3, 'juego', 'Memoria Química: Elementos y Símbolos', 'Empareja los elementos más importantes de la tabla periódica con su símbolo químico.', 'juego:memoria:quimica', '2026-10-22 03:05:51'),
(45, 3, 'juego', 'Simulador PhET: Construye un Átomo', 'Agrega protones, neutrones y electrones para crear elementos y ver su carga neta.', 'https://phet.colorado.edu/sims/html/build-an-atom/latest/build-an-atom_all.html', '2026-10-22 03:05:51'),
(46, 4, 'juego', 'Palabra Oculta: Biología Celular', 'Adivina los términos clave sobre genética y biología antes de agotar tus intentos.', 'juego:ahorcado:biologia', '2026-10-22 03:05:51'),
(47, 4, 'juego', 'Trivia Relámpago: Reinos de la Naturaleza', 'Reto contrarreloj de 60 segundos sobre ecosistemas y seres vivos.', 'juego:trivia:4', '2026-10-22 03:05:51'),
(48, 5, 'juego', 'Palabra Oculta: Figuras Literarias', 'Descubre metáforas, hipérboles y términos del lenguaje mediante pistas.', 'juego:ahorcado:literatura', '2026-10-22 03:05:51'),
(49, 6, 'juego', 'Trivia Ciudadana Saber 11', 'Demuestra cuánto sabes sobre la Constitución de Colombia y los derechos humanos.', 'juego:trivia:6', '2026-10-22 03:05:51'),
(50, 8, 'juego', 'Speed Vocabulary Challenge', 'Acierta la mayor cantidad de traducciones y palabras en inglés antes de que acabe el tiempo.', 'juego:trivia:8', '2026-10-22 03:05:51');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `talleres`
--

CREATE TABLE `talleres` (
  `id` int(11) NOT NULL,
  `materia_id` int(11) NOT NULL,
  `titulo` varchar(180) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `duracion_minutos` int(11) DEFAULT 15,
  `nivel` varchar(50) DEFAULT 'Saber 11',
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `talleres`
--

INSERT INTO `talleres` (`id`, `materia_id`, `titulo`, `descripcion`, `duracion_minutos`, `nivel`, `creado_en`) VALUES
(1, 1, 'Simulacro ICFES Saber 11: Álgebra y Razonamiento Cuantitativo', 'Evaluación de competencias matemáticas: interpretación de datos, formulación de ecuaciones y modelación geométrica según estándares oficiales del ICFES.', 15, 'Saber 11', '2026-10-22 03:05:17'),
(2, 2, 'Taller Saber 11: Mecánica Clásica, Cinemática y Energía', 'Preguntas contextualizadas sobre las Leyes de Newton, conservación de la energía mecánica y análisis de gráficas de movimiento.', 15, 'Saber 11', '2026-10-22 03:05:17'),
(3, 3, 'Simulacro Saber 11: Estructura Atómica y Enlaces Químicos', 'Preguntas tipo ICFES sobre tabla periódica, enlaces iónicos y covalentes, balanceo de reacciones y soluciones químicas.', 15, 'Saber 11', '2026-10-22 03:05:17'),
(4, 4, 'Simulacro Saber 11: Genética, Célula y Ecosistemas', 'Evaluación de competencias biológicas: herencia mendeliana, flujo de energía en ecosistemas y funcionamiento celular.', 15, 'Saber 11', '2026-10-22 03:05:17'),
(5, 5, 'Simulacro Saber 11: Lectura Crítica y Análisis Textual', 'Evaluación de las 3 competencias ICFES: identificar contenidos locales, comprender el sentido global y reflexionar críticamente a partir del texto.', 15, 'Saber 11', '2026-10-22 03:05:17'),
(6, 6, 'Simulacro Saber 11: Constitución, Estado y Derechos Humanos', 'Preguntas sobre mecanismos de participación ciudadana en Colombia, estructura del Estado y resolución de conflictos sociales.', 15, 'Saber 11', '2026-10-22 03:05:17'),
(7, 8, 'Saber 11: English Reading Comprehension & Grammar', 'Evaluación de habilidades en lengua inglesa: tiempos verbales, vocabulario contextual y comprensión de avisos y textos cortos.', 15, 'Saber 11 (B1)', '2026-10-22 03:05:17');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(120) NOT NULL,
  `usuario` varchar(60) NOT NULL,
  `correo` varchar(150) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `rol` enum('estudiante','admin') NOT NULL DEFAULT 'estudiante',
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `usuario`, `correo`, `contrasena`, `rol`, `creado_en`) VALUES
(1, 'Administrador RepasaYa', 'admin', 'admin@repasaya.com', '$2b$12$6fSHZGjLkoG.1mbihS5nk.Xkn64MljDRXoc02v5yoG/YXEmDSBO3u', 'admin', '2026-09-20 22:11:06'),
(2, 'ass2', 'ass2', 'ass2@pee.com', '$2y$10$DbUEUgyElQiTyBfjVDATcOVuMTLLtdKjYiK/4JtgwWdiNbLWAfmhi', 'admin', '2026-09-20 22:11:32'),
(3, 'Lorena', '111125', 'penecorto@gmail.com', 'yapo', 'estudiante', '2026-10-22 03:26:29'),
(4, 'ass2', '1234', '1234@gmail.com', '1234', 'estudiante', '2026-10-22 18:35:18'),
(5, 'henry', 'henry', 'henry@gmail.com', '1234', 'admin', '2026-10-22 18:41:53'),
(6, 'El Administrador', 'fede', 'garcesfedericohenao@gmail.com', '123456', 'admin', '2026-10-23 03:41:53'),
(7, 'chamorro', 'profesor', 'chamorro@gmail.com', '12345', 'estudiante', '2026-10-23 13:41:17'),
(8, 'Sofia Ocampo Loaiza', 'Sofi la alcachofi', 'sofiaocampo01@gmail.com', '123', 'estudiante', '2026-10-26 18:44:38');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `intentos_taller`
--
ALTER TABLE `intentos_taller`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `taller_id` (`taller_id`);

--
-- Indices de la tabla `materias`
--
ALTER TABLE `materias`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indices de la tabla `preguntas`
--
ALTER TABLE `preguntas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `taller_id` (`taller_id`);

--
-- Indices de la tabla `recursos`
--
ALTER TABLE `recursos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `materia_id` (`materia_id`);

--
-- Indices de la tabla `talleres`
--
ALTER TABLE `talleres`
  ADD PRIMARY KEY (`id`),
  ADD KEY `materia_id` (`materia_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`),
  ADD UNIQUE KEY `correo` (`correo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `intentos_taller`
--
ALTER TABLE `intentos_taller`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `materias`
--
ALTER TABLE `materias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `preguntas`
--
ALTER TABLE `preguntas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT de la tabla `recursos`
--
ALTER TABLE `recursos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT de la tabla `talleres`
--
ALTER TABLE `talleres`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `intentos_taller`
--
ALTER TABLE `intentos_taller`
  ADD CONSTRAINT `intentos_taller_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `intentos_taller_ibfk_2` FOREIGN KEY (`taller_id`) REFERENCES `talleres` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `preguntas`
--
ALTER TABLE `preguntas`
  ADD CONSTRAINT `preguntas_ibfk_1` FOREIGN KEY (`taller_id`) REFERENCES `talleres` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `recursos`
--
ALTER TABLE `recursos`
  ADD CONSTRAINT `recursos_ibfk_1` FOREIGN KEY (`materia_id`) REFERENCES `materias` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `talleres`
--
ALTER TABLE `talleres`
  ADD CONSTRAINT `talleres_ibfk_1` FOREIGN KEY (`materia_id`) REFERENCES `materias` (`id`) ON DELETE CASCADE;
--
-- Base de datos: `taller_reparaciones`
--
CREATE DATABASE IF NOT EXISTS `taller_reparaciones` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `taller_reparaciones`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios`
--

CREATE TABLE `servicios` (
  `id` int(11) NOT NULL,
  `numero_servicio` varchar(20) NOT NULL,
  `cliente_nombre` varchar(100) NOT NULL,
  `cliente_telefono` varchar(20) NOT NULL,
  `cliente_email` varchar(100) DEFAULT NULL,
  `tipo_dispositivo` varchar(50) NOT NULL,
  `marca` varchar(50) NOT NULL,
  `modelo` varchar(50) DEFAULT NULL,
  `descripcion_dano` text NOT NULL,
  `diagnostico` text DEFAULT NULL,
  `estado` enum('Recibido','En diagnóstico','En reparación','Cerrado') NOT NULL DEFAULT 'Recibido',
  `costo_estimado` decimal(10,2) DEFAULT 0.00,
  `tecnico_asignado` varchar(100) DEFAULT NULL,
  `fecha_ingreso` datetime NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `fecha_cierre` datetime DEFAULT NULL
) ;

--
-- Volcado de datos para la tabla `servicios`
--

INSERT INTO `servicios` (`id`, `numero_servicio`, `cliente_nombre`, `cliente_telefono`, `cliente_email`, `tipo_dispositivo`, `marca`, `modelo`, `descripcion_dano`, `diagnostico`, `estado`, `costo_estimado`, `tecnico_asignado`, `fecha_ingreso`, `fecha_actualizacion`, `fecha_cierre`) VALUES
(1, 'SRV-2026-0001', 'Carlos Mendoza', '3104567890', 'carlos.mendoza@email.com', 'Portátil / Laptop', 'Lenovo', 'ThinkPad T480', 'El equipo se apaga repentinamente tras 15 minutos de uso y recalienta en la base.', 'Sistema de refrigeración obstruido por polvo y pasta térmica solidificada. Ventilador requiere lubricación.', 'En diagnóstico', 85000.00, 'Técnico de Soporte', '2026-10-25 09:30:00', '2026-11-05 13:18:36', NULL),
(2, 'SRV-2026-0002', 'María Camila López', '3159876543', 'mcamila.lopez@email.com', 'Computador de escritorio', 'Dell', 'OptiPlex 7070', 'No da video ni emite pitidos al encender. El botón de encendido parpadea en naranja.', 'Falla en la fuente de poder de 260W y banco de memoria RAM sulfatado. Se reemplazó la fuente y se limpiaron los contactos.', 'En reparación', 160000.00, 'Técnico de Soporte', '2026-10-27 14:15:00', '2026-11-05 13:18:36', NULL),
(3, 'SRV-2026-0003', 'Andrés Felipe Roa', '3001234567', 'andres.roa@email.com', 'Smartphone', 'Samsung', 'Galaxy S21', 'Pantalla quebrada tras caída. El táctil responde intermitentemente en la zona superior.', NULL, 'Recibido', 220000.00, 'Sin asignar', '2026-11-01 11:00:00', '2026-11-05 13:18:36', NULL),
(4, 'SRV-2026-0004', 'Diana Patricia Silva', '3206549870', 'diana.silva@email.com', 'Tablet', 'Apple', 'iPad Air 4', 'No retiene la carga eléctrica. Se apaga al desconectar el cable de alimentación.', 'Batería degradada al 42% de capacidad original con ciclos excedidos. Se procedió al cambio exitoso de módulo de batería original y pruebas de estrés pasadas.', 'Cerrado', 280000.00, 'Administrador Principal', '2026-10-20 10:00:00', '2026-11-05 13:18:36', '2026-10-24 16:45:00'),
(5, 'SRV-2026-0005', 'Registro de Prueba Test', '3000000000', 'test@prueba.com', 'Consola de videojuegos', 'Sony', 'PlayStation 5', 'Registro temporal creado para probar la función de eliminación del sistema.', NULL, 'Recibido', 0.00, 'Sin asignar', '2026-11-02 08:00:00', '2026-11-05 13:18:36', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `rol` enum('admin','usuario') NOT NULL DEFAULT 'usuario',
  `creado_en` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `usuario`, `password`, `nombre`, `rol`, `creado_en`) VALUES
(1, 'admin', '$2y$10$8rSM2DCgAvddqidaIan5/.icLwTxrbrv2ehynIAiuHch/9GdOx4/S', 'Administrador Principal', 'admin', '2026-11-05 13:18:36'),
(2, 'usuario', '$2y$10$aYrsaK38qoe2ZApoi1vdNOMu5pJ2vRTSAxd4IkmAGMDalw2.fNDdS', 'Técnico de Soporte', 'usuario', '2026-11-05 13:18:36');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `servicios`
--
ALTER TABLE `servicios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `numero_servicio` (`numero_servicio`),
  ADD KEY `idx_numero_servicio` (`numero_servicio`),
  ADD KEY `idx_estado` (`estado`),
  ADD KEY `idx_cliente` (`cliente_nombre`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `servicios`
--
ALTER TABLE `servicios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- Base de datos: `test`
--
CREATE DATABASE IF NOT EXISTS `test` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `test`;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
