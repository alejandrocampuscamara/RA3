<?php
class Variante {
    public $color;
    public $almacenamiento;
    public $precio;
    
    public function __construct($color, $almacenamiento, $precio) {
        $this->color = $color;
        $this->almacenamiento = $almacenamiento;
        $this->precio = $precio;
    }
}

class Producto {
    public $nombre;
    public $marca;
    public $precio;
    public $variantes = [];
    
    public function __construct($nombre, $marca, $precio, $variantes = []) {
        $this->nombre = $nombre;
        $this->marca = $marca;
        $this->precio = $precio;
        foreach ($variantes as $variante) {
            $this->agregarVariante(
                new Variante($variante["color"], $variante["almacenamiento"] ?? "N/A", $variante["precio"])
            );
        }
    }
    
    public function agregarVariante($variante) {
        $this->variantes[] = $variante;
    }
}

class Categoria {
    public $nombre;
    public $productos = [];
    
    public function __construct($nombre, $productos = []) {
        $this->nombre = $nombre;
        foreach ($productos as $producto) {
            $this->agregarProducto(
                new Producto($producto["nombre"], $producto["marca"], $producto["precio"], $producto["variantes"])
            );
        }
    }
    
    public function agregarProducto($producto) {
        $this->productos[] = $producto;
    }
}

class Tienda {
    public $categorias = [];
    
    public function __construct($categorias = []) {
        foreach ($categorias as $categoria) {
            $this->agregarCategoria(new Categoria($categoria["nombre"], $categoria["productos"]));
        }
    }
    
    public function agregarCategoria($categoria) {
        $this->categorias[] = $categoria;
    }

    public function listarCategorias() {
        foreach ($this->categorias as $categoria) {
            echo "Categoría: {$categoria->nombre}\n";
        }
    }
    
    public function listarProductosPorCategoria($nombreCategoria) {
        foreach ($this->categorias as $categoria) {
            if ($categoria->nombre === $nombreCategoria) {
                echo "Productos en la categoría '{$nombreCategoria}':\n";
                foreach ($categoria->productos as $producto) {
                    echo "- {$producto->nombre} ({$producto->marca}) - Precio: {$producto->precio}\n";
                }
                return;
            }
        }
        echo "Categoría no encontrada.\n";
    }
}

// Cargar el JSON
$jsonData = file_get_contents("tienda.json");
$dataArray = json_decode($jsonData, true);

// Instanciar la tienda
$tienda = new Tienda($dataArray['categorias']);

// Mostrar las categorías
echo "Categorías Disponibles:\n";
$tienda->listarCategorias();

// Mostrar productos de una categoría específica
echo "\nProductos de la categoría 'Electrónica':\n";
$tienda->listarProductosPorCategoria("Electrónica");

?>
