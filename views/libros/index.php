<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Listado de Libros</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>

<body>
  <div class="container">
    <h1 class="mt-5">Gestión de libros</h1>
    <button class="btn btn-success">Agregar</button>
    <table class="table table-striped mt-4" id="table">
      <thead>
        <tr>
          <th>Id</th>
          <th>Titulo</th>
          <th>Autor</th>
          <th>Año de publicación</th>
          <th>Genero</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($libros as $libro) : ?>
          <tr data-id="<?php echo $libro->id; ?>">
            <td><?php echo $libro->id; ?></td>
            <td><?php echo $libro->titulo; ?></td>
            <td><?php echo $libro->autor; ?></td>
            <td><?php echo $libro->anio_publicacion; ?></td>
            <td><?php echo $libro->genero; ?></td>
            <td>
              <button class="btn btn-warning btnEditar">Editar</button>
              <button class="btn btn-danger btnEliminar">Eliminar</button>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <div class="modal fade" id="librosModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Crear Libro</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="form-floating mb-3">
            <input type="text" name="titulo" class="form-control" placeholder="*">
            <label>titulo</label>
          </div>
          <div class="form-floating mb-3">
            <input type="text" name="autor" class="form-control" placeholder="*">
            <label>autor</label>
          </div>
          <div class="form-floating mb-3">
            <input type="text" name="anio_publicacion" class="form-control" placeholder="*">
            <label>anio_publicacion</label>
          </div>
          <div class="form-floating mb-3">
            <input type="text" name="genero" class="form-control" placeholder="*">
            <label>genero</label>
          </div>
        </div>
        <input type="hidden" id="identificador" value="">
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-primary btn-guardar">Guardar</button>
        </div>
      </div>
    </div>
  </div>
  <script>
    let myModal = new bootstrap.Modal(document.getElementById('librosModal'));

    const fetchLibro = (event) => {
      let id = event.target.parentNode.parentNode.dataset.id;
      axios.get(`http://localhost:8888/Actividades_Quirola_Camila/ExamenFinal/libros/find/${id}`).then((res) => {
        let info = res.data;
        document.querySelector("#exampleModalLabel").innerHTML = "Editar Libro";
        document.querySelector('input[name="titulo"]').value = info.titulo;
        document.querySelector('input[name="autor"]').value = info.autor;
        document.querySelector('input[name="anio_publicacion"]').value = info.anio_publicacion;
        document.querySelector('input[name="genero"]').value = info.genero;
        document.querySelector('#identificador').value = id;
        myModal.show();
      })
    }

    const deleteLibro = (event) => {
      let id = event.target.parentNode.parentNode.dataset.id;
      axios.delete(`http://localhost:8888/Actividades_Quirola_Camila/ExamenFinal/libros/delete/${id}`).then((res) => {
        let info = res.data;
        if (info.status) {
          document.querySelector(`tr[data-id="${id}"]`).remove();
        }
      })
    }

    document.querySelector('.btn.btn-success')
      .addEventListener('click', () => {
        document.querySelector("#exampleModalLabel").innerHTML = "Crear Libro";
        document.querySelector('input[name="autor"]').value = ""
        document.querySelector('input[name="titulo"]').value = ""
        document.querySelector('input[name="anio_publicacion"]').value = ""
        document.querySelector('input[name="genero"]').value = ""
        myModal.show();

      });
      
    document.querySelector('.btn-guardar')
      .addEventListener('click', () => {
        let titulo = document.querySelector('input[name="titulo"]').value;
        let autor = document.querySelector('input[name="autor"]').value;
        let anio_publicacion = document.querySelector('input[name="anio_publicacion"]').value;
        let genero = document.querySelector('input[name="genero"]').value;
        let id = document.querySelector('#identificador').value;
        axios.post(`http://localhost:8888/Actividades_Quirola_Camila/ExamenFinal/libros/${id == "" ? 'create' : 'update'}`, {
            titulo,
            autor,
            id
          })
          .then((r) => {
            let info = r.data;
            if (id == "") {
              // Agregar
              let tr = document.createElement("tr");
              tr.setAttribute('data-id', info.id);
              tr.innerHTML = `<td>${info.id}</td>
                              <td>${info.titulo}</td>
                              <td>${info.autor}</td>
                              <td>${info.anio_publicacion}</td>
                              <td>${info.genero}</td>
                              <td><button class='btn btn-warning btnEditar'>Editar</button>
                              <button class='btn btn-danger btnEliminar'>Eliminar</button></td>`;
              document.getElementById("table")
                .querySelector("tbody").append(tr);
              tr.querySelector('td:last-child .btnEditar')
                .addEventListener('click', fetchLibro);
              tr.querySelector('td:last-child .btnEliminar')
                .addEventListener('click', deleteLibro);
            } else {
              // Editar
              let tr = document.querySelector(`tr[data-id="${id}"]`);
              let cols = tr.querySelectorAll("td");
              cols[1].innerText = info.titulo;
              cols[2].innerText = info.autor;
              cols[3].innerText = info.anio_publicacion;
              cols[4].innerText = info.genero;
            }
            myModal.hide();

          })
      })
    let btnsEditar = document.querySelectorAll('.btnEditar');
    let btnsEliminar = document.querySelectorAll('.btnEliminar');
    for (let i = 0; i < btnsEditar.length; i++) {
      btnsEditar[i].addEventListener('click', fetchLibro);
      btnsEliminar[i].addEventListener('click', deleteLibro);
    }
  </script>
</body>

</html>