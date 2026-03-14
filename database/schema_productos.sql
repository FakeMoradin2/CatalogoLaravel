-- =====================================================
-- Base de datos para API de productos (Backend)
-- Ejecutar en MySQL Workbench para crear la estructura
-- =====================================================
--
-- FORMATO ESPERADO POR EL CLIENTE LARAVEL:
--
-- GET /products (listado):
-- { "products": [ { "id", "title", "description", "price", "thumbnail", "images": [urls] } ] }
--
-- GET /products/{id} (detalle):
-- { "id", "title", "description", "price", "stock", "images": [imagen_1, imagen_2, imagen_3], "thumbnail": imagen_1 }
--
-- El backend debe construir "images" como array con las URLs no nulas (imagen_1, imagen_2, imagen_3)
-- y "thumbnail" = imagen_1 para la vista de listado
--

-- Crear base de datos
CREATE DATABASE IF NOT EXISTS catalogo_api
  DEFAULT CHARACTER SET utf8mb4
  DEFAULT COLLATE utf8mb4_unicode_ci;

USE catalogo_api;

-- Tabla de productos
-- Las URLs de imágenes deben ser públicas para que el cliente las visualice
DROP TABLE IF EXISTS productos;

CREATE TABLE productos (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  description TEXT,
  price DECIMAL(10, 2) NOT NULL,
  stock INT UNSIGNED NOT NULL DEFAULT 0,
  imagen_1 VARCHAR(500) NOT NULL COMMENT 'URL pública de la imagen principal',
  imagen_2 VARCHAR(500) NULL COMMENT 'URL pública de la segunda imagen',
  imagen_3 VARCHAR(500) NULL COMMENT 'URL pública de la tercera imagen',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Índices para búsquedas frecuentes
CREATE INDEX idx_productos_price ON productos(price);
CREATE INDEX idx_productos_stock ON productos(stock);

-- Datos de ejemplo (URLs de DummyJSON para pruebas; reemplazar por tus propias URLs al subir imágenes)
INSERT INTO productos (title, description, price, stock, imagen_1, imagen_2, imagen_3) VALUES
('Essence Mascara Lash Princess', 'Máscara de pestañas conocida por su efecto volumizador y alargador. Fórmula duradera y libre de crueldad animal.', 9.99, 99, 'https://cdn.dummyjson.com/product-images/beauty/essence-mascara-lash-princess/1.webp', 'https://cdn.dummyjson.com/product-images/beauty/essence-mascara-lash-princess/1.webp', NULL),
('Eyeshadow Palette with Mirror', 'Paleta de sombras con espejo incorporado. Ideal para maquillaje sobre la marcha.', 19.99, 34, 'https://cdn.dummyjson.com/product-images/beauty/eyeshadow-palette-with-mirror/1.webp', NULL, NULL),
('Powder Canister', 'Polvo compacto para fijar el maquillaje y controlar el brillo. Fórmula ligera y translúcida.', 14.99, 89, 'https://cdn.dummyjson.com/product-images/beauty/powder-canister/1.webp', NULL, NULL),
('Laptop Pro 15', 'Laptop de alto rendimiento con procesador de última generación y 16GB RAM.', 1299.99, 25, 'https://cdn.dummyjson.com/product-images/5/1.jpg', 'https://cdn.dummyjson.com/product-images/5/2.jpg', 'https://cdn.dummyjson.com/product-images/5/3.jpg'),
('Smartphone X', 'Smartphone con pantalla AMOLED, cámara triple y batería de larga duración.', 599.99, 50, 'https://cdn.dummyjson.com/product-images/1/1.jpg', 'https://cdn.dummyjson.com/product-images/1/2.jpg', 'https://cdn.dummyjson.com/product-images/1/3.jpg');
