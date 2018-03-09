-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 08-03-2018 a las 21:30:29
-- Versión del servidor: 10.1.13-MariaDB
-- Versión de PHP: 5.6.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `bachillerato`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE ` EstatusAlumnoCaliEspe` ()  NO SQL
BEGIN
  	DECLARE _id_alumno int(11);
  	DECLARE _id_periodo int(3);
    DECLARE v_ultima_fila INT DEFAULT 0;
   
    DECLARE calAlumPeriActual CURSOR FOR SELECT calificacion.id_alumno, periodo.id_periodo FROM calificacion INNER JOIN grupo on grupo.id_grupo = calificacion.id_grupo INNER JOIN periodo ON periodo.id_periodo = grupo.id_periodo INNER JOIN alumno ON alumno.id_alumno = calificacion.id_alumno WHERE alumno.estado = 1 AND grupo.nombre = "S6" GROUP BY calificacion.id_alumno;

       DECLARE CONTINUE HANDLER FOR NOT FOUND SET v_ultima_fila=1;
        OPEN calAlumPeriActual;
          calAlumPeriActualCursor:LOOP FETCH calAlumPeriActual  INTO _id_alumno, _id_periodo;
        IF v_ultima_fila=1 THEN
          LEAVE calAlumPeriActualCursor;
            
        ELSEIF (SELECT COUNT(calificacion.estatus) FROM calificacion WHERE calificacion.estatus = 'REPROBO' AND calificacion.id_alumno = _id_alumno) > 0 THEN
          UPDATE alumno SET estado = 0 WHERE alumno.id_alumno = _id_alumno;
            UPDATE alumno SET estatusSemestreCal = 'REPROBO', alumno.estado = 0 WHERE alumno.id_alumno = _id_alumno;
        ELSEIF (SELECT COUNT(calificacion.estatus) FROM calificacion WHERE calificacion.estatus = 'REPROBO' AND calificacion.id_alumno = _id_alumno) = 0 THEN
            UPDATE alumno SET estatusSemestreCal = 'APROBO' WHERE alumno.id_alumno = _id_alumno;
            IF (SELECT grupo.nombre FROM grupo INNER JOIN calificacion on calificacion.id_grupo = grupo.id_grupo WHERE calificacion.id_alumno = _id_alumno ORDER BY grupo.nombre DESC LIMIT 1) = 'S6' THEN
              UPDATE alumno SET estatusSemestreCal = CONCAT('EGRESO-',_id_periodo, "ESPE") WHERE alumno.id_alumno = _id_alumno;
            END iF;
      END IF;
        END LOOP calAlumPeriActualCursor;
        CLOSE calAlumPeriActual;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `AlumnoEgresadoNuevoToAntiguo` ()  NO SQL
