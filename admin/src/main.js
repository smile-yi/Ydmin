import Vue from 'vue'
import App from './App.vue'

import router from './router'

Vue.config.productionTip = false

//引入ElementUI
import Element from 'element-ui'
// import "../public/style/theme/index.css";
import './element-variables.scss'
Vue.use(Element)

new Vue({
    router,
    render: h => h(App),
}).$mount('#app')
