function openAddModal() {
	// Cambiar el título del modal a "Añadir"
	document.getElementById("workspaceModalLabel").innerText =
		"Añadir Espacio de Trabajo";

	// Restablecer el formulario y preparar para insertar
	document.getElementById("workspaceForm").reset();
	document.getElementById("imagenPreview").innerHTML = ""; // Limpiar previsualización de imagen
	document.getElementById("workspaceForm").action = BASE_URL + "espacios/store"; // Acción para guardar

	// Restablecer la selección de la categoría
	document.getElementById("categoria_id").value = ""; // Restablecer el selector de categorías

	// Abrir el modal
	var myModal = new bootstrap.Modal(document.getElementById("workspaceModal"), {
		keyboard: false,
	});
	myModal.show();
}

function editEspacio(id) {
	// Cambiar el título del modal a "Editar"
	document.getElementById("workspaceModalLabel").innerText =
		"Editar Espacio de Trabajo";

	// Obtener los datos del espacio mediante AJAX
	fetch(BASE_URL + "espacios/edit/" + id)
		.then((response) => response.json())
		.then((data) => {
			// Rellenar el formulario con los datos del espacio
			document.getElementById("workspaceId").value = data.id;
			document.getElementById("nombre").value = data.nombre;
			document.getElementById("descripcion").value = data.descripcion;
			document.getElementById("estado").value = data.estado;
			document.getElementById("color").value = data.color_fondo;

			// Establecer la categoría seleccionada
			document.getElementById("categoria_id").value = data.categoria_id;

			// Previsualizar la imagen si existe
			if (data.imagen) {
				document.getElementById(
					"imagenPreview"
				).innerHTML = `<img src="data:image/jpeg;base64,${data.imagen}" class="img-thumbnail mt-2" width="100">`;
			} else {
				document.getElementById("imagenPreview").innerHTML = "Sin imagen";
			}

			// Cambiar la acción del formulario para actualizar
			document.getElementById("workspaceForm").action =
				BASE_URL + "espacios/update/" + id;

			// Abrir el modal
			var myModal = new bootstrap.Modal(
				document.getElementById("workspaceModal"),
				{
					keyboard: false,
				}
			);
			myModal.show();
		});
}
// Selección de color de fondo
document.querySelectorAll(".color-circle").forEach((circle) => {
	circle.addEventListener("click", function () {
		// Eliminar la clase 'selected' de todos los círculos
		document
			.querySelectorAll(".color-circle")
			.forEach((c) => c.classList.remove("selected"));

		// Añadir la clase 'selected' al círculo actual
		this.classList.add("selected");

		// Almacenar el color seleccionado en el campo oculto
		const selectedColor = this.getAttribute("data-color");
		document.getElementById("color").value = selectedColor;
	});
});

// Restablecer el formulario y título cuando se cierra el modal
document
	.getElementById("workspaceModal")
	.addEventListener("hidden.bs.modal", function () {
		document.getElementById("workspaceForm").reset();
		document.getElementById("workspaceModalLabel").innerText =
			"Añadir Espacio de Trabajo";
		document.getElementById("imagenPreview").innerHTML = ""; // Limpiar previsualización de la imagen
		document.getElementById("workspaceForm").action =
			BASE_URL + "espacios/store"; // Cambiar la acción del formulario para agregar

		// Restablecer la selección de la categoría
		document.getElementById("categoria_id").value = ""; // Restablecer el selector de categorías
	});