BEGIN
  DECLARE _id_alumno int(11);
    DECLARE v_ultima_fila INT DEFAULT 0;
   
    DECLARE calAlumPeriActual CURSOR FOR SELECT calificacion.id_alumno FROM calificacion INNER JOIN grupo on grupo.id_grupo = calificacion.id_grupo INNER JOIN periodo ON periodo.id_periodo = grupo.id_periodo INNER JOIN alumno ON alumno.id_alumno = calificacion.id_alumno WHERE alumno.estado = 1 AND alumno.estatusSemestreCal = "EGRESO NUEVO" GROUP BY calificacion.id_alumno;

       DECLARE CONTINUE HANDLER FOR NOT FOUND SET v_ultima_fila=1;
        OPEN calAlumPeriActual;
          calAlumPeriActualCursor:LOOP FETCH calAlumPeriActual  INTO _id_alumno;
        IF v_ultima_fila=1 THEN
          LEAVE calAlumPeriActualCursor;
        ELSE
            UPDATE alumno SET estatusSemestreCal = 'EGRESO ANTIGUO' WHERE alumno.id_alumno = _id_alumno;
      END IF;
        END LOOP calAlumPeriActualCursor;
        CLOSE calAlumPeriActual;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `cursor1` ()  BEGIN
    DECLARE _id_alumno int(11);
    DECLARE _nombre varchar(60);
    DECLARE _curp varchar(50);
    DECLARE v_ultima_fila INT DEFAULT 0;
   
    DECLARE c_alumnos CURSOR FOR
    SELECT id_alumno, nombre, curp
        FROM alumno 
        ORDER BY nombre ASC;
       DECLARE CONTINUE HANDLER FOR NOT FOUND SET v_ultima_fila=1;
        OPEN c_alumnos;
        alumnos_cursor: LOOP
        FETCH c_alumnos  INTO _id_alumno, _nombre, _curp;
        
        IF v_ultima_fila=1 THEN
        LEAVE alumnos_cursor;
        END IF;
       
            
        INSERT INTO alumno1(id_alumno, nombre, curp) VALUES   (_id_alumno, _nombre, _curp);
        END LOOP alumnos_cursor;
        CLOSE c_alumnos;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `cursor4` ()  BEGIN
    DECLARE _id_alumno int(11);
    DECLARE _nombre varchar(60);
    DECLARE _curp varchar(50);
    DECLARE _id_plantel int(11);
    DECLARE v_ultima_fila INT DEFAULT 0;
   
    DECLARE c_alumnos CURSOR FOR SELECT id_alumno, nombre, curp, id_plantel FROM alumno;
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET v_ultima_fila=1;
	OPEN c_alumnos;
	alumnos_cursor: LOOP FETCH c_alumnos  INTO _id_alumno, _nombre, _curp, _id_plantel;
        
    IF v_ultima_fila=1 THEN
    LEAVE alumnos_cursor;
    END IF;
       
    INSERT INTO alumno1(id_alumno, nombre, curp, id_plantel, nLista) VALUES   (_id_alumno, _nombre, _curp, _id_plantel, 0);
    END LOOP alumnos_cursor;
    CLOSE c_alumnos;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `EstatusAlumnoCali` ()  NO SQL
BEGIN
    DECLARE _id_alumno int(11);
    DECLARE _id_periodo int(3);
    DECLARE v_ultima_fila INT DEFAULT 0;
   
    DECLARE calAlumPeriActual CURSOR FOR SELECT calificacion.id_alumno, periodo.id_periodo FROM calificacion INNER JOIN grupo on grupo.id_grupo = calificacion.id_grupo INNER JOIN periodo ON periodo.id_periodo = grupo.id_periodo INNER JOIN alumno ON alumno.id_alumno = calificacion.id_alumno AND alumno.estado = 1 AND periodo.estatus = 1 AND grupo.nombre = "S6" GROUP BY calificacion.id_alumno;

       DECLARE CONTINUE HANDLER FOR NOT FOUND SET v_ultima_fila=1;
        OPEN calAlumPeriActual;
          calAlumPeriActualCursor:LOOP FETCH calAlumPeriActual  INTO _id_alumno, _id_periodo;
        IF v_ultima_fila=1 THEN
          LEAVE calAlumPeriActualCursor;
            
        ELSEIF (SELECT COUNT(calificacion.estatus) FROM calificacion WHERE calificacion.estatus = 'REPROBO' AND calificacion.id_alumno = _id_alumno) > 2 THEN
          UPDATE alumno SET estado = 0 WHERE alumno.id_alumno = _id_alumno;
            UPDATE alumno SET estatusSemestreCal = 'REPROBO' WHERE alumno.id_alumno = _id_alumno;
        ELSEIF (SELECT COUNT(calificacion.estatus) FROM calificacion WHERE calificacion.estatus = 'REPROBO' AND calificacion.id_alumno = _id_alumno) = 0 THEN
            UPDATE alumno SET estatusSemestreCal = 'APROBO' WHERE alumno.id_alumno = _id_alumno;
            IF (SELECT grupo.nombre FROM grupo INNER JOIN calificacion on calificacion.id_grupo = grupo.id_grupo WHERE calificacion.id_alumno = _id_alumno ORDER BY grupo.nombre DESC LIMIT 1) = 'S6' THEN
            	UPDATE alumno SET estatusSemestreCal = CONCAT('EGRESO-',_id_periodo) WHERE alumno.id_alumno = _id_alumno;
            END IF;
        ELSE
            UPDATE alumno SET estatusSemestreCal = 'PENDIENTE' WHERE alumno.id_alumno = _id_alumno;
      END IF;
        END LOOP calAlumPeriActualCursor;
        CLOSE calAlumPeriActual;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `EstatusAlumnoCaliExtra` ()  NO SQL
