# NexoFiscal

Plataforma PHP para gestión fiscal, facturación electrónica AFIP, ventas, compras, stock y generación de comprobantes (facturas, tickets, remitos, presupuestos, etc.).

## Características principales
- Integración AFIP (SDK afipsdk/afip.php) para emisión de comprobantes electrónicos.
- Gestión de clientes, proveedores, productos, stock y precios.
- Generación y reimpresión de comprobantes (factura, ticket, remito, pedido, presupuesto, cierre de caja, acopio / desacopio, notas).
- Exportaciones Excel (PhpSpreadsheet) y PDF (FPDF / HTML2PDF) incluyendo QR AFIP (chillerlan/php-qrcode).
- Interfaz web con Bootstrap 5 y múltiples componentes JS.
- Módulos de balances, caja, libro IVA ventas, reportes y paneles gráficos (Chart.js, ECharts, Morris, etc.).

## Requisitos
- PHP >= 8.0 (recomendado) con extensiones: curl, json, mbstring, openssl, zip, gd.
- Composer
- Servidor web (Apache recomendado con mod_rewrite) o stack XAMPP.
- Acceso a servicios AFIP (CUIT, Certificado, Clave Privada, entorno homologación / producción).
- Base de datos (MySQL/MariaDB). (Definir esquemas: pendiente documentar si no está incluido.)

## Instalación
1. Clonar el repositorio:
```
git clone https://github.com/nicobernasconi/nexofiscal_2025.git
cd nexofiscal_2025
```
2. Instalar dependencias PHP:
```
composer install --no-dev
```
3. Copiar variables de entorno:
```
copy .env.example .env   # Windows PowerShell
```
4. Configurar archivo `.env` (ver sección Variables de entorno) y credenciales AFIP.
5. Configurar VirtualHost o usar `http://localhost/nexofiscal` bajo XAMPP.
6. Ajustar permisos de escritura (logs, temp, exportaciones, si aplica).

## Variables de entorno (.env)
Ejemplo básico en `.env.example`:
```
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost/nexofiscal
AFIP_HOMOLOGACION=true
AFIP_CUIT=XXXXXXXXXXX
AFIP_CERT=storage/certs/cert.crt
AFIP_KEY=storage/certs/priv.key
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nexofiscal
DB_USERNAME=root
DB_PASSWORD=
TIMEZONE=America/Argentina/Buenos_Aires
```
Ajustar rutas de certificados AFIP y credenciales BD. Si hay múltiples puntos de venta, documentarlos.

## Dependencias principales
| Paquete | Uso |
|---------|-----|
| afipsdk/afip.php | Conexión e interacción con AFIP (WSFE, etc.) |
| firebase/php-jwt & lcobucci/jwt | Tokens / autenticación (si aplica) |
| phpoffice/phpspreadsheet | Exportación Excel |
| setasign/fpdf & spipu/html2pdf | Generación PDF |
| chillerlan/php-qrcode | Códigos QR (AFIP) |
| guzzlehttp/guzzle | HTTP cliente |
| twbs/bootstrap | UI framework |

## Scripts comunes
(No existe todavía composer.json con scripts definidos. Se pueden agregar por ejemplo:)
```
"scripts": {
  "post-install-cmd": ["php artisan optimize"],
  "lint": "php -l"    
}
```
(Adaptar según framework interno si se incorpora uno.)

## Estructura (parcial)
- `print_*.php` generación / render de distintos comprobantes.
- `_wsafip_*.php` integración AFIP / utilidades.
- `productos_*.php` gestión avanzada de productos.
- `empresas_*.php` módulos administrativos por empresa.
- `composer.json` dependencias backend.

## Flujo AFIP (alto nivel)
1. Preparar datos del comprobante (cliente, ítems, alícuotas, IVA, total). 
2. Obtener token/TA (si no vigente) usando credenciales y certificados.
3. Generar CAE vía WSFE.
4. Guardar respuesta (CAE, vencimiento) y generar PDF con código QR.
5. Registrar en libro IVA / ventas y permitir reimpresión.

## Tareas pendientes sugeridas
- Documentar estructura DB (crear `docs/db_schema.sql`).
- Añadir migraciones / seeds (si se adopta un micro framework o propio).
- Crear tests básicos (unitarios para helpers AFIP, generación QR, cálculo IVA).
- Configurar CI (GitHub Actions: composer install + lint + pruebas).
- Añadir control de acceso / autenticación formal si usa JWT.
- Definir licencia (ver más abajo).

## Contribución
1. Crear rama feature: `git checkout -b feature/nombre`.
2. Commit con mensajes claros (Convencional: feat:, fix:, refactor:, docs:, etc.).
3. Pull Request hacia `main` con descripción y pasos de prueba.
4. Asegurar que no se suban archivos ignorados (vendor, .env, etc.).

## Licencia
Actualmente sin licencia explícita. Se recomienda MIT si será open source:
```
MIT License
Copyright (c) 2025 NexoFiscal
```
(Se puede crear archivo LICENSE para formalizar.)

## Seguridad
- Nunca commitear certificados, claves privadas ni .env.
- Rotar certificados AFIP antes de expiración.
- Validar inputs en formularios (XSS/CSRF – evaluar añadir token CSRF).

## Soporte / Contacto
Abrir Issues en GitHub o contactar al mantenedor.

---
Generado inicialmente por asistente automatizado. Ajustar y ampliar según evolucione el proyecto.
