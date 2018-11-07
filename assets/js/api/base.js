import axios from 'axios';

export const Routing = window.Routing;

function handleResponse(resolve, reject, response)
{
    if (response.data.hasOwnProperty('status')) {
        if (('' + response.data.status)[0] === '2') {
            resolve(response.data);
        } else {
            reject(response.data)
        }
    } else {
        resolve(response.data);
    }
}

export default {
    get(route, params) {
        return new Promise((resolve, reject) => {
            axios.get(Routing.generate(route, params))
                .then((response) => {
                    handleResponse(resolve, reject, response);
                })
                .catch((error) => {
                    reject(error);
                })
        });
    },
    post(route, params, data) {
        return new Promise((resolve, reject) => {
            axios.post(Routing.generate(route, params), data)
                .then((response) => {
                    if (response.data.hasOwnProperty('status')) {
                        handleResponse(resolve, reject, response);
                    }

                })
                .catch((error) => {
                    reject(error);
                })
        });
    }
}