BEGIN
  	DECLARE _id_alumno int(11);
  	DECLARE _id_periodo int(11);
    DECLARE v_ultima_fila INT DEFAULT 0;
   
    DECLARE calAlumPeriActual CURSOR FOR SELECT calificacion.id_alumno, periodo.id_periodo FROM calificacion INNER JOIN grupo on grupo.id_grupo = calificacion.id_grupo INNER JOIN periodo ON periodo.id_periodo = grupo.id_periodo INNER JOIN alumno ON alumno.id_alumno = calificacion.id_alumno WHERE alumno.estado = 1 AND grupo.nombre = "S6" GROUP BY calificacion.id_alumno;

       DECLARE CONTINUE HANDLER FOR NOT FOUND SET v_ultima_fila=1;
        OPEN calAlumPeriActual;
          calAlumPeriActualCursor:LOOP FETCH calAlumPeriActual  INTO _id_alumno, _id_periodo;
        IF v_ultima_fila=1 THEN
          LEAVE calAlumPeriActualCursor;
         
        ELSEIF (SELECT COUNT(calificacion.estatus) FROM calificacion WHERE calificacion.estatus = 'REPROBO' AND calificacion.id_alumno = _id_alumno) > 2 THEN
          UPDATE alumno SET estado = 0 WHERE alumno.id_alumno = _id_alumno;
            UPDATE alumno SET estatusSemestreCal = 'REPROBO' WHERE alumno.id_alumno = _id_alumno;
        ELSEIF (SELECT COUNT(calificacion.estatus) FROM calificacion WHERE calificacion.estatus = 'REPROBO' AND calificacion.id_alumno = _id_alumno) = 0 THEN
            UPDATE alumno SET estatusSemestreCal = 'APROBO' WHERE alumno.id_alumno = _id_alumno;
            IF (SELECT grupo.nombre FROM grupo INNER JOIN calificacion on calificacion.id_grupo = grupo.id_grupo WHERE calificacion.id_alumno = _id_alumno ORDER BY grupo.nombre DESC LIMIT 1) = 'S6' THEN
              UPDATE alumno SET estatusSemestreCal = CONCAT('EGRESO-',_id_periodo, 'EX') WHERE alumno.id_alumno = _id_alumno;
            END iF;
        ELSE
            UPDATE alumno SET estatusSemestreCal = 'PENDIENTE' WHERE alumno.id_alumno = _id_alumno;
      END IF;
        END LOOP calAlumPeriActualCursor;
        CLOSE calAlumPeriActual;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `EstatusAlumnoCaliToEspe` ()  NO SQL
