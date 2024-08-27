<?php

class LibrosController {

    public function index() {
        $libros = Libro::all();        
        view( 'libros.lista', [ 'libros'=>$libros, 'user'=>'Juanito Perez' ] );
    }
}
?>
