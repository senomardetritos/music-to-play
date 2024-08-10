<template>
	<div>
		<header>
			<div class="row p-3 bg-dark">
				<div class="col-6 text-light h4 title">MUSIC TO PLAY</div>
				<div class="col-6 text-light text-end small" v-if="token">
					{{ nome }}
					<div class="pointer" @click="sair">Sair</div>
				</div>
			</div>
		</header>

		<main class="p-3">
			<RouterView class="router-view" />
		</main>
	</div>
</template>

<script setup>
	import { ref, watch, onMounted } from 'vue';
	import { RouterView, useRoute, useRouter } from 'vue-router';
	import { api } from './api.js';

	const route = useRoute();
	const router = useRouter();

	const token = ref(null);
	const nome = ref('');

	watch(route, async (to) => {
		checkLogin();
	});

	function checkLogin() {
		nome.value = localStorage.nome;
		token.value = localStorage.token;
		if (!token.value && route.meta.auth !== false) {
			router.push('/');
		}
	}

	onMounted(async () => {
		if (!localStorage.musicas_marcadas) localStorage.musicas_marcadas = '{}';
		checkLogin();
	});

	function sair() {
		delete localStorage.token;
		delete localStorage.id;
		delete localStorage.nome;
		delete localStorage.email;
		router.push('/');
	}
</script>