BEGIN
    DECLARE _id_alumno int(11);
    DECLARE _id_grupo int(11);
    DECLARE _id_materia int(11);
    DECLARE _id_docente int(11);
    DECLARE _espe int(11);
    DECLARE v_ultima_fila INT DEFAULT 0;
   
    DECLARE calAlumPeriActual CURSOR FOR SELECT calificacion.id_alumno, calificacion.id_grupo, calificacion.id_materia, calificacion.id_docente, calificacion.especial FROM calificacion INNER JOIN grupo on grupo.id_grupo = calificacion.id_grupo INNER JOIN periodo ON periodo.id_periodo = grupo.id_periodo WHERE calificacion.estatus = "REPROBO";

       DECLARE CONTINUE HANDLER FOR NOT FOUND SET v_ultima_fila=1;
        OPEN calAlumPeriActual;
            calAlumPeriActualCursor:LOOP FETCH calAlumPeriActual  INTO _id_alumno, _id_grupo, _id_materia, _id_docente, _espe;
        IF v_ultima_fila=1 THEN
            LEAVE calAlumPeriActualCursor;
        ELSEIF _espe >= 6 THEN
            UPDATE calificacion SET estatus = 'APROBO' WHERE id_alumno = _id_alumno AND id_grupo = _id_grupo AND id_materia = _id_materia AND id_docente = _id_docente;
        ELSE
            UPDATE calificacion SET estatus = 'REPROBO' WHERE id_alumno = _id_alumno AND id_grupo = _id_grupo AND id_materia = _id_materia AND id_docente = _id_docente;
        END IF;
        END LOOP calAlumPeriActualCursor;
        CLOSE calAlumPeriActual;
        CALL EstatusAlumnoCaliEspe();
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `EstatusAlumnoCaliToExtra` ()  NO SQL
BEGIN
    DECLARE _id_alumno int(11);
    DECLARE _id_grupo int(11);
    DECLARE _id_materia int(11);
    DECLARE _id_docente int(11);
    DECLARE _extra1 int(11);
    DECLARE _extra2 int(11);
    DECLARE v_ultima_fila INT DEFAULT 0;
   
    DECLARE calAlumPeriActual CURSOR FOR SELECT calificacion.id_alumno, calificacion.id_grupo, calificacion.id_materia, calificacion.id_docente, calificacion.extraordinario1, calificacion.extraordinario2 FROM calificacion INNER JOIN grupo on grupo.id_grupo = calificacion.id_grupo INNER JOIN periodo ON periodo.id_periodo = grupo.id_periodo WHERE calificacion.estatus = "REPROBO";

       DECLARE CONTINUE HANDLER FOR NOT FOUND SET v_ultima_fila=1;
        OPEN calAlumPeriActual;
            calAlumPeriActualCursor:LOOP FETCH calAlumPeriActual  INTO _id_alumno, _id_grupo, _id_materia, _id_docente, _extra1, _extra2;
        IF v_ultima_fila=1 THEN
            LEAVE calAlumPeriActualCursor;
        ELSEIF _extra1 >= 6 OR _extra2 >= 6 THEN
            UPDATE calificacion SET estatus = 'APROBO' WHERE id_alumno = _id_alumno AND id_grupo = _id_grupo AND id_materia = _id_materia AND id_docente = _id_docente;
        ELSE
            UPDATE calificacion SET estatus = 'REPROBO' WHERE id_alumno = _id_alumno AND id_grupo = _id_grupo AND id_materia = _id_materia AND id_docente = _id_docente;
        END IF;
        END LOOP calAlumPeriActualCursor;
        CLOSE calAlumPeriActual;
        CALL EstatusAlumnoCaliExtra();
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `EstatusCali` ()  NO SQL
BEGIN
	DECLARE _id_alumno int(11);
	DECLARE _id_grupo int(11);
	DECLARE _id_materia int(11);
	DECLARE _id_docente int(11);
    DECLARE promedio float;
    DECLARE v_ultima_fila INT DEFAULT 0;
   
    DECLARE calAlumPeriActual CURSOR FOR SELECT calificacion.id_alumno, calificacion.id_grupo, calificacion.id_materia, calificacion.id_docente, ((calificacion.evaluacion1+calificacion.evaluacion2+calificacion.evaluacionFinal)/3) AS promedio FROM calificacion INNER JOIN grupo on grupo.id_grupo = calificacion.id_grupo INNER JOIN periodo ON periodo.id_periodo = grupo.id_periodo AND periodo.estatus = 1;

       DECLARE CONTINUE HANDLER FOR NOT FOUND SET v_ultima_fila=1;
        OPEN calAlumPeriActual;
        	calAlumPeriActualCursor:LOOP FETCH calAlumPeriActual  INTO _id_alumno, _id_grupo, _id_materia, _id_docente, promedio;
        IF v_ultima_fila=1 THEN
        	LEAVE calAlumPeriActualCursor;
        ELSEIF promedio >= 6 THEN
        	UPDATE calificacion SET estatus = 'APROBO' WHERE id_alumno = _id_alumno AND id_grupo = _id_grupo AND id_materia = _id_materia AND id_docente = _id_docente;
        ELSE
        	UPDATE calificacion SET estatus = 'REPROBO' WHERE id_alumno = _id_alumno AND id_grupo = _id_grupo AND id_materia = _id_materia AND id_docente = _id_docente;
        END IF;
        END LOOP calAlumPeriActualCursor;
        CLOSE calAlumPeriActual;
        CALL EstatusAlumnoCali();
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `UpNoControl` ()  BEGIN
		DECLARE _id_alumno INT;
		DECLARE _numero_control VARCHAR(10);
		DECLARE _nLista VARCHAR(3);
    	DECLARE v_ultima_fila INT DEFAULT 0;
    	DECLARE alumnos_numero_control CURSOR FOR SELECT alumno.id_alumno, alumno.nLista FROM alumno WHERE alumno.numero_control IS NULL;
    	DECLARE asignar_numero_control CURSOR FOR SELECT CONCAT(SUBSTRING(periodo.inicio, 3, 2),
													SUBSTRING(grupo.nombre, 1, 1), LPAD(alumno.id_plantel, 3, 0), alumno.nLista) 
													AS numeroControl
													FROM alumno 
													INNER JOIN grupo_alumno 
														ON alumno.id_alumno=grupo_alumno.id_alumno 
														AND alumno.id_alumno=_id_alumno 
													INNER JOIN grupo 
														ON grupo_alumno.id_grupo=grupo.id_grupo
													INNER JOIN periodo
														ON periodo.estatus=1;
        DECLARE CONTINUE HANDLER FOR NOT FOUND SET v_ultima_fila=1;
			OPEN alumnos_numero_control;
				loop_alumnos_numero_control:LOOP FETCH alumnos_numero_control INTO _id_alumno, _nLista;
					IF v_ultima_fila=1 THEN
							LEAVE loop_alumnos_numero_control;
					END IF;
					OPEN asignar_numero_control;
						loop_asignar_numero_control:LOOP FETCH asignar_numero_control INTO _numero_control;
						IF v_ultima_fila=1 THEN
							LEAVE loop_asignar_numero_control;
						END IF;
						IF _nLista IS NOT NULL THEN
							UPDATE alumno SET numero_control=_numero_control WHERE id_alumno=_id_alumno;
						END	IF;
						END LOOP loop_asignar_numero_control;
    				CLOSE asignar_numero_control;
    				SET v_ultima_fila=0;
				END LOOP loop_alumnos_numero_control;
			CLOSE alumnos_numero_control;
        DROP TABLE planteles_cambios;
	END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `UpNoLista` ()  BEGIN
		DECLARE _id_alumno INT;
		DECLARE _nLista INT;
		DECLARE _nPlantel INT;
		DECLARE nlist INT DEFAULT 0;
    	DECLARE v_ultima_fila INT DEFAULT 0;
    	DECLARE num_planteles CURSOR FOR SELECT grupo_alumno.id_plantel FROM alumno INNER JOIN grupo_alumno ON alumno.id_alumno=grupo_alumno.id_alumno GROUP BY grupo_alumno.id_plantel;
    	DECLARE alumnos_ordenados CURSOR FOR SELECT alumno.id_alumno, alumno.nLista FROM alumno INNER JOIN grupo_alumno ON grupo_alumno.id_alumno = alumno.id_alumno INNER JOIN grupo ON grupo.id_grupo = grupo_alumno.id_grupo INNER JOIN periodo ON periodo.id_periodo = grupo.id_periodo INNER JOIN plantel ON plantel.id_plantel = grupo_alumno.id_plantel WHERE alumno.id_plantel=_nPlantel AND grupo.nombre = "S1" AND periodo.estatus = 1 ORDER BY alumno.apellido_paterno ASC;
        DECLARE CONTINUE HANDLER FOR NOT FOUND SET v_ultima_fila=1;
			OPEN num_planteles;
				num_actual_plantel:LOOP FETCH num_planteles INTO _nPlantel;
					IF v_ultima_fila=1 THEN
							LEAVE num_actual_plantel;
					END IF;
					OPEN alumnos_ordenados;
						alumnos_cursor_ordenados:LOOP FETCH alumnos_ordenados INTO _id_alumno, _nLista;
						IF v_ultima_fila=1 THEN
							LEAVE alumnos_cursor_ordenados;
						END IF;
						SET nlist=nlist+1;
						IF _nLista IS NULL THEN
							UPDATE alumno SET nLista=LPAD(nlist, 3, 0) WHERE id_alumno=_id_alumno;
						END	IF;
						END LOOP alumnos_cursor_ordenados;
    				CLOSE alumnos_ordenados;
    				SET nlist=0;
    				SET v_ultima_fila=0;
				END LOOP num_actual_plantel;
			CLOSE num_planteles;
        DROP TABLE IF EXISTS info_eventos;
        CALL UpNoControl();
        UPDATE docente SET docente.permiso=0 WHERE docente.id_rol=2 		AND docente.permiso=1;
	END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alumno`
--

CREATE TABLE `alumno` (
  `id_alumno` int(11) NOT NULL,
  `id_rol` int(11) DEFAULT NULL,
  `nombre` varchar(60) DEFAULT NULL,
  `apellido_paterno` varchar(60) DEFAULT NULL,
  `apellido_materno` varchar(60) DEFAULT NULL,
  `genero` varchar(15) DEFAULT NULL,
  `curp` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `alumno`
--

INSERT INTO `alumno` (`id_alumno`, `id_rol`, `nombre`, `apellido_paterno`, `apellido_materno`, `genero`, `curp`, `password`) VALUES
(3, 4, 'ANTONN', 'ROJAS', 'SOTO', 'MUJER', 'ROSA041001MOCJTN07', '$2y$10$j/mziNdadtoUw5XWBo7Ki.nroToxoLT3AGcRiSXOUpGbrIvg.cvdy'),
(4, 4, 'BELEN', 'FIGUEROA', 'ESPINOZA', 'MUJER', 'FIEB041001MOCGSL00', '$2y$10$c6gb/tkICDFb6Vv2Z4nwPevx8tJxVOvb/qIjVQlYJgUXior.Vr9XS'),
(5, 4, 'OMAR', 'DIAZ', 'RIQUELME', 'HOMBRE', 'DIRO041001HOCZQM07', '$2y$10$yQMONSNvRgbKMBWU22UR2OooG7ioccV68j.vo83cyxOgRTfbXhZqG'),
(25, 4, 'juan', 'perez', 'perez', 'H', 'AJSJASJAS', '$2y$10$C9ZHNkdkegXrnMcq.nNlv.l6Zl5jNYizwi/3AhHz3XHQIDAoBwzW.'),
(26, 4, 'esteba', 'sanchez', 'villegas', 'h', 'hahsahas', '12345'),
(27, 4, NULL, NULL, NULL, NULL, NULL, '12345');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entidad`
--

