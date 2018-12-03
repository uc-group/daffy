import Api from './base';

export default {
    list() {
        return Api.get('os_list');
    },
    create(name, version, packageManager, description, images) {
        return Api.post('os_create', {}, {
            name,
            version,
            packageManager,
            description,
            images
        });
    },
    update(id, packageManager, description, images) {
        return Api.post('os_update', {id}, {packageManager, description, images});
    },
    delete(ids) {
        return Api.post('os_delete', {}, {id: ids});
    }
}
