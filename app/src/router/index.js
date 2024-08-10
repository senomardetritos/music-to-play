import { createRouter, createWebHashHistory } from 'vue-router';

import Login from '../components/Login.vue';
import Recuperar from '../components/Recuperar.vue';
import Cadastrar from '../components/Cadastrar.vue';
import AlterarSenha from '../components/AlterarSenha.vue';
import Menu from '../components/Menu.vue';
import CadastrarMusica from '../components/CadastrarMusica.vue';
import Estilos from '../components/Estilos.vue';
import Cantores from '../components/Cantores.vue';
import Musicas from '../components/Musicas.vue';

const router = createRouter({
	history: createWebHashHistory(),
	routes: [
		{ path: '/', name: 'Login', component: Login, meta: { auth: false } },
		{ path: '/recuperar', name: 'Recuperar', component: Recuperar, meta: { auth: false } },
		{ path: '/cadastrar', name: 'Cadastrar', component: Cadastrar, meta: { auth: false } },
		{ path: '/alterar-senha', name: 'AlterarSenha', component: AlterarSenha, meta: { auth: true } },
		{ path: '/menu', name: 'Menu', component: Menu, meta: { auth: true } },
		{ path: '/cadastrar-musica', name: 'CadastrarMusica', component: CadastrarMusica, meta: { auth: true } },
		{ path: '/editar-musica/:id', name: 'EditarMusica', component: CadastrarMusica, props: true, meta: { auth: true } },
		{ path: '/estilos', name: 'Estilos', component: Estilos, meta: { auth: true } },
		{ path: '/cantores/:estilo', name: 'Cantores', component: Cantores, props: true, meta: { auth: true } },
		{ path: '/musicas/:estilo/:cantor', name: 'Musicas', component: Musicas, props: true, meta: { auth: true } },
	],
});

export { router };
