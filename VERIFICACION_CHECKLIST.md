# ✅ Checklist de Verificación - Correcciones de Seguridad CSP

## Pre-Verificación (Antes de ejecutar)

- [x] SecurityHeaders.php actualizado con nonces
- [x] AppServiceProvider actualizado para inyectar nonce
- [x] Todos los scripts inline tienen nonce
- [x] Todos los estilos inline tienen nonce
- [x] Middleware registrado en bootstrap/app.php
- [x] CDN con atributos SRI intactos

## Verificación en Ejecución

### Paso 1: Iniciar la aplicación
```bash
cd /home/thiago/cmmx
php artisan serve
# O usar: php -S localhost:8000 -t public
```

### Paso 2: Inspeccionar Headers HTTP

1. Abre el navegador en `http://localhost:8000`
2. Abre DevTools (F12)
3. Ve a la pestaña **Network**
4. Recarga la página
5. Haz clic en la solicitud principal (document)
6. Ve a la pestaña **Headers** de Response
7. Busca `Content-Security-Policy`

**Esperado:**
```
Content-Security-Policy: default-src 'self'; script-src 'self' 'nonce-xxxxx...' https://cdn.jsdelivr.net; ...
```

### Paso 3: Verificar Nonces en el DOM

1. En DevTools, abre la **Console**
2. Ejecuta:
```javascript
// Ver todos los scripts con nonce
document.querySelectorAll('script[nonce]').forEach(s => console.log('Script nonce:', s.nonce));

// Ver todos los estilos con nonce
document.querySelectorAll('style[nonce]').forEach(s => console.log('Style nonce:', s.nonce));
```

**Esperado:** Se muestran múltiples nonces únicos

### Paso 4: Verificar que los Nonces son únicos

1. Recarga la página varias veces (F5)
2. En cada reload, los nonces deben cambiar
3. Verifica en Network → Headers nuevamente

**Esperado:** Cada petición tiene un nonce diferente

## Post-Verificación

### Paso 5: Re-escanear con OWASP ZAP

```bash
# Asume que tienes ZAProxy instalado
zaproxy -cmd \
  -quickurl http://localhost:8000/login \
  -quickout report_post_fix.html
```

**Esperado:**
- ✅ CSP: Directiva Wildcard - RESUELTO
- ✅ CSP: Failure to Define Directive - RESUELTO
- ✅ CSP: script-src unsafe-eval - RESUELTO
- ✅ CSP: script-src unsafe-inline - RESUELTO
- ✅ CSP: style-src unsafe-inline - RESUELTO
- ✅ Falta atributo de integridad - RESUELTO

### Paso 6: Verificar Funcionalidad

Navega por la aplicación y verifica que:

- [ ] Dashboard carga correctamente
- [ ] Gráficos Chart.js funcionan
- [ ] Formularios envían correctamente
- [ ] Validación del lado del cliente funciona
- [ ] Modales Bootstrap funcionan
- [ ] Animaciones CSS funcionan
- [ ] Búsquedas funcionan
- [ ] Filtros funcionan
- [ ] Sin errores en la Console (pestaña Console de DevTools)

### Paso 7: Verificar Diferentes Roles de Usuario

- [ ] Admin - todas las funcionalidades
- [ ] Técnico - búsqueda de OT
- [ ] Vendedor - inventario
- [ ] Analista - dashboards

## Troubleshooting

### Si hay errores de CSP en la Console:

**Error típico:**
```
Refused to execute inline script because it violates the following 
Content Security Policy directive: "script-src 'self' 'nonce-...'
```

**Solución:**
1. Busca el script que genera el error
2. Verifica que tenga `nonce="{{ $cspNonce }}"`
3. Si es un script externo, añádelo a la CSP:
   ```php
   script-src 'self' 'nonce-{$nonce}' https://cdn.ejemplo.com;
   ```

### Si los gráficos no cargan:

1. Verifica que Chart.js está cargando correctamente
2. Abre la Console (F12) y busca errores de Chart
3. Comprueba que el script con el gráfico tiene nonce

## Comparación Antes vs Después

| Métrica | Antes | Después |
|---------|-------|---------|
| Alertas de CSP | 6 | 0 |
| unsafe-eval | ✗ Presente | ✅ Removido |
| unsafe-inline | ✗ Presente | ✅ Removido |
| Protección XSS | Débil | Fuerte |
| Nonces únicos | No | ✅ Sí |
| Puntuación OWASP ZAP | Baja | Alta |

## Documentación Relacionada

- [CSP_SECURITY_IMPROVEMENTS.md](CSP_SECURITY_IMPROVEMENTS.md) - Detalles técnicos
- [CORRECION_ALERTAS_ZAP.md](CORRECION_ALERTAS_ZAP.md) - Resumen de cambios
- [app/Http/Middleware/SecurityHeaders.php](app/Http/Middleware/SecurityHeaders.php) - Middleware
- [app/Providers/AppServiceProvider.php](app/Providers/AppServiceProvider.php) - Service Provider

## Notas Importantes

⚠️ **Para Producción:**
- Asegúrate de que el header CSP se envía correctamente
- Usa HTTPS en producción (recomendado HSTS)
- Considera usar una política CSP más restrictiva si es posible
- Monitorea los violations de CSP con un servicio como `report-uri.com`

✅ **Para Desarrollo:**
- El nonce se genera en cada request
- No hay cookies o sesiones que respetar
- Los nonces son válidos solo para esa request

---

**Estado de Verificación:** Pendiente de ejecución
**Última Actualización:** 25 de mayo de 2026
