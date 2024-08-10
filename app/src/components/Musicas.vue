<template>
	<div>
		<div class="row">
			<div class="col-10 h4">{{ cantor }}</div>
			<div class="col-2 text-end pointer" @click="voltar">
				<i class="bi bi-arrow-left h4"></i>
			</div>
		</div>
		<div class="row mt-4">
			<div class="col-12" v-if="lista">
				<div class="row" v-for="(item, i) in lista" :key="i">
					<div class="col-11" v-if="musicas_marcadas[item.musica]">
						<button class="btn btn-light w-100 mb-2">{{ item.musica }}</button>
					</div>
					<div class="col-11" v-else>
						<button class="btn btn-primary w-100 mb-2" @click="marcar_musica(item)">{{ item.musica }}</button>
					</div>
					<div class="col-1 p-0">
						<div class="dropdown btn-sm">
							<button class="btn btn-light" type="button" data-bs-toggle="dropdown" aria-expanded="false">
								<i class="bi bi-three-dots-vertical h6"></i>
							</button>
							<ul class="dropdown-menu">
								<li v-if="musicas_marcadas[item.musica]">
									<a class="dropdown-item pointer" @click="desmarcar_musica(item)">Desmarcar</a>
								</li>
								<li v-else>
									<a class="dropdown-item pointer" @click="marcar_musica(item)">Marcar</a>
								</li>
								<li><a class="dropdown-item pointer" @click="editar(item)">Editar</a></li>
								<li><a class="dropdown-item pointer" @click="excluir(item)">Excluir</a></li>
							</ul>
						</div>
					</div>
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
	const props = defineProps({
		estilo: String,
		cantor: String,
	});

	const lista = ref([]);
	const musicas_marcadas = ref({});

	onMounted(async () => {
		lista.value = null;
		const res = await api.get('musicas/?action=musicas&cantor=' + props.cantor);
		lista.value = res.data.data;
		musicas_marcadas.value = JSON.parse(localStorage.musicas_marcadas);
	});

	function voltar() {
		router.push('/cantores/' + props.estilo);
	}

	function marcar_musica(item) {
		const musicas = JSON.parse(localStorage.musicas_marcadas);
		if (!musicas[item.musica]) musicas[item.musica] = true;
		localStorage.musicas_marcadas = JSON.stringify(musicas);
		musicas_marcadas.value = musicas;
	}

	function desmarcar_musica(item) {
		const musicas = JSON.parse(localStorage.musicas_marcadas);
		if (musicas[item.musica]) delete musicas[item.musica];
		localStorage.musicas_marcadas = JSON.stringify(musicas);
		musicas_marcadas.value = musicas;
	}

	function editar(item) {
		router.push('/editar-musica/' + item.id);
	}

	function excluir(item) {
		if (confirm('Deseja excluir ' + item.musica + '?')) {
			console.log(item);
		}
	}
</script>
