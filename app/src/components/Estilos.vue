<template>
	<div>
		<div class="row">
			<div class="col-10 h4">ESTILOS</div>
			<div class="col-2 text-end pointer" @click="voltar">
				<i class="bi bi-arrow-left h4"></i>
			</div>
		</div>
		<div class="row mt-4">
			<div class="col-12" v-if="lista">
				<div v-for="(item, i) in lista" :key="i">
					<button class="btn btn-primary w-100 mb-2" @click="openEstilos(item)">{{ item.estilo }}</button>
				</div>
			</div>
			<div class="col-12" v-else>Carregando...</div>
		</div>
	</div>
</template>

<script setup>
	import { ref, onMounted } from 'vue';
	import { useRouter } from 'vue-router';
	import { api } from '../api';

	const router = useRouter();

	const lista = ref([]);

	onMounted(async () => {
		lista.value = null;
		const res = await api.get('musicas/?action=estilos');
		lista.value = res.data.data;
		console.log(res.data);
	});

	function voltar() {
		router.push('/menu/');
	}

	function openEstilos(item) {
		router.push('/cantores/' + item.estilo);
	}
</script>
