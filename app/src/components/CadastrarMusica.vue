<template>
	<div>
		<div class="row">
			<div class="col-12 h4">CADASTRAR MÚSICA</div>
		</div>
		<div class="row mt-4">
			<div class="col-12">
				<div class="mb-3" v-if="id">
					<label for="id" class="form-label">ID</label>
					<input type="text" class="form-control" id="id" v-model="dataForm.id" disabled />
				</div>
				<div class="mb-3" v-if="!estilos">
					<div class="row">
						<div class="col-10">
							<label for="estilo" class="form-label">Estilo</label>
							<input type="text" class="form-control" id="estilo" v-model="dataForm.estilo" @change="loadCantores()" />
						</div>
						<div class="col-2 p-0 mt-4 pt-2">
							<button class="btn btn-light" type="button" @click="volta_estilo()">
								<i class="bi bi-list h4"></i>
							</button>
						</div>
					</div>
				</div>
				<div class="mb-3" v-else>
					<div class="row">
						<div class="col-10">
							<label for="estilo" class="form-label">Estilo</label>
							<select class="form-select" id="estilo" v-model="dataForm.estilo" @change="troca_estilo()">
								<option :value="item.estilo" v-for="(item, i) in estilos" :key="i">{{ item.estilo }}</option>
							</select>
						</div>
						<div class="col-2 p-0 mt-4 pt-2">
							<button class="btn btn-light" type="button" @click="tira_estilo()">
								<i class="bi bi-plus h4"></i>
							</button>
						</div>
					</div>
				</div>
				<div class="mb-3" v-if="!cantores">
					<div class="row">
						<div class="col-10">
							<label for="cantor" class="form-label">Cantor</label>
							<input type="text" class="form-control" id="cantor" v-model="dataForm.cantor" />
						</div>
						<div class="col-2 p-0 mt-4 pt-2">
							<button class="btn btn-light" type="button" @click="volta_cantor()">
								<i class="bi bi-list h4"></i>
							</button>
						</div>
					</div>
				</div>
				<div class="mb-3" v-else>
					<div class="row">
						<div class="col-10">
							<label for="cantor" class="form-label">Cantor</label>
							<select class="form-select" id="cantor" v-model="dataForm.cantor">
								<option :value="item.cantor" v-for="(item, i) in cantores" :key="i">{{ item.cantor }}</option>
							</select>
						</div>
						<div class="col-2 p-0 mt-4 pt-2">
							<button class="btn btn-light" type="button" @click="tira_cantor()">
								<i class="bi bi-plus h4"></i>
							</button>
						</div>
					</div>
				</div>
				<div class="mb-3">
					<label for="musica" class="form-label">Música</label>
					<input type="text" class="form-control" id="musica" v-model="dataForm.musica" />
				</div>
			</div>
		</div>
		<div class="row mb-3" v-if="error">
			<div class="col-12 text-danger">
				{{ error }}
			</div>
		</div>
		<div class="row mt-2">
			<div class="col-6">
				<button class="btn btn-success w-100" @click="cadastrar_musica">Cadastrar</button>
			</div>
			<div class="col-6">
				<button class="btn btn-primary w-100" @click="voltar">Voltar</button>
			</div>
		</div>
	</div>
</template>

<script setup>
	import { ref, onMounted } from 'vue';
	import { useRouter } from 'vue-router';
	import { api } from '../api';

	const props = defineProps({
		id: String,
	});

	const router = useRouter();

	const dataForm = ref({});
	const error = ref(null);
	const lista = ref([]);
	const estilos = ref(null);
	const cantores = ref({});

	onMounted(async () => {
		if (props.id) {
			const res = await api.get('musicas/?action=musica&id=' + props.id);
			if (res.data.data) {
				dataForm.value = res.data.data[0];
			}
		}
		const res = await api.get('listas/?action=estilos');
		if (res.data.data) {
			lista.value = res.data.data;
			loadEstilos();
			loadCantores();
		}
	});

	function loadEstilos() {
		estilos.value = {};
		for (const i in lista.value) {
			const item = lista.value[i];
			if (!estilos.value[item.estilo]) {
				estilos.value[item.estilo] = item;
			}
		}
	}

	function loadCantores() {
		cantores.value = {};
		for (const i in lista.value) {
			const item = lista.value[i];
			if (!cantores.value[item.cantor] && item.estilo == dataForm.value.estilo) {
				cantores.value[item.cantor] = item;
			}
		}
	}

	function troca_estilo() {
		cantores.value = {};
		for (const i in lista.value) {
			const item = lista.value[i];
			if (!cantores.value[item.cantor] && item.estilo == dataForm.value.estilo) {
				cantores.value[item.cantor] = item;
			}
		}
	}

	function tira_estilo() {
		estilos.value = null;
		cantores.value = null;
	}

	function volta_estilo() {
		loadEstilos();
		cantores.value = {};
	}

	function tira_cantor() {
		cantores.value = null;
	}

	function volta_cantor() {
		troca_estilo();
	}

	async function cadastrar_musica() {
		error.value = null;
		if (!dataForm.value.estilo) {
			error.value = 'Estilo é requerido';
			return;
		} else if (!dataForm.value.cantor) {
			error.value = 'Cantor é requerido';
			return;
		} else if (!dataForm.value.musica) {
			error.value = 'Música é requerida';
			return;
		}
		const res = await api.post('musicas/?action=cadastrar', dataForm.value);
		if (res.data.error) {
			error.value = res.data.error;
		} else {
			alert('Música cadastrada com sucesso');
			if (props.id) {
				router.push('/musicas/' + dataForm.value.estilo + '/' + dataForm.value.cantor);
			} else {
				router.push('/menu');
			}
		}
	}

	function voltar() {
		router.push('/menu');
	}
</script>
