<template>
	<div>
		<div class="row">
			<div class="col-12 h4">ALTERAR SENHA</div>
		</div>
		<div class="row mt-4">
			<div class="col-12">
				<div class="mb-3">
					<label for="password" class="form-label">Senha Atual</label>
					<input type="password" class="form-control" id="password" v-model="dataForm.password" />
				</div>
				<div class="mb-3">
					<label for="newpassword" class="form-label">Nova Senha</label>
					<input type="password" class="form-control" id="newpassword" v-model="dataForm.newpassword" />
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
				<button class="btn btn-success w-100" @click="cadastrar">Alterar</button>
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
		if (!dataForm.value.password) {
			error.value = 'Senha é requerida';
			return;
		} else if (!dataForm.value.newpassword) {
			error.value = 'Nova Senha é requerida';
			return;
		}
		const res = await api.post('usuarios/?action=alterarSenha', dataForm.value);
		if (res.data.error) {
			error.value = res.data.error;
		} else {
			alert('Senha Alterada com sucesso');
			router.push('/');
		}
	}

	function voltar() {
		router.push('/menu');
	}
</script>
