<template>
	<div>
		<div class="row">
			<div class="col-10 h4">{{ estilo }}</div>
			<div class="col-2 text-end pointer" @click="voltar">
				<i class="bi bi-arrow-left h4"></i>
			</div>
		</div>
		<div class="row mt-4">
			<div class="col-12" v-if="lista">
				<div v-for="(item, i) in lista" :key="i">
					<button class="btn btn-primary w-100 mb-2" @click="openCantores(item)">{{ item.cantor }}</button>
				</div>
			</div>
			<div class="col-12" v-else>Carregando...</div>
		</div>
		<div class="row mb-3" v-if="error">
			<div class="col-12 text-danger">
				{{ error }}
			</div>
		</div>
	</div>
</template>

<script setup>
	import { ref, onMounted } from 'vue';
	import { useRouter } from 'vue-router';
	import { api } from '../api';

	const router = useRouter();
	const props = defineProps({
		estilo: String,
	});

	const lista = ref([]);
	const error = ref('');

	onMounted(async () => {
		lista.value = null;
		const res = await api.get('musicas/?action=cantores&estilo=' + props.estilo);
		if (res.data.data) {
			lista.value = res.data.data;
		} else if (res.data.error) {
			error.value = res.data.error;
		}
	});

	function voltar() {
		router.push('/estilos/');
	}

	function openCantores(item) {
		router.push('/musicas/' + props.estilo + '/' + item.cantor);
	}
</script>
