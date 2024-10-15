INSERT INTO tipo_iva_empresa (`id`, `nombre`, `descripcion`, `letra_factura`, `id_remoto`) VALUES
('1', 'INSCRIPTO', 'Descripción del Tipo IVA 1', 'A', '1'),
('2', 'MONOTRIBUTO', 'Descripción del Tipo IVA 2', 'C', '2'),
('3', 'EXENTO', NULL, 'B', '3'),
('4', 'NO FISCAL', NULL, '-', '4');
INSERT INTO empresas (`id`, `nombre`, `logo`, `direccion`, `telefono`, `tipo_iva`, `cuit`, `responsable`, `email`, `rubro_id`, `fecha_inicio_actividades`, `descripcion`, `razon_social`, `inicio_actividad`, `iibb`, `cert`, `key`, `fiscal`, `id_remoto`) VALUES
('42', 'SR', NULL, ' GUATEMALA 1448  CP: 2000  Localidad: ROSARIO SUD Provincia: SANTA FE', '3415666274', '1', '20215239226', 'Guastamacchia Marcelo Fabia', 'admin@marcelo_sr.com.ar', NULL, '2024-09-01', 'Guastamacchia Marcelo Fabia', 'SR', '2024-09-01', '20215239226', '-----BEGIN CERTIFICATE-----
MIIDRTCCAi2gAwIBAgIIP/W32EjZHaUwDQYJKoZIhvcNAQENBQAwMzEVMBMGA1UEAwwMQ29tcHV0
YWRvcmVzMQ0wCwYDVQQKDARBRklQMQswCQYDVQQGEwJBUjAeFw0yNDA5MDUxNTA1MzBaFw0yNjA5
MDUxNTA1MzBaMDAxEzARBgNVBAMMCkdVQVNUQU5FWE8xGTAXBgNVBAUTEENVSVQgMjAyMTUyMzky
MjYwggEiMA0GCSqGSIb3DQEBAQUAA4IBDwAwggEKAoIBAQDD7NaPvRZ1t5D0P8gPrryfKGsfjfdi
PV/qOz+qIqgWVbPUF9JuIkTDI1BE3/cZ+MySRGLFNT0+388V59lK85kwDTZGaTvkPDlwuSBL2Mm2
7q5hAX51CaY6KBlcOmpKWJ0+jUIoi7mcC8uQ/bkroghDNI4AX2zVbwHlNGmX3tzRdgRjCAHHuG+E
cPppTJz8ZCvCR7/mdbahnXZ4DdivNMeXhVx8DBRlBzegx0eOFqAjXGrTnZ+KgO2B/eidT1Xzii76
VfDuU4X7MJrUN/VfCXjaImdQYwq80geH8pB6hT/y1qnhYlspN/cJSJPei4xE+d5d1JTKvz24EbtZ
mPzknKnJAgMBAAGjYDBeMAwGA1UdEwEB/wQCMAAwHwYDVR0jBBgwFoAUKw0vyN9h/QjJThHQNZME
bY5b0G4wHQYDVR0OBBYEFCjVbySkhMI66bLkFyrRuC+OAQNGMA4GA1UdDwEB/wQEAwIF4DANBgkq
hkiG9w0BAQ0FAAOCAQEAnQTqHZh8mT8zFn1Nu8pohEU5FFUMHKtypclm2UT+3YEti3rLggYSRgJb
u03IUugQ3CkY5PpA/AmbYwNLk9W6zwJ1KohgpY/RBdOIW7N9bsABMAmrOrapm8DkqcMKJ4WoQOPQ
pRXyn6sLyKwIi7SpP5unYOTmMh/EVAPBKgq8CCeoPG6n7kbjwlCXG5VzaanBBZpRuoP8Z2aE04KT
8rxLzGkVE3RlqpzacrJS6uy8naP7NxFoEF2FOyq2uJkBF3/F0LN/ftUxt3+REjVWZFNdSRS1AMjR
ZmDHlF1yCgSiWR0Wx/V1ZN8/X6EDBQ9W/zCXgYasWA3RtjKxGKAV0ZsZUA==
-----END CERTIFICATE-----', '-----BEGIN PRIVATE KEY-----
MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDD7NaPvRZ1t5D0
P8gPrryfKGsfjfdiPV/qOz+qIqgWVbPUF9JuIkTDI1BE3/cZ+MySRGLFNT0+388V
59lK85kwDTZGaTvkPDlwuSBL2Mm27q5hAX51CaY6KBlcOmpKWJ0+jUIoi7mcC8uQ
/bkroghDNI4AX2zVbwHlNGmX3tzRdgRjCAHHuG+EcPppTJz8ZCvCR7/mdbahnXZ4
DdivNMeXhVx8DBRlBzegx0eOFqAjXGrTnZ+KgO2B/eidT1Xzii76VfDuU4X7MJrU
N/VfCXjaImdQYwq80geH8pB6hT/y1qnhYlspN/cJSJPei4xE+d5d1JTKvz24EbtZ
mPzknKnJAgMBAAECggEAA8qN86sY3P9H2UobVh5Z/QnHBOMg4drxLl2mI5CByH4x
Ds4aiXy1RZdl2BIDZz0WwaUmyiJ3xG3dRShpiSyXOW76rrhzlbzf7wFOEYRTkZbN
yLJtiYM9ybX5Q6fHuGzoN2Emt2p4wCnERH0nEPk/iAQOKWNZ/klPHpR55JvwkZxa
fQIPV0o2JWLglWR6fliUs2ibuqA2zGhUP2AN9sjAZROm+KdDX9MN2LfcRPYGde5T
JgWIfBZJ3aXhToyVaY2TIFcMN1wQD4uOJmMPHcCtq02ccjhdYIJ3ZsNPwwY6P63B
MdjlzfLdY4qADW9CgG7WxkDhsDPuBDM96zo7r83pxwKBgQDzlfRd01FUMruh5VgL
oDuvPCbpA7nnYCa2jfwvMm3HSA7/9yTn9tOXQy1RvplyAwnyUgaBxalzT+KGfaDT
YQdrCFzNGFkls43H1Fqy6XATq8RtqPOHnZhXL2I71WlSoT5eIUVlUcO3UJnldQws
TZGQzYgGvGgSP3D/h+Zp70H6qwKBgQDN6Q8hAerNv8m9h1dFDmy1LuSwaqJ9nCSx
gcca3ir9YmUGXIy7XiJVFPR/7Evfk1QJNT+J6UpqfyL2YAglfAFd2gzx9b/RPGAa
CM4edtoTU/4BOXToyXa82VhXZz0/Qv4YfU92qcxUOtdWpahvEUdKJZ1qAX+ITa3d
VcnOVUmtWwKBgDFTQUuqmgJ5z4MGJSEdjlh6zspKpd+TmeNHtzR7er1q+xacHk4W
rlwoD0pJVVtAAEigkY6/zwN5vr5LPEDxmgXziI9DifHXfTNk7gTivn5NPxaRqtbg
c9Pb1YGWQqCn5DA9fob42rAJU1CithdWkc6HwC7VAiIH1ML56w+ykmLHAoGBAKqv
xQDBjBheISdg53CxEl/mrV+oJWYmZyxVveyBufUs+T2avnYbBC6vHjZkdEiy4fh4
EWGK50r8dMjytg4Miz13IT/5TLZva+Jo5iPOtdtLxsTREh5d0nWdXWOmitjD+cnn
JlXhTyWLWTHURJk9I3FNWP2knEkqyzMsiAroHBj9AoGAM5MrPlUgZmt85ErtSvoK
IqKcW7xJQYQ1pYMo72Cslv7ZO7O+SS2yCuDL+84XEY/EttoSnWjRHzdkBB9JXTaZ
Aua1ZuMqx2BvIoOHr6S6Ly67Q64RiKhrmwI231Jz2iEEAuBSbn9bxJC8bcB/8HQ1
hycDDrzaIP48M1rc3evI4Tk=
-----END PRIVATE KEY-----
', '1', '42');
INSERT INTO moneda (`id`, `simbolo`, `nombre`, `cotizacion`, `empresa_id`, `id_remoto`) VALUES
('43', '$', 'Pesos argentinos', '1', '42', '43'),
('44', 'USD', 'Dólar estadounidense', '886', '42', '44');
INSERT INTO agrupacion (`id`, `numero`, `nombre`, `color`, `icono`, `empresa_id`, `id_remoto`) VALUES
('30', '1', 'GENERAL', '#0098DA', 'fas fa-boxes', '42', '30');
INSERT INTO categoria (`id`, `nombre`, `se_imprime`, `empresa_id`, `id_remoto`) VALUES
('28', 'CATEGORIA', '1', '42', '28');
INSERT INTO puntos_venta (`id`, `numero`, `descripcion`, `empresa_id`, `id_remoto`) VALUES
('38', '7', 'Punto de venta 7', '42', '38');
INSERT INTO sucursales (`id`, `empresa_id`, `nombre`, `direccion`, `telefono`, `email`, `contacto_nombre`, `contacto_telefono`, `contacto_email`, `referente_nombre`, `referente_telefono`, `referente_email`, `id_remoto`) VALUES
('31', '42', 'Central', ' GUATEMALA 1448  CP: 2000  Localidad: ROSARIO SUD Provincia: SANTA FE', '3415666274', 'admin@marcelo_sr.com.ar', NULL, NULL, NULL, NULL, NULL, NULL, '31');
INSERT INTO roles (`id`, `nombre`, `descripcion`, `empresa_id`, `id_remoto`) VALUES
('56', 'cajero', 'Cajero/Vendedor', '42', '56'),
('57', 'admin', 'Administrador', '42', '57');
INSERT INTO usuarios (`id`, `nombre_usuario`, `password`, `nombre_completo`, `rol_id`, `activo`, `empresa_id`, `sucursal_id`, `punto_venta_id`, `venta_rapida`, `lista_precios`, `imprimir`, `tipo_comprobante_imprimir`, `id_remoto`) VALUES
('55', 'admin@marcelo_sr.com.ar', 'a989ed30b315bc5567cb0f9a1c66758f', 'Administrador', '57', '1', '42', '31', '38', '0', '1', '1', '1', '55');
INSERT INTO pais (`id`, `nombre`, `id_remoto`) VALUES
('1', 'Argentina', '1'),
('2', 'Uruguay', '2'),
('3', 'Brasil', '3'),
('4', 'Chile', '4');
INSERT INTO provincias (`id`, `nombre`, `pais_id`, `id_remoto`) VALUES
('2', 'CORDOBA', '1', '2'),
('3', 'Buenos Aires', '1', '3'),
('4', 'CABA', '1', '4'),
('5', 'Catamarca', '1', '5'),
('6', 'Chaco', '1', '6'),
('7', 'Chubut', '1', '7'),
('9', 'Corrientes', '1', '9'),
('10', 'Entre Ríos', '1', '10'),
('11', 'Formosa', '1', '11'),
('12', 'Jujuy', '1', '12'),
('13', 'La Pampa', '1', '13'),
('14', 'La Rioja', '1', '14'),
('15', 'Mendoza', '1', '15'),
('16', 'Misiones', '1', '16'),
('17', 'Neuquén', '1', '17'),
('18', 'Río Negro', '1', '18'),
('19', 'Salta', '1', '19'),
('20', 'San Juan', '1', '20'),
('21', 'San Luis', '1', '21'),
('22', 'Santa Cruz', '1', '22'),
('23', 'Santa Fe', '1', '23'),
('24', 'Santiago del Estero', '1', '24'),
('25', 'Tierra del Fuego, Antártida e Islas del Atlántico Sur', '1', '25'),
('26', 'Tucumán', '1', '26');
INSERT INTO localidad (`id`, `nombre`, `provincia_id`, `codigo_postal`, `id_remoto`) VALUES
('1', 'Córdoba', '2', '5000', '1'),
('6', 'Río Cuarto', '2', '5800', '6'),
('7', 'Villa María', '2', '5900', '7'),
('8', 'Carlos Paz', '2', '5152', '8'),
('9', 'La Plata', '3', '1900', '9'),
('10', 'Mar del Plata', '3', '7600', '10'),
('11', 'Bahía Blanca', '3', '8000', '11'),
('12', 'Tandil', '3', '7000', '12'),
('13', 'Ciudad Autónoma de Buenos Aires', '4', 'C1000', '13'),
('14', 'Avellaneda', '4', '1870', '14'),
('15', 'Lomas de Zamora', '4', '1832', '15'),
('16', 'Quilmes', '4', '1879', '16'),
('17', 'San Fernando del Valle de Catamarca', '5', '4700', '17'),
('18', 'San Fernando', '5', '4706', '18'),
('19', 'Valle Viejo', '5', '4701', '19'),
('20', 'Santa María', '5', '4703', '20'),
('21', 'Resistencia', '6', '3500', '21'),
('22', 'Barranqueras', '6', '3503', '22'),
('23', 'Fontana', '6', '3501', '23'),
('24', 'Presidencia Roque Sáenz Peña', '6', '3700', '24'),
('25', 'Rawson', '7', '9103', '25'),
('26', 'Comodoro Rivadavia', '7', '9000', '26'),
('27', 'Esquel', '7', '9200', '27'),
('28', 'Puerto Madryn', '7', '9120', '28'),
('29', 'Corrientes', '9', '3400', '29'),
('30', 'Goya', '9', '3450', '30'),
('31', 'Mercedes', '9', '3470', '31'),
('32', 'Curuzú Cuatiá', '9', '3460', '32'),
('33', 'Paraná', '10', '3100', '33'),
('34', 'Concordia', '10', '3200', '34'),
('35', 'Gualeguaychú', '10', '2820', '35'),
('36', 'Colón', '10', '3280', '36'),
('37', 'Formosa', '11', '3600', '37'),
('38', 'Clorinda', '11', '3610', '38'),
('39', 'Pirané', '11', '3617', '39'),
('40', 'Ingeniero Juárez', '11', '3632', '40'),
('41', 'San Salvador de Jujuy', '12', '4600', '41'),
('42', 'San Pedro', '12', '4500', '42'),
('43', 'Libertador General San Martín', '12', '4512', '43'),
('44', 'Palpalá', '12', '4612', '44'),
('45', 'Santa Rosa', '13', '6300', '45'),
('46', 'General Pico', '13', '6360', '46'),
('47', 'Toay', '13', '6301', '47'),
('48', 'Realicó', '13', '6313', '48'),
('49', 'La Rioja', '14', '5300', '49'),
('50', 'Chilecito', '14', '5360', '50'),
('51', 'Aimogasta', '14', '5307', '51'),
('52', 'Chamical', '14', '5303', '52'),
('53', 'Mendoza', '15', '5500', '53'),
('54', 'San Rafael', '15', '5600', '54'),
('55', 'Godoy Cruz', '15', '5501', '55'),
('56', 'Luján de Cuyo', '15', '5507', '56'),
('57', 'Posadas', '16', '3300', '57'),
('58', 'Eldorado', '16', '3380', '58'),
('59', 'Oberá', '16', '3360', '59'),
('60', 'Puerto Iguazú', '16', '3370', '60'),
('61', 'Neuquén', '17', '8300', '61'),
('62', 'Centenario', '17', '8316', '62'),
('63', 'Cutral Có', '17', '8322', '63'),
('64', 'Plottier', '17', '8316', '64'),
('65', 'Viedma', '18', '8500', '65'),
('66', 'General Roca', '18', '8332', '66'),
('67', 'San Antonio Oeste', '18', '8520', '67'),
('68', 'Cipolletti', '18', '8324', '68'),
('69', 'Salta', '19', '4400', '69'),
('70', 'San Ramón de la Nueva Orán', '19', '4530', '70'),
('71', 'Tartagal', '19', '4560', '71'),
('72', 'Cafayate', '19', '4427', '72'),
('73', 'San Juan', '20', '5400', '73'),
('74', 'Rawson', '20', '5439', '74'),
('75', 'Caucete', '20', '5405', '75'),
('76', 'Pocito', '20', '5442', '76'),
('77', 'San Luis', '21', '5700', '77'),
('78', 'Villa Mercedes', '21', '5730', '78'),
('79', 'Merlo', '21', '5881', '79'),
('80', 'La Punta', '21', '5711', '80'),
('81', 'Río Gallegos', '22', '9400', '81'),
('82', 'Caleta Olivia', '22', '9011', '82'),
('83', 'Pico Truncado', '22', '9011', '83'),
('84', 'Puerto Deseado', '22', '9050', '84'),
('85', 'Rosario', '23', '2000', '85'),
('86', 'Santa Fe', '23', '3000', '86'),
('87', 'Venado Tuerto', '23', '2600', '87'),
('88', 'Rafaela', '23', '2300', '88'),
('89', 'Santiago del Estero', '24', '4200', '89'),
('90', 'La Banda', '24', '4206', '90'),
('91', 'Termas de Río Hondo', '24', '4220', '91'),
('92', 'Fernández', '24', '4301', '92'),
('93', 'Ushuaia', '25', '9410', '93'),
('94', 'Río Grande', '25', '9420', '94'),
('95', 'Tolhuin', '25', '9412', '95'),
('96', 'San Miguel de Tucumán', '26', '4000', '96'),
('97', 'Tafí Viejo', '26', '4103', '97'),
('98', 'Yerba Buena', '26', '4107', '98'),
('99', 'Concepción', '26', '4126', '99');
INSERT INTO tipo_documento (`id`, `nombre`, `empresa_id`, `id_remoto`) VALUES
('39', 'DNI', '42', '39'),
('40', 'L.E.', '42', '40'),
('41', 'PASAPORTE', '42', '41');
INSERT INTO tipo_iva (`id`, `nombre`, `descripcion`, `porcentaje`, `letra_factura`, `empresa_id`, `id_remoto`) VALUES
('41', 'INSCRIPTO', NULL, NULL, 'A', '42', '41'),
('42', 'MONOTRIBUTO', NULL, NULL, 'C', '42', '42'),
('43', 'CONSUMIDOR FINAL', NULL, NULL, 'B', '42', '43'),
('44', 'EXENTO', NULL, NULL, 'B', '42', '44');
INSERT INTO vendedores (`id`, `nombre`, `direccion`, `telefono`, `porcentaje_comision`, `fecha_ingreso`, `empresa_id`, `sucursal_id`, `id_remoto`) VALUES
('46', 'VENDEROR', NULL, NULL, '0', '2024-09-10', '42', '31', '46');
INSERT INTO codigos_barras (`id`, `inicio`, `id_long`, `payload_type`, `payload_int`, `long`, `empresa_id`, `id_remoto`) VALUES
('20', '2', '5', 'P', '5', '13', '42', '20');
INSERT INTO tasa_iva (`id`, `nombre`, `tasa`, `empresa_id`, `id_remoto`) VALUES
('64', 'IVA 21%', '0.21', '42', '64'),
('65', 'IVA 10.5%', '0.105', '42', '65'),
('66', 'Sin IVA', '0', '42', '66');
INSERT INTO familias (`id`, `numero`, `nombre`, `empresa_id`, `id_remoto`) VALUES
('39', '1', 'GENERAL', '42', '39');
INSERT INTO proveedores (`id`, `razon_social`, `direccion`, `localidad_id`, `telefono`, `email`, `tipo_iva_id`, `cuit`, `categoria_id`, `subcategoria_id`, `fecha_ultima_compra`, `fecha_ultimo_pago`, `saldo_actual`, `empresa_id`, `id_remoto`) VALUES
('54', 'Proveedor por defecto', 'Calle Ejemplo 123', '1', '123456789', 'proveedor@example.com', NULL, '11111111', NULL, NULL, NULL, NULL, '0', '42', '54');
INSERT INTO tipo (`id`, `numero`, `nombre`, `empresa_id`, `id_remoto`) VALUES
('68', '1', 'Producto', '42', '68'),
('69', '2', 'Servicio', '42', '69'),
('70', '3', 'Producto y servicio', '42', '70');
INSERT INTO unidad (`id`, `nombre`, `simbolo`, `empresa_id`, `id_remoto`) VALUES
('147', 'Kilogramo', 'KG', '42', '147'),
('148', 'Unidad', 'UN.', '42', '148'),
('149', 'Metro', 'M', '42', '149'),
('150', 'Metro Cuadrado', 'M2', '42', '150'),
('151', 'Metro Cubico', 'M3', '42', '151'),
('152', 'Litro', 'LTS.', '42', '152'),
('153', 'Gramo', 'GR.', '42', '153');
INSERT INTO productos (`id`, `codigo`, `descripcion`, `descripcion_ampliada`, `familia_id`, `subfamilia_id`, `agrupacion_id`, `marca_id`, `codigo_barra`, `proveedor_id`, `fecha_alta`, `fecha_actualizacion`, `articulo_activado`, `tipo_id`, `producto_balanza`, `precio_costo`, `moneda_id`, `tasa_iva`, `tasa_iva_id`, `incluye_iva`, `impuesto_interno`, `precio1`, `precio2`, `precio3`, `fraccionado`, `rg5329_23`, `activo`, `texto_panel`, `stock`, `stock_minimo`, `stock_pedido`, `iibb`, `codigo_barra2`, `oferta`, `margen_ganancia`, `publicado_web`, `unidad_id`, `empresa_id`, `favorito`, `tipo_impuesto_interno`, `id_remoto`) VALUES
('55172', '1', 'GENERAL (21%)', NULL, NULL, NULL, '30', NULL, '1', NULL, '2024-09-10', '2024-09-10 15:47:36', NULL, '68', '0', '0', NULL, '0', '64', NULL, NULL, '0', '0', '0', NULL, NULL, '1', NULL, '0', NULL, NULL, NULL, NULL, NULL, '0', NULL, '148', '42', '1', NULL, '55172'),
('55173', '2', 'GENERAL (10,5%)', NULL, NULL, NULL, '30', NULL, '2', NULL, '2024-09-10', '2024-09-10 15:49:06', NULL, '68', '0', '0', NULL, '0', '65', NULL, '0', '0', '0', '0', '0', '0', '0', NULL, '0', NULL, NULL, '0', NULL, '0', '0', '0', NULL, '42', '1', NULL, '55173');
INSERT INTO forma_pago (`id`, `nombre`, `porcentaje`, `activo`, `empresa_id`, `id_remoto`) VALUES
('37', 'EFECTIVO', '0', '1', '42', '37');
INSERT INTO comprobantes (`id`, `numero`, `punto_venta`, `tipo_factura`, `tipo_documento`, `numero_de_documento`, `cuotas`, `cliente_id`, `remito`, `persona`, `provincia_id`, `fecha`, `hora`, `fecha_proceso`, `letra`, `numero_factura`, `prefijo_factura`, `operacion_negocio_id`, `retencion_iva`, `retencion_iibb`, `retencion_ganancias`, `porcentaje_ganancias`, `porcentaje_iibb`, `porcentaje_iva`, `no_gravado`, `importe_iva`, `total`, `condicion_venta_id`, `descripcion_flete`, `vendedor_id`, `recibo`, `observaciones_1`, `observaciones_2`, `observaciones_3`, `observaciones_4`, `descuento`, `descuento_1`, `descuento_2`, `descuento_3`, `descuento_4`, `iva_2`, `impresa`, `cancelado`, `nombre_cliente`, `direccion_cliente`, `localidad_cliente`, `garantia`, `concepto`, `notas`, `linea_pago_ultima`, `relacion_tk`, `total_iibb`, `importe_iibb`, `provincia_categoria_iibb_id`, `importe_retenciones`, `provincia_iva_proveedor_id`, `ganancias_proveedor_id`, `importe_ganancias`, `numero_iibb`, `numero_ganancias`, `ganancias_proveedor`, `cae`, `fecha_vencimiento`, `forma_pago_id`, `remito_cliente`, `texto_dolares`, `comprobante_final`, `numero_guia_1`, `numero_guia_2`, `numero_guia_3`, `tipo_alicuota_1`, `tipo_alicuota_2`, `tipo_alicuota_3`, `importe_iva_21`, `importe_iva_105`, `importe_iva_0`, `no_gravado_iva_21`, `no_gravado_iva_105`, `no_gravado_iva_0`, `importe_impuesto_interno`, `direccion_entrega`, `fecha_entrega`, `hora_entrega`, `total_pagado`, `tipo_comprobante_id`, `empresa_id`, `usuario_id`, `cierre_caja_id`, `fecha_baja`, `motivo_baja`, `url_pdf`, `sucursal_id`, `qr`, `comprobante_id_baja`, `subido`, `id_remoto`) VALUES
('24048', NULL, '7', '6', '96', '111111', NULL, '1', NULL, NULL, NULL, '2024-09-10', '16:09:48', '2024-09-10', 'C', '1', '7', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1.74', '10', NULL, NULL, '46', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '74376567251365', '2024-09-20', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1.74', '0', '0', '8', '0', '0', '0', NULL, NULL, NULL, '10', '1', '42', '55', NULL, '2024-09-10 00:00:00.000000', NULL, 'https://storage.googleapis.com/a2p-v2-storage/f6de960f-a03c-4a08-beac-d7eb17c0002c', '31', 'https://www.afip.gob.ar/fe/qr/?p=eyJ2ZXIiOjEsImZlY2hhIjoiMjAyNC0wOS0xMCIsImN1aXQiOiIyMDIxNTIzOTIyNiIsInB0b1Z0YSI6IjciLCJ0aXBvQ21wIjoiNiIsIm5yb0NtcCI6MSwiaW1wb3J0ZSI6MS43NCwibW9uZWRhIjoiUEVTIiwiY3R6IjoxLCJ0aXBvRG9jUmVjIjoiOTYiLCJucm9Eb2NSZWMiOiIxMTExMTEiLCJ0aXBvQ29kQXV0IjoiRSIsImNvZEF1dCI6Ijc0Mzc2NTY3MjUxMzY1In0=', NULL, '0', '24048'),
('24049', NULL, '7', '8', '96', '111111', NULL, '1', NULL, NULL, NULL, '2024-09-10', '16:10:14', '2024-09-10', 'C', '1', '7', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '10', NULL, NULL, '46', NULL, NULL, '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '74376567324010', '2024-09-20', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1.74', '0', '0', '8', '0', '0', NULL, NULL, NULL, NULL, '10', '4', '42', '55', NULL, NULL, NULL, 'https://storage.googleapis.com/a2p-v2-storage/48d35bab-8017-429b-8019-1a5d6d578446', '31', 'https://www.afip.gob.ar/fe/qr/?p=eyJ2ZXIiOjEsImZlY2hhIjoiMjAyNC0wOS0xMCIsImN1aXQiOiIyMDIxNTIzOTIyNiIsInB0b1Z0YSI6IjciLCJ0aXBvQ21wIjo4LCJucm9DbXAiOjEsImltcG9ydGUiOjEyLjEsIm1vbmVkYSI6IlBFUyIsImN0eiI6MSwidGlwb0RvY1JlYyI6Ijk2IiwibnJvRG9jUmVjIjoiMTExMTExIiwidGlwb0NvZEF1dCI6IkUiLCJjb2RBdXQiOiI3NDM3NjU2NzMyNDAxMCJ9', '24048', '0', '24049');
INSERT INTO funciones (`id`, `nombre`, `descripcion`, `empresa_id`, `id_remoto`) VALUES
('1027', 'comprobantes', 'Comprobantes', '42', '1027'),
('1028', 'productos', 'Productos', '42', '1028'),
('1029', 'clientes', 'Clientes', '42', '1029'),
('1030', 'agrupaciones', 'Agrupaciones', '42', '1030'),
('1031', 'categorias', 'Categorias', '42', '1031'),
('1032', 'categoria_iibb', 'Categoria IIBB', '42', '1032'),
('1033', 'cierre_cajas', 'Cierres Cajas', '42', '1033'),
('1034', 'condiciones_venta', 'Condiciones Venta', '42', '1034'),
('1035', 'configuraciones', 'Configuraciones', '42', '1035'),
('1036', 'familias', 'Familias', '42', '1036'),
('1037', 'formas_pago', 'Formas de pago', '42', '1037'),
('1038', 'ganancias_clientes', 'Ganancias de clientes', '42', '1038'),
('1039', 'ganancias_prooveedor', 'Ganancias prooveedor', '42', '1039'),
('1040', 'listas_pecios', 'Listas de pecios', '42', '1040'),
('1041', 'localidades', 'Localidades', '42', '1041'),
('1042', 'marcas', 'Marcas', '42', '1042'),
('1043', 'monedas', 'Monedas', '42', '1043'),
('1044', 'operacion_negocio', 'Operacion Negocio', '42', '1044'),
('1045', 'pais', 'Pais', '42', '1045'),
('1046', 'presupuestos', 'presupuestos', '42', '1046'),
('1047', 'productos_stock', 'Productos Stock', '42', '1047'),
('1048', 'promociones', 'Promociones', '42', '1048'),
('1049', 'proveedores', 'proveedores', '42', '1049'),
('1050', 'proveedores', 'Proveedores', '42', '1050'),
('1051', 'provincia_categoria_iibb', 'Provincia Categoria IIBB', '42', '1051'),
('1052', 'provincia_iva_proveedor', 'Provincia IVA proveedor', '42', '1052'),
('1053', 'provincias', 'provincias', '42', '1053'),
('1054', 'remitos', 'remitos', '42', '1054'),
('1055', 'roles', 'Roles', '42', '1055'),
('1056', 'subcategoria', 'Sub Categoria', '42', '1056'),
('1057', 'subfamilias', 'Sub Familias', '42', '1057'),
('1058', 'sucursales', 'Sucursales', '42', '1058'),
('1059', 'tipo', 'Tipo', '42', '1059'),
('1060', 'tipo_comprobante', 'Tipo Comprobante', '42', '1060'),
('1061', 'tipo_documento', 'Tipo Documento', '42', '1061'),
('1062', 'tipo_iva', 'Tipo de IVA', '42', '1062'),
('1063', 'tipos_cajas', 'Tipos Cajas', '42', '1063'),
('1064', 'ubicacion', 'Ubicacion', '42', '1064'),
('1065', 'unidad', 'Unidad', '42', '1065'),
('1066', 'usuarios', 'Usuarios', '42', '1066'),
('1067', 'vendedores', 'Vendedores', '42', '1067'),
('1068', 'stock', 'Stock', '42', '1068'),
('1069', 'gastos', 'Gastos', '42', '1069'),
('1070', 'compras', 'Compras', '42', '1070');
INSERT INTO funciones_roles (`rol_id`, `funcion_id`, `permite_crear`, `permite_ver`, `permite_modificar`, `permite_eliminar`, `permite_imprimir`, `permite_listar`, `empresa_id`) VALUES
('56', '1027', '1', '0', '0', '0', '0', '1', '42'),
('56', '1028', '0', '0', '0', '0', '0', '1', '42'),
('56', '1029', '0', '0', '0', '0', '0', '1', '42'),
('56', '1030', '0', '0', '0', '0', '0', '0', '42'),
('56', '1031', '0', '0', '0', '0', '0', '0', '42'),
('56', '1032', '0', '0', '0', '0', '0', '0', '42'),
('56', '1033', '1', '0', '0', '0', '0', '1', '42'),
('56', '1034', '0', '0', '0', '0', '0', '0', '42'),
('56', '1035', '0', '0', '0', '0', '0', '0', '42'),
('56', '1036', '0', '0', '0', '0', '0', '0', '42'),
('56', '1037', '0', '0', '0', '0', '0', '0', '42'),
('56', '1038', '0', '0', '0', '0', '0', '0', '42'),
('56', '1039', '0', '0', '0', '0', '0', '0', '42'),
('56', '1040', '0', '0', '0', '0', '0', '0', '42'),
('56', '1041', '0', '0', '0', '0', '0', '0', '42'),
('56', '1042', '0', '0', '0', '0', '0', '0', '42'),
('56', '1043', '0', '0', '0', '0', '0', '0', '42'),
('56', '1044', '0', '0', '0', '0', '0', '0', '42'),
('56', '1045', '0', '0', '0', '0', '0', '0', '42'),
('56', '1046', '0', '0', '0', '0', '0', '0', '42'),
('56', '1047', '0', '0', '0', '0', '0', '0', '42'),
('56', '1048', '0', '0', '0', '0', '0', '1', '42'),
('56', '1049', '0', '0', '0', '0', '0', '0', '42'),
('56', '1050', '0', '0', '0', '0', '0', '0', '42'),
('56', '1051', '0', '0', '0', '0', '0', '0', '42'),
('56', '1052', '0', '0', '0', '0', '0', '0', '42'),
('56', '1053', '0', '0', '0', '0', '0', '0', '42'),
('56', '1054', '0', '0', '0', '0', '0', '0', '42'),
('56', '1055', '0', '0', '0', '0', '0', '0', '42'),
('56', '1056', '0', '0', '0', '0', '0', '0', '42'),
('56', '1057', '0', '0', '0', '0', '0', '0', '42'),
('56', '1058', '0', '0', '0', '0', '0', '0', '42'),
('56', '1059', '0', '0', '0', '0', '0', '0', '42'),
('56', '1060', '0', '0', '0', '0', '0', '0', '42'),
('56', '1061', '0', '0', '0', '0', '0', '0', '42'),
('56', '1062', '0', '0', '0', '0', '0', '0', '42'),
('56', '1063', '0', '0', '0', '0', '0', '0', '42'),
('56', '1064', '0', '0', '0', '0', '0', '0', '42'),
('56', '1065', '0', '0', '0', '0', '0', '0', '42'),
('56', '1066', '0', '0', '0', '0', '0', '0', '42'),
('56', '1067', '0', '0', '0', '0', '0', '1', '42'),
('56', '1068', '0', '0', '0', '0', '0', '0', '42'),
('56', '1069', '1', '0', '0', '0', '0', '1', '42'),
('56', '1070', '0', '0', '0', '0', '0', '0', '42'),
('57', '1027', '1', '1', '1', '1', '1', '1', '42'),
('57', '1028', '1', '1', '1', '1', '1', '1', '42'),
('57', '1029', '1', '1', '1', '1', '1', '1', '42'),
('57', '1030', '1', '1', '1', '1', '1', '1', '42'),
('57', '1031', '1', '1', '1', '1', '1', '1', '42'),
('57', '1032', '1', '1', '1', '1', '1', '1', '42'),
('57', '1033', '1', '1', '1', '1', '1', '1', '42'),
('57', '1034', '1', '1', '1', '1', '1', '1', '42'),
('57', '1035', '1', '1', '1', '1', '1', '1', '42'),
('57', '1036', '1', '1', '1', '1', '1', '1', '42'),
('57', '1037', '1', '1', '1', '1', '1', '1', '42'),
('57', '1038', '1', '1', '1', '1', '1', '1', '42'),
('57', '1039', '1', '1', '1', '1', '1', '1', '42'),
('57', '1040', '1', '1', '1', '1', '1', '1', '42'),
('57', '1041', '1', '1', '1', '1', '1', '1', '42'),
('57', '1042', '1', '1', '1', '1', '1', '1', '42'),
('57', '1043', '1', '1', '1', '1', '1', '1', '42'),
('57', '1044', '1', '1', '1', '1', '1', '1', '42'),
('57', '1045', '1', '1', '1', '1', '1', '1', '42'),
('57', '1046', '1', '1', '1', '1', '1', '1', '42'),
('57', '1047', '1', '1', '1', '1', '1', '1', '42'),
('57', '1048', '1', '1', '1', '1', '1', '1', '42'),
('57', '1049', '1', '1', '1', '1', '1', '1', '42'),
('57', '1050', '1', '1', '1', '1', '1', '1', '42'),
('57', '1051', '1', '1', '1', '1', '1', '1', '42'),
('57', '1052', '1', '1', '1', '1', '1', '1', '42'),
('57', '1053', '1', '1', '1', '1', '1', '1', '42'),
('57', '1054', '1', '1', '1', '1', '1', '1', '42'),
('57', '1055', '1', '1', '1', '1', '1', '1', '42'),
('57', '1056', '1', '1', '1', '1', '1', '1', '42'),
('57', '1057', '1', '1', '1', '1', '1', '1', '42'),
('57', '1058', '1', '1', '1', '1', '1', '1', '42'),
('57', '1059', '1', '1', '1', '1', '1', '1', '42'),
('57', '1060', '1', '1', '1', '1', '1', '1', '42'),
('57', '1061', '1', '1', '1', '1', '1', '1', '42'),
('57', '1062', '1', '1', '1', '1', '1', '1', '42'),
('57', '1063', '1', '1', '1', '1', '1', '1', '42'),
('57', '1064', '1', '1', '1', '1', '1', '1', '42'),
('57', '1065', '1', '1', '1', '1', '1', '1', '42'),
('57', '1066', '1', '1', '1', '1', '1', '1', '42'),
('57', '1067', '1', '1', '1', '1', '1', '1', '42'),
('57', '1068', '1', '1', '1', '1', '1', '1', '42'),
('57', '1069', '1', '1', '1', '1', '1', '1', '42'),
('57', '1070', '1', '1', '1', '1', '1', '1', '42');
INSERT INTO productos_stock (`id`, `codigo`, `stock_inicial`, `controla_stock`, `punto_pedido`, `largo`, `alto`, `ancho`, `peso`, `unidad_id`, `ubicacion_id`, `proveedores_id`, `producto_id`, `empresa_id`, `stock_actual`, `sucursal_id`, `id_remoto`) VALUES
('7216', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '55172', '42', '0', '31', '7216');
INSERT INTO renglones_comprobantes (`id`, `comprobante_id`, `producto_id`, `descripcion`, `cantidad`, `tasa_iva`, `precio_unitario`, `descuento`, `total_linea`, `id_remoto`) VALUES
('55805', '24048', '55172', 'GENERAL (21%)', '1', '0.21', '10', '0', '10', '55805');
INSERT INTO tipo_comprobante (`id`, `nombre`, `id_remoto`) VALUES
('1', 'FC', '1'),
('2', 'RTO', '2'),
('3', 'PDO', '3'),
('4', 'NC', '4');
