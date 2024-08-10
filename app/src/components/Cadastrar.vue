<template>
	<div>
		<div class="row">
			<div class="col-12 h4">CADASTRAR</div>
		</div>
		<div class="row mt-4">
			<div class="col-12">
				<div class="mb-3">
					<label for="nome" class="form-label">Nome</label>
					<input type="text" class="form-control" id="nome" v-model="dataForm.nome" />
				</div>
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
				<button class="btn btn-success w-100" @click="cadastrar">Cadastrar</button>
			</div>
			<div class="col-6">
				<button class="btn btn-primary w-100" @click="voltar">Voltar</button>
			</div>
		</div>
	</div>
</template>

<script setup>
	import { ref } from 'vue';
	import { useRouter } from 'vue-router';
	import { api } from '../api';

	const router = useRouter();

	const dataForm = ref({});
	const error = ref(null);

	async function cadastrar() {
		error.value = null;
		if (!dataForm.value.nome) {
			error.value = 'Nome é requerido';
			return;
		} else if (!dataForm.value.email) {
			error.value = 'Email é requerido';
			return;
		} else if (!dataForm.value.password) {
			error.value = 'Senha é requerida';
			return;
		}
		const res = await api.post('usuarios/?action=cadastrar', dataForm.value);
		if (res.data.error) {
			error.value = res.data.error;
		} else {
			alert('Cadastrado com sucesso');
			router.push('/');
		}
	}

	function voltar() {
		router.push('/');
	}
</script>
