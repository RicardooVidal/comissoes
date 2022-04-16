/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import masks from'./masks';

$(document).ready(function($){
    $('.date').mask('00/00/0000');
    $('.time').mask('00:00:00');
    $('.date_time').mask('00/00/0000 00:00:00');
    $('.cep').mask('00000-000');
    $('.phone').mask('0000-0000');
    $('.phone_with_ddd').mask('(00) 00000-0000');
    $('.phone_us').mask('(000) 000-0000');
    $('.mixed').mask('AAA 000-S0S');
    $('.cpf').mask('000.000.000-00', {reverse: true});
    $('.cnpj').mask('00.000.000/0000-00', {reverse: true});
    $('.money').mask('000.000.000.000.000,00', {reverse: true});
    $('.money2').mask("#.##0,00", {reverse: true});
    $('.ip_address').mask('0ZZ.0ZZ.0ZZ.0ZZ', {
      translation: {
        'Z': {
          pattern: /[0-9]/, optional: true
        }
      }
    });
    $('.ip_address').mask('099.099.099.099');
    $('.percent').mask('##0,00%', {reverse: true});
    $('.clear-if-not-match').mask("00/00/0000", {clearIfNotMatch: true});
    $('.placeholder').mask("00/00/0000", {placeholder: "__/__/____"});
    $('.fallback').mask("00r00r0000", {
        translation: {
          'r': {
            pattern: /[\/]/,
            fallback: '/'
          },
          placeholder: "__/__/____"
        }
      });
    $('.selectonfocus').mask("00/00/0000", {selectOnFocus: true});
    $('.agencia').mask("0000");
    $('.digito').mask("0");
    $('.conta_banco').mask("00000000000");
    $('.rg').mask('00.000.000-A');
  });

window.Vue = require('vue').default;

import Vue from 'vue';

/* importando e configurando o vuex */
import Vuex from 'Vuex'

Vue.use(Vuex);

Vue.prototype.$store = new Vuex.Store({
    state: {
        item: {},
        select: { id: '', nome: ''},
        transaction: {status: '', message: '', data: ''}
    }
})

Vue.prototype.$setStore = (obj) => {
    Vue.prototype.$store.state.transaction.status = '';
    Vue.prototype.$store.state.transaction.message = '';
    Vue.prototype.$store.state.transaction.data = '';
    Vue.prototype.$store.state.item = obj;
}

Vue.prototype.$urlBase = 'http://localhost:8000/api';
Vue.prototype.$getToken = () => {
    let token = document.cookie.split(';').find(indice => {
        return indice.includes('token=');
    });

    token = token.split('=')[1];
    token = 'Bearer ' + token;

    return token;
}

Vue.prototype.$verifyBoolean = (bool) => {
    return bool ? 'SIM' : 'NÃO';
}

Vue.prototype.$verifyBooleanString = (bool) => {
    return bool === "true" ? 1 : 0;
}

Vue.prototype.$convertToDecimal = (value) => {
    let parsed = (parseFloat(value) / 100).toFixed(2)

    if (isNaN(parsed)) {
      return 0;
    }

    return parsed;
}

Vue.prototype.$toPercentage = (value) => {
  return parseInt(value * 100);
}

// Tratamento para corrigir bug quando se utiliza mask e store
Vue.prototype.$getInputValueWithMask = (objId, maskName) => {
  let maskInput = masks[maskName];
  $(objId).mask(maskInput[0], {reverse: [maskInput[1]]});
  return $(objId).val();
}

Vue.prototype.$showLoading = () => {
    $('#loading').css("visibility", "visible");
}

Vue.prototype.$hideLoading = () => {
    $('#loading').css("visibility", "hidden");
}

Vue.prototype.$closeModal = (modal) => {
    $(modal).modal('hide');
}

Vue.prototype.$getFilter = (field, filter) => {
  if (
    field == 'id' 
    || field == 'ativo'
    || field == 'venda_id'
  ) {
    return `${filter}`
  }

  return `${filter}%`
}

Vue.prototype.$emptySelect = () => {
  Vue.prototype.$store.state.select = {
    id: '',
    nome: ''
  }
}

Vue.prototype.$emptyUrlFilter = () => {
  Vue.prototype.$store.state.select = {
    updateUrlFilter: '',
  }
}

Vue.prototype.$emptyObject = (obj) => {
  return Object.keys(obj).length === 0;
}


Vue.prototype.$activateBancoFields = () => {
  $('#inputAgencia').attr('disabled', false);
  $('#inputConta').attr('disabled', false);
  $('#inputDigito').attr('disabled', false);
  $('#selectTipoConta').attr('disabled', false);
  $('#inputDigitoAgencia').attr('disabled', false);
}