CREATE TABLE `entidad` (
  `idEntidad` int(11) NOT NULL,
  `cveEntidad` int(11) NOT NULL,
  `nombreEntidad` varchar(255) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `entidad`
--

INSERT INTO `entidad` (`idEntidad`, `cveEntidad`, `nombreEntidad`) VALUES
(1, 20, 'OAXACA');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `id_rol` int(11) NOT NULL,
  `rol` varchar(40) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`id_rol`, `rol`) VALUES
(0, 'administrador'),
(1, 'auxiliar'),
(2, 'director'),
(3, 'docente'),
(4, 'alumno');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` varchar(40) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(60) DEFAULT NULL,
  `apellido_paterno` varchar(60) DEFAULT NULL,
  `apellido_materno` varchar(60) DEFAULT NULL,
  `curp` varchar(50) DEFAULT NULL,
  `email` varchar(40) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `id_rol` int(11) NOT NULL,
  `id_plantel` int(11) NOT NULL,
  `estado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `id_usuario`, `nombre`, `apellido_paterno`, `apellido_materno`, `curp`, `email`, `password`, `id_rol`, `id_plantel`, `estado`) VALUES
('AAAY941001MOCLLR003', 80, 'YURI NECTANDRA', 'ALMARAZ', 'ALMARAZZ', 'AAAY941001MOCLLR00', 'rbi59760@qiaua.com', '$2y$10$0XPMSMP6/Vf66nttaX2b3OB3t2zLBmVNIpSM.T4esRJsUTJV84e5G', 3, 1, 0),
('CUGA941001MOCRRL043', 81, 'ALEJANDRA GLORIA', 'CRUZ', 'GARCIA', 'CUGA941001MOCRRL04', 'xbm31138@qiaua.com', '$2y$10$YBG8U0ww0czP775EybV3fOlQkU6YG40hWTBSg9goOY/Pv7QiLK7AK', 3, 1, 1),
('DIRO041001HOCZQM07', 5, 'OMAR', 'DIAZ', 'RIQUELME', 'DIRO041001HOCZQM07', '2015001002', '$2y$10$yQMONSNvRgbKMBWU22UR2OooG7ioccV68j.vo83cyxOgRTfbXhZqG', 4, 1, 1),
('FIEB041001MOCGSL00', 4, 'BELEN', 'FIGUEROA', 'ESPINOZA', 'FIEB041001MOCGSL00', '2015001004', '$2y$10$c6gb/tkICDFb6Vv2Z4nwPevx8tJxVOvb/qIjVQlYJgUXior.Vr9XS', 4, 1, 1),
('GARL961001MOCRYT01', 12, 'LETICIA', 'GARCIA', 'REYES', 'GARL961001MOCRYT01', 'mfo24769@miauj.com', '$2y$10$JnAd0XJWBNnRvNMOAj4quefqiPG6SBLZQwXyiLgHVwlhOk5WuU9DG', 1, 0, 1),
('LOHH941001HOCPRG083', 84, 'HUGO IVAN', 'LOPEZ', 'HERNANDEZ', 'LOHH941001HOCPRG08', 'cdn77216@pdold.com', '$2y$10$TrYi6q39VBHI8onIbvyul./.e/a6ZphCOhO6UWeq/QfqrP4KmZsqy', 3, 2, 1),
('MESC920206MOCNNL013', 83, 'CELESTE', 'MÉNDEZ', 'SÁNCHEZ', 'MESC920206MOCNNL01', 'xod22132@pdold.com', '$2y$10$ISBqyReQkCQiTg4PM85oJuvWFSL/Bgb/8FhEnuwfyB1MZ8vhLv8D.', 3, 2, 1),
('MESM941001HOCNNR092', 86, 'MARCOS ANTONIO', 'MÉNDEZ', 'SÁNCHEZZ', 'MESM941001HOCNNR09', 'amn66733@pdold.com', '$2y$10$rXKCque5wZrgqJNd1S647Oqn71tpeIfwJvq5YjAh3en81HmhUvI5q', 2, 2, 1),
('MESM941001HOCNNR093', 85, 'MARCOS ANTONIO', 'MÉNDEZ', 'SÁNCHEZ', 'MESM941001HOCNNR09', 'gfl13398@pdold.com', '$2y$10$GoiSv560okGy16S43CTBbOAFPAivwDm5Vkj29VmuUSysjvuKUC3bK', 3, 2, 1),
('ROSA041001MOCJTN07', 3, 'ANTONIA', 'ROJAS', 'SOTO', 'ROSA041001MOCJTN07', 'naj22735@pdold.com', '$2y$10$j/mziNdadtoUw5XWBo7Ki.nroToxoLT3AGcRiSXOUpGbrIvg.cvdy', 4, 1, 1),
('ROVE930928HOCDZM05', 0, 'EMMANUEL', 'RODRÍGUEZ', 'VÁZQUEZ', 'ROVE930928HOCDZM05', 'admin', '$2y$10$P0rJ/3GeA8OozLSz9S464ePE8BBMJSWoQMXlEQEtOO26YXyvWniY6', 0, 0, 1),
('SAVE920509HOCNLS092', 82, 'ESTEBAN', 'SANCHEZ', 'VILLEGAS', 'SAVE920509HOCNLS09', 'villegas09sanz@gmail.com', '$2y$10$IJYndzcA./7YLu8FgHK28.ZGiEzxer3kPDSlcf6XeVX88ya1MgX8S', 2, 1, 1),
('SAVE920509HOCNLS093', 79, 'ESTEBAN', 'SANCHEZ', 'VILLEGAS', 'SAVE920509HOCNLS09', 'villegas09sanz@gmail.com', '$2y$10$Dxg.6w0PDuHngvLQ6uU1/evYcWLdSpFFJn6aB8zdmeOd8przbcC7m', 3, 1, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `alumno`
--
ALTER TABLE `alumno`
  ADD PRIMARY KEY (`id_alumno`),
  ADD KEY `id_rol` (`id_rol`);

--
-- Indices de la tabla `entidad`
--
ALTER TABLE `entidad`
  ADD PRIMARY KEY (`idEntidad`),
  ADD UNIQUE KEY `cveEntidad` (`cveEntidad`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`,`id_rol`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `alumno`
--
ALTER TABLE `alumno`
  MODIFY `id_alumno` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
--
-- AUTO_INCREMENT de la tabla `entidad`
--
ALTER TABLE `entidad`
  MODIFY `idEntidad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `alumno`
--
ALTER TABLE `alumno`
  ADD CONSTRAINT `alumno_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `rol` (`id_rol`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
