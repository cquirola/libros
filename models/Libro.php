<?php

class Libro extends DB {

    public $id;
    public $titulo;
    public $autor;
    public $anio_publicacion;
    public $genero;

    public static function all() {
        $db = new DB();
        $prepare = $db->prepare( 'SELECT * FROM libros' );
        $prepare->execute();

        return $prepare->fetchAll( PDO::FETCH_CLASS, Libro::class );
    }

    public static function find( $id ) {
        $bd = new DB();
        $prepare = $bd->prepare( 'SELECT * FROM libros WHERE id=:id' );
        $prepare->execute( [ ':id'=>$id ] );

        return $prepare->fetchObject( Libro::class );
    }

    public function remove() {
        $prepare = $this->prepare( 'DELETE FROM libros WHERE id=:id' );
        $prepare->execute( [ ':id'=>$this->id ] );
    }

    public function save() {
        $params = [ ':titulo'=>$this->titulo, ':vecimiento'=>$this->autor ];
        if ( empty( $this->id ) ) {
            $prepare = $this->prepare( 'INSERT INTO libros(titulo,vecimiento,anio_publicacion,genero) VALUES(:titulo,:vecimiento,:anio_publicacion,:genero)' );
            $prepare->execute( $params );

            $prepare2 = $this->prepare( 'SELECT MAX(id) id FROM Libros' );
            $prepare2->execute();
            $this->id = $prepare2->fetch( PDO::FETCH_ASSOC )[ 'id' ];
        } else {
            $params[ ':id' ] = $this->id;
            $prepare = $this->prepare( 'UPDATE libros SET titulo=:titulo, vecimiento=:vecimiento, anio_publiacion=:anio_publicacion, genero=:genero WHERE id=:id' );
            $prepare->execute( $params );
        }
    }

}

?>
