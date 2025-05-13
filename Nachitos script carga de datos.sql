-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 13-05-2025 a las 21:36:44
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
-- Base de datos: `nachitos`
--

--
-- Volcado de datos para la tabla `comentarios`
--

INSERT INTO `comentarios` (`comentario_id`, `usuario_id`, `producto_id`, `calificacion`, `comentario`, `fecha_comentario`) VALUES
(1, 2, 3, 5, 'Muy buen horno, cumplió mis expectativas.', '2025-05-12 21:06:07');

--
-- Volcado de datos para la tabla `detalle_pedido`
--

INSERT INTO `detalle_pedido` (`detalle_id`, `pedido_id`, `producto_id`, `cantidad`, `precio_unitario`) VALUES
(1, 1, 3, 1, 130000.00),
(2, 2, 1, 3, 80000.00),
(3, 2, 2, 1, 100000.00),
(4, 2, 3, 1, 130000.00),
(5, 3, 1, 3, 80000.00),
(6, 3, 2, 2, 100000.00),
(7, 3, 3, 1, 130000.00),
(8, 4, 1, 1, 80000.00),
(9, 4, 2, 1, 100000.00),
(10, 4, 3, 1, 130000.00),
(11, 4, 1, 1, 80000.00),
(12, 4, 2, 1, 100000.00),
(13, 4, 3, 1, 130000.00),
(14, 5, 1, 1, 80000.00),
(15, 5, 1, 1, 80000.00);

--
-- Volcado de datos para la tabla `envios`
--

INSERT INTO `envios` (`envio_id`, `pedido_id`, `ciudad_destino`, `costo_envio`) VALUES
(1, 1, 'Santiago', 8000.00);

--
-- Volcado de datos para la tabla `notificaciones`
--

INSERT INTO `notificaciones` (`notificacion_id`, `pedido_id`, `tipo`, `fecha_envio`, `estado`) VALUES
(1, 1, 'cliente', '2025-05-12 21:06:07', 'enviado');

--
-- Volcado de datos para la tabla `pagos`
--

INSERT INTO `pagos` (`pago_id`, `pedido_id`, `monto`, `metodo_pago`, `fecha_pago`) VALUES
(1, 1, 130000.00, 'transferencia', '2025-05-12 21:06:07');

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`pedido_id`, `usuario_id`, `fecha_pedido`, `descripcion_cliente`, `estado`, `total`) VALUES
(1, 2, '2025-05-12 21:06:07', 'Favor entregar con pala incluida.', 'en proceso', 130000.00),
(2, NULL, '2025-05-13 00:58:20', 'Nombre: DonAndriuw\nCorreo: andres.ignacio204@gmail.com\nTeléfono: +56933300204\nMensaje: ', 'pendiente', 470000.00),
(3, NULL, '2025-05-13 01:04:40', 'Nombre: DonAndriuw\nCorreo: andres.ignacio204@gmail.com\nTeléfono: +56933300204\nMensaje: adswsawd', 'pendiente', 570000.00),
(4, NULL, '2025-05-13 01:08:48', 'Nombre: DonAndriuw\nCorreo: andres.ignacio204@gmail.com\nTeléfono: +56933300204\nMensaje: asdwasd', 'pendiente', 310000.00),
(5, NULL, '2025-05-13 01:13:41', 'Nombre: Andres Ignacio\nCorreo: andres.ignacio204@gmail.com\nTeléfono: +56933300204\nMensaje: Buenas tardes, me gustaría saber si se puede cambiar el color del horno, me gustaria uno azul en caso de ser posible, saludos', 'pendiente', 80000.00);

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`producto_id`, `nombre_producto`, `descripcion`, `precio`, `stock`, `imagen_url`) VALUES
(1, 'Horno Común', 'Horno artesanal común, ideal para 2-3 personas.', 80000.00, 10, 'horno_comun.jpg'),
(2, 'Horno Especial', 'Diseño reforzado para uso frecuente.', 100000.00, 5, 'horno_especial.jpg'),
(3, 'Horno Pizzero', 'Ideal para pizzas, con ladrillo refractario.', 130000.00, 2, 'horno_pizzero.jpg');

--
-- Volcado de datos para la tabla `reservas`
--

INSERT INTO `reservas` (`reserva_id`, `usuario_id`, `pedido_id`, `fecha_inicio`, `fecha_termino`, `comentario`) VALUES
(1, 2, 1, '2025-05-10', '2025-05-19 19:00:00', 'Se solicita entrega matutina.');

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`usuario_id`, `nombre`, `correo_electronico`, `telefono`, `contrasena`, `perfil`) VALUES
(2, 'Juan Perez', 'juan@gmail.com', '911112223', 'cliente123', 'cliente'),
(6, 'Administrador', 'administrador@nachitos.cl', NULL, '$2y$10$m6TJyUjrit7S1OEGX8vvSOE.fxchbQoohz/ObjmLPwhOhB50sDcI2', 'administrador');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
