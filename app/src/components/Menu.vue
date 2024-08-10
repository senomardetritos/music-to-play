<template>
	<div>
		<div class="row">
			<div class="col-12 h4">MENU</div>
		</div>
		<div class="row mt-4">
			<div class="col-12">
				<button class="btn btn-primary w-100 mb-2" @click="estilos">Estilos</button>
				<button class="btn btn-primary w-100 mb-2" @click="cadastrar_musica">Cadastrar Música</button>
				<button class="btn btn-primary w-100 mb-2" @click="alterar_senha">Alterar Senha</button>
				<button class="btn btn-primary w-100 mb-2" @click="desmarcar_musicas" v-if="musicas">Desmarcar Todas Músicas</button>
				<button class="btn btn-danger w-100 mb-2" @click="sair">Sair</button>
			</div>
		</div>
	</div>
</template>

<script setup>
	import { ref, onMounted } from 'vue';
	import { useRouter } from 'vue-router';

	const router = useRouter();

	const musicas = ref(null);

	onMounted(() => {
		musicas.value = JSON.parse(localStorage.musicas_marcadas);
		if (Object.keys(musicas.value).length == 0) musicas.value = null;
	});

	function desmarcar_musicas() {
		localStorage.musicas_marcadas = '{}';
		musicas.value = null;
	}

	function estilos() {
		router.push('/estilos');
	}

	function cadastrar_musica() {
		router.push('/cadastrar-musica');
	}

	function alterar_senha() {
		router.push('/alterar-senha');
	}

	function sair() {
		delete localStorage.token;
		delete localStorage.id;
		delete localStorage.nome;
		delete localStorage.email;
		router.push('/');
	}
</script>
