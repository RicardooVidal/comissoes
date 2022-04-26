window._ = require('lodash');
window.$ = window.jQuery = require('jquery')

try {
    require('bootstrap');
    require('jquery-mask-plugin');
} catch (e) {}

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/** interceptar os requests da aplicação */
axios.interceptors.request.use(
    config => {
        $('#loading').css("visibility", "visible");
        let token = (() => {
            let token = document.cookie.split(';').find(indice => {
                return indice.includes('token=');
            });

            token = token.split('=')[1];
            token = 'Bearer ' + token;

            return token;
        });

        //definir para todas as requisições os parâmetros de accept e authorization
        config.headers['Accept'] = 'application/json';
        config.headers['Authorization'] = token();

        // console.log('interceptando o request antes do envio', request);
        return config;
    },
    error => {
        console.log('Erro na requisição:', error);
        return Promise.reject(error);
    },
);

/** interceptar os responses da aplicação */
axios.interceptors.response.use(
    response => {
        $('#loading').css("visibility", "hidden");
        // console.log('interceptando a resposta antes da aplicação', response);
        return response;
    },
    error => {
        console.log('Erro na resposta:', error.response);
        $('#loading').css("visibility", "hidden");
        if(error.response.status == 401 && error.response.data.message == 'Token has expired') {
            $('#alert-session').css("visibility", "visible");
            
            // Forçar logout
            axios.post('http://localhost:1102/logout')
                .then((response) => {
                    // Redirecionar para login
                    setTimeout(() => {window.location.href = "/login"}, 5000);
                })
                .catch((err) => {
                    console.log(err);
                })
        }
        return Promise.reject(error);
    }
);

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo';

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     forceTLS: true
// });
