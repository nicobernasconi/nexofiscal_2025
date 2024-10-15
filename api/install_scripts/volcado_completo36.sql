INSERT INTO tipo_iva_empresa (`id`, `nombre`, `descripcion`, `letra_factura`, `id_remoto`) VALUES
('1', 'INSCRIPTO', 'Descripción del Tipo IVA 1', 'A', '1'),
('2', 'MONOTRIBUTO', 'Descripción del Tipo IVA 2', 'C', '2'),
('3', 'EXENTO', NULL, 'B', '3'),
('4', 'NO FISCAL', NULL, '-', '4');
INSERT INTO empresas (`id`, `nombre`, `logo`, `direccion`, `telefono`, `tipo_iva`, `cuit`, `responsable`, `email`, `rubro_id`, `fecha_inicio_actividades`, `descripcion`, `razon_social`, `inicio_actividad`, `iibb`, `cert`, `key`, `fiscal`, `id_remoto`) VALUES
('36', 'AMOIA SEBASTIAN', NULL, '23 N° 1434', '2213053378', '1', '23356111699', 'Sebastian', 'sebastian@nexofiscal.com.ar', NULL, '2020-10-01', NULL, 'AMOIA SEBASTIAN', NULL, '23356111699', '-----BEGIN CERTIFICATE-----
MIIDQDCCAiigAwIBAgIIMnnemhfQOZkwDQYJKoZIhvcNAQENBQAwMzEVMBMGA1UEAwwMQ29tcHV0
YWRvcmVzMQ0wCwYDVQQKDARBRklQMQswCQYDVQQGEwJBUjAeFw0yNDA2MTQxNjI4MzNaFw0yNjA2
MTQxNjI4MzNaMCsxDjAMBgNVBAMMBU5FWE9TMRkwFwYDVQQFExBDVUlUIDIzMzU2MTExNjk5MIIB
IjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAyBjwz7KWgsYAovYztWf44idZexkkWSkcs6p3
mOsJ57insfzDueh4AotqvmykzyKAUVbPeTTepNt/xv+NRHl4wJ89R9rd3SG8MgEyXXj+8SmdAmfi
uYIwV5O/xaYD1NBt2MkkqkN7EAparmkAUslepLtesE8xPrJtA2giCbLSWfMSg008m+YDLuE64riC
ERo7XgbHzRQwO6+3AhzB53wT6pPvB8X4b8JZoi9oUBDXeDQDM8EPa6nx03IMxbYjW7TVXBbyENLK
+B8wnZatSqZG+/uatRvdTY1rmkZET9LHnNs3Ijm4e81stmkeA1UnZmW0JvOLYbv/b/HFqlL/0e9D
TQIDAQABo2AwXjAMBgNVHRMBAf8EAjAAMB8GA1UdIwQYMBaAFCsNL8jfYf0IyU4R0DWTBG2OW9Bu
MB0GA1UdDgQWBBQbaunSG+4xRkNkoiloZIbpOzCy+jAOBgNVHQ8BAf8EBAMCBeAwDQYJKoZIhvcN
AQENBQADggEBAMakjtINUP+4CBT0m6rjAhVIFI7FZVZKfS0xdj9bbNsx9yUyW9r1Pw3VnIRKhxHV
0sSuImzYZ2YEIwtz2C8Esq3p76PtdhkPuapWRLqA22bQ3RbejfQyZJx2L5vVJTf5Y5DF6hmpHIha
afzeslT29DWRXwVKKNVGyLkCPlJCGTKacfy0awv6G3BZb2+w1GLVh+Y3y6B6EG/XO+LbWFrD9A2A
qrbyXXzdmsojWr86v7IxehXObk321Y2tS0my+MV8Q3ABR22pkOt5k0EgHelsvNvaa5nF+74WMMX4
ne7ygDPtEYqsXGv7KPLBYSKWeraaqzYPRHPWfQbt8+AMxW5ivCQ=
-----END CERTIFICATE-----', '-----BEGIN PRIVATE KEY-----
MIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQDIGPDPspaCxgCi
9jO1Z/jiJ1l7GSRZKRyzqneY6wnnuKex/MO56HgCi2q+bKTPIoBRVs95NN6k23/G
/41EeXjAnz1H2t3dIbwyATJdeP7xKZ0CZ+K5gjBXk7/FpgPU0G3YySSqQ3sQClqu
aQBSyV6ku16wTzE+sm0DaCIJstJZ8xKDTTyb5gMu4TriuIIRGjteBsfNFDA7r7cC
HMHnfBPqk+8HxfhvwlmiL2hQENd4NAMzwQ9rqfHTcgzFtiNbtNVcFvIQ0sr4HzCd
lq1Kpkb7+5q1G91NjWuaRkRP0sec2zciObh7zWy2aR4DVSdmZbQm84thu/9v8cWq
Uv/R70NNAgMBAAECggEADz47Jg9cQOUVilSIFuMvRgcqXWLKbpJfsd4kEz2geQsL
l1cCkAiCD+uP+6aqHW9q6AddccXRyixAfSfdmqBITyAyWmV1blGSFRsbO5yqizOY
1uSUFH6y8RD71HIzq4QfQhr8lI3kc5HeW5f7ItNdRt5RNcwqm7Sa8l397dA2Dwz/
TFt+qjjcHSuOMpYekM5bsM2IuzZUTLmQKvV5DEmL9S6X6SpfwN+GYH6UwmW9eX9i
M+jkn/fLGYG71+Slfw+B8b0JlBNBxi+U2ZOe9OLHdZe6DIu9r1vuLwIyat4rG8P8
oOTr4xXOD+Sx9LCF30b43M+YrKx/95xEJep4RNOUsQKBgQDvLRbZN/1M9h5DYlrM
G5NAULT9oce3C6V3As1ofkmifKPzCA0LprwNqYWmRr/nEbt9BgxIkVAUdyyW/Ae0
OXU7Ugi8sHXqM7xVYbsmggw8IwqdUrXu6/w1tI1zsrTzMsKGmnam3hc46cMvjc2N
q8ajOLui9FTLD7vQEbwlEJzFuQKBgQDWLCZ9wtjTCa5B+U/iRCOA7YQq4/GcLm6N
mpG6FIi0NLW0Edwa5WrwxjwDKL+6ug83eqRq66lDdoidaxoEzOxnmIeDcJMi/oQU
/TlprwChOTrYz1jETaPmFoSeaQpYgdUwXqk1+JcYaEk7BX2fvRczZlYwDB80XsNX
mj/uQDb0NQKBgQDG7uvoNGec0cEOLwpyZiUuA3Lm2t7wYuF3gX80AIZifeUnyXSA
UmhrvKqLSKFpIhidvqAM48CFpTITSwFlZ70YX/0gZG1PJUTqh2VQfC6M/mBfxmHI
ncOjL8/Pgb574aZmqqcx19vc3KIaNYnY4h1PuWpn+W1lkGqYf4fMFGsxEQKBgAwm
eRCNa0udsvsfL78Aaps33lWolN5ta+wwpq/N1muSyfQrRzdnaIe8V08+kpH/WDmn
hYhjUjj3koyLtPAsyASgjJ+SVWaY1dly/DzYpsp+uq3uJXUNiozVHjT2dJXM19Fk
rWjYb4n88Jqelx/m1FggKCeVqHeKIL7pi1Ly5as5AoGBALMh+x21GPYxkzdI/ml4
2QR+GvjpNYg1l3hHJcByKCYJjJzLv0kg9shdbCsl/tv49hBuTuF6EsArgv/SBy6Y
FgwIKaE6pyVGzYlOUmt+Wlc0HHMxsJtogj5T1hT98OrDOjNMxshXmwrbILi9QQPd
du4O0vzBiUOJAdOOePDpXafv
-----END PRIVATE KEY-----
', '1', '36');
INSERT INTO moneda (`id`, `simbolo`, `nombre`, `cotizacion`, `empresa_id`, `id_remoto`) VALUES
('31', '$', 'Pesos argentinos', '1', '36', '31'),
('32', 'USD', 'Dólar estadounidense', '886', '36', '32');
INSERT INTO agrupacion (`id`, `numero`, `nombre`, `color`, `icono`, `empresa_id`, `id_remoto`) VALUES
('24', '1', 'GENERAL', '#0098DA', 'fas fa-boxes', '36', '24');
INSERT INTO categoria (`id`, `nombre`, `se_imprime`, `empresa_id`, `id_remoto`) VALUES
('22', 'CATEGORIA', '1', '36', '22');
INSERT INTO puntos_venta (`id`, `numero`, `descripcion`, `empresa_id`, `id_remoto`) VALUES
('11', '6', 'Grupo SEIC', '36', '11');
INSERT INTO sucursales (`id`, `empresa_id`, `nombre`, `direccion`, `telefono`, `email`, `contacto_nombre`, `contacto_telefono`, `contacto_email`, `referente_nombre`, `referente_telefono`, `referente_email`, `id_remoto`) VALUES
('25', '36', 'Grupo SEIC', '23 N° 1434', '111', 'sebastian@nexofiscal.com.ar', NULL, NULL, NULL, NULL, NULL, NULL, '25');
INSERT INTO roles (`id`, `nombre`, `descripcion`, `empresa_id`, `id_remoto`) VALUES
('44', 'cajero', 'Cajero/Vendedor', '36', '44'),
('45', 'admin', 'Administrador', '36', '45');
INSERT INTO usuarios (`id`, `nombre_usuario`, `password`, `nombre_completo`, `rol_id`, `activo`, `empresa_id`, `sucursal_id`, `punto_venta_id`, `venta_rapida`, `lista_precios`, `imprimir`, `tipo_comprobante_imprimir`, `id_remoto`) VALUES
('47', 'sebastian@nexofiscal.com.ar', 'a4431d8b0b8ed55938a2bc6ecdb7b274', 'Administrador', '45', '1', '36', '25', '11', '0', '1', '1', '2', '47');
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
('21', 'DNI', '36', '21'),
('22', 'L.E.', '36', '22'),
('23', 'PASAPORTE', '36', '23');
INSERT INTO tipo_iva (`id`, `nombre`, `descripcion`, `porcentaje`, `letra_factura`, `empresa_id`, `id_remoto`) VALUES
('21', 'INSCRIPTO', NULL, NULL, 'A', '36', '21'),
('22', 'MONOTRIBUTO', NULL, NULL, 'C', '36', '22'),
('23', 'CONSUMIDOR FINAL', NULL, NULL, 'B', '36', '23'),
('37', 'RESPONSABLE EXENTO', NULL, NULL, 'B', '36', '37');
INSERT INTO vendedores (`id`, `nombre`, `direccion`, `telefono`, `porcentaje_comision`, `fecha_ingreso`, `empresa_id`, `sucursal_id`, `id_remoto`) VALUES
('40', 'VENDEROR', NULL, NULL, '0', '2024-06-17', '36', '25', '40');
INSERT INTO clientes (`id`, `nro_cliente`, `nombre`, `tipo_iva_id`, `cuit`, `tipo_documento_id`, `numero_documento`, `direccion_comercial`, `direccion_entrega`, `localidad_id`, `telefono`, `celular`, `email`, `contacto`, `telefono_contacto`, `categoria_id`, `vendedor_id`, `porcentaje_descuento`, `limite_credito`, `saldo_inicial`, `saldo_actual`, `fecha_ultima_compra`, `fecha_ultimo_pago`, `percepcion_iibb`, `desactivado`, `categoria_iibb_id`, `empresa_id`, `id_remoto`) VALUES
('50', '27', 'BERNASCONI NICOLAS OMAR', '22', '20308762050', NULL, '30876205', '608 478 - LOS HORNOS (LAS CALLES CON ALTURA MAYORES A 900) - BUENOS AIRES', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '0', '0', '0', NULL, NULL, '0', NULL, NULL, '36', '50'),
('51', '28', 'AMOIA RUBEN ANGEL', '22', '20144638094', NULL, '14463809', '23 1430 - LA PLATA SUDESTE CALLE 50 AMBAS VEREDAS - BUENOS AIRES', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '0', '0', '0', NULL, NULL, '0', NULL, NULL, '36', '51'),
('52', '29', 'COOPERATIVA DE INSTALADORES ELECTRICISTAS Y AFINES DEL PARTIDO DE LA PLATA PARA PROVISION Y VIVIENDA LIMITADA', '21', '30542276408', NULL, '0', '38 1027 - LA PLATA NOROESTE CALLE 50 - BUENOS AIRES', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '0', '0', '0', NULL, NULL, '0', NULL, NULL, '36', '52'),
('53', '30', 'GRANJA ECOLOGICA SA', '21', '30677211802', NULL, '0', '14 1481 - LA PLATA SUDESTE CALLE 50 AMBAS VEREDAS - BUENOS AIRES', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '0', '0', '0', NULL, NULL, '0', NULL, NULL, '36', '53'),
('54', '31', 'HISOYMM EMPRESA S.R.L.', '21', '30711493251', NULL, '0', '122 2363 - LA PLATA SUDESTE CALLE 50 AMBAS VEREDAS - BUENOS AIRES', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '0', '0', '0', NULL, NULL, '0', NULL, NULL, '36', '54'),
('55', '32', 'MERCEDES LA PLATA S.R.L.', '21', '30707086498', NULL, '0', 'RUTA 36 E/47 Y 49 790 - LISANDRO OLMOS(NOROESTE) - BUENOS AIRES', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '0', '0', '0', NULL, NULL, '0', NULL, NULL, '36', '55'),
('56', '33', 'BERDUCCI PABLO MANUEL', '21', '23304647949', NULL, '30464794', '50 Y 190 4748 - LISANDRO OLMOS(NOROESTE) - BUENOS AIRES', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '0', '0', '0', NULL, NULL, '0', NULL, NULL, '36', '56'),
('57', '34', 'COCCARO ANGEL MARTIN', '21', '20304648903', NULL, '30464890', '60 ( RUTA 10 ) 255 - BERISSO - BUENOS AIRES', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '0', '0', '0', NULL, NULL, '0', NULL, NULL, '36', '57'),
('58', '35', 'MIGUEL COLOMBO S.A.', '21', '30714503312', NULL, '0', '44 4375 - LISANDRO OLMOS(NOROESTE) - BUENOS AIRES', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '0', '0', '0', NULL, NULL, '0', NULL, NULL, '36', '58'),
('60', '37', 'HORCAJO LUCAS', '22', '23273139729', NULL, '27313972', '659 853 - LA PLATA SUDESTE CALLE 50 AMBAS VEREDAS - BUENOS AIRES', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '0', '0', '0', NULL, NULL, '0', NULL, NULL, '36', '60'),
('66', '41', 'NUEVA ESCUELA ARGENTINA SRL', '37', '30708689757', '21', '1', '6 480 - LA PLATA NOROESTE CALLE 50 - BUENOS AIRES', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '0', '0', '0', NULL, NULL, '0', NULL, NULL, '36', '66'),
('67', '42', 'DAYRAUT IGNACIO', '22', '20300684557', NULL, '30068455', 'RECONQUISTA 60 - CHACABUCO - BUENOS AIRES', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '0', '0', '0', NULL, NULL, '0', NULL, NULL, '36', '67'),
('68', '43', 'ASCENSORES NORTE SA', '21', '30709858838', '21', '1', '6 313 - ENTRE LAS CALLES : 528 BIS Y 529', NULL, '9', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '0', '0', '0', NULL, NULL, '0', NULL, NULL, '36', '68'),
('74', '46', 'TITO I SRL', '21', '30677997164', NULL, NULL, '485 ESQUINA DIAGONAL 6 4597 - JOAQUIN GORINA - BUENOS AIRES', NULL, '9', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '0', '0', '0', NULL, NULL, '0', NULL, NULL, '36', '74');
INSERT INTO codigos_barras (`id`, `inicio`, `id_long`, `payload_type`, `payload_int`, `long`, `empresa_id`, `id_remoto`) VALUES
('14', '2', '5', 'P', '5', '13', '36', '14');
INSERT INTO tasa_iva (`id`, `nombre`, `tasa`, `empresa_id`, `id_remoto`) VALUES
('43', 'IVA 21%', '0.21', '36', '43'),
('44', 'IVA 10.5%', '0.105', '36', '44'),
('45', 'Sin IVA', '0', '36', '45');
INSERT INTO familias (`id`, `numero`, `nombre`, `empresa_id`, `id_remoto`) VALUES
('33', '1', 'GENERAL', '36', '33');
INSERT INTO proveedores (`id`, `razon_social`, `direccion`, `localidad_id`, `telefono`, `email`, `tipo_iva_id`, `cuit`, `categoria_id`, `subcategoria_id`, `fecha_ultima_compra`, `fecha_ultimo_pago`, `saldo_actual`, `empresa_id`, `id_remoto`) VALUES
('41', 'Proveedor por defecto', 'Calle Ejemplo 123', '1', '123456789', 'proveedor@example.com', NULL, '11111111', NULL, NULL, NULL, NULL, '0', '36', '41');
INSERT INTO tipo (`id`, `numero`, `nombre`, `empresa_id`, `id_remoto`) VALUES
('50', '1', 'Producto', '36', '50'),
('51', '2', 'Servicio', '36', '51'),
('52', '3', 'Producto y servicio', '36', '52');
INSERT INTO unidad (`id`, `nombre`, `simbolo`, `empresa_id`, `id_remoto`) VALUES
('102', 'Kilogramo', 'KG', '36', '102'),
('103', 'Unidad', 'UN.', '36', '103'),
('104', 'Metro', 'M', '36', '104'),
('105', 'Metro Cuadrado', 'M2', '36', '105'),
('106', 'Metro Cubico', 'M3', '36', '106'),
('107', 'Litro', 'LTS.', '36', '107'),
('108', 'Gramo', 'GR.', '36', '108');
INSERT INTO productos (`id`, `codigo`, `descripcion`, `descripcion_ampliada`, `familia_id`, `subfamilia_id`, `agrupacion_id`, `marca_id`, `codigo_barra`, `proveedor_id`, `fecha_alta`, `fecha_actualizacion`, `articulo_activado`, `tipo_id`, `producto_balanza`, `precio_costo`, `moneda_id`, `tasa_iva`, `tasa_iva_id`, `incluye_iva`, `impuesto_interno`, `precio1`, `precio2`, `precio3`, `fraccionado`, `rg5329_23`, `activo`, `texto_panel`, `stock`, `stock_minimo`, `stock_pedido`, `iibb`, `codigo_barra2`, `oferta`, `margen_ganancia`, `publicado_web`, `unidad_id`, `empresa_id`, `favorito`, `tipo_impuesto_interno`, `id_remoto`) VALUES
('16155', '1', 'Venta 21', NULL, '33', NULL, '24', NULL, NULL, NULL, '2024-06-18', '2024-06-18 15:18:03', NULL, '50', '0', '0', NULL, '0', '43', NULL, NULL, '0', '0', '0', NULL, NULL, '1', NULL, '1', NULL, NULL, NULL, NULL, NULL, '0', NULL, '103', '36', '1', NULL, '16155'),
('16156', '2', 'Venta 10.5', NULL, '33', NULL, '24', NULL, NULL, NULL, '2024-06-18', '2024-06-18 15:18:38', NULL, '50', '0', '0', NULL, '0', '44', NULL, NULL, '0', '0', '0', NULL, NULL, '1', NULL, '1', NULL, NULL, NULL, NULL, NULL, '0', NULL, '103', '36', '1', NULL, '16156'),
('51129', '3', 'Facturador Baiwang', NULL, '33', NULL, '24', NULL, NULL, NULL, '2024-09-04', '2024-09-04 13:02:02', NULL, '50', '0', '100000', NULL, '0', '44', NULL, NULL, '650000', NULL, NULL, NULL, NULL, '1', NULL, '1', NULL, NULL, NULL, NULL, NULL, '550', NULL, '103', '36', '1', NULL, '51129'),
('51272', '4', 'Generico 21-2', NULL, '33', NULL, '24', NULL, NULL, NULL, '2024-09-04', '2024-09-04 16:19:03', NULL, '50', '0', '0', NULL, '0', '43', NULL, '0', '0', '0', '0', '0', '0', '0', NULL, '1', NULL, NULL, '0', NULL, '0', '0', '0', NULL, '36', '1', NULL, '51272'),
('51273', '5', 'Mano de Obra', NULL, '33', NULL, '24', NULL, NULL, NULL, '2024-09-04', '2024-09-04 16:19:11', NULL, '50', '0', '0', NULL, '0', '43', NULL, '0', '0', '0', '0', '0', '0', '0', NULL, '1', NULL, NULL, '0', NULL, '0', '0', '0', '103', '36', '1', NULL, '51273'),
('51295', '6', 'Reparacion', NULL, '33', NULL, '24', NULL, NULL, NULL, '2024-09-04', '2024-09-04 16:37:01', NULL, '51', '0', '0', NULL, '0', '43', NULL, NULL, '0', NULL, NULL, NULL, NULL, '1', NULL, '1', NULL, NULL, NULL, NULL, NULL, '0', NULL, '103', '36', '1', NULL, '51295'),
('51296', '7', 'Reparacion 10,5', NULL, '33', NULL, '24', NULL, NULL, NULL, '2024-09-04', '2024-09-04 16:37:35', NULL, '50', '0', '0', NULL, '0', '44', NULL, NULL, '0', NULL, NULL, NULL, NULL, '1', NULL, '1', NULL, NULL, NULL, NULL, NULL, '0', NULL, '103', '36', '1', NULL, '51296'),
('51297', '8', 'Soporte Mensual', NULL, '33', NULL, '24', NULL, NULL, NULL, '2024-09-04', '2024-09-04 16:38:03', NULL, '51', '0', '0', NULL, '0', '43', NULL, NULL, '0', NULL, NULL, NULL, NULL, '1', NULL, '1', NULL, NULL, NULL, NULL, NULL, '0', NULL, NULL, '36', '1', NULL, '51297'),
('55589', '8', 'Reparacion 2', NULL, '33', NULL, NULL, NULL, NULL, NULL, '2024-09-11', '2024-09-11 08:48:55', NULL, '51', '0', '0', NULL, '0', '43', NULL, NULL, '0', NULL, NULL, NULL, NULL, '1', NULL, '1', NULL, NULL, NULL, NULL, NULL, '0', NULL, '103', '36', '1', NULL, '55589');
INSERT INTO forma_pago (`id`, `nombre`, `porcentaje`, `activo`, `empresa_id`, `id_remoto`) VALUES
('27', 'EFECTIVO', '0', '1', '36', '27'),
('29', 'MERCADOPAGO', '0', '1', '36', '29'),
('30', 'TRANSFERENCIA', '0', '1', '36', '30'),
('35', 'CUENTA CORRIENTE', '0', '1', '36', '35');
INSERT INTO comprobantes (`id`, `numero`, `punto_venta`, `tipo_factura`, `tipo_documento`, `numero_de_documento`, `cuotas`, `cliente_id`, `remito`, `persona`, `provincia_id`, `fecha`, `hora`, `fecha_proceso`, `letra`, `numero_factura`, `prefijo_factura`, `operacion_negocio_id`, `retencion_iva`, `retencion_iibb`, `retencion_ganancias`, `porcentaje_ganancias`, `porcentaje_iibb`, `porcentaje_iva`, `no_gravado`, `importe_iva`, `total`, `condicion_venta_id`, `descripcion_flete`, `vendedor_id`, `recibo`, `observaciones_1`, `observaciones_2`, `observaciones_3`, `observaciones_4`, `descuento`, `descuento_1`, `descuento_2`, `descuento_3`, `descuento_4`, `iva_2`, `impresa`, `cancelado`, `nombre_cliente`, `direccion_cliente`, `localidad_cliente`, `garantia`, `concepto`, `notas`, `linea_pago_ultima`, `relacion_tk`, `total_iibb`, `importe_iibb`, `provincia_categoria_iibb_id`, `importe_retenciones`, `provincia_iva_proveedor_id`, `ganancias_proveedor_id`, `importe_ganancias`, `numero_iibb`, `numero_ganancias`, `ganancias_proveedor`, `cae`, `fecha_vencimiento`, `forma_pago_id`, `remito_cliente`, `texto_dolares`, `comprobante_final`, `numero_guia_1`, `numero_guia_2`, `numero_guia_3`, `tipo_alicuota_1`, `tipo_alicuota_2`, `tipo_alicuota_3`, `importe_iva_21`, `importe_iva_105`, `importe_iva_0`, `no_gravado_iva_21`, `no_gravado_iva_105`, `no_gravado_iva_0`, `importe_impuesto_interno`, `direccion_entrega`, `fecha_entrega`, `hora_entrega`, `total_pagado`, `tipo_comprobante_id`, `empresa_id`, `usuario_id`, `cierre_caja_id`, `fecha_baja`, `motivo_baja`, `url_pdf`, `sucursal_id`, `qr`, `comprobante_id_baja`, `subido`, `id_remoto`) VALUES
('4339', NULL, '6', '6', '96', '111111', NULL, '1', NULL, NULL, NULL, '2024-06-19', '10:41:34', '2024-06-19', 'C', '1', '6', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '173.55', '1000', NULL, NULL, '40', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '74259012677748', '2024-06-29', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '173.55', '0', '0', '826', '0', '0', '0', NULL, NULL, NULL, '1000', '1', '36', '47', NULL, NULL, NULL, 'https://storage.googleapis.com/a2p-v2-storage/9a92a7c5-0f34-449f-8d72-840b27f6fbbd', '25', 'https://www.afip.gob.ar/fe/qr/?p=eyJ2ZXIiOjEsImZlY2hhIjoiMjAyNC0wNi0xOSIsImN1aXQiOiIyMzM1NjExMTY5OSIsInB0b1Z0YSI6IjYiLCJ0aXBvQ21wIjoiNiIsIm5yb0NtcCI6MSwiaW1wb3J0ZSI6MTczLjU1LCJtb25lZGEiOiJQRVMiLCJjdHoiOjEsInRpcG9Eb2NSZWMiOiI5NiIsIm5yb0RvY1JlYyI6IjExMTExMSIsInRpcG9Db2RBdXQiOiJFIiwiY29kQXV0IjoiNzQyNTkwMTI2Nzc3NDgifQ==', NULL, '0', '4339'),
('4375', '1', '6', NULL, NULL, NULL, NULL, '56', NULL, NULL, NULL, '2024-06-19', '18:28:35', '2024-06-19', NULL, NULL, '6', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '200000', NULL, NULL, '40', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '200000', '3', '36', '47', NULL, NULL, NULL, NULL, '25', NULL, NULL, '0', '4375'),
('14414', NULL, '6', '6', '80', '23273139729', NULL, '60', NULL, NULL, NULL, '2024-08-05', '17:21:43', '2024-08-05', 'B', '2', '6', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '61764.7', '650000', NULL, NULL, '40', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '74329403763549', '2024-08-15', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '61764.7', '0', '0', '588235', '0', '0', NULL, NULL, NULL, '650000', '1', '36', '47', NULL, NULL, NULL, 'https://storage.googleapis.com/a2p-v2-storage/5de8a8db-cf10-4047-8e40-f57d7c59a512', '25', 'https://www.afip.gob.ar/fe/qr/?p=eyJ2ZXIiOjEsImZlY2hhIjoiMjAyNC0wOC0wNSIsImN1aXQiOiIyMzM1NjExMTY5OSIsInB0b1Z0YSI6IjYiLCJ0aXBvQ21wIjoiNiIsIm5yb0NtcCI6MiwiaW1wb3J0ZSI6NjE3NjQuNzEsIm1vbmVkYSI6IlBFUyIsImN0eiI6MSwidGlwb0RvY1JlYyI6IjgwIiwibnJvRG9jUmVjIjoiMjMyNzMxMzk3MjkiLCJ0aXBvQ29kQXV0IjoiRSIsImNvZEF1dCI6Ijc0MzI5NDAzNzYzNTQ5In0=', NULL, '0', '14414'),
('16977', NULL, '6', '6', '96', '111111', NULL, '1', NULL, NULL, NULL, '2024-08-15', '09:19:19', '2024-08-15', 'C', '3', '6', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '4304.13', '24800', NULL, NULL, '40', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '74339526973376', '2024-08-25', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '4304.13', '0', '0', '20496', '0', '0', '0', NULL, NULL, NULL, '24800', '1', '36', '47', NULL, NULL, NULL, 'https://storage.googleapis.com/a2p-v2-storage/04d371c5-0184-4fca-ac73-c017c73ea0c8', '25', 'https://www.afip.gob.ar/fe/qr/?p=eyJ2ZXIiOjEsImZlY2hhIjoiMjAyNC0wOC0xNSIsImN1aXQiOiIyMzM1NjExMTY5OSIsInB0b1Z0YSI6IjYiLCJ0aXBvQ21wIjoiNiIsIm5yb0NtcCI6MywiaW1wb3J0ZSI6NDMwNC4xMywibW9uZWRhIjoiUEVTIiwiY3R6IjoxLCJ0aXBvRG9jUmVjIjoiOTYiLCJucm9Eb2NSZWMiOiIxMTExMTEiLCJ0aXBvQ29kQXV0IjoiRSIsImNvZEF1dCI6Ijc0MzM5NTI2OTczMzc2In0=', NULL, '0', '16977'),
('16978', NULL, '6', '1', '80', '30709858838', NULL, '68', NULL, NULL, NULL, '2024-08-15', '09:22:00', '2024-08-15', 'A', '1', '6', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '4304.13', '24800', NULL, NULL, '40', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '74339527316095', '2024-08-25', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '4304.13', '0', '0', '20496', '0', '0', '0', NULL, NULL, NULL, '24800', '1', '36', '47', NULL, NULL, NULL, 'https://storage.googleapis.com/a2p-v2-storage/2b1006eb-7476-4cba-b2be-3df2041a57e0', '25', 'https://www.afip.gob.ar/fe/qr/?p=eyJ2ZXIiOjEsImZlY2hhIjoiMjAyNC0wOC0xNSIsImN1aXQiOiIyMzM1NjExMTY5OSIsInB0b1Z0YSI6IjYiLCJ0aXBvQ21wIjoiMSIsIm5yb0NtcCI6MSwiaW1wb3J0ZSI6NDMwNC4xMywibW9uZWRhIjoiUEVTIiwiY3R6IjoxLCJ0aXBvRG9jUmVjIjoiODAiLCJucm9Eb2NSZWMiOiIzMDcwOTg1ODgzOCIsInRpcG9Db2RBdXQiOiJFIiwiY29kQXV0IjoiNzQzMzk1MjczMTYwOTUifQ==', NULL, '0', '16978'),
('22365', '2', '6', NULL, NULL, NULL, NULL, '74', NULL, NULL, NULL, '2024-09-04', '16:17:15', '2024-09-04', NULL, NULL, '6', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '740000', NULL, NULL, '40', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '740000', '3', '36', '47', NULL, NULL, NULL, NULL, '25', NULL, NULL, '0', '22365'),
('24038', '3', '6', NULL, NULL, NULL, NULL, '74', NULL, NULL, NULL, '2024-09-10', '15:44:19', '2024-09-10', NULL, NULL, '6', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '60000', NULL, NULL, '40', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '60000', '3', '36', '47', NULL, NULL, NULL, NULL, '25', NULL, NULL, '0', '24038'),
('24039', NULL, '6', '1', '80', '30542276408', NULL, '52', NULL, NULL, NULL, '2024-09-10', '15:50:47', '2024-09-10', 'A', '2', '6', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '11760', '67760', NULL, NULL, '40', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '74379564663982', '2024-09-20', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '11760', '0', '0', '56000', '0', '0', '0', NULL, NULL, NULL, '67760', '1', '36', '47', NULL, NULL, NULL, 'https://storage.googleapis.com/a2p-v2-storage/c18c8cdd-b7a9-41ba-b853-170b4198f9c6', '25', 'https://www.afip.gob.ar/fe/qr/?p=eyJ2ZXIiOjEsImZlY2hhIjoiMjAyNC0wOS0xMCIsImN1aXQiOiIyMzM1NjExMTY5OSIsInB0b1Z0YSI6IjYiLCJ0aXBvQ21wIjoiMSIsIm5yb0NtcCI6MiwiaW1wb3J0ZSI6MTE3NjAsIm1vbmVkYSI6IlBFUyIsImN0eiI6MSwidGlwb0RvY1JlYyI6IjgwIiwibnJvRG9jUmVjIjoiMzA1NDIyNzY0MDgiLCJ0aXBvQ29kQXV0IjoiRSIsImNvZEF1dCI6Ijc0Mzc5NTY0NjYzOTgyIn0=', NULL, '0', '24039'),
('24237', '4', '6', NULL, NULL, NULL, NULL, '53', NULL, NULL, NULL, '2024-09-11', '08:50:28', '2024-09-11', NULL, NULL, '6', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '280000', NULL, NULL, '40', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '280000', '3', '36', '47', NULL, NULL, NULL, NULL, '25', NULL, NULL, '0', '24237'),
('24256', NULL, '6', '1', '80', '30677211802', NULL, '53', NULL, NULL, NULL, '2024-09-11', '11:11:36', '2024-09-11', 'A', '3', '6', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '9312.22', '98000', NULL, NULL, '40', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '74379647814358', '2024-09-21', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '9312.22', '0', '0', '88688', '0', '0', NULL, NULL, NULL, '98000', '1', '36', '47', NULL, NULL, NULL, 'https://storage.googleapis.com/a2p-v2-storage/31fc1a60-c2b8-49ce-b18f-8e8d930e490b', '25', 'https://www.afip.gob.ar/fe/qr/?p=eyJ2ZXIiOjEsImZlY2hhIjoiMjAyNC0wOS0xMSIsImN1aXQiOiIyMzM1NjExMTY5OSIsInB0b1Z0YSI6IjYiLCJ0aXBvQ21wIjoiMSIsIm5yb0NtcCI6MywiaW1wb3J0ZSI6OTMxMi4yMiwibW9uZWRhIjoiUEVTIiwiY3R6IjoxLCJ0aXBvRG9jUmVjIjoiODAiLCJucm9Eb2NSZWMiOiIzMDY3NzIxMTgwMiIsInRpcG9Db2RBdXQiOiJFIiwiY29kQXV0IjoiNzQzNzk2NDc4MTQzNTgifQ==', NULL, '0', '24256');
INSERT INTO funciones (`id`, `nombre`, `descripcion`, `empresa_id`, `id_remoto`) VALUES
('763', 'comprobantes', 'Comprobantes', '36', '763'),
('764', 'productos', 'Productos', '36', '764'),
('765', 'clientes', 'Clientes', '36', '765'),
('766', 'agrupaciones', 'Agrupaciones', '36', '766'),
('767', 'categorias', 'Categorias', '36', '767'),
('768', 'categoria_iibb', 'Categoria IIBB', '36', '768'),
('769', 'cierre_cajas', 'Cierres Cajas', '36', '769'),
('770', 'condiciones_venta', 'Condiciones Venta', '36', '770'),
('771', 'configuraciones', 'Configuraciones', '36', '771'),
('772', 'familias', 'Familias', '36', '772'),
('773', 'formas_pago', 'Formas de pago', '36', '773'),
('774', 'ganancias_clientes', 'Ganancias de clientes', '36', '774'),
('775', 'ganancias_prooveedor', 'Ganancias prooveedor', '36', '775'),
('776', 'listas_pecios', 'Listas de pecios', '36', '776'),
('777', 'localidades', 'Localidades', '36', '777'),
('778', 'marcas', 'Marcas', '36', '778'),
('779', 'monedas', 'Monedas', '36', '779'),
('780', 'operacion_negocio', 'Operacion Negocio', '36', '780'),
('781', 'pais', 'Pais', '36', '781'),
('782', 'presupuestos', 'presupuestos', '36', '782'),
('783', 'productos_stock', 'Productos Stock', '36', '783'),
('784', 'promociones', 'Promociones', '36', '784'),
('785', 'proveedores', 'proveedores', '36', '785'),
('786', 'proveedores', 'Proveedores', '36', '786'),
('787', 'provincia_categoria_iibb', 'Provincia Categoria IIBB', '36', '787'),
('788', 'provincia_iva_proveedor', 'Provincia IVA proveedor', '36', '788'),
('789', 'provincias', 'provincias', '36', '789'),
('790', 'remitos', 'remitos', '36', '790'),
('791', 'roles', 'Roles', '36', '791'),
('792', 'subcategoria', 'Sub Categoria', '36', '792'),
('793', 'subfamilias', 'Sub Familias', '36', '793'),
('794', 'sucursales', 'Sucursales', '36', '794'),
('795', 'tipo', 'Tipo', '36', '795'),
('796', 'tipo_comprobante', 'Tipo Comprobante', '36', '796'),
('797', 'tipo_documento', 'Tipo Documento', '36', '797'),
('798', 'tipo_iva', 'Tipo de IVA', '36', '798'),
('799', 'tipos_cajas', 'Tipos Cajas', '36', '799'),
('800', 'ubicacion', 'Ubicacion', '36', '800'),
('801', 'unidad', 'Unidad', '36', '801'),
('802', 'usuarios', 'Usuarios', '36', '802'),
('803', 'vendedores', 'Vendedores', '36', '803'),
('804', 'stock', 'Stock', '36', '804'),
('805', 'gastos', 'Gastos', '36', '805'),
('806', 'compras', 'Compras', '36', '806');
INSERT INTO funciones_roles (`rol_id`, `funcion_id`, `permite_crear`, `permite_ver`, `permite_modificar`, `permite_eliminar`, `permite_imprimir`, `permite_listar`, `empresa_id`) VALUES
('44', '763', '1', '0', '0', '0', '0', '1', '36'),
('44', '764', '0', '0', '0', '0', '0', '1', '36'),
('44', '765', '0', '0', '0', '0', '0', '1', '36'),
('44', '766', '0', '0', '0', '0', '0', '0', '36'),
('44', '767', '0', '0', '0', '0', '0', '0', '36'),
('44', '768', '0', '0', '0', '0', '0', '0', '36'),
('44', '769', '1', '0', '0', '0', '0', '1', '36'),
('44', '770', '0', '0', '0', '0', '0', '0', '36'),
('44', '771', '0', '0', '0', '0', '0', '0', '36'),
('44', '772', '0', '0', '0', '0', '0', '0', '36'),
('44', '773', '0', '0', '0', '0', '0', '0', '36'),
('44', '774', '0', '0', '0', '0', '0', '0', '36'),
('44', '775', '0', '0', '0', '0', '0', '0', '36'),
('44', '776', '0', '0', '0', '0', '0', '0', '36'),
('44', '777', '0', '0', '0', '0', '0', '0', '36'),
('44', '778', '0', '0', '0', '0', '0', '0', '36'),
('44', '779', '0', '0', '0', '0', '0', '0', '36'),
('44', '780', '0', '0', '0', '0', '0', '0', '36'),
('44', '781', '0', '0', '0', '0', '0', '0', '36'),
('44', '782', '0', '0', '0', '0', '0', '0', '36'),
('44', '783', '0', '0', '0', '0', '0', '0', '36'),
('44', '784', '0', '0', '0', '0', '0', '1', '36'),
('44', '785', '0', '0', '0', '0', '0', '0', '36'),
('44', '786', '0', '0', '0', '0', '0', '0', '36'),
('44', '787', '0', '0', '0', '0', '0', '0', '36'),
('44', '788', '0', '0', '0', '0', '0', '0', '36'),
('44', '789', '0', '0', '0', '0', '0', '0', '36'),
('44', '790', '0', '0', '0', '0', '0', '0', '36'),
('44', '791', '0', '0', '0', '0', '0', '0', '36'),
('44', '792', '0', '0', '0', '0', '0', '0', '36'),
('44', '793', '0', '0', '0', '0', '0', '0', '36'),
('44', '794', '0', '0', '0', '0', '0', '0', '36'),
('44', '795', '0', '0', '0', '0', '0', '0', '36'),
('44', '796', '0', '0', '0', '0', '0', '0', '36'),
('44', '797', '0', '0', '0', '0', '0', '0', '36'),
('44', '798', '0', '0', '0', '0', '0', '0', '36'),
('44', '799', '0', '0', '0', '0', '0', '0', '36'),
('44', '800', '0', '0', '0', '0', '0', '0', '36'),
('44', '801', '0', '0', '0', '0', '0', '0', '36'),
('44', '802', '0', '0', '0', '0', '0', '0', '36'),
('44', '803', '0', '0', '0', '0', '0', '1', '36'),
('44', '804', '0', '0', '0', '0', '0', '0', '36'),
('44', '805', '1', '0', '0', '0', '0', '1', '36'),
('44', '806', '0', '0', '0', '0', '0', '0', '36'),
('45', '763', '1', '1', '1', '1', '1', '1', '36'),
('45', '764', '1', '1', '1', '1', '1', '1', '36'),
('45', '765', '1', '1', '1', '1', '1', '1', '36'),
('45', '766', '1', '1', '1', '1', '1', '1', '36'),
('45', '767', '1', '1', '1', '1', '1', '1', '36'),
('45', '768', '1', '1', '1', '1', '1', '1', '36'),
('45', '769', '1', '1', '1', '1', '1', '1', '36'),
('45', '770', '1', '1', '1', '1', '1', '1', '36'),
('45', '771', '1', '1', '1', '1', '1', '1', '36'),
('45', '772', '1', '1', '1', '1', '1', '1', '36'),
('45', '773', '1', '1', '1', '1', '1', '1', '36'),
('45', '774', '1', '1', '1', '1', '1', '1', '36'),
('45', '775', '1', '1', '1', '1', '1', '1', '36'),
('45', '776', '1', '1', '1', '1', '1', '1', '36'),
('45', '777', '1', '1', '1', '1', '1', '1', '36'),
('45', '778', '1', '1', '1', '1', '1', '1', '36'),
('45', '779', '1', '1', '1', '1', '1', '1', '36'),
('45', '780', '1', '1', '1', '1', '1', '1', '36'),
('45', '781', '1', '1', '1', '1', '1', '1', '36'),
('45', '782', '1', '1', '1', '1', '1', '1', '36'),
('45', '783', '1', '1', '1', '1', '1', '1', '36'),
('45', '784', '1', '1', '1', '1', '1', '1', '36'),
('45', '785', '1', '1', '1', '1', '1', '1', '36'),
('45', '786', '1', '1', '1', '1', '1', '1', '36'),
('45', '787', '1', '1', '1', '1', '1', '1', '36'),
('45', '788', '1', '1', '1', '1', '1', '1', '36'),
('45', '789', '1', '1', '1', '1', '1', '1', '36'),
('45', '790', '1', '1', '1', '1', '1', '1', '36'),
('45', '791', '1', '1', '1', '1', '1', '1', '36'),
('45', '792', '1', '1', '1', '1', '1', '1', '36'),
('45', '793', '1', '1', '1', '1', '1', '1', '36'),
('45', '794', '1', '1', '1', '1', '1', '1', '36'),
('45', '795', '1', '1', '1', '1', '1', '1', '36'),
('45', '796', '1', '1', '1', '1', '1', '1', '36'),
('45', '797', '1', '1', '1', '1', '1', '1', '36'),
('45', '798', '1', '1', '1', '1', '1', '1', '36'),
('45', '799', '1', '1', '1', '1', '1', '1', '36'),
('45', '800', '1', '1', '1', '1', '1', '1', '36'),
('45', '801', '1', '1', '1', '1', '1', '1', '36'),
('45', '802', '1', '1', '1', '1', '1', '1', '36'),
('45', '803', '1', '1', '1', '1', '1', '1', '36'),
('45', '804', '1', '1', '1', '1', '1', '1', '36'),
('45', '805', '1', '1', '1', '1', '1', '1', '36'),
('45', '806', '1', '1', '1', '1', '1', '1', '36');
INSERT INTO productos_stock (`id`, `codigo`, `stock_inicial`, `controla_stock`, `punto_pedido`, `largo`, `alto`, `ancho`, `peso`, `unidad_id`, `ubicacion_id`, `proveedores_id`, `producto_id`, `empresa_id`, `stock_actual`, `sucursal_id`, `id_remoto`) VALUES
('435', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '16155', '36', '-5', '25', '435'),
('4641', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '16156', '36', '-1', '25', '4641'),
('7125', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '51272', '36', '-1', '25', '7125'),
('7214', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '51297', '36', '-4', '25', '7214'),
('7233', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '51295', '36', '-1', '25', '7233'),
('7234', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '55589', '36', '-1', '25', '7234'),
('7235', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '51273', '36', '-1', '25', '7235'),
('7295', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '51296', '36', '-1', '25', '7295');
INSERT INTO renglones_comprobantes (`id`, `comprobante_id`, `producto_id`, `descripcion`, `cantidad`, `tasa_iva`, `precio_unitario`, `descuento`, `total_linea`, `id_remoto`) VALUES
('9736', '4339', '16155', 'Prueba', '1', '0.21', '1000', '0', '1000', '9736'),
('9799', '4375', '16155', 'Venta 21', '1', '0.21', '200000', '0', '200000', '9799'),
('33105', '14414', '16156', 'Facturador movil Baiwang', '1', '0.105', '650000', '0', '650000', '33105'),
('39291', '16977', '16155', 'Visita tecnica', '1', '0.21', '24800', '0', '24800', '39291'),
('39292', '16978', '16155', 'Visita tecnica', '1', '0.21', '24800', '0', '24800', '39292'),
('51546', '22365', '16155', 'Diferencial costos materiales de presupuesto Nº4284', '1', '0.21', '280000', '0', '280000', '51546'),
('51547', '22365', '51272', 'Mano de Obra', '1', NULL, '460000', '0', '460000', '51547'),
('55785', '24038', '51297', 'Soporte Mensual', '1', '0.21', '60000', '0', '60000', '55785'),
('55786', '24039', '51297', 'Soporte Mensual mes 8 y 9', '2', '0.21', '33880', '0', '67760', '55786'),
('56218', '24237', '51295', 'Reparacion balanza Kretz Report 6 teclas, cambio de mascara y reparacion de impresor', '1', '0.21', '120000', '0', '120000', '56218'),
('56219', '24237', '55589', 'Reparacion bascula Kretz cambio de celda {Usada} calibracion y cambio de fuente', '1', '0.21', '98000', '0', '98000', '56219'),
('56220', '24237', '51273', 'Mantenimiento contadoras de billetes', '1', '0.21', '28000', '0', '28000', '56220'),
('56221', '24237', '51297', 'Mantenimiento Web', '1', '0.21', '34000', '0', '34000', '56221'),
('56279', '24256', '51296', 'Reparacion equipo', '1', '0.105', '98000', '0', '98000', '56279');
INSERT INTO tipo_comprobante (`id`, `nombre`, `id_remoto`) VALUES
('1', 'FC', '1'),
('2', 'RTO', '2'),
('3', 'PDO', '3'),
('4', 'NC', '4');
