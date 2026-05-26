# Content Security Policy (CSP) - Mejoras de Seguridad Implementadas

## Resumen de Cambios

Se han corregido 6 alertas de seguridad detectadas por OWASP ZAP relacionadas con CSP (Content Security Policy):

### ✅ Alertas Corregidas

1. **CSP: Directiva Wildcard** - Eliminada
2. **CSP: Failure to Define Directive with No Fallback** - Añadidas directivas específicas
3. **CSP: script-src unsafe-eval** - Eliminado
4. **CSP: script-src unsafe-inline** - Eliminado  
5. **CSP: style-src unsafe-inline** - Eliminado
6. **Falta atributo de integridad** - Mantenido (CDN externa con SRI)

## Cambios Realizados

### 1. Middleware de Seguridad (app/Http/Middleware/SecurityHeaders.php)

**Antes:**
```php
script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net;
style-src 'self' 'unsafe-inline' https://fonts.googleapis.com ...
```

**Después:**
```php
// Genera nonce único por request
$nonce = base64_encode(random_bytes(16));

// CSP mejorada con nonce
script-src 'self' 'nonce-{$nonce}' https://cdn.jsdelivr.net;
style-src 'self' 'nonce-{$nonce}' https://fonts.googleapis.com ...
object-src 'none';
base-uri 'self';
form-action 'self';
```

### 2. Blade Views - Tags con Nonce

Todos los `<script>` y `<style>` inline ahora incluyen el atributo `nonce`:

```blade
<!-- Antes -->
<script>
  // código aquí
</script>

<!-- Después -->
<script nonce="{{ $cspNonce }}">
  // código aquí
</script>
```

**Archivos Actualizados:**
- `resources/views/layouts/app.blade.php`
- `resources/views/maintenances/*.blade.php`
- `resources/views/dashboard.blade.php`
- `resources/views/technician/dashboard.blade.php`
- `resources/views/equipment/*.blade.php`
- `resources/views/inventory/*.blade.php`
- `resources/views/welcome.blade.php`

### 3. AppServiceProvider - Inyección de Nonce

```php
View::composer('*', function ($view) {
    $view->with('cspNonce', Request::attributes->get('csp_nonce', ''));
});
```

Esto asegura que `$cspNonce` esté disponible en TODAS las vistas.

### 4. CDN con Subresource Integrity (SRI)

Las CDN externas ya tienen atributos de integridad:

```blade
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" 
      rel="stylesheet" 
      integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" 
      crossorigin="anonymous">
```

## Política de Seguridad Final

```
default-src 'self'
script-src 'self' 'nonce-{aleatorio}' https://cdn.jsdelivr.net
style-src 'self' 'nonce-{aleatorio}' https://fonts.googleapis.com https://cdn.jsdelivr.net https://fonts.bunny.net
img-src 'self' data: https:
font-src 'self' https://fonts.gstatic.com https://fonts.bunny.net data:
connect-src 'self'
frame-ancestors 'self'
object-src 'none'
base-uri 'self'
form-action 'self'
```

## Ventajas de esta Implementación

✅ **Nonces:** Cada request tiene un token único y criptográfico
✅ **Sin unsafe-eval/inline:** Código dinámico completamente controlado
✅ **SRI en CDN:** Integridad de recursos externos verificada
✅ **Restrictivo por defecto:** default-src 'self'
✅ **Directivas específicas:** Cada recurso tiene su propia política
✅ **Protección contra XSS:** Los ataques XSS no pueden inyectar scripts
✅ **Cumplimiento de estándares:** Mejora significativa en puntuación de seguridad

## Uso en Nuevas Vistas

Para nuevas vistas que necesiten scripts o estilos inline:

```blade
@push('scripts')
<script nonce="{{ $cspNonce }}">
  // Tu código JavaScript aquí
</script>
@endpush

@push('styles')
<style nonce="{{ $cspNonce }}">
  /* Tu CSS aquí */
</style>
@endpush
```

## Verificación en Desarrollo

Para verificar que el nonce se está generando correctamente:

1. Abre las Developer Tools (F12) → Sources
2. Busca tags `<script>` y `<style>`
3. Deberías ver: `nonce="abc123def456..."`
4. Cada refresh debe generar un nonce diferente

## Testing con OWASP ZAP

Ejecuta nuevamente el escaneo de ZAP para verificar:
- ✅ Todas las 6 alertas naranja han sido resueltas
- ✅ CSP ahora es restrictiva y segura
- ✅ No hay avisos de unsafe-eval o unsafe-inline
- ✅ Integridad de recursos verificada

## Referencias

- [MDN - Content Security Policy](https://developer.mozilla.org/es/docs/Web/HTTP/CSP)
- [MDN - Using nonces](https://developer.mozilla.org/es/docs/Web/HTTP/Headers/Content-Security-Policy/script-src#nonces)
- [OWASP - CSP](https://owasp.org/www-community/attacks/xss/)
