const routes = {
    home: {
        path: '/',
        redirect: {
            name: 'addClient',
        },
    },
    addClient: {
        path: '/add-client',
        component: () => import('../views/AddClientView.vue'),
    },
};

export default routes;