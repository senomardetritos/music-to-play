<template>
	<div>
		<div class="row">
			<div class="col-12 h4">LOGIN</div>
		</div>
		<div class="row mt-4">
			<div class="col-12">
				<div class="mb-3">
					<label for="email" class="form-label">Email</label>
					<input type="email" class="form-control" id="email" v-model="dataForm.email" />
				</div>
				<div class="mb-3">
					<label for="password" class="form-label">Senha</label>
					<input type="password" class="form-control" id="password" v-model="dataForm.password" />
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
				<button class="btn btn-success w-100" @click="entrar">Entrar</button>
			</div>
			<div class="col-6">
				<button class="btn btn-primary w-100" @click="cadastrar">Cadastrar</button>
			</div>
		</div>
		<div class="row mt-4">
			<div class="col-12 text-center">
				<div class="pointer text-primary" @click="recuperar">Recuperar Senha</div>
			</div>
		</div>
	</div>
</template>

<script setup>
	import { ref, onMounted } from 'vue';
	import { useRouter } from 'vue-router';
	import { api } from '../api';

	const router = useRouter();

	const dataForm = ref({});
	const error = ref(null);

	onMounted(() => {
		if (localStorage.token) {
			router.push('/menu');
		}
	});

	async function entrar() {
		error.value = null;
		const res = await api.post('usuarios/?action=login', dataForm.value);
		if (res.data.error) {
			error.value = res.data.error;
		} else {
			localStorage.token = res.data.data.token;
			localStorage.id = res.data.data.id;
			localStorage.nome = res.data.data.nome;
			localStorage.email = res.data.data.email;
			api.defaults.headers.Authorization = 'Bearer ' + localStorage.id + '_' + localStorage.token;
			router.push('/menu');
		}
	}

	function cadastrar() {
		router.push('/cadastrar');
	}

	function recuperar() {
		router.push('/recuperar');
	}
</script>
