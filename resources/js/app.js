require("./bootstrap");

import Vue from "vue";
import table from "../components/table";
import popup from "../components/pop";
const app = new Vue({
    el: "#app",
    components: {
        "word-table": table,
        "pop-up": popup
    },
});
