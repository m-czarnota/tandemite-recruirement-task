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
    }
};

export default routes;