import Vue from "vue";
import VueRouter from "vue-router";
import Home from "../App";

Vue.use(VueRouter);

export default new VueRouter({
    mode: "history",
    routes: [
        { path: "/vue", component: Vue },
        { path: "*", redirect: "/vue" }
    ]
});