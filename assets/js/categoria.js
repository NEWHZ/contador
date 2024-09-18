// assets/js/categoria.js

function openAddModal() {
	document.getElementById("categoriaForm").reset();
	document.getElementById("categoriaId").value = "";
	document.getElementById(
		"categoriaForm"
	).action = `${BASE_URL}categorias/store`;
	$("#categoriaModal").modal("show");
}

function editCategoria(id) {
	fetch(`${BASE_URL}categorias/edit/${id}`)
		.then((response) => {
			if (!response.ok) {
				throw new Error("No se pudo obtener la categoría.");
			}
			return response.json();
		})
		.then((data) => {
			document.getElementById("nombre").value = data.nombre;
			document.getElementById("precio").value = data.precio;
			document.getElementById("categoriaId").value = id;
			document.getElementById(
				"categoriaForm"
			).action = `${BASE_URL}categorias/update/${id}`;
			$("#categoriaModal").modal("show");
		})
		.catch((error) => {
			Swal.fire({
				icon: "error",
				title: "Error",
				text: error.message,
			});
		});
}

function deleteCategoria(id) {
	Swal.fire({
		title: "¿Estás seguro?",
		text: "¡No podrás revertir esto!",
		icon: "warning",
		showCancelButton: true,
		confirmButtonColor: "#3085d6",
		cancelButtonColor: "#d33",
		confirmButtonText: "Sí, eliminarlo",
	}).then((result) => {
		if (result.isConfirmed) {
			window.location.href = `${BASE_URL}categorias/delete/${id}`;
		}
	});
}
