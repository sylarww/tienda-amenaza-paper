<?php
$servidor = "localhost";
$usuario = "root";
$clave ="";
$baseDeDatos = "pepelerias";
$enlace = mysqli_connect($servidor,$usuario,$clave,$baseDeDatos);
?>
<?php

$productos = [
    ["Bolígrafos", "Muy usados en escuela y oficina.", "https://http2.mlstatic.com/D_NQ_NP_797523-MLM73479939143_122023-O-lapiceros-boligrafos-finos-elegantes-tinta-gel-negro-clasico.webp"],
    ["Cuadernos / Libretas", "Para apuntes y tareas.", "https://st.depositphotos.com/1875497/3781/i/450/depositphotos_37810929-stock-photo-books-on-white.jpg"],
    ["Hojas de papel", "Papel bond y hojas sueltas.", "https://http2.mlstatic.com/D_NQ_NP_917417-MLU74232610634_022024-O.webp"],
    ["Lápices", "Lápices de grafito y escolares.", "https://m.media-amazon.com/images/I/61Vu16aIRVL._AC_UF350,350_QL80_.jpg"],
    ["Gomas de borrar", "Borradores escolares.", "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTfLm03fqnACjbhTfzJX9HIBBrUoNk7SX7hnSIgyEHJk4LyjRAwGdRjMLGgzw4oWIC2esA&usqp=CAU"],
    ["Pegamento / Adhesivos", "Pegamento en barra y líquido.", "https://http2.mlstatic.com/D_NQ_NP_896271-MLA95502947045_102025-O.webp"],
    ["Resaltadores / Marcadores", "Para estudio y apuntes.", "https://util.com.pe/cdn/shop/files/70_10-2-202.jpg?v=1753117774&width=1946"],
    ["Tijeras", "Recortar y actividades didacticas.", "https://www.latienditadelumina.com/cdn/shop/files/IMG_7462copia_1200x1200.jpg?v=1703963193"]
];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Catálogo de Papelería</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 20px;
      }
  h1 {
          text-align: center;
        margin-bottom: 30px;
        }
  .container {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }
.card {
            background: white;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
        }
  .card img {
            width: 100%;
            height: 120px;
            object-fit: cover;
            border-radius: 8px;
        }
      .card h3 {
            margin: 10px 0 5px;
        }
        .card img:hover {
    transform: scale(1.08) rotate(-1deg);
    filter: brightness(1.15) contrast(1.1); 
    box-shadow: 
        0 6px 20px rgba(0,0,0,0.28),
        inset 0 0 8px rgba(255,255,255,0.4);
}
.card img {
    width: 100%;              
    max-width: 220px;         
    height: 180px;             
    object-fit: cover;      
    border-radius: 15px;     
    border: 3px solid #e0e0e0; 
    padding: 4px;           
    background: #fff;          /* Fondo para contraste */
}
nav{
  background-color: #e0e5e671;
    text-align: left;
    padding: 15px 20px;
    border-bottom: 1px solid #cffafcff;
  }
  nav a {
    color: #556B2F;
    margin: 0 20px 0 0;
    text-decoration: none;
    font-weight: 500;
    transition: color 0.3s;
  }
  nav a:hover {
    color: #333333;
    text-decoration: underline;
  }
    </style>
</head>
 <nav>
   <a href="index.php" target="_blank"> INICIO </a> 
    <a href="productos.php">CATALOGO ILUSTRATIVO</a>
    <a href="compra.php">COMPRA</a>
   <a href="login.html">INVENTARIO</a>
   
  </nav>

<body>

<h1>Catálogo de Productos</h1>

<div class="container">
    <?php foreach ($productos as $p): ?>
        <div class="card">
            <img src="<?php echo $p[2]; ?>" alt="<?php echo $p[0]; ?>">
            <h3><?php echo $p[0]; ?></h3>
            <p><?php echo $p[1]; ?></p>
        </div>
    <?php endforeach; ?>
</div>

</body>
</html>