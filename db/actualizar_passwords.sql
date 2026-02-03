-- =============================================
-- ACTUALIZAR CONTRASEÑAS DE USUARIOS DE PRUEBA
-- Contraseña: escuela2026
-- =============================================

USE escuela_pablo_neruda;

UPDATE usuarios 
SET password_hash = '$2y$10$vSiuSCx2tBAcqV7BGy.tz.jB7FMpC0t09HGsA8yJuSRMV8HK2rxJ6'
WHERE username IN ('admin', 'director', 'profesor');

-- Verificar actualización
SELECT id_usuario, username, email, nombre_completo, estado, 
       LEFT(password_hash, 20) as hash_preview
FROM usuarios 
WHERE username IN ('admin', 'director', 'profesor');
