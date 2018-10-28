import Api from './base';

export default {
    list() {
        return Api.get('os_list');
    },
    create(name, version, description, images) {
        return Api.post('os_create', {}, {
            name,
            version,
            description,
            images
        });
    }
}