Vue.prototype.$deactivateBancoFields = (operation) => {
  $('#inputAgencia' + operation).val('').attr('disabled', true);
  $('#inputConta' + operation).val('').attr('disabled', true);
  $('#inputDigito' + operation).val('').attr('disabled', true);
  $('#selectTipoConta' + operation).val('').attr('disabled', true);
  $('#inputDigitoAgencia' + operation).val('').attr('disabled', true);
}

Vue.prototype.$verifyValidadeIndicacao = (data_indicacao, validade_indicacao) => {
  let today = new Date().toDateString();
  console.log(today);
  today = Vue.prototype.$formatDateToUS(today);
  if (today > validade_indicacao) {
    return 'EXPIRADO';
  } 

  return 'ATIVO';
}

Vue.prototype.$formatDate = (dateToFormat) => {
    if (!dateToFormat) {
      return '  /  /    '
    }
    let date = new Date(dateToFormat.replace(/-/g, '\/').replace(/T.+/, ''));
    let day  = date.getDate().toString().padStart(2, '0');
    let month  = (date.getMonth()+1).toString().padStart(2, '0');
    let year  = date.getFullYear();
    return `${day}/${month}/${year}`;
}

Vue.prototype.$formatDateToUS = (dateToFormat) => {
  if (!dateToFormat) {
    return '  /  /    '
  }
  let date = new Date(dateToFormat.replace(/-/g, '\/').replace(/T.+/, ''));
  let day  = date.getDate().toString().padStart(2, '0');
  let month  = (date.getMonth()+1).toString().padStart(2, '0');
  let year  = date.getFullYear();
  return `${year}-${month}-${day}`;
}

Vue.prototype.$errorTreatment = (errors) => {
  let message = errors.response.data.message ? errors.response.data.message : errors.response.data.result;
  let data = errors.response.data.errors ? errors.response.data.errors : '';
  Vue.prototype.$store.state.transaction.status = 'error';
  Vue.prototype.$store.state.transaction.message =  message;
  Vue.prototype.$store.state.transaction.data =  data;
}

Vue.filter('removeNullProps',function(object) {
  // sorry for using lodash and ES2015 arrow functions :-P
  return _.reject(object, (value) => value === null)
})

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('login-component', require('./components/LoginComponent.vue').default);

// Loading
Vue.component('loading', require('./components/Loading.vue').default);

// Alert
Vue.component('alert-component', require('./components/AlertComponent.vue').default);

// Forms
Vue.component('table-component', require('./components/TableComponent.vue').default);
Vue.component('card-component', require('./components/CardComponent.vue').default);
Vue.component('paginate-component', require('./components/PaginateComponent.vue').default);
Vue.component('modal-component', require('./components/ModalComponent.vue').default);
Vue.component('input-container-component', require('./components/InputContainerComponent.vue').default);

// Parâmetros
Vue.component('taxa-parametro-component', require('./components/parametros/TaxaComponent.vue').default);
Vue.component('comissao-parametro-component', require('./components/parametros/ComissaoComponent.vue').default);

// Outros
Vue.component('configuracao-component', require('./components/outros/ConfiguracaoComponent.vue').default);
Vue.component('banco-component', require('./components/outros/BancoComponent.vue').default);
Vue.component('forma-pagamento-component', require('./components/outros/FormaPagamentoComponent.vue').default);

// Revendedores
Vue.component('revendedor-component', require('./components/revendedor/RevendedorComponent.vue').default);
Vue.component('revendedor-create-component', require('./components/revendedor/InsertRevendedorComponent.vue').default);
Vue.component('select-revendedor-component', require('./components/revendedor/SelectRevendedorComponent.vue').default);
Vue.component('view-indicados-component', require('./components/revendedor/ViewIndicadosComponent.vue').default);
Vue.component('view-comissoes-revendedor-component', require('./components/revendedor/ViewComissoesRevendedorComponent.vue').default);

// Vendas
Vue.component('venda-component', require('./components/venda/VendaComponent.vue').default);
Vue.component('venda-create-component', require('./components/venda/InsertVendaComponent.vue').default);
Vue.component('view-comissoes-venda-component', require('./components/venda/ViewComissoesVendaComponent.vue').default);

// Comissões
Vue.component('comissao-component', require('./components/comissao/ComissaoComponent.vue').default);
Vue.component('baixar-comissao-component', require('./components/comissao/BaixarComissaoComponent.vue').default);

Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});