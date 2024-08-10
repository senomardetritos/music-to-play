<template>
	<div>
		<div class="row">
			<div class="col-12 h4">RECUPERAR</div>
		</div>
		<div class="row mt-4">
			<div class="col-12">
				<div class="mb-3">
					<label for="email" class="form-label">Email</label>
					<input type="email" class="form-control" id="email" v-model="dataForm.email" />
				</div>
			</div>
			<div class="col-12">
				<div class="mb-3">
					<label for="email" class="form-label">Email</label>
					<input type="email" class="form-control" id="email" v-model="dataForm.email" />
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
				<button class="btn btn-success w-100" @click="recuperar">Recuperar</button>
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

	const router = useRouter();

	const dataForm = ref({});
	const error = ref(null);
	const showCode = ref(false);

	onMounted(() => {});

	async function recuperar() {
		error.value = null;
		const res = await api.post('usuarios/?action=enviarRecuperar', dataForm.value);
		if (res.data.error) {
			error.value = res.data.error;
		} else {
			showCode.value = true;
		}
	}

	function voltar() {
		router.push('/');
	}
</script>
