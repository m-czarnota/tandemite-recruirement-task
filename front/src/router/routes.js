const routes = {
    home: {
        path: '/',
        redirect: {
            name: 'addClient',
        },
    },
    addClient: {
        path: '/clients/add',
        component: () => import('../views/AddClientView.vue'),
    },
    listClients: {
        path: '/clients/list',
        component: () => import('../views/ListClientsView.vue')
    },
    login: {
        path: '/login',
        component: () => import('../views/LoginView.vue')
    },
    logout: {
        path: '/logout',
        component: () => import('../views/LogoutView.vue')
    },
};

export default routes;