import PedidosIndex from "./components/Pedido";
import PedidosCreate from "./components/pedidos/Create";
import PedidosEdit from "./components/pedidos/Edit";

export const routes = [
    {
        path: "/pedidos",
        name: "PedidosIndex",
        component: PedidosIndex
    },
    {
        path: "/pedidos/create",
        name: "PedidosCreate",
        component: PedidosCreate
    },
    {
        path: "/pedidos/:id/edit",
        name: "PedidosEdit",
        component: PedidosEdit
    },
];